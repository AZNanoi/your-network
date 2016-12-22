<?php
	//Report any errors
	ini_set("display_errors", "1");
	error_reporting(E_ALL);
	require("dbc.php");
	if(isset($_GET['postID'])){
		$postID =mysqli_real_escape_string($link, $_GET['postID']);
		$fileName =mysqli_real_escape_string($link, $_GET['fileName']);
		$query = mysqli_query($link, "SELECT * FROM post_photos WHERE id='$postID' and fileName='$fileName'");
		while($row=mysqli_fetch_assoc($query)){
			$imageData=$row['blob'];
			$imageType=$row['imageType'];
		}
		$contentType="content-type:".$imageType;
		header($contentType);
		echo $imageData;
	}
	else{
		echo "Error!";
	}
?>