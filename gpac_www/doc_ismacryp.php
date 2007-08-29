<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ISMACryp Support - GPAC Project on Advanced Content</title>
<link href="code/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fond">
<?php include_once("nav.php"); ?>
<!-- =================== ENTETE DE LA PAGE =========================================  -->
	<div id="Chapeau_court">
		<h1>
		MP4Box can be used to encrypt or decrypt media streams in a more or less format-agnostic manner, according to the ISMA E&A and OMA DRM 2.0 specifications. An XML language is used by MP4Box to get/set the encryption parameters. 
		</h1>
	</div>
<!-- =================== CORPS DE LA PAGE ============================================  -->
	<div id="Centre_court">
      <?php include_once("pack_left.php"); ?>
			<!-- =================== SECTION 2 ============  -->
			<div class="Col2">

		<div id="sous_menu">
<ul>
<li><a href="#samples">Sample Files</a></li>
<li><a href="#decrypt">Decryption</a></li>
<li><a href="#encrypt">Encryption</a></li>
</ul>
		</div>

<h1>Introduction</h1>
<p>As of version 0.2.4, GPAC supports the ISMA E&amp;A specification, better known as ISMACryp. This specification provides reliable transmission of encrypted media data with key signaling and cryptographic resynchronization in case of packet loss or random access.</p> 
<p>As of version 0.4.4, GPAC supports the OMA DRM PDCF specification available <a href="http://www.openmobilealliance.org/release_program/docs/CopyrightClick.asp?pck=DRM&file=V2_0-20060303-A/OMA-TS-DRM-DCF-V2_0-20060303-A.pdf"=>here</a>. This specification is derived from the ISMA E&A specification and OMA DRM PDCF files have a structure almost equivalent to ISMA protected files. GPAC does NOT support the ROAP protocol and other tools from the OMA DRM framework, but has a dedicated module API (still at beta stage) allowing for pluging a decryption agent in the streaming core.</p>
<p>Note that, unlike OMA DRM, ISMACryp does not mandate anything regarding how keys are to be distributed (hereafter refered to as <i>KMS - Key Management System</i>) which is up to the content provider/distributor. 
In other words, ISMACryp is concerned with cryptographic interoperability only, not rights management.</p>
<p>The current version of the specification uses for encryption the AES 128 bit algorithm in counter mode (AES-CTR). You do not need to know of all these things, the only thing you need to know 
is that ISMACryp uses a 128 bit key and a 64 bit salt, which would need to be fetched by the client at some point for decryption.
<br>In this document, the key and the salt will simply be refered to as <i>key</i> unless specified otherwise.</p>

<p>One interesting feature of the ISMACryp specification is that it allows for selective encryption, in other words you may decide to encrypt only specific samples in the media track rather than the whole media. 
Selective encryption will reduce the complexity of the decryption process, and may also be very nice in demonstrations - for example, encrypting only I-frames in a video
can give very nice effects ... </p>
<p>If you are familiar with MPEG-4 IPMPX specification, you must be aware that ISMACryp selective encryption is different from IPMP-X one: in ISMACryp, selective encryption means whether or not a sample 
is encrypted while in IPMP-X selective encryption usually means whether specific bitstream syntax elements (motion vectors, DCT, audio codewords, etc) are encrypted or not.</p>
<p>Another interesting feature of ISMACryp is the possibility to <i>roll</i> keys, eg have more than one key needed for stream decryption. ISMACryp provides for sample-based synchronization 
of keys and media which is much more reliable than a clock-based synchronization (relying on media/key timestamps). GPAC does not currently support usage of multiple keys in ISMACryp, only one key can be used in the stream lifetime.</p>
<p>
<i>Note </i>: <b>!! GPAC does not currently support usage of ISMACryp with MPEG-4 AVC/H264 video !! </b>
</p>
<br>
<h1 ID="encrypt">ISMACryp authoring</h1>

