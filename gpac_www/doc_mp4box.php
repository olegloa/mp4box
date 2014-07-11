<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MP4BOX Documentation - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
MP4Box is a multimedia packager, with a vast number of functionalities: conversion, splitting, hinting, dumping and others. It is a command-line tool whose major switches are documented here.
		</h1>
	</div>


<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre_court">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("pack_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
<div id="sous_menu">
<ul>
<li><a href="#misc">Misc</a></li>
<li><a href="#meta">MetaData</a></li>
<li><a href="#ismacryp">ISMA E&amp;A</a></li>
<li><a href="#coding">Coding</a></li>
<li><a href="#extraction">Extraction</a></li>
<li><a href="#dumping">Dumping</a></li>
<li><a href="#hinting">Hinting</a></li>
<li><a href="#split_concat">Splitting</a></li>
<li><a href="#conversion">Conversion</a></li>
<li><a href="#general">General</a></li>
</ul>
</div>

<h1 id="Introduction">Introduction</h1>
<p>This document may refer to IsoMedia files. IsoMedia is a generic name for all formats based on the MPEG-4 Part 12 specification: MP4, 3GP and MJ2K files. Support for MJ2K files has not be tested in GPAC yet.
</p>
<p>As of version 0.2.4, MP4Box performs in-place rewrite of IsoMedia files (the input file is overwritten). You can change this behaviour by using the <i>-out Filename</i> option.

</p>
<p>For older versions, when MP4Box is used to modify an existing IsoMedia file, the original file (for example <i>AFILE.mp4</i>) is NOT overwritten, the resulting file is stored in <i>out_AFILE.mp4</i>. To specify another name for the resulting file, use the <i>-out Filename</i> option.
</p>
<p>As of version 0.2.4, MP4Box always stores the file with 0.5 second interleaving and meta-data at the begining, making it suitable for HTTP streaming.
</p>
<p>MP4Box usually generates a temporary file when creating a new IsoMedia file. The location of this temporary file is OS-dependent, and it may happen that the drive/partition the temporary file is created on has not enough space or no write access. In such a case, you can specify a temporary file location with the <i>-tmp path_to_dir</i> option.
</p>

<p>MP4Box does NOT perform audio/video/image transcoding (re-encoding media tracks to a different coded format). If you need to transcode content, you will need other tools.
</p>
<p>As of version 0.2.2, you don't need to follow any specific option ordering at prompt.
</p>
<p>Please be aware that this page documents the latest version of MP4Box and may therefore give details on options available only on GPAC SVN. If your version of MP4Box does not support an option please upgrade.
</p>

<h1 id="general">General operations ("MP4Box -h general")</h1>
<p>Most of these options are used to specify how to store a given file, either just created/converted or existing.</p>
<p><b><i>-tmp dir</i></b>: specifies where the temporary file(s) used by MP4Box shall be created. This is quite usefull on Windows systems where user may not has the rights to create temporary files. By default, MP4Box uses the OS temporary file handling as provided in C stdio.</p>
<p><b><i>-inter Duration</i></b> : interleaves media data in chunks of desired duration (in seconds). This is usefull to optimize the file for HTTP/FTP streaming or reducing disk access. All meta data are placed first in the file, allowing a player to start playback while downloading the content. By default MP4Box always stores files with half a second interleaving and performs drift checking between tracks while interleaving. Specifying a 0 interleaving time
will result in the file being stored without interleaving, with all meta-data placed at beginning of the file.</p>

<p><b><i>-tight</i></b> : performs sample-based interleaving of media tracks (!!the created file is much larger !!). This is normally used when hinting a file, in order to reduce disk seeks at server side (depending on server implementation).</p>
<p><b><i>-flat</i></b> : forces flat storage of the file: media data placed at the begining of the file without interleaving, and meta-data at the end of the file. When used with <i>-add</i> to create a new file, no temporary file is created (faster storage).</p>
<p><b><i>-frag time_ms</i></b> : fragments the file with fragments of given duration. Movie fragmenting allows meta data (timing and co) to be interleaved with media data rather than at the begining or at the end of the file. Frgamenting a file will always disable interleaving.</p>
<p><b><i>-out fileName</i></b> : specifies to store the modified file to a different file, rather than overriding the input file.</p>

