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
GPAC provides a highly configurable multimedia player available in many flavors (command-line, GUI and browser plugins).
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
The GPAC multimedia player is more than a traditional audiovisual player because, in addition to its capabilities to play most video or audio formats and its support for most of the existing delivery protocols, it focuses on graphics, animations and interactivity technologies. 
</p>
<p>
GPAC offers a unique integrated player capable of playing back audiovisual content mixed with 2D or 3D content in the following formats: MPEG-4 BIFS and LASeR, W3C SVG, W3D VRML and X3D. 
It is already widely used by academics and by industrials to display interactive 2D and/or 3D scenes.</p>

				<p>
The GPAC player is supported on Windows platforms (Osmo4/Osmophone on PocketPC) and all platforms with GCC, SDL 1.2 (and wxWidgets 2.5.2 for Osmo4).

				</p>
				<p>
The GPAC player is integrated with web browsers like Firefox, Opera (Osmozilla plugin - windows, linux) or Internet Explorer (GPAX ActiveX control - windows and windows mobile).
				</p>
        
        <h1 id="osmo">GPAC Player with GUI: Osmo4</h1>
        <img src="img/osmo4_gui.png" width="328" height="405" 
alt="GUI"/>
				<p>The GPAC project offers a very simple GUI for the control of the player. 
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
        Additionally, four menus (File, View, Play, and ?) allow respectively performing high-level operations (load a file or a URL, to view information on the current file), changing the viewing options (aspect ratio, navigation options), managing the playback and playlist, and viewing the shortcuts and information about the player.
        </p>
 
         
        <h2>Installation Check</h2>
        <img src="img/configuration_ok.png" width="558" height="565" 
alt="Configuration"/>
        <p>For the purpose of evaluating if the compilation and installation steps went well, the GPAC framework 
        provides a <a href="demos/configuration.mp4">test content</a> in the form of an MPEG-4 file containing an MPEG-4 BIFS stream 
        for text and vector graphics, a PNG image, an MPEG-4 video Part 2 stream and an MP3 stream. 
        If the installation step went well, you should see this result.</p>
        <img src="img/configuration_not_ok.png" width="558" height="565" 
alt="Partial Configuration"/>
        <p>The GPAC player may be used even if the compilation of the JavaScript engine, of the raster image decoder, of the MPEG-4 video decoder (ffmpeg or XviD), of the MP3 audio decoder (MAD) or of the GDIPlus or FreeType font engine went wrong, in such case, you may see, when playing configuration.mp4, a line showing that a particular support for a particular type of data is not correct.</p>
       
	<h1 id="config">GPAC Player Configuration</h1>

        <img src="img/osmo4_options.png" width="354" height="242" 
alt="Options"/>
				<p>GPAC is highly configurable. The player uses a configuration file shared among modules and reloadable at run time. Modules may use the configuration file as well (to avoid multiple config files). The documentation of the configuration file is available <a href="doc_config.php">here</a>.</p>
        <p>The View>Options menu of the GUI allows setting some parameters in the configuration (for 2D Rendering, 3D Rendering, XML parsing, media handling …).</p>
        <p>The GPAC player supports rendering of 2D graphics formats like SVG or BIFS and 3D graphics formats like VRML or X3D.</p> 
        <br/>
        <p><img src="img/osmo4_3dswitch.png" width="279" 
height="25" alt="3DSwitch"/>An important point to note is that the 
rendering engine 
used for 3D graphics is (currently) different from the 2D graphics rendering engine and the switch between engines is not automatic. Therefore, before playing 3D content, one needs to make sure the selector in the main tool bar is in the right position as shown.</p>
		</div>
	</div>

<?php $mod_date="\$Date: 2008-04-11 09:48:31 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>

