<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GPAC X3D Features - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="spacer"></div>
  <div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("play_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
<p>The following table is a detailed listing of supported X3D nodes in GPAC. Some nodes are only implemented in one of the renderers, some in both and the rest in none... 
Some nodes are refered to as not implemented, which means these nodes are not supported but implementation is on the short-term roadmap. Not supported nodes are likely to remain unsupported for a longer time.</p>
<br>
<p>This is not a bug tracker page, in other words it is quite possible that some nodes listed as supported have strange behaviors. In such a case please report on <a href="https://sourceforge.net/projects/gpac">gpac sourceforge page</a></p>
<table border="1" width="100%">
	<tbody><tr><td><b><i>Node</i></b></td>
		<td><b><i>2D Renderer</i></b></td>
		<td><b><i>3D Renderer</i></b></td>
	</tr>
</tbody></table>
<p>
<br>
<b>X3D Scene Description</b>
</p>
<table border="1" width="100%">
	<tbody><tr><td><b>Anchor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Appearance</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Arc2D</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>ArcClose2D</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>AudioClip</b> </td><td>Supported (1)</td><td>Supported (1)</td></tr>
	<tr><td><b>Background</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Billboard</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>BooleanFilter</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>BooleanSequencer</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>BooleanToggle</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>BooleanTrigger</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Box</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Circle2D</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>Collision</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Color</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ColorInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ColorRGBA</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Cone</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Contour2D</b> </td><td class="node_no3d">No 3D Support</td><td>Not Supported</td></tr>
	<tr><td><b>ContourPolyline2D</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>CoordinateDouble</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>CoordinateInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>CoordinateInterpolator2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Cylinder</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>DirectionalLight</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Disk2D</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>ElevationGrid</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>EspduTransform</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Extrusion</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>FillProperties</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>Fog</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>FontStyle</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>GeoCoordinate</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>GeoElevationGrid</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>GeoLocation</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>GeoLOD</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>GeoMetadata</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>GeoOrigin</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>GeoPositionInterpolator</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>GeoTouchSensor</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>GeoViewpoint</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Group</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>HAnimDisplacer</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>HAnimHumanoid</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>HAnimJoint</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>HAnimSegment</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>HAnimSite</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>ImageTexture</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>IndexedFaceSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>IndexedLineSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>IndexedTriangleFanSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>IndexedTriangleSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>IndexedTriangleStripSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Inline</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>IntegerSequencer</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>IntegerTrigger</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>KeySensor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>LineProperties</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>LineSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>LoadSensor</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>LOD</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Material</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>MetadataDouble</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>MetadataFloat</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>MetadataInteger</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>MetadataSet</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>MetadataString</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>MovieTexture</b> </td><td>Supported (1)</td><td>Supported (1)</td></tr>
	<tr><td><b>MultiTexture</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>MultiTextureCoordinate</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Implemenetd</td></tr>
	<tr><td><b>MultiTextureTransform</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>NavigationInfo</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Normal</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>NormalInterpolator</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>NurbsCurve</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsCurve2D</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsOrientationInterpolator</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsPatchSurface</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsPositionInterpolator</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsSet</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsSurfaceInterpolator</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsSweptSurface</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsSwungSurface</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsTextureCoordinate</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsTrimmedSurface</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>OrientationInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PixelTexture</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PlaneSensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>PointLight</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>PointSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Polyline2D</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>Polypoint2D</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>PointSet&gt;</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>PositionInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PositionInterpolator2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ProximitySensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>RecieverPdu</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Rectangle2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ScalarInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Script</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Shape</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>SignalPdu</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Sound</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Sphere</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>SphereSensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>SpotLight</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>StaticGroup</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>StringSensor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Switch</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Text</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>TextureBackground</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>TextureCoordinate</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>TextureCoordinateGenerator</b> </td><td class="node_to_complete">Not Supported</td><td>Partial Support (local coords only)</td></tr>
	<tr><td><b>TextureTransform</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>TimeSensor</b> </td><td>Supported (1)</td><td>Supported (1)</td></tr>
	<tr><td><b>TimeTrigger</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>TouchSensor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Transform</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>TransmitterPdu</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>TriangleFanSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>TriangleSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>TriangleSet2D</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>TriangleStripSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Viewpoint</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>VisibilitySensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>WorldInfo</b> </td><td>Supported</td><td>Supported</td></tr>
</tbody></table>
<p><b>Note:</b><br>
(1): pauseTime/resumeTime not implemented in X3D time nodes.
</p>
<br><br>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
<?php $mod_date="\$Date: 2008-04-11 09:48:30 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>