<p><b><i>-new</i></b> : forces creation of a new destination file. This is usefull when importing media in batch processes for example. If not set and an existing file with the given name is found, all media import operations will be done on this file. This option is ignored when encoding scenes.</p>
<p><b><i>-no-sys</i></b> : removes all MPEG-4 systems tracks and keeps an empty InitialObjectDescriptor will be left in the file for MPEG-4 Level@Profile indications.</p>
<p><b><i>-no-iod</i></b> : removes the file InitialObjectDescriptor.</p>
<p><b><i>-isma</i></b>: converts file to ISMA 1.0 specification. This is extremely usefull since most MPEG-4 players only understand ISMA-like content. All systems information and tracks numbering are rewritten to comply to the specification.
<br/>WARNING: some media tracks may be removed.</p>
<p><b><i>-3gp</i></b> : converts to 3GPP specification. This will remove all MPEG-4 Systems information, leaving only the audio/video/text media tracks supported by 3GPP. This option is always turned on when the file extension is '3gp' or '3g2'.

<br/>WARNING: some media tracks may be removed.</p>
<p><b><i>-brand ABCD[:v]</i></b> : sets the major brand of a file. Brands are used to identify the most common usage of a file (MPEG-4 presentation, 3GP movie, etc...). If 'v' is set, also sets the version of the brand (default version is 0).</p>
<p><b><i>-ab ABCD</i></b> : adds an alternate brand to the file. Alternate Brands are used to identify the other possible usage of a file (whether the 3GP file compliant with MPEG-4, etc...)</p>
<p><b><i>-rb ABCD</i></b> : removes an alternate brand from the file. </p>
<p><b><i>-rem trackID</i></b> : removes given track from file.</p>

<p><b><i>-par trackID=PAR</i></b> : sets pixel aspect ratio of given track. <i>PAR</i> can be "none" to remove PAR info, or of the form "N:D" where N is PAR numerator and D its denominator. Only supported for MPEG-4 Visual and MPEG-4 AVC/H264</p>
<p><b><i>-lang [trackID=]lang</i></b> : sets the language of the given track or of all tracks if <i>trackID</i> is not specified. The language can be either ISO 639-1 2-char code, ISO 639-2 3-char code, or the full language name. To get the listing of supported languages, use <i>MP4Box -languages</i></p>
<p><b><i>-delay trackID=TIME</i></b> : sets track start-time offset, specified in milliseconds.</p>

<p><b><i>-name trackID=NAME</i></b> : sets track handler name. Handler name is sometimes used to identify the track content (for example, audio language).</p>
<p><b><i>-cprt string</i></b> : adds copyright to file.</p>
<p><b><i>-chap chap_file</i></b> : adds chapter information located in <i>chap_file</i> to the destination file. Chapter extensions have been introduced by Nero and are NOT standard extensions of IsoMedia file format, don't be surprised if some players don't understand them.</p>
The following syntaxes are supported in the chapter text file, with one chapter entry per line:
<ul>

<li><i>ZoomPlayer chapter files</i> : <b>AddChapter(nb_frames,chapter name)</b>, <b>AddChapterBySeconds(nb_sec,chapter name)</b> and <b>AddChapterByTime(h,m,s,chapter name)</b>. One chapter entry per line.</li>
<li><i>Time codes</i> : <b>h:m:s name</b>, <b>h:m:s:ms name</b> and <b>h:m:s.ms name</b>. One chapter entry per line.</li>

<li><i>SMPTE codes</i> : <b>h:m:s;nb_f/fps name</b> and <b>h:m:s;nb_f name</b> with <i>nb_f</i> the number of frames and <i>fps</i> the framerate. One chapter entry per line.</li>
<li><i>Common syntax</i> : <b>CHAPTERX=h:m:s[:ms</b> or <b>.ms]</b> on one line and <b>CHAPTERXNAME=name</b> on the other - the order is not important but chapter lines MUST be declared sequencially (same <i>X</i> value expected for 2 consecutive lines).</li>

</ul>
<p>Some existing MP4 files may use MPEG-4 Visual tracks with B-Frames in an improper way. There is currently no automatic cleaning of such files
in MP4Box, but reimporting the track will solve the problem. To do this:</p>
<ul>
<li><i>MP4Box -avi trackID file.mp4</i>: exports track to avi (raw MPEG-4 video also possible).</li>
<li><i>MP4Box -import file.avi dest.mp4</i>: converts avi into MP4 and handles B-Frame correct import (packed bitstreams, n-Vops).</li>
</ul>
<br/><br/>

<h1 id="conversion">File conversion ("MP4Box -h import")</h1>
MP4Box can convert the following files into compliant IsoMedia files:
<ul>

<li>RAW Formats and extensions:
<ul>
<li>MPEG-4 Video (.cmp .m4v)</li>
<li>MPEG-4 Audio ADTS-AAC (.aac) - ADIF or RAW formats not supported</li>
<li>MPEG-1/2 Video (.m1v .m2v)</li>
<li>MPEG-1/2 Audio (.mp3)</li>
<li>JPEG and PNG Images (.jpg .jpeg .m4v)</li>
<li>H263 Video (.263 .h263)</li>
<li>AVC/H264 Video (.264 .h264, .26l .h26l)</li>
<li>AMR and AMR-WideBand Speech (.amr .awb)</li>

