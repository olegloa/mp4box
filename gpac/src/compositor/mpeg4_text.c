/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Jean Le Feuvre 2000-2005
 *					All rights reserved
 *
 *  This file is part of GPAC / Scene Compositor sub-project
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



#include "nodes_stacks.h"
#include "visual_manager.h"
#include "mpeg4_grouping.h"
#include "texturing.h"
#include <gpac/utf.h>
#include <gpac/options.h>

/*default value when no fontStyle*/
#define FSFAMILY	(fs && fs->family.count) ? (const char *)fs->family.vals[0]	: ""

/*here it's tricky since it depends on our metric system...*/
#define FSSIZE		(fs ? fs->size : -1)
#define FSSTYLE		(fs && fs->style.buffer) ? (const char *)fs->style.buffer : ""
#define FSMAJOR		( (fs && fs->justify.count && fs->justify.vals[0]) ? (const char *)fs->justify.vals[0] : "FIRST")
#define FSMINOR		( (fs && (fs->justify.count>1) && fs->justify.vals[1]) ? (const char *)fs->justify.vals[1] : "FIRST")
#define FSHORIZ		(fs ? fs->horizontal : 1)
#define FSLTR		(fs ? fs->leftToRight : 1)
#define FSTTB		(fs ? fs->topToBottom : 1)
#define FSLANG		(fs ? fs->language : "")
#define FSSPACE		(fs ? fs->spacing : 1)


/*exported to access the strike list ...*/
typedef struct
{
	Drawable *graph;
	Fixed ascent, descent;
	GF_List *spans;
	GF_Rect bounds;
	u32 texture_text_flag;
	GF_Compositor *compositor;
} TextStack;

void text_clean_paths(GF_Compositor *compositor, TextStack *stack)
{
	GF_TextSpan *span;
	/*delete all path objects*/
	while (gf_list_count(stack->spans)) {
		span = (GF_TextSpan*) gf_list_get(stack->spans, 0);
		gf_list_rem(stack->spans, 0);
		gf_font_manager_delete_span(compositor->font_manager, span);
	}
	stack->bounds.width = stack->bounds.height = 0;
	drawable_reset_path(stack->graph);
}


