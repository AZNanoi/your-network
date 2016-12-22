<?php
  $UA = getenv('HTTP_USER_AGENT');
  echo $UA;
?>
<?php
	require("dbc.php");
	if($_POST){
		$inputData=$_POST["inputData"];
		$query="SELECT concat(fistname,' ',lastname) fullname
				FROM profiles
				WHERE concat(fistname,' ',lastname) LIKE '%$inputData%' ORDER BY firstname LIMIT 5";
		$result=mysqli_query($link, $query);
		$b_inputData='<strong>'.$inputData.'</strong>';
		while ($row=$result->fetch_object()) {
			$username=$row->fullname;
			$output_username=str_ireplace($inputData, $b_inputData, $username);
?>
<div class="show" align="left">
	<img src="images/blank-profiles.png" style="width:50px; height:50px; float:left; margin-right:6px;" />
	<span class="name"><?php echo $output_username; ?></span>
</div>
<?php
		}
	}
?>