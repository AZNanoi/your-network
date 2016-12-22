<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/1999/xhtml"
                version="1.0">
<xsl:output method="html"
  doctype-public="-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
  doctype-system="http://www.wapforum.org/DTD/xhtml-mobile10.dtd" indent="yes" encoding="UTF-8"/>
<xsl:template match="root">
  <html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <xsl:apply-templates select="head"/>
    </head>
    <body>
      <xsl:apply-templates select="body"/>
    </body>
  </html>
</xsl:template>

<xsl:template match="head">
  <link rel="shortcut icon">
    <xsl:attribute name="href"><xsl:value-of select="icon"/></xsl:attribute>
  </link>
  <title><xsl:value-of select="title"/></title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" type="text/css" href="layout-mobile.css"/>
  <script type="text/javascript" src="fb-login.js"><xsl:value-of select="''"/></script>
</xsl:template>

<xsl:template match="body">
  <div id="header">
    <xsl:apply-templates select="header/logo"/>
  </div>
  <xsl:apply-templates select="header/login"/>
  <xsl:apply-templates select="wrapper/rightContent/rightBox1"/>
  <a href="index.php?register=1" class="fgLogin">Register and get an accout at Your Network?</a>
</xsl:template>

<xsl:template match="header/logo">
    <img alt="YN logo" width="30px" height="30px">
      <xsl:attribute name="src"><xsl:value-of select="img"/></xsl:attribute>
    </img>
    <span style="font-size:25pt; color:white; font-weight:bold; ">
      <xsl:value-of select="title"/>
    </span>
</xsl:template>

<xsl:template match="header/login">
  <div class="login">
    <form action="checkYNlogin.php" autocomplete="off" method="post" style="width: 90%;text-align: center;margin-left: 5%;">
      <input name="userEmail" type="email" placeholder="Email" required="required" class="login-input"/>
      <input name="userPassword" type="password" placeholder="Password" required="required" class="login-input"/>
      <input type="submit" value="Log In" class="submit-rounded-button" style="float:right;"/>
    </form>
    <a href="forgot-login-mobile.php" class="fgLogin"><xsl:value-of select="fgLogin"/></a>
  </div>
</xsl:template>

<xsl:template match="rightBox1">
  <div class="rightBox1">
    <table>
      <tr>
        <td colspan="3" style="text-align:center;"><span style="font-size:20px; font-family:helvetica,arial,sans-serif;"><xsl:value-of select="title"/></span></td>
      </tr>
      <tr height="60px">
        <td width="200px">
          <button type="button" class="fb-button" onclick="fb_login();">
            <table>
              <tr>
                <td><img src="{button[1]/img}" alt="fb icon" width="25px" height="25px" ></img></td>
                <td><xsl:value-of select="button[1]/name"/></td>
              </tr>
            </table>
          </button>
        </td>
        <td width="200px">
          <button type="button" class="tw-button">
            <table>
              <tr>
                <td><img src="{button[2]/img}" alt="tw icon" width="35px" height="25px" ></img></td>
                <td><xsl:value-of select="button[2]/name"/></td>
              </tr>
            </table>
          </button>
        </td>
      </tr>
      <tr height="60px">
        <td>
          <button type="button" class="inst-button">
            <table>
              <tr>
                <td><img src="{button[3]/img}" alt="instagram icon" width="25px" height="25px" ></img></td>
                <td><xsl:value-of select="button[3]/name"/></td>
              </tr>
            </table>
          </button>
        </td>
      </tr>
    </table>
  </div>
</xsl:template>

</xsl:stylesheet>