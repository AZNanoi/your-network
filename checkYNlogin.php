<?php
	session_start();
	require("dbc.php");
	$tbl_name="profiles"; // Table name 

	// username and password sent from form 
	$userEmail=$_POST['userEmail']; 
	$userPassword=$_POST['userPassword']; 

	// To protect MySQL injection (more detail about MySQL injection)
	$userEmail = stripcslashes($userEmail);
	$userPassword = stripcslashes($userPassword);
	$userEmail = mysqli_real_escape_string($link, $userEmail);
	$userPassword = mysqli_real_escape_string($link, $userPassword);
	$userPassword = md5($userPassword);

	$query = "SELECT * FROM profiles WHERE email='$userEmail' and password='$userPassword'";
	if (($result=mysqli_query($link, $query)) === false) {
		printf("Query failed: %s<br />\n%s", $query, mysqli_error($link));
    	exit();
	}

	if(mysqli_num_rows($result)==1){
		while ($row=$result->fetch_object()){
			$firstname = $row->firstname;
			$lastname = $row->lastname;
			$userName = $firstname.' '.$lastname;
			$userID = $row->id;
		}
		$_SESSION['yn-userName'] = $userName;
		$_SESSION['userID'] = $userID;
		header("location:index.php");
	}
	else {
		echo "Wrong Username or Password";
	}
	mysqli_free_result($result);
	mysqli_close($link);
?>