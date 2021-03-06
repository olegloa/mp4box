<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Stream Language Selection Demo - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
This movie shows the power of MPEG-4 regarding media stream selection. With very few scripting you can dynamically switch languages or subtitles while 
ensuring a proper synchronization between the streams		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
<p>In this sequence, the user can switch between english and japanese sound tracks and french and spanish subtitles. The video
 material used is the introduction of "The Second Renaissance Part I" from the animatrix series, freely available <a href="http://www.intothematrix.com/">on their web site</a>.
 The content also features an animated, embedded skin providing media control (play/pause/stop/ffw) and a link to an internet web page.
</p>

<div style="text-align: center; width: 100%;">
<object type="application/x-gpac" height="240" width="320">
<param name="src" value="demos/Animatrix_The_Second_Renaissance_I.mp4"/>
</object>
</div>

<p>Note that the stream selection can happen when the main player is paused, because pausing only affects timelines, not interactions.</p>


<h1>Important features used in this content</h1>
<h2>Media Management</h2>
<ul>
<li>MediaControl
<br/>This node allows to pause and resume a media object from a given 
point in time, rather than from its begining. It may also be used to mute media composition.
<br/>In this example, the MediaControl controls the playback of the 
video stream, on which all other streams are synchronized..
</li>
<li>MediaSensor
<br/>This node is used to track the timeline of a media object. It may 
also be used to track switching of media segments (eg chapters).
<br/>In this example, the MediaSensor tracks the timeline of the video 
stream, on which all other streams are synchronized..
</li>

<li>AnimationStream
<br/>This node is used to control the playback of a media object 
carrying enhancement to the scene description, usually BIFS commands or BIFS animation. The enhancement are carried in a dedicated stream. This feature is very similar to animation sprites in games or other multimedia formats.
It may also be used to control a 3GPP text stream. 
<br/>In this example, the AnimationStream controls the playback of the 
subtitles.
</li>
</ul>


<h2>Scripting</h2>
<ul>
<li>Conditional
<br/>This node is used to exectute a set of BIFS commands (replace, 
delete, ...) upon a scene event (pointing device, time event). It allows fast and memory-efficient modification of the scene, without the overhead of a full scripting language.
<br/>In this example, the Conditional nodes are used to modify object 
appearances on mouse move.
</li>
<li>Script
<br/>This node is used to exectute complex scripting, typically 
ECMAScript. It gives more control on the scene compared to a conditional, and is very usefull to handle state variables.
<br/>In this example, the script is used to manage the stream selection 
state as well as the MediaControl node.

</li>
</ul>

<h2>User Interactivity</h2>
<ul>
<li>Anchor
<br/>This node is used to navigate between viewpoints inside the scene, 
to another scene or to an external, non-MPEG-4 ressource such as an HTML page. 
<br/>In this example, the anchor node is used to navigate to the home 
page of the Animatrix series.
</li>
<li>TouchSensor
<br/>This node is used enable pointing devices interactions with 
graphical elements of the scene, generating a set of simple (isOver, isActive) as well as complex (hitPoint, hitNormal, hitTexCoord) events.
</li>
<li>TimeSensor
<br/>This node is used to generate time events at each simultaion tick, 
as well as cycle events when the sensor loops.
<br/>In this example, the TimeSensor node drives the control bar 
animations.
</li>

<li>PositionInterpolator2D
<br/>This node is used to generate a SFVec2f value by linear 
interpolation between user defined values.
<br/>In this example, the PositionInterpolator2D node controls the 
translation and scaling of the control bar.
</li>
</ul>
	</div>
<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>
