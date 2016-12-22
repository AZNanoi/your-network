<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php
$host="localhost"; // Host name 
$username="marang"; // Mysql username 
$password="marang-xmlpub13"; // Mysql password 
$db_name="marang"; // Database name 
$tbl_name="register"; // Table name 

$link=mysqli_connect('$host', '$username', '$password', '$db_name');
if (mysqli_connect_errno()) {
	printf("Connection failed: %s\n", mysqli_connect_error());
	exit();
}


?>

</body>
</html>