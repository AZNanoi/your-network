<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
// Check if session is not registered, redirect back to main page. 
// Put this code in first line of web page. 
<?php
session_start();
if(!session_is_registered('status')){
header("location:index.php");
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>


<body>
<a href="logout.php">Login Successful</a>

</body>
</html>