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
        <h2>December 2008 - GPAC 0.4.5 released</a></h2>
        <p>Many new features in this new release:</p>
          <ul>
          <li>Massive improvements of the SVG support - checkout the <a href="http://dev.w3.org/cvsweb/~checkout~/SVG/profiles/1.2T/test/SVGT12-ImpReport.html?rev=1.7&content-type=text/html;%20charset=iso-8859-1">W3C SVG Implementation Report</a></li>
          <li>Integrated 2D/3D renderer with support for mixed 2D/3D drawing (documents mixing BIFS, SVG, VRML/X3D)</li>
          <li>Support for 3GPP DIMS (hinting, streaming and file playback).</li>
          <li>Support for AC3 muxing in ISO Media and AC3 decoding in GPAC</li>
          <li>Added support for MPEG-4 over MPEG-2 systems (T-DMB)</li>
          <li>Improvements on Symbian version</li> 
          <li>And many small fixes and improvements in MP4Box and GPAC clients</li>
          </ul>
        <h2>September 2008 - GPAC will be demonstrated at <a href="http://www.icmc.usp.br/~doceng08/">the ACM Document Engineering Conference</a></h2>
        <p>GPAC players are now capable of playing mixed documents: documents in the SVG language containing links to MPEG-4 or X3D content and vice-versa. Details of the demonstrations will be available on the ACM portal.</p>
        <h2>July 2008 - Meet the GPAC team at <a href="http://mpeg.tnt.uni-hannover.de/">the 85th MPEG meeting</a></h2>
        <p>The team participates to the Systems activities (File Format, Transport, MPEG-4 BIFS, MPEG-4 LASeR and MPEG User Interface Framework).</p>
        <h2>May 2008 - Implementation of SVG support in GPAC is published</h2>
        <p>The underlying principles and algorithms of the SVG support in GPAC players is now published in the journal <a href="http://ieeexplore.ieee.org/iel5/30/4560070/04560176.pdf?tp=&isnumber=4560070&arnumber=4560176">IEEE Transactions on Consumer Electronics</a></p>
        <h2>September 2007 - GPAC team is at <a href="http://mmc36.informatik.uni-augsburg.de/acmmm2007/">ACM MM 2007</a></h2>
        <p>A presentation of GPAC will take place on thursday afternoon - don't miss it!.</p>
        <h2>July 26th 2007 - New web site</h2>
        <p>A brand new web site is available. </p>
        <h2>May 31st 2007 - GPAC Release 0.4.4</h2>
        <p>New features in this long-awaited release:</p>
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
        

        <p>Check the complete <a href="http://gpac.svn.sourceforge.net/viewvc/gpac/?view=log">changelog</a>.</p>
        <p>Read the <a href="home_news.php">old news</a>.</p>

			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2008-12-02 19:02:17 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