static void build_text_split(TextStack *st, M_Text *txt, GF_TraverseState *tr_state)
{
	u32 i, j, k, len, styles, idx, first_char;
	Bool split_words = 0;
	GF_Font *font;
	GF_TextSpan *tspan;
	GF_FontManager *ft_mgr = tr_state->visual->compositor->font_manager;
	Fixed fontSize, start_y;
	M_FontStyle *fs = (M_FontStyle *)txt->fontStyle;

	fontSize = FSSIZE;
	if (fontSize <= 0) {
		fontSize = INT2FIX(12);
		if (!tr_state->pixel_metrics) fontSize = gf_divfix(fontSize, tr_state->visual->compositor->output_width);
    }

	styles = 0;
	if (fs && fs->style.buffer) {
		if (strstr(fs->style.buffer, "BOLD") || strstr(fs->style.buffer, "bold")) styles |= GF_FONT_BOLD;
		if (strstr(fs->style.buffer, "ITALIC") || strstr(fs->style.buffer, "italic")) styles |= GF_FONT_ITALIC;
		if (strstr(fs->style.buffer, "UNDERLINED") || strstr(fs->style.buffer, "underlined")) styles |= GF_FONT_UNDERLINED;
	}

	font = gf_font_manager_set_font(ft_mgr, fs ? fs->family.vals : NULL, fs ? fs->family.count : 0, styles);
	if (!font) return;

	st->ascent = (fontSize*font->ascent) / font->em_size;
	st->descent = -(fontSize*font->descent) / font->em_size;

	if (!strcmp(FSMINOR, "MIDDLE")) {
		start_y = (st->descent + st->ascent)/2;
	}
	else if (!strcmp(FSMINOR, "BEGIN")) {
		start_y = st->descent;
	}
	else if (!strcmp(FSMINOR, "END")) {
		start_y = st->descent + st->ascent;
	}
	else {
		start_y = st->ascent;
	}
	
	st->bounds.width = st->bounds.x = st->bounds.height = 0;
	idx = 0;
	split_words = (tr_state->text_split_mode==1) ? 1 : 0;

	for (i=0; i < txt->string.count; i++) {

		char *str = txt->string.vals[i];
		if (!str) continue;

		tspan = gf_font_manager_create_span(ft_mgr, font, str, fontSize, 0, 0);
		len = tspan->nb_glyphs;
		tspan->horizontal = 1;

		first_char = 0;
		for (j=0; j<len; j++) {
			u32 is_space = 0;
			GF_TextSpan *span;

			/*we currently only split sentences at spaces*/
			if (tspan->glyphs[j]->utf_name == (unsigned short) ' ') is_space = 1;
			if (split_words && (j+1!=len) && !is_space) 
				continue;

			span = (GF_TextSpan*) malloc(sizeof(GF_TextSpan));
			memcpy(span, tspan, sizeof(GF_TextSpan));

			span->nb_glyphs = split_words ? (j - first_char) : 1;
			if (split_words && !is_space) span->nb_glyphs++;
			span->glyphs = malloc(sizeof(void *)*span->nb_glyphs);

			span->bounds.height = st->ascent + st->descent;
			span->bounds.y = start_y;
			span->bounds.x = 0;
			span->bounds.width = 0;

			if (split_words) {
				for (k=0; k<span->nb_glyphs; k++) {
					span->glyphs[k] = tspan->glyphs[FSLTR ? (first_char+k) : (len - first_char - k - 1)];
					span->bounds.width += tspan->font_scale * (span->glyphs[k] ? span->glyphs[k]->horiz_advance : tspan->font->max_advance_h);
				}
			} else {
				span->glyphs[0] = tspan->glyphs[FSLTR ? j : (len - j - 1) ];
				span->glyphs[0] = tspan->glyphs[j];
				span->bounds.width = tspan->font_scale * (span->glyphs[0] ? span->glyphs[0]->horiz_advance : tspan->font->max_advance_h);
			}

			gf_list_add(st->spans, span);

			/*request a context (first one is always valid when entering sort phase)*/
			if (idx) parent_node_start_group(tr_state->parent, NULL, 0);

			idx++;
			parent_node_end_text_group(tr_state->parent, &span->bounds, st->ascent, st->descent, idx);

			if (is_space && split_words) {
				span = (GF_TextSpan*) malloc(sizeof(GF_TextSpan));
				memcpy(span, tspan, sizeof(GF_TextSpan));
				span->nb_glyphs = 1;
				span->glyphs = malloc(sizeof(void *));

				gf_list_add(st->spans, span);
				span->bounds.height = st->ascent + st->descent;
				span->bounds.y = start_y;
				span->bounds.x = 0;
				k = (j - first_char);
				span->glyphs[0] = tspan->glyphs[FSLTR ? (first_char+k) : (len - first_char - k - 1)];
				span->bounds.width = tspan->font_scale * (span->glyphs[0] ? span->glyphs[0]->horiz_advance : tspan->font->max_advance_h);
				parent_node_start_group(tr_state->parent, NULL, 1);
				idx++;
				parent_node_end_text_group(tr_state->parent, &span->bounds, st->ascent, st->descent, idx);
			}
			first_char = j+1;
		}
		gf_font_manager_delete_span(ft_mgr, tspan);
	}
}


