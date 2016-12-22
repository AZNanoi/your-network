<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:rss="http://purl.org/rss/1.0/"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
                xmlns="http://www.w3.org/1999/xhtml"
                xmlns:media="http://search.yahoo.com/mrss/"
                xmlns:yn="http://xml.csc.kth.se/~marang/DM2517/your-network/yn-comment.dtd"
                version="1.0">
<xsl:output doctype-system="string" indent="yes" encoding="UTF-8"/>
<xsl:template match="rss">
	<html>
		<head>
		</head>
		<body>
			<xsl:for-each select=".//item">
        <xsl:apply-templates select="."/>
      </xsl:for-each>
		</body>
	</html>
</xsl:template>
<xsl:template match="item">
  <xsl:variable name="com_ID" select="guid"/>
	<div id="{$com_ID}" class='comment'>
    <div class="c-pic f-l">
      <a href="https://www.facebook.com/{yn:authorID}" target="blank"><img src='{media:thumbnail/@url}'/></a>
    </div>
    <div class="com-lb">
      <a href="https://www.facebook.com/{yn:authorID}" target="blank" class='font-link'><xsl:value-of select="author"/></a><span class='font-time'> (<xsl:value-of select="dc:date"/>)</span>
      <xsl:choose>
        <xsl:when test="substring($com_ID,1,2)='yn'">
          <img src='images/yn_icon.png' style="opacity:0.45;"/>
        </xsl:when>
        <xsl:otherwise>
          <img src='images/fb_icon.png' style="opacity:0.45;"/>
        </xsl:otherwise>
      </xsl:choose>
      <div>
        <span class='font-standard'><xsl:value-of select="description"/></span><br/>
        <xsl:if test="media:content">
          <img src='{media:content/@url}' alt="image" width='100px' height='100px'/>
        </xsl:if>
      </div>
      <div class="c-button f-l" style='width:80px; padding-bottom:5px;'>
        <div class="f-l">
        <img src='images/profile-photo-b.png'/>
        </div>
        <div class="f-l" style='padding:0px 7px;'>
          <span class='font-link f1'>0</span>
        </div>
      </div>
      <div class="c-button f-l">
        <div class="f-l">
        <img src='images/comment-button-b.png'/>
        </div>
        <div class="f-l" style='padding:0px 7px;'>
          <span class='font-link f1'>0</span>
        </div>
      </div>
    </div>
  </div>
</xsl:template>


</xsl:stylesheet>