<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Authoring with GPAC - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
<p>This movie shows the power of MPEG-4 object constructing through PROTO construct for item display and ECMAScript for game management.
</p>

<div style="text-align: center; width: 100%;">
<object type="application/x-gpac" height="200" width="400">
<param name="src" value="demos/bubbles.mp4">
<param name="use3d" value="false">
</object>
</div>

<h1>Important features used in this content</h1>
<h2>Scene construct</h2>
<ul>
<li>PROTO
<br>The proto node is a template node made of other protos or native MPEG-4 objects. It may be used in the scene as any other node, greatly simplifying complex scene organization. 
<br>In this example, the proto node is used to modelize the bubbles appearance and interaction behaviour.
</li>
<li>Viewport
<br>This node is used to specify how to map a given content to a 2D rectangle, indicating aspect ratio and alignment constraints. This enables designing a scene in any given coordinate system and mapping it precisely to the final display.
</li>
</ul>


<h2>Scripting</h2>

<ul>
<li>Script
<br>This node is used to exectute complex scripting, typically ECMAScript. It gives more control on the scene compared to a conditional, and is very usefull to handle state variables.
<br>In this example, the script is used to manage the game state: bubbles gathering, discarding and appearance handling.
</li>
</ul>

<h2>User Interactivity</h2>
<ul>
<li>TouchSensor
<br>This node is used enable pointing devices interactions with graphical elements of the scene, generating a set of simple (isOver, isActive) as well as complex (hitPoint, hitNormal, hitTexCoord) events.
</li>
</ul>



	</div>
<?php $mod_date="\$Date: 2008-04-11 09:48:30 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>

