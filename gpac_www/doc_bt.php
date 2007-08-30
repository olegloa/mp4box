<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BIFS Textual Formats documentation - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
MPEG-4 Systems scene coding and media packaging with MP4Box requires the use of textual description of the scene and of the media. MP4Box relies on standard MPEG-4 description but defines its own extensions for media multiplexing.
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre_court">
      <?php include_once("pack_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">
		<div id="sous_menu">
<ul>
<li><a href="#tips">Tips &amp; Tricks</a></li>
<li><a href="#commands">MPEG-4 Commands</a></li>
<li><a href="#XMT">XMT Format</a></li>
<li><a href="#BT">BT Format</a></li>
</ul>
		</div>
<h1 id="BT">BT Format</h1>
<p>BT stands for BIFS Text and is an exact textual representation of the MPEG-4 BIFS scene. Its syntax is the same as the VRML/X3D (.wrl and .x3dv files) ones for the scene description part, and it has been extended for other MPEG-4 tools (OD, OCI, IPMP). </p>
<p>You will find plenty of example BT files in the tutorial and regression test suite, and we strongly recommend using these to get more familiar with the BT syntax.</p>
<p>The BT language has been originally developped at ENST as the textual format of the late MP4Tool. The format is still enhanced at ENST for GPAC needs, but we try not to mess up too much with it to keep VRML compatibility.</p>
There are 3 major parts in a BT file:
<ul>
<li>The root scene, made of a collection of PROTO nodes if desired, a single top level node and a list of routes for interaction if needed. This is the part common to BT and VRML formats.</li>

<li>The InitialObjectDescriptor, describing the streams that must be opened when opening the MPEG-4 scene. It usually contains a BIFS stream description and an OD stream description when visual/audio media are present in the scene.</li>
<li>A succession of modification to the scene and their associated timing.</li>
</ul>

<h2>MP4Box specific BT syntax</h2>
The syntax of BIFS/OD commands in BT has been enhanced to enable animation stream and scalable description encodings. 
<br/>The new elements are placed at the access unit declaration 'AT 
TIMING' element:
<ul>
<li>'RAP' element: specifies the following access unit is a random access points. This is needed because MP4Box cannot currently compute the scene random access state per stream. Note however that the first access unit of any systems stream is considered as a random access point</li>
<li>'IN' element: specifies the following access unit happens in the stream of given ID.</li>
</ul>
<p>For example, <i>RAP AT 1000 IN 20 { ... }</i> means that the access unit is a random access point, its timing is 1000 (in stream timescale) and it happens in stream whose ES_ID is 20.</p>

<p>
The syntax of the 'AT TIMING' element has been extended to support differential timing to enable authors to specify relative
time between commands rather than absolute timing. The differential timing is signaled by using a capital D before the timing itself.
<br/><br/>
For example, <i>AT D2000 { ... }</i> means that this access unit occurs 2000 time ticks after the preceding access unit.
</p>

<h2>Importing media with MP4Box and BT</h2>
<p>MP4Box uses a specific descriptor for stream importing called MuxInfo. This descriptor is not normative (although used by MP4Tool and all tools from MPEG-4 Systems Reference Software). It is never encoded in the file. The modifications made to this descriptor are backward compatible with other tools using this descriptor. The current syntax is:</p>
<table class="xml_app">
<tbody><tr><td><i><b>MuxInfo</b> { </i> </td></tr>

<tr style="text-indent: 5%;"><td>fileName </td></tr>
<tr style="text-indent: 5%;"><td>streamFormat </td></tr>
<tr style="text-indent: 5%;"><td>GroupID </td></tr>
<tr style="text-indent: 5%;"><td>startTime </td></tr>
<tr style="text-indent: 5%;"><td>duration </td></tr>
<tr style="text-indent: 5%;"><td>frameRate </td></tr>
<tr style="text-indent: 5%;"><td>useDataReference </td></tr>
<tr style="text-indent: 5%;"><td>noFrameDrop </td></tr>
<tr style="text-indent: 5%;"><td>SBR_Type </td></tr>

<tr style="text-indent: 5%;"><td>compactSize </td></tr>
<tr style="text-indent: 5%;"><td>textNode </td></tr>
<tr style="text-indent: 5%;"><td>fontNode </td></tr>
<tr><td><i>}</i> </td></tr>
</tbody></table>

<h3>Semantics :</h3>
<p><i>fileName</i> : specifies location of stream to import. Optional for BIFS/OD streams, required for others. Supported formats are the same as those supported by <a href="doc_mp4box.php">MP4Box</a>:</p>

<ul>
<li>AVI, MPEG: syntax "src_filename" if only one video in movie, otherwise "src_filename#audio", "src_filename#video".</li>
<li>MP3, AAC-ADTS, JPG, PNG, SRT, SUB: syntax "src_filename".</li>
<li><a href="doc_nhnt.php">NHNT</a>: syntax "src_filename" where fileName is either the ".nhnt" or the ".media" file of the NHNT source.</li>
<li><a href="doc_nhnt.php#NHML">NHML</a>: fileName must be the NHML source file.</li>
<li>IsoMedia (MP4/3GP): syntax "src_filename" if only one track in file, "src_filename#trackID" where TrackID is the ID of the track to import from src_filename.</li>
</ul>
<p><i>streamFormat</i> : optional, one of "AVI", "MP3", "JPEG", "PNG", "MP4", "NHNT", "SRT" depending on input type. This should not be needed as MP4Box uses the file extension to figure out the media format, it is kept for old BT compatibility.</p>

<p><i>GroupID</i> : optional integer, specifies the multiplexing order in the final mp4. Media tracks are interleaved by groups, the group with the lowest ID being written first to disk. Using groups may greatly improve http streaming of the file.</p>
<p><i>startTime</i> : optional integer, specifies the DTS/CTS of the first sample - by default the first sample imported has CTS/DTS 0. Expressed in milliseconds.</p>
<p><i>duration</i> : optional integer, specifies run-time of media data (from source start) to import. Expressed in milliseconds.
<br/>
</p>
<p><i>Note</i> : When creating OCR tracks in MP4, you must use the muxInfo.duration to specify the desired duration of the OCR track, otherwise the OCR track won't have any associated duration and will never stop (looping not possible).
</p>

<p><i>useDataReference</i> : optional boolean, specifies that media data shall not be copied in the final MP4 but only referenced. This may not be supported depending on input data framing. cf '-dref' option in MP4Box.</p>
<p><i>frameRate</i> : overrides the source media framerate when possible (same as <i>MP4Box -fps</i>).</p>
<p><i>noFrameDrop</i> : optional boolean for AVI import only, specifies that video shall be imported at constant frame rate, eg non coded frames in AVI file shall be kept in MP4. cf '-nodrop' option in MP4Box.</p>
<p><i>SBR_Type</i> : optional string for AAC-SBR import only, either "implicit" or "explicit" - cf MP4Box -sbr and -sbrx options.</p>

<p><i>compactSize</i> : optional string, either "TRUE" or "FALSE", indicating if sample sizes should be stored in a compact way or not.</p>
<p><i>textNode, fontNode</i> : required string identifier for SRT importing, ignored for other streams. SRT importing is done by creating a BIFS animation stream carrying the text and fonts modifications (sample available in regression test suite). </p>
<ul>
<li>The textNode shall be the DEF identifier of the text node to modify (it can be a proto, but then it MUST have an MFString field named "string")</li>
<li>The fontNode may be ignored. If set, it shall be the DEF identifier of the font node to modify (it can be a proto, but then it MUST have an SFString field named "style")</li>
</ul>


<br/><br/>
<h1 id="XMT">XMT Format</h1>

XMT is the official textural description of MPEG-4 scenes. It is part of ISO/IEC 14496-11, and is quite similar to the X3D XML language. For more details on the XMT syntax, please have a look at <a href="auth_tutorial.php">the tutorial</a>.
 
<h2>Importing media with MP4Box and XMT</h2>
<p>There is no standard element in XMT-A allowing to describe multiplexing parameters for MP4. In order to do this MP4Box uses an XML representation of MuxInfo in the <i>StreamSource</i> sub elements (encoding hints) called <i>MP4MuxHints</i>. 
</p>
The complete syntax is:
<table class="xml_app">
<tbody><tr><td>&lt;<b>StreamSource</b> url="filename" &gt;</td></tr>

<tr style="text-indent: 5%;"><td>&lt;<b>MP4MuxHints</b> GroupID="..." startTime="..." duration="..." useDataReference="..." noFrameDrop="..." SBR_Type="..." frameRate="..." compactSize="..." textNode="..." fontNode="..." /&gt;</td></tr>
<tr><td>&lt;/<b>StreamSource</b>&gt;</td></tr>
</tbody></table>
<p>The streamFormat parameter is not represented in XMT-A.</p>

<p><i>Note</i> When decompressing an mp4 file to bt (XMT-A), a MuxInfo (MP4MuxHints) is automatically generated for all streams other than BIFS and OD in order to keep track of media location in the original MP4. You can then simply modify and reencode the BT or XMT file without specifying the media data location.
</p>

<br/><br/>

<h1 id="commands">MPEG-4 Scene Commands</h1>
We will now review the syntax of MPEG-4 scene commands in both BT and XMT-A formats. Please remember that BT and XMT languages are case sensitive.
<h2>Command declaration</h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>RAP AT time IN esID { .... }</td></tr></tbody></table>
<br/><i>RAP</i> and <i>IN esID</i> can be omitted most of the time.
<br/>

<i>XMT format</i>
<table class="xml_app"><tbody><tr><td>&lt;par begin="time" atES_ID="esID" isRAP="yes" &gt; ... &lt;/par&gt;</td></tr></tbody></table>
<br/><i>isRAP</i> and <i>atES_ID</i> can be omitted most of the time. 
<i>isRAP</i> is a GPAC extension.

<h2>Replacing a simple field</h2>

<i>BT format</i>
<table class="xml_app"><tbody><tr><td>REPLACE nodeName.fieldName BY newValue</td></tr></tbody></table>
<i>XMT format</i>
<table class="xml_app"><tbody><tr><td>&lt;Replace atNode="nodeName" atField="fieldName" value="newValue" /&gt;</td></tr></tbody></table>

<h2>Replacing a SFNode field</h2>
<i>BT format</i> 
<table class="xml_app"><tbody><tr><td>REPLACE nodeName.fieldName BY NodeDeclaration {... }</td></tr></tbody></table>
<i>XMT format</i>

<table class="xml_app">
<tbody><tr><td>&lt;Replace atNode="nodeName" atField="fieldName" &gt;</td></tr>
<tr><td>&lt;NodeXXX /&gt;</td></tr>
<tr><td>&lt;/Replace&gt;</td></tr>
</tbody></table>
<br/>
Note that the new node can be DEF'ed, or that a null node may be specified (<i>NULL</i>). 

<h2>Replacing a value in a multiple field </h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>REPLACE nodeName.fieldName[idx] BY newValue</td></tr></tbody></table>

<i>XMT format</i>
<table class="xml_app"><tbody><tr><td>&lt;Replace atNode="nodeName" atField="fieldName" position="idx" value="newValue" /&gt;</td></tr></tbody></table>
<br/>For XMT-A, <i>idx</i> can also take the special values 'BEGIN' and 
'END'.
<br/>
Replacement of a node in an MFNode field is the combinaison of both syntax

<h2>Replacing a multiple field </h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>REPLACE nodeName.fieldName BY [value1 ... valueN]</td></tr></tbody></table>
<table class="xml_app"><tbody><tr><td>REPLACE nodeName.fieldName BY [Node { ... } ... Node { ... }]</td></tr></tbody></table>

<i>XMT format</i>
<table class="xml_app"><tbody><tr><td>&lt;Replace atNode="nodeName" atField="fieldName" value="value1 ... valueN" /&gt;</td></tr></tbody></table>
<table class="xml_app">
<tbody><tr><td>&lt;Replace atNode="nodeName" atField="fieldName" &gt;</td></tr>
<tr><td>&lt;NodeXXX&gt;...&lt;/NodeXXX&gt; </td></tr>
<tr><td>&lt;NodeXXX&gt;...&lt;/NodeXXX&gt; </td></tr>

<tr><td>&lt;/Replace&gt;</td></tr>
</tbody></table>
<br/>
Replacement of a node in an MFNode field is the combinaison of both syntax

<h2>Deleting a node</h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>DELETE nodeName</td></tr></tbody></table>
<table class="xml_app"><tbody><tr><td>DELETE nodeName.fieldName</td></tr></tbody></table>
<i>XMT format</i>
<table class="xml_app"><tbody><tr><td>&lt;Delete atNode="nodeName" /&gt;</td></tr></tbody></table>

<table class="xml_app"><tbody><tr><td>&lt;Delete atNode="nodeName" atField="fieldName" /&gt;</td></tr></tbody></table>

<h2>Deleting a value in a multiple field</h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>DELETE nodeName.fieldName[idx]</td></tr></tbody></table>
<i>XMT format</i>
<table class="xml_app"><tbody><tr><td>&lt;Delete atNode="nodeName" atField="fieldName" position="idx" /&gt;</td></tr></tbody></table>
<br/>For XMT-A, <i>idx</i> can also take the special values 'BEGIN' and 
'END'.


<h2>Inserting a simple value in a multiple field</h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>INSERT AT nodeName.fieldName[idx] newValue</td></tr></tbody></table>
<table class="xml_app"><tbody><tr><td>APPEND TO nodeName.fieldName newValue</td></tr></tbody></table>
<i>XMT format</i>
<table class="xml_app"><tbody><tr><td>&lt;Insert atNode="nodeName" atField="fieldName" position="idx" value="newValue" /&gt;</td></tr></tbody></table>
<br/>For XMT-A, <i>idx</i> can also take the special values 'BEGIN' and 
'END'.

<h2>Inserting a node in a node list field</h2>

<i>BT format</i>
<table class="xml_app"><tbody><tr><td>INSERT AT nodeName.fieldName[idx] Node { }</td></tr></tbody></table>
<table class="xml_app"><tbody><tr><td>APPEND TO nodeName.fieldName Node { }</td></tr></tbody></table>
<i>XMT format</i>
<table class="xml_app">
<tbody><tr><td>&lt;Insert atNode="nodeName" atField="fieldName" position="idx" &gt;</td></tr>
<tr><td>&lt;NodeXXX&gt;...&lt;/NodeXXX&gt; </td></tr>
<tr><td>&lt;/Insert&gt; </td></tr>

</tbody></table>
<br/>For XMT-A, <i>idx</i> can also take the special values 'BEGIN' and 
'END'.


<h2>Replacing a route</h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>REPLACE ROUTE routeName BY nodeName1.fieldName1 TO nodeName2.fieldName2</td></tr></tbody></table>
<i>XMT format</i>
<table class="xml_app">
<tbody><tr><td>&lt;Replace atRoute="routeName"&gt;</td></tr>
<tr><td>&lt;ROUTE fromNode="nodeName1"  fromfield="fieldName1" toNode="nodeName2" toField="fieldName2" /&gt;</td></tr>

<tr><td>&lt;/Replace&gt;</td></tr>
</tbody></table>

<h2>Inserting a route</h2>
<i>BT format</i>
<table class="xml_app"><tbody><tr><td>INSERT ROUTE nodeName1.fieldName1 TO nodeName2.fieldName2</td></tr></tbody></table>
<i>XMT format</i>
<table class="xml_app">
<tbody><tr><td>&lt;Insert&gt;</td></tr>
<tr><td>&lt;ROUTE fromNode="nodeName1"  fromfield="fieldName1" toNode="nodeName2" toField="fieldName2" /&gt;</td></tr>

<tr><td>&lt;/Insert&gt;</td></tr>
</tbody></table>

<br/><br/>

<h1 id="tips">MP4Box tips and tricks with BT and XMT</h1>

<h2>Minimal Stream Descriptors for MP4Box (BT and XMT)</h2>
When encoding a BIFS or OD ES_Descriptor, MP4Box must find at least:
<ul>
<li>The decoderConfigDescriptor with the right streamType set</li>
</ul>
<p>When encoding an interaction stream descriptor, MP4Box must find at least:</p>
<ul>
<li>The decoderConfigDescriptor with the right streamType set</li>

<li>The UIConfig descriptor with the deviceName field. Note however that most of the time you will need to specify the ES_ID and the OCR_ES_ID of this descriptor to make sure events are not tied to any timeline.</li>
</ul>
<p>When encoding a regular ES_Descriptor, MP4Box must find at least:</p>
<ul>
<li>The MuxInfo descriptor with the fileName (StreamSource in XMT) set.</li>
</ul>

<p>
For systems streams, if the SLConfigDescriptor is not found, MP4Box uses a stream timescale of 1000, otherwise it uses <i>SLConfigDescriptor.timestampResolution</i> as stream timescale.
</p>
<p>

When the InitialObjectDescriptor is not found in the BT file, MP4Box will create one for you.
</p>

<h2>Non-linear Parsing</h2>
MP4Box can perform non-linear parsing of text files (BT,XMT,VRML,X3D/XML), in other words it understands usage of a node before its definition. This greatly simplifies
content authoring, in terms of complexity (for example, 2 conditionals referencing each-other) and readability (you don't have to declare things at specific places).
When encoding, node declarations are put back before node referencing (thus the decoded file will not look the same as the original one).

<h2>Multiple DEF handling</h2>
MP4Box can handle redefinition of nodes with the same DEF identifier (ex, "DEF AC AudioClip" and "DEF AC MovieTexture"). However when doing so, the nodes don't 
have the same binary IDs and only the first DEFed node can be safely used for field replacement. This feature should only be used with care to replace a whole node.

<h2>Forcing binary IDs</h2>
You can make MP4Box use your own binary identifiers for nodes and routes by using the syntax NXX for nodes or RXX for routes, where XX is the 
desired binary identifier (&gt;=0). In case a node in the scene already has the same binary ID, its ID is changed to a non-conflicting value and MP4Box will 
print a warning message indicating a node ID has been changed.

<h2>HTML Color Codes (BT only)</h2>

MP4Box can use HTML color codes in BT instead of the regular 3 floats SFColor. Since '#' is a comment character in BT/WRL, HTML color codes are signaled by the '$' character, 
for example <i>emissiveColor $FFAAB4</i>.

<h2>Simple Macro processor (BT only)</h2>
MP4Box can use simple macros in BT in order to help authoring. <b>A BT (or WRL) file with such macros cannot be understood by any other tools than MP4Box!</b>.
<p>The macro must be defined on a single line as follows:</p>
<table class="xml_app">
<tbody><tr><td><b>#define</b> MACRO_NAME REP LAC E MENT STR ING</td></tr>
</tbody></table>
<p>The BT parser will simply replace any occurence of the macro by its value.</p>

Here is an example of a BT macro:
<table class="xml_app">
<tbody><tr><td>#define MYAPP Appearance { material Material2D { emissiveColor 1 0 0 filled TRUE} }</td></tr>
<tr><td>#define MYCOORDS 200 20</td></tr>
<tr><td><br/></td></tr>
<tr><td>Transform2D {</td></tr>
<tr style="text-indent: 5%;"><td>translation MYCOORDS</td></tr>
<tr style="text-indent: 5%;"><td>children [</td></tr>
<tr style="text-indent: 10%;"><td>Shape {</td></tr>
<tr style="text-indent: 15%;"><td>appearance MYAPP</td></tr>

<tr style="text-indent: 15%;"><td>geometry Rectangle { size 20 20 }</td></tr>
<tr style="text-indent: 10%;"><td>}</td></tr>
<tr style="text-indent: 5%;"><td>]</td></tr>
<tr><td>}</td></tr>
</tbody></table>

<p>
</p>
		</div>
	</div>
<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>
