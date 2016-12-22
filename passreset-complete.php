<?php
session_start();
require("dbc.php");

$newpassword = $_POST['newpassword'];
$newpassword2 = $_POST['newpassword2'];
$post_email = $_POST['recoverEmail'];
$code = $_GET['code'];

if ($newpassword == $newpassword2) {
	$hashed_password = md5($newpassword);
	mysqli_query($link, "UPDATE profiles SET password='$hashed_password' WHERE email='$post_email'");
	mysqli_query($link, "UPDATE profiles SET passreset='0' WHERE email='$post_email'");
	$UA = getenv('HTTP_USER_AGENT');
	if (preg_match('/OPR|Firefox|Android|Opera|Symbian|Motorola|Nokia|Siemens|Samsung|Ericsson|LG|NEC|SEC|MIDP|Windows CE/', $UA))
	{
		header("location:forgot-login-mobile.php?resetCompleted=1");
	}
	else
	{
		header("location:forgot-login.php?resetCompleted=1");
	}
	
}
else {
	header("location:forgot-login.php?code=$code&recoverEmail=$post_email&confirmError=1");
}

?>