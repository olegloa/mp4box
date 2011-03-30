//#include "wrapper_jni.h"

#ifdef __cplusplus
extern "C" {
#endif

#include <android/log.h>

#define jniTAG "WRAPPER_JNI"

#define jniLOGV(X)  __android_log_print(ANDROID_LOG_VERBOSE, jniTAG, X)

#define CAST_HANDLE(wr) jclass c = env->GetObjectClass(obj);\
                        if (!c) return;\
                        jfieldID fid = env->GetFieldID(c, "handle", "J");\
                        if (!fid){\
                          __android_log_print(ANDROID_LOG_ERROR, jniTAG, "No Field ID, ERROR");\
                          return;\
                        }\
                        jlong h = env->GetLongField(obj, fid);\
                        CNativeWrapper* wr = (CNativeWrapper*) h;
//                        __android_log_print(ANDROID_LOG_VERBOSE, jniTAG, "Handle = %p", wr);

/*
 * Class:     com_artemis_Osmo4_GpacObject
 * Method:    gpacinit
 * Signature: (Ljava/lang/Object;Lcom/artemis/Osmo4/GpacCallback;IILjava/lang/String;Ljava/lang/String;Ljava/lang/String;Ljava/lang/String;)V
 */
JNIEXPORT jlong JNICALL Java_com_artemis_Osmo4_GPACInstance_createInstance(JNIEnv * env, jclass obj, jobject callback, jint width, jint height, jstring cfg_dir, jstring modules_dir, jstring cache_dir, jstring font_dir)
{
        jboolean isCopy;
        const char * s1 = env->GetStringUTFChars(cfg_dir, &isCopy);
        const char * s2 = env->GetStringUTFChars(modules_dir, &isCopy);
        const char * s3 = env->GetStringUTFChars(cache_dir, &isCopy);
        const char * s4 = env->GetStringUTFChars(font_dir, &isCopy);
        const char * s5 = NULL;
/*        if (urlToLoad)
          s5 = env->GetStringUTFChars(font_dir, &isCopy);
        else
          s5 = NULL;*/
        CNativeWrapper * gpac_obj = new CNativeWrapper();
        if (gpac_obj){
          int w = width;
          int h = height;
          if (gpac_obj->init(env, NULL, &callback,
                              w, h,
                              s1, s2, s3, s4, s5)){
            jniLOGV("FAILED to init(), return code not 0");
            delete gpac_obj;
            gpac_obj = NULL;
          }
        }

        env->ReleaseStringUTFChars(cfg_dir, s1);
        env->ReleaseStringUTFChars(modules_dir, s2);
        env->ReleaseStringUTFChars(cache_dir, s3);
        env->ReleaseStringUTFChars(font_dir, s4);
        if (s5)
          env->ReleaseStringUTFChars(font_dir, s5);
        __android_log_print(ANDROID_LOG_VERBOSE, jniTAG, "Returned Handle = %p", gpac_obj);
        return (jlong) gpac_obj;
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpacconnect(JNIEnv * env, jobject obj, jstring fileName)
{
        CAST_HANDLE(wr);
        jniLOGV("connect::start");
        if (!wr){
          jniLOGV("connect::end : aborted");
          return;
        }
        jboolean isCopy;
        const char * cFileName = env->GetStringUTFChars(fileName, &isCopy);

        wr->connect(cFileName);

        env->ReleaseStringUTFChars(fileName, cFileName);
        jniLOGV("connect::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpacdisconnect(JNIEnv * env, jobject obj){
        CAST_HANDLE(wr);
        jniLOGV("disconnect::start");
        if (wr)
          wr->disconnect();
        jniLOGV("disconnect::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpacfree(JNIEnv * env, jobject obj)
{
        CAST_HANDLE(wr);
        jniLOGV("free::start");
        if (wr)
          delete wr;
        jniLOGV("free::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpacrender (JNIEnv * env, jobject obj)
{
        CAST_HANDLE(wr);
        //jniLOGV("render::start");
        if (wr)
          wr->step(env, NULL);
        //jniLOGV("render::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpacresize (JNIEnv * env, jobject obj, jint width, jint height)
{
        CAST_HANDLE(wr);
        jniLOGV("resize::start");
        if (wr)
          wr->resize(width, height);
        jniLOGV("resize::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpaceventmousedown(JNIEnv * env, jobject obj, jfloat x, jfloat y){
        CAST_HANDLE(wr);
        jniLOGV("mouseDown::start");
        if (wr)
          wr->onMouseDown(x, y);
        jniLOGV("mouseDown::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpaceventmouseup(JNIEnv * env, jobject obj, jfloat x, jfloat y){
        CAST_HANDLE(wr);
        jniLOGV("mouseUp::start");
        if (wr)
          wr->onMouseUp(x, y);
        jniLOGV("mouseUp::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpaceventmousemove(JNIEnv * env, jobject obj, jfloat x, jfloat y){
        CAST_HANDLE(wr);
        jniLOGV("mouseMouv::start");
        if (wr)
          wr->onMouseMove(x, y);
        jniLOGV("mouseMouv::end");
}
//-----------------------------------
JNIEXPORT void JNICALL Java_com_artemis_Osmo4_GPACInstance_gpaceventkeypress(JNIEnv * env, jobject obj, jint keycode, jint rawkeycode, jint up, jint flag){
        CAST_HANDLE(wr);
        jniLOGV("keypress::start");
        if (wr)
          wr->onKeyPress(keycode, rawkeycode, up, flag);
        jniLOGV("keypress::end");
}
//-----------------------------------

#ifdef __cplusplus
}
#endif
