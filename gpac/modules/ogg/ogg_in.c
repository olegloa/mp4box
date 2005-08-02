/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Jean Le Feuvre 2000-2005 
 *					All rights reserved
 *
 *  This file is part of GPAC / XIPH.org module
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

#include "ogg_in.h"
#include <ogg/ogg.h>

typedef struct 
{
	u32 streamType;	/*MPEG-4 streamType*/
	u32 num_init_headers;
	u32 sample_rate, bitrate;

	u32 theora_kgs;
	Float frame_rate;
	u32 frame_rate_base;

	u32 type;
} OGGInfo;

typedef struct
{
	ogg_stream_state os;
	u32 serial_no;
	/*DSI for ogg in mp4 - cf constants.h*/
	char *dsi;
	u32 dsi_len;

	OGGInfo info;
	Bool got_headers;
	s64 seek_granule, last_granule;

	Bool is_running;
	u32 parse_headers;
	LPNETCHANNEL ch;
	u16 ESID;
	Bool eos_detected, map_time;

	u32 ogg_ts;

	GF_VorbisParser vp;
} OGGStream;

typedef struct
{
	GF_ClientService *service;
	GF_Thread *demuxer;

	GF_List *streams;

	FILE *ogfile;
	u32 file_size;
	Bool is_remote;
	u32 nb_playing, kill_demux, do_seek, service_type, init_remain, bos_done;

	/*ogg ogfile state*/
    ogg_sync_state oy;
	
	Bool needs_od;
	OGGStream *resync_stream;

	Bool has_video, has_audio, is_single_media;

	Double dur;
	u32 data_buffer_ms;

	LPNETCHANNEL od_ch;

	Bool needs_connection;
	Double start_range, end_range;
	/*file downloader*/
	GF_DownloadSession * dnload;
	Bool is_live;
	u32 tune_in_time;
} OGGReader;


void OGG_EndOfFile(OGGReader *read)
{
	u32 i;
	gf_term_on_sl_packet(read->service, read->od_ch, NULL, 0, NULL, GF_EOS);
	for (i=0; i<gf_list_count(read->streams); i++) {
		OGGStream *st = gf_list_get(read->streams, i);
		gf_term_on_sl_packet(read->service, st->ch, NULL, 0, NULL, GF_EOS);
	}
}

#define OGG_BUFFER_SIZE 4096

static Bool OGG_ReadPage(OGGReader *read, ogg_page *oggpage)
{
	char buf[OGG_BUFFER_SIZE];
	GF_Err e;

	/*remote file, check if we use cache*/
	if (read->is_remote) {
		u32 total_size, status;
		e = gf_dm_sess_get_stats(read->dnload, NULL, NULL, &total_size, NULL, NULL, &status);
		/*not ready*/
		if ((e<GF_OK) || (status > GF_DOWNLOAD_STATE_RUNNING)) return 0;
		if (status == GF_DOWNLOAD_STATE_RUNNING) {
			if (!total_size && !read->is_live) {
				read->is_live = 1;
				read->tune_in_time = gf_sys_clock();
			}
			else if (!read->is_live  && !read->ogfile) {
				const char *szCache = gf_dm_sess_get_cache_name(read->dnload);
				if (!szCache) return 0;
				read->ogfile = fopen((char *) szCache, "rb");
				if (!read->ogfile) return 0;
			}
		}
	}

    while (ogg_sync_pageout(&read->oy, oggpage ) != 1 ) {
        char *buffer;
		u32 bytes;
		
		if (read->ogfile) {
			if (feof(read->ogfile)) {
				OGG_EndOfFile(read);
				return 0;
			}
			bytes = fread(buf, 1, OGG_BUFFER_SIZE, read->ogfile);
		} else {
			e = gf_dm_sess_fetch_data(read->dnload, buf, OGG_BUFFER_SIZE, &bytes);
			if (e) return 0;
		}
		if (!bytes) return 0;
		buffer = ogg_sync_buffer(&read->oy, bytes);
		memcpy(buffer, buf, bytes);
        ogg_sync_wrote(&read->oy, bytes);
    }
    return 1;
}

static OGGStream *OGG_FindStreamForPage(OGGReader *read, ogg_page *oggpage)
{
	u32 i, count;
	count = gf_list_count(read->streams);
	for (i=0; i<count; i++) {
		OGGStream *st = gf_list_get(read->streams, i);
        if (ogg_stream_pagein(&st->os, oggpage) == 0) return st;
	}
	return NULL;
}



