/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Jean Le Feuvre 2000-2005
 *					All rights reserved
 *
 *  This file is part of GPAC / Scene Graph sub-project
 *
 *  GPAC is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation; either version 2, or (at your option)
 *  any later version.
 *   
 *  GPAC is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *   
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; see the file COPYING.  If not, write to
 *  the Free Software Foundation, 675 Mass Ave, Cambridge, MA 02139, USA. 
 *
 */

#include <gpac/internal/scenegraph_dev.h>

/*MPEG4 & X3D tags (for node tables & script handling)*/
#include <gpac/nodes_mpeg4.h>
#include <gpac/nodes_x3d.h>


static Bool gf_node_in_table_by_tag(u32 tag, u32 NDTType)
{
	if (!tag) return 0;
	if (tag==TAG_ProtoNode) return 1;
	else if (tag<=GF_NODE_RANGE_LAST_MPEG4) {
		u32 i;
		u32 gf_bifs_get_node_type(u32 NDT_Tag, u32 NodeTag, u32 Version);

		for (i=0;i<GF_BIFS_LAST_VERSION; i++) {
			if (gf_bifs_get_node_type(NDTType, tag, i+1)) return 1;
		}
		return 0;
	} else if (tag<=GF_NODE_RANGE_LAST_X3D) {
		Bool X3D_IsNodeInTable(u32 NDT_Tag, u32 NodeTag);
		return X3D_IsNodeInTable(NDTType, tag);
	}
	return 0;
}

Bool gf_node_in_table(GF_Node *node, u32 NDTType)
{
	u32 tag = node ? node->sgprivate->tag : 0;
	if (tag==TAG_ProtoNode) {
		tag = gf_sg_proto_get_render_tag(((GF_ProtoInstance *)node)->proto_interface);
		if (tag==TAG_UndefinedNode) return 1;
	}
	return gf_node_in_table_by_tag(tag, NDTType);
}

/*this is not a NodeReplace, thus only the given container is updated - pos is 0-based*/
GF_Err gf_node_replace_child(GF_Node *node, GF_List *container, s32 pos, GF_Node *newNode)
{
	u32 count;
	GF_Node *n;
	
	count = gf_list_count(container);
	if (!count) return GF_OK;
	/*last item*/
	if ( (pos == -1) || ((u32) pos >= count) ) pos = count - 1;
	n = gf_list_get(container, (u32) pos);

	/*delete node*/

	if (n) gf_node_unregister(n, node);

	/*delete entry*/
	gf_list_rem(container, (u32) pos);

	if (newNode) gf_list_insert(container, newNode, pos);
	return GF_OK;
}

void gf_sg_destroy_routes(GF_SceneGraph *sg)
{
	while (gf_list_count(sg->routes_to_destroy) ) {
		GF_Route *r = gf_list_get(sg->routes_to_destroy, 0);
		gf_list_rem(sg->routes_to_destroy, 0);
		gf_sg_route_unqueue(sg, r);
		if (r->name) free(r->name);
		free(r);
	}
}


void gf_sg_route_queue(GF_SceneGraph *sg, GF_Route *r)
{
	u32 now;
	if (!sg) return;

	/*get the top level scene (that's the only reliable one regarding simulatioin tick)*/
	while (sg->parent_scene) sg = sg->parent_scene;
	/*a single route may not be activated more than once in a simulation tick*/
	now = 1 + sg->simulation_tick;
	if (r->lastActivateTime >= now) return;
	r->lastActivateTime = now;
	gf_list_add(sg->routes_to_activate, r);
}

/*activate all routes in the order they where triggered*/
void gf_sg_activate_routes(GF_SceneGraph *sg)
{
	GF_Route *r;
	GF_Node *targ;
	if (!sg) return;

	sg->simulation_tick++;

	while (gf_list_count(sg->routes_to_activate)) {
		r = gf_list_get(sg->routes_to_activate, 0);
		gf_list_rem(sg->routes_to_activate, 0);
		if (r) {
			targ = r->ToNode;
			if (gf_sg_route_activate(r))
				if (r->is_setup) gf_node_changed(targ, &r->ToField);
		}
	}
	gf_sg_destroy_routes(sg);
}

void gf_sg_route_unqueue(GF_SceneGraph *sg, GF_Route *r)
{
	/*get the top level scene*/
	while (sg->parent_scene) sg = sg->parent_scene;
	/*remove route from queue list*/
	gf_list_del_item(sg->routes_to_activate, r);
}

static void Node_on_add_children(GF_Node *node)
{
	GF_Node *child;
	s32 i;
	GF_FieldInfo field;
	GF_VRMLParent *n = (GF_VRMLParent *)node;

	
	gf_node_get_field(node, 2, &field);

	/*for each node in input*/
	while (gf_list_count(n->addChildren)) {
		child = gf_list_get(n->addChildren, 0);
		/*nothing in VRML stops from adding twice the same node but we don't allow that*/
		i = gf_list_find(n->children, child);
		if (i<0) {
			gf_node_register(child, node);
			gf_list_add(n->children, child);
		}
		gf_list_rem(n->addChildren, 0);
		gf_node_unregister(child, node);
	}
	/*signal children field is modified*/
	gf_node_changed(node, &field);
}

static void Node_on_remove_children(GF_Node *node)
{
	GF_Node *child;
	GF_FieldInfo field;
	s32 i;
	GF_VRMLParent *n = (GF_VRMLParent *)node;
	gf_node_get_field(node, 2, &field);

	/*for each node in input*/
	while (gf_list_count(n->removeChildren)) {
		child = gf_list_get(n->removeChildren, 0);
		/*remove from children*/
		i = gf_list_find(n->children, child);
		if (i>=0) {
			gf_list_rem(n->children, i);
			gf_node_unregister(child, node);
		}

		gf_list_rem(n->removeChildren, 0);
		gf_node_unregister(child, node);
	}
	/*signal children field is modified*/
	gf_node_changed(node, &field);
}

void gf_sg_vrml_parent_setup(GF_Node *pNode)
{
	GF_VRMLParent *par = (GF_VRMLParent *)pNode;
	par->children = gf_list_new();
	par->addChildren = gf_list_new();
	par->on_addChildren = Node_on_add_children;
	par->removeChildren = gf_list_new();
	par->on_removeChildren = Node_on_remove_children;
	pNode->sgprivate->is_dirty |= GF_SG_CHILD_DIRTY;
}

void gf_sg_vrml_parent_reset(GF_Node *pNode)
{
	GF_VRMLParent *par = (GF_VRMLParent *)pNode;
	gf_node_list_del(par->children, pNode);
	gf_node_list_del(par->addChildren, pNode);
	gf_node_list_del(par->removeChildren, pNode);
}

GF_Err gf_sg_delete_all_protos(GF_SceneGraph *scene)
{
	if (!scene) return GF_BAD_PARAM;
	while (gf_list_count(scene->protos)) {
		GF_Proto *p = gf_list_get(scene->protos, 0);
		gf_sg_proto_del(p);
	}
	return GF_OK;
}

void gf_sg_set_proto_loader(GF_SceneGraph *scene, GF_SceneGraph *(*GetExternProtoLib)(void *SceneCallback, MFURL *lib_url))
{
	if (!scene) return;
	scene->GetExternProtoLib = GetExternProtoLib;
}


u32 gf_sg_get_next_available_route_id(GF_SceneGraph *sg) 
{
	u32 i, count;
	u32 ID = 0;

	if (!sg->max_defined_route_id) {
		count = gf_list_count(sg->Routes);
		/*routes are not sorted*/
		for (i=0; i<count; i++) {
			GF_Route *r = gf_list_get(sg->Routes, i);
			if (ID<=r->ID) ID = r->ID;
		}
		return ID+1;
	} else {
		sg->max_defined_route_id++;
		return sg->max_defined_route_id;
	}
}

void gf_sg_set_max_defined_route_id(GF_SceneGraph *sg, u32 ID)
{
	sg->max_defined_route_id = ID;
}

u32 gf_sg_get_next_available_proto_id(GF_SceneGraph *sg) 
{
	u32 i, count;
	u32 ID = 0;
	count = gf_list_count(sg->protos);
	/*protos are not sorted*/
	for (i=0; i<count; i++) {
		GF_Proto *p = gf_list_get(sg->protos, i);
		if (ID<=p->ID) ID = p->ID;
	}
	count = gf_list_count(sg->unregistered_protos);
	for (i=0; i<count; i++) {
		GF_Proto *p = gf_list_get(sg->unregistered_protos, i);
		if (ID<=p->ID) ID = p->ID;
	}
	return ID+1;
}

