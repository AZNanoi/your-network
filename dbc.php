<?php
$host="localhost"; // Host name 
$username="marang"; // Mysql username 
$password="marang-xmlpub13"; // Mysql password 
$db_name="marang"; // Database name 

// Connect to server and select databse.
$link=mysqli_connect($host, $username, $password, $db_name);
if (mysqli_connect_errno()) {
	printf("Connection failed: %s\n", mysqli_connect_error());
	exit();
}
?>