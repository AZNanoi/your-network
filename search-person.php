<?php
	require("dbc.php");
	if($_POST){
		$inputData=$_POST["inputData"];
		$query="SELECT concat(firstname,' ',lastname) fullname, id
				FROM profiles
				WHERE concat(firstname,' ',lastname) LIKE '%$inputData%' ORDER BY firstname LIMIT 5";
		$result=mysqli_query($link, $query);
		$b_inputData='<strong>'.$inputData.'</strong>';
		while ($row=$result->fetch_object()) {
			$username=$row->fullname;
			$userID=$row->id;
			$output_username=str_ireplace($inputData, $b_inputData, $username);
		
?>
<div class="show" align="left" style="overflow: hidden;margin-bottom: 3px;border-radius: 5px;border: 1px solid rgb(255, 255, 255);background-color: rgba(182, 232, 255, 0.2);">
	<a href="profile.php?profileID=<?php echo $userID; ?>&amp;profile_userName=<?php echo $username; ?>" style="text-decoration:none;color:black;"><img src="http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=<?php echo $userID; ?>" style="padding-bottom:2px;border-radius:5px;width:50px; height:50px; float:left; margin-right:6px;" />
	<span class="name"><?php echo $output_username; ?></span></a>
</div>

<?php
		}
	}
?>