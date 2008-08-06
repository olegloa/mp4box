<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:gpac="urn:enst:gpac" >
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>    
    <xsl:template match="support">
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        	<head>
        		<title>SVG Support in GPAC</title>
				    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        		<link rel="stylesheet" type="text/css" href="gpac_svg_support.css"/>
        	</head>
            <body>

              <div id="content">
                
                <!--xsl:call-template name="nav"/-->
                
                <!--h1 id="top" >SVG Support in GPAC</h1-->
                <xsl:copy-of select="comment[@apply-to='all']/p"/>               
                                
                <xsl:call-template name="nav"/>
                <h2 id="element_support" >Supported elements</h2> 
                <xsl:copy-of select="comment[@apply-to='element']/p"/>               

                <h3 id="tiny_element_support" >SVG Tiny 1.2</h3> 
        				<table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="element[@supported='yes' and @profile='Tiny']"/>
        					<xsl:apply-templates select="element[@supported='partial' and @profile='Tiny']"/>
        					<xsl:apply-templates select="element[@supported='no' and @profile='Tiny']"/>
                </table>

                <h3 id="full_element_support" >SVG Full 1.1</h3> 
                <xsl:call-template name="nav"/>
        				<table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="element[@supported='yes' and @profile='Full']"/>
        					<xsl:apply-templates select="element[@supported='partial' and @profile='Full']"/>
        					<xsl:apply-templates select="element[@supported='no' and @profile='Full']"/>
                </table>

                <h2 id="attribute_support" >Supported attributes</h2>                
                <xsl:call-template name="nav"/>
                <xsl:copy-of select="comment[@apply-to='attribute']/p"/>               
                <h3 id="tiny_attribute_support" >SVG Tiny 1.2</h3> 
        				<table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="attribute[@supported='yes' and @profile='Tiny']"/>
        					<xsl:apply-templates select="attribute[@supported='partial' and @profile='Tiny']"/>
        					<xsl:apply-templates select="attribute[@supported='no' and @profile='Tiny']"/>
                </table>

                <h3 id="full_attribute_support" >SVG Full 1.1</h3> 
                <xsl:call-template name="nav"/>
        				<table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="attribute[@supported='yes' and @profile='Full']"/>
        					<xsl:apply-templates select="attribute[@supported='partial' and @profile='Full']"/>
        					<xsl:apply-templates select="attribute[@supported='no' and @profile='Full']"/>
                </table>

                <h2 id="property_support" >Supported properties</h2>                
                <xsl:call-template name="nav"/>
                <xsl:copy-of  select="comment[@apply-to='property']/p"/>               
                <h3 id="tiny_property_support" >SVG Tiny 1.2</h3> 
                <table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="property[@supported='yes' and @profile='Tiny']"/>
        					<xsl:apply-templates select="property[@supported='partial' and @profile='Tiny']"/>
        					<xsl:apply-templates select="property[@supported='no' and @profile='Tiny']"/>
                </table>

                <h3 id="full_property_support" >SVG Full 1.1</h3> 
                <xsl:call-template name="nav"/>
                <table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="property[@supported='yes' and @profile='Full']"/>
        					<xsl:apply-templates select="property[@supported='partial' and @profile='Full']"/>
        					<xsl:apply-templates select="property[@supported='no' and @profile='Full']"/>
                </table>

                <h2 id="script_support" >List of supported scripting interfaces</h2> 
                <table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="api[@supported='yes']"/>
        					<xsl:apply-templates select="api[@supported='partial']"/>
        					<xsl:apply-templates select="api[@supported='no']"/>
                </table>
                <!--h2 id="laser_support" >Supported LASeR elements</h2> 
                <xsl:call-template name="nav"/>
                <xsl:copy-of select="comment[@apply-to='LASeR']/p"/>               
                <h3 id="laser_v1_support" >LASeR v1</h3> 
        				<table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="element[@supported='yes' and @profile='LASeR' and @version='1']"/>
        					<xsl:apply-templates select="element[@supported='partial' and @profile='LASeR'and @version='1']"/>
        					<xsl:apply-templates select="element[@supported='no' and @profile='LASeR'and @version='1']"/>
                </table>

                <h3 id="laser_v2_support" >LASeR v2</h3> 
        				<table>
        				  <xsl:call-template name="table-header"/>
        					<xsl:apply-templates select="element[@supported='yes' and @profile='LASeR' and @version='2']"/>
        					<xsl:apply-templates select="element[@supported='partial' and @profile='LASeR' and @version='2']"/>
        					<xsl:apply-templates select="element[@supported='no' and @profile='LASeR' and @version='2']"/>
                </table-->

              </div>
            </body>
    	</html>
    </xsl:template>
    
    <xsl:template name="table-header">
			<thead>
				<tr>
					<th>Name</th>
					<th>Supported</th>
					<th>Comments</th>
					<!--th>Exemple</th>
					<th>Bugs</th-->
				</tr>
			</thead>
    </xsl:template>

    <xsl:template match="element | property | attribute | api ">
    	<tbody>
    		<tr>
    			<td class="{@supported}"><xsl:value-of select="@name"/></td>
    			<td class="{@supported}"><xsl:value-of select="@supported"/></td>
    			<td class="{@supported}"><xsl:copy-of select="text()"/></td>
    			<!--td class="{@supported}"><xsl:apply-templates select="example"/></td>
    			<td class="{@supported}"><xsl:apply-templates select="bug"/></td-->
    		</tr>
    	</tbody>
    </xsl:template>
    
    <xsl:template match="example">
      <a href="{@url}"><xsl:value-of select="position()"/></a><br/>
    </xsl:template>

    <xsl:template name="nav">
      <div id="nav">
        <a href="#top">Top</a> | 
        <a href="#element_support">Element Support</a>
        [<a href="#tiny_element_support">Tiny 1.2</a> | <a href="#full_element_support">Full 1.1</a>] | 
        <a href="#attribute_support">Attribute Support</a> 
        [<a href="#tiny_attribute_support">Tiny 1.2</a> | <a href="#full_attribute_support">Full 1.1</a>] |
        <a href="#property_support">Property Support</a>
        [<a href="#tiny_property_support">Tiny 1.2</a> | <a href="#full_property_support">Full 1.1</a>] |
        <a href="#script_support">Scripting Support</a>
        <!--a href="#laser_support">LASeR Support</a> 
        [<a href="#laser_v1_support">Version 1</a> | <a href="#laser_v2_support">Version 2</a>]-->
      </div>
    </xsl:template>
</xsl:stylesheet>

