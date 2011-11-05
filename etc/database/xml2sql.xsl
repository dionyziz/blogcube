<?xml version="1.0" encoding="ISO-8859-1"?>
<!--
	Developer: Dionyziz
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<html>
			<head>
				<title>
					XML2SQL: BlogCube 
					<xsl:value-of select="/blogcube/@version" /> 
					"<xsl:value-of select="/blogcube/@codename" />"
				</title>
				<style>
					body {
						width: 700px;
						margin: auto;
						font-family: Helvetica;
						margin-top: 5px;
						margin-bottom: 5px;
					}
					span.databasename, span.tablename {
						color: darkred;
						font-family: "Courier New";
						font-weight: bold;
					}
					span.fieldname {
						color: blue;
					}
					span.notnull {
						color: lightgray;
					}
					span.fieldtype {
						color: green;
					}
					span.sqlquery {
						font-variant: small-caps;
						background-color: lightyellow;
					}
					span.fielddefault, span.enum {
						font-style: italic;
						color: midnightblue;
					}
					span.field {
						display: block;
						padding-left: 20px;
					}
					span.semicolon {
						font-weight: bold;
					}
					span.header {
						display: block;
						background-color: aliceblue;
						padding: 5px 5px 5px 5px;
						border: 1px solid midnightblue;
						font-size: 90%;
					}
					div.viewsource {
						float:right;
						font-size:80%;
					}
				</style>
			</head>
			<body>
				<span class="header">
					<div class="viewsource">
						To view the XML source, use View &gt; Page Source
					</div>
					BlogCube <a href="https://blogcube.net/repos/etc/database/xml2sql.xsl">XML2SQL Script</a><br />
					Copyright (c) 2006, <a href="http://blogcube.net/">BlogCube</a><br />
					Developer: <a href="mailto:dionyziz@blogcube.net">Dionyziz</a><br />
				</span><br />
				
				<xsl:for-each select="/blogcube/database">
					<xsl:sort select="@name" />
					<span class="sqlquery">SELECT DATABASE</span> `<span class="databasename"><xsl:value-of select="@name" /></span>`<span class="semicolor">;</span><br /><br />
					<xsl:for-each select="table">
						<xsl:sort select="@name" />
						<span class="sqlquery">CREATE TABLE</span> `<span class="tablename"><xsl:value-of select="@name" /></span>` (<br />
							<xsl:for-each select="field"><span class="field">
								`<span class="fieldname"><xsl:value-of select="@name" /></span>`
								<xsl:choose>
									<xsl:when test="@type='INT'">
										<span class="fieldtype">int(11)</span> <span class="notnull"> NOT NULL </span>
										<xsl:if test="not(@autoincrement='yes')">
											<span class="fielddefault">default '0'</span>
										</xsl:if>
										<xsl:if test="@autoincrement='yes'">
											auto_increment
										</xsl:if>
									</xsl:when>
									<xsl:when test="@type='TEXT'">
										<span class="fieldtype">text</span> <span class="notnull"> NOT NULL </span>
									</xsl:when>
									<xsl:when test="@type='LONGTEXT'">
										<span class="fieldtype">longtext</span> <span class="notnull"> NOT NULL </span>
									</xsl:when>
									<xsl:when test="@type='DATE'">
										<span class="fieldtype">date</span> <span class="notnull"> NOT NULL </span> <span class="fielddefault">default '0000-00-00'</span>
									</xsl:when>
									<xsl:when test="@type='BLOB'">
										<span class="fieldtype">BLOB</span> <span class="notnull"> NOT NULL </span>
									</xsl:when>
									<xsl:when test="@type='MEDIUMBLOB'">
										<span class="fieldtype">MEDIUMBLOB</span> <span class="notnull"> NOT NULL </span>
									</xsl:when>
									<xsl:when test="@type='DATETIME' or @type='TIMESTAMP'">
										<span class="fieldtype">datetime</span> <span class="notnull"> NOT NULL </span> <span class="fielddefault">default '0000-00-00 00:00:00'</span>
									</xsl:when>
									<xsl:when test="@type='ENUM'">
										<span class="fieldtype">ENUM</span>(<span class="enum">
										<xsl:value-of select="@values" />
										</span>)
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="@type" />
									</xsl:otherwise>
								</xsl:choose>
								,
							</span></xsl:for-each>
							<xsl:for-each select="field[@primarykey='yes']"><span class="field">
								<span class="sqlquery">PRIMARY KEY</span> (
								`<span class="fieldname"><xsl:value-of select="@name" /></span>`
								)
								<xsl:if test="position()&lt;last()">
									,
								</xsl:if>
							</span></xsl:for-each>
						)<span class="semicolon">;</span><br /><br />
					</xsl:for-each>
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
