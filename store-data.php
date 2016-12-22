<?php
// Display all warnings
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
	require("dbc.php");
	session_start();
	$id=$_SESSION["userID"];

	function resizeImage($originalImage,$toWidth,$toHeight,$width,$height){
	    // Get the original geometry and calculate scales
	    $xscale=$width/$toWidth;
	    $yscale=$height/$toHeight;

	    // Recalculate new size with default ratio
	    if ($yscale>$xscale){
	        $new_width = round($width * (1/$yscale));
	        $new_height = round($height * (1/$yscale));
	    }
	    else {
	        $new_width = round($width * (1/$xscale));
	        $new_height = round($height * (1/$xscale));
	    }

	    // Resize the original image
	    $imageResized = imagecreatetruecolor($new_width, $new_height);
	    $imageTmp     = imagecreatefromjpeg($originalImage);
	    imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	    return $imageResized;
	}

	if(isset($_POST['profilePicSubmit'])){
		//$originalImage=mysqli_real_escape_string($link, $_FILES["thumbnail"]["name"]);
		$fileData=mysqli_real_escape_string($link, file_get_contents($_FILES["thumbnail"]["tmp_name"]));
		$fileType=mysqli_real_escape_string($link, $_FILES["thumbnail"]["type"]);
		list($width, $height)=getimagesize($_FILES["thumbnail"]["tmp_name"]);
		//$src = imagecreatefromstring(file_get_contents($_FILES["thumbnail"]["tmp_name"]));
		//if ($width > 90){
		//	$toHeight=$height*(90/$width);
		//}
		//$resizeImage=resizeImage($fileData,90,$toHeight,$width, $height);
		mysqli_query($link, "UPDATE profiles SET image='$fileData', imageType='$fileType' WHERE id='$id'");
		header("location:index.php");
	}
?>