/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Jean Le Feuvre 2000-2005
 *					All rights reserved
 *
 *  This file is part of GPAC / 2D+3D rendering module
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

#ifndef _VISUAL_MANAGER_H_
#define _VISUAL_MANAGER_H_

#include "render.h"
#include "drawable.h"

/*all 2D related functions and macro are locate there*/
#include "visual_manager_2d.h"

/*all 3D related functions and macro are locate there*/
#include "visual_manager_3d.h"

struct _visual_manager
{
	Render *render;

#ifndef GPAC_DISABLE_3D
	/*3D type for the visual:
	0: visual is 2D
	1: visual is 2D with 3D acceleration (2D camera)
	2: visual is 3D MPEG-4 (with 3D camera)
	3: visual is 3D X3D (with 3D camera)
	*/
	u32 type_3d;
#endif


	/*background stack*/
	GF_List *back_stack;
	/*viewport stack*/
	GF_List *view_stack;

	/*size in pixels*/
	u32 width, height;

	
	/*
	 *	Visual Manager part for 2D drawing and dirty rect
	 */

	/*the one and only dirty rect collector for this visual manager*/
	GF_RectArray to_redraw;
#ifdef TRACK_OPAQUE_REGIONS
	u32 draw_node_index;
#endif

	/*display list (list of drawable context). The first context with no drawable attached to 
	it (ctx->drawable==NULL) marks the end of the display list*/
	DrawableContext *context, *cur_context;

	/*keeps track of nodes drawn last frame*/
	struct _drawable_store *prev_nodes, *last_prev_entry;

	/*pixel area in BIFS coords - eg area to fill with background*/
	GF_IRect surf_rect;
	/*top clipper (may be different than surf_rect when a viewport is active)*/
	GF_IRect top_clipper;

	Bool last_had_back;

	/*signals that the hardware surface is attached to buffer/device/stencil*/
	Bool is_attached;
	Bool center_coords;

	/*gets access to graphics handle (either OS-specific or raw memory)*/
	GF_Err (*GetSurfaceAccess)(GF_VisualManager *);
	/*release graphics handle*/
	void (*ReleaseSurfaceAccess)(GF_VisualManager *);

	/*draws specified texture as flat bitmap*/
	void (*DrawBitmap)(GF_VisualManager *, struct _gf_sr_texture_handler *, GF_IRect *clip, GF_Rect *unclip, u8 alpha, u32 *col_key, GF_ColorMatrix *cmat);
	Bool (*SupportsFormat)(GF_VisualManager *surf, u32 pixel_format);

	/*raster surface interface*/
	GF_SURFACE raster_surface;
	/*raster brush interface*/
	GF_STENCIL raster_brush;

	/*node owning this visual manager (composite textures) - NULL for root visual*/
	GF_Node *offscreen;

	/*value of the flag to use to signal any geometry changes*/
	u32 bounds_tracker_modif_flag;


#ifndef GPAC_DISABLE_3D
	/*
	 *	Visual Manager part for 3D drawing 
	 */

	/*navigation stack*/
	GF_List *navigation_stack;
	/*fog stack*/
	GF_List *fog_stack;

	/*the one and only camera associated with the visual*/
	GF_Camera camera;

	/*list of transparent nodes to draw after TRAVERSE_RENDER pass*/
	GF_List *alpha_nodes_to_draw;

	/*lighting stuff*/
	u32 num_lights;
	u32 max_lights;
	/*cliping stuff*/
	u32 num_clips;
	u32 max_clips;
#endif

};

/*constructor/destructor*/
GF_VisualManager *visual_new(Render *sr);
void visual_del(GF_VisualManager *vis);

/*rendering cycle for the visual - called at each cycle start regardless of rendering mode*/
Bool visual_render_frame(GF_VisualManager *vis, GF_Node *root, GF_TraverseState *tr_state, Bool is_root_visual);

/*executes scene event (picks node if needed) - returns FALSE if no scene event handler has been called*/
Bool visual_execute_event(GF_VisualManager *vis, GF_TraverseState *tr_state, GF_Event *ev, GF_ChildNodeItem *children);

#endif	/*_VISUAL_MANAGER_H_*/