static GF_ObjectDescriptor *OGG_GetOD(OGGStream *st, Bool no_ocr)
{
	GF_ObjectDescriptor *od;
	GF_ESD *esd;

	od = (GF_ObjectDescriptor *) gf_odf_desc_new(GF_ODF_OD_TAG);
	od->objectDescriptorID = (st->info.streamType==GF_STREAM_AUDIO) ? 3 : 2;
	esd = gf_odf_desc_esd_new(0);
	esd->decoderConfig->streamType = st->info.streamType;
	esd->decoderConfig->objectTypeIndication = GPAC_OGG_MEDIA_OTI;
	esd->decoderConfig->avgBitrate = st->info.bitrate;
	esd->ESID = st->ESID;
	esd->OCRESID = no_ocr ? 0 : 1;

	esd->slConfig->useTimestampsFlag = 1;
	esd->slConfig->useAccessUnitEndFlag = esd->slConfig->useAccessUnitStartFlag = 1;
	esd->slConfig->timestampResolution = st->info.sample_rate ? st->info.sample_rate : (u32) (1000*st->info.frame_rate);
	if (st->info.sample_rate) esd->slConfig->hasRandomAccessUnitsOnlyFlag = 1;
	else esd->slConfig->useRandomAccessPointFlag = 1;

	esd->decoderConfig->decoderSpecificInfo->dataLength = st->dsi_len;
	GF_SAFEALLOC(esd->decoderConfig->decoderSpecificInfo->data, st->dsi_len);
	memcpy(esd->decoderConfig->decoderSpecificInfo->data, st->dsi, sizeof(char) * st->dsi_len);
	gf_list_add(od->ESDescriptors, esd);
	return od;
}

static void OGG_SendStreams(OGGReader *read)
{
	GF_ODCodec *codec;
	char *data;
	u32 data_len, i;
	SLHeader slh;
	GF_ODUpdate *odU;
	GF_ObjectDescriptor *od;

	if (!read->needs_od) return;
	read->needs_od = 0;

	codec = gf_odf_codec_new();
	odU = (GF_ODUpdate *) gf_odf_com_new(GF_ODF_OD_UPDATE_TAG);

	/*this will NOT work properly for multi track files other than 1 video, 1 audio*/
	for (i=0; i<gf_list_count(read->streams); i++) {
		OGGStream *st = gf_list_get(read->streams, i);
		od = OGG_GetOD(st, 0);
		gf_list_add(odU->objectDescriptors, od);
	}
	gf_odf_codec_add_com(codec, (GF_ODCom *)odU);
	gf_odf_codec_encode(codec);
	data = NULL;
	data_len = 0;
	gf_odf_codec_get_au(codec, &data, &data_len);
	gf_odf_codec_del(codec);
	memset(&slh, 0, sizeof(SLHeader));
	slh.accessUnitEndFlag = slh.accessUnitStartFlag = 1;
	slh.compositionTimeStampFlag = 1;
	slh.compositionTimeStamp = (u64) (read->start_range * 1000);
	slh.randomAccessPointFlag = 1;
	gf_term_on_sl_packet(read->service, read->od_ch, data, data_len, &slh, GF_OK);
	free(data);
}

u64 OGG_GranuleToTime(OGGInfo *cfg, s64 granule)
{
	if (cfg->sample_rate) {
		return granule;
	}
	if (cfg->frame_rate) {
		s64 iframe = granule>>cfg->theora_kgs;
		s64 pframe = granule - (iframe<<cfg->theora_kgs);
		pframe += iframe;
		pframe *= cfg->frame_rate_base;
		return (u64) (pframe / cfg->frame_rate);
	}
	return 0;
}

Double OGG_GranuleToMediaTime(OGGInfo *cfg, s64 granule)
{
	Double t = (Double) (s64) OGG_GranuleToTime(cfg, granule);
	if (cfg->sample_rate) t /= cfg->sample_rate;
	else t /= cfg->frame_rate_base;
	return t;
}