static void build_text(TextStack *st, M_Text *txt, GF_TraverseState *tr_state)
{
	u32 i, j, int_major, k, styles, count;
	Fixed fontSize, start_x, start_y, line_spacing, tot_width, tot_height, max_scale, space;
	GF_Font *font;
	Bool horizontal;
	GF_FontManager *ft_mgr = tr_state->visual->compositor->font_manager;
	M_FontStyle *fs = (M_FontStyle *)txt->fontStyle;

	fontSize = FSSIZE;
	if (fontSize <= 0) {
		fontSize = INT2FIX(12);
		if (!tr_state->pixel_metrics) fontSize = gf_divfix(fontSize, tr_state->visual->compositor->output_width);
    }
	horizontal = FSHORIZ;

	styles = 0;
	if (fs && fs->style.buffer) {
		if (strstr(fs->style.buffer, "BOLD") || strstr(fs->style.buffer, "bold")) styles |= GF_FONT_BOLD;
		if (strstr(fs->style.buffer, "ITALIC") || strstr(fs->style.buffer, "italic")) styles |= GF_FONT_ITALIC;
		if (strstr(fs->style.buffer, "UNDERLINED") || strstr(fs->style.buffer, "underlined")) styles |= GF_FONT_UNDERLINED;
	}

	font = gf_font_manager_set_font(ft_mgr, fs ? fs->family.vals : NULL, fs ? fs->family.count : 0, styles);
	if (!font) return;

	/*NOTA: we could use integer maths here but we have a risk of overflow with large fonts, so use fixed maths*/
	st->ascent = gf_muldiv(fontSize, INT2FIX(font->ascent), INT2FIX(font->em_size));
	st->descent = -gf_muldiv(fontSize, INT2FIX(font->descent), INT2FIX(font->em_size));
	space = gf_muldiv(fontSize, INT2FIX(font->line_spacing), INT2FIX(font->em_size)) ;
	line_spacing = gf_mulfix(FSSPACE, fontSize);

	tot_width = tot_height = 0;
	for (i=0; i < txt->string.count; i++) {
		GF_TextSpan *tspan;
		u32 size;
		char *str = txt->string.vals[i];
		if (!str) continue;

		tspan = gf_font_manager_create_span(ft_mgr, font, txt->string.vals[i], fontSize, 0, 0);
		if (!tspan) continue;
		
		tspan->horizontal = horizontal;

		if ((horizontal && !FSLTR) || (!horizontal && !FSTTB)) {
			for (k=0; k<tspan->nb_glyphs/2; k++) {
				GF_Glyph *g = tspan->glyphs[k];
				tspan->glyphs[k] = tspan->glyphs[tspan->nb_glyphs-1-k];
				tspan->glyphs[tspan->nb_glyphs-k-1] = g;
			}
		}

		size = 0;
		for (j=0; j<tspan->nb_glyphs; j++) {
			if (horizontal) {
				size += tspan->glyphs[j] ? tspan->glyphs[j]->horiz_advance : tspan->font->max_advance_h;
			} else {
				size += tspan->glyphs[j] ? tspan->glyphs[j]->vert_advance : tspan->font->max_advance_v;
			}
		}
		gf_list_add(st->spans, tspan);

		if (horizontal) {
			tspan->bounds.width = tspan->font_scale * size;
			/*apply length*/
			if ((txt->length.count>i) && (txt->length.vals[i]>0)) {
				tspan->x_scale = gf_divfix(txt->length.vals[i], tspan->bounds.width);
				tspan->bounds.width = txt->length.vals[i];
			}
			if (tot_width < tspan->bounds.width ) tot_width = tspan->bounds.width ;
		} else {
			tspan->bounds.height = tspan->font_scale * size;

			/*apply length*/
			if ((txt->length.count>i) && (txt->length.vals[i]>0)) {
				tspan->y_scale = gf_divfix(txt->length.vals[i], tspan->bounds.height);
				tspan->bounds.height = txt->length.vals[i];
			}
			if (tot_height < tspan->bounds.height) tot_height = tspan->bounds.height;
		}
	}
	
	max_scale = FIX_ONE;
	if (horizontal) {
		if ((txt->maxExtent > 0) && (tot_width>txt->maxExtent)) {
			max_scale = gf_divfix(txt->maxExtent, tot_width);
			tot_width = txt->maxExtent;
		}
		tot_height = (txt->string.count-1) * line_spacing + (st->ascent + st->descent);
		st->bounds.height = tot_height;

		if (!strcmp(FSMINOR, "MIDDLE")) {
			if (FSTTB) {
				start_y = tot_height/2;
				st->bounds.y = start_y;
			} else {
				start_y = st->descent + st->ascent - tot_height/2;
				st->bounds.y = tot_height/2;
			}
		}
		else if (!strcmp(FSMINOR, "BEGIN")) {
			if (FSTTB) {
				start_y = st->descent;
				start_y = 0;
				st->bounds.y = start_y;
			} else {
				st->bounds.y = st->bounds.height;
				start_y = st->descent + st->ascent;
			}
		}
		else if (!strcmp(FSMINOR, "END")) {
			if (FSTTB) {
				start_y = tot_height;
				st->bounds.y = start_y;
			} else {
				start_y = -tot_height + 2*st->descent + st->ascent;
				st->bounds.y = start_y - (st->descent + st->ascent) + tot_height;
			}
		}
		else {
			start_y = st->ascent;
			st->bounds.y = FSTTB ? start_y : (tot_height - st->descent);
		}
	} else {
		if ((txt->maxExtent > 0) && (tot_height>txt->maxExtent) ) {
			max_scale = gf_divfix(txt->maxExtent, tot_height);
			tot_height = txt->maxExtent;
		}
		tot_width = txt->string.count * line_spacing;
		st->bounds.width = tot_width;

		if (!strcmp(FSMINOR, "MIDDLE")) {
			if (FSLTR) {
				start_x = -tot_width/2;
				st->bounds.x = start_x;
			} else {
				start_x = tot_width/2 - line_spacing;
				st->bounds.x = - tot_width + line_spacing;
			}
		}
		else if (!strcmp(FSMINOR, "END")) {
			if (FSLTR) {
				start_x = -tot_width;
				st->bounds.x = start_x;
			} else {
				start_x = tot_width-line_spacing;
				st->bounds.x = 0;
			}
		}
		else {
			if (FSLTR) {
				start_x = 0;
				st->bounds.x = start_x;
			} else {
				start_x = -line_spacing;
				st->bounds.x = -tot_width;
			}
		}
	}
			

	/*major-justification*/
	if (!strcmp(FSMAJOR, "MIDDLE") ) {
		int_major = 0;
	} else if (!strcmp(FSMAJOR, "END") ) {
		int_major = 1;
	} else {
		int_major = 2;
	}

	st->bounds.width = st->bounds.height = 0;

	count = gf_list_count(st->spans);
	for (i=0; i < count; i++) {
		GF_TextSpan *span = gf_list_get(st->spans, i);
		switch (int_major) {
		/*major-justification MIDDLE*/
		case 0:
			if (horizontal) {
				start_x = -span->bounds.width/2;
			} else {
				start_y = FSTTB ? span->bounds.height/2 : (-span->bounds.height/2 + space);
			}
			break;
		/*major-justification END*/
		case 1:
			if (horizontal) {
				start_x = (FSLTR) ? -span->bounds.width : 0;
			} else {
				start_y = FSTTB ? span->bounds.height : (-span->bounds.height + space);
			}
			break;
		/*BEGIN, FIRST or default*/
		default:
			if (horizontal) {
				start_x = (FSLTR) ? 0 : -span->bounds.width;
			} else {
				start_y = FSTTB ? 0 : space;
			}
			break;
		}
		span->off_x = start_x;
		span->bounds.x = start_x;
		if (horizontal) {
			span->off_y = start_y - st->ascent;
			span->x_scale = gf_mulfix(span->x_scale, max_scale);
			span->bounds.y = start_y;
		} else {
			span->y_scale = gf_mulfix(span->y_scale, max_scale);
			span->off_y = start_y - gf_mulfix(st->ascent, span->y_scale);
			span->bounds.y = start_y;
		}

		if (horizontal) {
			start_y += FSTTB ? -line_spacing : line_spacing;
			span->bounds.height = st->descent + st->ascent;
		} else {
			start_x += FSLTR ? line_spacing : -line_spacing;
			span->bounds.width = line_spacing;
		}
		gf_rect_union(&st->bounds, &span->bounds);
	}
}

