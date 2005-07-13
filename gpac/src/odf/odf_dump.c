/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Jean Le Feuvre 2000-2005 
 *					All rights reserved
 *
 *  This file is part of GPAC / MPEG-4 ObjectDescriptor sub-project
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

#include <gpac/internal/odf_dev.h>
#include <gpac/constants.h>
/*for import flags*/
#include <gpac/media_tools.h>

#define OD_MAX_TREE		100

#define OD_FORMAT_INDENT( ind_buf, indent ) \
	{ \
		u32 z;	\
		assert(OD_MAX_TREE>indent);	\
		for (z=0; z<indent; z++) ind_buf[z] = ' '; \
		ind_buf[z] = 0; \
	} \

GF_Err gf_odf_dump_com(void *p, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_ODCom *com = (GF_ODCom *)p;

	switch (com->tag) {
	case GF_ODF_OD_UPDATE_TAG:
		return gf_odf_dump_od_update((GF_ODUpdate *)com, trace, indent, XMTDump);
	case GF_ODF_OD_REMOVE_TAG:
		return gf_odf_dump_od_remove((GF_ODRemove *)com, trace, indent, XMTDump);
	case GF_ODF_ESD_UPDATE_TAG:
		return gf_odf_dump_esd_update((GF_ESDUpdate *)com, trace, indent, XMTDump);
	case GF_ODF_ESD_REMOVE_TAG:
		return gf_odf_dump_esd_remove((GF_ESDRemove *)com, trace, indent, XMTDump);
	case GF_ODF_IPMP_UPDATE_TAG:
		return gf_odf_dump_ipmp_update((GF_IPMPUpdate *)com, trace, indent, XMTDump);
	case GF_ODF_IPMP_REMOVE_TAG:
		return gf_odf_dump_ipmp_remove((GF_IPMPRemove *)com, trace, indent, XMTDump);
	default:
		return gf_odf_dump_base_command((GF_BaseODCom *) com, trace, indent, XMTDump);
	}
}


GF_Err gf_odf_dump_au(char *data, u32 dataLength, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_ODCom *com;
	GF_ODCodec *odread = gf_odf_codec_new();
	gf_odf_codec_set_au(odread, data, dataLength);
	gf_odf_codec_decode(odread);

	while (1) {
		com = gf_odf_codec_get_com(odread);
		if (!com) break;

		gf_odf_dump_com(com, trace, indent, XMTDump);
		gf_odf_com_del(&com);
	}
	gf_odf_codec_del(odread);
	return GF_OK;
}

GF_Err gf_odf_dump_com_list(GF_List *commandList, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_ODCom *com;
	u32 i;
	for (i=0; i<gf_list_count(commandList); i++) {
		com = gf_list_get(commandList, i);
		gf_odf_dump_com(com, trace, indent, XMTDump);
	}
	return GF_OK;
}


GF_Err gf_odf_dump_desc(void *ptr, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_Descriptor *desc = (GF_Descriptor *)ptr;

	switch (desc->tag) {
	case GF_ODF_IOD_TAG :
		return gf_odf_dump_iod((GF_InitialObjectDescriptor *)desc, trace, indent, XMTDump);
	case GF_ODF_ESD_TAG :
		return gf_odf_dump_esd((GF_ESD *)desc, trace, indent, XMTDump);
	case GF_ODF_DCD_TAG :
		return gf_odf_dump_dcd((GF_DecoderConfig *)desc, trace, indent, XMTDump);
	case GF_ODF_SLC_TAG:
		return gf_odf_dump_slc((GF_SLConfig *)desc, trace, indent, XMTDump);
	case GF_ODF_CC_TAG:
		return gf_odf_dump_cc((GF_CCDescriptor *)desc, trace, indent, XMTDump);
	case GF_ODF_CC_DATE_TAG:
		return gf_odf_dump_cc_date((GF_CC_Date *)desc, trace, indent, XMTDump);
	case GF_ODF_CC_NAME_TAG:
		return gf_odf_dump_cc_name((GF_CC_Name *)desc, trace, indent, XMTDump);
	case GF_ODF_CI_TAG:
		return gf_odf_dump_ci((GF_CIDesc *)desc, trace, indent, XMTDump);
	case GF_ODF_ESD_INC_TAG:
		return gf_odf_dump_esd_inc((GF_ES_ID_Inc *)desc, trace, indent, XMTDump);
	case GF_ODF_ESD_REF_TAG:
		return gf_odf_dump_esd_ref((GF_ES_ID_Ref *)desc, trace, indent, XMTDump);
	case GF_ODF_TEXT_TAG:
		return gf_odf_dump_exp_text((GF_ExpandedTextual *)desc, trace, indent, XMTDump);
	case GF_ODF_EXT_PL_TAG:
		return gf_odf_dump_pl_ext((GF_PLExt *)desc, trace, indent, XMTDump);
	case GF_ODF_IPI_PTR_TAG:
	case GF_ODF_ISOM_IPI_PTR_TAG:
		return gf_odf_dump_ipi_ptr((GF_IPIPtr *)desc, trace, indent, XMTDump);
	case GF_ODF_IPMP_TAG:
		return gf_odf_dump_ipmp((GF_IPMP_Descriptor *)desc, trace, indent, XMTDump);
	case GF_ODF_IPMP_PTR_TAG:
		return gf_odf_dump_ipmp_ptr((GF_IPMPPtr *)desc, trace, indent, XMTDump);
	case GF_ODF_KW_TAG:
		return gf_odf_dump_kw((GF_KeyWord *)desc, trace, indent, XMTDump);
	case GF_ODF_LANG_TAG:
		return gf_odf_dump_lang((GF_Language *)desc, trace, indent, XMTDump);
	case GF_ODF_ISOM_IOD_TAG:
		return gf_odf_dump_isom_iod((GF_IsomInitialObjectDescriptor *)desc, trace, indent, XMTDump);
	case GF_ODF_ISOM_OD_TAG:
		return gf_odf_dump_isom_od((GF_IsomObjectDescriptor *)desc, trace, indent, XMTDump);
	case GF_ODF_OD_TAG:
		return gf_odf_dump_od((GF_ObjectDescriptor *)desc, trace, indent, XMTDump);
	case GF_ODF_OCI_DATE_TAG:
		return gf_odf_dump_oci_date((GF_OCI_Data *)desc, trace, indent, XMTDump);
	case GF_ODF_OCI_NAME_TAG:
		return gf_odf_dump_oci_name((GF_OCICreators *)desc, trace, indent, XMTDump);
	case GF_ODF_PL_IDX_TAG:
		return gf_odf_dump_pl_idx((GF_PL_IDX *)desc, trace, indent, XMTDump);
	case GF_ODF_QOS_TAG:
		return gf_odf_dump_qos((GF_QoS_Descriptor *)desc, trace, indent, XMTDump);
	case GF_ODF_RATING_TAG:
		return gf_odf_dump_rating((GF_Rating *)desc, trace, indent, XMTDump);
	case GF_ODF_REG_TAG:
		return gf_odf_dump_reg((GF_Registration *)desc, trace, indent, XMTDump);
	case GF_ODF_SHORT_TEXT_TAG:
		return gf_odf_dump_short_text((GF_ShortTextual *)desc, trace, indent, XMTDump);
	case GF_ODF_SMPTE_TAG:
		return gf_odf_dump_smpte_camera((GF_SMPTECamera *)desc, trace, indent, XMTDump);
	case GF_ODF_SCI_TAG:
		return gf_odf_dump_sup_cid((GF_SCIDesc *)desc, trace, indent, XMTDump);

	case GF_ODF_SEGMENT_TAG:
		return gf_odf_dump_segment((GF_Segment *)desc, trace, indent, XMTDump);
	case GF_ODF_MEDIATIME_TAG:
		return gf_odf_dump_mediatime((GF_MediaTime *)desc, trace, indent, XMTDump);

	case GF_ODF_MUXINFO_TAG:
		return gf_odf_dump_muxinfo((GF_MuxInfo *)desc, trace, indent, XMTDump);
	case GF_ODF_BIFS_CFG_TAG:
		return gf_odf_dump_bifs_cfg((GF_BIFSConfig *)desc, trace, indent, XMTDump);
	case GF_ODF_UI_CFG_TAG:
		return gf_odf_dump_ui_cfg((GF_UIConfig *)desc, trace, indent, XMTDump);
	case GF_ODF_IPMP_TL_TAG:
		return gf_odf_dump_ipmp_tool_list((GF_IPMP_ToolList*)desc, trace, indent, XMTDump);
	case GF_ODF_IPMP_TOOL_TAG:
		return gf_odf_dump_ipmp_tool((GF_IPMP_Tool*)desc, trace, indent, XMTDump);
	default:
		return gf_odf_dump_default((GF_DefaultDescriptor *)desc, trace, indent, XMTDump);
	}
	return GF_OK;
}



static void StartDescDump(FILE *trace, char *descName, u32 indent, Bool XMTDump)
{
	char ind_buf[OD_MAX_TREE];
	OD_FORMAT_INDENT(ind_buf, indent);

	if (!XMTDump) {
		fprintf(trace, "%s {\n", descName);
	} else {
		fprintf(trace, "%s<%s ", ind_buf, descName);
	}
}