static void OGG_GetStreamInfo(ogg_packet *oggpacket, OGGInfo *info) 
{
	oggpack_buffer opb;

	memset(info, 0, sizeof(OGGInfo));

	/*vorbis*/
	if ((oggpacket->bytes >= 7) && !strncmp(&oggpacket->packet[1], "vorbis", 6)) {
		info->streamType = GF_STREAM_AUDIO;
		oggpack_readinit(&opb, oggpacket->packet, oggpacket->bytes);
		oggpack_adv( &opb, 88);
		oggpack_adv( &opb, 8);	/*nb chan*/
		info->sample_rate = oggpack_read(&opb, 32);
		oggpack_adv( &opb, 32);	/*max rate*/
		info->bitrate = oggpack_read(&opb, 32);
		info->num_init_headers = 3;
		info->type = OGG_VORBIS;
	}
	/*speex*/
	else if ((oggpacket->bytes >= 7) && !strncmp(&oggpacket->packet[0], "Speex", 5)) {
		info->streamType = GF_STREAM_AUDIO;
		oggpack_readinit(&opb, oggpacket->packet, oggpacket->bytes);
		oggpack_adv(&opb, 224);
		oggpack_adv(&opb, 32);
		oggpack_adv( &opb, 32);
		info->sample_rate = oggpack_read(&opb, 32);
		info->type = OGG_SPEEX;
		info->num_init_headers = 1;
	}
	/*flac*/
	else if ((oggpacket->bytes >= 4) && !strncmp(&oggpacket->packet[0], "fLaC", 4)) {
		info->streamType = GF_STREAM_AUDIO;
		info->type = 3;
		info->num_init_headers = OGG_FLAC;
	}
	/*theora*/
	else if ((oggpacket->bytes >= 7) && !strncmp(&oggpacket->packet[1], "theora", 6)) {
		GF_BitStream *bs;
		u32 fps_numerator, fps_denominator, keyframe_freq_force;

		info->streamType = GF_STREAM_VISUAL;
		info->type = OGG_THEORA;
		bs = gf_bs_new(oggpacket->packet, oggpacket->bytes, GF_BITSTREAM_READ);
		gf_bs_read_int(bs, 56);
		gf_bs_read_int(bs, 8); /* major version num */
		gf_bs_read_int(bs, 8); /* minor version num */
		gf_bs_read_int(bs, 8); /* subminor version num */
		gf_bs_read_int(bs, 16) /*<< 4*/; /* width */
		gf_bs_read_int(bs, 16) /*<< 4*/; /* height */
		gf_bs_read_int(bs, 24); /* frame width */
		gf_bs_read_int(bs, 24); /* frame height */
		gf_bs_read_int(bs, 8); /* x offset */
		gf_bs_read_int(bs, 8); /* y offset */
		fps_numerator = gf_bs_read_u32(bs);
		fps_denominator = gf_bs_read_u32(bs);
		gf_bs_read_int(bs, 24); /* aspect_numerator */
		gf_bs_read_int(bs, 24); /* aspect_denominator */
		gf_bs_read_int(bs, 8); /* colorspace */
		gf_bs_read_int(bs, 24);/* bitrate */
		gf_bs_read_int(bs, 6); /* quality */

		keyframe_freq_force = 1 << gf_bs_read_int(bs, 5);
		info->theora_kgs = 0;
		keyframe_freq_force--;
		while (keyframe_freq_force) {
			info->theora_kgs ++;
			keyframe_freq_force >>= 1;
		}
	    info->frame_rate = ((Float)fps_numerator) / fps_denominator;
		info->num_init_headers = 3;
		gf_bs_del(bs);
		info->frame_rate_base = fps_denominator;
	}
}

static void OGG_ResetupStream(OGGReader *read, OGGStream *st, ogg_page *oggpage)
{
	ogg_stream_clear(&st->os);
	ogg_stream_init(&st->os, st->serial_no);
	ogg_stream_pagein(&st->os, oggpage);
	st->parse_headers = st->info.num_init_headers;
	
	if (st->info.sample_rate) {
		st->seek_granule = (s64) (read->start_range * st->info.sample_rate);
	} else if (st->info.frame_rate) {
		s64 seek = (s64) (read->start_range * st->info.frame_rate) - 1;
		if (seek<0) seek=0;
		st->seek_granule = (seek)<<st->info.theora_kgs;
	}
	st->last_granule = -1;
}

static void OGG_NewStream(OGGReader *read, ogg_page *oggpage)
{
	ogg_packet oggpacket;
	u32 serial_no, i;
	OGGStream *st;

	/*reannounce of stream (caroussel in live streams) - until now I don't think icecast uses this*/
	serial_no = ogg_page_serialno(oggpage);
	for (i=0; i<gf_list_count(read->streams); i++) {
		st = gf_list_get(read->streams, i);
		if (st->serial_no==serial_no) {
			OGG_ResetupStream(read, st, oggpage);
			return;
		}
	}

	/*look if we have the same stream defined (eg, reuse first stream dead with same header page)*/
	for (i=0; i<gf_list_count(read->streams); i++) {
		st = gf_list_get(read->streams, i);
		if (st->eos_detected) {
			ogg_stream_state os;
			ogg_stream_init(&os, serial_no);
			ogg_stream_pagein(&os, oggpage);
			ogg_stream_packetpeek(&os, &oggpacket);
			if (st->dsi && !memcmp(st->dsi, oggpacket.packet, oggpacket.bytes)) {
				ogg_stream_clear(&os);
				st->serial_no = serial_no;
				OGG_ResetupStream(read, st, oggpage);
				return;
			}
			ogg_stream_clear(&os);
			/*nope streams are different, signal eos on this one*/
			gf_term_on_sl_packet(read->service, st->ch, NULL, 0, NULL, GF_EOS);
		}
	}

	GF_SAFEALLOC(st, sizeof(OGGStream));
    st->serial_no = serial_no;
    ogg_stream_init(&st->os, st->serial_no);
	ogg_stream_pagein(&st->os, oggpage);
	
	ogg_stream_packetpeek(&st->os, &oggpacket);
	OGG_GetStreamInfo(&oggpacket, &st->info);

	/*check we don't discard audio or visual streams*/
	if ( ((read->service_type==1) && (st->info.streamType==GF_STREAM_AUDIO))
		|| ((read->service_type==2) && (st->info.streamType==GF_STREAM_VISUAL)) )
	{
	    ogg_stream_clear(&st->os);
		free(st);
		return;
	}

	gf_list_add(read->streams, st);
	st->ESID = 2 + gf_list_count(read->streams);
	st->parse_headers = st->info.num_init_headers;
	if (st->parse_headers) read->init_remain++;
	
	if (st->info.sample_rate) {
		st->seek_granule = (s64) (read->start_range * st->info.sample_rate);
	} else if (st->info.frame_rate) {
		s64 seek = (s64) (read->start_range * st->info.frame_rate) - 1;
		if (seek<0) seek=0;
		st->seek_granule = (seek)<<st->info.theora_kgs;
	}
	st->last_granule = -1;

	if (st->info.streamType==GF_STREAM_VISUAL) {
		read->has_video = 1;
	} else {
		read->has_audio = 1;
	}
}

