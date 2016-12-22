<?php ob_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Your Network</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="layout.css"/>

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
    <div class="centerContent" style="width:500px; height:270px; border-radius: 25px;
    border:1px solid gray; text-align:center; padding:10px; vertical-align: middle;">

    <?php
    require("dbc.php");
    session_start();

    if ($_GET['code']) {
      $get_recoverEmail = $_GET['recoverEmail'];
      $get_code = $_GET['code'];
      $query = mysqli_query($link, "SELECT * FROM profiles WHERE email='$get_recoverEmail'");
      while ($row=mysqli_fetch_assoc($query)) {
        $db_code = $row['passreset'];
        $db_email = $row['email'];
      }
      if($get_recoverEmail==$db_email && $get_code==$db_code){
        echo "
          <form action='passreset-complete.php?code=$get_code' method='post'>
            <h1>Reset Your Password</h1>
            <p>
              Enter a new password and click on the button to update your password!
            </p>
            <table style='margin:auto;'>
              <tr>
                <td><input type='password' name='newpassword' class='search-input' placeholder='Enter a new password'></input></td>
                <td><input type='hidden' name='recoverEmail' value='$db_email'></input></td>
              </tr>
              <tr>
                <td><input type='password' name='newpassword2' class='search-input' placeholder='Re-enter the password'></input></td>
              </tr>
              <tr>
                <td style='width:65px; height:55px; float:right;'><input type='submit' name='submit' class='send-button' value='Update' style='background-color:#61C668;'></input></td>
                <td></td>
              </tr>
            </table>
          </form>
        ";
        if (isset($_GET['confirmError'])==true) {
          echo "<span style='color:#ff0000;'>Password does not match the confirm password! Try again!</span>";
        }
      }
    }

    if (isset($_GET['resetCompleted'])==true) {
      echo "
        <h1 style='color:#55C630;'>Congratulation! Your password has been updated.</h1>
        <br/>
        <a href='index.php'>Click here to login</a>
      ";
    }


    if (isset($_GET['error'])==false && isset($_GET['code'])==false && isset($_GET['resetCompleted'])==false){
    echo "
    <form action='forgot-login.php' method='post'>
      <h1>Reset Your Password</h1>
      <p >
      Enter your email below to get a new password!
      <span style='color:gray; font-size:13px;'>You will receive a link in your e-mail inbox for resetting password.</span>
      <br/>
      <table style='margin:auto;'>
        <tr>
          <td><input type='text' name='recoverEmail' class='search-input' placeholder='Enter your email'></input></td>
          <td style='width:65px; height:60px;'><input type='submit' name='submit' class='send-button' value='Send' style='background-color:#61C668;'></input></td>
        </tr>
      </table>
      </p>
    </form>
    ";
    if (isset($_GET['linkSent'])==true) {
      echo "<span style='color:#55C630;'>En email has been sent to your email. Check your inbox!</span>";
    }

    if (isset($_POST['submit'])){
      $recoverEmail=$_POST['recoverEmail'];
      $query = "SELECT * FROM profiles WHERE email='$recoverEmail'";
      $result = mysqli_query($link, $query);
      $numrow = mysqli_num_rows($result);
      if ($numrow!=0){
        while($row=mysqli_fetch_assoc($result)){
          $db_email = $row['email'];
        }
        if ($recoverEmail == $db_email){
          $code = rand(10000, 1000000);
          $to = $db_email;
          $subject = "Password Reset";
          $message = "
            This is an automated email. Please DO NOT reply to this email!

            Click the link below or paste it into your browser 
            http://xml.csc.kth.se/~marang/DM2517/your-network/forgot-login.php?code=$code&recoverEmail=$recoverEmail
          ";
          $query2 = "UPDATE profiles SET passreset='$code' WHERE email='$recoverEmail'";
          mysqli_query($link, $query2);
          mail($to, $subject, $message);
          unset($_POST);
          header("location:forgot-login.php?linkSent=1");
        }
        else {
          echo "Email is incorrect.";
          header("location:forgot-login.php?error=1&numrow=$numrow");
        }
      }
      else {
        echo "That email doesn't exist. Try again!";
        header("location:forgot-login.php?error=1");
      }
    }
    }
    ?>

    <?php
      if (isset($_GET['error'])==true) {
        echo "
          <form action='forgot-login.php' method='post'>
            <h1>Reset Your Password</h1>
            <p>
            Enter your email below to get a new password!
            <span style='color:gray; font-size:13px;'>You will receive a link in your e-mail inbox for resetting password.</span>
            <br/>
            <table style='margin:auto;'>
              <tr>
                <td><input type='text' name='recoverEmail' class='search-input' placeholder='Enter your email'></input></td>
                <td style='width:65px; height:60px;'><input type='submit' name='submit' class='send-button' value='Send' style='background-color:#61C668;'></input></td>
              </tr>
            </table>
            </p>
            <span style='color:#ff0000;'>That email doesn't exist. Try again!</span>
          </form>
        ";
      }
    ?>
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