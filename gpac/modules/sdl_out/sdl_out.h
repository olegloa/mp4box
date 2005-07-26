/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Jean Le Feuvre 2000-2005
 *					All rights reserved
 *
 *  This file is part of GPAC / SDL audio and video module
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


/*driver interfaces*/
#include <gpac/modules/audio_out.h>
#include <gpac/modules/video_out.h>
#include <gpac/thread.h>
#include <gpac/list.h>
/*SDL*/
#include <SDL.h>

/*SDL init routines*/
Bool SDLOUT_InitSDL();
void SDLOUT_CloseSDL();

typedef struct
{
	SDL_Surface *surface;
	u32 pixel_format, id;

} SDLWrapSurface;

typedef struct
{
	GF_Thread *sdl_th;
	GF_Mutex *evt_mx;
	u32 sdl_th_state;
	Bool is_init, fullscreen;
	/*fullscreen display size*/
	u32 fs_width, fs_height;
	/*backbuffer size before entering fullscreen mode (used for restore)*/
	u32 store_width, store_height;
	/*cursors*/
	SDL_Cursor *curs_def, *curs_hand, *curs_collide;
	/*display size*/
	u32 display_width, display_height;

	GF_List *surfaces;

	SDL_Surface *screen;
	SDL_Surface *back_buffer;

	u32 width, height;

	Bool is_3D_out;
	void *os_handle;
} SDLVidCtx;

void SDL_DeleteVideo(void *ifce);
void *SDL_NewVideo();

void SDL_SetupVideo2D(GF_VideoOutput *driv);
GF_Err SDLVid_Blit(GF_VideoOutput *dr, u32 src_id, u32 dst_id, GF_Window *src, GF_Window *dst);

/*soft stretch since SDL doesn't support HW stretch*/
void StretchBits(void *dst, u32 dst_bpp, u32 dst_w, u32 dst_h, u32 dst_pitch,
				void *src, u32 src_bpp, u32 src_w, u32 src_h, u32 src_pitch,
				Bool FlipIt);


/*
			SDL audio
*/
typedef struct
{
	u32 num_buffers, total_duration, delay_ms, total_size;
	Bool is_init, is_running;
} SDLAudCtx;

void SDL_DeleteAudio(void *ifce);
void *SDL_NewAudio();