void OGG_SignalEndOfStream(OGGReader *read, OGGStream *st)
{
	if (st->eos_detected) {
		gf_term_on_sl_packet(read->service, st->ch, NULL, 0, NULL, GF_EOS);
		ogg_stream_clear(&st->os);
	}
}

GFINLINE void OGG_SendPackets(OGGReader *read, OGGStream *st, ogg_packet *oggpacket)
{
	SLHeader slh;
	memset(&slh, 0, sizeof(SLHeader));
	if (st->info.type==OGG_VORBIS) {
		slh.accessUnitEndFlag = slh.accessUnitStartFlag = 1;
		slh.randomAccessPointFlag = 1;
		slh.compositionTimeStampFlag = 1;
		slh.compositionTimeStamp = st->ogg_ts;
		gf_term_on_sl_packet(read->service, st->ch, oggpacket->packet, oggpacket->bytes, &slh, GF_OK);
		st->ogg_ts += gf_vorbis_check_frame(&st->vp, oggpacket->packet, oggpacket->bytes);
	}
	else if (st->info.type==OGG_THEORA) {
		oggpack_buffer opb;
		oggpackB_readinit(&opb, oggpacket->packet, oggpacket->bytes);
		/*new frame*/
		if (oggpackB_read(&opb, 1) == 0) {
			slh.accessUnitStartFlag = slh.accessUnitEndFlag = 1;
			/*add packet*/
			slh.randomAccessPointFlag = oggpackB_read(&opb, 1) ? 0 : 1;
			slh.compositionTimeStampFlag = 1;
			slh.compositionTimeStamp = st->ogg_ts;
			gf_term_on_sl_packet(read->service, st->ch, oggpacket->packet, oggpacket->bytes, &slh, GF_OK);
			st->ogg_ts += 1000;
		}
	}
}

