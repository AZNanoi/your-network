<?php header("Content-type: text/xml; charset=utf-8"); ?>
<?php 
  // Display all warnings
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  session_start();
  $postID=$_GET['postID'];
  $access_token=$_GET['access_token'];
?>
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
      if(isset($_GET['summary'])==true){
        $file='https://graph.facebook.com/v2.5/'.$postID.'?fields=likes.summary(true).limit(0)&access_token='.$access_token;
      }else{
        $file='https://graph.facebook.com/v2.5/'.$postID.'?fields=likes.summary(true).limit(5){name}&access_token='.$access_token;
      }
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