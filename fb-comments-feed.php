<?php 
  header("Content-type: text/xml; charset=utf-8");
  // Display all warnings
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
?>

<?php 
  require("dbc.php");
  if(isset($_GET['postID'])){
    $postID=$_GET['postID'];
  }else{
    $postID='';
  }
  
  require 'fb-conf.php';
  if(isset($_GET['access_token'])){
    
    $access_token=$_GET['access_token'];
    $facebook->setAccessToken($access_token);
    $access_token=$facebook->getAccessToken();
  }
  $yn_total=0;
  $fb_total=0;
  if (isset($_GET['fb_id']) && $postID != '')
    {
      $fb_Data=$facebook->api('/'.$_GET['fb_id'].'?fields=comments.summary(true).order(reverse_chronological){from,message,created_time,attachment}','GET');
      $fb_total=$fb_Data['comments']['summary']['total_count'];
      $fb_Data=$fb_Data['comments']['data'];
      $query="SELECT * FROM comments WHERE postID='$postID' ORDER BY created_time ASC";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_all($result,MYSQLI_ASSOC);
      $com_Data=array_merge($db_response, $fb_Data);
      function date_compare($a, $b)
        {
            $t1 = strtotime($a['created_time']);
            $t2 = strtotime($b['created_time']);
            if ($t1==$t2) return 0;
            return ($t1<$t2)?1:-1;
        }
      usort($com_Data, 'date_compare');

      $serialized = array();
      function isInArray($arg, $array){
        for ($i=0; $i < sizeof($array); $i++) {
          $t2 = $array[$i]['fb_id'];
          if ($arg==$t2){
            return true;
          }
        }
        return false;
      }
      $fb_total=0;
      for ($i=0; $i < sizeof($com_Data); $i++) {
        if (substr($com_Data[$i]['id'], 0, 2)=='yn'){
          $serialized[] = $com_Data[$i];
        }else{
          $test = isInArray($com_Data[$i]['id'], $db_response);
          if ($test == false) {
            $serialized[] = $com_Data[$i];
            $fb_total = $fb_total + 1;
          }
        }
      }
      $yn_total=count($db_response);
      $com_total=$yn_total+$fb_total;
      $com_Data=array_slice($serialized,0,2);
      $com_Data=array_reverse($com_Data);
      mysqli_free_result($result);
    }
  elseif (isset($_GET['only_yn'])==true && $postID != '')
    {
      $query="SELECT * FROM comments WHERE postID='$postID' ORDER BY created_time ASC";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $com_Data=mysqli_fetch_all($result,MYSQLI_ASSOC);
      $com_total=count($com_Data);
      mysqli_free_result($result);
    }
  else{
    if($postID != ''){
      $com_Data=$facebook->api('/'.$postID.'?fields=comments.summary(true).order(reverse_chronological).limit(2){from,message,created_time,attachment}','GET');
      $com_total=$com_Data['comments']['summary']['total_count'];
      $fb_total=$com_total;
      $com_Data=array_reverse($com_Data['comments']['data']);
    }
  }
?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
      xmlns:yn="http://xml.csc.kth.se/~marang/DM2517/your-network/yn-comment.dtd"
    >
  <channel>
  	<title>Comments on: <?php echo $postID; ?></title>
  	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
  	<description>Read comments from all apps</description>
  	<dc:language>en-us</dc:language>
    <?php
      $dateStamp=date("c");
      echo "<dc:date>$dateStamp</dc:date>";
    ?>
  	<dc:creator>marang@kth.se</dc:creator>
    <category>PostsSyndicationFeed</category>
    <syn:updatePeriod>daily</syn:updatePeriod>
    <syn:updateFrequency>1</syn:updateFrequency>
    <syn:updateBase>2006-01-01T00:00+00:00</syn:updateBase>
    <yn:totalComCount><?php echo $com_total; ?></yn:totalComCount>
    <yn:ynComCount><?php echo $yn_total; ?></yn:ynComCount>
    <yn:fbComCount><?php echo $fb_total; ?></yn:fbComCount>
    <yn:twComCount>0</yn:twComCount>
    <?php
        $com='';
        if($com_total > 2){
          $remain=$com_total-2;
          $com="<comments>$remain</comments>";
        }
        foreach($com_Data as $entry) {
          if (substr($entry['id'],0,2)=='yn'){
            $commenter=$entry['commenter'];
            $commenter_ID=$entry['commenter_ID'];
          }else{
            $commenter=$entry['from']['name'];
            $commenter_ID=$entry['from']['id'];
          }
      		$message=utf8_encode($entry['message']);
      		$created_time=$entry['created_time'];
          $created_time=date('Y-m-d \a\t h:i \a\g\o', strtotime($created_time));
          $comID=$entry['id'];
          $media='';
          if(array_key_exists('attachment', $entry)){
            $attachment=$entry['attachment']['url'];
            $attachment = preg_replace('/\&/', '&amp;', $attachment);
            $media="<media:content url='$attachment' medium='image'></media:content>";
          }
      		echo "<item>
      	<title>$commenter</title>
      	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
      	<description>
        	<![CDATA[$message]]>
      	</description>
      	<author>$commenter</author>
        <yn:authorID>$commenter_ID</yn:authorID>
      	<guid isPermaLink='false'>$comID</guid>
      	<dc:date>$created_time</dc:date>
        $com
        <media:thumbnail url='https://graph.facebook.com/$commenter_ID/picture'/>
        $media
    </item>";
      	}
    ?>
  </channel>
</rss>
<?php 
if(isset($_POST["moreReviews"])==true){
  require "comments-postfix.php";
}
?>