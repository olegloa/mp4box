/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Jean Le Feuvre 2000-2005
 *					All rights reserved
 *
 *  This file is part of GPAC / Media terminal sub-project
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



#include <gpac/internal/terminal_dev.h>
#include <gpac/constants.h>
#include <gpac/renderer.h>
#include "media_memory.h"
#include "media_control.h"
#include "input_sensor.h"

/*update config of object*/
void MO_UpdateCaps(GF_MediaObject *mo);


#define TIME_CHECK		3


GF_Err Codec_Load(GF_Codec *codec, GF_ESD *esd, u32 PL);

GF_Codec *gf_codec_new(GF_ObjectManager *odm, GF_ESD *base_layer, s32 PL, GF_Err *e)
{
	GF_Codec *tmp = malloc(sizeof(GF_Codec));
	if (! tmp) {
		*e = GF_OUT_OF_MEM;
		return NULL;
	}
	memset(tmp, 0, sizeof(GF_Codec));
	tmp->odm = odm;

	if (PL<0) PL = 0xFF;
	*e = Codec_Load(tmp, base_layer, PL);
	
	if (*e) {
		free(tmp);
		return NULL;
	}
	/*remember codec type*/
	tmp->type = base_layer->decoderConfig->streamType;
	tmp->inChannels = gf_list_new();	
	tmp->Status = GF_ESM_CODEC_STOP;
	
	return tmp;
}

GF_Codec *gf_codec_use_codec(GF_Codec *codec, GF_ObjectManager *odm)
{
	GF_Codec *tmp;
	if (!codec->decio) return NULL;
	GF_SAFEALLOC(tmp, sizeof(GF_Codec));
	tmp->type = codec->type;
	tmp->inChannels = gf_list_new();	
	tmp->Status = GF_ESM_CODEC_STOP;
	tmp->odm = odm;
	tmp->flags = codec->flags | GF_ESM_CODEC_IS_USE;
	tmp->decio = codec->decio;
	return tmp;
}

GF_Err gf_codec_add_channel(GF_Codec *codec, GF_Channel *ch)
{
	GF_Err e;
	GF_NetworkCommand com;
	GF_Channel *a_ch;
	unsigned char *dsi;
	u32 dsiSize, CUsize, i;
	GF_CodecCapability cap;
	u32 min, max;


	/*only for valid codecs (eg not OCR)*/
	if (codec->decio) {
		com.get_dsi.dsi = NULL;
		dsi = NULL;
		dsiSize = 0;
		if (ch->esd->decoderConfig->upstream) codec->flags |= GF_ESM_CODEC_HAS_UPSTREAM;
		if (ch->esd->decoderConfig->decoderSpecificInfo) {
			dsi = ch->esd->decoderConfig->decoderSpecificInfo->data;
			dsiSize = ch->esd->decoderConfig->decoderSpecificInfo->dataLength;
		} 
		/*ALWAYS override with network DSI if any*/
		com.command_type = GF_NET_CHAN_GET_DSI;
		com.base.on_channel = ch;
		e = gf_term_service_command(ch->service, &com);
		if (!e && com.get_dsi.dsi) {
			dsi = com.get_dsi.dsi;
			dsiSize = com.get_dsi.dsi_len;
		}

		e = codec->decio->AttachStream(codec->decio, ch->esd->ESID, 
					dsi, dsiSize, ch->esd->dependsOnESID, ch->esd->decoderConfig->objectTypeIndication, ch->esd->decoderConfig->upstream);

		if (com.get_dsi.dsi) {
			if (ch->esd->decoderConfig->decoderSpecificInfo->data) free(ch->esd->decoderConfig->decoderSpecificInfo->data);
			ch->esd->decoderConfig->decoderSpecificInfo->data = com.get_dsi.dsi;
			ch->esd->decoderConfig->decoderSpecificInfo->dataLength = com.get_dsi.dsi_len;
		}

		if (e) return e;

		/*ask codec for desired output capacity - note this may be 0 if stream is not yet configured*/
		cap.CapCode = GF_CODEC_OUTPUT_SIZE;
		gf_codec_get_capability(codec, &cap);
		if (codec->CB && (cap.cap.valueInt != codec->CB->UnitSize)) {
			CB_Delete(codec->CB);
			codec->CB = NULL;
		}
		CUsize = cap.cap.valueInt;

		/*get desired amount of units and minimal fullness (used for scheduling)*/
		switch(codec->type) {
		case GF_STREAM_VISUAL:
		case GF_STREAM_AUDIO:
			cap.CapCode = GF_CODEC_BUFFER_MIN;
			gf_codec_get_capability(codec, &cap);
			min = cap.cap.valueInt;
			cap.CapCode = GF_CODEC_BUFFER_MAX;
			gf_codec_get_capability(codec, &cap);
			max = cap.cap.valueInt;
			break;
		default:
			min = max = 0;
		}
		if ((codec->type==GF_STREAM_AUDIO) && (max<2)) max = 2;

		/*setup CB*/
		if (!codec->CB && max) {
			codec->CB = CB_New(CUsize, max);
			codec->CB->Min = min;
			codec->CB->odm = codec->odm;
		}

		/*check re-ordering*/
		cap.CapCode = GF_CODEC_REORDER;
		gf_codec_get_capability(codec, &cap);
		if (cap.cap.valueInt) codec->is_reordering = 1;
	}

	/*assign the first base layer as the codec clock by default, or current channel clock if no clock set
	Also assign codec priority here*/
	if (!ch->esd->dependsOnESID || !codec->ck) {
		codec->ck = ch->clock;
		codec->Priority = ch->esd->streamPriority;
		/*insert base layer first - note we are sure this is a stream of the same type
		as the codec (other streams - OCI, MPEG7, MPEGJ - are not added that way)*/
		return gf_list_insert(codec->inChannels, ch, 0);
	}
	else {
		/*make sure all channels are in order*/
		for (i=0; i<gf_list_count(codec->inChannels); i++) {
			a_ch = gf_list_get(codec->inChannels, i);
			if (ch->esd->dependsOnESID == a_ch->esd->ESID) {
				return gf_list_insert(codec->inChannels, ch, i+1);
			}
			if (a_ch->esd->dependsOnESID == ch->esd->ESID) {
				return gf_list_insert(codec->inChannels, ch, i);
			}
		}
		/*by default append*/
		return gf_list_add(codec->inChannels, ch);
	}
}

