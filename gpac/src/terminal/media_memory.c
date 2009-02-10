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
#include "media_memory.h"
#include "media_control.h"

#define NO_TEMPORAL_SCALABLE	1


GF_DBUnit *gf_db_unit_new()
{
	GF_DBUnit *tmp;
	GF_SAFEALLOC(tmp, GF_DBUnit)
	return tmp;
}

void gf_db_unit_del(GF_DBUnit *db)
{
	if (!db) return;
	if (db->next) gf_db_unit_del(db->next);
	if (db->data) free(db->data);
	free(db);
}

static GF_CMUnit *gf_cm_unit_new()
{
	GF_CMUnit *tmp;
	GF_SAFEALLOC(tmp, GF_CMUnit)
	return tmp;
}

static void gf_cm_unit_del(GF_CMUnit *cb)
{
	if (cb->next) gf_cm_unit_del(cb->next);
	cb->next = NULL;
	if (cb->data) {
		free(cb->data);
		cb->data = NULL;
	}
	free(cb);
}


GF_CompositionMemory *gf_cm_new(u32 UnitSize, u32 capacity)
{
	GF_CompositionMemory *tmp;
	GF_CMUnit *cu, *prev;
	u32 i;
	if (!capacity) return NULL;
	
	GF_SAFEALLOC(tmp, GF_CompositionMemory)

	tmp->Capacity = capacity;
	tmp->UnitSize = UnitSize;

	prev = NULL;
	i = 1;
	while (capacity) {
		cu = gf_cm_unit_new();
		if (!prev) {
			tmp->input = cu;
		} else {
			prev->next = cu;
			cu->prev = prev;
		}
		cu->dataLength = 0;
		cu->data = UnitSize ? (char*)malloc(sizeof(char)*UnitSize) : NULL;
		if (cu->data) memset(cu->data, 0, sizeof(char)*UnitSize);
		prev = cu;
		capacity --;
		i++;
	}
	cu->next = tmp->input;
	tmp->input->prev = cu;

	/*close the loop. The output is the input as the first item 
	that will be ready for composition will be filled in the input*/
	tmp->output = tmp->input;

	tmp->Status = CB_STOP;
	return tmp;
}

void gf_cm_del(GF_CompositionMemory *cb)
{
	gf_odm_lock(cb->odm, 1);
	/*may happen when CB is destroyed right after creation in case*/
	if (cb->Status == CB_BUFFER) {
		gf_clock_buffer_off(cb->odm->codec->ck);
		GF_LOG(GF_LOG_DEBUG, GF_LOG_SYNC, ("[SyncLayer] ODM%d: buffering off at %d (nb buffering on clock: %d)\n", cb->odm->OD->objectDescriptorID, gf_term_get_time(cb->odm->term), cb->odm->codec->ck->Buffering));
	}
	
	/*break the loop and destroy*/
	cb->input->prev->next = NULL;
	gf_cm_unit_del(cb->input);
	gf_odm_lock(cb->odm, 0);
	free(cb);
}

void gf_cm_rewind_input(GF_CompositionMemory *cb)
{
	if (cb->UnitCount) {
		cb->UnitCount--;
		cb->input = cb->input->prev;
		cb->input->dataLength = 0; 
	}
}

/*access to the input buffer - return NULL if no input is available (buffer full)*/
GF_CMUnit *gf_cm_lock_input(GF_CompositionMemory *cb, u32 TS, Bool codec_reordering)
{
#if !NO_TEMPORAL_SCALABLE
	GF_CMUnit *cu;
	if (codec_reordering) {
#endif
		/*there is still something in the input buffer*/
		if (cb->input->dataLength) {
			if (cb->input->TS==TS) 
				return cb->input;
			return NULL;
		}
		cb->input->TS = TS;
		return cb->input;

#if !NO_TEMPORAL_SCALABLE
	}

	/*spatial scalable, go backward to fetch same TS*/
	cu = cb->input;
	while (1) {
		if (cu->TS == TS) 
			return cu;
		cu = cu->prev;
		if (cu == cb->input) break;
	}
	/*temporal scalable (find empty slot)*/
	cu = cb->input;
	while (1) {
		if (!cu->dataLength) {
			assert(!cu->TS || (cb->Capacity==1));
			cu->TS = TS;
			return cu;
		}
		cu = cu->next;
		if (cu == cb->input) return NULL;
	}
	return NULL;
#endif
}