void OGG_Process(OGGReader *read)
{
	OGGStream *st;
    ogg_packet oggpacket;
	ogg_page oggpage;

	if (read->resync_stream) {
		st = read->resync_stream;
		read->resync_stream = NULL;
		goto process_stream;
	}

	if (!OGG_ReadPage(read, &oggpage)) {
		return;
	}

	if (ogg_page_bos(&oggpage)) {
		OGG_NewStream(read, &oggpage);
		return;
	}

	st = OGG_FindStreamForPage(read, &oggpage);
	if (!st) {
		if (!read->bos_done && read->is_live) {
			u32 now = gf_sys_clock();
			if (now-read->tune_in_time > 1000) {
				gf_term_on_message(read->service, GF_OK, "Waiting for tune in...");
				read->tune_in_time = now;
			}
		}
		return;
	}

	if (ogg_page_eos(&oggpage)) 
		st->eos_detected = 1;

	if (st->parse_headers && !st->got_headers) {
		while (ogg_stream_packetout(&st->os, &oggpacket ) > 0 ) {
			GF_BitStream *bs;
			if (st->info.type==OGG_VORBIS)
				gf_vorbis_parse_header(&st->vp, oggpacket.packet, oggpacket.bytes);

			bs = gf_bs_new(NULL, 0, GF_BITSTREAM_WRITE);
			if (st->dsi) {
				gf_bs_write_data(bs, st->dsi, st->dsi_len);
				free(st->dsi);
				st->dsi = NULL;
				st->dsi_len=0;
			}
			gf_bs_write_u16(bs, oggpacket.bytes);
			gf_bs_write_data(bs, oggpacket.packet, oggpacket.bytes);
			gf_bs_get_content(bs, (unsigned char **)&st->dsi, &st->dsi_len);
			gf_bs_del(bs);
			st->parse_headers--;
			if (!st->parse_headers) {
				st->got_headers = 1;
				break;
			}
		}
		if (!st->got_headers) return;
		assert(read->init_remain);
		read->init_remain--;
		if (!read->init_remain) read->bos_done = 1;
		return;
	}
	/*from here we should have passed all headers*/
	if (read->init_remain) return;

process_stream:
	/*live insertion (not supported yet, just a reminder)*/
	if (!st->ch) {
		read->resync_stream = st;
		return;
	}

	while (ogg_stream_packetout(&st->os, &oggpacket ) > 0 ) {
		if (oggpacket.granulepos != -1) {
			st->last_granule = oggpacket.granulepos;
		}
		/*stream reinit, don't resend headers*/
		if (st->parse_headers) st->parse_headers--;
		else if (st->map_time) {
			Double t;
			if (read->start_range && (oggpacket.granulepos==-1)) continue;
			t = OGG_GranuleToMediaTime(&st->info, st->last_granule);
			if (t>=read->start_range) {
				GF_NetworkCommand map;
				map.command_type = GF_NET_CHAN_MAP_TIME;
				map.map_time.on_channel = st->ch;
				map.map_time.reset_buffers = (read->start_range>0.2) ? 1 : 0;
				map.map_time.timestamp = st->ogg_ts = 0;
				map.map_time.media_time = t;
				gf_term_on_command(read->service, &map, GF_OK);
				st->map_time = 0;
				OGG_SendPackets(read, st, &oggpacket);
			}
		}
		else {
			OGG_SendPackets(read, st, &oggpacket);
		}
	}
}

static u32 OggDemux(void *par)
{
	GF_NetworkCommand com;
	Bool go;
	u32 i;
	OGGReader *read = (OGGReader *) par;

	read->bos_done = 0;
	memset(&com, 0, sizeof(GF_NetworkCommand));
	com.command_type = GF_NET_CHAN_BUFFER_QUERY;

    ogg_sync_init(&read->oy);

	while (!read->kill_demux) {
		OGG_Process(read);

		if (!read->bos_done) continue;

		if (read->needs_connection) {
			read->needs_connection=0;
			gf_term_on_connect(read->service, NULL, GF_OK);
			/*wait till we have an OD stream if we need one*/
			if (!read->is_single_media) {
				while (!read->kill_demux && !read->od_ch) gf_sleep(20);
				if (read->kill_demux) break;
			}
		}
		
		/*idle*/
		while (!read->kill_demux && !read->nb_playing) {
			/*send OD updates*/
			OGG_SendStreams(read);
			gf_sleep(20);
		}

		/*(re)starting, seek*/
		if (read->do_seek) {
			read->do_seek = 0;
			ogg_sync_clear(&read->oy);
			ogg_sync_init(&read->oy);
			OGG_SendStreams(read);

			if (read->ogfile) {
				u32 seek_to = 0;
				read->resync_stream = NULL;
				if (read->dur) seek_to = (u32) (read->file_size * (read->start_range/read->dur) * 0.6f);
				if ((s32) seek_to > ftell(read->ogfile) ) {
					fseek(read->ogfile, seek_to, SEEK_SET);
				} else {
					fseek(read->ogfile, 0, SEEK_SET);
				}
			}
		}


		/*sleep untill the buffer occupancy is too low - note that this work because all streams in this
		demuxer are synchronized*/
		go = read->nb_playing;
		while (go && !read->kill_demux) {
			for (i=0; i<gf_list_count(read->streams); i++) {
				OGGStream *st = gf_list_get(read->streams, i);
				if (!st->ch) continue;
				com.base.on_channel = st->ch;
				gf_term_on_command(read->service, &com, GF_OK);
				if (com.buffer.occupancy < read->data_buffer_ms) {
					//fprintf(stdout, "OGG: channel %d needs fill (%d ms data, %d max buffer)\n", st->ESID, com.buffer.occupancy, read->data_buffer_ms);
					go = 0;
					break;
				}
			}
			if (!i || !read->nb_playing) break;
			gf_sleep(10);
		}
	}
    ogg_sync_clear(&read->oy);
	read->kill_demux=2;
	return 0;
}