Bool gf_codec_remove_channel(GF_Codec *codec, struct _es_channel *ch)
{
	s32 i;
	i = gf_list_find(codec->inChannels, ch);
	if (i>=0) {
		if (codec->decio) codec->decio->DetachStream(codec->decio, ch->esd->ESID);
		gf_list_rem(codec->inChannels, (u32) i);
		return 1;
	}
	return 0;
}


static void codec_update_stats(GF_Codec *codec, u32 dataLength, u32 dec_time)
{
	codec->total_dec_time += dec_time;
	codec->nb_dec_frames++;
	if (dec_time>codec->max_dec_time) codec->max_dec_time = dec_time;

	if (dataLength) {
		u32 now = gf_clock_time(codec->ck);
		if (codec->last_stat_start + 1000 <= now) {
			if (!codec->cur_bit_size) {
				codec->last_stat_start = now;
			} else {
				codec->avg_bit_rate = codec->cur_bit_size;
				if (codec->avg_bit_rate > codec->max_bit_rate) codec->max_bit_rate = codec->avg_bit_rate;
				codec->last_stat_start = now;
				codec->cur_bit_size = 0;
			}
		}
		codec->cur_bit_size += 8*dataLength;
	}
}

/*scalable browsing of input channels: find the AU with the lowest DTS on all input channels*/
void Decoder_GetNextAU(GF_Codec *codec, GF_Channel **activeChannel, LPAUBUFFER *nextAU)
{
	GF_Channel *ch;
	LPAUBUFFER AU;
	u32 count, minDTS, i;
	count = gf_list_count(codec->inChannels);
	*nextAU = NULL;
	*activeChannel = NULL;

	if (!count) return;

	minDTS = (u32) -1;
	/*reverse browsing to make sure we fill enhancement before base layer*/
	for (i=count;i>0;i--) {
		ch = gf_list_get(codec->inChannels, i-1);

		if ((codec->type==GF_STREAM_OCR) && ch->IsClockInit) {
			/*check duration - we assume that scalable OCR streams are just pure nonsense...*/
			if (ch->is_pulling && codec->odm->duration) {
				if (gf_clock_time(codec->ck) > codec->odm->duration) 
					gf_es_on_eos(ch);
			}
			return;
		}

		AU = gf_es_get_au(ch);
		if (!AU) {
			if (! (*activeChannel)) *activeChannel = ch;
			continue;
		}

		/*we use <= to make sure we first fetch data on base layer if same
		DTS (which is possible in spatial scalability)*/
		if (AU->DTS <= minDTS) {
			minDTS = AU->DTS;
			*activeChannel = ch;
			*nextAU = AU;
		}
	}

	/*FIXME - we're breaking sync (couple of frames delay)*/
	if (*nextAU && codec->is_reordering) (*nextAU)->CTS = (*nextAU)->DTS;
}


