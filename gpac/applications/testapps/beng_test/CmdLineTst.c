#include <gpac/bifsengine.h>

GF_Err SampleCallBack(void *calling_object, u16 ESID, char *data, u32 size, u64 ts)
{
	fprintf(stdout, "Received at time %I64d, buffer %d bytes long.\n", ts, size);
	return GF_OK;
}

int main(int argc, char **argv)
{
	int i;
	GF_BifsEngine *codec1 = NULL;
	GF_BifsEngine *codec2 = NULL;

	gf_sys_init();

	gf_log_set_level(GF_LOG_INFO);
	gf_log_set_tools(0xFFFFFFFF);

	if (1) {
		/*these default update are related to rect_aggregate.bt*/
		char update1[] = "AT 1000 IN 2 {\
						INSERT AT OG.children[0] DEF TR2 Transform2D {\
						translation -100 -50\
						children [\
						DEF S Shape {\
						appearance Appearance {\
						material Material2D {\
						emissiveColor 1 0 0\
						filled TRUE\
						} }\
						geometry DEF REC Rectangle {\
						size 50 100\
						} } ] } }";
		char update2[] = "AT 2000 IN 3 {\
						REPLACE REC.size BY 100 100\
						}";

		codec1 = gf_beng_init(NULL, argv[1]);

		gf_beng_encode_context(codec1, SampleCallBack);
		gf_beng_save_context(codec1, "initial_context.bt");
		gf_beng_encode_from_string(codec1, (char*)update1, SampleCallBack);
		gf_beng_save_context(codec1, "non_aggregated_context1.xmt");
		gf_beng_encode_from_string(codec1, (char*)update2, SampleCallBack);
		gf_beng_save_context(codec1, "non_aggregated_context2.xmt");
		gf_beng_mark_for_aggregation(codec1, 2); /*mark ESID 2 for aggregation*/
		gf_beng_aggregate_context(codec1);
		gf_beng_save_context(codec1, "aggregated_context2.xmt");
		gf_beng_terminate(codec1);
	} else if (1) {
		char scene[] = "OrderedGroup {children [Background2D {backColor 1 1 1}Shape {appearance Appearance {material DEF M Material2D {emissiveColor 0 0 1 filled TRUE } } geometry Rectangle { size 100 75 } } ] }";
		char update[] = "\n AT \n 500 \n { \n REPLACE \n M.emissiveColor BY 1 0 0 \n REPLACE \n M.filled BY FALSE} \n";

		codec1 = gf_beng_init_from_string(NULL, scene, 200, 200, 1);

		gf_beng_encode_context(codec1, SampleCallBack);
		gf_beng_save_context(codec1, "initial_context.mp4");
		gf_beng_encode_from_string(codec1, (char *) update, SampleCallBack);
		gf_beng_save_context(codec1, "non_aggregated_context.mp4");
		gf_beng_aggregate_context(codec1);
		gf_beng_save_context(codec1, "aggregated_context.mp4");
		gf_beng_terminate(codec1);
	} else {

		for (i = 0; i <10; i++) {
			char context_rootname[] = "rect_context";
			char in_context[100], 
				 bt_out_na_context[100], bt_out_agg_context[100],
				 mp4_out_na_context[100], mp4_out_agg_context[100];
			char update[1000];// = "REPLACE M.emissiveColor BY 1 1 0";
			char timed_update[1000];

			sprintf(update, "REPLACE M.emissiveColor BY %f 0 0", i/10.0f);

			if (i != 0) {
				sprintf(in_context, "na_%s_%i.bt", context_rootname, i);
			} else {
				strcpy(in_context, "rect.bt");
			}

			codec2 = gf_beng_init(NULL, in_context);

			sprintf(timed_update, "AT %i { %s }", 1000 + i, update);
			
			gf_beng_encode_from_string(codec2, timed_update, SampleCallBack);

			sprintf(mp4_out_na_context, "na_%s_%i.mp4", context_rootname, i+1);
			sprintf(bt_out_na_context, "na_%s_%i.bt", context_rootname, i+1);
			sprintf(mp4_out_agg_context, "agg_%s_%i.mp4", context_rootname, i+1);
			sprintf(bt_out_agg_context, "agg_%s_%i.bt", context_rootname, i+1);

			gf_beng_save_context(codec2, mp4_out_na_context);
			gf_beng_save_context(codec2, bt_out_na_context);
			gf_beng_aggregate_context(codec2);
			gf_beng_save_context(codec2, mp4_out_agg_context);
			gf_beng_save_context(codec2, bt_out_agg_context);
		
			gf_beng_terminate(codec2);
		}
		fprintf(stdout, "Done.\n");
	}

	gf_sys_close();

	return 0;
}
