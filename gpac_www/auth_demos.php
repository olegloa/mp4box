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
Some features of existing multimedia languages such as BIFS or SVG may be tricky to understand, especially when dealing with media control and interaction. These case studies are an attempt at clarifying the way they work. 
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
<h1>Stream Selection</h1>
<p>This movie shows the power of MPEG-4 regarding media stream selection. With very few scripting you can dynamically switch languages or subtitles while 
ensuring a proper synchronization between the streams. In this sequence, the user can switch between english and japanese sound tracks and french and spanish subtitles. The video
 material used is the introduction of "The Second Renaissance Part I" from the animatrix series, freely available <a href="http://www.intothematrix.com/">on their web site</a>.</p>
<p>The content also features an animated, embedded skin providing media control (play/pause/stop/ffw) and a link to an internet web page.</p>

<table border="0" cellpadding="5" cellspacing="5">
<tbody>

<tr>
	<td><img src="demos/srpo_1.jpg" alt="Animatrix Japanese/Spanish" 
height="240" width="320"/></td>
	<td><img src="demos/srpo_2.jpg" alt="Animatrix English/French" 
height="240" width="320"/></td>
</tr>
<tr>
	<td>Movie playing with japanese audio and spanish subtitles.</td>
	<td>Movie playing with english audio and french subtitles.</td>

</tr>
</tbody></table>

<b><a href="demos/Animatrix_The_Second_Renaissance_I.mp4">Download</a> this sample file or  View the sample <a href="auth_demos_srpo.php">embedded in a web page</a></b>.

<h1>Quality Selection</h1>
<p>This movie shows another usage of MPEG-4 stream selection, this time based on quality switching rather than language switching. In this movie you may choose two different qualities for audio and video, 
and still ensure a proper synchronization between the streams. The interesting aspect of this demonstration is its use of MPEG-4 Object Clock Reference stream, which acts as a virtual clock
 other streams synchronize to, rather than synchronizing media streams on eachother such as video on audio. Of course such a selection of streams would usually be made at the server side, but this
 nonetheless shows the ease and feasability of stream switching in MPEG-4.
</p>
<p>This content was designed by Mr Mollet from <a href="http://multimedialab.elis.ugent.be">UGent</a></p>

<table border="0" cellpadding="5" cellspacing="5">

<tbody>

<tr>
	<td><a href="demos/taken_high2.jpg"><img 
src="demos/taken_high2.jpg" alt="Taken High + Spanish" height="160" 
width="350"/></a></td>
	<td><a href="demos/taken_low2.jpg"><img 
src="demos/taken_low2.jpg" alt="Taken Low + English" height="160" width="350"/></a></td>
</tr>
<tr>
	<td>High quality version of both audio and video streams, with spanish subtitles.</td>
	<td>Low quality version of both audio and video streams, with english subtitles.</td>
</tr>
</tbody></table>

Also note that the navigation and subtitle stream switching, as well as the possibility to move the subtitles around the screen if needed.
<p>
<b><a href="demos/Taken_trailer.mp4">Download</a> this sample file or  View the sample <a href="auth_demos_taken.php">embedded in a web page</a></b>.
</p>

<!--

<h1>Multiple viewpoints in 3D scenes</h1>
<p>This movie shows usage of MPEG-4 3D layers, a powerfull tool which enables viewing a same scene or model from different viewpoints or in different environement, each with their own navigation parameters.
</p>
<p>This content is taken from GPAC regression tests</p>

<div style="text-align: center;">
<a href="demos/layers3D.jpg"><img src="demos/layers3D.jpg" alt="Layer 3D views" height="160" width="350"></a>
</div>

<p>
<b><a href="demos/layer3D.mp4">Download</a> this sample file or  View the sample <a href="auth_demos_layer3D.php">embedded in a web page</a></b>.
</p>


<br>

<h1>Virtual Reality Panoramic</h1>

<p>This movie shows a cubic QT-VR of NYC Time Square translated to MPEG-4. You may find other samples on <a href="http://www.apple.com/quicktime/gallery/cubicvr/">QuickTime Cubic VR gallery</a>.
</p>
<p>This content is taken from GPAC regression tests</p>

<div style="text-align: center;">
<a href="demos/TimesSquare.jpg"><img src="demos/TimesSquare.jpg" alt="TimeSquare Cubic VR" height="250" width="400"></a>
</div>

<p>
<b><a href="demos/TimeSquare.mp4">Download</a> this sample file or  View the sample <a href="auth_demos_qtvr.php">embedded in a web page</a></b>.

</p>


<h1>VRML/MPEG-4 World navigation</h1>
<p>This movie has you navigate through a castle model. It also exercices GPAC plugin scripting.</p>
<p>This model is taken from the great <a href="http://www.bbc.co.uk/history/">history pages at BBC</a></p>
<div style="text-align: center;">
<a href="screenshots/castle.jpg"><img src="screenshots/castle.jpg" alt="Virtual Castle Tour" height="300" width="400"></a>
</div>

<p>
<b><a href="demos/castle.mp4">Download</a> this sample file or  View the sample <a href="auth_demos_castle.php">embedded in a web page</a></b>.

</p>



<h1>MPEG-4 Game</h1>
<p>This is a classic bubble game done in MPEG-4 and ECMAScript.
</p>

<div style="text-align: center;">
<a href="demos/bubbles.jpg"><img src="demos/bubbles.jpg" alt="Bubble Game" height="200" width="340"></a>
</div>

<p>
<b><a href="demos/bubbles.mp4">Download</a> this sample file or  View the sample <a href="auth_demos_bubbles.php">embedded in a web page</a></b>.

</p>

-->

	</div>
<?php $mod_date="\$Date: 2007-08-30 13:19:18 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>