#if 0
static void check_temporal(GF_CompositionMemory *cb)
{
	GF_CMUnit *cu;
	cu = cb->output;
	while (1) {
		if (cu->next==cb->output) break;
		assert(!cu->next->dataLength || (cu->TS < cu->next->TS));
		assert(!cu(>TS || (cu->TS >= cb->LastRenderedTS));
		cu = cu->next;
	}
}
#endif

/*re-orders units in case of temporal scalability. Blocking call as it may change the output unit too*/
static GF_CMUnit *gf_cm_reorder_unit(GF_CompositionMemory *cb, GF_CMUnit *unit, Bool codec_reordering)
{
	GF_CMUnit *cu;
#if NO_TEMPORAL_SCALABLE
	cu = cb->input;
	cb->input = cb->input->next;
	return cu;
#else
	/*lock the buffer since we may move pointers*/
	gf_odm_lock(cb->odm, 1);

	/*1- unit is next on time axis (no temporal scalability)*/
	if (!cb->input->dataLength || cb->input->TS < unit->TS) {

		if (unit != cb->input) {
			/*remove unit from the list*/
			unit->prev->next = unit->next;
			unit->next->prev = unit->prev;

			/*insert unit after input*/
			unit->prev = cb->input;
			unit->next = cb->input->next;
			unit->next->prev = unit;
			unit->prev->next = unit;
		}
		/*set input to our unit*/
		cb->input = unit;
		goto exit;
	}
	/*2- unit is before our last delivered input - temporal scalability*/
	cu = cb->input;
	while (1) {

		if (cu->TS > unit->TS) {
			/*we have a unit after this one that has been consumed - discard our unit*/
			if (!cu->dataLength) {
				unit = NULL;
				goto exit;
			}
			/*previous unit is the active one - check one further*/
			if (cu->prev == unit) {
				if (!unit->prev->dataLength || (unit->prev->TS < unit->TS)) 
					break;
			} 
			/*no previous unit or our unit is just after the previous unit*/
			else if (!cu->prev->dataLength || (cu->prev->TS < unit->TS)) {
				break;
			}
			/*otherwise need to go one further*/
		}

		/*go on*/
		cu = cu->prev;
		/*done (should never happen)*/
		if (cu == cb->input) 
			goto exit;
	}
	
	/*remove unit from the list*/
	unit->prev->next = unit->next;
	unit->next->prev = unit->prev;

	/*insert active before cu*/
	unit->next = cu;
	unit->prev = cu->prev;
	unit->next->prev = unit;
	unit->prev->next = unit;
	GF_LOG(GF_LOG_DEBUG, GF_LOG_MEDIA, ("Swapping CU buffer\n"));
	
exit:

	/*perform sanity check on output ordering*/
	if (unit) {
		/*if no more output reset to our min*/
		if (!cb->output->dataLength) {
			cb->output = cb->input;
			while (1) {
				if (!cb->output->prev->dataLength) break;
				cb->output = cb->output->prev;
			}
		}
		/*otherwise we just inserted a CU that should be the next one to be consumed*/
		else if (cb->output->TS > unit->TS) {
			cb->output = unit;
		}

	}

	//check_temporal(cb);
	/*unlock the buffer*/
	gf_odm_lock(cb->odm, 0);
	return unit;
#endif
}

void gf_cm_unlock_input(GF_CompositionMemory *cb, GF_CMUnit *cu, u32 cu_size, Bool codec_reordering)
{
	/*nothing dispatched, ignore*/
	if (!cu_size) {
		cu->dataLength = 0;
		cu->TS = 0;
		return;
	}
	gf_odm_lock(cb->odm, 1);

	if (cu->TS < cb->input->TS)
		cu = cu;


	if (codec_reordering) {
		cb->input = cb->input->next;
	} else {
		cu = gf_cm_reorder_unit(cb, cu, codec_reordering);
		assert(cu);
	}

	if (cu) {
		/*FIXME - if the CU already has data, this is spatial scalability so same num buffers*/
		if (!cu->dataLength) cb->UnitCount += 1;
		cu->dataLength = cu_size;
		cu->RenderedLength = 0;

		/*turn off buffering - this must be done now rather than when fetching first output frame since we're not 
		sure output is fetched (Switch node, ...)*/
		if ( (cb->Status == CB_BUFFER) && (cb->UnitCount >= cb->Capacity) ) {
			/*done with buffering, signal to the clock (ONLY ONCE !)*/
			cb->Status = CB_BUFFER_DONE;
			gf_clock_buffer_off(cb->odm->codec->ck);
			GF_LOG(GF_LOG_DEBUG, GF_LOG_SYNC, ("[SyncLayer] ODM%d: buffering off at %d (nb buffering on clock: %d)\n", cb->odm->OD->objectDescriptorID, gf_term_get_time(cb->odm->term), cb->odm->codec->ck->Buffering));
		} 

		/*since a new CU is here notify the compositor*/
		if ((cb->odm->codec->type==GF_STREAM_VISUAL) && cb->odm->mo && cb->odm->mo->num_open) {
			gf_term_invalidate_compositor(cb->odm->term);
		}
	}
	gf_odm_lock(cb->odm, 0);
}


/*Reset composition memory. Note we don't reset the content of each frame since it would lead to green frames 
when using bitmap (visual), where data is not cached*/
void gf_cm_reset(GF_CompositionMemory *cb)
{
	GF_CMUnit *cu;

	gf_odm_lock(cb->odm, 1);

	cu = cb->input;
	cu->RenderedLength = 0;
	cu->dataLength = 0;
	cu->TS = 0;
	cu = cu->next;
	while (cu != cb->input) {
		cu->RenderedLength = 0;
		cu->TS = 0;
		cu->dataLength = 0;
		cu = cu->next;
	}
	cb->output = cb->input;
	cb->UnitCount = 0;
	cb->HasSeenEOS = 0;

	if (cb->odm->mo) cb->odm->mo->timestamp = 0;
	gf_odm_lock(cb->odm, 0);
}

/*resize buffers (blocking)*/
void gf_cm_resize(GF_CompositionMemory *cb, u32 newCapacity)
{
	GF_CMUnit *cu;
	if (!newCapacity) return;

	/*lock buffer*/
	gf_odm_lock(cb->odm, 1);
	cu = cb->input;

	cb->UnitSize = newCapacity;
	/*don't touch any existing memory (it may happen on the fly with audio)*/
	cu->data = (char*)realloc(cu->data, sizeof(char)*newCapacity);
	cu = cu->next;
	while (cu != cb->input) {
		cu->data = (char*)realloc(cu->data, sizeof(char)*newCapacity);
		cu = cu->next;
	}

	gf_odm_lock(cb->odm, 0);
}



/*resize buffers (blocking)*/
void gf_cm_reinit(GF_CompositionMemory *cb, u32 UnitSize, u32 Capacity)
{
	GF_CMUnit *cu, *prev;
	u32 i;
	if (!Capacity || !UnitSize) return;

	gf_odm_lock(cb->odm, 1);
	/*break the loop and destroy*/
	cb->input->prev->next = NULL;
	gf_cm_unit_del(cb->input);

	cu = NULL;
	cb->Capacity = Capacity;
	cb->UnitSize = UnitSize;

	prev = NULL;
	i = 1;
	while (Capacity) {
		cu = gf_cm_unit_new();
		if (!prev) {
			cb->input = cu;
		} else {
			prev->next = cu;
			cu->prev = prev;
		}
		cu->dataLength = 0;
		cu->data = (char*)malloc(sizeof(char)*UnitSize);
		prev = cu;
		Capacity --;
		i++;
	}
	cu->next = cb->input;
	cb->input->prev = cu;
	cb->output = cb->input;
	gf_odm_lock(cb->odm, 0);
}

/*access to the first available CU for rendering
this is a blocking call since input may change the output (temporal scalability)*/
GF_CMUnit *gf_cm_get_output(GF_CompositionMemory *cb)
{
	GF_CMUnit *out = NULL;

	/*if paused or stop or buffering, do nothing*/
	switch (cb->Status) {
	case CB_BUFFER:
		return NULL;
	case CB_STOP:
	case CB_PAUSE:
		/*only visual buffers deliver data when paused*/
		if (cb->odm->codec->type != GF_STREAM_VISUAL) goto exit;
		break;
	case CB_BUFFER_DONE:
		cb->Status = CB_PLAY;
		break;
	}

	/*no output*/
	if (!cb->output->dataLength) {
		if ((cb->Status != CB_STOP) && cb->HasSeenEOS && (cb->odm && cb->odm->codec)) {
			GF_LOG(GF_LOG_DEBUG, GF_LOG_MEDIA, ("[ODM%d] Switching composition memory to stop state - time %d\n", cb->odm->OD->objectDescriptorID, (u32) cb->odm->media_stop_time));

			cb->Status = CB_STOP;
			cb->odm->current_time = (u32) cb->odm->media_stop_time;
			/*force update of media time*/
			MS_UpdateTiming(cb->odm, 1);
		}
		goto exit;
	}

	/*update the timing*/
	if ((cb->Status != CB_STOP) && cb->odm && cb->odm->codec) {
		cb->odm->current_time = cb->output->TS;

		/*handle visual object - EOS if no more data (we keep the last CU for rendering, so check next one)*/
		if (cb->HasSeenEOS && (!cb->output->next->dataLength || (cb->Capacity==1))) {
			GF_LOG(GF_LOG_DEBUG, GF_LOG_MEDIA, ("[ODM%d] Switching composition memory to stop state - time %d\n", cb->odm->OD->objectDescriptorID, (u32) cb->odm->media_stop_time));
			cb->Status = CB_STOP;
			cb->odm->current_time = (u32) cb->odm->media_stop_time;
			/*force update of media time*/
			MS_UpdateTiming(cb->odm, 1);
		}
	}
	out = cb->output;

	//assert(out->TS >= cb->LastRenderedTS);

exit:
	return out;
}



/*drop the output CU*/
void gf_cm_drop_output(GF_CompositionMemory *cb)
{
	assert(cb->UnitCount);
	/*this allows reuse of the CU*/
	cb->output->RenderedLength = 0;
	cb->LastRenderedTS = cb->output->TS;

	/*on visual streams, always keep the last AU*/
	if (cb->output->dataLength && (cb->odm->codec->type == GF_STREAM_VISUAL) ) {
		if ( !cb->output->next->dataLength || (cb->Capacity == 1) )  {
			return;
		}
	}
	
	/*reset the output*/
	cb->output->dataLength = 0;
	cb->output->TS = 0;
	cb->output = cb->output->next;
	cb->UnitCount -= 1;

	if (!cb->HasSeenEOS && cb->UnitCount <= cb->Min) {
		cb->odm->codec->PriorityBoost = 1;
	}
}

void gf_cm_set_status(GF_CompositionMemory *cb, u32 Status)
{
	gf_odm_lock(cb->odm, 1);
	/*if we're asked for play, trigger on buffering*/
	if (Status == CB_PLAY) {
		switch (cb->Status) {
		case CB_STOP:
			cb->Status = CB_BUFFER;
			gf_clock_buffer_on(cb->odm->codec->ck);
			break;
		case CB_PAUSE:
			cb->Status = CB_PLAY;
			break;
		/*this should never happen (calling play while already buffering ...)*/
		case CB_BUFFER:
			cb->LastRenderedTS = 0;
			break;
		default:
			cb->Status = Status;
			break;
		}
	} else {
		cb->LastRenderedTS = 0;
		if (cb->Status == CB_BUFFER) {
			gf_clock_buffer_off(cb->odm->codec->ck);
			GF_LOG(GF_LOG_DEBUG, GF_LOG_SYNC, ("[SyncLayer] ODM%d: buffering off at %d (nb buffering on clock: %d)\n", cb->odm->OD->objectDescriptorID, gf_term_get_time(cb->odm->term), cb->odm->codec->ck->Buffering));
		}
		if (Status == CB_STOP) {
			gf_cm_reset(cb);
		}
		cb->Status = Status;
	}

	gf_odm_lock(cb->odm, 0);

}

void gf_cm_set_eos(GF_CompositionMemory *cb)
{
	gf_odm_lock(cb->odm, 1);
	/*we may have a pb if the stream is so short that the EOS is signaled 
	while we're buffering. In this case we shall turn the clock on and 
	keep a trace of the EOS notif*/
	if (cb->Status == CB_BUFFER) {
		cb->Status = CB_BUFFER_DONE;
		gf_clock_buffer_off(cb->odm->codec->ck);
		GF_LOG(GF_LOG_DEBUG, GF_LOG_SYNC, ("[SyncLayer] ODM%d: buffering off at %d (nb buffering on clock: %d)\n", cb->odm->OD->objectDescriptorID, gf_term_get_time(cb->odm->term), cb->odm->codec->ck->Buffering));
	}
	cb->HasSeenEOS = 1;
	gf_term_invalidate_compositor(cb->odm->term);
	gf_odm_lock(cb->odm, 0);
}


Bool gf_cm_is_running(GF_CompositionMemory *cb)
{
	if (cb->Status == CB_PLAY)
		return !cb->odm->codec->ck->Paused;

	if ((cb->Status == CB_BUFFER_DONE) && (gf_clock_is_started(cb->odm->codec->ck) || cb->odm->term->play_state) ) {
		cb->Status = CB_PLAY;
		return 1;
	}

	if ((cb->odm->codec->type == GF_STREAM_VISUAL)
		&& (cb->Status == CB_STOP)
		&& cb->output->dataLength) return 1;

	return 0;
}


Bool gf_cm_is_eos(GF_CompositionMemory *cb)
{
	return ( (cb->Status == CB_STOP) && cb->HasSeenEOS);
}

void gf_cm_abort_buffering(GF_CompositionMemory *cb)
{
	if (cb->Status == CB_BUFFER) {
		cb->Status = CB_BUFFER_DONE;
		gf_clock_buffer_off(cb->odm->codec->ck);
		GF_LOG(GF_LOG_DEBUG, GF_LOG_SYNC, ("[SyncLayer] ODM%d: buffering off at %d (nb buffering on clock: %d)\n", cb->odm->OD->objectDescriptorID, gf_term_get_time(cb->odm->term), cb->odm->codec->ck->Buffering));
	}
}
