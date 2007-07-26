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
GPAC is an Open Source multimedia framework for research and academic purposes. 
<br/>The project covers different aspects of multimedia, 
          with a focus on presentation technologies (graphics, animation and interactivity).

		</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("home_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
				<h1>Overview</h1>
        <img src="img/ImgHome.gif" />
        <p>GPAC is cross-platform. It is written in (almost 100% ANSI) C for portability reasons 
          (embedded platforms and DSPs), attempting to keep the memory footprint as low as possible. 
          It is currently running under Windows, Linux, WindowsCE (SmartPhone, PocketPC 2002/2003), 
          Embedded Linux (familiar 8, GPE) and recent SymbianOS systems.</p>				
        <p>
          The project is intended to a wide audience ranging from end-users or 
          content creators with development skills who want to experiment the new 
          standards for interactive technologies or want to convert files for 
          mobile devices, to developers who need players and/or server for multimedia 
          streaming applications.    
        </p>
        <p>
          The GPAC framework is being developed at 
          <a href="http://www.enst.fr">ENST</a> 
          as part of research work on digital media.
        </p>      
        
        <h1>GPAC and Standards</h1>
				<p>GPAC can probably be seen as the most advanced and robust 2D MPEG-4 Player publicly available worldwide, as well as a decent 3D player. It also has some good support for X3D, W3C SVG Tiny 1.2, and 3GPP features. 3D support is available on embedded platforms through <a href="http://www.khronos.org/opengles/">OpenGL-ES</a>.</p>
				<p>GPAC also features MPEG-4 Systems encoders and multiplexers, publishing and content distribution tools for MP4 and 3GPP(2) files and many tools for scene descriptions (MPEG4/VRML/X3D converters, SWF/MPEG-4, etc...) For more information on supported tools, check out the <a href="feat.php">features</a> page.</p>
        <p>
          GPAC officially started as an open-source project  in 2003 with the initial goal to develop from scratch, 
          in ANSI C, clean software compliant to the MPEG-4 Systems standard, 
          a small and flexible alternative to the MPEG-4 reference software. 
          Since then, the project has evolved and features now three sets of tools based on a core library called libgpac: 
          <ul>
            <li>A multimedia player, called <a href="player.php">Osmo4</a>,</li> 
            <li>A multimedia packager, called <a href="packager.php">MP4Box</a>,</li> 
            <li>And some server tools.</li> 
          </ul>
        </p>


        <p>
          GPAC is a project under constant evolution. 
          We invite people, companies and universities interested in Rich Media 
          around the world to have a look at GPAC and bring in valuable <a href="home_support.php">help and 
          feedback</a>.
        </p>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2007-07-26 15:56:25 $"; ?><?php include_once("bas.php"); ?></div>
</body>
</html>

