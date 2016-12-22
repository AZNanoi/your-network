<?php
	session_start();
	require "dbc.php";
	require 'fb-conf.php';
  if(empty($_POST["appsList"])){
    $appsList=array();
  }else{
    $appsList=$_POST["appsList"];
  }
	$uid=strval($_SESSION['userID']);
	if(isset($_SESSION["yn-userName"])){
		$userName=$_SESSION["yn-userName"];
	}else{
		$userName=$_SESSION["fb-userName"];
	}
	$postID=strval($_POST['postID']);
	$message=$_POST['message'];
	$dateStamp=date('c');
	$created_time=date('Y-m-d \a\t h:i \a\g\o', strtotime($dateStamp));

  if(substr($postID, 0, 2)=='yn'){
    $itemID_split=explode('_', $postID);
    $c_id=$itemID_split[2];
    $hashedTime=md5($dateStamp);
    $comID='yn_'.$c_id.'_'.$hashedTime;
    mysqli_query($link, "INSERT INTO comments VALUES('$comID', '$postID', '$userName', '$uid', '$message', '$dateStamp', 'null')");
    if(in_array("fb", $appsList)){
      $query="SELECT fb_id FROM posts WHERE id='$postID'";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_array($result,MYSQLI_ASSOC);
      if ($db_response['fb_id']!='null'){
        $res = $facebook->api('/'.$db_response['fb_id'].'/comments', 'POST', array('message'=> $message));
        $fb_id=$res['id'];
        mysqli_query($link, "UPDATE comments SET fb_id='$fb_id' WHERE id='$comID'");
      }
      mysqli_free_result($result);
    }
  }else{
    $itemID_split=explode('_', $postID);
    $c_id=$itemID_split[1];
    $hashedTime=md5($dateStamp);
    $comID='yn_'.$c_id.'_'.$hashedTime;
    mysqli_query($link, "INSERT INTO comments VALUES('$comID', '$postID', '$userName', '$uid', '$message', '$dateStamp', 'null')");
    if(in_array("fb", $appsList)){
      $res = $facebook->api('/'.$postID.'/comments', 'POST', array('message'=> $message));
      $fb_id=$res['id'];
      mysqli_query($link, "UPDATE comments SET fb_id='$fb_id' WHERE id='$comID'");
    }
  }
	echo '<div class="comment" style="width: 100%; overflow: hidden;">
	          <div class="c-pic f-l">
	            <img src="https://graph.facebook.com/'.$uid.'/picture">
	          </div>
	          <div style="float: left;">
	            <span class="font-link">'.$userName.'</span>
	          	<span class="font-time">('.$created_time.')</span>
              <img src="images/yn_icon.png" style="opacity:0.45;"/>
	          	<div>
            		<span class="font-standard">'.$message.'</span><br>
          		</div>
          		<div class="c-button f-l" style="width:80px; padding-bottom:5px;">
            		<div class="f-l">
                		<img src="images/profile-photo-b.png">
            		</div>
            		<div class="f-l" style="padding:0px 7px;">
                		<span class="font-link f1">0</span>
            		</div>
          		</div>
          		<div class="c-button f-l">
            		<div class="f-l">
              			<img src="images/comment-button-b.png">
            		</div>
            		<div class="f-l" style="padding:0px 7px;">
              			<span class="font-link f1">0</span>
            		</div>
          		</div>
              </div>
          </div>';
?>