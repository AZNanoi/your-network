<?php 
  // Display all warnings
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
ob_start();
?>

<?php 
  $postID=$_POST['postID'];
  if(isset($_GET['access_token'])){
    $access_token=$_GET['access_token'];
  }else{
    $access_token=$_SESSION["fb_access_token"];
  }
  require 'fb-conf.php';
  require("dbc.php");
  $facebook->setAccessToken($access_token);
  $access_token=$facebook->getAccessToken();
  if ($_POST["app"]=='yn')
    {
      $query="SELECT * FROM comments WHERE postID='$postID' ORDER BY created_time DESC LIMIT 5";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_all($result,MYSQLI_ASSOC);
      $com_Data=array_reverse($db_response);
      mysqli_free_result($result);
    }
  elseif ($_POST["app"]=='fb')
    {
      if (substr($postID, 0, 2)== 'yn'){
        $result=mysqli_query($link, "SELECT fb_id FROM posts WHERE id='$postID'");
        $result_l=mysqli_fetch_array($result,MYSQLI_ASSOC);
        $fb_id=$result_l['fb_id'];
        mysqli_free_result($result);
      }else{
        $fb_id=$postID;
      }
      $fb_Data=array(array());
      if($fb_id!='null'){
        $fb_Data=$facebook->api('/'.$fb_id.'?fields=comments.summary(true).order(reverse_chronological){from,message,created_time,attachment}','GET');
        $fb_total=$fb_Data['comments']['summary']['total_count'];
        $fb_Data=$fb_Data['comments']['data'];
      }
      $query="SELECT fb_id FROM comments WHERE postID='$postID' ORDER BY created_time ASC";
      if (($result2=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_all($result2,MYSQLI_ASSOC);
      function isInArray($arg, $array){
        for ($i=0; $i < sizeof($array); $i++) {
          $t2 = $array[$i]['fb_id'];
          if ($arg==$t2){
            return true;
          }
        }
        return false;
      }
      $com_Data = array();
      for ($i=0; $i < sizeof($fb_Data); $i++) {
        $test = isInArray($fb_Data[$i]['id'], $db_response);
        if ($test == false) {
          $com_Data[] = $fb_Data[$i];
        }
      }
      $eNum=count($com_Data);
      if ($eNum > 5){
        $com_Data=array_slice($com_Data,0,5);
      }
      $com_Data=array_reverse($com_Data);
      mysqli_free_result($result2);
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
    <description>fetch more comments</description>
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
    <?php
        if(count($com_Data)!=0){
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
          <media:thumbnail url='https://graph.facebook.com/$commenter_ID/picture'/>
          $media
      </item>";
        }
      }
    ?>
  </channel>
</rss>
<?php 
  require "comments-postfix.php";
?>