GF_Err SystemCodec_Process(GF_Codec *codec, u32 TimeAvailable)
{
	LPAUBUFFER AU;
	GF_Channel *ch;
	u32 now, obj_time;
	Bool scene_locked, invalidate_scene;
	Bool check_next_unit;
	GF_SceneDecoder *sdec = (GF_SceneDecoder *)codec->decio;
	GF_Err e = GF_OK;

	scene_locked = 0;
	invalidate_scene = 0;
	
	/*for resync, if needed - the logic behind this is that there is no composition memory on sytems codecs so
	"frame dropping" is done by preventing the renderer from redrawing after an update and decoding following AU
	so that the renderer is always woken up once all late systems AUs are decoded. This flag is overriden when 
	seeking*/
	check_next_unit = codec->odm->term->bifs_can_resync;
	
check_unit:

	/*muting systems codec means we don't decode until mute is off - likely there will be visible however
	there is no other way to decode system AUs without modifying the content, which is what mute is about on visual...*/
	if (codec->Muted) goto exit;

	/*fetch next AU in DTS order for this codec*/
	Decoder_GetNextAU(codec, &ch, &AU);

	/*get the object time*/
	obj_time = gf_clock_time(codec->ck);

	/*no active channel return*/
	if (!AU || !ch) {
		/*if the codec is in EOS state, move to STOP*/
		if (codec->Status == GF_ESM_CODEC_EOS) {
			GF_CodecCapability cap;
			cap.CapCode = GF_CODEC_MEDIA_NOT_OVER;
			cap.cap.valueInt = 0;
			sdec->GetCapabilities(codec->decio, &cap);
			if (!cap.cap.valueInt) {
				gf_mm_stop_codec(codec);
				if ((codec->type==GF_STREAM_OD) && (codec->nb_dec_frames==1)) {
					/*this is just by safety, since seeking is only allowed when a single clock is present 
					in the scene*/
					if (gf_list_count(codec->odm->net_service->Clocks)==1)
						codec->odm->subscene->static_media_ressources=1;
				}
			}
		}
		goto exit;
	}
	

	/*clock is not started*/
	if (ch->first_au_fetched && !gf_clock_is_started(ch->clock)) goto exit;

	/*check timing based on the input channel and main FPS*/
	if ( (AU->DTS > obj_time + codec->odm->term->half_frame_duration) ) goto exit;

	/*check seeking and update timing - do NOT use the base layer, since BIFS streams may depend on other 
	streams not on the same clock*/
	if (codec->last_unit_cts == AU->CTS ) {
		/*hack for RTSP streaming of systems streams, except InputSensor*/
		if (!ch->is_pulling && (codec->type != GF_STREAM_INTERACT) && (AU->dataLength == codec->prev_au_size)) {
			gf_es_drop_au(ch);
			fprintf(stdout, "Same MPEG-4 Systems AU detected - dropping\n");
			goto check_unit;
		}
		/*seeking for systems is done by not releasing the graph until seek is done*/
		check_next_unit = 1;
	} 
	/*set system stream timing*/
	else {
		codec->last_unit_cts = AU->CTS;
	}

	/*lock scene*/
	if (!scene_locked) {
		gf_term_lock_renderer(codec->odm->term, 1);
		scene_locked = 1;
	}

	/*current media time for system objects is the clock time, since the media is likely to have random 
	updates in time*/
	codec->odm->current_time = gf_clock_time(codec->ck);

	now = gf_term_get_time(codec->odm->term);
	e = sdec->ProcessData(sdec, (unsigned char *) AU->data, AU->dataLength, ch->esd->ESID, codec->odm->current_time, GF_CODEC_LEVEL_NORMAL);
	now = gf_term_get_time(codec->odm->term) - now;

	codec_update_stats(codec, AU->dataLength, now);
	codec->prev_au_size = AU->dataLength;

	/*destroy this AU*/
	gf_es_drop_au(ch);

	if (e) goto exit;
	/*dynamic OD stream, regenerate scene*/
	if (codec->flags & GF_ESM_CODEC_IS_STATIC_OD) gf_is_regenerate(codec->odm->subscene ? codec->odm->subscene : codec->odm->parentscene);

	/*always force redraw for system codecs*/
	invalidate_scene = 1;

	/*if no release restart*/
	if (check_next_unit) goto check_unit;
	
exit:
	if (scene_locked) gf_term_lock_renderer(codec->odm->term, 0);
	if (invalidate_scene) gf_term_invalidate_renderer(codec->odm->term);
	return e;
}