/*get streams & duration*/
Bool OGG_CheckFile(OGGReader *read)
{
	OGGInfo info, the_info;
	ogg_page oggpage;
	ogg_packet oggpacket;
	ogg_stream_state os, the_os;
	u64 max_gran;
	Bool has_stream = 0;
	fseek(read->ogfile, 0, SEEK_SET);

    ogg_sync_init(&read->oy);
	max_gran = 0;
	while (1) {
		if (!OGG_ReadPage(read, &oggpage)) break;

		if (ogg_page_bos(&oggpage)) {
			ogg_stream_init(&os, ogg_page_serialno(&oggpage));
			if (ogg_stream_pagein(&os, &oggpage) >= 0 ) {
				ogg_stream_packetpeek(&os, &oggpacket);
				if (ogg_stream_pagein(&os, &oggpage) >= 0 ) {
					ogg_stream_packetpeek(&os, &oggpacket);
					OGG_GetStreamInfo(&oggpacket, &info); 
				}
				if (!has_stream) {
					has_stream = 1;
					ogg_stream_init(&the_os, ogg_page_serialno(&oggpage));
					the_info = info;
				}
			}
			ogg_stream_clear(&os);
			continue;
		}
		if (has_stream && (ogg_stream_pagein(&the_os, &oggpage) >= 0) ) {
			while (ogg_stream_packetout(&the_os, &oggpacket ) > 0 ) {
				if ((oggpacket.granulepos>=0) && ((u64) oggpacket.granulepos>max_gran) ) {
					max_gran = oggpacket.granulepos;
				}
			}
		}
	}
    ogg_sync_clear(&read->oy);
	read->file_size = ftell(read->ogfile);
	fseek(read->ogfile, 0, SEEK_SET);
	read->dur = 0;
	if (has_stream) {
		ogg_stream_clear(&the_os);
		read->dur = (Double) (s64) OGG_GranuleToTime(&the_info, max_gran);
		if (the_info.sample_rate) read->dur /= the_info.sample_rate;
		else read->dur /= the_info.frame_rate_base;
	}
	return has_stream;
}

static Bool OGG_CanHandleURL(GF_InputService *plug, const char *url)
{
	char *sExt;
	sExt = strrchr(url, '.');
	if (!sExt) return 0;

	if (gf_term_check_extension(plug, "application/ogg", "ogg", "Xiph.org OGG Movie", sExt)) return 1;
	if (gf_term_check_extension(plug, "application/x-ogg", "ogg", "Xiph.org OGG Movie", sExt)) return 1;
	return 0;
}

static Bool ogg_is_local(const char *url)
{
	if (!strnicmp(url, "file://", 7)) return 1;
	if (strstr(url, "://")) return 0;
	return 1;
}

void OGG_OnState(void *cbk, char *data, u32 size, u32 state, GF_Err e)
{
	OGGReader *read = (OGGReader *) cbk;

	gf_term_download_update_stats(read->dnload);
	
	if ((e == GF_EOS) && read->ogfile) {
		read->is_remote = 0;
		/*reload file*/
		OGG_CheckFile(read);
		return;
	}
	if (e && read->needs_connection) {
		read->needs_connection = 0;
		read->kill_demux = 2;
		gf_term_on_connect(read->service, NULL, e);
	}
	/*we never receive data from here since the downloader is not threaded*/
}

void OGG_DownloadFile(GF_InputService *plug, char *url)
{
	OGGReader *read = (OGGReader*) plug->priv;

	read->dnload = gf_term_download_new(read->service, url, GF_DOWNLOAD_SESSION_NOT_THREADED, OGG_OnState, read);
	if (!read->dnload) {
		read->kill_demux=2;
		read->needs_connection = 0;
		gf_term_on_connect(read->service, NULL, GF_NOT_SUPPORTED);
	}
	/*service confirm is done once fetched, but start the demuxer thread*/
	gf_th_run(read->demuxer, OggDemux, read);
}


static GF_Err OGG_ConnectService(GF_InputService *plug, GF_ClientService *serv, const char *url)
{
	char szURL[2048];
	char *ext;
	GF_Err reply;
	OGGReader *read = plug->priv;
	read->service = serv;

	if (read->dnload) gf_term_download_del(read->dnload);
	read->dnload = NULL;

	read->service_type = 0;
	strcpy(szURL, url);
	ext = strrchr(szURL, '#');
	if (ext) {
		if (!strcmp(ext, "#video")) read->service_type = 1;
		else if (!strcmp(ext, "#audio")) read->service_type = 2;
		ext[0] = 0;
	}

	/*remote fetch*/
	read->is_remote = !ogg_is_local(szURL);
	if (read->is_remote) {
		read->needs_connection = 1;
		OGG_DownloadFile(plug, szURL);
		return GF_OK;
	} else {
		read->ogfile = fopen(szURL, "rb");
		if (!read->ogfile) {
			reply = GF_URL_ERROR;
		} else {
			reply = GF_OK;
			/*init ogg file in local mode*/
			if (!OGG_CheckFile(read)) {
				fclose(read->ogfile);
				reply = GF_NON_COMPLIANT_BITSTREAM;
			} else {
				read->needs_connection = 1;
				/*start the demuxer thread*/
				gf_th_run(read->demuxer, OggDemux, read);
				return GF_OK;
			}
		}
	}
	/*error*/
	read->kill_demux=2;
	gf_term_on_connect(serv, NULL, reply);
	return GF_OK;
}