//adds a child in the children list
GF_Err gf_node_insert_child(GF_Node *parent, GF_Node *new_child, s32 Position)
{
	GF_ParentNode *node = (GF_ParentNode *) parent;
	if (Position == -1) {
		return gf_list_add(node->children, new_child);
	} else {
		return gf_list_insert(node->children, new_child, Position);
	}
}

/*for V4Studio...*/
GF_Err gf_node_remove_child(GF_Node *parent, GF_Node *toremove_child) 
{
	s32 ind;
	GF_ParentNode *node = (GF_ParentNode *) parent;

	ind = gf_list_del_item(node->children, toremove_child);
	if (ind<0) return GF_BAD_PARAM;
	/*V4Studio doesn't handle DEF/USE properly yet...*/
	/*gf_node_unregister(toremove_child, parent);*/
	return GF_OK;
}

void gf_sg_script_load(GF_Node *n)
{
	if (n && n->sgprivate->scenegraph->gf_sg_script_load) n->sgprivate->scenegraph->gf_sg_script_load(n);
}

GF_Proto *gf_sg_find_proto(GF_SceneGraph *sg, u32 ProtoID, char *name)
{
	GF_Proto *proto;
	u32 i;

	assert(sg);

	/*browse all top-level */
	for (i=0; i<gf_list_count(sg->protos); i++) {
		proto = gf_list_get(sg->protos, i);
		/*first check on name if given, since parsers use this with ID=0*/
		if (name) {
			if (proto->Name && !stricmp(name, proto->Name)) return proto;
		} else if (proto->ID == ProtoID) return proto;
	}
	/*browse all top-level unregistered in reverse order*/
	for (i=gf_list_count(sg->unregistered_protos); i>0; i--) {
		proto = gf_list_get(sg->unregistered_protos, i-1);
		if (name) {
			if (proto->Name && !stricmp(name, proto->Name)) return proto;
		} else if (proto->ID == ProtoID) return proto;
	}
	return NULL;
}



u32 gf_bifs_get_child_table(GF_Node *Node)
{
	assert(Node);
	return gf_sg_mpeg4_node_get_child_ndt(Node);
}


GF_Err gf_bifs_get_field_index(GF_Node *Node, u32 inField, u8 IndexMode, u32 *allField)
{
	assert(Node);
	switch (Node->sgprivate->tag) {
	case TAG_ProtoNode:
		return gf_sg_proto_get_field_ind_static(Node, inField, IndexMode, allField);
	case TAG_MPEG4_Script: 
	case TAG_X3D_Script: 
		return gf_sg_script_get_field_index(Node, inField, IndexMode, allField);
	default: 
		return gf_sg_mpeg4_node_get_field_index(Node, inField, IndexMode, allField);
	}
}


/* QUANTIZATION AND BIFS_Anim Info */
Bool gf_bifs_get_aq_info(GF_Node *Node, u32 FieldIndex, u8 *QType, u8 *AType, Fixed *b_min, Fixed *b_max, u32 *QT13_bits)
{
	switch (Node->sgprivate->tag) {
	case TAG_ProtoNode: return gf_sg_proto_get_aq_info(Node, FieldIndex, QType, AType, b_min, b_max, QT13_bits);
	default: return gf_sg_mpeg4_node_get_aq_info(Node, FieldIndex, QType, AType, b_min, b_max, QT13_bits);
	}
}

static SFBool *NewSFBool()
{
	SFBool *tmp = malloc(sizeof(SFBool));
	memset(tmp, 0, sizeof(SFBool));
	return tmp;
}
static SFFloat *NewSFFloat()
{
	SFFloat *tmp = malloc(sizeof(SFFloat));
	memset(tmp, 0, sizeof(SFFloat));
	return tmp;
}
static SFDouble *NewSFDouble()
{
	SFDouble *tmp = malloc(sizeof(SFDouble));
	memset(tmp, 0, sizeof(SFDouble));
	return tmp;
}
static SFTime *NewSFTime()
{
	SFTime *tmp = malloc(sizeof(SFTime));
	memset(tmp, 0, sizeof(SFTime));
	return tmp;
}
static SFInt32 *NewSFInt32()
{
	SFInt32 *tmp = malloc(sizeof(SFInt32));
	memset(tmp, 0, sizeof(SFInt32));
	return tmp;
}
static SFString *NewSFString()
{
	SFString *tmp = malloc(sizeof(SFString));
	memset(tmp, 0, sizeof(SFString));
	return tmp;
}
static SFVec3f *NewSFVec3f()
{
	SFVec3f *tmp = malloc(sizeof(SFVec3f));
	memset(tmp, 0, sizeof(SFVec3f));
	return tmp;
}
static SFVec3d *NewSFVec3d()
{
	SFVec3d *tmp = malloc(sizeof(SFVec3d));
	memset(tmp, 0, sizeof(SFVec3d));
	return tmp;
}
static SFVec2f *NewSFVec2f()
{
	SFVec2f *tmp = malloc(sizeof(SFVec2f));
	memset(tmp, 0, sizeof(SFVec2f));
	return tmp;
}
static SFVec2d *NewSFVec2d()
{
	SFVec2d *tmp = malloc(sizeof(SFVec2d));
	memset(tmp, 0, sizeof(SFVec2d));
	return tmp;
}
static SFColor *NewSFColor()
{
	SFColor *tmp = malloc(sizeof(SFColor));
	memset(tmp, 0, sizeof(SFColor));
	return tmp;
}
static SFColorRGBA *NewSFColorRGBA()
{
	SFColorRGBA *tmp = malloc(sizeof(SFColorRGBA));
	memset(tmp, 0, sizeof(SFColorRGBA));
	return tmp;
}
static SFRotation *NewSFRotation()
{
	SFRotation *tmp = malloc(sizeof(SFRotation));
	memset(tmp, 0, sizeof(SFRotation));
	return tmp;
}
static SFImage *NewSFImage()
{
	SFImage *tmp = malloc(sizeof(SFImage));
	memset(tmp, 0, sizeof(SFImage));
	return tmp;
}
static SFURL *NewSFURL()
{
	SFURL *tmp = malloc(sizeof(SFURL));
	memset(tmp, 0, sizeof(SFURL));
	return tmp;
}
static SFCommandBuffer *NewSFCommandBuffer()
{
	SFCommandBuffer *tmp = malloc(sizeof(SFCommandBuffer));
	memset(tmp, 0, sizeof(SFCommandBuffer));
	tmp->commandList = gf_list_new();
	return tmp;
}
static MFBool *NewMFBool()
{
	MFBool *tmp = malloc(sizeof(MFBool));
	memset(tmp, 0, sizeof(MFBool));
	return tmp;
}
static MFFloat *NewMFFloat()
{
	MFFloat *tmp = malloc(sizeof(MFFloat));
	memset(tmp, 0, sizeof(MFFloat));
	return tmp;
}
static MFTime *NewMFTime()
{
	MFTime *tmp = malloc(sizeof(MFTime));
	memset(tmp, 0, sizeof(MFTime));
	return tmp;
}
static MFInt32 *NewMFInt32()
{
	MFInt32 *tmp = malloc(sizeof(MFInt32));
	memset(tmp, 0, sizeof(MFInt32));
	return tmp;
}
static MFString *NewMFString()
{
	MFString *tmp = malloc(sizeof(MFString));
	memset(tmp, 0, sizeof(MFString));
	return tmp;
}
static MFVec3f *NewMFVec3f()
{
	MFVec3f *tmp = malloc(sizeof(MFVec3f));
	memset(tmp, 0, sizeof(MFVec3f));
	return tmp;
}
static MFVec3d *NewMFVec3d()
{
	MFVec3d *tmp = malloc(sizeof(MFVec3d));
	memset(tmp, 0, sizeof(MFVec3d));
	return tmp;
}
static MFVec2f *NewMFVec2f()
{
	MFVec2f *tmp = malloc(sizeof(MFVec2f));
	memset(tmp, 0, sizeof(MFVec2f));
	return tmp;
}
static MFVec2d *NewMFVec2d()
{
	MFVec2d *tmp = malloc(sizeof(MFVec2d));
	memset(tmp, 0, sizeof(MFVec2d));
	return tmp;
}
static MFColor *NewMFColor()
{
	MFColor *tmp = malloc(sizeof(MFColor));
	memset(tmp, 0, sizeof(MFColor));
	return tmp;
}
static MFColorRGBA *NewMFColorRGBA()
{
	MFColorRGBA *tmp = malloc(sizeof(MFColorRGBA));
	memset(tmp, 0, sizeof(MFColorRGBA));
	return tmp;
}
static MFRotation *NewMFRotation()
{
	MFRotation *tmp = malloc(sizeof(MFRotation));
	memset(tmp, 0, sizeof(MFRotation));
	return tmp;
}
static MFURL *NewMFURL()
{
	MFURL *tmp = malloc(sizeof(MFURL));
	memset(tmp, 0, sizeof(MFURL));
	return tmp;
}

