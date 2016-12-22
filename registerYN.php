<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php
session_start();
require("dbc.php");
$tbl_name="profiles"; // Table name 

// username and password sent from form 
$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$email=$_POST['email'];
$password=$_POST['password'];
$reEnterPassword=$_POST['reEnterPassword'];
$phone=$_POST['phone'];
$year=$_POST['year'];
$month=$_POST['month'];
$day=$_POST['day'];
$gender=$_POST['gender'];
$birthday="$year-$month-$day";
$datestamp = date("Y-m-d",strtotime($birthday));
$id=md5($email);
if ($password == $reEnterPassword){
	$hashed_password = md5($password);
	$query="INSERT INTO $tbl_name (id, firstname, lastname, email, password, telephone, birthday, gender)
		VALUES ('$id', '$firstname', '$lastname', '$email', '$hashed_password', '$phone', '$datestamp', '$gender')";

	if (mysqli_query($link, $query)) {
	    // Register $myusername, $mypassword and redirect to file "login_success.php"
		$userName = $firstname." ".$lastname;
		$_SESSION['yn-userName'] = $userName;
		$_SESSION['userID'] = $id;
		header("location:login_success.php");
		mysqli_close($link);

	} else {
	    echo "Error: " . $query . "<br>" . mysqli_error($link);
		exit();
	}
}
else {
	header("location:index.php?error=1");
	//setcookie("ico","Password does not match the confirm password!", mktime()+2);
	exit();
}
?>

</body>
</html>