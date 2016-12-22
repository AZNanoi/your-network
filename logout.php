<?php 
session_start();
unset($_POST);
session_destroy();
ob_end_flush();
require 'fb-conf.php';
$facebook->destroySession();
header("location:index.php");
?>