void *gf_sg_vrml_field_pointer_new(u32 FieldType) 
{
	switch (FieldType) {
	case GF_SG_VRML_SFBOOL: return NewSFBool();
	case GF_SG_VRML_SFFLOAT: return NewSFFloat();
	case GF_SG_VRML_SFDOUBLE: return NewSFDouble();
	case GF_SG_VRML_SFTIME: return NewSFTime();
	case GF_SG_VRML_SFINT32: return NewSFInt32();
	case GF_SG_VRML_SFSTRING: return NewSFString();
	case GF_SG_VRML_SFVEC3F: return NewSFVec3f();
	case GF_SG_VRML_SFVEC2F: return NewSFVec2f();
	case GF_SG_VRML_SFVEC3D: return NewSFVec3d();
	case GF_SG_VRML_SFVEC2D: return NewSFVec2d();
	case GF_SG_VRML_SFCOLOR: return NewSFColor();
	case GF_SG_VRML_SFCOLORRGBA: return NewSFColorRGBA();
	case GF_SG_VRML_SFROTATION: return NewSFRotation();
	case GF_SG_VRML_SFIMAGE: return NewSFImage();
	case GF_SG_VRML_MFBOOL: return NewMFBool();
	case GF_SG_VRML_MFFLOAT: return NewMFFloat();
	case GF_SG_VRML_MFTIME: return NewMFTime();
	case GF_SG_VRML_MFINT32: return NewMFInt32();
	case GF_SG_VRML_MFSTRING: return NewMFString();
	case GF_SG_VRML_MFVEC3F: return NewMFVec3f();
	case GF_SG_VRML_MFVEC2F: return NewMFVec2f();
	case GF_SG_VRML_MFVEC3D: return NewMFVec3d();
	case GF_SG_VRML_MFVEC2D: return NewMFVec2d();
	case GF_SG_VRML_MFCOLOR: return NewMFColor();
	case GF_SG_VRML_MFCOLORRGBA: return NewMFColorRGBA();
	case GF_SG_VRML_MFROTATION: return NewMFRotation();

	//used in proto and script 
	case GF_SG_VRML_MFNODE: 
	{
		return gf_list_new();
	}
	//used in commands
	case GF_SG_VRML_SFCOMMANDBUFFER:
		return NewSFCommandBuffer();

	case GF_SG_VRML_SFURL: 
		return NewSFURL();
	case GF_SG_VRML_MFURL:
		return NewMFURL();
	}
	return NULL;
}

void gf_sg_mfint32_del(MFInt32 par) { free(par.vals); }
void gf_sg_mffloat_del(MFFloat par) { free(par.vals); }
void gf_sg_mfdouble_del(MFDouble par) { free(par.vals); }
void gf_sg_mfbool_del(MFBool par) { free(par.vals); }
void gf_sg_mfcolor_del(MFColor par) { free(par.vals); }
void gf_sg_mfcolor_rgba_del(MFColorRGBA par) { free(par.vals); }
void gf_sg_mfrotation_del(MFRotation par) { free(par.vals); }
void gf_sg_mftime_del(MFTime par) { free(par.vals); }
void gf_sg_mfvec2f_del(MFVec2f par) { free(par.vals); }
void gf_sg_mfvec2d_del(MFVec2d par) { free(par.vals); }
void gf_sg_mfvec3f_del(MFVec3f par) { free(par.vals); }
void gf_sg_mfvec3d_del(MFVec3d par) { free(par.vals); }
void gf_sg_mfvec4f_del(MFVec4f par) { free(par.vals); }
void gf_sg_sfimage_del(SFImage im) { free(im.pixels); }
void gf_sg_sfurl_del(SFURL url) { if (url.url) free(url.url); }
void gf_sg_sfstring_del(SFString par) { if (par.buffer) free(par.buffer); }

void gf_sg_mfstring_del(MFString par)
{
	u32 i;
	for (i=0; i<par.count; i++) {
		if (par.vals[i]) free(par.vals[i]);
	}
	free(par.vals);
}


void gf_sg_sfcommand_del(SFCommandBuffer cb)
{
	u32 i;
	for (i=gf_list_count(cb.commandList); i>0; i--) {
		GF_Command *com = gf_list_get(cb.commandList, i-1);
		gf_sg_command_del(com);
	}
	gf_list_del(cb.commandList);
	if (cb.buffer) free(cb.buffer);
}

void gf_sg_mfurl_del(MFURL url)
{
	u32 i;
	for (i=0; i<url.count; i++) {
		gf_sg_sfurl_del(url.vals[i]);
	}
	free(url.vals);
}
void gf_sg_mfscript_del(MFScript sc)
{
	u32 i;
	for (i=0; i<sc.count; i++) {
		if (sc.vals[i].script_text) free(sc.vals[i].script_text);
	}
	free(sc.vals);
}


void gf_sg_vrml_field_pointer_del(void *field, u32 FieldType) 
{
	GF_Node *node;

	switch (FieldType) {
	case GF_SG_VRML_SFBOOL: 
	case GF_SG_VRML_SFFLOAT:
	case GF_SG_VRML_SFDOUBLE:
	case GF_SG_VRML_SFTIME: 
	case GF_SG_VRML_SFINT32:
	case GF_SG_VRML_SFVEC3F:
	case GF_SG_VRML_SFVEC3D:
	case GF_SG_VRML_SFVEC2F:
	case GF_SG_VRML_SFVEC2D:
	case GF_SG_VRML_SFCOLOR:
	case GF_SG_VRML_SFCOLORRGBA:
	case GF_SG_VRML_SFROTATION:
		break;
	case GF_SG_VRML_SFSTRING:
		if ( ((SFString *)field)->buffer) free(((SFString *)field)->buffer);
		break;
	case GF_SG_VRML_SFIMAGE:
		gf_sg_sfimage_del(* ((SFImage *)field));
		break;

	case GF_SG_VRML_SFNODE: 
		node = *(GF_Node **) field;
		if (node) gf_node_del(node);
		return;
	case GF_SG_VRML_SFCOMMANDBUFFER:
		gf_sg_sfcommand_del(*(SFCommandBuffer *)field);
		break;
	
	case GF_SG_VRML_MFBOOL:
		gf_sg_mfbool_del( * ((MFBool *) field));
		break;
	case GF_SG_VRML_MFFLOAT: 
		gf_sg_mffloat_del( * ((MFFloat *) field));
		break;
	case GF_SG_VRML_MFDOUBLE: 
		gf_sg_mfdouble_del( * ((MFDouble *) field));
		break;
	case GF_SG_VRML_MFTIME: 
		gf_sg_mftime_del( * ((MFTime *)field));
		break;
	case GF_SG_VRML_MFINT32:
		gf_sg_mfint32_del( * ((MFInt32 *)field));
		break;
	case GF_SG_VRML_MFSTRING:
		gf_sg_mfstring_del( *((MFString *)field));
		break;
	case GF_SG_VRML_MFVEC3F:
		gf_sg_mfvec3f_del( * ((MFVec3f *)field));
		break;
	case GF_SG_VRML_MFVEC2F:
		gf_sg_mfvec2f_del( * ((MFVec2f *)field));
		break;
	case GF_SG_VRML_MFVEC3D:
		gf_sg_mfvec3d_del( * ((MFVec3d *)field));
		break;
	case GF_SG_VRML_MFVEC2D:
		gf_sg_mfvec2d_del( * ((MFVec2d *)field));
		break;
	case GF_SG_VRML_MFCOLOR:
		gf_sg_mfcolor_del( * ((MFColor *)field));
		break;
	case GF_SG_VRML_MFCOLORRGBA:
		gf_sg_mfcolor_rgba_del( * ((MFColorRGBA *)field));
		break;
	case GF_SG_VRML_MFROTATION:
		gf_sg_mfrotation_del( * ((MFRotation *)field));
		break;
	case GF_SG_VRML_MFURL:
		gf_sg_mfurl_del( * ((MFURL *) field));
		break;		
	//used only in proto since this field is created by default for regular nodes
	case GF_SG_VRML_MFNODE: 
		while (gf_list_count((GF_List *)field)) {
			node = gf_list_get((GF_List *)field, 0);
			gf_node_del(node);
			gf_list_rem((GF_List *)field, 0);
		}
		gf_list_del((GF_List *)field);
		return;

	default:
		assert(0);
		return;
	}
	//free pointer
	free(field);
}


