<?php
	session_start();
	require 'fb-conf.php';
	$user = $facebook->getUser();
	if ($user){
		$extendedToken = $facebook->setExtendedAccessToken();
		$access_token = $facebook->getAccessToken();
		$_SESSION["fb_access_token"]= (string) $access_token;
		$_SESSION["userID"]=$user;
		$user_profile=$facebook->api('/me');
		$userName=$user_profile['name'];
		$_SESSION["fb-userName"]=$userName;
	}
	header("location:index.php");
?>