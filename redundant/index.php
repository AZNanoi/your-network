<?php
  session_start();
  // Display all warnings
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  ob_start();
?>
<?php if (isset($_SESSION["yn-userName"]) && isset($_SESSION["userID"])): ?>
  <?php include 'yn-feed.php'; ?>
  <?php include 'yn-postfix.php';?>
<?php elseif (isset($_SESSION["fb_access_token"])): ?>
  <?php include 'fb-feed-rss.php'; ?>
<?php else: ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <link rel="shortcut icon" href="images/icon_logo.png"/>
  <title>Your Network</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="layout.css"/>
  <script type="text/javascript" src="fb-login.js"></script>
  </head>
  <body>
    <div id="header">
      <div class="header-wrap">
        <div class="logo">
          <img src="images/logo.png" width="70px" height="70px"></img>
          <span style="font-size:30pt; color:white; font-weight:bold; vertical-align: 50%;">Your Network</span>
        </div>
        <div class="login">
        <form action="checkYNlogin.php" autocomplete="off" method="post">
          <table type="border-collapse: collapse;">
            <tr>
              <td width="210px">Email:</td>
              <td width="190px">Password:</td>
              <td width="60px" rowspan="3"><input type="submit" value="Log In" class="submit-rounded-button"/></td>
            </tr>
            <tr>
              <td><input name="userEmail" type="email" required="required" class="login-input"/></td>
              <td><input name="userPassword" type="password" required="required" class="login-input"/></td>
            </tr>
            <tr>
              <td colspan="2"><a href="forgot-login.php" class="fgLogin">Forgot your login name/password?</a></td>
            </tr>
          </table>
        </form>
        </div>
      </div>
    </div>
    <div id="content-bg"></div>
    <div id="wrapper">
      <div class="leftContent">
        <div class="leftBox1"><img src="images/network.png" width="400px" height="400px" class="centerContent"></img></div>
        <form>
        <table>
          <tr>
            <td><input type="submit" value="" class="search-submit"></input></td>
            <td><input name="search" value="" class="search-input" placeholder="Search friends"></input></td>
          </tr>
        </table>
        </form>
        People who are using Your Network.
        <div class="leftBox2">
          <img src="images/logo.png" width="70px" height="70px"></img>Your Network
          <img src="images/f1.jpg" width="70px" height="70px"></img>Ah Zau Marang
          <img src="images/NETWORKING.jpg" width="70px" height="70px"></img>Networking
          <img src="images/NETWORKING.jpg" width="70px" height="70px"></img>Networking
          <img src="images/NETWORKING.jpg" width="70px" height="70px"></img>Networking
        </div>
      </div>
      <div class="rightContent">
        <div class="rightBox1">
          <table>
            <tr>
              <td colspan="3" style="text-align:center;"><span style="font-size:20px; font-family:helvetica,arial,sans-serif;">Log in with:</span></td>
            </tr>
            <tr height="60px">
              <td width="200px">
                <button type="button" class="fb-button" onclick="fb_login();">
                  <table>
                    <tr>
                      <td><img src="images/fb_logo.png" width="30px" height="30px" ></img></td>
                      <td>Log in with facebook</td>
                    </tr>
                  </table>
                </button>
              </td>
              <td width="200px">
                <button type="button" class="tw-button">
                  <table>
                    <tr>
                      <td><img src="images/tw_logo.png" width="40px" height="30px" ></img></td>
                      <td>Log in with twitter</td>
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
                      <td><img src="images/inst_logo.png" width="30px" height="30px" ></img></td>
                      <td>Log in with instagram</td>
                    </tr>
                  </table>
                </button>
              </td>
            </tr>
          </table>
        </div>
        <div class="rightBox2">
        <form action="registerYN.php" method="post" autocomplete="on">
          <table>
            <th colspan="2"><span style="font-size:30px; font-weight:bold;">Sign Up</span></th>
            <tr>
              <td colspan="2">Register and get an accout at Your Network.</td>
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
                      while(j++<k){
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
              <td width="300px">
                <?php
                if(isset($_GET['error'])==true){
                  echo "<div style=text-align:center;'><span style='color:#ff0000;'>Password does not match the confirm password! Try again!</span></div>";
                }
                ?>
              </td>
              <td width="120px" height="55px">
                <input type="submit" name="Submit" class="signUp-submit" value="Register" style="float:right;"/>
              </td>
            </tr>
          </table>
        </form>
        </div>
      </div>
    </div>
    <div id="footer">
      <div class="footer-nav">
        <ul>
          <li><a href="">Sign Up</a></li>
          <li><a href="">log In</a></li>
          <li><a href="">Find Friends</a></li>
          <li><a href="" style="border-right: 0px solid gray;">Help</a></li>
        </ul>
      </div>
      <div style="letter-spacing:3px; text-align:center; font-size:12px; margin-top:-10px;">Your Network is a platform which allows you to gather all your social accounts and controll them all from one place. 
      It also includes other features such as music player which you can create your own playists by URL address or uploading your own songs, allowing you share them with friends.
      This is the apps which will effectively facilitate your daily life and make you enjoy your day more. While using this site, you agree to have read and accepted our terms of use, cookie and privacy policy. 
      <br/><span style="font-size:9px; color:gray;">Copyright 2015 by Your Network. All Rights Reserved.</span></div>
    </div>
  </body>
  </html>
<?php endif; ?>