static void text_get_draw_opt(GF_Node *node, TextStack *st, Bool *force_texture, u32 *hl_color)
{
	const char *fs_style;
	char *hlight;
	M_FontStyle *fs = (M_FontStyle *) ((M_Text *) node)->fontStyle;

	*hl_color = 0;

	fs_style = FSSTYLE;
	hlight = NULL;
	hlight = strstr(fs_style, "HIGHLIGHT");
	if (hlight) hlight = strchr(hlight, '#');
	if (hlight) {
		hlight += 1;
		if (!strnicmp(hlight, "RV", 2)) *hl_color = 0x00FFFFFF;
		else {
			sscanf(hlight, "%x", hl_color);
			if (strlen(hlight)!=8) *hl_color |= 0xFF000000;
		}
	}
	*force_texture = st->texture_text_flag;
	if (strstr(fs_style, "TEXTURED")) *force_texture  = 1;
}


#ifndef GPAC_DISABLE_3D


static Bool text_is_3d_material(GF_TraverseState *tr_state)
{
	GF_Node *__mat;
	if (!tr_state->appear) return 0;
	__mat = ((M_Appearance *)tr_state->appear)->material;
	if (!__mat) return 0;
	if (gf_node_get_tag(__mat)==TAG_MPEG4_Material2D) return 0;
	return 1;
}

