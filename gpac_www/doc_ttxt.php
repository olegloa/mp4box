<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>TTXT Format Documentation - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
The 3GPP consortium has defined a standard for text streaming, independent of any scene description such as SMIL, SVG or BIFS: 3GPP Timed Text. MP4Box supports this standard and uses its own textual format to describe a streaming text session.
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre_court">
      <?php include_once("pack_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">

		<div id="sous_menu">
<ul>
<li><a href="#samples">Samples</a></li>
<li><a href="#impl">Playback</a></li>
<li><a href="#encode">Encoding</a></li>
<li><a href="#ttxt">TTXT Format</a></li>
<li><a href="#overview">Overview</a></li>
</ul>
</div>

<h1>Foreword</h1>

<p>As of version 0.2.3, GPAC supports 3GPP timed text, also known as MPEG-4 Streaming Text. These standards provide a way to stream text and styles applying to text such as font size, color, highlighting.
This is a very convenient way to encode subtitles, tickers, etc,... independantly of the scene description language (MPEG-4, X3D, SVG/SMIL, ...), hence providing reusability of the text data.
</p>
<p>For regular MPEG-4/VRML static text, please refer to the MPEG-4 tutorial or any VRML tutorials available on the internet.</p>

<h1 id="overview">Overview of a timed text stream</h1>
The 3GPP timed text specification is called <a href="http://www.3gpp.org/ftp/Specs/archive/26_series/26.245/26245-600.zip">3GPP TS 26.245</a> (3GPP web site) and will 
give you in-depth knowledge of this format. If you don't want to read and understand all the technical details, there are some important things to know about timed text streams, 
so let's review them.


<p>
A text stream can be devided in two major sections, very much like most modern audio/video codecs: the configuration data for the decoder and the data samples. As you can 
guess, this is quite different from most subtitling formats, where subtitles configuration is left up to the player (color, font size, positioning, etc..).
<br/>Authoring 3GPP text streams therefore requires carefull design as to which font size shall be picked, color used and similar. 
<br/>The terminal may not changed these settings as these have been chosen by the author, in other words the text stream becomes an element of the final content just like video or audio tracks (think of it as 
DVD subtitles whose styles and position are part of the DVD and cannot be changed by the DVD player).
</p>

<h2>Decoder Configuration</h2>
The configuration data of a text stream provides information about postioning in the visual display, the list of ALL fonts used in the text stream and default styles (color, bold/italic, background color).

<h2>Positioning</h2>
Just like any video or image media, a text stream has size information, usually called <i>Text Track</i> size. This size defines the maximum width and height used by the text stream. 
All text will be drawn within this defined rectangle which will also act as a clipper (i.e., nothing will appear outside it).
This rectangle can be positioned in the final display through horizontal and vertical offsets, allowing for instance to define a text track of 320*50 pixels at the bottom of a 320*240 video.

<h2>Default Styles</h2>
Samples in a text stream usually use the default properties defined in the decoder configuration. Since some styles may be used quite often in a text stream (for example, italic), the decoder configuration data
contains a list of default styles, and samples then refer to a specific style in this list. This is known as the <i>TextSampleEntry</i> in 3GPP. A single TextSampleEntry contains:
<br/>
<ul>
<li>A list of font names ("Times", "Arial", etc) with an associated ID - fonts are ALWAYS referenced by IDs in a 3GPP text stream. A compliant decoder shall be able to understand the default font names "Serif", "Sans-serif" and "Monospace".</li>
<li>Text Styles: default font ID, font size, font styles (italic, bold, underlined), text color in RGBA colorspace.</li>
<li>Text Position: in a 3GPP text stream, the text may be drawn in a given rectangle within the stream main display rectangle. This is known as the <i>TextBox</i> in 3GPP. Any text justification 
is always performed against this TextBox, not against the main display rectangle.</li>
<li>Other styles: background color in RGBA colorspace, horizontal and vertical justification, text scrolling modes, etc..</li>
</ul>

<h2>Text Samples</h2>
Text samples convey text data and, optionally, temporary styles to apply to this text. The text data can be encoded in UTF-8 or UTF-16, and may be empty (typically used for subtitles). 
The temporary styles are known as <i>TextModifiers</i> in 3GPP, and are of two kinds: those impacting the entire text, and those impacting only a sub-string of the text, identified through 
starting and ending characters.
<h3>Sub-String Text Modifiers</h3>
<ul>
<li>Styles: overrides text style (font name, size and style, text color) for a substring of the text. All text characters not covered by temporary modifiers use the default text style from the decoder configuration.</li>
<li>Highlight: specifies a given substring shall be highlighted.</li>
<li>Hyper Text: specifies a given substring shall be treated as an hyper-link, and gives associated URL.</li>
<li>Blinking: specifies a given substring shall blink - blinking frequency is left up to the decoder.</li>

<li>Karaoke: specifies how highlighting shall be dynamically applied to text characters for a karaoke style.</li>
</ul>
<h3>Complete String Text Modifiers</h3>
<ul>
<li>Text Box: specifies a temporary box where this text sample is displayed.</li>
<li>Highlight Color: specifies the highlight color when highlighting is used. When no highlighting color is given the decoder should use reverse video.</li>
<li>Wrap: specifies text wrapping shall be done on the text string if needed.</li>
<li>Scroll Delay: specifies the amount of time a text shall be presented statically to the user during a scroll.</li>
</ul>


<h1 id="ttxt">GPAC TTXT Format</h1>
There is no official textual representation of a text stream. Moreover, the specification relies on IsoMedia knowledge for most structure descriptions. In order to help authoring text streams, 
an XML format has been developed in GPAC, called <b>TTXT</b> for timed-text - the extension used being <i>.ttxt</i>. This format is not related in any way to any scene description language to keep the
timed text authoring a standalone step in the authoring process.

<h2>Obtaining a sample TTXT file</h2>
As said above, this format has been developed in GPAC, and you will likely not find any tool other than MP4Box supporting this format for quite some time. To get a sample file, you have two possibilities:
<ul>
<li>Find a 3GP file with a text track, and run <i>MP4Box -ttxt trackID file.3gp</i>. This will dump the text track in TTXT format.</li>
<li>Find an SRT or SUB subtitle file, and run <i>MP4Box -ttxt file.srt</i>. This will convert the subtitles in TTXT format.</li>

</ul>

<h2>Syntax of a TTXT file</h2>
The TTXT format is an XML description of the timed text stream, and as such MUST begin with the usual XML header with encoding hints - only UTF-8 has currently been tested. 
The text stream is encapsulated in a single element at the root of the document, called <i>TextStream</i>. This element has a single defined attribute <i>version</i> identifying the format version - the current and only defined version is "1.0".
The TextStream element must have one and only one <i>TextStreamHeader</i> child and has as many <i>TextSample</i> children as desired.
<br/>Note: All coordinates are specified in pixels, 
framebuffer-coordinate like: 

<ul>
<li>X-axis increases from left to right, with origin (0) on left edge.</li>
<li>Y-axis increases from top to bottom, with origin (0) on top edge.</li>
</ul>

<h2>TextStreamHeader</h2>
The TextStreamHeader describes all 3GPP text stream decoder configuration. It must contain at least one TextSampleDescription element. 

<h3>Syntax</h3>
<table class="xml_app">
<tbody><tr><td>&lt;TextStreamHeader width="..." height="..." translation_x="..." translation_y="..." layer="..." /&gt;</td></tr>
<tr><td>...</td></tr>

<tr><td>&lt;/TextStreamHeader&gt;</td></tr>
</tbody></table>

<h3>Semantics</h3>
<ul>
<li><i>width</i> : defines text stream width in pixels (type: unsigned integer). Default value is 400 pixels.</li>
<li><i>height</i> : defines text stream height in pixels (type: unsigned integer). Default value is 80 pixels.</li>
<li><i>translation_x</i> : defines text stream horizontal translation in main display in pixels (type: signed integer). Default value is 0 pixels.</li>

<li><i>translation_y</i> : defines text stream vertical translation in main display in pixels (type: signed integer). Default value is 0 pixels.</li>
<li><i>layer</i> : defines text stream z-order (type: signed short). This is only needed when composing several text streams in a single presentation: more negative layer values are towards the viewer. Default value is 0.</li>
</ul>

<h2>TextSampleDescription</h2>
The TextSampleDescription element may be present as many times as desired in a TextStreamHeader. It defines the default styles text samples may refer to in the stream. 
<br/>The TextSampleDescription may also contain zero or one 
<i>FontTable</i>, <i>TextBox</i> and <i>Style</i>.


<h3>Syntax</h3>
<table class="xml_app">
<tbody><tr><td>&lt;TextSampleDescription horizontalJustification="..." verticalJustification="..." backColor="..." verticalText="..." fillTextRegion="..." continuousKaraoke="..." scroll="..." scrollMode="..." &gt;</td></tr>
<tr><td>...</td></tr>
<tr><td>&lt;/TextSampleDescription&gt;</td></tr>
</tbody></table>

<h3>Semantics</h3>
<ul>
<li><i>horizontalJustification</i> : specifies horizontal text justification in the TextBox. Possible values are <i>"center"</i>, <i>"left"</i> and <i>"right"</i>. Default value is <i>"left"</i>.</li>

<li><i>verticalJustification</i> : specifies vertical text justification in the TextBox. Possible values are <i>"center"</i>, <i>"top"</i> and <i>"bottom"</i>. Default value is <i>"bottom"</i>.</li>
<li><i>backColor</i> : specifies the color to use for background fill. Expressed as space-separated, hexadecimal R, G, B and A values - for example, semi-transparent red is "ff 00 00 7f". Default value is "00 00 00 00" (no back color).</li>

<li><i>verticalText</i> : specifies whether the text shall be drawn vertically or not.  Possible values are <i>"yes"</i> and <i>"no"</i>. Default value is <i>"no"</i>. </li>
<li><i>fillTextRegion</i> : specifies whether the entire text stream rectangle shall be filled with the backColor, or only the TextBox. Possible values are <i>"yes"</i> and <i>"no"</i>. Default value is <i>"no"</i>. </li>

<li><i>continuousKaraoke</i> : specifies whether karaoke is continous (all characters from begining of text samples are highlighted) or not. Possible values are <i>"yes"</i> and <i>"no"</i>. Default value is <i>"no"</i>.</li>
<li><i>scroll</i> : specifies text scrolling mode. Possible values are <i>"In"</i> (text is scrolling in), <i>"Out"</i> (text is scrolling out), <i>"InOut"</i> (text is scrolling in then out) and <i>"None"</i> (text is not being scrolled). Default value is <i>"None"</i>.</li>

<li><i>scrollMode</i> : specifies text scrolling mode. Possible values are <i>"Credits"</i> (scroll from bottom to top), <i>"Marquee"</i> (scroll from right to left), <i>"Down"</i> (scroll from top to bottom) and <i>"Right"</i> (scroll from left to right). Default value is <i>"Credits"</i>.</li>

</ul>

<h2>FontTable</h2>
The FontTable element specifies all fonts used by samples refering to this stream description. There should be one and only one FontTable element in a TextSampleDescription. If not found, the default "Serif" font will be used with an ID of 1. 

<h3>Syntax</h3>
<table class="xml_app">
<tbody><tr><td>&lt;FontTable&gt;</td></tr>
<tr><td>&lt;FontTableEntry fontID="..." fontName="..." /&gt;</td></tr>
<tr><td>&lt;/FontTable&gt;</td></tr>
</tbody></table>

<h3>Semantics</h3>

The FontTable has no attribute, it is a collection of <i>FontTableEntry</i> elements. The FontTableEntry element has two attributes:
<ul>
<li><i>fontName</i> : specifies the font name to use. A terminal shall understand the names <i>Serif</i>, <i>Sans-Serif</i> and <i>Monospace</i>.</li>
<li><i>fontID</i> : specifies the associated ID.</li>

</ul>
<b>NOTE</b> : There are no default values for this element, omitting values will result in undefined stream behaviour.

<h2>TextBox</h2>
The TextBox element specifies where the text should be drawn within the stream main display rectangle. There should be one TextBox in a TextSampleDescription element. If the text box is not found or empty, the entire display rectangle will be used for the default text box. 
<h3>Syntax</h3>
<table class="xml_app"><tbody><tr><td>
&lt;TextBox top="..." left="..." bottom="..." right="..." /&gt;
</td></tr></tbody></table>

<h3>Semantics</h3>
<ul>

<li><i>top</i> : specifies vertical offset from stream main display rectangle top edge (type: signed integer). Default value: 0 pixels.</li>
<li><i>left</i> : specifies horizontal offset from stream main display rectangle left edge (type: signed integer). Default value: 0 pixels.</li>
<li><i>bottom</i> : specifies vertical extend of the text box (type: signed integer). Default value: 0 pixels.</li>
<li><i>right</i> : specifies horizontal extend of the text box (type: signed integer). Default value: 0 pixels.</li>
</ul>

<h3>Style</h3>
The Style element specifies the default text style for this sample description. There should be one and only one Style element in a TextSampleDescription. If not found, all default values are used with a fontID of 1. 
<h3>Syntax</h3>
<table class="xml_app"><tbody><tr><td>
&lt;Style fromChar="..." toChar="..." fontID="..." fontSize="..." color="..." styles="..." /&gt;
</td></tr></tbody></table>

<h3>Semantics</h3>
<ul>
<li><i>fromChar</i> : specifies the first character (0-based index) in the string this style applies to (type: unsigned integer). Default value is 0. Note: This field MUST be set to 0 when used in TextSampleDescription.</li>

<li><i>toChar</i> : specifies the first character (0-based index) in the string this style stops applying to (type: unsigned integer). Default value is 0. Note: This field MUST be set to 0 when used in TextSampleDescription.</li>
<li><i>fontID</i> : specifies the ID of the font to use, as defined in the FontTable element (type: unsigned integer). Default value: 1.</li>
<li><i>fontSize</i> : specifies the font size to use (type: unsigned integer). Default value: 18.</li>
<li><i>color</i> : specifies the color to use for text. Expressed as space-separated, hexadecimal R, G, B and A values - for example, semi-transparent red is "ff 00 00 7f". Default value is "ff ff ff ff" (opaque white).</li>
<li><i>styles</i> : specifies the font styles to use (type: string). If set, it shall consist of a space-separated list of the following styles: <i>"Bold"</i> (text is in bold), <i>"Italic"</i> (text is in italic) and <i>"Underlined"</i> (text is underlined). Default value: no style ("").</li>

</ul>


<h2>TextSample</h2>
The TextSample element describes a given text sample and all its associated style. Most of the time, no children elements will be specified. 
<h3>Syntax</h3>
<table class="xml_app">
<tbody><tr><td>&lt;TextSample sampleTime="..." sampleDescriptionIndex="..." text="..." scrollDelay="..." highlightColor="..." wrap="..." &gt;</td></tr>
<tr><td>...</td></tr>
<tr><td>&lt;/TextSample&gt; </td></tr></tbody></table>

<h3>Semantics</h3>
<ul>
<li><i>sampleTime</i> : specifies the time at which the text sample shall be displayed. Time can be expressed in the usual "hh:mm:ss.ms" in hours, minutes, seconds and milliseconds format, or as a double-precision number in second unit. Default value is "0" second.</li>
<li><i>sampleDescriptionIndex</i> : specifies the TextSampleDescription this sample refered to. This is a 1-based index, value 0 is forbidden. Default value is "1", which means you do not need to specify this field most of the time if the main sample description is the first one. </li>
<li><i>text</i> : the text data itself. String shall be formatted as a series of lines, each line being enclosed by single-quote characters ('). This text MUST follow XML text encoding conventions. Currently only UTF-8 text is supported.</li>
<li><i>scrollDelay</i> : specifies the scrollDelay in seconds (type: double-precision number. This is the delay after a scroll In and/or before scroll Out. Default value "0".</li>

<li><i>highlightColor</i> : specifies the highlight color to be used by any enclosed highlighted strings (including karaoke). Expressed as other colors (see <i>Styles</i> above). Default value "0 0 0 0".</li>
<li><i>wrap</i> : specifies whether text should be wraped or not. Possible values are "Automatic" (text is wrapped by terminal) or "None" (text is not wrapped). Default value is "None".</li>
</ul>
<p>When text style modification or any special text effects are desired, they are described through children elements of the TextSample element. It is invalid to specify several modifiers of the same type (for instance two hyper-links) on the same character, you must make sure 
modifiers of a same type do not have overlapping character ranges.</p>

<h2>Style</h2>

See above for semantics.

<h2>TextBox</h2>
See above for semantics. At most one TextBox modifier shall be set per sample.

<h2>Highlight</h2> 
The Highlight modifier indicates a given substring shall be highlighted. 
<h3>Syntax</h3>
<table class="xml_app"><tbody><tr><td>
&lt;Highlight fromChar="..." toChar="..."/&gt;
</td></tr></tbody></table>
<h3>Semantics</h3>
<ul>
<li><i>fromChar</i> : specifies the first character (0-based index) in the string this style applies to (type: unsigned integer). Default value is 0. </li>

<li><i>toChar</i> : specifies the first character (0-based index) in the string this style stops applying to (type: unsigned integer). Default value is 0. </li>
</ul>
<h2>Blinking</h2>
The Blinking modifier indicates a given substring shall blink. The blinking rate is up to the terminal.
<h3>Syntax</h3>
<table class="xml_app"><tbody><tr><td>
&lt;Blinking fromChar="..." toChar="..."/&gt;
</td></tr></tbody></table>
<h3>Semantics</h3>
<ul>

<li><i>fromChar</i> : specifies the first character (0-based index) in the string this style applies to (type: unsigned integer). Default value is 0. </li>
<li><i>toChar</i> : specifies the first character (0-based index) in the string this style stops applying to (type: unsigned integer). Default value is 0. </li>
</ul>
<h2>HyperLink</h2>
The Hyperlink modifier indicates that a given substring shall be treated as a hyper link.
<h3>Syntax</h3>
<table class="xml_app"><tbody><tr><td>
&lt;Hyperlink fromChar="..." toChar="..." URL="..." URLToolTip="..."/&gt;

</td></tr></tbody></table>
<h3>Semantics</h3>
<ul>
<li><i>fromChar</i> : specifies the first character (0-based index) in the string this style applies to (type: unsigned integer). Default value is 0. </li>
<li><i>toChar</i> : specifies the first character (0-based index) in the string this style stops applying to (type: unsigned integer). Default value is 0. </li>
<li><i>URL</i> : URL this HyperLink is linking to. UTF-8 only format supported.</li>
<li><i>URLToolTip</i> : "tooltip" presented to the user if supported by decoder. UTF-8 only format supported.</li>

</ul>

<h2>Karaoke</h2>
The Karaoke element indicates dynamic highlighting, or karaoke, applies to this sample. At most one Karaoke element shall be set per TextSample. The Karaoke is defined as a sequence of highlighting times, all sepcified as children element of the Karaoke through the <i>KaraokeRange</i> element.
<h3>Syntax</h3>
<table class="xml_app">
<tbody><tr><td>&lt;Karaoke startTime="..."&gt;</td></tr>
<tr><td>&lt;KaraokeRange fromChar="..." toChar="..." endTime="..."/&gt;</td></tr>
<tr><td>&lt; /Karaoke &gt;</td></tr>

</tbody></table>
<h3>Semantics</h3>
<ul>
<li><i>startTime</i> : specifies the highlighting start time offset in seconds from the begining of the sample (type: double-precision number, default value "0").</li>
<li><i>fromChar</i> : specifies the first character (0-based index) in the string this style applies to (type: unsigned integer). Default value is 0.</li>
<li><i>toChar</i> : specifies the first character (0-based index) in the string this style stops applying to (type: unsigned integer). Default value is 0. </li>
<li><i>endTime</i> : specifies the highlighting end time offset in seconds from the begining of the sample (type: double-precision number, default value "0"). The start time of a karaoke segment is the end time of the previous one, or the start time of the <i>Karaoke</i> element.</li>

</ul>

<h1 id="encode">Encoding of text streams</h1>
Using text streams in a 3GPP file or outisde the MPEG-4 Systems scene description is quite straightforward. All you need to do is add the text stream to your (existing) file with MP4Box. For example, adding a text track and an AVI file into a new 3GP file can be done in a single call:
<br/><i>MP4Box -3gp -add movie.avi -add text.ttxt dest.3gp</i>. 
<p>Note that you can use either SRT/SUB subtitles or TTXT files with the <i>-add</i> option. Other subtitles formats are currently not supported.</p>
<p>Using text streams within MPEG-4 scene description is much more tricky (is it?). You will add an ObjectDescriptor to your scene as you add an audio/video object, specifying the file name with the usual MuxInfo. <b>Note</b>: when using SRT/SUB, make sure you don't have <i>TextNode</i> specified in the MuxInfo, as this ALWAYS triggers subtitles to BIFS conversion and not 3GPP timed text.</p>

<p>The second step is controlling the new text object in the same way you control an audio or visual object. This is done through the AnimationStream node. Instead of controlling a BIFS stream, you can start/stop/play the text stream with this node.</p>

<h1 id="impl">GPAC Implementation</h1>
<ul>
<li>During playback, GPAC does not support dynamic highlighting (Karaoke) nor soft text wrapping (wrapping is only done at newline characters).</li>
<li>GPAC should support vertical text drawing and alignment, but this has not be really tested yet.</li>
<li>GPAC should support UTF-16 text in decoding and hinting, but DOES NOT SUPPORT UTF-16 text at encoding time yet.</li>
<li>GPAC should support text streams placed below the main video or on its right, but cannot currently handle text streams placed above or on the left of the main video.</li>
</ul>

<h1 id="samples">Sample TTXT Files</h1>

The following files are taken from a 3GPP test suite and are pure translations to TTXT with MP4Box. They should give you a good overview of the format and help you author your own test tracks.
<p>
Download GPAC TTXT sample streams <a href="downloads/ttxt_sample_streams.zip">.zip</a> <a href="downloads/ttxt_sample_streams.tar.gz">.tgz</a>
</p>

<p><i>This is version 1.0 documentation of GPAC TimedText stream textual 
format. Comments, details and any correction are welcome</i></p>

 		</div>
	</div>

<?php $mod_date="\$Date: 2007-08-30 13:19:19 $"; ?>
<?php include_once("bas.php"); ?>
<!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>

