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

        <img src="img/ImgHome.gif" alt="" />

        <p>GPAC provides three sets of tools based on a core library 
called libgpac:</p>
          <ul>
            <li>A multimedia player, called <a href="player.php">Osmo4</a>,</li> 
            <li>A multimedia packager, called <a href="packager.php">MP4Box</a>,</li> 
            <li>And some server tools (under development).</li> 
          </ul>
      

<!--
<p>GPAC features encoders and multiplexers, publishing and content distribution tools for MP4 and 3GPP(2) files and many tools for scene descriptions (BIFS/VRML/X3D converters, SWF/BIFS, SVG/BIFS, etc...). For more information on supported tools, check out the <a href="feat.php">features</a> page.</p>
-->
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
<p>
          GPAC officially started as an open-source project  in 2003 with the initial goal to develop from scratch, 
          in ANSI C, clean software compliant to the MPEG-4 Systems standard, 
          a small and flexible alternative to the MPEG-4 reference software. GPAC can probably be seen as the most advanced and robust 2D MPEG-4 Player publicly available worldwide, as well as a decent 3D player.
</p>

<p>In parallel, the project has evolved and now supports many other multimedia standards, with some good support for X3D, W3C SVG Tiny 1.2, and OMA/3GPP/ISMA features. 3D support is available on embedded platforms through <a href="http://www.khronos.org/opengles/">OpenGL-ES</a>.</p>


        <h1>People @ GPAC</h1>


<p>
The project is hosted at ENST (Ecole Nationale Supérieure des 
Télécommunications), leading French engineering school, located in Paris. 
Current main contributors of GPAC are:</p>
<ul>
<li>Jean Le Feuvre</li>
<li>Cyril Concolato</li>
</ul>

<p>Other (current or past) contributors from ENST are:</p>
<ul>
<li>Jean-Claude Moissinac</li>
<li>Benoît Pellan</li>
<li>Philippe de Cuetos</li>
</ul>


<p>
Additionally, GPAC is used at ENST for pedagogical purposes. Students regularly 
participate in the development of the project. The main students projects which 
have been contributed to GPAC are:</p>
<ul>
<li>Support for MPEG-2 transport stream (W. Ben Hania)</li>
<li>DVB-H simulator (A.-V. Bui, X. Liu, Y. Qiu, H. Chi)</li>
<li>Browser plugins for mozilla (osmozilla), IE (GPAX) (Y. Xi, X. Zhao)</li>
<li>Java integration (N. Nehme)</li>
<li>Experimental voice controler based on HTK (L. Laisné)</li>
<li>3D add-ons (M. Chahid and B. Anglaret)</li>
<li>Experimental authoring tool (J. Nitard)</li>
<li>BIFS Broadcaster (E. Boustani, E. Ghevre)</li>
</ul>

        <p>
          GPAC is a project under constant evolution. 
          We invite people, companies and universities interested in Rich Media 
          around the world to have a look at GPAC and bring in valuable <a href="home_support.php">help and 
          feedback</a>.
        </p>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

