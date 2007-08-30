<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Stream Quality Selection Demo - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
This movie shows another usage of MPEG-4 stream selection, this time based on quality switching rather than language switching
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
<p>In this movie you may choose two different qualities for audio and video, 
and still ensure a proper synchronization between the streams. The interesting aspect of this demonstration is its use of MPEG-4 Object Clock Reference stream, which acts as a virtual clock
 other streams synchronize to, rather than synchronizing media streams on eachother such as video on audio. Of course such a selection of streams would usually be made at the server side, but this
 nonetheless shows the ease and feasability of stream switching in MPEG-4.
</p>
<p>
This movie also features chapter navigation and subtitle stream switching, as well as the possibility to move the subtitles around the screen if needed.
</p>

<div style="text-align: center; width: 100%;">
<object type="application/x-gpac" height="320" width="700">
<param name="src" value="demos/Taken_trailer.mp4"/>
</object>
</div>

<p>This content was designed by Mr Mollet from <a href="http://multimedialab.elis.ugent.be">UGent</a></p>

<h1>Important features used in this content</h1>
<h2>Media Management</h2>
<ul>
<li>Object Clock Reference Stream
<br/>This special MPEG-4 stream acts as a virtual timebase, on which any 
stream may synchronize. The main applications are broadcast environment and media selection applications.
<br/>In this example, the OCR streams acts as the timebase of currently 
playing media objects, ensuring a proper synchronization between audio and video, regardless of the media streams chosen.
</li>
<li>MediaControl
<br/>This node allows to pause and resume a media object from a given 
point in time, rather than from its begining. It may also be used to mute media composition.
<br/>In this example, the MediaControl controls the playback of the OCR 
stream, on which all other streams are synchronized..

</li>
<li>MediaSensor
<br/>This node is used to track the timeline of a media object. It may 
also be used to track switching of media segments (eg chapters).
<br/>In this example, the MediaSensor tracks the timeline of the OCR 
stream, on which all other streams are synchronized..
</li>
<li>AnimationStream
<br/>This node is used to control the playback of a media object 
carrying enhancement to the scene description, usually BIFS commands or BIFS animation. The enhancement are carried in a dedicated stream. This feature is very similar to animation sprites in games or other multimedia formats. It may also be used to control a 3GPP text stream. 
<br/>In this example, the AnimationStream controls the playback of the 
subtitles.
</li>
</ul>

<h2>Scripting</h2>
<ul>
<li>Conditional
<br/>This node is used to exectute a set of BIFS commands (replace, 
delete, ...) upon a scene event (pointing device, time event). It allows fast and memory-efficient modification of the scene, without the overhead of a full scripting language.
<br/>In this example, the Conditional nodes are used to trigger chapter 
selection upon mouse clicks.

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
<br/>In this example, the anchor node is used to navigate to the UGent 
home page or to send a mail to the author.
</li>
<li>TouchSensor
<br/>This node is used enable pointing devices interactions with 
graphical elements of the scene, generating a set of simple (isOver, isActive) as well as complex (hitPoint, hitNormal, hitTexCoord) events.
</li>

</ul>

<h2>Scene Constructs</h2>
<ul>
<li>Proto
<br/>The proto node is a template node made of other protos or native 
MPEG-4 objects. It may be used in the scene as any other node, greatly simplifying complex scene organization. 
<br/>In this example, the proto node is used to modelize the buttons' 
appearance and interaction behaviour.
</li>
<li>Form and Layout
<br/>These two nodes provide automatic element layout functionalities, 
either through explicit alignment rules (Form) or line/column fitting algorithm (Layout).
<br/>In this example, these nodes are used to layout the interaction 
buttons, the movie and the movie control bar.
</li>
</ul>



	</div>
<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>
