<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GPAC Features - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="spacer"></div>
  <div id="fond">

<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
		GPAC covers different aspects of multimedia: A/V codecs, network protocols and synchronization management algorithms, scene representation tools, etc...
    Here is a brief summary of the technologies implemented in GPAC in the packaging, playing and streaming areas.     
		</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("home_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
	       <h1 id="MP4Box">Packaging Multimedia Content</h1>
<p>GPAC features encoders and multiplexers, publishing and content 
distribution tools for MP4 and 3GPP(2) files and many tools for scene 
descriptions (BIFS/VRML/X3D converters, SWF/BIFS, SVG/BIFS, etc...). 
MP4Box provides all these tools in a single command-line application. An 
exhaustive	list of packaging features is available <a href="packager.php">here</a> but a good summary of what MP4Box can do for you is the following:</p>
	<ul>
		<li>MP4/3GP Conversion from MP3, AVI, MEPG-2 TS, MPEG-PS, AAC, H263, H264, AMR, and many others,</li>
		<li>File layout: fragmentation or interleaving, and cleaning,</li>
		<li>File hinting for RTP/RTSP and QTSS/DSS servers (MPEG-4 / ISMA / 3GP / 3GP2 files),</li>
		<li>File splitting by size or time, extraction from file and file concatenation,</li>
		<li>XML information dumping for MP4 and RTP hint tracks,</li>
		<li>Media Track extractions,</li>
		<li>ISMA E&amp;A encryption and decryption,</li>
		<li>3GPP timed text tools (SUB/SRT/TTXT/TeXML), VobSub import/export,</li>
		<li>BIFS codec and scene conversion between MP4, BT and XMT-A,</li>
		<li>LASeR codec and scene conversion between  MP4, SAF, SVG and XSR (XML LASeR),</li>
		<li>XML scene statistics for BIFS scene (BT, XMT-A and MP4),</li>
		<li>Conversion to and from BT, XMT-A, WRL, X3D and X3DV with support for gzip.</li>
<!--
		<li>Conversion of simple Macromedia Flash (SWF) to MPEG-4 Systems (BT/XMT/MP4).</li>
-->
	</ul>
  
  	       <h1 id="Player">Playing Multimedia Content</h1>
	<p>GPAC supports many protocols and standards, among which: </p>
		<ul>
			<li>BIFS scenes (2D, 3D and mixed 2D/3D scenes),</li>
			<li>VRML 2.0 (VRML97) scenes (without GEO or NURBS extensions),</li>
			<li>X3D scenes (not complete) in X3D (XML) and X3DV (VRML) formats,</li>
			<li>SVG Tiny 1.2 scenes,</li>
			<li>LASeR and SAF (partial) support,</li>

			<li>Progressive loading/rendering of SVG, X3D and XMT files,</li>
			<li>HTTP reading of all scene descriptions,</li>
			<li>GZIP supported for all textual formats of MPEG4/X3D/VRML/SVG,</li>

			<li>MP4 and 3GPP file reading (local &amp; http),</li>
			<li>MP3 and AAC files (local &amp; http) and HTTP streaming (ShoutCast/ICECast radios),</li>
			<li>Most common media codecs for image, audio and video,</li>
			<li>Most common media containers,</li>

			<li>3GPP Timed Text / MPEG-4 Streaming Text,</li>
			<li>MPEG-2 TS demuxer (local/UDP/RTP) with DVB support (Linux only),</li>
			<li>Streaming support through RTP/RTCP (unicast and multicast) and RTSP/SDP,</li>

			<li>Plugins for Mozilla (Win32 and Linux) and Internet EXplorer (Win32 and PPC 2003).</li>
<!--
			<li>Simple SWF (Macromedia Flash) scenes (no ActionScript, no clipping, etc).</li>
			<li>Experimental streaming cache (recorder).</li>
			<li>Simple ISMA E&amp;A Decryption support.</li>
			<li>Multichannel audio, multichannel to stereo mapper.</li>
-->
		</ul>
		

	       <h1 id="Server">Streaming Multimedia Content</h1>
	<p>As of version 0.4.4, GPAC has some experimental server-side 
tools:</p>
		<ul>
			<li>MP4/3GP file RTP streamer (unicast and multicast),</li>
			<li>RTP streamer with service timeslicing (DVB-H) simulation,</li>
			<li>MPEG-2 TS broadcaster using MP4/3GP files or RTP streams as inputs,</li>
			<li>BIFS RTP broadcaster tool performing live encoding and RandomAccessPoints generation.</li>
		</ul>
	
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

