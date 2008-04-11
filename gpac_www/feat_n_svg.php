<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GPAC SVG Features - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="spacer"></div>
  <div id="fond">

<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
<h1>This page describes the status of the implementation in the GPAC
project of the Scalable Vector Graphics (SVG) language. It describes
the features that are implemented and the roadmap for missing or new features.</h1>
	</div>

<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre">
			<!-- =================== SECTION 1 ============  -->
      <?php include_once("play_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
		<div id="sous_menu">
<ul>
<li><a href="#results">Test Suite Results</a></li>
<li><a href="#status">Overall Status</a></li>
<li><a href="#overview">Overview</a></li>
</ul>
		</div>
        <h1 id="overview">Overview</h1>
          <p>The goal of the SVG support in GPAC is not to provide yet another mixed HTML/SVG browser but to focus on integration of description languages with audio/video resources. The GPAC player will remain in between a document browser and a traditional audio/video player with support for languages like BIFS, SVG, X3D ...</p>
          <p>The implementation of the SVG support in GPAC is divided into 3 parts as follows:</p>
        <ul>
        
        <li>SVG Parsing</li>
        <p>The GPAC framework reads SVG files and builds the memory tree. It uses a (simple, limited but functional) SAX parser. The parser can load, progressively or not, an SVG document into memory. If you are interested, the source code for this part is <a href="http://gpac.cvs.sourceforge.net/gpac/gpac/src/scene_manager/loader_svg_da.c?view=markup">here</a>.</p>
        
        <li>SVG Tree Managment</li>
        
        <p>This part of the SVG support is common in the GPAC implementation with the MPEG-4 BIFS tree managment, and is called in general Scene Graph Managment. The Scene Graph part is responsible for the creation of elements, the handling
        of attributes (parsing, dump, cloning ...). It also handles the animations and scripting features of SVG.<br>If you check out the source code, look for svg_nodes.h, scenegraph_svg.h, src/scenegraph/svg_*.*.</p>
        
        <li>SVG Compositing</li>
        <p>The compositing operation in our terminology is the process which
        consists in transforming the scene graph into structures used by a
        rasterizer. The GPAC project has two rasterizers: GDI Plus on Windows
        platforms, and the GPAC 2D Rasterizer, based on FreeType.<br>If you check out the source code, look for src/compositor/svg*.c.</p>
        </ul>

<h1 id="status">Overall Status</h1>

<p>Obviously, the current implementation has some limitations, which we
would like to remove and bugs we would like to fix. The following items
give the area that we want to improve. The order is not important.
</p>

<h4>&gt; SVG Fonts</h4>
<p>SVG Fonts are not yet supported. Initially, we did not consider this
feature as important because text can be shown without it. But font
embedding is an important feature if you want your text to be displayed
correctly on all terminals. So this is a feature we want to look at (as well as MPEG-4 Font streaming) but this requires modification of the text processing of GPAC (i.e. the integration with Freetype).</p>

<h4>&gt; Conditional Processing</h4>
<p>The switch element and the required* attributes are not yet
supported, but we plan to support it to enable adapation of the content
on the user terminal. This is of particular interest for us, because
adaptation is one of our research topic. We plan to investigate how to
extend the existing features for dynamic adaptation and especially in
Broadcast environments.</p>

<h4>&gt; Text display</h4>
<p>The text layout is very basic in the current implementation. We
would like to improve it taking into account the proposed new features
in Tiny 1.2. Some nice support for the textArea element is already there.</p>

<h4>&gt; Animations</h4>
<p>Though animations are supported, the current model is not completely
compliant with the combined SMIL/CSS/SVG specifications. For instance, animations restarting are not reorderd. We would also like to improve the performances of the animation module, basically making assumptions on usage scenarios.</p>

<h4>&gt; CSS</h4>
<p>CSS is a big specification and supporting it all is not really under consideration. We currently support the CSS properties referenced by the SVG specification, the use of presentation attributes and the inheritance mechanisms (except tricky cases with paint servers). The style attribute is also supported but not the style element.</p>

<h4>&gt; DOM API</h4>
<p>Support for DOM is envisaged but a simpler API like Micro DOM will be prefered. Micro DOM is partially supported.</p>

<h4>&gt; Filters</h4>
<p>Support for Filters is not envisaged at the moment.</p>

<h4>&gt; Paint Servers</h4>
<p>Solid colors and gradients are supported but patterns are not. The integration of SVG patterns should not be so difficult as it is supported for other languages.</p>

<h4>&gt; Linking</h4>
<p>We currently support hyperlinking to full SVG documents. Linking to external elements from use elements is not supported for instance. svgView is not yet supported but is planned.</p>

<h4>&gt; Use</h4>
<p>The use element is implemented but we initially thought it was close to VRML USE. This is not at all the case and therefore implementation is not fully conformant. We also need to think about how to best implement the 'synchronized deep-cloning' behavior attached to a use element. However, this only has impact when doing heavy scripting.</p>

<h4>&gt; Audio/Video</h4>

<p>Audio/Video support, using various encoding formats and protocols,
is currently available for SVG presentations. We are still trying to understand the specification and the following features: independent timelines for SVG document and embedded medias, SMIL features like clipBegin and clipEnd, media timeline control (begin, end).</p>

<h4 >&gt; The animation element</h4>
<p>The animation element, which is equivalent to the Flash MovieClip feature, and which allows sprite animations, is supported.</p>

<h4>&gt; Constrained Transformations</h4>
<p>The transform="ref()" syntax is implemented in GPAC.</p>

<h4>&gt; Focus &amp; Navigation</h4>
<p>The focus ring concept is implemented but the SVG specification is not clear in some parts (look at the test suite) and the implementation will be fixed when the spec is fixed.</p>

<h4>&gt; Timeline and Progressive Rendering</h4>
<p>Some support for progressive rendering is there but the timelineBegin and externalResourceRequired attributes are not yet supported.</p>

<h4 >&gt; Memory Managment</h4>
<p>The discard element is fully supported already.</p>

<h4>&gt; Prefetch</h4>
<p>The prefetch element is supported because it is parsed and ignored. But we plan to see in the near future how it can be leveraged for low-latency presentations.</p>

<h4>&gt; Miscellaneous</h4>
There are also some small features which are currently supported like : System Colors, currentColor, preserveAspectRatio on images, whitespace preservation, vector-effect, viewport-fill* and some others that we still need to implement: xml:base, snapshotTime, playbackOrder, ....

		<h1 id="results">Detailed results for version 0.4.5-DEV (build 10) - 2007/10/12</h1>
    
    <p>The following table is the result of the SVG Tiny 1.2 test suite behaviour in GPAC. Some elements are refered to as not implemented, which means they are not supported but implementation is on the short-term roadmap. Elements not supported are likely to remain unsupported for a longer time.</p>
		</h1>
		<h2>Global results</h2>
		<style type="text/css" xml:space="preserve">
                    tr { padding: 0.2em; background: #ddd }
                    td {padding: 0.2em}
                    tr p {margin-top: 0.2em; margin-bottom: 0.2em}
                    .OK { background: #cfc; } 
                    .PARTIAL { background: #eee } 
                    .FAIL { background: #fdc; border: 2px solid #f33 } 
                </style>

		<h2>Global results</h2>
		<table>
			<tbody>
				<tr>
					<td>Total number of tests</td>
					<td align="right">516</td>
				</tr>
				<tr>
					<td class="OK">Number of (non-empty) tests with status = OK</td>
					<td class="OK" align="right">296</td>
					<td class="OK" align="right">57%</td>
				</tr>
				<tr>
					<td class="PARTIAL">Number of (non-empty) tests with status = PARTIAL</td>
					<td class="PARTIAL" align="right">115</td>
					<td class="PARTIAL" align="right">22%</td>
				</tr>
				<tr>
					<td class="FAIL">Number of (non-empty) tests with status = FAIL</td>
					<td class="FAIL" align="right">103</td>
					<td class="FAIL" align="right">20%</td>
				</tr>
			</tbody>
		</table>
		<h2>Detailled Results</h2>
		<table>
			<thead>
				<tr>
					<th>Test File</th>
					<th>Revision</th>
					<th>Comment in case of failure or partial result.</th>
				</tr>
			</thead>
			<tbody>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-02-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-03-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-04-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-05-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-06-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-07-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-08-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-09-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-10-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-11-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL">paced keyword is not supported yet, linear used instead.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-12-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL">spline keyword is not supported yet, linear used instead.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-13-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-14-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-15-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL">paced keyword is not supported yet, linear used instead.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-17-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL">spline keyword is not supported yet, linear used instead.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-19-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-20-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-21-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-22-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-23-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-24-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-25-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-26-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-27-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-28-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-29-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-30-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-31-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-32-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-33-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-34-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-35-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">stroke animation is not correctly supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-36-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK">Rendering problem when rotating images</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-37-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-38-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-39-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-40-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-41-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-44-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-46-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-52-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-53-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">paced keyword is not supported yet, linear used instead.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-60-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">No wallclock events (not in tiny), negative offsets in end based time is not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-61-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-62-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">No wallclock events (not in tiny), negative offsets in end based time is not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-63-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">Negative offset in end attribute</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-64-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-65-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK">min less than repeatDur (same as ASV and Opera)</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-66-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-67-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK">Same behavior in Opera</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-68-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-69-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-70-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-77-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.6</td>
					<td xmlns="" class="PARTIAL">DOMText Node parsing problem + Animation of text-anchor and font-style buggy.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-78-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK">synchronisation problem ?</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-80-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-81-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-82-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.8</td>
					<td xmlns="" class="PARTIAL">paced keyword is not supported yet, linear used instead.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-83-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-84-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-85-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-86-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-202-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL">Precision problem in additive animations</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL">Misalignement of shapes during the animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-205-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL">Misalignement of shapes during the animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-206-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL">Misalignement of shapes during the animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-207-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">animate-elem-208-t</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL">animateMotion bug</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-209-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateMotions</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-210-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-211-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateTransform</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-212-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateTransform</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-213-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateTransform</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-214-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateTransform</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-215-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateTransform</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-216-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateTransform</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-217-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">cumulative/additive animateTransform</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-218-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-219-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL">attributeType not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-220-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">calcMode spline not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-221-t</td>
					<td xmlns="" class="OK" align="center">1.1</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">animate-elem-222-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL">begin, end, dur, min</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">animate-elem-223-t</td>
					<td xmlns="" class="OK" align="center">1.1</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">animate-elem-224-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-coord-01-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-coord-02-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-01-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-02-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-03-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-04-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-05-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-06-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-07-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-08-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-trans-09-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-units-01-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-viewattr-05-t</td>
					<td xmlns="" class="OK" align="center">1.11</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-viewattr-06-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK">Not a Tiny test (use of overflow)</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-constr-201-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-constr-202-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-constr-203-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">coords-constr-204-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL">order between animateMotion and transform=ref</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">coords-pAR-201-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">extend-namespace-02-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-desc-01-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-desc-02-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-desc-03-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-desc-05-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-elem-01-t</td>
					<td xmlns="" class="FAIL" align="center">1.7</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-elem-02-t</td>
					<td xmlns="" class="FAIL" align="center">1.7</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-elem-03-t</td>
					<td xmlns="" class="FAIL" align="center">1.6</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-elem-05-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-elem-06-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-glyph-02-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-glyph-03-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-glyph-04-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-glyph-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-glyph-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-kern-01-t</td>
					<td xmlns="" class="FAIL" align="center">1.6</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">fonts-overview-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-dom-02-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-events-02-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-order-04-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-order-05-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">interact-order-06-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.8</td>
					<td xmlns="" class="PARTIAL">Strings are not selectable</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">interact-pevents-01-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">Some text is not pointed correctly.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">interact-pevents-02-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.8</td>
					<td xmlns="" class="PARTIAL">Some text is not pointed correctly.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-pevents-03-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-pevents-04-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">interact-pevents-05-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">interact-pevents-06-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-pevents-07-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-pevents-08-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-zoom-01-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-zoom-02-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-zoom-03-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-event-201-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-event-202-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-event-203-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-event-204-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-201-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK">According to our interpretation of the spec, this test requires the use of previous focus to see any modifications.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-202-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-203-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK">No viewport modification to follow focus</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-204-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-205-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK">bounding box highlight bug</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-206-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-207-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-208-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-209-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">interact-focus-210-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">top row doesn't turn red</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-211-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK">According to our interpretation of the spec, this test requires the use of previous focus to see any modifications.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">interact-focus-212-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-201-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-202-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-203-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-204-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-205-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-206-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-207-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-208-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-209-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">jpeg-required-210-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">linking-a-01-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-a-03-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL">Fragment views not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">linking-a-04-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.8</td>
					<td xmlns="" class="PARTIAL">Fragment</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">linking-a-05-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.6</td>
					<td xmlns="" class="PARTIAL">Centering on the circle doesn't work</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-a-07-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL">target attribute is not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-a-08-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL">tspan</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-a-09-b</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-frag-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-frag-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">linking-refs-201-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">linking-refs-202-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">linking-refs-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">linking-refs-204-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-refs-205-t</td>
					<td xmlns="" class="FAIL" align="center">1.6</td>
					<td xmlns="" class="FAIL">xml:base</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-uri-01-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-uri-02-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">linking-uri-03-t</td>
					<td xmlns="" class="FAIL" align="center">1.8</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">masking-opacity-01-t</td>
					<td xmlns="" class="OK" align="center">1.1</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-alevel-201-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-alevel-202-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-alevel-203-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-alevel-204-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-alevel-205-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">media-alevel-206-t</td>
					<td xmlns="" class="FAIL" align="center">1.6</td>
					<td xmlns="" class="FAIL">audio-level inheritance</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-alevel-207-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-alevel-208-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-anim-201-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-202-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.8</td>
					<td xmlns="" class="PARTIAL">Primary documents loaded multiple times</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.8</td>
					<td xmlns="" class="PARTIAL">Primary documents loaded multiple times</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.10</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-205-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.9</td>
					<td xmlns="" class="PARTIAL">viewport-fill and vpfo in animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-206-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.9</td>
					<td xmlns="" class="PARTIAL">viewport-fill and vpfo in animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-207-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL">viewport-fill and vpfo in animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-208-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.9</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-anim-209-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-210-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.10</td>
					<td xmlns="" class="PARTIAL">Focus Problem</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">media-anim-211-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL">Wrong media types accepted</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">media-anim-212-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">animated xlink:href on animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-anim-213-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-201-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-202-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-203-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-204-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-205-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-206-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-audio-207-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">sync problem between animation and audio</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-208-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-audio-209-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">end event problem</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-210-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-211-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-212-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-audio-213-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">sound audible at R2</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-audio-214-t</td>
					<td xmlns="" class="OK" align="center">1.1</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">syncBehavior locked but repeatCount indefinite on animation ...</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-202-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">canSlip ...</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">locked with pause ?</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-205-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-206-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-207-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-208-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-209-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-210-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-synch-211-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-201-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-video-202-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.9</td>
					<td xmlns="" class="PARTIAL">video does not disappear when complete</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAILED" align="center">media-video-203-t</td>
					<td xmlns="" class="FAILED" align="center">1.7</td>
					<td xmlns="" class="FAILED">transformBehavior pinned not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-204-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-205-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-206-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-207-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-208-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-209-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-210-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-211-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-212-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-213-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-214-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-video-215-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.6</td>
					<td xmlns="" class="PARTIAL">animate, handler, setAttributeNS on video xlink:href</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">media-video-216-t</td>
					<td xmlns="" class="FAIL" align="center">1.7</td>
					<td xmlns="" class="FAIL">transformBehavior pinned not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-217-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-video-218-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">main function is ok, but some events are not sent correctly</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-video-219-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">animate, handler, removeChild ...</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">media-video-220-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">animate, handler, removeChild ...</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-221-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">media-video-222-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">metadata-example-01-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-color-01-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-color-03-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-color-04-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-color-05-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-colors-201-t</td>
					<td xmlns="" class="OK" align="center">1.1</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-fill-01-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-fill-02-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-fill-03-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-fill-04-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-fill-05-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-04-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-05-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-07-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-08-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-09-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-11-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-12-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-15-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-16-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-17-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-18-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-19-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">paint-grad-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">stop-opacity animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-202-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-203-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-204-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-grad-205-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-nsstroke-201-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">paint-nsstroke-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-nsstroke-203-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-other-201-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-other-202-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">solid-opacity animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-other-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">solid-color animation</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-01-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-02-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-03-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-04-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-05-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-06-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-07-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-08-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-stroke-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-stroke-202-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-stroke-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-stroke-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-stroke-205-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-206-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-stroke-207-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-vfill-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paint-vfill-202-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-vfill-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-vfill-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-vfill-205-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-vfill-206-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">paint-vfillo-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-01-t</td>
					<td xmlns="" class="OK" align="center">1.12</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-02-t</td>
					<td xmlns="" class="OK" align="center">1.12</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-04-t</td>
					<td xmlns="" class="OK" align="center">1.11</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-05-t</td>
					<td xmlns="" class="OK" align="center">1.12</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-06-t</td>
					<td xmlns="" class="OK" align="center">1.11</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-07-t</td>
					<td xmlns="" class="OK" align="center">1.11</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-08-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-09-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-10-t</td>
					<td xmlns="" class="OK" align="center">1.10</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-12-t</td>
					<td xmlns="" class="OK" align="center">1.10</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-13-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-14-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">paths-data-15-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-elems-01-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-elems-02-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-elems-03-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-elems-06-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-elems-07-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-elems-08-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-groups-01-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">render-groups-03-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">script-element-201-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">script-element-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">script-handle-05-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">script-handle-06-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.8</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">script-handle-07-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">script-handle-08-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">script-handle-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">script-handle-202-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">script-listener-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">script-listener-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-circle-01-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-circle-02-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-circle-03-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-ellipse-01-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-ellipse-02-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-ellipse-03-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-intro-01-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-line-01-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-line-02-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-polygon-01-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-polygon-02-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-polyline-01-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-polyline-02-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-rect-01-t</td>
					<td xmlns="" class="OK" align="center">1.9</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-rect-02-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">shapes-rect-03-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-cond-01-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-cond-02-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-cond-03-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-cond-04-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-cond-205-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-cond-206-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-cond-207-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-defs-01-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-defs-201-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-discard-101-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-discard-201-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-discard-202-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-discard-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-discard-204-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-discard-205-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-discard-206-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-discard-207-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-frag-01-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-frag-02-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-frag-03-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-frag-04-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-frag-05-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-frag-06-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-group-01-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-group-03-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">font-style bug</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-01-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-03-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-04-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-05-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-06-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-image-07-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">xml:base</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-08-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-09-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-image-10-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-progressive-201-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-progressive-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL">eRR not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-progressive-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-progressive-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-svg-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL">snapshot time</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-svg-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL">snapshot time</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-svg-203-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-svg-204-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-svg-205-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-use-01-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-use-03-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-04-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">property handling not correct</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">struct-use-05-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-06-b</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-06-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-07-b</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-07-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-08-b</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">Infinite referencing loop</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">struct-use-09-b</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">reference restrictions in use</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">struct-use-202-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">styling-inherit-01-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">styling-pres-01-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-align-01-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-align-03-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL">Bug in text-anchor</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-align-04-t</td>
					<td xmlns="" class="OK" align="center">1.1</td>
					<td xmlns="" class="OK">tref and toap are not tiny elements</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-align-07-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">text-align-08-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-align-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">last space in tspan not rendered properly.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">text-align-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.8</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-201-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">text-area-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-203-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-204-t</td>
					<td xmlns="" class="OK" align="center">1.6</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-205-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-206-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-207-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">text-area-208-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-209-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-210-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-area-211-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-area-212-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-area-213-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">display-align not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-area-220-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.6</td>
					<td xmlns="" class="PARTIAL">display-align not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-area-221-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">display-align not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">text-area-222-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL">editable not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-deco-01-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-fonts-01-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK">SVG Fonts not supported</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-fonts-02-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-fonts-03-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-fonts-04-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-fonts-202-t</td>
					<td xmlns="" class="OK" align="center">1.4</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-intro-01-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-intro-04-t</td>
					<td xmlns="" class="OK" align="center">1.7</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-intro-05-t</td>
					<td xmlns="" class="OK" align="center">1.5</td>
					<td xmlns="" class="OK">Using GDI+ Font Engine</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-intro-06-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK">Using GDI+ Font Engine</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-intro-201-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK">Using GDI+ Font Engine</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-text-03-t</td>
					<td xmlns="" class="OK" align="center">1.1</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-text-04-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-text-05-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-text-06-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.6</td>
					<td xmlns="" class="PARTIAL">SVG Fonts not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-text-07-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.5</td>
					<td xmlns="" class="PARTIAL">rotate text glyphs not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">text-text-08-t</td>
					<td xmlns="" class="OK" align="center">1.8</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-text-09-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.4</td>
					<td xmlns="" class="PARTIAL">rotate text glyphs not supported.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">text-tref-01-b</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">text-tselect-03-t</td>
					<td xmlns="" class="FAIL" align="center">1.7</td>
					<td xmlns="" class="FAIL">text is not selectable.</td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-ws-01-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.6</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">text-ws-02-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.7</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">types-basicDOM-02-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">types-data-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.1</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">types-data-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">types-data-203-t</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">types-data-204-t</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-conform-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-203-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-204-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-205-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-206-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-207-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-208-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-209-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-210-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-211-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-212-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-dom-213-t</td>
					<td xmlns="" class="FAIL" align="center">1.6</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-203-t</td>
					<td xmlns="" class="FAIL" align="center">1.6</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-204-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-205-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-206-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-207-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-event-208-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-glob-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-node-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-node-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-node-203-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-node-204-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-over-01-t</td>
					<td xmlns="" class="FAIL" align="center">1.7</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-smil-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-smil-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-smil-203-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-201-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-203-t</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-204-t</td>
					<td xmlns="" class="FAIL" align="center">1.5</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-205-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-206-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-207-t</td>
					<td xmlns="" class="FAIL" align="center">1.1</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-208-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-209-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-210-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svg-211-t</td>
					<td xmlns="" class="FAIL" align="center">1.3</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">udom-svgtimedelement-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-svgtimedelement-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">udom-svgtimedelement-203-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">udom-svgtimedelement-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">udom-svgtimedelement-205-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">udom-textcontent-201-t</td>
					<td xmlns="" class="OK" align="center">1.2</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">udom-textcontent-202-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">udom-traitaccess-201-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.2</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-traitaccess-202-t</td>
					<td xmlns="" class="FAIL" align="center">1.2</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-traitaccess-203-t</td>
					<td xmlns="" class="FAIL" align="center">1.6</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="PARTIAL" align="center">udom-traitaccess-204-t</td>
					<td xmlns="" class="PARTIAL" align="center">1.3</td>
					<td xmlns="" class="PARTIAL"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="CRASH" align="center">udom-traitaccess-205-t</td>
					<td xmlns="" class="CRASH" align="center">1.3</td>
					<td xmlns="" class="CRASH"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="OK" align="center">udom-traitaccess-206-t</td>
					<td xmlns="" class="OK" align="center">1.3</td>
					<td xmlns="" class="OK"></td>
				</tr>
				<tr xmlns="">
					<td xmlns="" class="FAIL" align="center">udom-traitaccess-207-t</td>
					<td xmlns="" class="FAIL" align="center">1.4</td>
					<td xmlns="" class="FAIL"></td>
				</tr>
			</tbody>
		</table>
    
            </div>
			</div>
	</div>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->

<?php $mod_date="\$Date: 2008-04-11 09:48:30 $"; ?>
<?php include_once("bas.php"); ?>
</div>
</body>
</html>