/*special handling of decoders not using ESM*/
GF_Err PrivateScene_Process(GF_Codec *codec, u32 TimeAvailable)
{
	Bool resume_clock;
	u32 now;
	GF_Channel *ch;
	GF_SceneDecoder *sdec = (GF_SceneDecoder *)codec->decio;
	GF_Err e = GF_OK;
	
	/*muting systems codec means we don't decode until mute is off - likely there will be visible however
	there is no other way to decode system AUs without modifying the content, which is what mute is about on visual...*/
	if (codec->Muted) return GF_OK;

	if (codec->Status == GF_ESM_CODEC_EOS) {
		gf_mm_stop_codec(codec);
		return GF_OK;
	}

	ch = gf_list_get(codec->inChannels, 0);
	if (!ch) return GF_OK;
	resume_clock = 0;
	/*init channel clock*/
	if (!ch->IsClockInit) {
		gf_es_init_dummy(ch);
		if (!gf_clock_is_started(ch->clock)) return GF_OK;
		/*let's be nice to the scene loader (that usually involves quite some parsing), pause clock while
		parsing*/
		gf_clock_pause(ch->clock);
		codec->last_unit_dts = 0;
	}

	codec->odm->current_time = codec->last_unit_cts = gf_clock_time(codec->ck);

	/*lock scene*/
	gf_term_lock_renderer(codec->odm->term, 1);
	now = gf_term_get_time(codec->odm->term);
	e = sdec->ProcessData(sdec, NULL, codec->odm->current_time, ch->esd->ESID, codec->odm->current_time, GF_CODEC_LEVEL_NORMAL);
	now = gf_term_get_time(codec->odm->term) - now;
	codec->last_unit_dts ++;
	/*resume on error*/
	if (e && (codec->last_unit_dts<2) ) {
		gf_clock_resume(ch->clock);
		codec->last_unit_dts = 2;
	}
	/*resume clock on 2nd decode (we assume parsing is done in 2 steps, one for first frame display, one for complete parse)*/
	else if (codec->last_unit_dts==2) {
		gf_clock_resume(ch->clock);
	}

	codec_update_stats(codec, 0, now);

	gf_term_lock_renderer(codec->odm->term, 0);
	gf_term_invalidate_renderer(codec->odm->term);

	if (e==GF_EOS) {
		/*first end of stream, evaluate duration*/
		if (!codec->odm->duration) gf_odm_set_duration(codec->odm, ch, codec->odm->current_time);
		gf_es_on_eos(ch);
		return GF_OK;
	}
	return e;
}
/*Get a pointer to the next CU buffer*/
static GFINLINE GF_Err LockCompositionUnit(GF_Codec *dec, u32 CU_TS, char **outBuffer, u32 *availableSize)
{
	LPCUBUFFER cu;
	*outBuffer = NULL;
	*availableSize = 0;

	if (!dec->CB) return GF_BAD_PARAM;
	
	cu = CB_LockInput(dec->CB, CU_TS);
	if (!cu ) return GF_OUT_OF_MEM;
	
	cu->TS = CU_TS;
	*outBuffer = cu->data;
	*availableSize = dec->CB->UnitSize;
	return GF_OK;
}


static GFINLINE GF_Err UnlockCompositionUnit(GF_Codec *dec, u32 CTS, u32 NbBytes)
{
	/*temporal scalability disabling: if we already rendered this, no point getting further*/
	if (CTS < dec->CB->LastRenderedTS) NbBytes = 0;

	/*unlock the CB*/
	CB_UnlockInput(dec->CB, CTS, NbBytes);
	return GF_OK;
}


