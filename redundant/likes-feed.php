<?php header("Content-type: text/xml; charset=utf-8"); ?>
<?php
  ini_set("display_errors", "1");
  error_reporting(E_ALL);
  $postID=$_GET['postID'];
  $userID=$_GET['userID'];
  require("dbc.php");
?>
<?php if(isset($_GET['summary'])==true):?>
  <?php
    $query="SELECT COUNT(*) FROM likes WHERE postID='$postID'";
    if (($result=mysqli_query($link, $query)) === false) {
      printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
      exit();
    }
    $like_count=mysqli_fetch_assoc($result);
    $like_count=$like_count['COUNT(*)'];
    $query="SELECT COUNT(*) FROM likes WHERE postID='$postID' AND userID='$userID'";
    if (($result2=mysqli_query($link, $query)) === false) {
      printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
      exit();
    }
    $has_liked=mysqli_num_rows($result2);
    $has_liked=$has_liked['COUNT(*)'];
    mysqli_free_result($result);
    mysqli_free_result($result2);
    echo "<likeCount id='$has_liked'>$like_count</likeCount>";
  ?>
<?php else:?>
  <rss version="2.0"
        xmlns:media="http://search.yahoo.com/mrss/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
      >
    <channel>
    	<title>Likes On All Posts</title>
    	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
    	<description>Likes on all posts</description>
    	<dc:language>en-us</dc:language>
      <?php
        $dateStamp=date("c");
        echo "<dc:date>$dateStamp</dc:date>
  ";
      ?>
    	<dc:creator>marang@kth.se</dc:creator>
      <category>LikesSyndicationFeed</category>
      <syn:updatePeriod>daily</syn:updatePeriod>
      <syn:updateFrequency>1</syn:updateFrequency>
      <syn:updateBase>2006-01-01T00:00+00:00</syn:updateBase>
      <?php
      	$query="SELECT * FROM likes WHERE postID='$postID' ORDER BY created_time DESC";
      	if (($result=mysqli_query($link, $query)) === false) {
          	printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
          	exit();
        	}
        	while ($row=$result->fetch_object()) {
        		$postID=$row->postID;
        		$userName=$row->userName;
        		$userID=$row->userID;
        		$created_time=$row->created_time;
        		echo "
      <item>
        	<title>By: $userName</title>
        	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
        	<description>$postID</description>
        	<author>$userName</author>
        	<guid isPermaLink='false'>$userID</guid>
        	<dc:date>$created_time</dc:date>
      </item>
      ";
        	}
        	mysqli_free_result($result);
      ?>
    </channel>
  </rss>
<?php endif ?>