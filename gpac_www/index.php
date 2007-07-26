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
	<div id="Chapeau">
		<h1>
			Welcome to the GPAC Project on Advanced Content!<br/>
<!--	GPAC is a multimedia framework being developed at <a href="http://www.enst.fr">ENST</a> (Ecole Nationale Sup&eacute;rieure des T&eacute;l&eacute;communications) as part of research work on digital media and is distributed under the GNU Lesser General Public License. -->
      GPAC is an Open Source multimedia framework for research and academic purposes in different aspects of multimedia, with a focus on presentation technologies (graphics, animation and interactivity).
      <br/>
      <a href="home_about.php">Read more here.</a>
		</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("home_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
			  
<!--
        <h1>Summary</h1>
        <p>GPAC is an Open Source multimedia framework for research and academic purposes in different aspects of multimedia, 
          with a focus on presentation technologies (graphics, animation and interactivity).</p>
        <p>The project currently features three sets of tools:</p> 
        <ul>
            <li>A multimedia player, called <a href="player.php#Osmo4">Osmo4</a>,</li> 
            <li>A multimedia packager, called <a href="packager.php">MP4Box</a>,</li> 
            <li>And some server tools.</li> 
          </ul>
        <p><a href="home_about.php">Read more here.</a></p>
-->
        <h1>News</h1>
        <h2>June 20th 2007 - New web site</h2>
        <p>A brand new web site is available. </p>
        <h2>May 31st 2007 - GPAC Release 0.4.4</h2>
        <p>New features in this long-awaited release:
          <ul>
          <li>Initial Symbian support for GPAC</li>
          <li>Massive memory optimizations for the 2D renderer and SVG scenes</li>
          <li>Better LASeR support</li>
          
          <li>ALSA output module</li>
          <li>Linux DVB support</li>
          <li>JPEG-2000 support through OpenJPEG library</li>
          <li>Major improvements in the SAX parser</li>
          <li>Support for multiple RTSP session per SDP</li>
          <li>Global log system</li>
          <li>mp4_streamer, a simple uni/multicaster for MP4/3GP files, working without hint tracks. This tool van also be used to 
          simulate DVB-H service bursts</li>
          <li>mp42ts, a draft MPEG-2 TS multiplexer. The multiplexer only supports MPEG-2 Video, MPEG-1 video and MPEG-1 audio. It is capable of outputing to a file or to a rtp/udp port. The source programs can either be local ISO Media files or SDP files describing a uni/multicast already setup (no RTSP support).</li>
          <li>And of course the usual lot of bug fixes and other improvements ...</li>
          </ul>
        <p>

        <p>Check the complete <a href="http://gpac.cvs.sourceforge.net/*checkout*/gpac/gpac/Changelog">changelog</a>.</p>
        <p>Read the <a href="home_news.php"/>old news</a>.</p>

			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
	<div id="Bas">
		<p>(C) 2000-05 JLF / (C) 2005-0X ENST - $Id: index.php,v 1.4 2007-07-26 13:14:54 cconcolato Exp $ - <a href="mailto:jeanlf@users.sourceforge.net">Webmaster</a></p>
	</div>
</div>
</body>
</html>

