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

  function index_search($arrays, $field, $value)
    {
      foreach($arrays as $key => $product)
      {
        if ( $product[$field] === $value )
          return $key;
      }
      return false;
    }

  if ($_POST["app"]=='fb')
    {
      $last_com_id=$_POST["last_com_id"];
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
      $serialized = array();
      for ($i=0; $i < sizeof($fb_Data); $i++) {
        $test = isInArray($fb_Data[$i]['id'], $db_response);
        if ($test == false) {
          $serialized[] = $fb_Data[$i];
        }
      }
      $lpi_index=index_search($serialized, "id", $last_com_id);
      $index=$lpi_index+1;
      $eNum=count($serialized);
      $diff=$eNum-$index;
      if($diff != 0){
        if($diff == 1){
          $com_Data=array_slice($serialized,$lpi_index+1);
        }else{
          $com_Data=array_slice($serialized,$lpi_index+1,5);
        }
        $com_Data=array_reverse($com_Data);
      }else{
        $com_Data=array();
      }
      mysqli_free_result($result2);
    }
  elseif ($_POST["app"]=='yn')
    {
      $last_com_id=$_POST["last_com_id"];
      $query="SELECT * FROM comments WHERE postID='$postID' ORDER BY created_time DESC";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_all($result,MYSQLI_ASSOC);
      $lpi_index=index_search($db_response, "id", $last_com_id);
      $index=$lpi_index+1;
      $eNum=count($serialized);
      $diff=$eNum-$index;
      if($diff != 0){
        if($diff == 1){
          $com_Data=array_slice($db_response,$lpi_index+1);
        }else{
          $com_Data=array_slice($db_response,$lpi_index+1,5);
        }
        $com_Data=array_reverse($com_Data);
      }else{
        $com_Data=array();
      }
      mysqli_free_result($result);
    }
  elseif ($_POST["app"]=='all')
    {
      $last_com_id=$_POST["last_com_id"];
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
      $query="SELECT * FROM comments WHERE postID='$postID' ORDER BY created_time ASC";
      if (($result2=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_all($result2,MYSQLI_ASSOC);
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

      for ($i=0; $i < sizeof($com_Data); $i++) {
        if (substr($com_Data[$i]['id'], 0, 2)=='yn'){
          $serialized[] = $com_Data[$i];
        }else{
          $test = isInArray($com_Data[$i]['id'], $db_response);
          if ($test == false) {
            $serialized[] = $com_Data[$i];
          }
        }
      }
      $lpi_index=index_search($serialized, "id", $last_com_id);
      $index=$lpi_index+1;
      $eNum=count($serialized);
      $diff=$eNum-$index;
      if($diff != 0){
        if($diff == 1){
          $com_Data=array_slice($serialized,$lpi_index+1);
        }else{
          $com_Data=array_slice($serialized,$lpi_index+1,5);
        }
        $com_Data=array_reverse($com_Data);
      }else{
        $com_Data=array();
      }
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