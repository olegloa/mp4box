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
<div style="text-align: center; width: 100%;">
<object id="gpac" type="application/x-gpac" height="300" width="400">
<param name="src" value="demos/castle.mp4">
<param name="use3d" value="true">
</object>
<br><br>
<input onclick="gpac.Update('application/x-bt', 'REPLACE N34.set_bind BY TRUE  REPLACE N33.set_bind BY TRUE');" value="Restart Tour" type="button">
</div>	</div>
<?php $mod_date="\$Date: 2008-04-11 09:48:30 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>
