<?php
	//Report any errors
	ini_set("display_errors", "1");
	error_reporting(E_ALL);
	require("dbc.php");
	if(isset($_GET['id'])){
		$id =mysqli_real_escape_string($link, $_GET['id']);
		$query = mysqli_query($link, "SELECT * FROM profiles WHERE id='$id'");
		if(mysqli_num_rows($query)==1){
			while($row=mysqli_fetch_assoc($query)){
				$imageData=$row['image'];
				$imageType=$row['imageType'];
			}
			if (empty($imageData)){
				header("location:images/blank-profile.jpg");
			}else{
				$contentType="content-type:".$imageType;
				header($contentType);
				echo $imageData;
			}
		}else{
			header("location:images/blank-profile.jpg");
		}
			
	}
	else{
		echo "Error!";
	}
?>