<li>EVRC Speech (.evc)</li>
<li>SMV Speech (.smv)</li>
<li>VobSub subtitles (.idx)</li>
</ul>
</li>
<li>AV Containers and extensions:
<ul>
<li>AVI (.avi) - Only MPEG-4 SP/ASP video and MP3 audio supported at the current time. To import AVC/H264 video, you must first extract the avi track with <i>MP4Box -aviraw video file.avi</i>.</li>
<li>MPEG-TS (.m2t .ts) - Only MPEG-1/2 video and MPEG-1/2 audio supported at the current time.</li>
<li>MPEG-PS (.mpg .mpeg .vob) - Only MPEG-1/2 video and MPEG-1/2 audio supported at the current time.</li>

<li>QCP (.qcp)</li>
<li>XIPH OGG (.ogg) - EXPERIMENTAL and not relevant to any IsoMedia-based standards. Only Vorbis audio and Theora video supported.</li>
<li>NHNT (.media .info .nhnt) - for more info on this format, check the <a href="doc_nhnt.php">NHNT documentation</a>.</li>
<li>NHML (.nhml) - for more info on this format, check the <a href="doc_nhnt.php#NHML">NHML documentation</a>.</li>
<li>SAF (.saf) - SAF is the MPEG-4 LASeR transport format over http.</li>
<li>IsoMedia files (no extension checking)</li>
</ul>

</li>
<li>Text formats and extensions:
<ul>
<li>SRT Subtitles (.srt)</li>
<li>SUB Subtitles (.sub)</li>
<li>QuickTime TeXML (.xml) - cf <a href="http://developer.apple.com/documentation/QuickTime/QT6_3/index.html">QT documentation</a></li>
<li><a href="doc_ttxt.php">GPAC Timed Text</a> (.ttxt)</li>
</ul>
</li>
</ul>

<p>The conversion syntax is <b>MP4Box -add inputFile destinationFile</b>. This option is used to import media from several sources. You can specify up to 20 <i>-add</i> in common MP4Box builds. This
process will create the destination file if not existing, and add the track(s) to it. If you wish to erase the destination file, just add the <i>-new</i> option.</p>
<br/>
<h2>Input file track selection</h2>
To select a desired media track, the following syntax is used:
<ul>
<li><i>-add inputFile#video</i>: adds the first video track in <i>inputFile</i>. DOES NOT WORK for IsoMedia nor MPEG-2 TS files.</li>

<li><i>-add inputFile#audio</i>: adds the first audio track in <i>inputFile</i>. DOES NOT WORK for IsoMedia nor MPEG-2 TS files.</li>
<li><i>-add inputFile#trackID=ID</i> or <i>-add inputFile#ID</i>: adds the specified track. For IsoMedia files, ID is the track ID. For other media files, ID is the value indicated by <i>MP4Box -info inputFile</i>.</li>
</ul>
<p>MP4Box can import a desired amount of the input file rather than the whole file. To do this, use the syntax <i>-add inputFile%N</i>, where N is the number of seconds you wish to import from input. MP4Box cannot start importing from a random point in the input, it always import from the begining.</p>

<p>When using <i>-add</i> option, MP4Box will automatically create default BIFS and OD tracks to make the resulting file compliant with the ISMA 1.0 standard if possible. If the destination file extension is .3gp or .3g2, MP4Box will 
automatically make the file 3GP(2) compliant. This means that MP4Box will always remove any systems tracks when using <i>-add</i>, you may prevent this by using the <i>-keepsys</i> option. If the destination file extension is .m4a, MP4Box will automatically setup the proper informations needed by iTunes. </p>
<p>When using <i>-add</i> option to import an existing IsoMedia file, MP4Box will automatically <b>REMOVE ALL TRACKS</b> not complying to the MPEG-4 or 3GPP(2) specifications. If you want to keep such tracks, use the <i>-keepall</i> option.</p>

<p><i>Note on text import</i> : When importing SRT or SUB files, MP4Box will choose default layout options to make the subtitle appear at the bottom of the video. 
You SHOULD NOT import such files before any video track is added to the destination file, otherwise the results will likelly not be usefull (default SRT/SUB importing uses default serif font, fontSize 18 and display size 400x60). For more details on 3GPP timed text, please <a href="doc_ttxt.php">go here</a>.
</p>
<br/>