static void EndDescDump(FILE *trace, char *descName, u32 indent, Bool XMTDump)
{
	char ind_buf[OD_MAX_TREE];
	OD_FORMAT_INDENT(ind_buf, indent);

	if (!XMTDump) {
		fprintf(trace, "%s}\n", ind_buf);
	} else {
		fprintf(trace, "%s</%s>\n", ind_buf, descName);
	}
}

/*special element open for XML only, appends "<eltName " - used because XMT-A OD representations use lots of 
subdescs not present in BT*/
static void StartSubElement(FILE *trace, char *eltName, u32 indent, Bool XMTDump)
{
	if (XMTDump) {
		char ind_buf[OD_MAX_TREE];
		OD_FORMAT_INDENT(ind_buf, indent);
		fprintf(trace, "%s<%s ", ind_buf, eltName);
	}
}
/*special close for XML only, appends "/>" - used because XMT-A OD representations use lots of 
subdescs not present in BT*/
static void EndSubElement(FILE *trace, u32 indent, Bool XMTDump)
{
	if (XMTDump) fprintf(trace, "/>\n");
}

static void EndAttributes(FILE *trace, u32 indent, Bool XMTDump)
{
	if (XMTDump) fprintf(trace, ">\n");
}

static void StartElement(FILE *trace, char *attName, u32 indent, Bool XMTDump, Bool IsList)
{
	char ind_buf[OD_MAX_TREE];
	OD_FORMAT_INDENT(ind_buf, indent);
	if (!XMTDump) {
		if (IsList) 
			fprintf(trace, "%s%s [\n", ind_buf, attName);
		else
			fprintf(trace, "%s%s ", ind_buf, attName);
	} else {
		fprintf(trace, "%s<%s>\n", ind_buf, attName);
	}
}

static void EndElement(FILE *trace, char *attName, u32 indent, Bool XMTDump, Bool IsList)
{
	char ind_buf[OD_MAX_TREE];
	OD_FORMAT_INDENT(ind_buf, indent);
	if (!XMTDump) {
		if (IsList) fprintf(trace, "%s]\n", ind_buf);
	} else {
		fprintf(trace, "%s</%s>\n", ind_buf, attName);
	}
}

static void StartAttribute(FILE *trace, char *attName, u32 indent, Bool XMTDump)
{
	char ind_buf[OD_MAX_TREE];
	OD_FORMAT_INDENT(ind_buf, indent);
	if (!XMTDump) {
		fprintf(trace, "%s%s ", ind_buf, attName);
	} else {
		fprintf(trace, "%s=\"", attName);
	}
}
static void EndAttribute(FILE *trace, u32 indent, Bool XMTDump)
{
	if (!XMTDump) {
		fprintf(trace, "\n");
	} else {
		fprintf(trace, "\" ");
	}
}

static void DumpInt(FILE *trace, char *attName, u32  val, u32 indent, Bool XMTDump)
{
	if (!val) return;
	StartAttribute(trace, attName, indent, XMTDump);
	fprintf(trace, "%d", val);
	EndAttribute(trace, indent, XMTDump);
}

static void DumpIntHex(FILE *trace, char *attName, u32  val, u32 indent, Bool XMTDump, Bool single_byte)
{
	StartAttribute(trace, attName, indent, XMTDump);
	if (single_byte) {
		fprintf(trace, "0x%02X", val);
	} else {
		fprintf(trace, "0x%04X", val);
	}
	EndAttribute(trace, indent, XMTDump);
}

static void DumpFloat(FILE *trace, char *attName, Float val, u32 indent, Bool XMTDump)
{
	StartAttribute(trace, attName, indent, XMTDump);
	fprintf(trace, "%g", val);
	EndAttribute(trace, indent, XMTDump);
}

static void DumpDouble(FILE *trace, char *attName, Double val, u32 indent, Bool XMTDump)
{
	StartAttribute(trace, attName, indent, XMTDump);
	fprintf(trace, "%g", val);
	EndAttribute(trace, indent, XMTDump);
}

static void DumpBool(FILE *trace, char *attName, u32  val, u32 indent, Bool XMTDump)
{
	if (!val) return;

	StartAttribute(trace, attName, indent, XMTDump);
	fprintf(trace, "%s", val ? "true" : "false");
	EndAttribute(trace, indent, XMTDump);
}

static void DumpString(FILE *trace, char *attName, char *val, u32 indent, Bool XMTDump)
{
	if (!val) return;
	StartAttribute(trace, attName, indent, XMTDump);
	if (!XMTDump) fprintf(trace, "\"");
	fprintf(trace, "%s", val);
	if (!XMTDump) fprintf(trace, "\"");
	EndAttribute(trace, indent, XMTDump);
}

static void DumpData(FILE *trace, char *name, char *data, u32 dataLength, u32 indent, Bool XMTDump)
{
	u32 i;
	if (!name ||!data) return;
	StartAttribute(trace, name, indent, XMTDump);
	if (XMTDump) fprintf(trace, "data:application/octet-string,");
	for (i=0; i<dataLength; i++) {
		fprintf(trace, "%%");
		fprintf(trace, "%02X", (unsigned char) data[i]);
	}
	EndAttribute(trace, indent, XMTDump);
}
static void DumpBin128(FILE *trace, char *name, char *data, u32 indent, Bool XMTDump)
{
	u32 i;
	if (!name ||!data) return;
	StartAttribute(trace, name, indent, XMTDump);
	fprintf(trace, "0x");
	i=0;
	while (!data[i] && (i<16)) i++;
	if (i==16) {
		fprintf(trace, "00");
	} else {
		for (; i<16; i++) fprintf(trace, "%02X", (unsigned char) data[i]);
	}
	EndAttribute(trace, indent, XMTDump);
}


GF_Err DumpDescList(GF_List *list, FILE *trace, u32 indent, char *ListName, Bool XMTDump)
{
	u32 i, count;
	GF_Descriptor *desc;
	char ind_buf[OD_MAX_TREE];
	if (!list) return GF_OK;
	count = gf_list_count(list);
	if (!count) return GF_OK;
	StartElement(trace, ListName, indent, XMTDump, 1);
	indent++;
	OD_FORMAT_INDENT(ind_buf, indent);
	for (i=0; i<count; i++) {
		desc = gf_list_get(list, i);
		//add offset if not XMT
		if (!XMTDump) fprintf(trace, "%s", ind_buf);
		gf_odf_dump_desc(desc, trace, indent, XMTDump);
	}
	indent--;
	EndElement(trace, ListName, indent, XMTDump, 1);
	return GF_OK;
}


GF_Err DumpDescListFilter(GF_List *list, FILE *trace, u32 indent, char *ListName, Bool XMTDump, u8 tag_only)
{
	u32 i, count, num_desc;
	GF_Descriptor *desc;
	char ind_buf[OD_MAX_TREE];
	if (!list) return GF_OK;

	count = gf_list_count(list);
	num_desc = 0;
	for (i=0; i<count; i++) {
		desc = gf_list_get(list, i);
		if (desc->tag == tag_only) num_desc++;
	}
	if (!num_desc) return GF_OK;
	
	StartElement(trace, ListName, indent, XMTDump, 1);
	indent++;
	OD_FORMAT_INDENT(ind_buf, indent);
	for (i=0; i<count; i++) {
		desc = gf_list_get(list, i);
		if (desc->tag == tag_only) {
			//add offset if not XMT
			if (!XMTDump) fprintf(trace, "%s", ind_buf);
			gf_odf_dump_desc(desc, trace, indent, XMTDump);
		}
	}
	indent--;
	EndElement(trace, ListName, indent, XMTDump, 1);
	return GF_OK;
}

