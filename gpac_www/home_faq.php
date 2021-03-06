<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GPAC FAQ - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="spacer"></div>
  <div id="fond">

<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
		Frequently Asked Questions.
		</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
			<div class="Col1">
        <h2 class="FAQ"><a href="#GPAC" >Why 'GPAC'?</a></h2>
        <h2 class="FAQ"><a href="#ADV" >What is 'Advanced Content' ?</a></h2>
        <h2 class="FAQ"><a href="#MPEG4" >Why another MPEG-4 movie player ?</a></h2>
        <h2 class="FAQ"><a href="#MOVIES" >Does GPAC support AVI, MOV, MPEG1/2 files or DVDs ?</a></h2>
        <h2 class="FAQ"><a href="#ADV2" >What about support for VRML/X3D, SVG/SMIL or Macromedia Flash ?</a></h2>
        <h2 class="FAQ"><a href="#Osmo4" >I heard you speak about Osmo4. What is that ?</a></h2>
        <h2 class="FAQ"><a href="#Platforms" >Which platforms does GPAC support ?</a></h2>
        <h2 class="FAQ"><a href="#MP4BoxGUI" >Will a GUI version of MP4Box ever be developed ?</a></h2>
        <h2 class="FAQ"><a href="#FAQS" >Why is ... ? Can I ... ? Will GPAC ... ?</a></h2>
			</div>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
	       <h1 id="GPAC">Why 'GPAC' ?</h1>
	        <p>Because the world is round it turns me on. Because the world is round...</p>
	       <h1 id="ADV">What is 'Advanced Content' ?</h1>
	<p>
  The 'official' term would rather be 'Rich Media' and you may spend some time on the web looking for a
	good definition of that term... To summarize, 'rich media' usually means multimedia content with local and client-server
	interactivity, natural audio and video (ex MPEG-1 and MPEG-2) mixed with synthetic audio (MIDI, MPEG-4 Structured
	Audio), 2D and 3D graphics, web content, etc. ... Add a bit of content protection and description,
	some intelligence (scripts, Java), distribute it as a whole or fragmented over several networks and you're
	close to it (at least on the client side aspects of the topic...).<br/>
	By 'Advanced Content', consider more the fact that we're using mostly primitive media content nowadays, appart from Flash and DVD, and that it's time for you to investigate a bit more some Great, Powerfull, Appealing Content...
	</p>
	       <h1 id="MPEG4">Why another MPEG-4 movie player?</h1>
  <p>
	To make a long answer short, the first thing you need to know is that your so-called MPEG-4 movie player only plays what you think 
	a movie is, that is a video and a synchronized audio (sometimes selectable) and maybe a subtitle. And this is the most primitive form of MPEG-4 movies. 
	<br/>
	The second thing you need to know, you will soon learn by browsing this web site.
	</p>

	       <h1 id="MOVIES">Does GPAC support AVI, MOV, MPEG1/2 files or DVDs?</h1>
  <p>
	DVD is not on the todo list, there are plenty of nice and efficient DVD players out there... For the rest and since version 0.1.2, GPAC supports FFMPEG libraries 
	for demuxing and decoding, so should play most AV file formats known.
	</p>

	       <h1 id="ADV2">What about support for VRML/X3D, SVG/SMIL or Macromedia Flash?</h1>
<ul>
<li>VRML 2.0 (VRML97) is supported (without NURBS/GEO extensions), X3D support is already well advanced.</li>
<li>Flash is not natively supported, however	a simple SWF to MPEG-4 converter is already available in GPAC (for encoding or playback).</li>
<li>GPAC partially supports SVG 1.1 Mobile and SVG 1.2 tiny with SMIL animation.</li>
<li>Complete SMIL suppport is still not considered.</li>
</ul>

	       <h1 id="Osmo4">I heard you speak about Osmo4. What is that?</h1>
<p>
	Osmo4 is a 2D MPEG-4 player for the Windows platform developed at ENST by your servitor during the year 2002-2003, based
	on the MPEG-4 Systems reference Software (a.k.a. IM1). The resulting player has been distributed by ENST under
	the name Osmo4 and started versioning at 1.0. Meanwhile the logical 2D renderer of Osmo4 was ported in C into the
	GPAC framework, the result being as good as the original player. At that time a
	lot of people where asking if porting Osmo4 to other platforms than Win32 would be easy, and the reply was always
	'no', Osmo4 being completely windows-centric. Moreover the MPEG-4 Systems Reference Software is a huge beast, not
	easily portable nor configurable. The solution to that was simple: releasing GPAC and have people help us in the
	port and development of the project. However since the name Osmo4 was registered at M4IF for binary releases in
	the year 2003, we have kept the name and restarted a versioning matching the GPAC project versions. Therefore the
	player available in GPAC is also named Osmo4: "Osmo4 est mort, Vive Osmo4!".
	</p>

	       <h1 id="Platforms">Which platforms does GPAC support ?</h1>
<p>GPAC is running on the following platforms:</p>
<ul>
<li>Windows 2K, XP, 98 and ME</li>
<li>Smartphone 2002, PocketPC 2002, PocketPC 2003, Smartphone 2003</li>
<li>Linux platforms with X11 and OSS support</li>
<li>Embedded Linux (Familiar) platforms</li>
<li>all GCC-powered platforms with <a href="http://www.libsdl.org">SDL</a> support</li>

<li>Symbian port of GPAC is currently underway.</li>
</ul>


	       <h1 id="MP4BoxGUI">Will a GUI version of MP4Box ever be developed ?</h1>
<p>
The answer is no. Such tools already exist (like the very nice <a href="http://yamb.unite-video.com/">YAMB</a> or MP4BoxGUI), and work quite well. GPAC's focus on GUI authoring is targeted at full multimedia authoring tools, but this is a much longer-term goal.
</p>

	       <h1 id="FAQS">Why is ... ? Can I ... ? Will GPAC ... ?</h1>
<p>
The answer is out there and it will find you... if you want it to. Or if you stop by our <a href="https://sourceforge.net/forum/?group_id=84101">forums</a> !
</p>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>