<h2>Import Options</h2>
<p>There are several media-specific options which can be used when importing media. To know which options are supported for non-IsoMedia files, use the <i>-info</i> option for the desired media track, for example <i>MP4Box -info 2 file.mpg</i>.
</p>

<p><b><i>-dref</i></b> : MP4Box can import media data without copying it, this is called <i>data referencing</i>. The resulting file only contains the meta-data of the presentation (frame sizes, timing, etc...) and references media data in the original file. 
This is extremely usefull when developping content, since importing and storage of the MP4 file is much faster and the resulting file much smaller. Use the <i>-dref</i> option to enable data referencing.
</p><p><i>Note</i> : Data referencing may fail on some files because it requires the framed data (eg an IsoMedia sample) to be continuous in the original file, which is not always the case depending on the original interleaving or bitstream format.
</p>
<p><b><i>-sbr</i></b> : forces importing the AAC-ADTS file as AAC SBR (aka HE-AAC, aka aacPlus) with backward compatible signaling (eg non SBR aware decoders should play the file).</p>
<p><b><i>-sbrx</i></b> : forces importing the AAC-ADTS file as AAC SBR (aka HE-AAC, aka aacPlus) with non-backward compatible signaling (eg non SBR aware decoders should NOT play the file).

</p><p><i>Note</i> : MP4Box cannot detect whether AAC input is regular or SBR AAC, so you must use one fo these options if you want to import AAC SBR files.
</p>
<p><b><i>-nodrop</i></b> : Some AVI files may have non-coded frames (n-VOPs) introduced by the encoder. By default, MP4Box will discard these frames, hence producing a variable frame-rate visual stream. You can force MP4Box to keep constant frame-rate by specifying <i>-nodrop</i> while importing the AVI file.</p>
<p><b><i>-packed</i></b>: When importing raw MPEG-4 Video, forces considering the bitstream as the dump of an AVI Packed Bitstream (removes all n-vops and import as constant FPS).</p>
<p><b><i>-fps FrameRate</i></b> : If possible, will override the original video frame rate. This option is also used when importing SUB text files to specify the SUB framerate. <i>Framerate</i> is a double-precision number.</p>

<p><b><i>-mpeg4</i></b> : This option forces MPEG-4 stream descriptions for formats having several description syntax available (QCELP, EVRC and SMV audio).</p>
<p><b><i>-agg N</i></b> : Aggregates N audio frames in an IsoMedia sample. This option is only valid for some 3GP(2) audio formats (AMR, QCELP, EVRC and SMV audio). The maximum acceptable value is 15.</p>
<br/>
<p>When importing several tracks/sources in one pass, all options will be applied if relevant to each source. These options are set for all imported streams. If you need to specify these options par stream, the syntax is:
<br/><b>MP4Box -add stream[:opt1:...:optN]	dest.mp4</b>
</p>
<br/>The following options are available:
<ul>
<li><i>fps=N</i> same as -fps, but only applies to the imported media.</li>

<li><i>lang=language</i> specifies the frame rate for the imported media.</li>
<li><i>delay=delta</i> specifies the frame rate for the imported media.</li>
<li><i>dref</i> same as -dref, but only applies to the imported media.</li>
<li><i>nodrop</i> same as -nodrop, but only applies to the imported media.</li>
<li><i>packed</i> same as -packed, but only applies to the imported media.</li>

<li><i>sbr</i> same as -sbr, but only applies to the imported media.</li>
<li><i>sbrx</i> same as -sbrx, but only applies to the imported media.</li>
<li><i>agg=val</i> same as -agg, but only applies to the imported media.</li>
<li><i>dur=</i> specifies amount of media to be imported, in seconds.</li>
<li><i>par=A:B</i> specifies the Pixel Aspect Ratio to assign to the imported media, or "none".</li>

<li><i>name=Val</i> specifies the name to give to the media track.</li>
</ul>

<p><i>Note on OGG Support</i> : MP4Box can import OGG files containing either Vorbis audio or Theora video. This feature is experimental and support for these media formats in IsoMedia files is NOT STANDARDIZED anywhere. This should only be used for development and R&amp;D purposes, and you must be aware
that files created this way may be unusable, even with future versions of GPAC.</p>

<br/><br/>

<h1 id="split_concat">File Splitting and Concatenation ("MP4Box -h general")</h1>
<p>MP4Box can split IsoMedia files by size, duration or extract a given part of the file to new IsoMedia file(s). This process requires that at most one track in 
the input file has non random-access points (typically one video track at most). This process will also ignore all MPEG-4 Systems tracks and hint tracks, but will try to split private media tracks.