static void text_draw_3d(GF_TraverseState *tr_state, GF_Node *node, TextStack *st)
{
	DrawAspect2D the_asp, *asp;
	Bool is_3d, force_texture;
	u32 hl_color;
	GF_Compositor *compositor = (GF_Compositor*)st->compositor;

	is_3d = text_is_3d_material(tr_state);
	asp = NULL;
	if (!is_3d) {
		memset(&the_asp, 0, sizeof(DrawAspect2D));
		asp = &the_asp;
		drawable_get_aspect_2d_mpeg4(node, asp, tr_state);
	}
	text_get_draw_opt(node, st, &force_texture, &hl_color);
	gf_font_spans_draw_3d(st->spans, tr_state, asp, hl_color, force_texture);
}

#endif




void text_draw_2d(GF_Node *node, GF_TraverseState *tr_state)
{
	Bool force_texture;
	u32 hl_color;
	TextStack *st = (TextStack *) gf_node_get_private((GF_Node *) node);

	if (!GF_COL_A(tr_state->ctx->aspect.fill_color) && !tr_state->ctx->aspect.pen_props.width) return;

	text_get_draw_opt(node, st, &force_texture, &hl_color);

	gf_font_spans_draw_2d(st->spans, tr_state, hl_color, force_texture);
}



