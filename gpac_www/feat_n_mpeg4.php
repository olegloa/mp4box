<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GPAC MPEG-4 BIFS Features - GPAC Project on Advanced Content</title>
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
<p>The following table is a detailed listing of supported MPEG-4 nodes in GPAC. Some nodes are only implemented in one of the renderers, some in both and the rest in none... 
Some nodes are refered to as not implemented, which means these nodes are not supported but implementation is on the short-term roadmap. Not supported nodes are likely to remain unsupported for a longer time.
</p>
<br>
<p>This is not a bug tracker page, in other words it is quite possible that some nodes listed as supported have strange behaviors. In such a case please report on <a href="https://sourceforge.net/projects/gpac">gpac sourceforge page</a>
</p>
<br>
<table border="1" width="100%">
	<tbody><tr><td><b><i>Node</i></b></td>
		<td><b><i>2D Renderer</i></b></td>
		<td><b><i>3D Renderer</i></b></td>
	</tr>
</tbody></table>
<p>
<br>
<b>MPEG-4 Scene Description Version 1</b>
</p>
<table border="1" width="100%">
	<tbody><tr><td><b>Anchor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>AnimationStream</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Appearance</b> </td><td> Supported</td><td>Supported</td></tr>
	<tr><td><b>AudioBuffer</b> </td><td>Supported with natural audio, not MPEG-4 SA</td><td>Supported with natural audio, not MPEG-4 SA</td></tr>
	<tr><td><b>AudioClip</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>AudioDelay</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>AudioFX</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>AudioMix</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>AudioSource</b> </td><td>Supported with natural audio, not MPEG-4 SA</td><td>Supported with natural audio, not MPEG-4 SA</td></tr>
	<tr><td><b>AudioSwitch</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Background</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Background2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Billboard</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Bitmap</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Box</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Circle</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Collision</b></td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Color</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ColorInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>CompositeTexture2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>CompositeTexture3D</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Conditional</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Cone</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Coordinate</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Coordinate2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>CoordinateInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>CoordinateInterpolator2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Curve2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Cylinder</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>CylinderSensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>DirectionalLight</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>DiscSensor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ElevationGrid</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Expression</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Extrusion</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Face</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>FaceDefMesh</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>FaceDefTables</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>FaceDefTransform</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>FAP</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
 <tr><td><b>FDP</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>FIT</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Fog</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>FontStyle</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Form</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Group</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ImageTexture</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>IndexedFaceSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>IndexedFaceSet2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>IndexedLineSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>IndexedLineSet2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Inline</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>LOD</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Layer2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Layer3D</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Layout</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>LineProperties</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ListeningPoint</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Material</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Material2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>NavigationInfo</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Normal</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>NormalInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>OrderedGroup</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>OrientationInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PixelTexture</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PlaneSensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>PlaneSensor2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PointLight</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>PointSet</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>PointSet2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PositionInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PositionInterpolator2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ProximitySensor2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ProximitySensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>QuantizationParameter</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Rectangle</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ScalarInterpolator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Script</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Shape</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Sound</b> </td><td class="node_no3d">No 3D Support</td><td>Supported - Stereo spatializer only</td></tr>
	<tr><td><b>Sound2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Sphere</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>SphereSensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>SpotLight</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Switch</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>TermCap</b> </td><td class="node_to_complete">Not Implemented</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>Text</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>TextureCoordinate</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>TextureTransform</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>TimeSensor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>TouchSensor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Transform</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Transform2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Valuator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Viewpoint</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>VisibilitySensor</b> </td><td class="node_no3d">No 3D Support</td><td>Supported</td></tr>
	<tr><td><b>Viseme</b> </td><td class="node_no3d">No 3D Support</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>WorldInfo</b> </td><td>Supported</td><td>Supported</td></tr>
</tbody></table>
<br>
<b>Note:</b> VRML 97 nodes not included in MPEG-4 are not supported and will NEVER be. Their X3D equivalents are not supported but will be some day...
<p>
<br>
<b>MPEG-4 Scene Description Version 2</b>
</p>
<table border="1" width="100%">
	<tbody><tr><td><b>AcousticMaterial</b></td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>AcousticScene</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>ApplicationWindow</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>BAP</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>BDP</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>Body</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>BodyDefTable</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>BodySegmentConnectionHint</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>DirectiveSound</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>Hierarchical3DMesh</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>MaterialKey</b> </td><td>Supported (binary keying only)</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>PerceptualParameters</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
</tbody></table>
<p>
<br>
<b>MPEG-4 Scene Description Version 3</b>
</p>
<table border="1" width="100%">
	<tbody><tr><td><b>TemporalTransform</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>TemporalGroup</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>ServerCommand</b> </td><td class="node_to_complete">Not Implemented</td><td class="node_to_complete">Not Implemented</td></tr>
</tbody></table>
<p>
<br>
<b>MPEG-4 Scene Description Version 4</b>
</p>
<table border="1" width="100%">
	<tbody><tr><td><b>InputSensor</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>MatteTexture</b> </td><td class="node_to_complete">Not Implemented</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>MediaBuffer</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>MediaControl</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>MediaSensor</b> </td><td>Supported</td><td>Supported</td></tr>
</tbody></table>
<p>
<br>
<b>MPEG-4 Scene Description Version 5</b>
</p>
<table border="1" width="100%">
	<tbody><tr><td><b>BitWrapper</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>CoordinateInterpolator4D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>DepthImage</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>FFD</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>MeshGrid</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NonLinearDeformer</b> </td><td class="node_to_complete">Not Supported</td><td>Supported</td></tr>
	<tr><td><b>NurbsCurve</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsCurve2D</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>NurbsSurface</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>OctreeImage</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>PointTexture</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>PositionAnimator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PositionAnimator2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PositionInterpolator4D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>ProceduralTexture</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SBBone</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SBMuscle</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SBSegment</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SBSite</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SBSkinnedModel</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SBVCAnimation</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>ScalarAnimator</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>SimpleTexture</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SubdivisionSurface</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>SubdivSurfaceSector</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>WaveletSubdivisionSurface</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
</tbody></table>
<p>
<br>
<b>MPEG-4 Scene Description Version 6</b>
</p>
<table border="1" width="100%">
	<tbody><tr><td><b>Clipper2D</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>ColorTransform</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Ellipse</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>LinearGradient</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>PathLayout</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>RadialGradient</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>SynthesizedTexture</b> </td><td class="node_to_complete">Not Supported</td><td class="node_to_complete">Not Supported</td></tr>
	<tr><td><b>TransformMatrix2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>Viewport</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>XCurve2D</b> </td><td>Supported</td><td>Supported</td></tr>
	<tr><td><b>XFontStyle</b> </td><td class="node_to_complete">Not Implemented</td><td class="node_to_complete">Not Implemented</td></tr>
	<tr><td><b>XLineProperties</b> </td><td>Supported</td><td>Supported</td></tr>
</tbody></table>
<br><br>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
<?php $mod_date="\$Date: 2008-04-11 09:48:30 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>