</p>
<ul>
<li><i>Note 1</i> : The input file must have enough random access points in order to be splitted. This may not be the case with some video files where only the very first 
sample of the video track is a key frame (many 3GP files with H263 video are recorded that way). In order to split such files you will have to use a real video editor and re-encode the content.</li>
<li><i>Note 2</i> : You can add media to a file and split it in the same pass. In this case, the destination file (the one which would be obtained without spliting) will not be stored.</li>
</ul>
<p><b><i>-split time_in_seconds</i></b> : splits the input file in a sequence of files lasting at most the specified time. Depending on random access distribution in the file (sync samples), the
duration of the resulting files may be less than specified.</p>
<p><b><i>-splits size_in_kb</i></b> : splits the input file in a sequence of files of maximum specified size. Depending on random access distribution in the file (sync samples), the
size of the resulting files may be less than specified.</p>

<p><b><i>-splitx StartTime:EndTime</i></b> : extracts a subfile from the input file. <i>StartTime</i> and <i>EndTime</i> are specified in seconds. Depending on random access distribution in the file (sync samples), the
startTime will be adjusted to the previous random access time in the file. </p>
<p><b><i>-cat a_file</i></b> : concatenates <i>a_file</i> to input file (samples are added to existing tracks rather than added to new tracks). The usage is the same as <i>-add</i>, you may use non IsoMedia input files (for example, AVIs or MPEGs) and 
concatenates them directly into a new IsoMedia file. This process will remove all MPEG-4 systems tracks from the final file and make it compliant to ISMA or 3GP just like the <i>-add</i> process. You can instruct MP4Box not to remove MPEG-4 
systems tracks by specifying <i>-keepsys</i>.</p>


<h1 id="hinting">File hinting ("MP4Box -h hint")</h1>
<p>IsoMedia File Hinting consists in creating special tracks in the file that contain transport protocol specific information and optionally multiplexing information. These tracks are then used by the server to create the actual packets being sent over the network, in other words they provide the server 'hints' regarding packet building, hence their names: Hint Tracks.</p>
<p>MP4Box can generate these hint tracks for the RTP protocol (the most widely used protocol for multimedia streaming). The resulting file can then be streamed to clients with any streaming server understanding the IsoMedia file format and hint tracks, such as Apple's QTSS/DSS servers.</p>
<p><b><i>-hint</i></b> : hints the given file for RTP/RTSP</p>
<p><b><i>-mtu size</i></b> : specifies the desired maximum packet size, or MTU (Maximum Transmission Unit). This must be choosen carefully: specifying too large packets will result in undesired packet fragmentation at lower transport layers. The default size when hinting is 1450 bytes (including the 12 bytes RTP header).</p>
<p><b><i>-multi [maxptime]</i></b> : enables sample concatenation in a single RTP packet for payload formats supporting it. <i>maxptime</i> is an optional integer specifying the maximum packet duration in milliseconds, used for some audio payloads. Its default value is 100 ms.</p>

<p><b><i>-copy</i></b> : forces hinted data to be copied to the hint track. This speeds up packet building at server side but takes much more space on disk.</p>
<p><b><i>-rate clock_rate</i></b> : specifies the rtp clock rate in Hz when no default one exists for the given RTP payload. The default rate of most AV formats is 90000 Hz or the audio sample rate.</p>
<p><b><i>-mpeg4</i></b> : forces usage of MPEG-4 Generic Payload whenever possible.</p>
<p><b><i>-latm</i></b> : forces usage of LATM payload for MPEG-4 AAC.</p>
<p><b><i>-static</i></b> : enables usage of static RTP payload IDs (pre-defined IDs as specified in RTP). By default MP4Box always uses dynamic payload IDs, since some players do not recognize static ones.</p>

<p><b><i>-sdp_ex string</i></b> : adds the given text to the movie SDP information (<i>-sdp_ex "a=x-test: an sdp test"</i>) or to a track (<i>-sdp_ex "N:a=x-test"</i>, where N is the hint track or its base track ID). This will take care of SDP line ordering. WARNING: You cannot add anything to SDP, please refer to <a href="http://www.faqs.org/rfcs/rfc2327.html">RFC2327</a> for more info.</p>
<p><b><i>-unhint</i></b> : removes all hint tracks and SDP information from file. This can be usefull since MP4Box doesn't remove any existing hint tracks when hinting the file.</p>
<br/>
<p>For advanced users, MP4Box can allow you to specify special options of the MPEG-4 Generic RTP payload format:</p>

