<?php
	session_start();
	require("dbc.php");
	$postID=$_POST['postID'];
	$message=$_POST['message'];
	$uid=$_SESSION['userID'];
	$query="UPDATE posts SET message='$message' WHERE uid='$uid' AND id='$postID'";
	mysqli_query($link, $query);
	$query2 = "SELECT * FROM posts WHERE message='$message' and id='$postID' and uid='$uid'";
	if (($result=mysqli_query($link, $query2)) === false) {
		printf("Query failed: %s<br />\n%s", $query, mysqli_error($link));
    	exit();
	}
	if(mysqli_num_rows($result)==1){
		echo 'updated';
	}else{
		echo 'error';
	}
?>