Bool gf_sg_vrml_is_sf_field(u32 FieldType)
{
	switch (FieldType) {
	case GF_SG_VRML_SFBOOL:
	case GF_SG_VRML_SFFLOAT:
	case GF_SG_VRML_SFDOUBLE:
	case GF_SG_VRML_SFTIME:
	case GF_SG_VRML_SFINT32:
	case GF_SG_VRML_SFSTRING:
	case GF_SG_VRML_SFVEC3F:
	case GF_SG_VRML_SFVEC3D:
	case GF_SG_VRML_SFVEC2F:
	case GF_SG_VRML_SFVEC2D:
	case GF_SG_VRML_SFCOLOR:
	case GF_SG_VRML_SFCOLORRGBA:
	case GF_SG_VRML_SFROTATION:
	case GF_SG_VRML_SFIMAGE:
	case GF_SG_VRML_SFNODE:
	case GF_SG_VRML_SFURL:
	case GF_SG_VRML_SFCOMMANDBUFFER:
		return 1;
	default:
		return 0;
	}
}

/*********************************************************************
		MF Fields manipulation (alloc, realloc, GetAt)
*********************************************************************/

//return the size of fixed fields (eg no buffer in the field)
u32 gf_sg_vrml_get_sf_size(u32 FieldType)
{
	switch (FieldType) {
	case GF_SG_VRML_SFBOOL:
	case GF_SG_VRML_MFBOOL:
		return sizeof(SFBool);
	case GF_SG_VRML_SFFLOAT:
	case GF_SG_VRML_MFFLOAT:
		return sizeof(SFFloat);
	case GF_SG_VRML_SFTIME:
	case GF_SG_VRML_MFTIME:
		return sizeof(SFTime);
	case GF_SG_VRML_SFDOUBLE:
	case GF_SG_VRML_MFDOUBLE:
		return sizeof(SFDouble);
	case GF_SG_VRML_SFINT32:
	case GF_SG_VRML_MFINT32:
		return sizeof(SFInt32);
	case GF_SG_VRML_SFVEC3F:
	case GF_SG_VRML_MFVEC3F:
		return 3*sizeof(SFFloat);
	case GF_SG_VRML_SFVEC2F:
	case GF_SG_VRML_MFVEC2F:
		return 2*sizeof(SFFloat);
	case GF_SG_VRML_SFVEC3D:
	case GF_SG_VRML_MFVEC3D:
		return 3*sizeof(SFDouble);
	case GF_SG_VRML_SFCOLOR:
	case GF_SG_VRML_MFCOLOR:
		return 3*sizeof(SFFloat);
	case GF_SG_VRML_SFCOLORRGBA:
	case GF_SG_VRML_MFCOLORRGBA:
		return 4*sizeof(SFFloat);
	case GF_SG_VRML_SFROTATION:
	case GF_SG_VRML_MFROTATION:
		return 4*sizeof(SFFloat);

	//check if that works!!
	case GF_SG_VRML_SFSTRING:
	case GF_SG_VRML_MFSTRING:
		//ptr to char
		return sizeof(SFString);
	case GF_SG_VRML_SFSCRIPT:
	case GF_SG_VRML_MFSCRIPT:
		return sizeof(SFScript);
	case GF_SG_VRML_SFURL:
	case GF_SG_VRML_MFURL:
		return sizeof(SFURL);
	default:
		return 0;
	}
}

u32 gf_sg_vrml_get_sf_type(u32 FieldType)
{
	switch (FieldType) {
	case GF_SG_VRML_SFBOOL:
	case GF_SG_VRML_MFBOOL:
		return GF_SG_VRML_SFBOOL;
	case GF_SG_VRML_SFFLOAT:
	case GF_SG_VRML_MFFLOAT:
		return GF_SG_VRML_SFFLOAT;
	case GF_SG_VRML_SFDOUBLE:
	case GF_SG_VRML_MFDOUBLE:
		return GF_SG_VRML_SFDOUBLE;
	case GF_SG_VRML_SFTIME:
	case GF_SG_VRML_MFTIME:
		return GF_SG_VRML_SFTIME;
	case GF_SG_VRML_SFINT32:
	case GF_SG_VRML_MFINT32:
		return GF_SG_VRML_SFINT32;
	case GF_SG_VRML_SFVEC3F:
	case GF_SG_VRML_MFVEC3F:
		return GF_SG_VRML_SFVEC3F;
	case GF_SG_VRML_SFVEC2F:
	case GF_SG_VRML_MFVEC2F:
		return GF_SG_VRML_SFVEC2F;
	case GF_SG_VRML_SFVEC3D:
	case GF_SG_VRML_MFVEC3D:
		return GF_SG_VRML_SFVEC3D;
	case GF_SG_VRML_SFVEC2D:
	case GF_SG_VRML_MFVEC2D:
		return GF_SG_VRML_SFVEC2D;
	case GF_SG_VRML_SFCOLOR:
	case GF_SG_VRML_MFCOLOR:
		return GF_SG_VRML_SFCOLOR;
	case GF_SG_VRML_SFCOLORRGBA:
	case GF_SG_VRML_MFCOLORRGBA:
		return GF_SG_VRML_SFCOLORRGBA;
	case GF_SG_VRML_SFROTATION:
	case GF_SG_VRML_MFROTATION:
		return GF_SG_VRML_SFROTATION;

	//check if that works!!
	case GF_SG_VRML_SFSTRING:
	case GF_SG_VRML_MFSTRING:
		//ptr to char
		return GF_SG_VRML_SFSTRING;
	case GF_SG_VRML_SFSCRIPT:
	case GF_SG_VRML_MFSCRIPT:
		return GF_SG_VRML_SFSCRIPT;
	case GF_SG_VRML_SFURL:
	case GF_SG_VRML_MFURL:
		return GF_SG_VRML_SFURL;
	case GF_SG_VRML_SFNODE:
	case GF_SG_VRML_MFNODE:
		return GF_SG_VRML_SFNODE;
	default:
		return GF_SG_VRML_UNKNOWN;
	}
}

const char *gf_sg_vrml_get_event_type_name(u32 EventType, Bool forX3D)
{
	switch (EventType) {
	case GF_SG_EVENT_IN: return forX3D ? "inputOnly" : "eventIn";
	case GF_SG_EVENT_FIELD: return forX3D ? "initializeOnly" : "field";
	case GF_SG_EVENT_EXPOSED_FIELD: return forX3D ? "inputOutput" : "exposedField";
	case GF_SG_EVENT_OUT: return forX3D ? "outputOnly" : "eventOut";
	default: return "unknownEvent";
	}
}

