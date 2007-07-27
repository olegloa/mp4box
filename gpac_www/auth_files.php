<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Authoring with GPAC - GPAC Project on Advanced Content</title><link href="code/styles.css" rel="stylesheet" type="text/css" /></head><body><div id="fond"><?php include_once("nav.php"); ?><!-- =================== ENTETE DE LA PAGE =========================================  -->	<div id="Chapeau_court">		<h1>The web is full of audiovisual, SVG and VRML content, but only a few sites propose BIFS content. Apart from the demos hosted on this site, you may find the following links useful.
		</h1>	</div><!-- =================== CORPS DE LA PAGE ============================================  -->	<div id="Centre">      <?php include_once("auth_left.php"); ?>			<!-- =================== SECTION 2 ============  -->			<div class="Col2">

		<div id="sous_menu"><ul><li><a href="#streaming">RTSP Resources</a></li><li><a href="#vrml">VRML & X3D</a></li><li><a href="#svg">SVG</a></li><li><a href="#bifs">MPEG4 Systems</a></li></ul>		</div>

<h1 ID="bifs">MPEG4 Systems content</h1>


<h2><a href="http://sourceforge.net/projects/mp4menu">MP4MENU</a></h2>
<p>A rather complete mapping of DVD interactivity to the BIFS standard, under GPL license.</p>


<h2>MPEG-4 Systems Conformance Streams</h2>
<p>ENST is currently hosting a <a href="http://www.comelec.enst.fr/%7Edufourd/mpeg-4/streams.html">large repository of conformance streams</a> for MPEG-4 Systems. These streams may proove extremely usefull
but may as well be a complete waste of time, authoring simplicity being not required for MPEG-4 conformance tests!</p>
<p>Although no awards apply in this domain, as MPEG-4 experts (and not as commercials :) we suggest looking at the GREAT test sequences from IBM in these packages, many
of whom have been intensively used for the GPAC renderer optimizations.</p>

<h2>Commercial and educational MPEG-4 Systems Content</h2>
<p>ENST has a nice <a href="http://www.comelec.enst.fr/%7Edufourd/mpeg-4/index.html#content">set of examples</a> available, although some are rather old
and were designed to <i>be playable</i>, coping with rendering bugs of players available at those prehistoric times. And of course there are some samples on the <a href="http://www.comelec.enst.fr/osmo4/#samples">Osmo4 web page</a> in case you haven't been there yet.</p>
<p>Envivio, Inc has also <a href="http://www.envivio.com/solutions/etv/sample.jsp">some samples</a> available for download. Be carefull, some of these samples use MPEG-4 still image codec (known as VTC) which is not supported in GPAC.</p>
<p>And of course there are plenty of simple Audio/Video content out there you can look for on the web.</p>

<h1 id="svg">SVG Content (Full, Mobile, Basic, Tiny) </h1>
<p>A lot of SVG content is available on the Internet. If you are an SVG beginner, you should start with the <a href="http://www.w3.org/Graphics/SVG/Test/">SVG 1.1 test suite</a>.</p>

<p>Some very nice cliparts are also available on <a href="http://www.croczilla.com/svg/samples/">mozilla web site</a>.</p>

<h1 id="vrml">VRML and X3D content</h1>
<p>A lot of VRML/X3D content is available on the Internet. If you are a VRML/X3D beginner, you should start with the <a href="http://xsun.sdct.itl.nist.gov/~mkass/vts/html/vrml.html">NIST vrml conformance files</a> and the <a href="http://www.web3d.org/x3d/learn/">Web3D X3D resource page</a>.</p>

<h1 id="streaming">MPEG-4 Streaming resources</h1>
<p><a href="http://www.telemak.com">Telemak</a> has kindly setup <a href="http://3gpp.telemak.com/">live broadcasts of 3GP content</a> that you can try with GPAC (for audio, you will need to install the 3GP AMR plugin).</p>

<p><a href="http://www.archive.org/">The Internet Movie Archive</a> has a lot of video ressources, including both HTTP and RTSP MPEG-4 files. To play the streaming files, you will have
to save the QT link as an HTML page and get the RTSP url in the QTSRC attribute of the EMBED element. You can then open this URL with GPAC.
</p>

To play the following RTSP presentations, copy and paste the URL in GPAC (Osmo4 or MP4Client).

<p><a href="http://www.wireonfire.com/launchmpeg4.html">Wire on Fire</a> artists have setup several MPEG-4 broadcasts:</p>
<ul>
<li><a href="rtsp://columbia.forest.net/audem/mp4playlist1">MPEG-4 Cast #1</a></li>
<li><a href="rtsp://columbia.forest.net/audem/mp4playlist2">MPEG-4 Cast #2</a></li>
<li><a href="rtsp://columbia.forest.net/audem/mp4playlist3">MPEG-4 Cast #3</a></li>
</ul>

<p><a href="http://www.epicrecords.com/mpeg4/">Epic records</a> has couple of streaming files to test:</p>
<ul>
<li><a href="rtsp://a1977.q.kamai.net/3/1977/1127/3e5d2ce1/1a1a1ad443b92287fa28990d83b11282f25ec3f154c3305fcc3bb11a7ff221940371ea197eee57ba/wheniseeyou_100.mp4">Macy Gray - When I See you</a></li>
<li><a href="rtsp://a1962.q.kamai.net/3/1962/1127/3e5541e4/1a1a1ad443b92287fa28990d83b11282f25ec3f154c3305fcc3bb11a7ff221940371ea197eee57ba/girlfriend_100.mp4">B2K  - Girlfriend</a></li>
<li><a href="rtsp://a1980.q.kamai.net/3/1980/1127/3e42b9ef/1a1a1ad443b92287fa28990d83b11282f25ec3f154c3305fcc3bb11a7ff221940371ea197eee57bae94cb11d86f459/drove_100.mp4">Celine Dion - I Drove all night</a></li>
<li><a href="rtsp://a564.q.kamai.net/3/564/1127/3e760aac/1a1a1ad443b92287fa28990d83b11282f25ec3f154c3305fcc3bb11a7ff221940371ea197eee57ba/comedigmeout_100.mp4">Kelly Osbourne - Come Dig Me Out</a></li>
</ul>

<p><a href="http://www.apple.com/quicktime/gallery/mpeg4.html">Apple Quicktime</a> has setup a video gallery:</p>
<ul>
<li><a href="rtsp://a1749.q.kamai.net/7/1749/1416/3c964c64/neo.qtv.apple.com/secure/may/preview/classic_cars_300.mp4">Classic Cars in Camel</a></li>
<li><a href="rtsp://a463.q.kamai.net/7/463/1416/3ce0984/neo.qtv.apple.com/secure/may/preview/pacific_300.mp4">Pacific Grove Coast</a></li>
<li><a href="rtsp://a1749.q.kamai.net/7/1749/1416/3c964c64/neo.qtv.apple.com/secure/may/preview/civ3_700.mp4">Civilization III trailer</a></li>
</ul>
	</div>
<?php $mod_date="\$Date: 2007-07-27 12:26:18 $"; ?><?php include_once("bas.php"); ?><!-- =================== FIN CADRE DE LA PAGE =========================================  --></div></body></html>
