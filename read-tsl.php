<?php header("Content-type: text/xml; charset=utf-8"); ?>
<?php 
  // Display all warnings
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  session_start();
  $postID=$_GET['postID'];
  $userID=$_GET['userID'];
  $access_token=$_GET['access_token'];
?>
<?php if(isset($_GET['summary'])==true):?>
	<?php
		require("dbc.php");
		$share_count=0;
		$like_count_fb=0;
		$has_liked_yn='false';
	  	$elements="<tag id='null'></tag>";
		if (substr($postID, 0, 2)=='yn'){
			$query="SELECT fb_id FROM posts WHERE id='$postID'";
	        if (($result=mysqli_query($link, $query)) === false) {
	          printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
	          exit();
	        }
	        $db_response=mysqli_fetch_array($result,MYSQLI_ASSOC);
	        $fb_id=$db_response['fb_id'];
	        mysqli_free_result($result);
		}else{
			$fb_id=$postID;
		}
		if ($fb_id!='null'){
			$file='https://graph.facebook.com/v2.5/'.$fb_id.'?fields=with_tags,shares,likes.summary(true).limit(0)&access_token='.$access_token;
			$json_object=file_get_contents($file);
			$response=json_decode($json_object,true);
			//$has_liked=$response['likes']['summary']['has_liked'];
			if (array_key_exists('with_tags', $response)){
				$elements='';
				foreach($response['with_tags']['data'] as $item) {
					$id=$item['id'];
					$name=$item['name'];
					$elements .= "<tag id='$id'>$name</tag>";
				}
			}
			if(array_key_exists('shares', $response)){
			  	$share_count=$response['shares']['count'];
			}
			$like_count_fb=$response['likes']['summary']['total_count'];
		}
		$query="SELECT userID FROM likes WHERE postID='$postID'";
		if (($result2=mysqli_query($link, $query)) === false) {
		  	printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
		  	exit();
		}
		$res_yn=mysqli_fetch_all($result2,MYSQLI_ASSOC);
		$like_count_yn=count($res_yn);
		function isInArray($arg, $array){
	        for ($i=0; $i < sizeof($array); $i++) {
	          $t2 = $array[$i]['userID'];
	          if ($arg==$t2){
	            return true;
	          }
	        }
	        return false;
	      }
		if (isInArray($userID, $res_yn)){
			$has_liked_yn='true';
		}
		mysqli_free_result($result2);
		$like_total=$like_count_yn+$like_count_fb;
		echo "<items><shareCount>$share_count</shareCount>
		<likeCount id='$has_liked_yn'>
			<total>$like_total</total>
			<yn>$like_count_yn</yn>
			<fb>$like_count_fb</fb>
		</likeCount>
		".$elements."</items>";
	?>
<?php else:?>
	<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
	    >
	  <channel>
	  	<title>Likes on: <?php echo $postID; ?></title>
	  	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
	    <?php if(substr($postID, 0, 2) == 'yn'){
	      echo "<description>0</description>";
	    }else{
	        $file='https://graph.facebook.com/v2.5/'.$postID.'?fields=likes.summary(true).limit(5){name}&access_token='.$access_token;
	      $json_object=file_get_contents($file);
	      $like_Data=json_decode($json_object,true);
	      $like_Data=$like_Data['likes'];
	      $total_count=strval($like_Data['summary']['total_count']);
	      $has_liked=$like_Data['summary']['has_liked'];
	      echo "<description>$total_count</description>";
	    }
	    ?>
	  	<dc:language>en-us</dc:language>
	    <?php
	      $dateStamp=date("c");
	      echo "<dc:date>$dateStamp</dc:date>
	";
	    ?>
	  	<dc:creator>$has_liked</dc:creator>
	    <category>LikesSyndicationFeed</category>
	    <syn:updatePeriod>daily</syn:updatePeriod>
	    <syn:updateFrequency>1</syn:updateFrequency>
	    <syn:updateBase>2006-01-01T00:00+00:00</syn:updateBase>
	    <?php
	      if(isset($_GET['summary'])==false){
	        foreach($like_Data['data'] as $entry) {
	          $user=$entry['name'];
	      		$user_ID=$entry['id'];
	      		echo "<item>
	      	<title>Liked by $user</title>
	      	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
	        <description>$user</description>
	      	<guid isPermaLink='false'>$user_ID</guid>
	    </item>";
	      	}
	      }
	    ?>
	  </channel>
	</rss>
<?php endif; ?>