<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MP4BOX Overview - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
			This section is dedicated to multimedia content manipulation and packaging using MP4Box, the GPAC swiss army knife.
		</h1>

	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre_court">
      <?php include_once("pack_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
	
<div id="sous_menu">
<ul>
<li><a href="#cont_trans">Scene Transcoding</a></li>
<li><a href="#cont_deli">Delivery Setup</a></li>
<li><a href="#cont_pack">Content Packaging</a></li>
</ul>
</div>
	  <h1>Overview</h1>
		<p>The multimedia packager available in GPAC is called MP4Box.
      It can be used for performing many manipulations on multimedia files like AVI, MPG, TS, but mostly on ISO media files (e.g. MP4, 3GP).</p> 
    <p>A complete documentation of this tool is available <a href="doc_mp4box.php">here</a>. 
    <p>In short, it can be used:</p> 
    <ul>
      <li>for encoding/decoding presentation languages like MPEG-4 XMT or W3C SVG into/from binary formats like MPEG-4 BIFS or LASeR,</li>
      <li>for manipulating ISO files like MP4, 3GP: adding, removing, multiplexing audio, video and presentation data (including subtitles) from different sources and in different formats,</li> 
      <li>for performing encryption of streams</li>
      <li>for attaching metadata to individual streams or to the whole ISO file to produce MPEG-21 compliant or hybrid MPEG-4/MPEG-21 files</li>
      <li>and packaging and tagging the result for streaming, download and playback on different devices (e.g. phones, PDA) or for different software (e.g. iTunes).</li>
    </ul>
    <p>It is widely used: by academics, by the <a href="www.doom9.org">video community</a> and is noticeably used in <a href="http://video.google.com/">Google Video services</a> 
    for preparation of multimedia files for playback on iPod and PlayStation.</p> 
    </p>

    <h1 ID="cont_pack">Content Packaging</h1>
    <p>MP4Box can be used to repackage existing content to compliant ISO Media Files (MP4, 3GP, 3G2, OMA DCF). Note however that MP4Box does NOT re-encode audio, video and still image content, external tools shall be used for this purpose.</p>
    <ul>
    <li>Transforming a DivX file to an MP4 file:
    <pre>MP4Box -add file.avi new_file.mp4</pre>
    </li>
    <li>Adding a secondary audio track to the previous file:
    <pre>MP4Box -add audio2.mp3 new_file.mp4</pre>
    </li>
    <li>MP4Box can import only parts or specific media from
an existing container. To get the supported media that
can be imported from a container:
    <pre>MP4Box -info file.avi
MP4Box -info file.mpg
MP4Box -info file.ts</pre>
    </li>
    <li>To add a single media from a container:
    <pre>MP4Box -add file.mpg#audio new_file.mp4</pre>
    </li>
    </ul>

    <h1 ID="cont_deli">Delivery Setup</h1>
    <p>MP4Box can be used to prepare files for different delivery protocols, mainly HTTP downloading or RTP streaming.</p>
    <ul>
    <li>To prepare a file for HTTP download, the following
instruction will interleave file data by chunks of 500
milliseconds in order to enable playback while
downloading the file (HTTP FastStart):
    <pre>MP4Box -inter 500 file.mp4</pre>
    </li>
    <li>To prepare for RTP, the following instruction will
create RTP hint tracks for the file. This enables classic
streaming servers like DarwinStreamingServer or
QuickTime Streaming Server to deliver the file through
RTSP/RTP:
    <pre>MP4Box -hint file.mp4</pre>
    </li>
    </ul>

    <h1 ID="cont_trans">Scene Transcoding</h1>
    <p>MP4Box can be used to encode MPEG-4 scene descriptions BIFS
and LASeR and to decode MPEG-4 scene descriptions BIFS and
LASeR.</p>
    <ul>
    <li>To encode an existing description:
    <pre>MP4Box -mp4 scene.bt
MP4Box -mp4 scene.xmt
MP4Box -mp4 scene.wrl
MP4Box -mp4 file.svg</pre>
<p>Note that MP4Box will do its best to encode VRML/X3D to
MPEG-4, but that not all tools from X3D or VRML extensions
are supported in MPEG-4.</p>
    </li>
    <li>To decode an existing BIFS track to a BIFS Text format
(VRML-like format)description:
    <pre>MP4Box -bt file.mp4</pre>
    </li>
    <li>To decode an existing BIFS track to XMT-A format:
    <pre>MP4Box -xmt file.mp4</pre>
    </li>
    <li>To decode an existing LASeR track to an XSR format
(SAF+LASeR Markup Language) description:
    <pre>MP4Box -lsr file.mp4</pre>
    </li>
    <li>To decode the first sample of an existing LASeR track
to an SVG file:
    <pre>MP4Box -svg file.mp4</pre>
    </li>
    </ul>
    </div>
	</div>

<?php $mod_date="\$Date: 2007-07-26 13:43:29 $"; ?><?php include_once("bas.php"); ?><!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>

