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
<object type="application/x-gpac" height="200" width="400">
<param name="src" value="demos/layers3D.mp4">
<param name="use3d" value="true">
</object>
</div>
<p>
This movie shows usage of MPEG-4 layers. The scene is composed of three 3D layers, each with their own navigation information. The first layer also shows that a 3D layer is considered as a 0-depth object in MPEG-4, and is composed with the other objects using the painter's algorithm.
</p>
<p>
Also not the usage of backgrounds in the scene, especially the alpha background of the second layer allowing see-through of the main scene movie background.
</p>
	</div>
<?php $mod_date="\$Date: 2008-04-11 09:48:30 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>