static GF_Err ResizeCompositionBuffer(GF_Codec *dec, u32 NewSize)
{
	if (!dec || !dec->CB) return GF_BAD_PARAM;
	
	/*update config*/
	MO_UpdateCaps(dec->odm->mo);

	/*bytes per sec not available: either video or audio not configured*/
	if (!dec->bytes_per_sec) {
		if (NewSize && (NewSize != dec->CB->UnitSize) ) CB_ResizeBuffers(dec->CB, NewSize);
	} 
	/*audio: make sure we have enough data in CM to entirely fill the HW audio buffer...*/
	else {
		u32 unit_size, audio_buf_len, unit_count;
		GF_CodecCapability cap;
		unit_size = NewSize;
		audio_buf_len = gf_sr_get_audio_buffer_length(dec->odm->term->renderer);
		if (audio_buf_len < 400) audio_buf_len = 400;
		cap.CapCode = GF_CODEC_BUFFER_MAX;
		gf_codec_get_capability(dec, &cap);
		unit_count = cap.cap.valueInt;
		/*at least 2 units for dec and render ...*/
		if (unit_count<2) unit_count = 2;
		while (unit_size*unit_count*1000 < dec->bytes_per_sec*audio_buf_len) unit_count++;

		CB_Reinit(dec->CB, unit_size, unit_count);
		dec->CB->Min = unit_size/3;
		if (!dec->CB->Min) dec->CB->Min = 1;
	}
	if ((dec->type==GF_STREAM_VISUAL) && dec->odm->parentscene->is_dynamic_scene) {
		void gf_is_force_scene_size_video(GF_InlineScene *is, GF_MediaObject *mo);

		gf_is_force_scene_size_video(dec->odm->parentscene, dec->odm->mo);
	}
	return GF_OK;
}