static void text_pick(GF_Node *node, TextStack *st, GF_TraverseState *tr_state)
{
	u32 i, count;
	GF_Matrix inv_mx;
	GF_Matrix2D inv_2d;
	Fixed x, y;
	GF_Compositor *compositor = tr_state->visual->compositor;

	/*TODO: pick the real glyph and not just the bounds of the text span*/
	count = gf_list_count(st->spans);

	if (tr_state->visual->type_3d) {
		GF_Ray r;
		SFVec3f local_pt;

		r = tr_state->ray;
		gf_mx_copy(inv_mx, tr_state->model_matrix);
		gf_mx_inverse(&inv_mx);
		gf_mx_apply_ray(&inv_mx, &r);

		if (!compositor_get_2d_plane_intersection(&r, &local_pt)) return;

		x = local_pt.x;
		y = local_pt.y;
	} else {
		gf_mx2d_copy(inv_2d, tr_state->transform);
		gf_mx2d_inverse(&inv_2d);
		x = tr_state->ray.orig.x;
		y = tr_state->ray.orig.y;
		gf_mx2d_apply_coords(&inv_2d, &x, &y);
	}

	for (i=0; i<count; i++) {
		GF_TextSpan *span = (GF_TextSpan*)gf_list_get(st->spans, i);

		if ((x>=span->bounds.x)
			&& (y<=span->bounds.y) 
			&& (x<=span->bounds.x+span->bounds.width) 
			&& (y>=span->bounds.y-span->bounds.height)) goto picked;

	}
	return;

picked:
	compositor->hit_local_point.x = x;
	compositor->hit_local_point.y = y;
	compositor->hit_local_point.z = 0;

	if (tr_state->visual->type_3d) {
		gf_mx_copy(compositor->hit_world_to_local, tr_state->model_matrix);
		gf_mx_copy(compositor->hit_local_to_world, inv_mx);
	} else {
		gf_mx_from_mx2d(&compositor->hit_world_to_local, &tr_state->transform);
		gf_mx_from_mx2d(&compositor->hit_local_to_world, &inv_2d);
	}

	compositor->hit_node = node;
	compositor->hit_use_dom_events = 0;
	compositor->hit_normal.x = compositor->hit_normal.y = 0; compositor->hit_normal.z = FIX_ONE;
	compositor->hit_texcoords.x = gf_divfix(x, st->bounds.width) + FIX_ONE/2;
	compositor->hit_texcoords.y = gf_divfix(y, st->bounds.height) + FIX_ONE/2;

	if (compositor_is_composite_texture(tr_state->appear)) {
		compositor->hit_appear = tr_state->appear;
	} else {
		compositor->hit_appear = NULL;
	}

	gf_list_reset(tr_state->visual->compositor->sensors);
	count = gf_list_count(tr_state->vrml_sensors);
	for (i=0; i<count; i++) {
		gf_list_add(tr_state->visual->compositor->sensors, gf_list_get(tr_state->vrml_sensors, i));
	}
}


static void text_check_changes(GF_Node *node, TextStack *stack, GF_TraverseState *tr_state)
{
	if (gf_node_dirty_get(node)) {
		text_clean_paths(tr_state->visual->compositor, stack);
		build_text(stack, (M_Text*)node, tr_state);
		gf_node_dirty_clear(node, 0);
		drawable_mark_modified(stack->graph, tr_state);
	}
}


static void Text_Traverse(GF_Node *n, void *rs, Bool is_destroy)
{
	DrawableContext *ctx;
	M_Text *txt = (M_Text *) n;
	TextStack *st = (TextStack *) gf_node_get_private(n);
	GF_TraverseState *tr_state = (GF_TraverseState *)rs;

	if (is_destroy) {
		text_clean_paths(gf_sc_get_compositor(n), st);
		drawable_del(st->graph);
		gf_list_del(st->spans);
		free(st);
		return;
	}

	if (!st->compositor->font_engine) return;
	if (!txt->string.count) return;

	if (tr_state->text_split_mode) {
		gf_node_dirty_clear(n, 0);
		build_text_split(st, txt, tr_state);
		return;
	}

	text_check_changes(n, st, tr_state);

	switch (tr_state->traversing_mode) {
	case TRAVERSE_DRAW_2D:
		text_draw_2d(n, tr_state);
		return;
#ifndef GPAC_DISABLE_3D
	case TRAVERSE_DRAW_3D:
		text_draw_3d(tr_state, n, st);
		return;
#endif
	case TRAVERSE_PICK:
		text_pick(n, st, tr_state);
		return;
	case TRAVERSE_GET_BOUNDS:
		tr_state->bounds = st->bounds;
		return;
	case TRAVERSE_SORT:
		break;
	default:
		return;
	}

#ifndef GPAC_DISABLE_3D
	if (tr_state->visual->type_3d) return;
#endif

	ctx = drawable_init_context_mpeg4(st->graph, tr_state);
	if (!ctx) return;
	ctx->sub_path_index = tr_state->text_split_idx;

	ctx->flags |= CTX_IS_TEXT;
	if (!GF_COL_A(ctx->aspect.fill_color)) {
		/*override line join*/
		ctx->aspect.pen_props.join = GF_LINE_JOIN_MITER;
		ctx->aspect.pen_props.cap = GF_LINE_CAP_FLAT;
	}
	if (ctx->sub_path_index) {
		GF_TextSpan *span = (GF_TextSpan *)gf_list_get(st->spans, ctx->sub_path_index-1);
		if (span) drawable_finalize_sort(ctx, tr_state, &span->bounds);
	} else {
		drawable_finalize_sort(ctx, tr_state, &st->bounds);
	}
}