<br/>
<p><b><i>-ocr</i></b> : forces all media tracks in the file to be served synchronized. This is needed because most streaming servers don't support desynchronized tracks in a single file. Be extremelly carefull when designing MPEG-4 interactive presentations for streaming since you will have to take care of the streaming server capabilities... 
MP4Box generates warnings when the file timeline can be ambiguously interpreted by the server.</p>
<p><b><i>-iod</i></b> : prevents ISMA-like IOD generation in SDP. MP4Box automatically detects ambiguous (ISMA/non-ISMA) files but nobody's perfect. This shouldn't be used with -isma option.</p>
<p><b><i>-rap</i></b> : signals random access points in the payload.</p>
<p><b><i>-ts</i></b> : signals AU timestamps in the payload. This option is automatically turned on when B-Frames (or similar) are detected in the media.</p>

<p><b><i>-size</i></b> : signals AU size in the payload.</p>
<p><b><i>-idx</i></b> : signals AU sequence number in the payload. </p>

<br/>
<p>MP4Box always detects the best payload possible and when not found gets back to MPEG-4 Generic payload. The configuration of the 
MPEG-4 Generic payload is quite complex, so MP4Box always computes the most suitable configuration for you.</p> 
<br/><br/>
<i>Examples:</i>
<ul>
<li><i>Prepare any mp4 for ISMA streaming</i>: MP4Box -isma -hint myfile.mp4</li>

<li><i>Prepare an mp4 optimized for server</i>: MP4Box -hint -copy -tight myfile.mp4</li>
<li><i>Prepare a complex mp4 with BIFS for streaming</i>: MP4Box -ocr -iod -hint myfile.mp4</li>
<li><i>Prepare any 3GP/MP4 for safe streaming</i>: MP4Box -nosys -hint myfile.3gp</li>
</ul>

<p><i>Q&amp;As:</i></p>
<h2><i>Can I stream MP4 files created with MPEG4IP's mp4creator to GPAC?</i></h2>
It depends. mp4creator hints mp3 audio with MPA or MPA-robust payload formats, specific to mp3 streams. MPA-robust is
not supported in GPAC and is not on the list of priorities, we strongly prefer working with RFC3640 payload for MPEG-4 streams. 
However if no MPA-robust payload is used, both players and hinters should interoperate.

<h2><i>Can I stream MP4 files created with MP4Box to MPEG4IP player?</i></h2>
It depends. MPEG4IP works with ISMA / plain AV files, therefore you should first convert your file to ISMA before hinting.
<h2><i>Can I stream complex MPEG-4 presentations created with MP4Box to any player ???</i></h2>
Yes and no. GPAC uses RFC3640 to stream MPEG-4 systems information, and most players don't accept that (they usually use their own format). Moreover
RTSP servers as known today only understand simple synchronized presentations, and most MPEG-4 presentations have too complex timing for servers to handle. If 
you need to know more about that join us in our forums.
<br/>
<br/>

<h1 id="dumping">File Dumping and information ("MP4Box -h dump")</h1>
MP4Box has many dump functionalities, from simple track listing to more complete reporting of special tracks
<p><b><i>-info</i></b> : prints some file information. File can be an IsoMedia file or any file supported by MP4Box for import.</p>
<p><b><i>-info TrackID</i></b> : prints extended track information for IsoMedia files, and supported import flags for other files.</p>

<p><b><i>-std</i></b> : dumps to stdout instead of file.</p>
<p><b><i>-diso</i></b> : creates XML dump of the file structure.</p>
<p><b><i>-drtp</i></b> : creates XML dump of all hint tracks samples of a hinted mp4 file.</p>
<p><b><i>-dcr</i></b> : creates XML dump of all ISMACryp tracks.</p>
<p><b><i>-dts</i></b> : dumps DTS (decoding timestamp) and CTS (composition timestamp) of all tracks, reporting found errors.</p>

<p><b><i>-sdp</i></b> : creates SDP file associated with a hinted mp4 file.</p>
<p><b><i>-ttxt</i></b> : converts input subtitle (SRT, SUB) to GPAC TTXT format.</p>
<p><b><i>-ttxt TrackID</i></b> : dumps text track to TTXT XML format.</p>
<p><b><i>-srt</i></b> : converts input subtitle (TTXT, SUB) to SRT format.</p>
<p><b><i>-srt TrackID</i></b> : dumps text track to SRT format.</p>

<br/><br/>

<h1 id="extraction">Media track Extraction ("MP4Box -h extract")</h1>
MP4Box can extract media tracks in a variety of formats:
<p><b><i>-raw TrackID</i></b> : extracts track to its native format. </p>
<p><b><i>-raws TrackID</i></b> : extracts each track sample to a file. To extract a single sample, use <i>-raws TrackID:N</i></p>
<p><b><i>-avi TrackID</i></b> : extracts visual track in avi format (MPEG-4 Visual and AVC/H264 supported).</p>