<p>In order to encrypt an MP4 file, MP4Box will need a specific file containing all cryptographic information, usually refered to as <i>drm_file</i> in MP4Box documentation. This file is an XML document containing
mainly key information, KMS information, encryption instructions and eventually MPEG-4 IPMP(X) indications. The syntax is very basic and will likelly be subject to many changes in the near future.</p>
<p>Just like any XML file, the file must begin with the usual xml header. The file encoding SHALL BE UTF-8. The file is then a collection of <i>ISMACrypTrack</i> elements placed at under an <i>ISMACryp</i> document root. Each element describes all ISMACryp information needed to encrypt a given track.</p>

<h2>XML Syntax</h2>
<table class="xml_app">
<tbody><tr><td>&lt;ISMACryp&gt;</td></tr>

<tr><td>&lt;ISMACrypTrack trackID="..." key="..." salt="..." scheme_URI="..." kms_URI="..." selectiveType="..." ipmpType="..." ipmpDescriptorID="..." /&gt;</td></tr>
<tr><td>&lt;/ISMACryp&gt;</td></tr>
</tbody></table>

<h2>Semantics</h2>
<p>
<ul>
<li><i>trackID</i> : specifies the track ID to encrypt. This is a mandatory field, not specifing it will result in an error.</li>
<li><i>key</i> : the AES-128 bit key to use. The key must be specified as an 32 bytes hex string, like <b>0x2b7e151628aed2a6abf7158809cf4f3c</b>. This is a mandatory field, not specifing it or using an improper length will result in an error.</li>

<li><i>salt</i> : the 64 bit salt key to use for the counter mode. The salting key must be specified as an 16 bytes hex string, like <b>0xf8f9fafbfcfdfeff</b>. This is a mandatory field, not specifing it or using an improper length will result in an error.</li>
<li><i>scheme_URI</i> : the URI of the scheme used for protection (for example the cryptographic tool provider). The default value is <b>urn:gpac:isma:encryption_scheme</b>. This URI is added to the track meta-data.</li>
<li><i>kms_URI</i> : the URI of the key management system. This is the URI to which an ISMACryp client will request the keys. This URI is added to the track meta-data. Apart from regular URLs and URIs, two specific values are interpreted by GPAC:
<ul>
<li><b>self</b> : the keys will be written in the media track meta-data using base64 encoding. This is mainly useful for testing :)</li>

<li><b>file</b> : the URI will be set to this drm_file name as given to MP4Box - in other words, if you indicate a relative path for the drm_file, then the relative path will be used for the KMS URI.</li>
</ul>
</li>
<li><i>selectiveType</i> : specifies how selective encryption is to be used. The possible values are:
<ul>
<li><b>None</b> : no selective encryption, all samples encrypted (this is the default behavior).</li>
<li><b>RAP</b> : only Random Access Samples (key frames) will be encrypted. If all media samples are RAPs, this defaults to <i>None</i>.</li>

<li><b>Non-RAP</b> : only non-Random Access Samples (non-key frames) will be encrypted. If all media samples are RAPs, this defaults to <i>None</i>.</li>
<li><b>Rand</b> : random selection of samples to encrypt is performed.</li>
<li><b>X</b> : encrypts the first sample every <i>X</i> samples. <i>X</i> must be an integer greater than 2.</li>

<li><b>RandX</b> : encrypts one random samples out of <i>X</i> samples. <i>X</i> must be an integer greater than 2.</li>
</ul>
</li>
<li><i>ipmpType</i> : specifies what kind of MPEG-4 IPMP signaling must be used for this media. The possible values are:
<ul>
<li><b>None</b> : no MPEG-4 IPMP signaling.</li>

