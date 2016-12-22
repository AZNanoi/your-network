<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/1999/xhtml"
                version="1.0">
<xsl:output method="html" doctype-system="" indent="yes" encoding="UTF-8"/>
<xsl:template match="root">
  <html>
    <head>
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
  <link rel="stylesheet" type="text/css" href="layout.css"/>
  <script type="text/javascript" src="fb-login.js"><xsl:value-of select="''"/></script>
</xsl:template>

<xsl:template match="body">
  <div id="header">
    <div class="header-wrap">
      <xsl:apply-templates select="header"/>
    </div>
  </div>
  <div id="content-bg"><xsl:value-of select="''"/></div>
  <div id="wrapper">
    <xsl:apply-templates select="wrapper"/>
  </div>
  <xsl:apply-templates select="footer"/>
</xsl:template>

<xsl:template match="header">
  <div class="logo">
    <img alt="YN logo" width="50px" height="50px">
      <xsl:attribute name="src"><xsl:value-of select="logo/img"/></xsl:attribute>
    </img>
    <span style="font-size:30pt; color:white; vertical-align: 50%; padding:15px 0 0 5px; font-family:Myriad Pro; position:absolute;">
      <xsl:value-of select="logo/title"/>
    </span>
  </div>
  <div class="login">
    <form action="checkYNlogin.php" autocomplete="off" method="post">
      <table type="border-collapse: collapse;">
        <tr>
          <td width="210px"><xsl:value-of select="login/email"/></td>
          <td width="190px"><xsl:value-of select="login/password"/></td>
          <td width="60px" rowspan="3"><input type="submit" value="Log In" class="submit-rounded-button"/></td>
        </tr>
        <tr>
          <td><input name="userEmail" type="email" required="required" class="login-input"/></td>
          <td><input name="userPassword" type="password" required="required" class="login-input"/></td>
        </tr>
        <tr>
          <td colspan="2"><a href="{login/fgLogin/@href}" class="fgLogin"><xsl:value-of select="login/fgLogin"/></a></td>
        </tr>
      </table>
    </form>
  </div>
</xsl:template>

<xsl:template match="wrapper">
  <xsl:apply-templates select="leftContent"/>
  <div class="rightContent">
    <xsl:apply-templates select="rightContent"/>
  </div>
</xsl:template>

<xsl:template match="rightContent">
  <xsl:apply-templates select="rightBox1"/>
  <xsl:apply-templates select="rightBox2"/>
</xsl:template>

<xsl:template match="leftContent">
  <div class="leftContent">
    <div class="leftBox1">
      <img src="{img}" alt="YN design" width="400px" height="400px" class="centerContent"></img>
    </div>
    <form>
      <table>
        <tr>
          <td><input type="{form/submit}" value="" class="search-submit"></input></td>
          <td><input name="search" value="" class="search-input" placeholder="{form/search}"></input></td>
        </tr>
      </table>
    </form>
    <xsl:value-of select="users/description"/>
    <div class="leftBox2">
      <xsl:for-each select="users/person">
        <img src="{img}" alt="user" width="70px" height="70px"></img><xsl:value-of select="name"/>
      </xsl:for-each>
    </div>
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
                <td><img src="{button[1]/img}" alt="fb icon" width="30px" height="30px" ></img></td>
                <td><xsl:value-of select="button[1]/name"/></td>
              </tr>
            </table>
          </button>
        </td>
        <td width="200px">
          <button type="button" class="tw-button">
            <table>
              <tr>
                <td><img src="{button[2]/img}" alt="tw icon" width="40px" height="30px" ></img></td>
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
                <td><img src="{button[3]/img}" alt="instagram icon" width="30px" height="30px" ></img></td>
                <td><xsl:value-of select="button[3]/name"/></td>
              </tr>
            </table>
          </button>
        </td>
      </tr>
    </table>
  </div>
</xsl:template>

