/*
 *			GPAC - Multimedia Framework C SDK
 *
 *			Copyright (c) Cyril Concolato 2000-2006
 *					All rights reserved
 *
 *  This file is part of GPAC / load&compare application
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
#include <gpac/scene_manager.h>
#include <zlib.h>

typedef struct {
	FILE *out;
	u32 nbloads;
} GF_LoadCompare;

u32 getlasertracksize(char *in)
{
	u32 j, totalsize = 0;
	GF_ISOFile *mp4 = gf_isom_open(in, GF_ISOM_OPEN_READ, NULL);
	u32 track_id = gf_isom_get_track_id(mp4, 1);
	u32 trackNum = gf_isom_get_track_by_id(mp4, track_id);
	for (j=0; j<gf_isom_get_sample_count(mp4, trackNum); j++) {
		GF_ISOSample *samp = gf_isom_get_sample_info(mp4, trackNum, j+1, NULL, NULL);
		totalsize += samp->dataLength;
	}
	gf_isom_delete(mp4);
	return totalsize;
}

GF_Err dumpsvg(char *in, char *out)
{
	GF_Err e;
	GF_SceneManager *ctx;
	GF_SceneGraph *sg;
	GF_SceneLoader load;
	e = GF_OK;

	sg = gf_sg_new();
	ctx = gf_sm_new(sg);
	memset(&load, 0, sizeof(GF_SceneLoader));
	load.fileName = in;
	load.ctx = ctx;

	load.isom = gf_isom_open(in, GF_ISOM_OPEN_READ, NULL);
	e = gf_sm_load_init(&load);
	if (!e) e = gf_sm_load_run(&load);
	gf_sm_load_done(&load);
	e = gf_sm_dump(ctx, out, GF_SM_DUMP_SVG);
	gf_sm_del(ctx);
	gf_sg_del(sg);
	gf_isom_delete(load.isom);
	return e;
}

GF_Err encodelaser(char *in, GF_ISOFile *mp4, GF_SMEncodeOptions *opts) 
{
	GF_Err e;
	GF_SceneLoader load;
	GF_SceneManager *ctx;
	GF_SceneGraph *sg;
	GF_StatManager *statsman = NULL;
	
	sg = gf_sg_new();
	ctx = gf_sm_new(sg);
	memset(&load, 0, sizeof(GF_SceneLoader));
	load.fileName = in;
	load.ctx = ctx;
	e = gf_sm_load_init(&load);
	e = gf_sm_load_run(&load);
	gf_sm_load_done(&load);

	if (opts->auto_qant) {
		fprintf(stdout, "Analysing Scene for Automatic Quantization\n");
		statsman = gf_sm_stats_new();
		e = gf_sm_stats_for_scene(statsman, ctx);
		if (!e) {
			GF_SceneStatistics *stats = gf_sm_stats_get(statsman);
			if (opts->resolution > (s32)stats->frac_res_2d) {
				fprintf(stdout, " Given resolution %d is (unnecessarily) too high, using %d instead.\n", opts->resolution, stats->frac_res_2d);
				opts->resolution = stats->frac_res_2d;
			} else if (stats->int_res_2d + opts->resolution <= 0) {
				fprintf(stdout, " Given resolution %d is too low, using %d instead.\n", opts->resolution, stats->int_res_2d - 1);
				opts->resolution = 1 - stats->int_res_2d;
			}				
			opts->coord_bits = stats->int_res_2d + opts->resolution;
			fprintf(stdout, " Coordinates & Lengths encoded using ");
			if (opts->resolution < 0) fprintf(stdout, "only the %d most significant bits (of %d).\n", opts->coord_bits, stats->int_res_2d);
			else fprintf(stdout, "a %d.%d representation\n", stats->int_res_2d, opts->resolution);

			fprintf(stdout, " Matrix Scale & Skew Coefficients ");
			if (opts->coord_bits < stats->scale_int_res_2d) {
				opts->scale_bits = stats->scale_int_res_2d - opts->coord_bits;
				fprintf(stdout, "encoded using a %d.8 representation\n", stats->scale_int_res_2d);
			} else  {
				opts->scale_bits = 0;
				fprintf(stdout, "not encoded.\n");
			}
		}
	}

	if (e) {
		fprintf(stdout, "Error loading file %s\n", gf_error_to_string(e));
		goto err_exit;
	} else {
		e = gf_sm_encode_to_file(ctx, mp4, opts);
	}

	gf_isom_set_brand_info(mp4, GF_ISOM_BRAND_MP42, 1);
	gf_isom_modify_alternate_brand(mp4, GF_ISOM_BRAND_ISOM, 1);

err_exit:
	if (statsman) gf_sm_stats_del(statsman);
	gf_sm_del(ctx);
	gf_sg_del(sg);
	return e;
}

u32 loadonefile(char *item_name, Bool is_mp4, u32 nbloads) 
{
	GF_Err e;
	u32 starttime,endtime, totaltime, i; 
	GF_SceneGraph *sg;
	GF_SceneLoader load;

	totaltime = 0;
	
	for (i =0; i<nbloads; i++) {
		memset(&load, 0, sizeof(GF_SceneLoader));
		sg = gf_sg_new();
		load.ctx = gf_sm_new(sg);
		load.fileName = item_name;
		if (is_mp4) load.isom = gf_isom_open(item_name, GF_ISOM_OPEN_READ, NULL);
		starttime = gf_sys_clock();

		e = gf_sm_load_init(&load);
		if (e) return 0;

		e = gf_sm_load_run(&load);
		if (!e) gf_sm_load_done(&load);
		endtime = gf_sys_clock();
		totaltime += endtime-starttime;

		gf_sm_del(load.ctx);
		gf_sg_del(sg);
		if (is_mp4) gf_isom_delete(load.isom);
	}

	return totaltime;
}

Bool loadcompare_one(void *cbck, char *item_name, char *item_path)
{
	u32 lasersize;
	FILE *tmpfile;
	char name[100], name2[100];
	char *tmp;
	GF_LoadCompare *lc = cbck;

	strcpy(name, (const char*)item_name);
	tmp = strrchr(name, '.');
	tmp[0] = 0;
	fprintf(stdout,"Processing %s\n", name);
	fprintf(lc->out,"%s", name);
	tmp[0] = '.';

	/* MP4 */
	fprintf(stdout,"Looking for MP4 file ...");
	strcpy(name, (const char*)item_name);
	tmp = strrchr(name, '.');
	strcpy(tmp, ".mp4");
	tmpfile = fopen(name, "rb");
	if (!tmpfile) { /* LASeR encoding the file if it does not exist */
		GF_SMEncodeOptions opts;
		GF_ISOFile *mp4;
		fprintf(stdout,"not present.\nEncoding SVG into LASeR...\n");

		memset(&opts, 0, sizeof(GF_SMEncodeOptions));
		opts.auto_qant = 1;
		opts.resolution = 8;
		mp4 = gf_isom_open(name, GF_ISOM_OPEN_WRITE, NULL);
		
		encodelaser(item_name, mp4, &opts);
		gf_isom_delete(mp4);
	} else {
		fprintf(stdout,"present.\n");
		fclose(tmpfile);
	}
	fprintf(stdout,"Loading and decoding %d time(s) the MP4 file ...\n", lc->nbloads);
	fprintf(lc->out,"\t%d", loadonefile(name, 1, lc->nbloads));

	/* Dump the decoded SVG */
	fprintf(stdout,"Looking for the decoded SVG ...");
	strcpy(name2, (const char*)item_name);
	tmp = strrchr(name2, '.');
	strcpy(tmp, "_out.svg");
	tmpfile = fopen(name2, "rt");
	if (!tmpfile) {
		fprintf(stdout,"not present.\nDecoding MP4 and dumping SVG ...\n");
		tmp = strrchr(name2, '.');
		tmp[0] = 0;
		dumpsvg(name, name2);
		tmp[0] = '.';
	} else {
		fprintf(stdout,"present.\n");
		fclose(tmpfile);
	}
	
	/* SVG */
	fprintf(stdout,"Loading and parsing %d time(s) the input SVG file ...\n", lc->nbloads);
	fprintf(lc->out,"\t%d", loadonefile(item_name, 0, lc->nbloads));
	fprintf(stdout,"Loading and parsing %d time(s) the decoded SVG file ...\n", lc->nbloads);
	fprintf(lc->out,"\t%d", loadonefile(name2, 0, lc->nbloads));

	/* SVGZ */
	fprintf(stdout,"Looking for the SVGZ ...");
	strcpy(name, (const char*)item_name);
	strcat(name, "z");
	tmpfile = fopen(name, "rb");
	if (!tmpfile) { /* GZIP the file if it does not exist */
		char buffer[100];
		u32 size;
		void *gzFile;
		fprintf(stdout,"not present.\nGzipping SVG ...\n");
		gzFile = gzopen(name, "wb");
		tmpfile = fopen(item_name, "rt");
		while ((size = fread(buffer, 1, 100, tmpfile))) gzwrite(gzFile, buffer, size);
		fclose(tmpfile);
		gzclose(gzFile);
	} else {
		fprintf(stdout,"present.\n");
		fclose(tmpfile);
	}
	fprintf(stdout,"Loading, decompressing and parsing %d time(s) the SVGZ file ...\n", lc->nbloads);
	fprintf(lc->out,"\t%d", loadonefile(name, 0, lc->nbloads));

	/*Sizes */
	fprintf(stdout,"Checking file sizes\n");
	tmpfile = fopen(item_name, "rt");
	fseek(tmpfile, 0, SEEK_END);
	fprintf(lc->out,"\t%d", (u32)ftell(tmpfile));
	fprintf(stdout,"Input SVG: %d\n", (u32)ftell(tmpfile));
	fclose(tmpfile);

	strcpy(name2, (const char*)item_name);
	tmp = strrchr(name2, '.');
	strcpy(tmp, "_out.svg");
	tmpfile = fopen(name2, "rt");
	fseek(tmpfile, 0, SEEK_END);
	fprintf(lc->out,"\t%d", (u32)ftell(tmpfile));
	fprintf(stdout,"Output SVG: %d\n", (u32)ftell(tmpfile));
	fclose(tmpfile);
	gf_delete_file(name2);

	strcpy(name, (const char*)item_name);
	strcat(name, "z");
	tmpfile = fopen(name, "rb");
	fseek(tmpfile, 0, SEEK_END);
	fprintf(lc->out,"\t%d", (u32)ftell(tmpfile));
	fprintf(stdout,"SVGZ: %d\n", (u32)ftell(tmpfile));
	fclose(tmpfile);
