<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GPAC SVG Features - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="spacer"></div>
  <div id="fond">

  <?php include_once("nav.php"); ?>
  <!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
    <h1>This page describes the status of the implementation in the GPAC
    project of the Scalable Vector Graphics (SVG) language. It describes
    the features that are implemented and the roadmap for missing or new features.</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("play_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
    		<div id="sous_menu">
          <ul>
          <li><a href="gpac_svg_testsuite_status.xml">Test Suite Results</a></li>
          <li><a href="gpac_svg_support.xml">Detailled Status</a></li>
          <li><a href="#status">Status</a></li>
          <li><a href="#overview">Overview</a></li>
          </ul>
    		</div>
        <h1 id="overview">Overview</h1>        
        
        <p>The GPAC project includes support for the playback and rendering of SVG content. The goal is not to provide yet another mixed HTML/SVG browser (Opera or Firefox are very good at that) but to focus on the integration of multimedia description languages with audio/video data. The GPAC player will therefore remain in between a document browser and a traditional audio/video player with support for languages like BIFS, SVG, X3D ...</p>
        
        <p>For Windows, the player is available: in command line (MP4Client), with an MFC GUI (Osmo4), with a cross-platform GUI (wxOsmo4) or as plugins of Internet Explorer (GPAX), Firefox or Opera (Osmozilla). For Linux, MP4Client, wxOsmo4 and Osmozilla are also available. For PocketPC and smartphone, the player is called respectively OsmoCE and Osmophone, and GPAX is also available. Finally, MP4Client can also be used on Linux Familiar and Linux on Nokia 770. A player is also available for some Symbian platform.</p>
        
        <p>The binary installer for Windows is available <a href="http://tsi.enst.fr/~concolat/GPAC">here</a>. For Linux users, please download the source code, compile and install (debian packages are not yet provided).</p>
        
        <p>The implementation of the SVG support in GPAC is divided into 3 parts as follows:</p>
        <ul>        
          <li>SVG Parsing</li>
          <p>The GPAC framework reads SVG files (or streams) and builds the memory tree. It uses a (simple, limited, but functional) SAX parser. The parser can load, progressively or not, an SVG document into memory. If you are interested, the source code for this part is <a href="http://gpac.cvs.sourceforge.net/gpac/gpac/src/scene_manager/loader_svg_da.c?view=markup">here</a>.</p>
          
          <li>SVG Tree Managment</li>
          
          <p>This part of the SVG support is common in the GPAC implementation with the MPEG-4 BIFS tree managment, and is called in general Scene Graph Managment. The Scene Graph part is responsible for the creation of elements, the handling
          of attributes (parsing, dump, cloning ...). It also handles the animations and scripting features of SVG.<br>If you check out the source code, look for svg_nodes.h, scenegraph_svg.h, <a href="http://gpac.cvs.sourceforge.net/gpac/gpac/src/scenegraph/">src/scenegraph</a>/svg_*.*.</p>
          
          <li>SVG Compositing and Rendering</li>
          <p>The compositing and rendering operations consists in applying animations, triggering user interactions, rasterizing the vector graphics and producing the final image. The GPAC project has two rasterizers: GDI Plus on Windows
          platforms, and the GPAC 2D Rasterizer, based on FreeType.<br>If you check out the source code, look for <a href="http://gpac.cvs.sourceforge.net/gpac/gpac/src/compositor">src/compositor</a>/svg*.c. </p>
        </ul>

        <h1 id="status">Status</h1>
        
        <p>GPAC currently supports SVG Tiny 1.2. Some elements, supported at parsing, are not yet implemented in the rendering. Scripting support using MicroDOM is provided by the use of the SpiderMonkey engine. Detailled support for each element, attribute, property and script interfaces is available <a href="gpac_svg_support.xml">here</a>. </p> 
        
        <p>Obviously, the current implementation has some limitations, which we would like to remove and bugs we would like to fix. You can report bugs <a href="http://sourceforge.net/tracker/?group_id=84101&atid=571738">here</a> and ask for feature support <a href="http://sourceforge.net/tracker/?group_id=84101&atid=571741">there</a>.</p>
        
        <p>Results of the GPAC behavior on the SVG 1.2 Tiny Test Suite can be found <a href="gpac_svg_testsuite_status.xml">here</a>.</p>
    
            </div>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2008-08-06 10:22:27 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