const char *gf_sg_vrml_get_field_type_by_name(u32 FieldType)
{

	switch (FieldType) {
	case GF_SG_VRML_SFBOOL: return "SFBool";
	case GF_SG_VRML_SFFLOAT: return "SFFloat";
	case GF_SG_VRML_SFDOUBLE: return "SFDouble";
	case GF_SG_VRML_SFTIME: return "SFTime";
	case GF_SG_VRML_SFINT32: return "SFInt32";
	case GF_SG_VRML_SFSTRING: return "SFString";
	case GF_SG_VRML_SFVEC3F: return "SFVec3f";
	case GF_SG_VRML_SFVEC2F: return "SFVec2f";
	case GF_SG_VRML_SFVEC3D: return "SFVec3d";
	case GF_SG_VRML_SFVEC2D: return "SFVec2d";
	case GF_SG_VRML_SFCOLOR: return "SFColor";
	case GF_SG_VRML_SFCOLORRGBA: return "SFColorRGBA";
	case GF_SG_VRML_SFROTATION: return "SFRotation";
	case GF_SG_VRML_SFIMAGE: return "SFImage";
	case GF_SG_VRML_SFNODE: return "SFNode";
	case GF_SG_VRML_SFVEC4F: return "SFVec4f";
	case GF_SG_VRML_MFBOOL: return "MFBool";
	case GF_SG_VRML_MFFLOAT: return "MFFloat";
	case GF_SG_VRML_MFDOUBLE: return "MFDouble";
	case GF_SG_VRML_MFTIME: return "MFTime";
	case GF_SG_VRML_MFINT32: return "MFInt32";
	case GF_SG_VRML_MFSTRING: return "MFString";
	case GF_SG_VRML_MFVEC3F: return "MFVec3f";
	case GF_SG_VRML_MFVEC2F: return "MFVec2f";
	case GF_SG_VRML_MFVEC3D: return "MFVec3d";
	case GF_SG_VRML_MFVEC2D: return "MFVec2d";
	case GF_SG_VRML_MFCOLOR: return "MFColor";
	case GF_SG_VRML_MFCOLORRGBA: return "MFColorRGBA";
	case GF_SG_VRML_MFROTATION: return "MFRotation";
	case GF_SG_VRML_MFIMAGE: return "MFImage";
	case GF_SG_VRML_MFNODE: return "MFNode";
	case GF_SG_VRML_MFVEC4F: return "MFVec4f";
	case GF_SG_VRML_SFURL: return "SFURL";
	case GF_SG_VRML_MFURL: return "MFURL";
	case GF_SG_VRML_SFCOMMANDBUFFER: return "SFCommandBuffer";
	case GF_SG_VRML_SFSCRIPT: return "SFScript";
	case GF_SG_VRML_MFSCRIPT: return "MFScript";
	default: return "UnknownType";
	}
}

u32 gf_sg_field_type_by_name(char *fieldType)
{
	if (!stricmp(fieldType, "SFBool")) return GF_SG_VRML_SFBOOL;
	else if (!stricmp(fieldType, "SFFloat")) return GF_SG_VRML_SFFLOAT;
	else if (!stricmp(fieldType, "SFDouble")) return GF_SG_VRML_SFDOUBLE;
	else if (!stricmp(fieldType, "SFTime")) return GF_SG_VRML_SFTIME;
	else if (!stricmp(fieldType, "SFInt32")) return GF_SG_VRML_SFINT32;
	else if (!stricmp(fieldType, "SFString")) return GF_SG_VRML_SFSTRING;
	else if (!stricmp(fieldType, "SFVec2f")) return GF_SG_VRML_SFVEC2F;
	else if (!stricmp(fieldType, "SFVec3f")) return GF_SG_VRML_SFVEC3F;
	else if (!stricmp(fieldType, "SFVec2d")) return GF_SG_VRML_SFVEC2D;
	else if (!stricmp(fieldType, "SFVec3d")) return GF_SG_VRML_SFVEC3D;
	else if (!stricmp(fieldType, "SFColor")) return GF_SG_VRML_SFCOLOR;
	else if (!stricmp(fieldType, "SFColorRGBA")) return GF_SG_VRML_SFCOLORRGBA;
	else if (!stricmp(fieldType, "SFRotation")) return GF_SG_VRML_SFROTATION;
	else if (!stricmp(fieldType, "SFImage")) return GF_SG_VRML_SFIMAGE;
	else if (!stricmp(fieldType, "SFNode")) return GF_SG_VRML_SFNODE;

	else if (!stricmp(fieldType, "MFBool")) return GF_SG_VRML_MFBOOL;
	else if (!stricmp(fieldType, "MFFloat")) return GF_SG_VRML_MFFLOAT;
	else if (!stricmp(fieldType, "MFDouble")) return GF_SG_VRML_MFDOUBLE;
	else if (!stricmp(fieldType, "MFTime")) return GF_SG_VRML_MFTIME;
	else if (!stricmp(fieldType, "MFInt32")) return GF_SG_VRML_MFINT32;
	else if (!stricmp(fieldType, "MFString")) return GF_SG_VRML_MFSTRING;
	else if (!stricmp(fieldType, "MFVec2f")) return GF_SG_VRML_MFVEC2F;
	else if (!stricmp(fieldType, "MFVec3f")) return GF_SG_VRML_MFVEC3F;
	else if (!stricmp(fieldType, "MFVec2d")) return GF_SG_VRML_MFVEC2D;
	else if (!stricmp(fieldType, "MFVec3d")) return GF_SG_VRML_MFVEC3D;
	else if (!stricmp(fieldType, "MFColor")) return GF_SG_VRML_MFCOLOR;
	else if (!stricmp(fieldType, "MFColorRGBA")) return GF_SG_VRML_MFCOLORRGBA;
	else if (!stricmp(fieldType, "MFRotation")) return GF_SG_VRML_MFROTATION;
	else if (!stricmp(fieldType, "MFImage")) return GF_SG_VRML_MFIMAGE;
	else if (!stricmp(fieldType, "MFNode")) return GF_SG_VRML_MFNODE;

	return GF_SG_VRML_UNKNOWN;
}

//
//	Insert (+alloc) an MFField with a specified position for insertion and sets the ptr to the 
//	newly created slot
//	!! Doesnt work for MFNodes
//	InsertAt is the 0-based index for the new slot
GF_Err gf_sg_vrml_mf_insert(void *mf, u32 FieldType, void **new_ptr, u32 InsertAt)
{
	char *buffer;
	u32 FieldSize, i, k;
	GenMFField *mffield = (GenMFField *)mf;

	if (gf_sg_vrml_is_sf_field(FieldType)) return GF_BAD_PARAM;
	if (FieldType == GF_SG_VRML_MFNODE) return GF_BAD_PARAM;

	FieldSize = gf_sg_vrml_get_sf_size(FieldType);
	
	//field we can't copy
	if (!FieldSize) return GF_BAD_PARAM;
	
	//first item ever
	if (!mffield->count || !mffield->array) {
		if (mffield->array) free(mffield->array);
		mffield->array = malloc(sizeof(char)*FieldSize);
		memset(mffield->array, 0, sizeof(char)*FieldSize);
		mffield->count = 1;
		if (new_ptr) *new_ptr = mffield->array;
		return GF_OK;
	}

	//alloc 1+itemCount
	buffer = malloc(sizeof(char)*(1+mffield->count)*FieldSize);

	//append at the end
	if (InsertAt >= mffield->count) {
		memcpy(buffer, mffield->array, mffield->count * FieldSize);
		memset(buffer + mffield->count * FieldSize, 0, FieldSize);
		if (new_ptr) *new_ptr = buffer + mffield->count * FieldSize;
		free(mffield->array);
		mffield->array = buffer;
		mffield->count += 1;
		return GF_OK;
	}
	//insert in the array
	k=0;
	for (i=0; i < mffield->count; i++) {
		if (InsertAt == i) {
			if (new_ptr) {
				*new_ptr = buffer + i*FieldSize;
				memset(*new_ptr, 0, sizeof(char)*FieldSize);
			}
			k = 1;
		}
		memcpy(buffer + (k+i) * FieldSize , mffield->array + i*FieldSize, FieldSize);
	}
	free(mffield->array);
	mffield->array = buffer;
	mffield->count += 1;
	return GF_OK;
}

#define MAX_MFFIELD_ALLOC		5000000
GF_Err gf_sg_vrml_mf_alloc(void *mf, u32 FieldType, u32 NbItems)
{
	u32 FieldSize;
	GenMFField *mffield = (GenMFField *)mf;

	if (gf_sg_vrml_is_sf_field(FieldType)) return GF_BAD_PARAM;
	if (FieldType == GF_SG_VRML_MFNODE) return GF_BAD_PARAM;

	FieldSize = gf_sg_vrml_get_sf_size(FieldType);
	
	//field we can't copy
	if (!FieldSize) return GF_BAD_PARAM;
	if (NbItems>MAX_MFFIELD_ALLOC) return GF_IO_ERR;

	if (mffield->count!=NbItems) 
		gf_sg_vrml_mf_reset(mf, FieldType);

	if (NbItems) {
		mffield->array = malloc(sizeof(char)*FieldSize*NbItems);
		memset(mffield->array, 0, sizeof(char)*FieldSize*NbItems);
	}
	mffield->count = NbItems;
	return GF_OK;
}

GF_Err gf_sg_vrml_mf_get_item(void *mf, u32 FieldType, void **new_ptr, u32 ItemPos)
{
	u32 FieldSize;
	GenMFField *mffield = (GenMFField *)mf;

	*new_ptr = NULL;
	if (gf_sg_vrml_is_sf_field(FieldType)) return GF_BAD_PARAM;
	if (FieldType == GF_SG_VRML_MFNODE) return GF_BAD_PARAM;

	FieldSize = gf_sg_vrml_get_sf_size(FieldType);
	
	//field we can't copy
	if (!FieldSize) return GF_BAD_PARAM;
	if (ItemPos >= mffield->count) return GF_BAD_PARAM;
	*new_ptr = mffield->array + ItemPos * FieldSize;
	return GF_OK;
}