//	gf_delete_file(name);

	strcpy(name, (const char*)item_name);
	tmp = strrchr(name, '.');
	strcpy(tmp, ".mp4");
	lasersize = getlasertracksize(name);
	fprintf(lc->out,"\t%d", lasersize);
	fprintf(stdout,"LASeR: %d\n", lasersize);
//	gf_delete_file(name);
	
	fprintf(stdout,"%s done\n", item_name);
	fprintf(lc->out,"\n");
	fflush(lc->out);
	return 0;
}

void usage() 
{
	fprintf(stdout, "Compare LASeR and SVG encoding size and loading time\n");
	fprintf(stdout, "usage: (-out output_result) (-single input.svg | -dir dir) (-nloads X)\n");
	fprintf(stdout, "defaults are: stdout, dir=. and X = 1");
}

int main(int argc, char **argv)
{
	u32 i;
	char *arg;
	GF_LoadCompare lc;
	Bool single = 0;
	char *out = NULL;
	char in[256] = ".";

	memset(&lc, 0, sizeof(GF_LoadCompare));
	lc.nbloads = 1;
	lc.out = stdout;
	
	for (i = 1; i < (u32) argc ; i++) {
		arg = argv[i];
		if (!stricmp(arg, "-out")) {
			out = argv[i+1];
			i++;
		} else if (!stricmp(arg, "-single")) {
			single = 1;
			strcpy(in, argv[i+1]);
			i++;
		} else if (!stricmp(arg, "-dir")) {
			strcpy(in, argv[i+1]);
			i++;
		} else if (!stricmp(arg, "-nloads")) {
			lc.nbloads = (u32)atoi(argv[i+1]);
			i++;
		} else {
			usage();
			return -1;
		}	
	}

	gf_sys_init();
	if (out) lc.out = fopen(out, "wt");
	if (!lc.out) {
		fprintf(stderr, "Cannot open output file %s\n", out);
		return -1;
	}

	fprintf(lc.out,"File Name\tMP4 Load Time\tInput SVG Load Time\tDecoded SVG Load Time\tSVGZ Load Time\tSVG Size\tDecoded SVG Size\tSVGZ Size\tLASeR track size\n");

	if (single) {
		loadcompare_one(&lc, in, NULL);
	} else {
		gf_enum_directory(in, 0, loadcompare_one, &lc, "svg");
	}
		
	if (lc.out) fclose(lc.out);
	gf_sys_close();
	return 0;
}