void compositor_init_text(GF_Compositor *compositor, GF_Node *node)
{
	TextStack *stack = (TextStack *)malloc(sizeof(TextStack));
	stack->graph = drawable_new();
	stack->graph->node = node;
	stack->graph->flags = DRAWABLE_USE_TRAVERSE_DRAW;
	stack->ascent = stack->descent = 0;
	stack->spans = gf_list_new();
	stack->texture_text_flag = 0;

	stack->compositor = compositor;
	gf_node_set_private(node, stack);
	gf_node_set_callback_function(node, Text_Traverse);
}


static void TraverseTextureText(GF_Node *node, void *rs, Bool is_destroy)
{
	TextStack *stack;
	GF_Node *text;
	GF_FieldInfo field;
	if (is_destroy) return;
	if (gf_node_get_field(node, 0, &field) != GF_OK) return;
	if (field.fieldType != GF_SG_VRML_SFNODE) return;
	text = *(GF_Node **)field.far_ptr;
	if (!text) return;

	if (gf_node_get_field(node, 1, &field) != GF_OK) return;
	if (field.fieldType != GF_SG_VRML_SFBOOL) return;

	if (gf_node_get_tag(text) != TAG_MPEG4_Text) return;
	stack = (TextStack *) gf_node_get_private(text);
	stack->texture_text_flag = *(SFBool*)field.far_ptr ? 1 : 0;
}


void compositor_init_texture_text(GF_Compositor *compositor, GF_Node *node)
{
	gf_node_set_callback_function(node, TraverseTextureText);
}

#ifndef GPAC_DISABLE_3D
void compositor_extrude_text(GF_Node *node, GF_TraverseState *tr_state, GF_Mesh *mesh, MFVec3f *thespine, Fixed creaseAngle, Bool begin_cap, Bool end_cap, MFRotation *spine_ori, MFVec2f *spine_scale, Bool txAlongSpine)
{
	u32 i, count;
	Fixed min_cx, min_cy, width_cx, width_cy;
	TextStack *st = (TextStack *) gf_node_get_private(node);

	/*rebuild text node*/
	if (gf_node_dirty_get(node)) {
		ParentNode2D *parent = tr_state->parent;
		tr_state->parent = NULL;
		text_clean_paths(tr_state->visual->compositor, st);
		drawable_reset_path(st->graph);
		gf_node_dirty_clear(node, 0);
		build_text(st, (M_Text *)node, tr_state);
		tr_state->parent = parent;
	}

	min_cx = st->bounds.x;
	min_cy = st->bounds.y - st->bounds.height;
	width_cx = st->bounds.width;
	width_cy = st->bounds.height;

	mesh_reset(mesh);
	count = gf_list_count(st->spans);
	for (i=0; i<count; i++) {
		GF_TextSpan *span = (GF_TextSpan *)gf_list_get(st->spans, i);
		GF_Path *span_path = gf_font_span_create_path(span, 0);
		mesh_extrude_path_ext(mesh, span_path, thespine, creaseAngle, min_cx, min_cy, width_cx, width_cy, begin_cap, end_cap, spine_ori, spine_scale, txAlongSpine);
		gf_path_del(span_path);
	}
	mesh_update_bounds(mesh);
	gf_mesh_build_aabbtree(mesh);
}

#endif



