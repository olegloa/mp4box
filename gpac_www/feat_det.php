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
A true multimedia player is a complex system involving networking, media and user interactions management, rasterizing, etc. The following section is a more or less exhaustive list of supported features in the GPAC player.
		</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("play_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">

		<div id="sous_menu">
<ul>
<li><a href="#raster">Rasterizing</a></li>
<li><a href="#hardware">HAL</a></li>
<li><a href="#rendering">Rendering</a></li>
<li><a href="#network">Network</a></li>
<li><a href="#decoding">Media</a></li>
<li><a href="#stream">Stream Management</a></li>
<li><a href="#svg">SVG</a></li>
<li><a href="#vrml">BIFS/VRML</a></li>
<li><a href="#input">ISO Media</a></li>
</ul>
		</div>

		<h1 id="input">ISO Media File Format (MP4, 3GP and QT-based)</h1>
		<ul>
			<li>supports reading, writing/capturing, edition.</li>

			<li>highly flexible interleaving of media data.</li>
			<li>RTP hint tracks writing and reading.</li>
			<li>Movie Fragments: enables writing the file as a set of small meta and media data chunks (beta).</li>
			<li>3GPP media extensions (AMR/H263/EVRC/SMV/QCELP) and unknown media (QT) extensions.</li>
			<li>Some AVC/H264 extensions.</li>
			<li>3GPP timed text support.</li>

			<li>Meta packaging (a la MPEG-21), allowing embedding of files and XML data in the movie.</li>
			<li>Simple iTune tagging</li>
		</ul>
		
		<h1 id="vrml">VRML-Based Scene Graphs</h1>

		<ul>
			<li>Complete scene graphs for MPEG-4/VRML and X3D, configurable through included scene graph generators.</li>
			<li>All common interactivity types (events and routes, interpolators, conditionals, MPEG-4 valuators and X3D event filters).</li>
			<li>ECMAScript support through Mozilla SpiderMonkey engine.</li>
			<li>Full-feature PROTO and EXTERNPROTO support.</li>
			<li>MPEG-4 InputSensor (KeySensor, Mouse and StringSensor), X3D KeySensor and StringSensor support.</li>

			<li>SAX Progressive loading of XMT and X3D files (text and gziped).</li>
			<li>Scene Manager for authoring, textual dumping and importing.</li>
		</ul>
		
    <p>The status of VRML/BIFS implementation in GPAC can be <a href="feat_n_mpeg4.php">checked  here</a>.</p>
    
    <p>The status of X3D implementation in GPAC can be <a href="feat_n_x3d.php">checked  here</a>.</p>

		<h3>Hardcoded EXTERNPROTO nodes</h3>

 	<p>Couple of hardcoded EXTERNPROTO nodes are supported for the pleasure (text texturing node, 2D path extrusion node for 3D text &amp; co, etc). With hardcoded protos, you can add 
 	built-in nodes without breaking MPEG-4 bitstreams or modifing the scenegraphs.</p>

		<h1 id="svg">SVG-based Scene Graph</h1>

		<ul>

			<li>SVG 1.2 Tiny scene graph under develoment (see <a href="feat_n_svg.php">SVG implementation details</a>).</li>
			<li>Complete LASeR scene graph (subset of SVG Tiny 1.2).</li>
			<li>Some SVG 1.1 Full extensions.</li>
			<li>XML events and SMIL animation for SVG content.</li>
			<li>uDOM (MicroDOM) ECMAScript support through Mozilla SpiderMonkey engine.</li>
			<li>SAX Progressive loading of SVG files (text and gziped).</li>
			<li>Scene Manager for authoring, textual dumping and importing.</li>
		</ul>

		<h1 id="stream">Media Stream Management</h1>

		<ul>
			<li>Support for any scalable codecs (audio, video and systems), spatial and temporal salability supported (includes frame reordering).</li>
			<li>Synchronization, media management and load scheduling, support for arbitrary number of timelines.</li>
			<li>Support for streams not using MPEG-4 Sync Layer and timestamps.</li>
			<li>Pulled streams, pushed streams with buffer management.</li>
			<li>Time control and segment descriptors (media control, media sensor).</li>

			<li>Dynamic insertion/update/removal of objects and streams.</li>
			<li>Inline scene support (local or remote) with MediaControl/MediaSensor support.</li>
			<li>Support for non-MPEG4 URLs in nodes (URL "http://" or "rtsp://").</li>
			<li>Scene management abstracted through plugins (eg a new scenegraph and its renderer may be pluged at runtime into GPAC).</li>
			<li>Support for non-streamed scenes for VRML/X3D, SWF and SVG.</li>
			<li>Experimental generic media cache (recorder).</li>

		</ul>

  		<h2 id="mpeg_stream_specific">MPEG-4 Systems specifc tools</h2>
		<ul>
			<li>ObjectDescriptor codec.</li>
			<li>ObjectContentInformation codec.</li>
			<li>BIFS Command Decoder with quantization, proto, script and predictive MF Field decoding.</li>
			<li>BIFS Command Encoder with quantization, proto, script (no predictive MF Field).</li>

			<li>LASeR Decoder and Encoder with LASeRScript support.</li>
			<li>SAF (LASeR Simple Agregation Format) multiplexing.</li>
		</ul>

		<h1 id="rendering">Scene Renderer</h1>

			<h2>Audio rendering</h2>
			<ul>
				<li>multichannel support.</li>

				<li>integer up/down sampling with mediaSpeed handling.</li>
				<li>multichannel to stereo mapper.</li>
				<li>N-sources, M-channels software mixing.</li>
				<li>per-channel volume support.</li>
				<li>lip-sync management.</li>
			</ul>

			<h2>2D Renderer</h2>
		<ul>
			<li>Direct and indirect (tile engine) rendering.</li>
			<li>Full alpha support (including ColorTransform).</li>
			<li>Text drawing in vectorial mode or textured mode.</li>

			<li>Texturing, gradients, user interaction (and user interaction on composite textures).</li>
			<li>Hardware accelerated blit if available in output plugin.</li>
		</ul>
			<h2>3D Renderer</h2>
		<ul>

			<li>Graphics backend: OpenGL and OpenGL-ES.</li>
			<li>real alpha-blending (z-sorting), fast texturing when available (support for rectangular textures and non-power-of-2 textures), gradients, X3D RGBA colored meshes...</li>
			<li>frustum culling, ray-based node picking, decent collision detection through AABB tree, gravity, etc...</li>
			<li>navigation in main screen and in 3D layers, viewpoint selection, etc... </li>
			<li>Text drawing in vectorial mode or textured mode (eg, supports NICE text under OpenGL!).</li>
			<li>Supports all nodes supported by 2D renderer (except SVG nodes).</li>

		</ul>
		<br/>
	

<br/><br/>

<p>
The GPAC framework heavily relies on plugins for most tasks (stream input, file downloading, hardware IO, specialized renderers and rasterizer,	...).
<br/>Currently the following plugins are available:
</p>

		<h1 id="decoding">Media Decoders</h1>
		<ul>
			<li>PNG (libPNG) and JPEG (libJPEG).</li>
			<li>MPEG-4 Visual (Xvid), MPEG-4 AAC (faad2), MPEG-1/2 audio (mad).</li>

			<li>AMR speech codec using 3GPP fixed-point reference code.</li>
			<li>AMR and AMR-WB speech codecs using 3GPP float reference code.</li>
			<li>Xiph Media codecs: Vorbis and Theora.</li>
			<li>Generic decoder using FFMPEG avcodec library, supports most AV codecs known.</li>
			<li>3GPP timed text / MPEG-4 Streaming Text decoder (rendering done through GPAC renderers).</li>
		</ul>

		<h1 id="network">Network clients</h1>
		<ul>
			<li>File access from local drive or through HTTP download (using MIME-type associations).</li>
			<li>MP4, 3GP, MP3/Shoutcast, JPEG, PNG, OGG/Icecast, AMR/EVRC/SMV input plugins.</li>
			<li>AAC files and radio streams (icecast AAC-ADTS - needs latest faad2 cvs tarball) input plugin.</li>

			<li>Generic demuxer using FFMPEG avformat library, supports most AV containers known (MPEG, VOB, AVI, MOV ...).</li>
			<li>Subtitle reader (SRT/SUB/TeXML/TTXT formats).</li>
			<li>SAF reader.</li>
			<li>MP4 recorder output plugin (experimental).</li>
			<li>SDP input - RTP/RTSP streaming including RTP/UDP streaming, RTP over RTSP and HTTP tunneling of RTP traffic (QuickTime/Darwin Streaming Server). RTP Payload formats supported are:
  			<ul>
  			<li><a href="http://www.ietf.org/rfc/rfc3016.txt">RFC 3016</a> for MPEG-4 Simple Profile video and simple LATM AAC.</li>
  
  			<li><a href="http://www.ietf.org/rfc/rfc3640.txt">RFC 3640</a> for any form of MPEG-4 streams (audio, video, systems).</li>
  			<li><a href="http://www.ietf.org/rfc/rfc3267.txt">RFC 3267</a> for AMR audio (narrow-band, octet-align format only).</li>
  			<li><a href="http://www.ietf.org/rfc/rfc2250.txt">RFC 2250</a> for MPEG-1/2 audio and video.</li>
  			<li><a href="http://www.ietf.org/rfc/rfc2429.txt">RFC 2429</a> for H263 video used by 3GPP (no VRC, no extra Picture Header).</li>
  
  			<li><a href="http://www.ietf.org/rfc/rfc3984.txt">RFC 3984</a> for H264/AVC video (only STAP-A, FU-A and regular NAL units).</li>
  			</ul>
			</li>
		</ul>
		
		<h1 id="hardware">Hardware abstraction plugins</h1>

		<ul>
			<li>audio output: Microsoft DirectSound (with multichannel support), Microsoft WaveOut, Linux OSS, cross-platform SDL.</li>
			<li>video output: Microsoft DirectDraw (supports hardware YUV and RGB stretch), cross-platform SDL, Linux X11 with OpenGL and shared memory support.</li>
			<li>raw memory video output for BIFS testing.</li>
		</ul>
		<br/>
		

		<h1 id="raster">2D Rasterizer Plugins</h1>
		<ul>
			<li>Microsoft GDIplus (Windows 98 and later only).</li>
			<li>GPAC rasterizer, ANSI C, using FreeType Anti-Aliased raster module.</li>
		</ul>
		<h2>Font Handlers</h2>
		<ul>
			<li>Microsoft GDIplus for TrueType and OpenType font outline extraction.</li>
			<li>FreeType2 for TrueType font outline extraction.</li>
		</ul>

<br/>
				</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2007-10-12 14:23:11 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

