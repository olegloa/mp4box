<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Rich Media Player - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
			The GPAC Project includes many multimedia players. This page is dedicated to them. It describes how to check that the installation of the different players is correct.
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre_court">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("play_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">

		<div id="sous_menu">
<ul>
<li><a href="#config">GUI Config</a></li>
<li><a href="#osmo">Osmo4</a></li>
</ul>
		</div>

        <h1>Overview</h1>
				<p>
				The GPAC Players (Osmo4 and MP4Client) are supported on Windows platforms (Osmo4/Osmophone on PocketPC) and all platforms with GCC, SDL 1.2 (and wxWidgets 2.5.2 for Osmo4).
				</p>
				<p>
				Osmozilla (GPAC plugin for mozilla-based browsers) is supported on Windows (except PocketPCs) and Linux platforms. GPAX (GPAC ActiveX control, IE only) is supported on Windows and PocketPC (navigation not supported) platforms.
				</p>
        
        <h1 ID="osmo">GPAC Player with GUI: Osmo4</h1>
        <img src="img/osmo4_gui.png" width="328" height="405"/>
				<p>The GPAC project offers a very simple GUI for the control of the Players. 
        The interface is similar to the GUI of traditional audio-visual players. 
        Buttons allow (from left to right): </p>
        <ul>
          <li>opening a file,</li>
          <li>navigating to the previously opened file, </li> 
          <li>navigating to next file in the playlist, </li> 
          <li>playing the current file, </li> 
          <li>stepping into the playback of the current file, </li> 
          <li>stopping the current playback, </li> 
          <li>displaying information about the current file, </li> 
          <li>changing the settings of the player, </li> 
          <li>and finally switching the rendering from 2D to 3D and vice and versa. </li> 
        </ul>
        <p>The address bar allows typing the path or URL of the content to play. Finally, content may also be dragged and dropped on the player window.
        </p>
        <p>
        Additionally, four menus (File, View, Play, and ?) allow respectively performing high-level operations (load a file or a URL, to view information on the current file �), changing the viewing options (aspect ratio, navigation options), managing the playback and playlist, and viewing the shortcuts and information about the player.
        </p>
 
         
        <h2>Installation Check</h2>
        <img src="img/configuration_ok.png" width="558" height="565"/>
        <p>For the purpose of evaluating if the compilation and installation steps went well, the GPAC framework 
        provides a <a href="configuration.mp4">test content</a> in the form of an MPEG-4 file containing an MPEG-4 BIFS stream 
        for text and vector graphics, a PNG image, an MPEG-4 video Part 2 stream and an MP3 stream. 
        If the installation step went well, you should see this result.</p>
        <img src="img/configuration_not_ok.png" width="558" height="565"/>
        <p>GPAC Players may be used even if the compilation of the JavaScript engine, of the raster image decoder, of the MPEG-4 video decoder (ffmpeg or XviD), of the MP3 audio decoder (MAD) or of the GDIPlus or FreeType font engine went wrong, in such case, you may see, when playing configuration.mp4, a line showing that a particular support for a particular type of data is not correct.</p>
         
	<h1 ID="config">GPAC Player Configuration</h1>

        <img src="img/osmo4_options.png" width="354" height="242"/>
				<p>GPAC is highly configurable. The players use a configuration file shared among modules and reloadable at run time. Modules may use the configuration file as well (to avoid multiple config files). The documentation of the configuration file is available <a href="doc_config.php">here</a>.</p>
        <p>The View>Options menu of the GUI allows setting some parameters in the configuration (for 2D Rendering, 3D Rendering, XML parsing, media handling �).</p>
        <p>The GPAC player supports rendering of 2D graphics formats like SVG or BIFS and 3D graphics formats like VRML or X3D.</p> 
        <br/>
        <p><img src="img/osmo4_3dswitch.png" width="279" height="25"/>An important point to note is that the rendering engine used for 3D graphics is (currently) different from the 2D graphics rendering engine and the switch between engines is not automatic. Therefore, before playing 3D content, one needs to make sure the selector in the main tool bar is in the right position as shown.</p>
		</div>
	</div>

<?php $mod_date="\$Date: 2007-07-26 13:43:29 $"; ?><?php include_once("bas.php"); ?><!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>