static GF_Err OGG_CloseService(GF_InputService *plug)
{
	OGGReader *read = plug->priv;
	if (!read->kill_demux) {
		read->kill_demux = 1;
		while (read->kill_demux!=2) gf_sleep(2);
	}

	if (read->ogfile) fclose(read->ogfile);
	read->ogfile = NULL;
	if (read->dnload) gf_term_download_del(read->dnload);
	read->dnload = NULL;
	gf_term_on_disconnect(read->service, NULL, GF_OK);
	return GF_OK;
}

static GF_Descriptor *OGG_GetServiceDesc(GF_InputService *plug, u32 expect_type, const char *sub_url)
{
	GF_ESD *esd;
	u32 i;
	GF_ObjectDescriptor *od;
	OGGReader *read = plug->priv;
	/*since we don't handle multitrack in ogg yes, we don't need to check sub_url, only use expected type*/

	/*single object*/
	if ((expect_type==GF_MEDIA_OBJECT_AUDIO) || (expect_type==GF_MEDIA_OBJECT_VIDEO)) {
		if ((expect_type==GF_MEDIA_OBJECT_AUDIO) && !read->has_audio) return NULL;
		if ((expect_type==GF_MEDIA_OBJECT_VIDEO) && !read->has_video) return NULL;
		for (i=0; i<gf_list_count(read->streams); i++) {
			OGGStream *st = gf_list_get(read->streams, i);
			if ((expect_type==GF_MEDIA_OBJECT_AUDIO) && (st->info.streamType!=GF_STREAM_AUDIO)) continue;
			if ((expect_type==GF_MEDIA_OBJECT_VIDEO) && (st->info.streamType!=GF_STREAM_VISUAL)) continue;
			
			od = OGG_GetOD(st, 1);
			read->is_single_media = 1;
			return (GF_Descriptor *) od;
		}
		/*not supported yet - we need to know what's in the ogg stream for that*/
		return NULL;
	}

	od = (GF_ObjectDescriptor *) gf_odf_desc_new(GF_ODF_OD_TAG);
	od->objectDescriptorID = 1;

	/*inline scene*/
	/*OD ESD*/
	esd = gf_odf_desc_esd_new(0);
	esd->slConfig->timestampResolution = 1000;
	esd->decoderConfig->streamType = GF_STREAM_OD;
	esd->decoderConfig->objectTypeIndication = GPAC_STATIC_OD_OTI;
	esd->ESID = 1;
	gf_list_add(od->ESDescriptors, esd);
	return (GF_Descriptor *) od;
}

static GF_Err OGG_ConnectChannel(GF_InputService *plug, LPNETCHANNEL channel, const char *url, Bool upstream)
{
	u32 ES_ID, i;
	GF_Err e;
	OGGReader *read = plug->priv;

	e = GF_SERVICE_ERROR;
	if (read->od_ch==channel) goto exit;

	e = GF_STREAM_NOT_FOUND;
	if (strstr(url, "ES_ID")) {
		sscanf(url, "ES_ID=%d", &ES_ID);
	}
	/*URL setup*/
//	else if (!read->es_ch && OGG_CanHandleURL(plug, url)) ES_ID = 3;

	switch (ES_ID) {
	case 1:
		read->od_ch = channel;
		e = GF_OK;
		break;
	default:
		for (i=0; i<gf_list_count(read->streams); i++) {
			OGGStream *st = gf_list_get(read->streams, i);
			if (st->ESID==ES_ID) {
				st->ch = channel;
				e = GF_OK;
				break;
			}
		}
		break;
	}

exit:
	gf_term_on_connect(read->service, channel, e);
	return e;
}

static GF_Err OGG_DisconnectChannel(GF_InputService *plug, LPNETCHANNEL channel)
{
	GF_Err e;
	OGGReader *read = plug->priv;

	e = GF_STREAM_NOT_FOUND;
	if (read->od_ch == channel) {
		read->od_ch = NULL;
		e = GF_OK;
	} else {
		u32 i;
		for (i=0; i<gf_list_count(read->streams); i++) {
			OGGStream *st = gf_list_get(read->streams, i);
			if (st->ch==channel) {
				st->ch = NULL;
				e = GF_OK;
				break;
			}
		}
	}
	gf_term_on_disconnect(read->service, channel, e);
	return GF_OK;
}

