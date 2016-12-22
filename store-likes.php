<?php
	session_start();
	require("dbc.php");
	$postID=$_REQUEST['postID'];
	if (isset($_SESSION['yn-userName'])){
		$userName=$_SESSION['yn-userName'];
	}else{
		$userName=$_SESSION['fb-userName'];
	}
	$userID=$_SESSION['userID'];
	$userID=strval($userID);
	$query="SELECT COUNT(*) AS rows FROM likes WHERE postID='$postID' AND userID='$userID'";
	if (($result=mysqli_query($link, $query)) === false) {
	  	printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
	  	exit();
	}
	$res=mysqli_fetch_assoc($result);
	if($res['rows']>0){
		$query2="DELETE FROM likes WHERE postID='$postID' AND userID='$userID'";
		mysqli_query($link, $query2);
		echo "removed";
	}else{
		$dateStamp=date("c");
		$query2="INSERT INTO likes VALUES('$postID', '$userName', '$userID', '$dateStamp')";
		mysqli_query($link, $query2);
		echo "added";
	}
	mysqli_free_result($result);
	//$result=mysqli_query($link, "SELECT COUNT(*) AS newNum FROM likes WHERE postID='$postID'");
	//while ($row = $result->fetch_object()){
	//	$newNum=$row->newNum;
	//	$newNum=strval($newNum);
	//}
?>