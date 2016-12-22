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
  <xsl:apply-templates select="wrapper/rightContent/rightBox2"/>
</xsl:template>

<xsl:template match="header/logo">
    <a href="index.php"><img src="images/HouseButton.png" alt="YN logo" width="35px" height="35px" style="float:left"></img></a>
    <img alt="YN logo" width="30px" height="30px">
      <xsl:attribute name="src"><xsl:value-of select="img"/></xsl:attribute>
    </img>
    <span style="font-size:25pt; color:white; font-weight:bold; ">
      <xsl:value-of select="title"/>
    </span>
</xsl:template>

<xsl:template match="wrapper/rightContent/rightBox2">
    <div class="rightBox2">
        <form action="{form/action}" method="post" autocomplete="on">
          <table>
            <th colspan="2"><span style="font-size:30px; font-weight:bold;"><xsl:value-of select="form/title"/></span></th>
            <tr>
              <td colspan="2"><xsl:value-of select="form/description"/></td>
            </tr>
            <tr>
              <td colspan="2"><input name="firstname" type="text" placeholder="First Name" class="signUp-input2"/></td>
            </tr>
            <tr>
              <td colspan="2"><input name="lastname" type="text" placeholder="Last Name" class="signUp-input2"/></td>
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
              <td></td>
              <td style="text-align:right; padding:10px;">
                <span style="padding-right:30px;">
                  <input type="radio" name="gender" value="male">Male</input>
                </span>
                <span style="text-align:right; margin-right:0;">
                  <input type="radio" name="gender" value="female">Female</input>
                </span>
              </td>
            </tr>
          </table>
          <input type="submit" name="Submit" class="signUp-submit" value="Register" style="float:right;display:block;"/>
        </form>
    </div>
</xsl:template>

</xsl:stylesheet>