static GF_Err OGG_ServiceCommand(GF_InputService *plug, GF_NetworkCommand *com)
{
	OGGReader *read = plug->priv;

	if (!com->base.on_channel) {
		/*if live session we may cache*/
		if (read->is_live && (com->command_type==GF_NET_IS_CACHABLE)) return GF_OK;
		return GF_NOT_SUPPORTED;
	}
	switch (com->command_type) {
	case GF_NET_CHAN_SET_PULL:
		/*no way to demux streams independently, and we keep OD as dynamic ogfile to handle
		chained streams*/
		return GF_NOT_SUPPORTED;
	case GF_NET_CHAN_INTERACTIVE:
		//live: return GF_NOT_SUPPORTED;
		return GF_OK;
	case GF_NET_CHAN_BUFFER:
		com->buffer.min = com->buffer.max = 0;
		if (read->is_live && (com->base.on_channel != read->od_ch)) {
			com->buffer.max = read->data_buffer_ms;
		}
		return GF_OK;
	case GF_NET_CHAN_SET_PADDING: return GF_NOT_SUPPORTED;

	case GF_NET_CHAN_DURATION:
		com->duration.duration = read->dur;
		return GF_OK;
	case GF_NET_CHAN_PLAY:
		read->start_range = com->play.start_range;
		read->end_range = com->play.end_range;
		if (read->od_ch == com->base.on_channel) {
			read->needs_od = 1;
			assert(!read->nb_playing);
		} else {
			u32 i;
			for (i=0; i<gf_list_count(read->streams); i++) {
				OGGStream *st = gf_list_get(read->streams, i);
				if (st->ch == com->base.on_channel) {
					st->is_running = 1;
					st->map_time = read->dur ? 1 : 0;
					if (!read->nb_playing) read->do_seek = 1;
					read->nb_playing ++;
					break;
				}
			}
		}
		/*recfg duration in case*/
		if (!read->is_remote && read->dur) { 
			GF_NetworkCommand rcfg;
			rcfg.base.on_channel = read->od_ch;
			rcfg.base.command_type = GF_NET_CHAN_DURATION;
			rcfg.duration.duration = read->dur;
			gf_term_on_command(read->service, &rcfg, GF_OK);
		}
		return GF_OK;
	case GF_NET_CHAN_STOP:
		if (read->od_ch == com->base.on_channel) {
		}
		else {
			u32 i;
			for (i=0; i<gf_list_count(read->streams); i++) {
				OGGStream *st = gf_list_get(read->streams, i);
				if (st->ch == com->base.on_channel) {
					st->is_running = 0;
					read->nb_playing --;
					break;
				}
			}
		}
		return GF_OK;
	default:
		return GF_OK;
	}
}

static Bool OGG_CanHandleURLInService(GF_InputService *plug, const char *url)
{
	char szURL[2048], *sep;
	OGGReader *read = (OGGReader *)plug->priv;
	const char *this_url = gf_term_get_service_url(read->service);
	if (!this_url || !url) return 0;

	strcpy(szURL, this_url);
	sep = strrchr(szURL, '#');
	if (sep) sep[0] = 0;

	if ((url[0] != '#') && strnicmp(szURL, url, sizeof(char)*strlen(szURL))) return 0;
	sep = strrchr(url, '#');
	if (!stricmp(sep, "#video") && (read->has_video)) return 1;
	if (!stricmp(sep, "#audio") && (read->has_audio)) return 1;
	return 0;
}

GF_InputService *OGG_LoadDemux()
{
	OGGReader *reader;
	GF_InputService *plug = malloc(sizeof(GF_InputService));
	memset(plug, 0, sizeof(GF_InputService));
	GF_REGISTER_MODULE_INTERFACE(plug, GF_NET_CLIENT_INTERFACE, "GPAC OGG Reader", "gpac distribution")

	plug->CanHandleURL = OGG_CanHandleURL;
	plug->ConnectService = OGG_ConnectService;
	plug->CloseService = OGG_CloseService;
	plug->GetServiceDescriptor = OGG_GetServiceDesc;
	plug->ConnectChannel = OGG_ConnectChannel;
	plug->DisconnectChannel = OGG_DisconnectChannel;
	plug->ServiceCommand = OGG_ServiceCommand;
	plug->CanHandleURLInService = OGG_CanHandleURLInService;

	reader = malloc(sizeof(OGGReader));
	memset(reader, 0, sizeof(OGGReader));
	reader->streams = gf_list_new();
	reader->demuxer = gf_th_new();
	reader->data_buffer_ms = 1000;

	plug->priv = reader;
	return plug;
}

void OGG_DeleteDemux(void *ifce)
{
	GF_InputService *plug = (GF_InputService *) ifce;
	OGGReader *read = plug->priv;
	gf_th_del(read->demuxer);

	/*just in case something went wrong*/
	while (gf_list_count(read->streams)) {
		OGGStream *st = gf_list_get(read->streams, 0);
		gf_list_rem(read->streams, 0);
		ogg_stream_clear(&st->os);
		if (st->dsi) free(st->dsi);
		free(st);
	}
	gf_list_del(read->streams);
	free(read);
	free(plug);
}