GF_Err MediaCodec_Process(GF_Codec *codec, u32 TimeAvailable)
{
	LPAUBUFFER AU;
	GF_Channel *ch;
	char *cu_buf;
	u32 cu_buf_size, mmlevel;
	u32 first, entryTime, now, obj_time;
	GF_MediaDecoder *mdec = (GF_MediaDecoder*)codec->decio;
	GF_Err e = GF_OK;

	/*if video codec muted don't decode (try to saves ressources)
	if audio codec muted we dispatch to keep sync in place*/
	if (codec->Muted && (codec->type==GF_STREAM_VISUAL) ) return GF_OK;

	entryTime = gf_term_get_time(codec->odm->term);

	/*fetch next AU in DTS order for this codec*/
	Decoder_GetNextAU(codec, &ch, &AU);
	/*no active channel return*/
	if (!AU || !ch) {
		/*if the codec is in EOS state, assume we're done*/
		if (codec->Status == GF_ESM_CODEC_EOS) {
			/*if codec is reordering, try to flush it*/
			if (codec->is_reordering) {
				if ( LockCompositionUnit(codec, codec->last_unit_cts+1, &cu_buf, &cu_buf_size) == GF_OUT_OF_MEM) 
					return GF_OK;
				e = mdec->ProcessData(mdec, NULL, 0, 0, cu_buf, &cu_buf_size, 0, 0);
				if (e==GF_OK) e = UnlockCompositionUnit(codec, codec->last_unit_cts+1, cu_buf_size);
			}
			gf_mm_stop_codec(codec);
			if (codec->CB) CB_SetEndOfStream(codec->CB);
		}
		/*if no data, and channel not buffering, ABORT CB buffer (data timeout or EOS not detectable)*/
		else if (ch && !ch->BufferOn) 
			CB_AbortBuffering(codec->CB);
		return GF_OK;
	}

	/*get the object time*/
	obj_time = gf_clock_time(codec->ck);
	/*Media Time for media codecs is updated in the CB*/

	if (!codec->CB) {
		gf_es_drop_au(ch);
		return GF_BAD_PARAM;
	}
	/*image codecs - usually only one image is tolerated in the stream, but just in case force reset of CB*/
	if ((codec->CB->Capacity==1) && codec->CB->UnitCount && (obj_time>=AU->CTS)) {
		codec->CB->output->dataLength = 0;
		codec->CB->UnitCount = 0;
	}

	/*try to refill the full buffer*/
	first = 1;
	while (codec->CB->Capacity > codec->CB->UnitCount) {
		/*set media processing level*/
		mmlevel = GF_CODEC_LEVEL_NORMAL;
		/*SEEK: if the last frame had the same TS, we are seeking. Ask the codec to drop*/
		if (!ch->skip_sl && codec->last_unit_cts && (codec->last_unit_cts == AU->CTS) && !ch->esd->dependsOnESID) {
			mmlevel = GF_CODEC_LEVEL_SEEK;
		}
		/*only perform drop in normal playback*/
		else if (codec->CB->Status == CB_PLAY) {
			if (!ch->skip_sl && (AU->CTS < obj_time) ) {
				/*extremely late, even if we decode the renderer will drop the frame 
				so set the level to drop*/
				mmlevel = GF_CODEC_LEVEL_DROP;
			}
			/*we are late according to the media manager*/
			else if (codec->PriorityBoost) {
				mmlevel = GF_CODEC_LEVEL_VERY_LATE;
			}
			/*otherwise we must have an idea of the load in order to set the right level
			use the composition buffer for that, only on the first frame*/
			else if (first) {
				//if the CB is almost empty set to very late
				if (codec->CB->UnitCount <= codec->CB->Min+1) {
					mmlevel = GF_CODEC_LEVEL_VERY_LATE;
				} else if (codec->CB->UnitCount * 2 <= codec->CB->Capacity) {
					mmlevel = GF_CODEC_LEVEL_LATE;
				}
				first = 0;
			}
		}

		/*when using temporal scalability make sure we can decode*/
		if (ch->esd->dependsOnESID && (codec->last_unit_dts > AU->DTS)){
//			printf("SCALABLE STREAM DEAD!!\n");
			goto drop;
		}

		if (ch->skip_sl) {
			if (codec->bytes_per_sec) {
				AU->CTS = codec->last_unit_cts + ch->ts_offset + codec->cur_audio_bytes * 1000 / codec->bytes_per_sec;
			} else if (codec->fps) {
				AU->CTS = codec->last_unit_cts + ch->ts_offset + (u32) (codec->cur_video_frames * 1000 / codec->fps);
			}
		}

		if ( LockCompositionUnit(codec, AU->CTS, &cu_buf, &cu_buf_size) == GF_OUT_OF_MEM) 
			return GF_OK;

		now = gf_term_get_time(codec->odm->term);

		e = mdec->ProcessData(mdec, AU->data, AU->dataLength, 
			ch->esd->ESID, cu_buf, &cu_buf_size, AU->PaddingBits, mmlevel);
		now = gf_term_get_time(codec->odm->term) - now;

		/*input is too small, resize composition memory*/
		switch (e) {
		case GF_BUFFER_TOO_SMALL:
			/*release but no dispatch*/
			UnlockCompositionUnit(codec, AU->CTS, 0);
			if (ResizeCompositionBuffer(codec, cu_buf_size)==GF_OK) continue;
			break;
		case GF_OK:
			/*if seek/drop don't dispatch any output*/
			if (mmlevel == GF_CODEC_LEVEL_SEEK) cu_buf_size = 0;
			//if ((mmlevel == GF_CODEC_LEVEL_SEEK) || ((mmlevel == GF_CODEC_LEVEL_DROP) && (codec->type!=GF_STREAM_AUDIO)) ) cu_buf_size = 0;
			e = UnlockCompositionUnit(codec, AU->CTS, cu_buf_size);
			codec_update_stats(codec, AU->dataLength, now);
			if (ch->skip_sl) {
				if (codec->bytes_per_sec) {
					codec->cur_audio_bytes += cu_buf_size;
					while (codec->cur_audio_bytes>codec->bytes_per_sec) {
						codec->cur_audio_bytes -= codec->bytes_per_sec;
						codec->last_unit_cts += 1000;
					}
				} else if (codec->fps && cu_buf_size) {
					codec->cur_video_frames += 1;
				}
			}
			break;
		/*this happens a lot when using non-MPEG-4 streams (ex: ffmpeg demuxer)*/
		case GF_PACKED_FRAMES:
			/*in seek don't dispatch any output*/
			if (mmlevel	== GF_CODEC_LEVEL_SEEK) cu_buf_size = 0;
			e = UnlockCompositionUnit(codec, AU->CTS, cu_buf_size);

			if (ch->skip_sl) {
				if (codec->bytes_per_sec) {
					codec->cur_audio_bytes += cu_buf_size;
				} else if (codec->fps && cu_buf_size) {
					codec->cur_video_frames += 1;
				}
			} else {
			  u32 deltaTS = 0;
				if (codec->bytes_per_sec) {
					deltaTS = cu_buf_size * 1000 / codec->bytes_per_sec;
				} else if (codec->fps && cu_buf_size) {
					deltaTS = (u32) (1000.0f / codec->fps);
				}
				AU->DTS += deltaTS;
				AU->CTS += deltaTS;
			}
			codec_update_stats(codec, 0, now);
			continue;
		default:
			UnlockCompositionUnit(codec, AU->CTS, 0);
			/*error - if the object is in intitial buffering resume it!!*/
			CB_AbortBuffering(codec->CB);
			break;
		}

		codec->last_unit_dts = AU->DTS;
		/*remember base layer timing*/
		if (!ch->esd->dependsOnESID && !ch->skip_sl) codec->last_unit_cts = AU->CTS;

drop:
		gf_es_drop_au(ch);
		if (e) return e;

		/*escape from decoding loop only if above critical limit - this is to avoid starvation on audio*/
		if (codec->CB->UnitCount > codec->CB->Min) {
			now = gf_term_get_time(codec->odm->term);
			if (now - entryTime >= TimeAvailable - TIME_CHECK) {
				return GF_OK;
			}
		}

		Decoder_GetNextAU(codec, &ch, &AU);
		if (!ch || !AU) return GF_OK;
	}
	return GF_OK;
}



