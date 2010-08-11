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
The GPAC framework is available for download in source code under LGPL License. Developments are ongoing as your read these lines. 
		</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("home_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
				<h1>Licensing</h1>
				<p>As of version 0.4.0 GPAC is licensed under the GNU Lesser General Public License. Older GPAC versions are available under the GNU General Public License. GPAC is being distributed under the LGPL license, but is also partially distributed by ENST under commercial license. For more info on commercial licensing, please <a href="#">contact us</a>.</p>

<h1>GPAC source code</h1>
<p>GPAC is updated on a day-to-day basis. The latest version is the development version, sometimes not stable. It is available on the <a href="http://sourceforge.net/svn/?group_id=84101">SVN repository hosted on sourceforge.net</a>. You can also  <a href="http://gpac.svn.sourceforge.net/viewvc/gpac/trunk/gpac/">browse the source code</a>.</p>
<p>For non-SVN user, there is no current packaged release (soon). Please contact us.</p>

<p>Supported platforms are:</p>
<ul>
<li>Windows,</li> 
<li>Windows CE,</li> 
<li>Windows Mobile,</li> 
<li>Linux,</li> 
<li>GCC+X11 or GCC+SDL,</li> 
<li>and Symbian 9.</li>
</ul> 


<h1>GPAC third-party libraries</h1>
<p>GPAC is only fully functionnal when compiled with several third-party libraries (media codecs, ECMAScript, Font engine, ...). Features of GPAC are limited without them.
<br>
For Linux users, most of these libraries are distributed in packaged versions (e.g. using apt). Here is the list:
zlib1g-dev xulrunner-1.9.2-dev libfreetype6-dev libjpeg62-dev libpng12-dev libopenjpeg-dev libmad0-dev libfaad-dev libogg-dev libvorbis-dev libtheora-dev liba52-0.7.4-dev libavcodec-dev libavformat-dev libavutil-dev libswscale-dev libxv-dev x11proto-video-dev libgl1-mesa-dev x11proto-gl-dev linux-sound-base libxvidcore-dev libwxbase2.8-dev libwxgtk2.8-dev wx2.8-headers libssl-dev libjack-dev libasound2-dev libpulse-dev libsdl1.2-dev dvb-apps
<br>
For windows users, you can download GPAC third-party libraries here, compilation instructions (sometimes complex) are in the archive.<br/><a class="download_link" href="http://downloads.sourceforge.net/gpac/gpac_extra_libs-0.4.5.zip">Windows Archive (zip)</a></h2>

			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2008-12-02 19:22:10 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