GF_Err gf_sg_vrml_mf_append(void *mf, u32 FieldType, void **new_ptr)
{
	GenMFField *mffield = (GenMFField *)mf;
	return gf_sg_vrml_mf_insert(mf, FieldType, new_ptr, mffield->count+2);
}


//remove the specified item (0-based index)
GF_Err gf_sg_vrml_mf_remove(void *mf, u32 FieldType, u32 RemoveFrom)
{
	char *buffer;
	u32 FieldSize, i, k;
	GenMFField *mffield = (GenMFField *)mf;

	FieldSize = gf_sg_vrml_get_sf_size(FieldType);
	
	//field we can't copy
	if (!FieldSize) return GF_BAD_PARAM;

	if (!mffield->count || RemoveFrom >= mffield->count) return GF_BAD_PARAM;

	if (mffield->count == 1) {
		free(mffield->array);
		mffield->array = NULL;
		mffield->count = 0;
		return GF_OK;
	}
	k=0;
	buffer = malloc(sizeof(char)*(mffield->count-1)*FieldSize);
	for (i=0; i<mffield->count; i++) {
		if (RemoveFrom == i) {
			k = 1;
		} else {
			memcpy(buffer + (i-k)*FieldSize, mffield->array + i*FieldSize, FieldSize);
		}
	}
	free(mffield->array);
	mffield->array = buffer;
	mffield->count -= 1;
	return GF_OK;
}

GF_Err gf_sg_vrml_mf_reset(void *mf, u32 FieldType)
{
	GenMFField *mffield = (GenMFField *)mf;
	if (!mffield->array) return GF_OK;

	//field we can't copy
	if (gf_sg_vrml_is_sf_field(FieldType)) return GF_BAD_PARAM;
	if (!gf_sg_vrml_get_sf_size(FieldType)) return GF_BAD_PARAM;

	switch (FieldType) {
	case GF_SG_VRML_MFSTRING:
		gf_sg_mfstring_del( * ((MFString *) mf));
		break;
	case GF_SG_VRML_MFURL:
		gf_sg_mfurl_del( * ((MFURL *) mf));
		break;
	case GF_SG_VRML_MFSCRIPT:
		gf_sg_mfscript_del( * ((MFScript *) mf));
		break;
	default:
		if (mffield->array) free(mffield->array);
		break;
	}

	mffield->array = NULL;
	mffield->count = 0;
	return GF_OK;
}

/*special cloning with type-casting from SF/MF strings to URL conversion since proto URL doesn't exist
as a field type (it's just a stupid encoding trick) */
void VRML_FieldCopyCast(void *dest, u32 dst_field_type, void *orig, u32 ori_field_type)
{
	SFURL *url;
	char tmp[50];
	u32 size, i, sf_type_ori, sf_type_dst;
	void *dst_field, *orig_field;
	if (!dest || !orig) return;
	
	switch (dst_field_type) {
	case GF_SG_VRML_SFSTRING:
		if (ori_field_type == GF_SG_VRML_SFURL) {
			url = ((SFURL *)orig);
			if (url->OD_ID>0) {
				sprintf(tmp, "%d", url->OD_ID);
				if ( ((SFString*)dest)->buffer) free(((SFString*)dest)->buffer);
				((SFString*)dest)->buffer = strdup(tmp);
			} else {
				if ( ((SFString*)dest)->buffer) free(((SFString*)dest)->buffer);
				((SFString*)dest)->buffer = strdup(url->url);
			}
		}
		/*for SFString to MFString cast*/
		else if (ori_field_type == GF_SG_VRML_SFSTRING) {
			if ( ((SFString*)dest)->buffer) free(((SFString*)dest)->buffer);
			((SFString*)dest)->buffer = strdup(((SFString*)orig)->buffer);
		}
		return;
	case GF_SG_VRML_SFURL:
		if (ori_field_type != GF_SG_VRML_SFSTRING) return;
		url = ((SFURL *)dest);
		url->OD_ID = 0;
		if (url->url) free(url->url);
		if ( ((SFString*)orig)->buffer) 
			url->url = strdup(((SFString*)orig)->buffer);
		else 
			url->url = NULL;
		return;
	case GF_SG_VRML_MFSTRING:
	case GF_SG_VRML_MFURL:
		break;
	default:
		return;
	}

	sf_type_dst = gf_sg_vrml_get_sf_type(dst_field_type);

	if (gf_sg_vrml_is_sf_field(ori_field_type)) {
		size = 1;
		gf_sg_vrml_mf_alloc(dest, dst_field_type, size);
		gf_sg_vrml_mf_get_item(dest, dst_field_type, &dst_field, 0);
		VRML_FieldCopyCast(dst_field, sf_type_dst, orig, ori_field_type);
		return;
	}

	size = ((GenMFField *)orig)->count;
	if (size != ((GenMFField *)dest)->count) gf_sg_vrml_mf_alloc(dest, dst_field_type, size);

	sf_type_ori = gf_sg_vrml_get_sf_type(ori_field_type);
	//duplicate all items
	for (i=0; i<size; i++) {
		gf_sg_vrml_mf_get_item(dest, dst_field_type, &dst_field, i);
		gf_sg_vrml_mf_get_item(orig, ori_field_type, &orig_field, i);
		VRML_FieldCopyCast(dst_field, sf_type_dst, orig_field, sf_type_ori);
	}
	return;
}

void gf_sg_vrml_field_copy(void *dest, void *orig, u32 field_type)
{
	u32 size, i, sf_type;
	void *dst_field, *orig_field;

	if (!dest || !orig) return;

	switch (field_type) {
	case GF_SG_VRML_SFBOOL:
		memcpy(dest, orig, sizeof(SFBool));
		break;
	case GF_SG_VRML_SFCOLOR:
		memcpy(dest, orig, sizeof(SFColor));
		break;
	case GF_SG_VRML_SFFLOAT:
		memcpy(dest, orig, sizeof(SFFloat));
		break;
	case GF_SG_VRML_SFINT32:
		memcpy(dest, orig, sizeof(SFInt32));
		break;
	case GF_SG_VRML_SFROTATION:
		memcpy(dest, orig, sizeof(SFRotation));
		break;
	case GF_SG_VRML_SFTIME:
		memcpy(dest, orig, sizeof(SFTime));
		break;
	case GF_SG_VRML_SFVEC2F:
		memcpy(dest, orig, sizeof(SFVec2f));
		break;
	case GF_SG_VRML_SFVEC3F:
		memcpy(dest, orig, sizeof(SFVec3f));
		break;
	case GF_SG_VRML_SFSTRING:
		if ( ((SFString*)dest)->buffer) free(((SFString*)dest)->buffer);
		if ( ((SFString*)orig)->buffer )
			((SFString*)dest)->buffer = strdup(((SFString*)orig)->buffer);
		else
			((SFString*)dest)->buffer = NULL;
		break;
	case GF_SG_VRML_SFURL:
		if ( ((SFURL *)dest)->url ) free( ((SFURL *)dest)->url );
		((SFURL *)dest)->OD_ID = ((SFURL *)orig)->OD_ID;
		if (((SFURL *)orig)->url) 
			((SFURL *)dest)->url = strdup(((SFURL *)orig)->url);
		else
			((SFURL *)dest)->url = NULL;
		break;
	case GF_SG_VRML_SFIMAGE:
		if (((SFImage *)dest)->pixels) free(((SFImage *)dest)->pixels);
		((SFImage *)dest)->width = ((SFImage *)orig)->width;
		((SFImage *)dest)->height = ((SFImage *)orig)->height;
		((SFImage *)dest)->numComponents  = ((SFImage *)orig)->numComponents;
		size = ((SFImage *)dest)->width * ((SFImage *)dest)->height * ((SFImage *)dest)->numComponents;
		((SFImage *)dest)->pixels = malloc(sizeof(char)*size);
		memcpy(((SFImage *)dest)->pixels, ((SFImage *)orig)->pixels, sizeof(char)*size);
		break;
	case GF_SG_VRML_SFCOMMANDBUFFER:
		gf_sg_sfcommand_del( *(SFCommandBuffer *)dest);
		((SFCommandBuffer *)dest)->commandList = gf_list_new();
		((SFCommandBuffer *)dest)->bufferSize = ((SFCommandBuffer *)orig)->bufferSize;
		((SFCommandBuffer *)dest)->buffer = malloc(sizeof(char)*((SFCommandBuffer *)orig)->bufferSize);
		memcpy(((SFCommandBuffer *)dest)->buffer, 
			((SFCommandBuffer *)orig)->buffer,
			sizeof(char)*((SFCommandBuffer *)orig)->bufferSize);
		break;

	/*simply copy text string*/
	case GF_SG_VRML_SFSCRIPT:
		if (((SFScript*)dest)->script_text) free(((SFScript*)dest)->script_text);		
		((SFScript*)dest)->script_text = NULL;
		if ( ((SFScript*)orig)->script_text)
			((SFScript *)dest)->script_text = strdup( ((SFScript*)orig)->script_text );
		break;


	//MFFields
	case GF_SG_VRML_MFBOOL:
	case GF_SG_VRML_MFFLOAT:
	case GF_SG_VRML_MFTIME:
	case GF_SG_VRML_MFINT32:
	case GF_SG_VRML_MFSTRING:
	case GF_SG_VRML_MFVEC3F:
	case GF_SG_VRML_MFVEC2F:
	case GF_SG_VRML_MFCOLOR:
	case GF_SG_VRML_MFROTATION:
	case GF_SG_VRML_MFIMAGE:
	case GF_SG_VRML_MFURL:
	case GF_SG_VRML_MFSCRIPT:
		size = ((GenMFField *)orig)->count;
		gf_sg_vrml_mf_reset(dest, field_type);
		gf_sg_vrml_mf_alloc(dest, field_type, size);
		sf_type = gf_sg_vrml_get_sf_type(field_type);
		//duplicate all items
		for (i=0; i<size; i++) {
			gf_sg_vrml_mf_get_item(dest, field_type, &dst_field, i);
			gf_sg_vrml_mf_get_item(orig, field_type, &orig_field, i);
			gf_sg_vrml_field_copy(dst_field, orig_field, sf_type);
		}
		break;
	}
}