GF_Err gf_odf_dump_iod(GF_InitialObjectDescriptor *iod, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "InitialObjectDescriptor", indent, XMTDump);
	indent++;

	StartAttribute(trace, "objectDescriptorID", indent, XMTDump);
	if (XMTDump) {
		fprintf(trace, "od%d", iod->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
		DumpInt(trace, "binaryID", iod->objectDescriptorID, indent, XMTDump);
	} else {
		fprintf(trace, "%d", iod->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
	}

	EndAttributes(trace, indent, XMTDump);

	StartSubElement(trace, "Profiles", indent, XMTDump);

	DumpInt(trace, "audioProfileLevelIndication", iod->audio_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "visualProfileLevelIndication", iod->visual_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "sceneProfileLevelIndication", iod->scene_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "graphicsProfileLevelIndication", iod->graphics_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "ODProfileLevelIndication", iod->OD_profileAndLevel, indent, XMTDump);
	DumpBool(trace, "includeInlineProfileLevelFlag", iod->inlineProfileFlag, indent, XMTDump);

	EndSubElement(trace, indent, XMTDump);

	if (iod->URLString) {
		StartSubElement(trace, "URL", indent, XMTDump);
		DumpString(trace, "URLstring", iod->URLString, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
		
	if (XMTDump) {
		StartElement(trace, "Descr", indent, XMTDump, 1);
		indent++;
	}
	//ESDescr
	DumpDescList(iod->ESDescriptors, trace, indent, "esDescr", XMTDump);
	DumpDescList(iod->OCIDescriptors, trace, indent, "ociDescr", XMTDump);
	DumpDescListFilter(iod->IPMP_Descriptors, trace, indent, "ipmpDescrPtr", XMTDump, GF_ODF_IPMP_PTR_TAG);
	DumpDescListFilter(iod->IPMP_Descriptors, trace, indent, "ipmpDescr", XMTDump, GF_ODF_IPMP_TAG);

	DumpDescList(iod->extensionDescriptors, trace, indent, "extDescr", XMTDump);

	if (iod->IPMPToolList) {
		StartElement(trace, "toolListDescr" , indent, XMTDump, 0);
		gf_odf_dump_desc(iod->IPMPToolList, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "toolListDescr" , indent, XMTDump, 0);
	}
	
	if (XMTDump) {
		indent--;
		EndElement(trace, "Descr", indent, XMTDump, 1);
	}
	indent--;
	EndDescDump(trace, "InitialObjectDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_esd(GF_ESD *esd, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_MuxInfo *mi;
	u32 i;
	StartDescDump(trace, "ES_Descriptor", indent, XMTDump);
	indent++;

	StartAttribute(trace, "ES_ID", indent, XMTDump);
	if (XMTDump) {
		fprintf(trace, "es%d", esd->ESID);
		EndAttribute(trace, indent, XMTDump);
		DumpInt(trace, "binaryID", esd->ESID, indent, XMTDump);
	} else {
		fprintf(trace, "%d", esd->ESID);
		EndAttribute(trace, indent, XMTDump);
	}
	DumpInt(trace, "streamPriority", esd->streamPriority, indent, XMTDump);

	if (XMTDump) {
		if (esd->dependsOnESID) {
			StartAttribute(trace, "dependsOn_ES_ID", indent, XMTDump);
			fprintf(trace, "es%d", esd->dependsOnESID);
			EndAttribute(trace, indent, XMTDump);
		}
	
		if (esd->OCRESID) {
			StartAttribute(trace, "OCR_ES_ID", indent, XMTDump);
			fprintf(trace, "es%d", esd->OCRESID);
			EndAttribute(trace, indent, XMTDump);
		}
	} else {
		if (esd->dependsOnESID) DumpInt(trace, "dependsOn_ES_ID", esd->dependsOnESID, indent, XMTDump);
		if (esd->OCRESID) DumpInt(trace, "OCR_ES_ID", esd->OCRESID, indent, XMTDump);
	}

	EndAttributes(trace, indent, XMTDump);

	if (esd->URLString) {
		StartSubElement(trace, "URL", indent, XMTDump);
		DumpString(trace, "URLstring", esd->URLString, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	if (esd->decoderConfig) {
		StartElement(trace, "decConfigDescr" , indent, XMTDump, 0);
		gf_odf_dump_desc(esd->decoderConfig, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "decConfigDescr" , indent, XMTDump, 0);
	}
	if (esd->slConfig) {
		StartElement(trace, "slConfigDescr" , indent, XMTDump, 0);
		gf_odf_dump_desc(esd->slConfig, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "slConfigDescr" , indent, XMTDump, 0);
	}
	if (esd->ipiPtr) {
		StartElement(trace, "ipiPtr" , indent, XMTDump, 0);
		gf_odf_dump_desc(esd->ipiPtr, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "ipiPtr" , indent, XMTDump, 0);
	}

	DumpDescList(esd->IPIDataSet, trace, indent, "ipIDS", XMTDump);
	DumpDescList(esd->IPMPDescriptorPointers, trace, indent, "ipmpDescrPtr", XMTDump);

	if (esd->qos) {
		StartElement(trace, "qosDescr" , indent, XMTDump, 0);
		gf_odf_dump_desc(esd->qos, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "qosDescr" , indent, XMTDump, 0);
	}
	if (esd->langDesc) {
		StartElement(trace, "langDescr" , indent, XMTDump, 0);
		gf_odf_dump_desc(esd->langDesc, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "langDescr" , indent, XMTDump, 0);
	}

	if (esd->RegDescriptor) {
		StartElement(trace, "regDescr" , indent, XMTDump, 0);
		gf_odf_dump_desc(esd->RegDescriptor, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "regDescr" , indent, XMTDump, 0);
	}

	mi = NULL;
	for (i=0; i<gf_list_count(esd->extensionDescriptors); i++) {
		mi = gf_list_get(esd->extensionDescriptors, i);
		if (mi->tag == GF_ODF_MUXINFO_TAG) {
			gf_list_rem(esd->extensionDescriptors, i);
			break;
		}
		mi = NULL;
	}

	DumpDescList(esd->extensionDescriptors, trace, indent, "extDescr", XMTDump);

	if (mi) {
		gf_list_insert(esd->extensionDescriptors, mi, i);
		if (XMTDump) {
			gf_odf_dump_desc(mi, trace, indent, 1);
		} else {
			StartElement(trace, "muxInfo" , indent, 0, 0);
			gf_odf_dump_desc(mi, trace, indent, 0);
			EndElement(trace, "muxInfo" , indent, 0, 0);
		}
	}

	indent--;
	EndDescDump(trace, "ES_Descriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_bifs_cfg(GF_BIFSConfig *dsi, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, (dsi->version==1) ? "BIFSConfig" : "BIFSv2Config", indent, XMTDump);
	indent++;
	
	if (dsi->version==2) {
		DumpBool(trace, "use3DMeshCoding", 0, indent, XMTDump);
		DumpBool(trace, "usePredictiveMFField", 0, indent, XMTDump);
	}
	DumpInt(trace, "nodeIDbits", dsi->nodeIDbits, indent, XMTDump);
	DumpInt(trace, "routeIDbits", dsi->routeIDbits, indent, XMTDump);
	if (dsi->version==2) DumpInt(trace, "protoIDbits", dsi->protoIDbits, indent, XMTDump);
	if (!dsi->isCommandStream) {
		EndAttributes(trace, indent, XMTDump);
		indent--;
		EndDescDump(trace, (dsi->version==1) ? "BIFSConfig" : "BIFSv2Config", indent, XMTDump);
		return GF_NOT_SUPPORTED;
	}
	if (XMTDump) {
		EndAttributes(trace, indent, XMTDump);
		indent++;
		StartDescDump(trace, "commandStream" , indent, XMTDump);
		DumpBool(trace, "pixelMetric", dsi->pixelMetrics, indent, XMTDump);
		if (XMTDump) EndAttributes(trace, indent, XMTDump);
	} else {
		DumpBool(trace, "isCommandStream", 1, indent, XMTDump);
		DumpBool(trace, "pixelMetric", dsi->pixelMetrics, indent, XMTDump);
	}
	if (dsi->pixelWidth && dsi->pixelHeight) {
		if (XMTDump) {
			indent++;
			StartDescDump(trace, "size" , indent, XMTDump);
		}

		DumpInt(trace, "pixelWidth", dsi->pixelWidth, indent, XMTDump);
		DumpInt(trace, "pixelHeight", dsi->pixelHeight, indent, XMTDump);
		if (XMTDump) {
			EndSubElement(trace, indent, XMTDump);
			indent--;
		}
	}

	if (XMTDump) {
		EndDescDump(trace, "commandStream", indent, XMTDump);
		indent--;
	}
	indent--;
	EndDescDump(trace, (dsi->version==1) ? "BIFSConfig" : "BIFSv2Config", indent, XMTDump);
	return GF_OK;
}

GF_Err DumpRawBIFSConfig(GF_DefaultDescriptor *dsi, FILE *trace, u32 indent, Bool XMTDump, u32 oti)
{
	GF_BitStream *bs;
	u32 flag;

	bs = gf_bs_new(dsi->data, dsi->dataLength, GF_BITSTREAM_READ);

	StartDescDump(trace, (oti==1) ? "BIFSConfig" : "BIFSv2Config", indent, XMTDump);
	indent++;
	
	if (oti==2) {
		DumpBool(trace, "use3DMeshCoding", gf_bs_read_int(bs, 1), indent, XMTDump);
		DumpBool(trace, "usePredictiveMFField", gf_bs_read_int(bs, 1), indent, XMTDump);
	}
	DumpInt(trace, "nodeIDbits", gf_bs_read_int(bs, 5), indent, XMTDump);
	DumpInt(trace, "routeIDbits", gf_bs_read_int(bs, 5), indent, XMTDump);

	if (oti==2) 
		DumpInt(trace, "protoIDbits", gf_bs_read_int(bs, 5), indent, XMTDump);

	flag = gf_bs_read_int(bs, 1);
	if (!flag) {
		gf_bs_del(bs);
		return GF_NOT_SUPPORTED;
	}

	if (XMTDump) {
		EndAttributes(trace, indent, XMTDump);
		indent++;
		StartDescDump(trace, "commandStream" , indent, XMTDump);
		DumpBool(trace, "pixelMetric", gf_bs_read_int(bs, 1), indent, XMTDump);
		if (XMTDump) EndAttributes(trace, indent, XMTDump);
	} else {
		DumpBool(trace, "isCommandStream", 1, indent, XMTDump);
		DumpBool(trace, "pixelMetric", gf_bs_read_int(bs, 1), indent, XMTDump);
	}

	if (gf_bs_read_int(bs, 1)) {

		if (XMTDump) {
			indent++;
			StartDescDump(trace, "size" , indent, XMTDump);
		}

		DumpInt(trace, "pixelWidth", gf_bs_read_int(bs, 16), indent, XMTDump);
		DumpInt(trace, "pixelHeight", gf_bs_read_int(bs, 16), indent, XMTDump);
		if (XMTDump) {
			EndSubElement(trace, indent, XMTDump);
			indent--;
		}

	}

	if (XMTDump) {
		EndDescDump(trace, "commandStream", indent, XMTDump);
		indent--;
	}
	indent--;
	EndDescDump(trace, (oti==1) ? "BIFSConfig" : "BIFSv2Config", indent, XMTDump);

	gf_bs_del(bs);
	return GF_OK;
}

GF_Err gf_odf_dump_ui_cfg(GF_UIConfig *uid, FILE *trace, u32 indent, Bool XMTDump)
{
	char devName[255];
	u32 i;

	StartDescDump(trace, "UIConfig" , indent, XMTDump);
	indent++;
	DumpString(trace, "deviceName", uid->deviceName, indent, XMTDump);
	
	if (!stricmp(devName, "StringSensor") && uid->termChar) {
		devName[0] = uid->termChar;
		devName[1] = 0;
		DumpString(trace, "termChar", devName, indent, XMTDump);
		devName[0] = uid->delChar;
		DumpString(trace, "delChar", devName, indent, XMTDump);
	}
	if (uid->ui_data_length) {
		if (!stricmp(uid->deviceName, "HTKSensor")) {
			u32 nb_word, nbPhone, c, j;
			GF_BitStream *bs = gf_bs_new(uid->ui_data, uid->ui_data_length, GF_BITSTREAM_READ);
			char szPh[3];
			StartAttribute(trace, "uiData", indent, XMTDump);
			if (!XMTDump) fprintf(trace, "\"");
			fprintf(trace, "HTK:");
			szPh[2] = 0;
			nb_word = gf_bs_read_int(bs, 8);
			for (i=0; i<nb_word; i++) {
				nbPhone = gf_bs_read_int(bs, 8);
				if (i) fprintf(trace, ";");
				while ((c=gf_bs_read_int(bs, 8))) fprintf(trace, "%c", c);
				fprintf(trace, " ");
				for (j=0; j<nbPhone; j++) {
					gf_bs_read_data(bs, szPh, 2);
					if (j) fprintf(trace, " ");
					if (!stricmp(szPh, "vc")) fprintf(trace, "vcl");
					else fprintf(trace, "%s", szPh);
				}
			}
			if (!XMTDump) fprintf(trace, "\"");
			EndAttribute(trace, indent, XMTDump);
			gf_bs_del(bs);
		} else {
			DumpData(trace, "uiData", uid->ui_data, uid->ui_data_length, indent, XMTDump);
		}
	}

	indent--;
	EndAttributes(trace, indent, XMTDump);
	EndDescDump(trace, "UIConfig", indent, XMTDump);
	return GF_OK;
}

GF_Err DumpRawUIConfig(GF_DefaultDescriptor *dsi, FILE *trace, u32 indent, Bool XMTDump, u32 oti)
{
	char devName[255];
	u32 i, len;
	GF_BitStream *bs;

	bs = gf_bs_new(dsi->data, dsi->dataLength, GF_BITSTREAM_READ);

	StartDescDump(trace, "UIConfig" , indent, XMTDump);
	indent++;
	len = gf_bs_read_int(bs, 8);
	for (i=0; i<len; i++) devName[i] = gf_bs_read_int(bs, 8);
	devName[i] = 0;
	DumpString(trace, "deviceName", devName, indent, XMTDump);
	
	if (!stricmp(devName, "StringSensor") && gf_bs_available(bs)) {
		devName[0] = gf_bs_read_int(bs, 8);
		devName[1] = 0;
		DumpString(trace, "termChar", devName, indent, XMTDump);
		devName[0] = gf_bs_read_int(bs, 8);
		DumpString(trace, "delChar", devName, indent, XMTDump);
	}
	len = (u32) gf_bs_available(bs);
	if (len) {
		if (!stricmp(devName, "HTKSensor")) {
			u32 nb_word, nbPhone, c, j;
			char szPh[3];
			StartAttribute(trace, "uiData", indent, XMTDump);
			if (!XMTDump) fprintf(trace, "\"");
			fprintf(trace, "HTK:");
			szPh[2] = 0;
			nb_word = gf_bs_read_int(bs, 8);
			for (i=0; i<nb_word; i++) {
				nbPhone = gf_bs_read_int(bs, 8);
				if (i) fprintf(trace, ";");
				while ((c=gf_bs_read_int(bs, 8))) fprintf(trace, "%c", c);
				fprintf(trace, " ");
				for (j=0; j<nbPhone; j++) {
					gf_bs_read_data(bs, szPh, 2);
					if (j) fprintf(trace, " ");
					if (!stricmp(szPh, "vc")) fprintf(trace, "vcl");
					else fprintf(trace, "%s", szPh);
				}
			}
			if (!XMTDump) fprintf(trace, "\"");
			EndAttribute(trace, indent, XMTDump);
		} else {
			char *data = dsi->data;
			data += (u32) gf_bs_get_position(bs);
			DumpData(trace, "uiData", data, len, indent, XMTDump);
		}
	}

	indent--;
	EndAttributes(trace, indent, XMTDump);
	EndDescDump(trace, "UIConfig", indent, XMTDump);
	gf_bs_del(bs);
	return GF_OK;
}

GF_Err OD_DumpDSI(GF_DefaultDescriptor *dsi, FILE *trace, u32 indent, Bool XMTDump, u32 streamType, u32 oti)
{
	switch (streamType) {
	case GF_STREAM_SCENE:
		if (oti<=2) return DumpRawBIFSConfig(dsi, trace, indent, XMTDump, oti);
		return GF_OK;
	case GF_STREAM_INTERACT:
		return DumpRawUIConfig(dsi, trace, indent, XMTDump, oti);
	default:
		return gf_odf_dump_desc(dsi, trace, indent, XMTDump);
	}
}

GF_Err gf_odf_dump_dcd(GF_DecoderConfig *dcd, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "DecoderConfigDescriptor", indent, XMTDump);
	indent++;

	DumpInt(trace, "objectTypeIndication", dcd->objectTypeIndication, indent, XMTDump);
	DumpInt(trace, "streamType", dcd->streamType, indent, XMTDump);
	DumpInt(trace, "upStream", dcd->upstream, indent, XMTDump);
	DumpInt(trace, "bufferSizeDB", dcd->bufferSizeDB, indent, XMTDump);
	DumpInt(trace, "maxBitrate", dcd->maxBitrate, indent, XMTDump);
	DumpInt(trace, "avgBitrate", dcd->avgBitrate, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);

	if (dcd->decoderSpecificInfo) {
		if (dcd->decoderSpecificInfo->tag==GF_ODF_DSI_TAG) {
			if (dcd->decoderSpecificInfo->dataLength) {
				StartElement(trace, "decSpecificInfo" , indent, XMTDump, 0);
				OD_DumpDSI(dcd->decoderSpecificInfo, trace, indent + (XMTDump ? 1 : 0), XMTDump, dcd->streamType, dcd->objectTypeIndication);
				EndElement(trace, "decSpecificInfo" , indent, XMTDump, 0);
			}
		} else {
			StartElement(trace, "decSpecificInfo" , indent, XMTDump, 0);
			gf_odf_dump_desc(dcd->decoderSpecificInfo, trace, indent + (XMTDump ? 1 : 0), XMTDump);
			EndElement(trace, "decSpecificInfo" , indent, XMTDump, 0);
		}
	}
	DumpDescList(dcd->profileLevelIndicationIndexDescriptor, trace, indent, "profileLevelIndicationIndexDescr", XMTDump);
	indent--;
	EndDescDump(trace, "DecoderConfigDescriptor", indent, XMTDump);

	return GF_OK;
}

GF_Err gf_odf_dump_slc(GF_SLConfig *sl, FILE *trace, u32 indent, Bool XMTDump)
{

	StartDescDump(trace, "SLConfigDescriptor", indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	indent++;

	if (sl->predefined) {
		StartSubElement(trace, "predefined" , indent, XMTDump);
		DumpInt(trace, "value", sl->predefined, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	if (XMTDump) StartSubElement(trace, "custom" , indent, XMTDump);

	if (!sl->predefined) {
		DumpBool(trace, "useAccessUnitStartFlag", sl->useAccessUnitStartFlag, indent, XMTDump);
		DumpBool(trace, "useAccessUnitEndFlag", sl->useAccessUnitEndFlag, indent, XMTDump);
		DumpBool(trace, "useRandomAccessPointFlag", sl->useRandomAccessPointFlag, indent, XMTDump);
		DumpBool(trace, "usePaddingFlag", sl->usePaddingFlag, indent, XMTDump);
		if (!XMTDump) DumpBool(trace, "useTimeStampsFlag", sl->useTimestampsFlag, indent, XMTDump);
		DumpBool(trace, "useIdleFlag", sl->useIdleFlag, indent, XMTDump);
		if (!XMTDump) DumpBool(trace, "durationFlag", sl->durationFlag, indent, XMTDump);
		DumpInt(trace, "timeStampResolution", sl->timestampResolution, indent, XMTDump);
		DumpInt(trace, "OCRResolution", sl->OCRResolution, indent, XMTDump);
		DumpInt(trace, "timeStampLength", sl->timestampLength, indent, XMTDump);
		DumpInt(trace, "OCRLength", sl->OCRLength, indent, XMTDump);
		DumpInt(trace, "AU_Length", sl->AULength, indent, XMTDump);
		DumpInt(trace, "instantBitrateLength", sl->instantBitrateLength, indent, XMTDump);
		DumpInt(trace, "degradationPriorityLength", sl->degradationPriorityLength, indent, XMTDump);
		DumpInt(trace, "AU_SeqNumLength", sl->AUSeqNumLength, indent, XMTDump);
		DumpInt(trace, "packetSeqNumLength", sl->packetSeqNumLength, indent, XMTDump);
	}
	EndAttributes(trace, indent, XMTDump);

	indent++;
	if (sl->durationFlag) {
		StartSubElement(trace, "Duration" , indent, XMTDump);
		DumpInt(trace, "timescale", sl->timeScale, indent, XMTDump);
		DumpInt(trace, "accessUnitDuration", sl->AUDuration, indent, XMTDump);
		DumpInt(trace, "compositionUnitDuration", sl->CUDuration, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	if (! sl->useTimestampsFlag) {
		StartSubElement(trace, "noUseTimeStamps" , indent, XMTDump);
		DumpInt(trace, "startDecodingTimeStamp", (u32) sl->startDTS, indent, XMTDump);
		DumpInt(trace, "startCompositionTimeStamp", (u32) sl->startCTS, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	indent--;
	if (XMTDump) EndElement(trace, "custom" , indent, XMTDump, 1);

	indent--;
	EndDescDump(trace, "SLConfigDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_cc(GF_CCDescriptor *ccd, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ContentClassificationDescriptor", indent, XMTDump);
	DumpInt(trace, "classificationEntity", ccd->classificationEntity, indent, XMTDump);
	DumpInt(trace, "classificationTable", ccd->classificationTable, indent, XMTDump);
	DumpData(trace, "ccd->contentClassificationData", ccd->contentClassificationData, ccd->dataLength, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	EndDescDump(trace, "ContentClassificationDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_cc_date(GF_CC_Date *cdd, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ContentClassificationDescriptor", indent, XMTDump);
	DumpString(trace, "creationDate", cdd->contentCreationDate, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	EndDescDump(trace, "ContentClassificationDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_cc_name(GF_CC_Name *cnd, FILE *trace, u32 indent, Bool XMTDump)
{
	u32 i;
	GF_ContentCreatorInfo *p;

	StartDescDump(trace, "ContentCreatorNameDescriptor", indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	indent++;
	for (i=0; i<gf_list_count(cnd->ContentCreators); i++) {
		p = gf_list_get(cnd->ContentCreators, i);
		StartSubElement(trace, "Creator", indent, XMTDump);
		DumpInt(trace, "languageCode", p->langCode, indent, XMTDump);
		DumpBool(trace, "isUTF8", p->isUTF8, indent, XMTDump);
		DumpString(trace, "Name", p->contentCreatorName, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	indent--;
	EndDescDump(trace, "ContentCreatorNameDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_ci(GF_CIDesc *cid, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ContentIdentificationDescriptor", indent, XMTDump);
	DumpBool(trace, "protectedContent", cid->protectedContent, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	indent++;
	if (cid->contentTypeFlag) {
		StartSubElement(trace, "contentType", indent, XMTDump);
		DumpInt(trace, "contentType", cid->contentType, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	if (cid->contentIdentifierFlag) {
		StartSubElement(trace, "contentIdentifierType", indent, XMTDump);
		DumpInt(trace, "contentIdentifierType", cid->contentIdentifierType, indent, XMTDump);
		DumpString(trace, "contentIdentifier", cid->contentIdentifier, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}

	indent--;
	EndDescDump(trace, "ContentIdentificationDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_default(GF_DefaultDescriptor *dd, FILE *trace, u32 indent, Bool XMTDump)
{
	if (dd->tag == GF_ODF_DSI_TAG) {
		StartDescDump(trace, "DecoderSpecificInfo", indent, XMTDump);
		indent++;
		if (XMTDump) {
			DumpString(trace, "type", "auto", indent, XMTDump);
			DumpData(trace, "src", dd->data, dd->dataLength, indent, XMTDump);
		} else {
			DumpData(trace, "info", dd->data, dd->dataLength, indent, XMTDump);
		}
		indent--;
		if (XMTDump) {
			EndSubElement(trace, indent, 1);
		} else {
			EndDescDump(trace, "", indent, 0);
		}
	} else {
		StartDescDump(trace, "DefaultDescriptor", indent, XMTDump);
		indent++;
		DumpData(trace, "data", dd->data, dd->dataLength, indent, XMTDump);
		indent--;
		EndSubElement(trace, indent, XMTDump);
	}
	return GF_OK;
}

GF_Err gf_odf_dump_esd_inc(GF_ES_ID_Inc *esd_inc, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ES_ID_Inc", indent, XMTDump);
	indent++;
	DumpInt(trace, "trackID", esd_inc->trackID, indent, XMTDump);
	indent--;
	EndAttributes(trace, indent, XMTDump);
	EndDescDump(trace, "ES_ID_Inc", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_esd_ref(GF_ES_ID_Ref *esd_ref, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ES_ID_Ref", indent, XMTDump);
	indent++;
	DumpInt(trace, "trackRef", esd_ref->trackRef, indent, XMTDump);
	indent--;
	EndAttributes(trace, indent, XMTDump);
	EndDescDump(trace, "ES_ID_Ref", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_exp_text(GF_ExpandedTextual *etd, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_ETD_ItemText *it1, *it2;
	u32 i;

	StartDescDump(trace, "ExpandedTextualDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "languageCode", etd->langCode, indent, XMTDump);
	DumpBool(trace, "isUTF8", etd->isUTF8, indent, XMTDump);
	DumpString(trace, "nonItemText", etd->NonItemText, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	
	for (i=0; i<gf_list_count(etd->itemDescriptionList); i++) {
		it1 = gf_list_get(etd->itemDescriptionList, i);
		it2 = gf_list_get(etd->itemTextList, i);
		StartSubElement(trace, "item", indent, XMTDump);	
		DumpString(trace, "description", it1->text, indent, XMTDump);
		DumpString(trace, "text", it2->text, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);	
	}
	indent--;
	EndDescDump(trace, "ExpandedTextualDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_pl_ext(GF_PLExt *pld, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ExtensionProfileLevelDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "profileLevelIndicationIndex", pld->profileLevelIndicationIndex, indent, XMTDump);
	DumpInt(trace, "ODProfileLevelIndication", pld->ODProfileLevelIndication, indent, XMTDump);
	DumpInt(trace, "sceneProfileLevelIndication", pld->SceneProfileLevelIndication, indent, XMTDump);
	DumpInt(trace, "audioProfileLevelIndication", pld->AudioProfileLevelIndication, indent, XMTDump);
	DumpInt(trace, "visualProfileLevelIndication", pld->VisualProfileLevelIndication, indent, XMTDump);
	DumpInt(trace, "graphicsProfileLevelIndication", pld->GraphicsProfileLevelIndication, indent, XMTDump);
	DumpInt(trace, "MPEGJProfileLevelIndication", pld->MPEGJProfileLevelIndication, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_ipi_ptr(GF_IPIPtr *ipid, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "IPI_DescrPointer", indent, XMTDump);
	indent++;
	DumpInt(trace, "IPI_ES_Id", ipid->IPI_ES_Id, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_ipmp(GF_IPMP_Descriptor *ipmp, FILE *trace, u32 indent, Bool XMTDump)
{
	u32 i;
	StartDescDump(trace, "IPMP_Descriptor", indent, XMTDump);
	indent++;

	DumpIntHex(trace, "IPMP_DescriptorID", ipmp->IPMP_DescriptorID, indent, XMTDump, 1);
	DumpIntHex(trace, "IPMPS_Type", ipmp->IPMPS_Type, indent, XMTDump, 0);


	if ((ipmp->IPMP_DescriptorID==0xFF) && (ipmp->IPMPS_Type==0xFFFF)) {
		DumpIntHex(trace, "IPMP_DescriptorIDEx", ipmp->IPMP_DescriptorIDEx, indent, XMTDump, 0);
		/*how the heck do we represent toolID??*/
		DumpBin128(trace, "IPMP_ToolID", ipmp->IPMP_ToolID, indent, XMTDump);
		DumpInt(trace, "controlPointCode", ipmp->control_point, indent, XMTDump);
		if (ipmp->control_point) DumpInt(trace, "sequenceCode", ipmp->cp_sequence_code, indent, XMTDump);
		EndAttributes(trace, indent, XMTDump);
		/*parse IPMPX data*/
		StartElement(trace, "IPMPX_Data", indent, XMTDump, 1);
		indent++;
		for (i=0; i<gf_list_count(ipmp->ipmpx_data); i++) {
			GF_IPMPX_Data *p = gf_list_get(ipmp->ipmpx_data, i);
			gf_ipmpx_dump_data(p, trace, indent, XMTDump);
		}
		indent--;
		EndElement(trace, "IPMPX_Data", indent, XMTDump, 1);
	}
	else if (!ipmp->IPMPS_Type) {
		DumpString(trace, "URLString", ipmp->opaque_data, indent, XMTDump);
	} else {
		DumpData(trace, "IPMP_data", ipmp->opaque_data, ipmp->opaque_data_size, indent, XMTDump);
	}
	indent--;
	EndDescDump(trace, "IPMP_Descriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_ipmp_ptr(GF_IPMPPtr *ipmpd, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "IPMP_DescriptorPointer", indent, XMTDump);
	indent++;
	if (ipmpd->IPMP_DescriptorID == 0xFF) {
		DumpInt(trace, "IPMP_DescriptorID", 0xFF, indent, XMTDump);
		DumpInt(trace, "IPMP_DescriptorIDEx", ipmpd->IPMP_DescriptorIDEx, indent, XMTDump);
		DumpInt(trace, "IPMP_ES_ID", ipmpd->IPMP_ES_ID, indent, XMTDump);
	} else {
		DumpInt(trace, "IPMP_DescriptorID", ipmpd->IPMP_DescriptorID, indent, XMTDump);
	}
	indent--;
	if (XMTDump) 
		EndSubElement(trace, indent, XMTDump);	
	else
		EndDescDump(trace, "IPMP_DescriptorPointer", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_kw(GF_KeyWord *kwd, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_KeyWordItem *p;
	u32 i;

	StartDescDump(trace, "KeyWordDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "languageCode", kwd->languageCode, indent, XMTDump);
	DumpBool(trace, "isUTF8", kwd->isUTF8, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);

	for (i=0; i<gf_list_count(kwd->keyWordsList); i++) {
		p = gf_list_get(kwd->keyWordsList, i);
		StartSubElement(trace, "keyWord", indent, XMTDump);
		DumpString(trace, "value", p->keyWord, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	indent--;
	EndDescDump(trace, "KeyWordDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_lang(GF_Language *ld, FILE *trace, u32 indent, Bool XMTDump)
{
	char sLan[4];
	StartDescDump(trace, "LanguageDescriptor", indent, XMTDump);
	indent++;
	sLan[0] = (ld->langCode>>16)&0xFF;
	sLan[1] = (ld->langCode>>8)&0xFF;
	sLan[2] = (ld->langCode)&0xFF;
	sLan[3] = 0;
	DumpString(trace, "languageCode", sLan, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);	
	if (!XMTDump) EndDescDump(trace, "LanguageDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_isom_iod(GF_IsomInitialObjectDescriptor *iod, FILE *trace, u32 indent, Bool XMTDump)
{

	
	StartDescDump(trace, "MP4InitialObjectDescriptor", indent, XMTDump);
	indent++;

	StartAttribute(trace, "objectDescriptorID", indent, XMTDump);


	if (XMTDump) {
		fprintf(trace, "od%d", iod->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
		DumpInt(trace, "binaryID", iod->objectDescriptorID, indent, XMTDump);
	} else {
		fprintf(trace, "%d", iod->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
	}

	EndAttributes(trace, indent, XMTDump);


	StartSubElement(trace, "Profile", indent, 1);

	DumpInt(trace, "audioProfileLevelIndication", iod->audio_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "visualProfileLevelIndication", iod->visual_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "sceneProfileLevelIndication", iod->scene_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "graphicsProfileLevelIndication", iod->graphics_profileAndLevel, indent, XMTDump);
	DumpInt(trace, "ODProfileLevelIndication", iod->OD_profileAndLevel, indent, XMTDump);
	DumpBool(trace, "includeInlineProfileLevelFlag", iod->inlineProfileFlag, indent, XMTDump);

	EndSubElement(trace, indent, XMTDump);

	if (iod->URLString) {
		StartSubElement(trace, "URL", indent, XMTDump);
		DumpString(trace, "URLstring", iod->URLString, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
		
	if (XMTDump) {
		StartElement(trace, "Descr", indent, XMTDump, 1);
		indent++;
	}
	//ESDescr
	if (gf_list_count(iod->ES_ID_IncDescriptors)) {
		DumpDescList(iod->ES_ID_IncDescriptors, trace, indent, "esDescrInc", XMTDump);
	} else {
		DumpDescList(iod->ES_ID_RefDescriptors, trace, indent, "esDescrRef", XMTDump);
	}
	DumpDescList(iod->OCIDescriptors, trace, indent, "ociDescr", XMTDump);
	DumpDescListFilter(iod->IPMP_Descriptors, trace, indent, "ipmpDescrPtr", XMTDump, GF_ODF_IPMP_PTR_TAG);
	DumpDescListFilter(iod->IPMP_Descriptors, trace, indent, "ipmpDescr", XMTDump, GF_ODF_IPMP_TAG);

	DumpDescList(iod->extensionDescriptors, trace, indent, "extDescr", XMTDump);

	if (iod->IPMPToolList) {
		StartElement(trace, "toolListDescr" , indent, XMTDump, 0);
		gf_odf_dump_desc(iod->IPMPToolList, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "toolListDescr" , indent, XMTDump, 0);
	}
	
	if (XMTDump) {
		indent--;
		EndElement(trace, "Descr", indent, XMTDump, 1);
	}
	indent--;
	EndDescDump(trace, "MP4InitialObjectDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_od(GF_ObjectDescriptor *od, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ObjectDescriptor", indent, XMTDump);
	indent++;
	StartAttribute(trace, "objectDescriptorID", indent, XMTDump);
	if (XMTDump) {
		fprintf(trace, "od%d", od->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
		DumpInt(trace, "binaryID", od->objectDescriptorID, indent, XMTDump);
	} else {
		fprintf(trace, "%d", od->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
	}
	EndAttributes(trace, indent, XMTDump);
	if (od->URLString) {
		StartSubElement(trace, "URL", indent, XMTDump);
		DumpString(trace, "URLstring", od->URLString, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
		
	if (XMTDump) {
		StartElement(trace, "Descr", indent, XMTDump, 1);
		indent++;
	}
	//ESDescr
	DumpDescList(od->ESDescriptors, trace, indent, "esDescr", XMTDump);
	DumpDescList(od->OCIDescriptors, trace, indent, "ociDescr", XMTDump);
	DumpDescListFilter(od->IPMP_Descriptors, trace, indent, "ipmpDescrPtr", XMTDump, GF_ODF_IPMP_PTR_TAG);
	DumpDescListFilter(od->IPMP_Descriptors, trace, indent, "ipmpDescr", XMTDump, GF_ODF_IPMP_TAG);

	DumpDescList(od->extensionDescriptors, trace, indent, "extDescr", XMTDump);
	
	if (XMTDump) {
		indent--;
		EndElement(trace, "Descr", indent, XMTDump, 1);
	}
	indent--;
	EndDescDump(trace, "ObjectDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_isom_od(GF_IsomObjectDescriptor *od, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "MP4ObjectDescriptor", indent, XMTDump);
	indent++;
	StartAttribute(trace, "objectDescriptorID", indent, XMTDump);
	if (XMTDump) {
		fprintf(trace, "od%d", od->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
		DumpInt(trace, "binaryID", od->objectDescriptorID, indent, XMTDump);
	} else {
		fprintf(trace, "%d", od->objectDescriptorID);
		EndAttribute(trace, indent, XMTDump);
	}
	EndAttributes(trace, indent, XMTDump);
	if (od->URLString) {
		StartSubElement(trace, "URL", indent, XMTDump);
		DumpString(trace, "URLstring", od->URLString, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
		
	if (XMTDump) {
		StartElement(trace, "Descr", indent, XMTDump, 1);
		indent++;
	}
	//ESDescr
	if (gf_list_count(od->ES_ID_IncDescriptors)) {
		DumpDescList(od->ES_ID_IncDescriptors, trace, indent, "esDescrInc", XMTDump);
	} else {
		DumpDescList(od->ES_ID_RefDescriptors, trace, indent, "esDescrRef", XMTDump);
	}
	DumpDescList(od->OCIDescriptors, trace, indent, "ociDescr", XMTDump);
	DumpDescListFilter(od->IPMP_Descriptors, trace, indent, "ipmpDescrPtr", XMTDump, GF_ODF_IPMP_PTR_TAG);
	DumpDescListFilter(od->IPMP_Descriptors, trace, indent, "ipmpDescr", XMTDump, GF_ODF_IPMP_TAG);
	DumpDescList(od->extensionDescriptors, trace, indent, "extDescr", XMTDump);
	
	if (XMTDump) {
		indent--;
		EndElement(trace, "Descr", indent, XMTDump, 1);
	}
	indent--;
	EndDescDump(trace, "MP4ObjectDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_oci_date(GF_OCI_Data *ocd, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "OCICreationDateDescriptor", indent, XMTDump);
	indent++;
	DumpString(trace, "OCICreationDate", ocd->OCICreationDate, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_oci_name(GF_OCICreators *ocn, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_OCICreator_item *p;
	u32 i;

	StartDescDump(trace, "OCICreatorNameDescriptor", indent, XMTDump);
	indent++;
	for (i=0; i<gf_list_count(ocn->OCICreators); i++) {
		p = gf_list_get(ocn->OCICreators, i);
		StartSubElement(trace, "Creator", indent, XMTDump);
		DumpInt(trace, "languageCode", p->langCode, indent, XMTDump);
		DumpBool(trace, "isUTF8", p->isUTF8, indent, XMTDump);
		DumpString(trace, "name", p->OCICreatorName, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	indent--;
	EndDescDump(trace, "OCICreatorNameDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_pl_idx(GF_PL_IDX *plid, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ProfileLevelIndicationIndexDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "profileLevelIndicationIndex", plid->profileLevelIndicationIndex, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_qos(GF_QoS_Descriptor *qos, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_QoS_Default *p;
	u32 i;

	StartDescDump(trace, "QoS_Descriptor", indent, XMTDump);
	indent++;

	if (qos->predefined) {
		StartSubElement(trace, "predefined", indent, XMTDump);
		DumpInt(trace, "value", qos->predefined, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	} else {
		for ( i = 0; i < gf_list_count(qos->QoS_Qualifiers); i++ ) {
			p = (GF_QoS_Default*)gf_list_get(qos->QoS_Qualifiers, i);
			switch (p->tag) {
			case QoSMaxDelayTag:
				StartSubElement(trace, "QoSMaxDelay", indent, XMTDump);
				DumpInt(trace, "value", ((GF_QoS_MaxDelay *)p)->MaxDelay, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			case QoSPrefMaxDelayTag:
				StartSubElement(trace, "QoSPrefMaxDelay", indent, XMTDump);
				DumpInt(trace, "value", ((GF_QoS_PrefMaxDelay *)p)->PrefMaxDelay, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			case QoSLossProbTag:
				StartSubElement(trace, "QoSLossProb", indent, XMTDump);
				DumpFloat(trace, "value", ((GF_QoS_LossProb *)p)->LossProb, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			case QoSMaxGapLossTag:
				StartSubElement(trace, "QoSMaxGapLoss", indent, XMTDump);
				DumpInt(trace, "value", ((GF_QoS_MaxGapLoss *)p)->MaxGapLoss, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			case QoSMaxAUSizeTag:
				StartSubElement(trace, "QoSMaxAUSize", indent, XMTDump);
				DumpInt(trace, "value", ((GF_QoS_MaxAUSize *)p)->MaxAUSize, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			case QoSAvgAUSizeTag:
				StartSubElement(trace, "QoSAvgAUSize", indent, XMTDump);
				DumpInt(trace, "value", ((GF_QoS_AvgAUSize *)p)->AvgAUSize, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			case QoSMaxAURateTag:
				StartSubElement(trace, "QoSMaxAURate", indent, XMTDump);
				DumpInt(trace, "value", ((GF_QoS_MaxAURate *)p)->MaxAURate, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			default:
				StartSubElement(trace, "QoSCustom", indent, XMTDump);
				DumpInt(trace, "tag", p->tag, indent, XMTDump);
				DumpData(trace, "customData", ((GF_QoS_Private *)p)->Data, ((GF_QoS_Private *)p)->DataLength, indent, XMTDump);
				EndSubElement(trace, indent, XMTDump);
				break;
			}
		}
	}
	indent--;
	EndDescDump(trace, "QoS_Descriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_rating(GF_Rating *rd, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "RatingDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "ratingEntity", rd->ratingEntity, indent, XMTDump);
	DumpInt(trace, "ratingCriteria", rd->ratingCriteria, indent, XMTDump);
	DumpData(trace, "ratingInfo", rd->ratingInfo, rd->infoLength, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_reg(GF_Registration *reg, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "RegistrationDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "formatIdentifier", reg->formatIdentifier, indent, XMTDump);
	DumpData(trace, "additionalIdentificationInfo", reg->additionalIdentificationInfo, reg->dataLength, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_short_text(GF_ShortTextual *std, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "ShortTextualDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "languageCode", std->langCode, indent, XMTDump);
	DumpBool(trace, "isUTF8", std->isUTF8, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	StartSubElement(trace, "event", indent, XMTDump);
	DumpString(trace, "name", std->eventName, indent, XMTDump);
	DumpString(trace, "text", std->eventText, indent, XMTDump);
	EndSubElement(trace, indent, XMTDump);
	indent--;
	EndDescDump(trace, "ShortTextualDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_smpte_camera(GF_SMPTECamera *cpd, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_SmpteParam *p;
	u32 i;

	StartDescDump(trace, "SMPTECameraPositionDescriptor", indent, XMTDump);
	indent++;
	DumpInt(trace, "cameraID", cpd->cameraID, indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	
	for (i=0; i<gf_list_count(cpd->ParamList); i++) {
		p = gf_list_get(cpd->ParamList, i);
		StartSubElement(trace, "parameter", indent, XMTDump);
		DumpInt(trace, "id", p->paramID, indent, XMTDump);
		DumpInt(trace, "value", p->param, indent, XMTDump);
		EndSubElement(trace, indent, XMTDump);
	}
	indent--;
	EndDescDump(trace, "SMPTECameraPositionDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_sup_cid(GF_SCIDesc *scid, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "SupplementaryContentIdentification", indent, XMTDump);
	indent++;
	DumpInt(trace, "languageCode", scid->languageCode, indent, XMTDump);
	DumpString(trace, "supplContentIdentiferTitle", scid->supplContentIdentifierTitle, indent, XMTDump);
	DumpString(trace, "supplContentIdentiferValue", scid->supplContentIdentifierValue, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}


GF_Err gf_odf_dump_segment(GF_Segment *sd, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "SegmentDescriptor", indent, XMTDump);
	indent++;
	DumpDouble(trace, "startTime", sd->startTime, indent, XMTDump);
	DumpDouble(trace, "duration", sd->Duration, indent, XMTDump);
	DumpString(trace, "name", sd->SegmentName, indent, XMTDump);
	indent--;
	if (XMTDump) 
		EndSubElement(trace, indent, XMTDump);
	else
		EndDescDump(trace, "SegmentDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_mediatime(GF_MediaTime *mt, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "MediaTimeDescriptor", indent, XMTDump);
	indent++;
	DumpDouble(trace, "mediaTimestamp ", mt->mediaTimeStamp, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}


GF_Err gf_odf_dump_muxinfo(GF_MuxInfo *mi, FILE *trace, u32 indent, Bool XMTDump)
{
	if (!XMTDump) {
		StartDescDump(trace, "MuxInfo", indent, 0);
		indent++;
		if (mi->file_name) DumpString(trace, "fileName", mi->file_name, indent, 0);
		if (mi->streamFormat) DumpString(trace, "streamFormat", mi->streamFormat, indent, 0);
		if (mi->GroupID) DumpInt(trace, "GroupID", mi->GroupID, indent, 0);
		if (mi->startTime) DumpInt(trace, "startTime", mi->startTime, indent, 0);
		if (mi->duration) DumpInt(trace, "duration", mi->duration, indent, 0);
#if 0
		if (mi->import_flags & GF_IMPORT_USE_DATAREF) DumpBool(trace, "useDataReference", 1, indent, 0);
		if (mi->import_flags & GF_IMPORT_NO_FRAME_DROP) DumpBool(trace, "noFrameDrop", 1, indent, 0);
		if (mi->import_flags & GF_IMPORT_SBR_IMPLICIT) DumpString(trace, "SBR_Type", "implicit", indent, 0);
		else if (mi->import_flags & GF_IMPORT_SBR_EXPLICIT) DumpString(trace, "SBR_Type", "explicit", indent, 0);
#endif

		if (mi->textNode) DumpString(trace, "textNode", mi->textNode, indent, 0);
		if (mi->fontNode) DumpString(trace, "fontNode", mi->fontNode, indent, 0);
		
		indent--;
		EndDescDump(trace, "MuxInfo", indent, 0);
		return GF_OK;
	}

	StartDescDump(trace, "StreamSource", indent, 1);
	indent++;
	if (mi->file_name) DumpString(trace, "url", mi->file_name, indent, 1);
	EndAttributes(trace, indent, 1);

	StartDescDump(trace, "MP4MuxHints", indent, 1);
	if (mi->GroupID) DumpInt(trace, "GroupID", mi->GroupID, indent, 1);
	if (mi->startTime) DumpInt(trace, "startTime", mi->startTime, indent, 1);
	if (mi->duration) DumpInt(trace, "duration", mi->duration, indent, 1);
	if (mi->import_flags & GF_IMPORT_USE_DATAREF) DumpBool(trace, "useDataReference", 1, indent, 1);
	if (mi->import_flags & GF_IMPORT_NO_FRAME_DROP) DumpBool(trace, "noFrameDrop", 1, indent, 1);
	if (mi->import_flags & GF_IMPORT_SBR_IMPLICIT) DumpString(trace, "SBR_Type", "implicit", indent, 1);
	else if (mi->import_flags & GF_IMPORT_SBR_EXPLICIT) DumpString(trace, "SBR_Type", "explicit", indent, 1);

	if (mi->textNode) DumpString(trace, "textNode", mi->textNode, indent, 1);
	if (mi->fontNode) DumpString(trace, "fontNode", mi->fontNode, indent, 1);
	EndSubElement(trace, indent, 1);

	indent--;
	EndElement(trace, "StreamSource" , indent, 1, 1);
	return GF_OK;
}

GF_Err gf_odf_dump_ipmp_tool_list(GF_IPMP_ToolList *tl, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "IPMP_ToolListDescriptor", indent, XMTDump);
	EndAttributes(trace, indent, XMTDump);
	indent++;
	DumpDescList(tl->ipmp_tools, trace, indent, "ipmpTool", XMTDump);
	indent--;
	EndDescDump(trace, "IPMP_ToolListDescriptor", indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_ipmp_tool(GF_IPMP_Tool*t, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "IPMP_Tool", indent, XMTDump);
	indent++;
	DumpBin128(trace, "IPMP_ToolID", t->IPMP_ToolID, indent, XMTDump);
	if (t->tool_url) DumpString(trace, "ToolURL", t->tool_url, indent, XMTDump);
	if (t->toolParamDesc) {
		StartElement(trace, "toolParamDesc" , indent, XMTDump, 0);
		gf_ipmpx_dump_data((GF_IPMPX_Data *)t->toolParamDesc, trace, indent + (XMTDump ? 1 : 0), XMTDump);
		EndElement(trace, "toolParamDesc" , indent, XMTDump, 0);
	}
	EndAttributes(trace, indent, XMTDump);
	indent--;
	EndDescDump(trace, "IPMP_Tool", indent, XMTDump);
	return GF_OK;
}


GF_Err gf_odf_dump_od_update(GF_ODUpdate *com, FILE *trace, u32 indent, Bool XMTDump)
{
	if (XMTDump) {
		StartDescDump(trace, "ObjectDescriptorUpdate", indent, XMTDump);
		EndAttributes(trace, indent, XMTDump);
		indent++;
		DumpDescList(com->objectDescriptors, trace, indent+1, "OD", XMTDump);
		indent--;
		EndDescDump(trace, "ObjectDescriptorUpdate", indent, XMTDump);
	} else {
		DumpDescList(com->objectDescriptors, trace, indent, "UPDATE OD", XMTDump);
	}
	return GF_OK;
}

GF_Err gf_odf_dump_od_remove(GF_ODRemove *com, FILE *trace, u32 indent, Bool XMTDump)
{
	u32 i;

	if (XMTDump) {
		StartDescDump(trace, "ObjectDescriptorRemove", indent, XMTDump);
		indent++;
		StartAttribute(trace, "objectDescriptorId", indent, XMTDump);
	} else {
		char ind_buf[OD_MAX_TREE];
		OD_FORMAT_INDENT(ind_buf, indent);
		fprintf(trace, "%sREMOVE OD [", ind_buf);
	}
	for (i=0; i<com->NbODs; i++) {
		if (i) fprintf(trace, " ");
		fprintf(trace, "%s%d", XMTDump ? "od" : "", com->OD_ID[i]);
	}
	if (XMTDump) {
		EndAttribute(trace, indent, XMTDump);
		indent--;
		EndSubElement(trace, indent, XMTDump);
	} else {
		fprintf(trace, "]\n");
	}
	return GF_OK;
}

GF_Err gf_odf_dump_esd_update(GF_ESDUpdate *com, FILE *trace, u32 indent, Bool XMTDump)
{
	if (XMTDump) {
		StartDescDump(trace, "ES_DescriptorUpdate", indent, XMTDump);
		StartAttribute(trace, "objectDescriptorId", indent, XMTDump);
		fprintf(trace, "od%d", com->ODID);
		EndAttribute(trace, indent, XMTDump);
		EndAttributes(trace, indent, XMTDump);
	} else {
		char ind_buf[OD_MAX_TREE];
		OD_FORMAT_INDENT(ind_buf, indent);
		fprintf(trace, "%sUPDATE ESD in %d\n", ind_buf, com->ODID);
	}
	indent++;
	DumpDescList(com->ESDescriptors, trace, indent+1, "esDescr", XMTDump);
	indent--;
	if (XMTDump) {
		EndDescDump(trace, "ES_DescriptorUpdate", indent, XMTDump);
	} else {
		fprintf(trace, "\n");
	}
	return GF_OK;
}

GF_Err gf_odf_dump_esd_remove(GF_ESDRemove *com, FILE *trace, u32 indent, Bool XMTDump)
{
	u32 i;

	if (XMTDump) {
		StartDescDump(trace, "ES_DescriptorRemove", indent, XMTDump);
		StartAttribute(trace, "objectDescriptorId", indent, XMTDump);
		fprintf(trace, "od%d", com->ODID);
		EndAttribute(trace, indent, XMTDump);
		StartAttribute(trace, "ES_ID", indent, XMTDump);
	} else {
		char ind_buf[OD_MAX_TREE];
		OD_FORMAT_INDENT(ind_buf, indent);
		fprintf(trace, "%sREMOVE ESD FROM %d [", ind_buf, com->ODID);
	}
	for (i=0; i<com->NbESDs; i++) {
		if (i) fprintf(trace, " ");
		if (XMTDump) fprintf(trace, "es");
		fprintf(trace, "%d", com->ES_ID[i]);
	}
	if (XMTDump) {
		EndAttribute(trace, indent, XMTDump);
		indent--;
		EndSubElement(trace, indent, XMTDump);
	} else {
		fprintf(trace, "]\n");
	}
	return GF_OK;
}

GF_Err gf_odf_dump_ipmp_update(GF_IPMPUpdate *com, FILE *trace, u32 indent, Bool XMTDump)
{
	if (XMTDump) {
		StartDescDump(trace, "IPMP_DescriptorUpdate", indent, XMTDump);
		EndAttributes(trace, indent, XMTDump);
		indent++;
		DumpDescList(com->IPMPDescList, trace, indent+1, "ipmpDesc", XMTDump);
		indent--;
		EndDescDump(trace, "IPMP_DescriptorUpdate", indent, XMTDump);
	} else {
		DumpDescList(com->IPMPDescList, trace, indent, "UPDATE IPMPD", XMTDump);
	}
	return GF_OK;
}

GF_Err gf_odf_dump_ipmp_remove(GF_IPMPRemove *com, FILE *trace, u32 indent, Bool XMTDump)
{
	u32 i;

	StartDescDump(trace, "IPMP_DescriptorRemove", indent, XMTDump);
	indent++;

	StartAttribute(trace, "IPMP_DescriptorID", indent, XMTDump);
	for (i=0; i<com->NbIPMPDs; i++) {
		if (i) fprintf(trace, " ");
		fprintf(trace, "%d", com->IPMPDescID[i]);
	}
	EndAttribute(trace, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}

GF_Err gf_odf_dump_base_command(GF_BaseODCom *com, FILE *trace, u32 indent, Bool XMTDump)
{
	StartDescDump(trace, "BaseODCommand", indent, XMTDump);
	indent++;

	DumpData(trace, "custom", com->data, com->dataSize, indent, XMTDump);
	indent--;
	EndSubElement(trace, indent, XMTDump);
	return GF_OK;
}


GF_Err gf_oci_dump_event(OCIEvent *ev, FILE *trace, u32 indent, Bool XMTDump)
{
	u8 H, M, S, hS, rien;
	u16 evID;
	u32 i;
	GF_Descriptor *desc;

	StartDescDump(trace, "OCI_Event", indent, XMTDump);
	indent++;
	gf_oci_event_get_id(ev, &evID);
	DumpInt(trace, "eventID", evID, indent, XMTDump);

	gf_oci_event_get_start_time(ev, &H, &M, &S, &hS, &rien);
	DumpBool(trace, "absoluteTimeFlag", rien, indent, XMTDump);
	StartAttribute(trace, "startingTime", indent, XMTDump);
	fprintf(trace, "%d:%d:%d:%d", H, M, S, hS);
	EndAttribute(trace, indent, XMTDump);

	gf_oci_event_get_duration(ev, &H, &M, &S, &hS);
	StartAttribute(trace, "duration", indent, XMTDump);
	fprintf(trace, "%d:%d:%d:%d", H, M, S, hS);
	EndAttribute(trace, indent, XMTDump);
	
	StartElement(trace, "OCIDescr", indent, XMTDump, 1);
	for (i=0; i<gf_oci_event_get_desc_count(ev); i++) {
		desc = gf_oci_event_get_desc(ev, i);
		gf_odf_dump_desc(desc, trace, indent+1, XMTDump);
	}
	EndElement(trace, "OCIDescr", indent, XMTDump, 1);
	indent--;
	EndDescDump(trace, "OCI_Event", indent, XMTDump);

	return GF_OK;
}


GF_Err gf_oci_dump_au(u8 version, char *au, u32 au_length, FILE *trace, u32 indent, Bool XMTDump)
{
	GF_Err e;
	OCICodec *codec = gf_oci_codec_new(0, version);
	if (!codec) return GF_BAD_PARAM;

	e = gf_oci_codec_decode(codec, au, au_length);

	if (!e) {
		while (1) {
			OCIEvent *ev = gf_oci_codec_get_event(codec);
			if (!ev) break;
			gf_oci_dump_event(ev, trace, indent, XMTDump);
		}
	}
	gf_oci_codec_del(codec);
	return e;
}
