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
<?php //header("Content-type: text/xml; charset=utf-8"); ?>
<?php //<!DOCTYPE root SYSTEM "http://xml.csc.kth.se/~marang/DM2517/your-network/yn-index-dtd.dtd"> ?>
<root>
  <head>
    <icon>images/icon_logo.png</icon>
    <title>YourNetwork</title>
  </head>
  <body>
    <header>
      <logo>
        <img>images/logo.png</img>
        <title>YourNetwork</title>
      </logo>
      <login>
        <email>Email:</email>
        <password>Password:</password>
        <fgLogin href="forgot-login.php">Forgot your login name/password?</fgLogin>
      </login>
    </header>
    
    <wrapper>
      <leftContent>
        <img>images/network.png</img>
        <form>
            <submit>submit</submit>
            <search>Search friends</search>
        </form>
        <users>
          <description>People who are using YourNetwork.</description>
          <person>
            <name>Ah Zau Marang</name>
            <img>images/f1.jpg</img>
          </person>
          <person>
            <name>YourNetwork</name>
            <img>images/logo.png</img>
          </person>
        </users>
      </leftContent>
      <rightContent>
        <rightBox1>
          <title>Log in with:</title>
          <button>
            <name>Log in with facebook</name>
            <img>images/fb_logo.png</img>
          </button>
          <button>
            <name>Log in with twitter</name>
            <img>images/tw_logo.png</img>
          </button>
          <button>
            <name>Log in with instagram</name>
            <img>images/inst_logo.png</img>
          </button>
        </rightBox1>
        <rightBox2>
          <form>
            <title>Sign Up</title>
            <action>registerYN.php</action>
            <description>Register and get an accout at Your Network.</description>
          </form>
        </rightBox2>
      </rightContent>
    </wrapper>
    <footer>
      <nav>
        <li>Sign Up</li>
        <li>log In</li>
        <li>Find Friends</li>
        <li>Help</li>
      </nav>
      <description>Your Network is a platform which allows you to gather all your social accounts and controll them all from one place. 
      It also includes other features such as music player which you can create your own playists by URL address or uploading your own songs, allowing you share them with friends.
      This is the apps which will effectively facilitate your daily life and make you enjoy your day more. While using this site, you agree to have read and accepted our terms of use, cookie and privacy policy. 
      </description>
      <copyright>Copyright 2015 by Your Network. All Rights Reserved.</copyright>
    </footer>
  </body>
</root>
<?php 
  if (isset($_GET["register"])==true){
    include 'index-postfix-register.php';
  }else{
    include 'index-postfix.php';
  }
?>
<?php endif; ?>