<li><b>IPMP</b> : use MPEG-4 IPMP (<i>the hooks</i>) signaling.</li>
<li><b>IPMPX</b> : use MPEG-4 IPMP-X (<i>ISO-IEC 14496-13</i> signaling.</li>
</ul>
</li>
<li><i>ipmpDescriptorID</i> : specifies the IPMP(X) descriptor ID for this media. If not set, <b>defaults to the media track 1-based index</b>. Ignored when IPMP(X) signaling is not used.</li>

</ul>
</p>

<h1 ID="decrypt">Decrypting a file with GPAC</h1>
<p>MP4Box/GPAC players will attempt to load the keys from a KMS URI as follows:</p>
<ul>
<li>if kms_URI begins with <b>(key)</b>, the keys are Base64 encoded in the track and can be fetched. This corresponds to the case of special value <i>kms_URI="self"</i> when encrypting.</li>
<li>if the scheme URI is GPAC default one (<b>urn:gpac:isma:encryption_scheme</b>) and the kms_URI points to a file (ONLY LOCAL FILES FOR MP4BOX), the key and salt will be fetched from this file. This corresponds to the case of special value <i>kms_URI="file"</i> when encrypting. In this case only the <i>trackID</i>, <i>key</i> and <i>salt</i> attributes of the <b>ISMACrypTrack</b> element are needed.</li>

<li>if kms_URI is <i>AudioKey</i> or <i>VideoKey</i>, KMS is assumed to be MPEG4IP one and the file ~/.kms_data is checked (cf <a href="http://www.mpeg4ip.net">MPEG4IP</a> documentation).</li>
</ul>
<br>In all other cases:
<ul>
<li>For MP4Box: You will need to provide a drm_file for decryption (eg, <i>MP4Box -decrypt drm_file myfile.mp4</i>).</li>

<li>For GPAC client: key fetching will fail and the stream will be decoded WITHOUT being decrypted.</li>
</ul>


<br>
<h1 ID="encrypt">OMA DRM authoring</h1>

<p>In order to encrypt a 3GP/MP4 file into a PDCF file, MP4Box uses the same process as ISMA encryption, only the drm file syntax changes.</p>
<p>Just like any XML file, the file must begin with the usual xml header. The file encoding SHALL BE UTF-8. The file is then a collection of <i>OMATrack</i> elements placed under an <i>OMADRM</i> document root (currently ignored). Each element describes all OMA DRM information needed to encrypt a given track. An <i>OMATrack</i> element may have children describing the optional textual headers defined in OMA DRM 2.0. Each textual header is inserted as is during OMA encryption, so be carefull not to specify twice the same header.</p>

<h2>XML Syntax</h2>
<table class="xml_app">
<tbody><tr><td>&lt;OMADRM&gt;</td></tr>

<tr><td>&lt;OMATrack trackID="..." key="..." selectiveType="..." rightsIssuerURL="..." contentID="..." transactionID="..." &gt;</td></tr>
<tr><td>&lt;OMATextHeader&gt;textual header&lt;/OMATextHeader&gt;</td></tr>
<tr><td>&lt;/OMATrack&gt;</td></tr>
<tr><td>&lt;/OMADRM&gt;</td></tr>
</tbody></table>

<h2>Semantics</h2>
<p>
<ul>
<li><i>trackID</i> : specifies the track ID to encrypt. This is a mandatory field, not specifing it will result in an error.</li>
<li><i>key</i> : the AES-128 bit key to use. The key must be specified as an 32 bytes hex string, like <b>0x2b7e151628aed2a6abf7158809cf4f3c</b>. This is a mandatory field, not specifing it or using an improper length will result in an error.</li>
<li><i>rightsIssuerURL</i> : the URL of the OMA DRM licence server. This is the URL to which an OMA client will request the keys using the ROAP protocol.</li>
<li><i>contentID</i> : a string identifier for the content, passed during ROAP exchanges.</li>
<li><i>transactionID</i> : a string identifier for the transaction, passed during ROAP exchanges.</li>
<li><i>selectiveType</i> : specifies how selective encryption is to be used. The possible values are:
<ul>
<li><b>None</b> : no selective encryption, all samples encrypted (this is the default behavior).</li>
<li><b>RAP</b> : only Random Access Samples (key frames) will be encrypted. If all media samples are RAPs, this defaults to <i>None</i>.</li>

<li><b>Non-RAP</b> : only non-Random Access Samples (non-key frames) will be encrypted. If all media samples are RAPs, this defaults to <i>None</i>.</li>
<li><b>Rand</b> : random selection of samples to encrypt is performed.</li>
<li><b>X</b> : encrypts the first sample every <i>X</i> samples. <i>X</i> must be an integer greater than 2.</li>

<li><b>RandX</b> : encrypts one random samples out of <i>X</i> samples. <i>X</i> must be an integer greater than 2.</li>
</ul>
</li>
</ul>
</p>
<br/>

<h1 ID="samples">Sample GPAC drm files</h1>
<p>The following example shows how to encrypt a file with one track, using selective encryption of RAP samples, embedded keys and no IPMP signaling.</p>

<table class="xml_app">
<tbody><tr><td>&lt;?xml version="1.0" encoding="UTF-8" &gt;</td></tr>
<tr><td>&lt;ISMACryp&gt;</td></tr>
<tr><td style="text-indent: 5%;">&lt;ISMACrypTrack trackID="1" key="0x2b7e151628aed2a6abf7158809cf4f3c" salt="0xf8f9fafbfcfdfeff" selectiveType="RAP" KMS_URI="self"/&gt;</td></tr>
<tr><td>&lt;/ISMACryp&gt;</td></tr>
</tbody></table>


<p>The following example shows how to encrypt a file with one track, using random encryption over 30 samples, using the source file as KMS URI and IPMP-X signaling.</p>

<table class="xml_app">
<tbody><tr><td>&lt;?xml version="1.0" encoding="UTF-8" &gt;</td></tr>
<tr><td>&lt;ISMACryp&gt;</td></tr>
<tr><td style="text-indent: 5%;">&lt;ISMACrypTrack trackID="1" key="0x2b7e151628aed2a6abf7158809cf4f3c" salt="0xf8f9fafbfcfdfeff" selectiveType="Rand30" KMS_URI="file" ipmpType="IPMPX" ipmpDescriptorID="20" /&gt;</td></tr>
<tr><td>&lt;/ISMACryp&gt;</td></tr>
</tbody></table>

<p>The following example shows how to encrypt a file with one track, without slective encryption, a KMS URI and no IPMP signaling.</p>

<table class="xml_app">
<tbody><tr><td>&lt;?xml version="1.0" encoding="UTF-8" &gt;</td></tr>
<tr><td>&lt;ISMACryp&gt;</td></tr>
<tr><td style="text-indent: 5%;">&lt;ISMACrypTrack trackID="1" key="0x2b7e151628aed2a6abf7158809cf4f3c" salt="0xf8f9fafbfcfdfeff" selectiveType="None" KMS_URI="https://gpac.sourceforge.net/kms/file.xml" /&gt;</td></tr>
<tr><td>&lt;/ISMACryp&gt;</td></tr>
</tbody></table>



<p>The following example shows how to encrypt a file with one track, without slective encryption, using OMA DRM.</p>

<table class="xml_app">
<tbody><tr><td>&lt;?xml version="1.0" encoding="UTF-8" &gt;</td></tr>
<tr><td>&lt;OMADRM&gt;</td></tr>
<tr><td style="text-indent: 5%;">&lt;OMATrack trackID="1" key="0x2b7e151628aed2a6abf7158809cf4f3c" selectiveType="None" rightsIssuerURL="https://gpac.sourceforge.net/kms" contentID="WatchMe1984" transactionID="14fd12zd3q" &gt;</td></tr>
<tr><td>&lt;OMATextHeader&gt;Preview=instant;http://other.content.com/sonaive&lt;/OMATextHeader&gt;</td></tr>
<tr><td>&lt;/OMATrack&gt;</td></tr>
<tr><td>&lt;/OMADRM&gt;</td></tr>
</tbody></table>

      </div>
	</div>

<?php $mod_date="\$Date: 2007-08-29 17:55:47 $"; ?><?php include_once("bas.php"); ?><!-- =================== FIN CADRE DE LA PAGE =========================================  -->
</div>
</body>
</html>