GF_Err gf_codec_process(GF_Codec *codec, u32 TimeAvailable)
{
	if (codec->Status == GF_ESM_CODEC_STOP) return GF_OK;
	codec->Muted = (codec->odm->media_ctrl && codec->odm->media_ctrl->control->mute) ? 1 : 0;

	/*OCR: needed for OCR in pull mode (dummy streams used to sync various sources)*/
	if (codec->type==GF_STREAM_OCR) {
		LPAUBUFFER AU;
		GF_Channel *ch;
		/*fetch next AU on OCR (empty AUs)*/
		Decoder_GetNextAU(codec, &ch, &AU);

		/*no active channel return*/
		if (!AU || !ch) {
			/*if the codec is in EOS state, move to STOP*/
			if (codec->Status == GF_ESM_CODEC_EOS) {
				gf_mm_stop_codec(codec);
				/*if a mediacontrol is ruling this OCR*/
				if (codec->odm->media_ctrl && codec->odm->media_ctrl->control->loop) MC_Restart(codec->odm); 
			}
		}
	}
	/*special case here (we tweak a bit the timing)*/
	else if (codec->type==GF_STREAM_PRIVATE_SCENE) {
		return PrivateScene_Process(codec, TimeAvailable);
	} else if (codec->decio->InterfaceType==GF_MEDIA_DECODER_INTERFACE) {
		return MediaCodec_Process(codec, TimeAvailable);
	} else if (codec->decio->InterfaceType==GF_SCENE_DECODER_INTERFACE) {
		return SystemCodec_Process(codec, TimeAvailable);
	}
	return GF_OK;
}


GF_Err gf_codec_get_capability(GF_Codec *codec, GF_CodecCapability *cap)
{
	cap->cap.valueInt = 0;
	if (!codec->decio) return GF_OK;
	return codec->decio->GetCapabilities(codec->decio, cap);
}

GF_Err gf_codec_set_capability(GF_Codec *codec, GF_CodecCapability cap)
{
	if (!codec->decio) return GF_OK;
	return codec->decio->SetCapabilities(codec->decio, cap);
}


void gf_codec_set_status(GF_Codec *codec, u32 Status)
{
	if (!codec) return;

	if (Status == GF_ESM_CODEC_PAUSE) codec->Status = GF_ESM_CODEC_STOP;
	else if (Status == GF_ESM_CODEC_BUFFER) codec->Status = GF_ESM_CODEC_PLAY;
	else if (Status == GF_ESM_CODEC_PLAY) {
		codec->last_unit_cts = 0;
		codec->prev_au_size = 0;
		codec->Status = Status;
		codec->last_stat_start = codec->cur_bit_size = codec->max_bit_rate = codec->avg_bit_rate = 0;
		codec->nb_dec_frames = codec->total_dec_time = codec->max_dec_time = 0;
		codec->cur_audio_bytes = codec->cur_video_frames = 0;
	}
	else codec->Status = Status;

	if (!codec->CB) return;
	
	/*notify CB*/
	switch (Status) {
	case GF_ESM_CODEC_PLAY:
		CB_SetStatus(codec->CB, CB_PLAY);
		return;
	case GF_ESM_CODEC_PAUSE:
		CB_SetStatus(codec->CB, CB_PAUSE);
		return;
	case GF_ESM_CODEC_STOP:
		CB_SetStatus(codec->CB, CB_STOP);
		return;
	case GF_ESM_CODEC_EOS:
		/*do NOT notify CB yet, wait till last AU is decoded*/
		return;
	case GF_ESM_CODEC_BUFFER:
	default:
		return;
	}
}

