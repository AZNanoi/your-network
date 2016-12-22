<?php ob_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Your Network</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="layout-mobile.css"/>

</head>
<body>
  <div id="header">
    <a href="index.php"><img src="images/HouseButton.png" alt="YN logo" width="35px" height="35px" style="float:left"></img></a>
    <img src="images/logo.png" width="30px" height="30px"></img>
    <span style="font-size:25pt; color:white; font-weight:bold; ">Your Network</span>
  </div>
  <div id="content-bg"></div>
  <div style="width:100%; margin-top:6em;">
    <div class="centerContent" style="width:95%; border-radius: 25px;
    border:1px solid gray; text-align:center; padding:10px; vertical-align: middle; transform: translate(-50%, 0%);">

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
    <form action='forgot-login-mobile.php' method='post'>
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
            http://xml.csc.kth.se/~marang/DM2517/your-network/forgot-login-mobile.php?code=$code&recoverEmail=$recoverEmail
          ";
          $query2 = "UPDATE profiles SET passreset='$code' WHERE email='$recoverEmail'";
          mysqli_query($link, $query2);
          mail($to, $subject, $message);
          unset($_POST);
          header("location:forgot-login-mobile.php?linkSent=1");
        }
        else {
          echo "Email is incorrect.";
          header("location:forgot-login-mobile.php?error=1&numrow=$numrow");
        }
      }
      else {
        echo "That email doesn't exist. Try again!";
        header("location:forgot-login-mobile.php?error=1");
      }
    }
    }
    ?>

    <?php
      if (isset($_GET['error'])==true) {
        echo "
          <form action='forgot-login-mobile.php' method='post'>
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
</body>
</html>