<p><b><i>-nhnt TrackID</i></b> : extracts track in <a href="doc_nhnt.php">NHNT format</a>.</p>
<p><b><i>-nhml TrackID</i></b> : extracts track in <a href="doc_nhnt.php#nhml">NHML format</a>.</p>
<p><b><i>-qcp TrackID</i></b> : same as <i>-raw</i> but defaults to QCP file for EVRC/SMV.</p>

<p><b><i>-aviraw track</i></b> : extracts avi track to its native format. <i>track</i> can be one of <i>video, audio, audioN</i> N being the number of the audio track.</p>
<p><b><i>-single TrackID</i></b> : extracts track in a new MP4 with a single track.</p>
<p><b><i>-saf</i></b> : remux input file to a SAF multiplex. This can also be used directly when encoding a LASeR content.</p>

<br/><br/>

<h1 id="coding">Scene Description Coding ("MP4Box -h dump" and "MP4Box -h encode" )</h1>
MP4Box can be used to encode and decode MPEG-4 Scene Description. It may also be used to convert to and from the various textual format: BT, XMT-A, WRL (VRML97), X3D in XML or VRML format, LASeR and SVG. These conversions will not always work since these standards do not use the same set of nodes.
<p><b><i>-mp4</i></b> : specifies input file is to be encoded. Supports .bt (BT), .xmt (XMT-A), .wrl (VRML97), .swf (Flash) and SVG/LASeR (.svg or .xsr) input. For more details on flash input, try <i>MP4Box -h swf</i>. For more details on BT/XMT-A, <a href="doc_bt.php">go here</a>.</p>
<p><b><i>-def</i></b> : encodes nodes and routes names, rather than just binary identifiers. This is usefull when developping content otherwise the decoded scene becomes quickly messy.</p>

<p><b><i>-log</i></b> : generates log file for BIFS encoder and for LASeR encoder/decoder. The log is only usefull to debug the scene codecs.</p>
<p><b><i>-ms</i></b> : specifies the media source to check for track importing. This is needed when no MuxInfo is present in the BT file, although this is not recommended. By default, MP4Box looks for tracks in MYFILE.mp4 when encoding MYFILE.bt</p>
<p><b><i>-bt</i></b> : dumps scene in a BT file.</p>
<p><b><i>-xmt</i></b> : dumps scene in an XMT-A file.</p>
<p><b><i>-wrl</i></b> : dumps scene into VRML97 format - unknown/incompatible nodes are removed.</p>

<p><b><i>-x3d</i></b> : dumps scene into X3D/XML format - unknown/incompatible nodes are removed.</p>
<p><b><i>-x3dv</i></b> : dumps scene into X3D/text format - - unknown/incompatible nodes are removed.</p>
<p><b><i>-lsr</i></b> : dumps scene in a LASeR+XML file.</p>
<p><b><i>-svg</i></b> : dumps LASeR scene root node to an SVG file.</p>
<br/>

<p><i>Note</i> : conversion from VRML-based scene graphs to/from SVG-based scene graphs is not supported.</p>
<br/>

<h2>LASeR encoding options</h2>
<p><b><i>-resolution res</i></b> : specifies the resolution to use when encoding points. Value ranges from -8 to 7, and all coordinates are multiplied by 2^res. The default resolution used is 0.</p>
<p><b><i>-coord-bits bits</i></b> : Number of bits used to encode a point coordinate. Default value is 12 bits.</p>
<p><b><i>-scale-bits bits</i></b> : Number of extra bits used to encode a scale factor (scale factor are therefore encoded on coord_bits+scale_bits). Default value is 0 bits.</p>

<p><b><i>-auto-quant res</i></b> : resolution is given as if using <i>-resolution</i> but coord-bits and scale-bits are computed dynamically. The default resolution used is 0.</p>
<br/><br/>

<h2>Scene Random Access</h2>
MP4Box can encode BIFS or LASeR streams and insert random access points at a given frequency. This is usefull when packaging content for broadcast, where users will not turn in the scene at the same time. In MPEG-4 terminology, this is called the scene carousel. 
<p><b><i>-carousel time</i></b> : inserts random access points at the desired frequency, specified in milliseconds. This cannot be used with the <i>-sync or -shadow</i> option.</p>

<p><b><i>-shadow time</i></b> : inserts random access points at the desired frequency, specified in milliseconds. This cannot be used with the <i>-sync or -carousel</i> option. The difference with <i>-carousel</i> is that random access samples can only be inserted as a substitution to existing samples, therefore their frequency is not guaranteed.</p>
<p><b><i>-sync time</i></b> : forces sync sample at the desired frequency by replacing the original sample. Time is specified in milliseconds. This cannot be used with the <i>-shadow or -carousel</i> option.</p>