Bool gf_sg_vrml_field_equal(void *dest, void *orig, u32 field_type)
{
	u32 size, i, sf_type;
	void *dst_field, *orig_field;
	Bool changed = 0;

	if (!dest || !orig) return 0;

	switch (field_type) {
	case GF_SG_VRML_SFBOOL:
		changed = memcmp(dest, orig, sizeof(SFBool));
		break;
	case GF_SG_VRML_SFCOLOR:
		if (((SFColor *)dest)->red != ((SFColor *)orig)->red) changed = 1;
		else if (((SFColor *)dest)->green != ((SFColor *)orig)->green) changed = 1;
		else if (((SFColor *)dest)->blue != ((SFColor *)orig)->blue) changed = 1;
		break;
	case GF_SG_VRML_SFFLOAT:
		if ( (*(SFFloat *)dest) != (*(SFFloat *)orig) ) changed = 1;
		break;
	case GF_SG_VRML_SFINT32:
		changed = memcmp(dest, orig, sizeof(SFInt32));
		break;
	case GF_SG_VRML_SFROTATION:
		if (((SFRotation *)dest)->x != ((SFRotation *)orig)->x) changed = 1;
		else if (((SFRotation *)dest)->y != ((SFRotation *)orig)->y) changed = 1;
		else if (((SFRotation *)dest)->z != ((SFRotation *)orig)->z) changed = 1;
		else if (((SFRotation *)dest)->q != ((SFRotation *)orig)->q) changed = 1;
		break;
	case GF_SG_VRML_SFTIME:
		if ( (*(SFTime *)dest) != (*(SFTime*)orig) ) changed = 1;
		break;
	case GF_SG_VRML_SFVEC2F:
		if (((SFVec2f *)dest)->x != ((SFVec2f *)orig)->x) changed = 1;
		else if (((SFVec2f *)dest)->y != ((SFVec2f *)orig)->y) changed = 1;
		break;
	case GF_SG_VRML_SFVEC3F:
		if (((SFVec3f *)dest)->x != ((SFVec3f *)orig)->x) changed = 1;
		else if (((SFVec3f *)dest)->y != ((SFVec3f *)orig)->y) changed = 1;
		else if (((SFVec3f *)dest)->z != ((SFVec3f *)orig)->z) changed = 1;
		break;
	case GF_SG_VRML_SFSTRING:
		if ( ((SFString*)dest)->buffer && ((SFString*)orig)->buffer) {
			changed = strcmp(((SFString*)dest)->buffer, ((SFString*)orig)->buffer);
		} else {
			changed = ( !((SFString*)dest)->buffer && !((SFString*)orig)->buffer) ? 0 : 1;
		}
		break;
	case GF_SG_VRML_SFURL:
		if (((SFURL *)dest)->OD_ID > 0 || ((SFURL *)orig)->OD_ID > 0) {
			if ( ((SFURL *)orig)->OD_ID != ((SFURL *)dest)->OD_ID) changed = 1;
		} else {
			if ( ((SFURL *)orig)->url && ! ((SFURL *)dest)->url) changed = 1;
			else if ( ! ((SFURL *)orig)->url && ((SFURL *)dest)->url) changed = 1;
			else if ( strcmp( ((SFURL *)orig)->url , ((SFURL *)dest)->url) ) changed = 1;
		}
		break;
	case GF_SG_VRML_SFIMAGE:
	case GF_SG_VRML_SFSCRIPT:
	case GF_SG_VRML_SFCOMMANDBUFFER:
		changed = 1;
		break;

	//MFFields
	case GF_SG_VRML_MFBOOL:
	case GF_SG_VRML_MFFLOAT:
	case GF_SG_VRML_MFTIME:
	case GF_SG_VRML_MFINT32:
	case GF_SG_VRML_MFSTRING:
	case GF_SG_VRML_MFVEC3F:
	case GF_SG_VRML_MFVEC2F:
	case GF_SG_VRML_MFCOLOR:
	case GF_SG_VRML_MFROTATION:
	case GF_SG_VRML_MFIMAGE:
	case GF_SG_VRML_MFURL:
	case GF_SG_VRML_MFSCRIPT:
		if ( ((GenMFField *)orig)->count != ((GenMFField *)dest)->count) changed = 1;
		else {
			size = ((GenMFField *)orig)->count;
			sf_type = gf_sg_vrml_get_sf_type(field_type);
			for (i=0; i<size; i++) {
				gf_sg_vrml_mf_get_item(dest, field_type, &dst_field, i);
				gf_sg_vrml_mf_get_item(orig, field_type, &orig_field, i);
				if (! gf_sg_vrml_field_equal(dst_field, orig_field, sf_type) ) {
					changed = 1;
					break;
				}
			}
		}
		break;
	}
	return changed ? 0 : 1;
}



SFColorRGBA gf_sg_sfcolor_to_rgba(SFColor val)
{
	SFColorRGBA res;
	res.alpha = 1;
	res.red = val.red;
	res.green = val.green;
	res.blue = val.blue;
	return res;
}



u32 gf_node_get_num_fields_in_mode(GF_Node *Node, u8 IndexMode)
{
	assert(Node);
#ifdef GF_NODE_USE_POINTERS
	return Node->sgprivate->get_field_count(Node, IndexMode);
#else

	if (Node->sgprivate->tag == TAG_ProtoNode) return gf_sg_proto_get_num_fields(Node, IndexMode);
	else if ((Node->sgprivate->tag == TAG_MPEG4_Script) || (Node->sgprivate->tag == TAG_X3D_Script) )
		return gf_sg_script_get_num_fields(Node, IndexMode);
	else if (Node->sgprivate->tag <= GF_NODE_RANGE_LAST_MPEG4) return gf_sg_mpeg4_node_get_field_count(Node, IndexMode);
	else if (Node->sgprivate->tag <= GF_NODE_RANGE_LAST_X3D) return gf_sg_x3d_node_get_field_count(Node);
	else return 0;

#endif
}



