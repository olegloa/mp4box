<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Home - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="spacer"></div>
  <div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
		News Archive
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
			<div class="Col1">
				<h2><a href="releases.php">???</a></h2>
			</div>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
<h2>July 22nd 2006 - GPAC Release 0.4.2</h2>
<p>
This release has a lot of new features, among which:
<ul>
<li>GPAC plugins for both Mozilla and Internet Explorer browsers - look <a href="doc_embed.php">here</a> for more details</li>
<li>Support for SmartPhone 2003 and PocketPC 2003 platforms, with experimental 3D support through OpenGL ES</li>
<li>Many SVG improvements (gradients, SVG 1.2 uDOM scripting, DOM events, etc.)</li>
<li>Draft MPEG-4 LASeR support - <b>WARNING</b> the LASeR standard is still under editing, INCLUDING bitstream syntax. This means that LASeR support in GPAC is completely experimental, encoded scenes will be broken in next release.</li>
<li>MP4Client now supports screenshots/raw avi extraction from any supported file (MP42AVI is now deprecated)</li>
<li>New media import/export XML-based format NHML extending NHNT capabilities</li>
<li>Configurable progressive rendering of all XML-based scene descriptions</li>
<li>Initial IPV6 support</li>
<li>MPEG-2 TS support (demultiplexer only) - this feature is under heavy development at the current time, this is more a "preview" than anything else, but it should be quite stable in MP4Box.</li>
<li>Support for VobSub subtitles in MP4Box (mux/demux) thanks to a great patch from Falco</li>
<li>MPEG-1/2 raw video import</li>
<li>Simple iTune tagging for MP4Box thanks to a patch from Andrew Voznytsa</li>
<li>And of course the usual lot of bug fixes and other improvements ...</li>
</ul>
</p>
<h2>August 3rd 2005 - GPAC Release 0.4.0</h2>
<p>
GPAC is from now on licensed under LGPL. This release is mainly a major API rewrite of the core library, now called libgpac. All APIs are no longer backward 
compatible, but most of them should now be in a frozen state for GPAC's lifetime. Note that due to massive code rewrite this release is not considered as stable as 0.3.0, although no serious issues have been identified. 
Apart from the license change and API redesign, GPAC 0.4.0 also features some interesting things:
<ul>
<li>Support for anamorphic video (MPEG-4 Visual importer and all decoders supporting it)</li>
<li>X11 video output support with integration in Osmo4/wxWidgets</li>
<li>OSS audio output support has been updated, GPAC can now be used without SDL at all on Linux.</li>
<li>Stable version of osmozilla, the GPAC plugin for Mozilla-based browsers, tested under Win32 and linux. This plugin is in its early development stage, the scripting API spec has not been produced yet (only Play, Pause and Reload methods are currently available for testing). Any scripting request from GPAC users for this plugin are more than welcome.</li>
<li>LATM RTP depacketizer support for GPAC clients.</li>
<li>Various fixes and updates in MP4Box (media language, ttxt and srt importing, track-based importing options).</li>
</ul>
This release also marks the start of the gpac core documentation which is under writing. You may have a look at it by generating it with <a href="http://www.doxygen.org">doxygen</a> (cf GPAC install documentation).
</p>
<h2>June 20th 2005 - GPAC Release 0.3.0</h2>
<p>
GPAC is officially back at ENST, hence this new numbering jump. The most important new feature is the support of fixed-point in the whole framework, 
with successfull testing on WinCE/PocketPC 2002 devices. But there are also many new things in MP4Box:
<ul>
<li>IsoMedia file splitting by size or duration, and support for chunk extraction.</li>
<li>File concatenation for all supported media types (eg, join directly a set of AVIs to a single MP4).</li>
<li>LATM RTP hinter for AAC.</li>
<li>Chapter info (Nero-like), media language and media delay tools.</li>
<li>Much more reliable AVC/H264 importer</li>
</ul>
Regarding GPAC client core, many optimisations have been done while intergating fixed-point, especially on 2D path objects which are no longer represented as flattened (thanks to freetype). 
For the rest, let's just quote:
<ul>
<li>wxOsmo4 now builds with wxWidgets+unicode</li>
<li>more efficient SVG renderer</li>
<li>And still many fixes here, there and everywhere...</li>
</ul>
</p>
<h2>March 30th 2005 - GPAC Release 0.2.4</h2>
<p>
Many new things on the authoring side in this release:
<ul>
<li>lifting of MP4Box (nicer help screens, progress reports and options cleaning). MP4Box usage is slightly different, make sure <a href="doc_mp4box.php">you read this</a> first !!</li>
<li>3GPP2 tools (importers/exporters/hinters for EVRC, QCELP and SMV codecs).</li>
<li>Support for ISMA E&amp;A (ISMACryp specification) in MP4Box including hinting, and some support in players.</li>
<li>More AVC/H264 (raw importer, exporter, hinter) and AVC B-slice support through ffmpeg.</li>
<li>More 3GPP/MPEG-4 text support (SUB subtitles and QT TeXML import, track extraction to SRT).</li>
<li>Many fixes in MP4Box hinters (AMR, MPA bandwidth signaling) and enhancement (RTP aggregation for non-MPEG-4 payloads).</li>
<li>Support for AMR and AMR-WB with 3GPP floating-point code in players (much faster).</li>
<li>H263 and raw MPEG-4 video (CMP/M4V) importers.</li>
<li>Experimental streaming cache in osmo4 (w32 and linux) for media recording (and I do mean experimental...)</li>
<li>Many, many, many fixes here, there and everywhere...</li>
</ul>
And for the first time in a year or so, Osmo4 on PocketPC (2D only) has been stabilized and is much more usable!
</p>
<h2>January 5th 2005 - GPAC Release 0.2.3</h2>
<p>
Many new things in this release:
<ul>
<li>AAC-ADTS format (including internet radios), HE-AAC/AAC-SBR/aacPlus support, AMR raw format</li>
<li>3GPP timed text (encoding/decoding/playback) and streaming (RTP packetizer and reassembler). Added SRT to 3GP text conversion.</li>
<li>AVC/H264 through ffmpeg along with YAIFDA (Yet Another Improved FFmpeg Demuxer Announcement).</li>
<li>Playlist and browser-like navigation in Osmo4 (w32 and wx)</li>
<li>MP4Box improvements:
<ul>
<li>MPEG-1/2 PS to MP4 and MPEG 1/2 video hinters (thanks to MPEG4IP)</li>
<li>Easy Multitrack MP4/3GP creation</li>
<li>3GP timed text tools (conversion, extraction)</li>
<li>AAC-ADTS and raw AMR support.</li>
</ul></li>
<li>More X3D support and 3D fixes</li>
<li>Reworked multichannel audio mixer, including multichannel-&gt;stereo converter</li>
<li>Stream selection (when possible) in Osmo4</li>
<li>PocketPC version should be usable (not entirely tested though :)</li>
</ul>
The GPAC Regression Tests have been updated (mainly X3D tests) and are available for download.
</p>
<h2>November 9th 2004 - GPAC Release 0.2.2</h2>
<p>or GPAC after a lifting... Many architectural changes since last release (GPAC core is now a shared library) but also new features:
<ul>
<li>Support for OGG format (including icecast streams), Vorbis and Theora codecs</li>
<li>Support for Shoutcast streams </li>
<li>Improved ffmpeg demuxer.</li>
<li>GPAC now relies on mime types for file associations.</li>
<li>X3D integration (both XML and VRML formats)</li>
<li>More VRML/X3D support: GZip'ed worlds, multiple URL, viewpoint addressing in URLs and Anchor</li>
<li>Decoding and network stats in all GUIs</li>
</ul>
The LGPL libm4isomedia has also been updated and is available for download.
</p>
<h2>October 15th 2004 - GPAC Release 0.2.1</h2>
<p>
GPAC is on the road to nowhere - Come on inside. Many new things in this release:
<ul>
<li>Stable 3D renderer supporting all common nodes between MPEG-4 and VRML 97. Overview:
<ul>
<li>Many 2D &amp; 3D graphic primitives (and extruded text through hardcoded protos :)</li>
<li>MPEG-4 special nodes: Layer2D, Layer3D, CompositeTexture2D and CompositeTexture3D</li>
<li>Lighting, texture mapping, viewpoints &amp; navigation</li>
<li>User interactions supported in main scene, layers and composite textures</li>
<li>User navigation supported in main scene and layer3D</li>
<li>Collision detection &amp; gravity - still buggy, you may walk through walls or get stuck on the ground like "Le Passe Muraille"...</li>
</ul></li>
<li>Support for BT/XMTA/WRL and basic SWF in the players.</li>
<li>Good VRML support, including the powerfull CreateVrmlFromString (but no compressed wrl support).</li>
<li>Much more stable SDL/Linux support.</li>
<li>Faster and more reliable javascript support.</li>
<li>MP4Box now supports &gt;2GB files (avi importer should also).</li>
</ul>
</p>
<h2>September 3rd 2004 - GPAC Release 0.2.0</h2>
<p>After several months of chaotic development, GPAC leaves the 2D world! Many new things in this release:
<ul>
<li>Rendering plugins for 2D (stable) and 2D/3D via OpenGL (quite basic, under development). The 3D renderer supports:
<ul>
<li>All 2D primitives, Box/Cone/Cylinder/Sphere/IndexedFaceSet/IndexedLineSet, texture mapping and material</li>
<li>Frustum culling, basic viewpoint handling.</li>
<li>No lights (except headlight), no user interactions.</li>
</ul></li>
<li>Osmo4 now available under linux through wxWidgets 2.5.2</li>
<li>MP4Box: stable avi B-Frame parser (packed and unpacked bitstreams), support for UTF-16 BT and XMT documents, simple Flash (.swf) to BT/XMT/MP4 converter.</li>
</p>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
<?php $mod_date="\$Date: 2008-04-11 09:48:30 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>
