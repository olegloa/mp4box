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
The GPAC framework is available for download in source code under LGPL License. Many developments are always ongoing and stable releases are usually issued twice a year, so do not hesitate to use the GPAC CVS repository.
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
<p>Due to licensing issues, there is currently no binary release of the GPAC framework. Only the source code is available.</p>

<h1>GPAC source code</h1>
<p>GPAC is updated on a day-to-day basis. The latest version is the development version, sometimes not stable. It is available on the <a href="http://sourceforge.net/cvs/?group_id=84101">CVS repository hosted on sourceforge.net</a>. You can also  <a href="http://gpac.cvs.sourceforge.net/gpac/gpac/">browse the source code</a>.</p>
<p>For non-CVS user, the latest packaged release is 0.4.4 RC2. However, since this version was issued in May 2007, it may not contain all the new features or bug fixes.</p>

<p>Supported platforms in this release are:</p>
<ul>
<li>Windows,</li> 
<li>Windows CE,</li> 
<li>Windows Mobile,</li> 
<li>Linux,</li> 
<li>GCC+X11 or GCC+SDL,</li> 
<li>and Symbian 9.</li>
</ul> 


<h2 class="download_section">Download GPAC source <br/><a class="download_link" href="http://downloads.sourceforge.net/gpac/gpac-0.4.4-rc2.zip">Windows Archive (zip)</a>&nbsp;&nbsp;&nbsp;<a class="download_link" href="http://downloads.sourceforge.net/gpac/gpac-0.4.4.tar.gz">Generic Archive (tar.gz)</a></h2>

<h1>GPAC third-party libraries</h1>
<p>GPAC is only fully functionnal when compiled with several third-party libraries (media codecs, ECMAScript, Font engine, ...). Features of GPAC are limited without them.
</p> 

<h2 class="download_section">Download GPAC third-party libraries <br/><a class="download_link" href="http://downloads.sourceforge.net/gpac/gpac_extra_libs-0.4.4.zip">Windows Archive (zip)</a>&nbsp;&nbsp;&nbsp;<a class="download_link" href="http://downloads.sourceforge.net/gpac/gpac_extra_libs-0.4.4.tar.gz">Generic Archive (tar.gz)</a></h2>

<p>If you need other releases of GPAC, please consult the <a href="http://sourceforge.net/project/showfiles.php?group_id=84101">SourceForge download page</a>.</p>

			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2008-08-05 13:48:55 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