/*all our internally handled nodes*/
void ScalarInt_SetFraction(GF_Node *node);
void PosInt2D_SetFraction(GF_Node *node);
void ColorInt_SetFraction(GF_Node *node);
void CI2D_SetFraction(GF_Node *n);
void CoordInt_SetFraction(GF_Node *n);
void PosInt_SetFraction(GF_Node *node);
void OrientInt_SetFraction(GF_Node *node);
void NormInt_SetFraction(GF_Node *n);
void Valuator_SetInSFBool(GF_Node *n);
void Valuator_SetInSFColor(GF_Node *n);
void Valuator_SetInSFFloat(GF_Node *n);
void Valuator_SetInSFInt32(GF_Node *n);
void Valuator_SetInSFTime(GF_Node *n);
void Valuator_SetInSFVec2f(GF_Node *n);
void Valuator_SetInSFVec3f(GF_Node *n);
void Valuator_SetInSFRotation(GF_Node *n);
void Valuator_SetInSFString(GF_Node *n);
void Valuator_SetInMFString(GF_Node *n);
void Valuator_SetInMFColor(GF_Node *n);
void Valuator_SetInMFFloat(GF_Node *n);
void Valuator_SetInMFInt32(GF_Node *n);
void Valuator_SetInMFVec2f(GF_Node *n);
void Valuator_SetInMFVec3f(GF_Node *n);
void Valuator_SetInMFRotation(GF_Node *n);
void PA_Init(GF_Node *n);
void PA_Modified(GF_Node *n, GF_FieldInfo *field);
void PA2D_Init(GF_Node *n);
void PA2D_Modified(GF_Node *n, GF_FieldInfo *field);
void SA_Init(GF_Node *n);
void SA_Modified(GF_Node *n, GF_FieldInfo *field);
void CI4D_SetFraction(GF_Node *n);
void PI4D_SetFraction(GF_Node *n);
/*X3D tools*/
void InitBooleanFilter(GF_Node *n);
void InitBooleanSequencer(GF_Node *n);
void InitBooleanToggle(GF_Node *n);
void InitBooleanTrigger(GF_Node *n);
void InitIntegerSequencer(GF_Node *n);
void InitIntegerTrigger(GF_Node *n);
void InitTimeTrigger(GF_Node *n);


Bool gf_sg_vrml_node_init(GF_Node *node)
{
	switch (node->sgprivate->tag) {
	case TAG_MPEG4_ColorInterpolator: 
	case TAG_X3D_ColorInterpolator:
		((M_ColorInterpolator *)node)->on_set_fraction = ColorInt_SetFraction; return 1;
	case TAG_MPEG4_CoordinateInterpolator: 
	case TAG_X3D_CoordinateInterpolator: 
		((M_CoordinateInterpolator *)node)->on_set_fraction = CoordInt_SetFraction; return 1;
	case TAG_MPEG4_CoordinateInterpolator2D: 
		((M_CoordinateInterpolator2D *)node)->on_set_fraction = CI2D_SetFraction; return 1;
	case TAG_MPEG4_NormalInterpolator: 
	case TAG_X3D_NormalInterpolator: 
		((M_NormalInterpolator*)node)->on_set_fraction = NormInt_SetFraction; return 1;
	case TAG_MPEG4_OrientationInterpolator: 
	case TAG_X3D_OrientationInterpolator: 
		((M_OrientationInterpolator*)node)->on_set_fraction = OrientInt_SetFraction; return 1;
	case TAG_MPEG4_PositionInterpolator: 
	case TAG_X3D_PositionInterpolator: 
		((M_PositionInterpolator *)node)->on_set_fraction = PosInt_SetFraction; return 1;
	case TAG_MPEG4_PositionInterpolator2D:
	case TAG_X3D_PositionInterpolator2D:
		((M_PositionInterpolator2D *)node)->on_set_fraction = PosInt2D_SetFraction; return 1;
	case TAG_MPEG4_ScalarInterpolator: 
	case TAG_X3D_ScalarInterpolator: 
		((M_ScalarInterpolator *)node)->on_set_fraction = ScalarInt_SetFraction; return 1;
	case TAG_MPEG4_Valuator:
		((M_Valuator *)node)->on_inSFTime = Valuator_SetInSFTime;
		((M_Valuator *)node)->on_inSFBool = Valuator_SetInSFBool;
		((M_Valuator *)node)->on_inSFColor = Valuator_SetInSFColor;
		((M_Valuator *)node)->on_inSFInt32 = Valuator_SetInSFInt32;
		((M_Valuator *)node)->on_inSFFloat = Valuator_SetInSFFloat;
		((M_Valuator *)node)->on_inSFVec2f = Valuator_SetInSFVec2f;
		((M_Valuator *)node)->on_inSFVec3f = Valuator_SetInSFVec3f;
		((M_Valuator *)node)->on_inSFRotation = Valuator_SetInSFRotation;
		((M_Valuator *)node)->on_inSFString = Valuator_SetInSFString;
		((M_Valuator *)node)->on_inMFColor = Valuator_SetInMFColor;
		((M_Valuator *)node)->on_inMFInt32 = Valuator_SetInMFInt32;
		((M_Valuator *)node)->on_inMFFloat = Valuator_SetInMFFloat;
		((M_Valuator *)node)->on_inMFVec2f = Valuator_SetInMFVec2f;
		((M_Valuator *)node)->on_inMFVec3f = Valuator_SetInMFVec3f;
		((M_Valuator *)node)->on_inMFRotation = Valuator_SetInMFRotation;
		((M_Valuator *)node)->on_inMFString = Valuator_SetInMFString;
		return 1;
	case TAG_MPEG4_PositionAnimator: PA_Init(node); return 1;
	case TAG_MPEG4_PositionAnimator2D: PA2D_Init(node); return 1;
	case TAG_MPEG4_ScalarAnimator: SA_Init(node); return 1;
	case TAG_MPEG4_PositionInterpolator4D: ((M_PositionInterpolator4D *)node)->on_set_fraction = PI4D_SetFraction; return 1;
	case TAG_MPEG4_CoordinateInterpolator4D: ((M_CoordinateInterpolator4D *)node)->on_set_fraction = CI4D_SetFraction; return 1;
	case TAG_MPEG4_Script: return 1;
	case TAG_X3D_Script: return 1;

	case TAG_X3D_BooleanFilter: InitBooleanFilter(node); return 1;
	case TAG_X3D_BooleanSequencer: InitBooleanSequencer(node); return 1;
	case TAG_X3D_BooleanToggle: InitBooleanToggle(node); return 1;
	case TAG_X3D_BooleanTrigger: InitBooleanTrigger(node); return 1;
	case TAG_X3D_IntegerSequencer: InitIntegerSequencer(node); return 1;
	case TAG_X3D_IntegerTrigger: InitIntegerTrigger(node); return 1;
	case TAG_X3D_TimeTrigger: InitTimeTrigger(node); return 1;
	}
	return 0;
}

Bool gf_sg_vrml_node_changed(GF_Node *node, GF_FieldInfo *field)
{
	switch (node->sgprivate->tag) {
	case TAG_ProtoNode:
		/*hardcoded protos need modification notifs*/
		if (node->sgprivate->RenderNode) return 0;
	case TAG_MPEG4_ColorInterpolator: 
	case TAG_X3D_ColorInterpolator:
	case TAG_MPEG4_CoordinateInterpolator: 
	case TAG_X3D_CoordinateInterpolator: 
	case TAG_MPEG4_CoordinateInterpolator2D: 
	case TAG_MPEG4_NormalInterpolator: 
	case TAG_X3D_NormalInterpolator: 
	case TAG_MPEG4_OrientationInterpolator: 
	case TAG_X3D_OrientationInterpolator: 
	case TAG_MPEG4_PositionInterpolator: 
	case TAG_X3D_PositionInterpolator: 
	case TAG_MPEG4_PositionInterpolator2D: 
	case TAG_MPEG4_ScalarInterpolator: 
	case TAG_X3D_ScalarInterpolator: 
	case TAG_MPEG4_Valuator:
	case TAG_MPEG4_PositionInterpolator4D:
	case TAG_MPEG4_CoordinateInterpolator4D:
	case TAG_MPEG4_Script:
	case TAG_X3D_Script:
	case TAG_X3D_BooleanFilter:
	case TAG_X3D_BooleanSequencer:
	case TAG_X3D_BooleanToggle:
	case TAG_X3D_BooleanTrigger:
	case TAG_X3D_IntegerSequencer:
	case TAG_X3D_IntegerTrigger:
	case TAG_X3D_TimeTrigger:
		return 1;
	case TAG_MPEG4_PositionAnimator: PA_Modified(node, field); return 1;
	case TAG_MPEG4_PositionAnimator2D: PA2D_Modified(node, field); return 1;
	case TAG_MPEG4_ScalarAnimator: SA_Modified(node, field); return 1;
	}
	return 0;
}