<xsl:template match="rightBox2">
    <div class="rightBox2">
        <form action="{form/action}" method="post" autocomplete="on">
          <table>
            <th colspan="2"><h2 style="font-size:30px; font-weight:bold;"><xsl:value-of select="form/title"/></h2></th>
            <tr>
              <td colspan="2"><xsl:value-of select="form/description"/></td>
            </tr>
            <tr>
              <td width="200"><input name="firstname" type="text" placeholder="First Name" class="signUp-input"/></td>
              <td width="200" style="float:right;"><input name="lastname" type="text" placeholder="Last Name" class="signUp-input"/></td>
            </tr>
            <tr>
              <td colspan="2"><input type="email" name="email" placeholder="Email" class="signUp-input2" autocomplete="off"/></td>
            </tr>
            <tr>
              <td colspan="2"><input name="phone" type="tel" placeholder="Phone(optional)" value="" class="signUp-input2"/></td>
            </tr>
            <tr>
              <td colspan="2"><input name="password" type="password" placeholder="Password" class="signUp-input2" autocomplete="off"/></td>
            </tr>
            <tr>
              <td colspan="2"><input name="reEnterPassword" type="password" placeholder="Re-enter Password" class="signUp-input2" autocomplete="off"/></td>
            </tr>
            <tr>
              <td style="float:right;">Birthday:</td>
              <td style="text-align:right;">
                <select name="year" id="year">
                  <option value="null">Year</option>
                </select>
                <select name="month" onChange="changeDate(this.options[selectedIndex].value);">
                  <option value="null">Month</option>
                  <option value="1">January</option>
                  <option value="2">February</option>
                  <option value="3">March</option>
                  <option value="4">April</option>
                  <option value="5">May</option>
                  <option value="6">June</option>
                  <option value="7">July</option>
                  <option value="8">August</option>
                  <option value="9">September</option> 
                  <option value="10">October</option>
                  <option value="11">November</option>
                  <option value="12">December</option>
                </select>
                <select name="day" id="day">
                  <option value="null">Day</option>
                </select>
                <script language="JavaScript" type="text/javascript">
                  function changeDate(i){
                    var e = document.getElementById('day');
                    while(e.length>0)
                      e.remove(e.length-1);
                      var j=-1;
                      if(i=="null")
                        k=0;
                      else if(i==2)
                        k=28;
                      else if(i==4||i==6||i==9||i==11)
                        k=30;
                      else
                        k=31;
                      while(j ++&lt; k){
                        var s=document.createElement('option');
                        var e=document.getElementById('day');
                        if(j==0){
                          s.text="Day";
                          s.value="null";
                          try{
                            e.add(s,null);}
                          catch(ex){
                            e.add(s);}
                        }
                        else{
                          s.text=j;
                          s.value=j;
                          try{
                            e.add(s,null);}
                          catch(ex){
                            e.add(s);}
                        }
                      }
                    }
                </script>
                <script language="JavaScript" type="text/javascript">
                  y = 2016;
                  while (y-->1908){
                    var s = document.createElement('option');
                    var e = document.getElementById('year');
                    s.text=y;
                    s.value=y;
                    try{
                      e.add(s,null);}
                    catch(ex){
                      e.add(s);}
                  }
                </script>
              </td>
            </tr>
            <tr>
              <td width="200px" ></td>
              <td width="200px" style="text-align:right; padding:10px;">
                <span style="padding-right:30px;">
                  <input type="radio" name="gender" value="male">Male</input>
                </span>
                <span style="text-align:right; margin-right:0;">
                  <input type="radio" name="gender" value="female">Female</input>
                </span>
              </td>
            </tr>
          </table>
          <table>
            <tr>
              <td width="300px"></td>
              <td width="120px" height="55px">
                <input type="submit" name="Submit" class="signUp-submit" value="Register" style="float:right;"/>
              </td>
            </tr>
          </table>
        </form>
    </div>
</xsl:template>

<xsl:template match="footer">
    <div id="footer">
      <div class="footer-nav">
        <ul>
          <xsl:for-each select="nav/li">
            <xsl:choose>
              <xsl:when test="position() = last()">
                <li><a href="" style="border-right: 0px solid gray;"><xsl:value-of select="."/></a></li>
              </xsl:when>
              <xsl:otherwise>
                <li><a href=""><xsl:value-of select="."/></a></li>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </ul>
      </div>
      <div style="letter-spacing:3px; text-align:center; font-size:12px; margin-top:-10px;">
        <p><xsl:value-of select="description"/></p>
        <br/>
        <span style="font-size:9px; color:gray;">
          <xsl:value-of select="copyright"/>
        </span>
      </div>
    </div>
</xsl:template>

</xsl:stylesheet>