static GF_Err Codec_LoadModule(GF_Codec *codec, GF_ESD *esd, u32 PL)
{
	char szPrefDec[500];
	const char *sOpt;
	GF_BaseDecoder *ifce;
	u32 i, plugCount;
	u32 ifce_type;
	char *cfg;
	u32 cfg_size;
	GF_Terminal *term = codec->odm->term;


	if (esd->decoderConfig->decoderSpecificInfo) {
		cfg = esd->decoderConfig->decoderSpecificInfo->data;
		cfg_size = esd->decoderConfig->decoderSpecificInfo->dataLength;
	} else {
		cfg = NULL;
		cfg_size = 0;
	}

	ifce_type = GF_SCENE_DECODER_INTERFACE;
	if ((esd->decoderConfig->streamType==GF_STREAM_AUDIO) || (esd->decoderConfig->streamType==GF_STREAM_VISUAL))
		ifce_type = GF_MEDIA_DECODER_INTERFACE;

	/*a bit dirty, if FFMPEG is used for demuxer load it for decoder too*/
	if (0 && !stricmp(codec->odm->net_service->ifce->module_name, "FFMPEG demuxer")) {
		sOpt = "FFMPEG decoder";
	} else {
		/*use user-defined module if any*/
		sOpt = NULL;
		switch (esd->decoderConfig->streamType) {
		case GF_STREAM_VISUAL:
			sOpt = gf_cfg_get_key(term->user->config, "Systems", "DefVideoDec");
			break;
		case GF_STREAM_AUDIO:
			sOpt = gf_cfg_get_key(term->user->config, "Systems", "DefAudioDec");
			break;
		default:
			break;
		}
	}
	
	if (sOpt) {
		ifce = (GF_BaseDecoder *) gf_modules_load_interface_by_name(term->user->modules, sOpt, ifce_type);
		if (ifce) {
			if (ifce->CanHandleStream && ifce->CanHandleStream(ifce, esd->decoderConfig->streamType, esd->decoderConfig->objectTypeIndication, cfg, cfg_size, PL) ) {
				codec->decio = ifce;
				return GF_OK;
			}
			gf_modules_close_interface((GF_BaseInterface *) ifce);		
		}
	}

	/*prefered codec module per streamType/objectType from config*/
	sprintf(szPrefDec, "codec_%02x_%02x", esd->decoderConfig->streamType, esd->decoderConfig->objectTypeIndication);
	sOpt = gf_cfg_get_key(term->user->config, "Systems", szPrefDec);
	if (sOpt) {
		ifce = (GF_BaseDecoder *) gf_modules_load_interface_by_name(term->user->modules, sOpt, ifce_type);
		if (ifce) {
			if (ifce->CanHandleStream && ifce->CanHandleStream(ifce, esd->decoderConfig->streamType, esd->decoderConfig->objectTypeIndication, cfg, cfg_size, PL) ) {
				codec->decio = ifce;
				return GF_OK;
			}
			gf_modules_close_interface((GF_BaseInterface *) ifce);		
		}
	}
	/*not found, check all modules*/
	plugCount = gf_modules_get_count(term->user->modules);
	for (i = 0; i < plugCount ; i++) {
		ifce = (GF_BaseDecoder *) gf_modules_load_interface(term->user->modules, i, ifce_type);
		if (!ifce) continue;
		if (ifce->CanHandleStream && ifce->CanHandleStream(ifce, esd->decoderConfig->streamType, esd->decoderConfig->objectTypeIndication, cfg, cfg_size, PL) ) {
			codec->decio = ifce;
			return GF_OK;
		}
		gf_modules_close_interface((GF_BaseInterface *) ifce);
	}
	return GF_CODEC_NOT_FOUND;
}

GF_Err Codec_Load(GF_Codec *codec, GF_ESD *esd, u32 PL)
{
	switch (esd->decoderConfig->streamType) {
	/*OCR has no codec, just a channel*/
	case GF_STREAM_OCR:
		codec->decio = NULL;
		return GF_OK;
	/*InteractionStream is currently hardcoded*/
	case GF_STREAM_INTERACT:
		codec->decio = (GF_BaseDecoder *) NewISCodec(PL);
		assert(codec->decio->InterfaceType == GF_SCENE_DECODER_INTERFACE);
		return GF_OK;

	/*load decoder module*/
	default:
		return Codec_LoadModule(codec, esd, PL);
	}
}


void gf_codec_del(GF_Codec *codec)
{
	if (gf_list_count(codec->inChannels)) return;

	if (!(codec->flags & GF_ESM_CODEC_IS_USE)) {
		switch (codec->type) {
		/*input sensor streams are handled internally for now*/
		case GF_STREAM_INTERACT:
			gf_mx_p(codec->odm->term->net_mx);
			ISDec_Delete(codec->decio);
			gf_list_del_item(codec->odm->term->input_streams, codec);
			gf_mx_v(codec->odm->term->net_mx);
			break;
		default:
			gf_modules_close_interface((GF_BaseInterface *) codec->decio);
			break;
		}
	}
	if (codec->CB) CB_Delete(codec->CB);
	gf_list_del(codec->inChannels);
	free(codec);
}