<br/><br/>

<h1 id="ismacryp">ISMA Encryption and description ("MP4Box -h crypt")</h1>
MP4Box supports ISMA E&amp;A specification, better known as ISMACryp. In order to describe the cryptographic info, GPAC uses its own XML format documented <a href="doc_ismacryp.php">here</a>.
<p><b><i>-crypt drm_file</i></b> : encrypts IsoMedia file according to rules specified <i>drm_file</i>.</p>
<p><b><i>-decrypt drm_file</i></b> : decrypts IsoMedia file. <i>drm_file</i> is optional if the keys are stored within the file.</p>

<p><b><i>-set-kms [trackID=]kms_uri</i></b> : changes the URI of the key management system for the specified track, or for all tracks in the file if no trackID is given.</p>

<br/><br/>
<h1 id="meta">Meta ("MP4Box -h meta")</h1>
IsoMedia files can be used as generic meta-data containers, for examples storing XML information and sample images for a movie. These information can be stored at the file root level, as is the case for MPEG-21 file format, or at the moovie or track level for a regular movie. 

<p><b><i>-set-meta args</i></b> : assign the given type to the meta container (similar to file branding). Arguments syntax is <i>ABCD[:tk=ID]</i> where:
</p>
<ul>

<li><i>ABCD</i> : four char meta type (NULL or 0 to remove meta)</li>
<li><i>tk=ID</i> : if not set, use the root (file) meta is used. If ID is 0, the moovie (moov) meta is used, otherwise the given track meta is used</li>
</ul>
<p><b><i>-add-item args</i></b> : adds a file resource to the meta container. Arguments syntax is <i>file_path + options (':' separated)</i> with the following options:
</p>
<ul>

<li><i>tk=ID</i> : meta adressing (file, moov, track) - same as above.</li>
<li><i>name=str</i> : overrides the item name, otherwise the file name is used.</li>
<li><i>mime=mtype</i> : specifies the item mime type.</li>
<li><i>encoding=enctype</i> : specifies the item content-encoding type.</li>
</ul>

<p><i>Note</i> : a file_path of <b>this</b> or <b>self</b> means the item is the containing file itself.</p>
<p><b><i>-rem-item args</i></b> : removes the given resource from the meta container. Arguments syntax is <i>item_ID[:tk=ID]</i>.</p>
<p><b><i>-set-primary args</i></b> : sets the given item as primary item for the meta container. A primary item is the item used when no XML information is available in the meta container. Arguments syntax is <i>item_ID[:tk=ID]</i>.</p>

<p><b><i>-set-xml args</i></b> : sets XML data of the meta container. Arguments syntax is <i>xml_file_path[:tk=ID][:binary]</i>, where binary specifies that the XML is not in plain text.</p>
<p><b><i>-rem-xml [tk=ID]</i></b> : removes XML data from the meta container.</p>
<p><b><i>-dump-xml args</i></b> : dumps XML data of the meta container to a file. Arguments syntax is <i>output_file_path[:tk=ID]</i>.</p>
<p><b><i>-dump-item args</i></b> : dumps given item to file. Arguments syntax is <i>item_ID[:tk=ID][:path=fileName]</i>, where path is the output file name.</p>

<p><b><i>-package</i></b> : packages the input XML file into an ISO container. All local media referenced (except hyperlinks) are added to file (only 'href' and 'url' attributes are currently processsed). <b>THIS IS AN EXPERIMENTAL FEATURE NOT FULLY TESTED</b></p>

<br/><br/>
<h1 id="misc">Misc ("MP4Box -h")</h1>
<p><b><i>-nodes</i></b> : prints list of MPEG-4 nodes supported in this MP4Box build.</p>
<p><b><i>-node NodeName</i></b> : prints MPEG-4 node syntax: fields, their type, event type, default value and quantization info if any. Note this works only for nodes supported in the current built.</p>

<p><b><i>-xnodes</i></b> : prints list of X3D nodes supported in this MP4Box build.</p>
<p><b><i>-xnode NodeName</i></b> : prints X3D node syntax: fields, their type, event type and default value. Note this works only for nodes supported in the current built.</p>
<!-- <p><b><i>-snodes</i></b> : prints list of SVG nodes supported in this MP4Box build.</p> -->
<p><b><i>-snode NodeName</i></b> : prints possible attributes and properties of the SVG node. Note this works only for nodes supported in the current built.</p>
<p><b><i>-languages</i></b> : prints list of supported languages and their ISO 639 associated codes.</p>

		</div>
	</div>

<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>

