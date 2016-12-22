<?Php
  session_start();
  require("dbc.php");
  require 'fb-conf.php';
  $id=$_SESSION['userID'];
  $appsList=explode(",", $_POST["appsList"]);
  $message=$_POST["status_message"];
  if (isset($_SESSION["fb-userName"])){
    $access_token = $_SESSION["fb_access_token"];
    $facebook->setAccessToken($access_token);
    $facebook->setFileUploadSupport(true);
  }
  $dateStamp=date('c');
  $hashedTime=md5($dateStamp);
  $strId=strval($id);
  $postID='yn_'.$strId.'_'.$hashedTime;
  $count=0;
  $files=array();
  $attachments='';
  $media='';
  $list=array();
  mysqli_query($link, "INSERT INTO posts VALUES('$strId', '$postID', '$message', 'null', '$dateStamp', 'null', 'null')");
  if (isset($_FILES["mediaFiles"]["name"])){
    foreach ($_FILES["mediaFiles"]["name"] as $fileName) {
      $fileName=mysqli_real_escape_string($link, $fileName);
      $fileData=mysqli_real_escape_string($link, file_get_contents($_FILES["mediaFiles"]["tmp_name"][$count]));
      $fileType=mysqli_real_escape_string($link, $_FILES["mediaFiles"]["type"][$count]);
      list($width, $height)=getimagesize($_FILES["mediaFiles"]["tmp_name"][$count]);
      mysqli_query($link, "INSERT INTO post_photos VALUES('$postID', '$fileData', '$fileName', '$fileType', '$width', '$height')");
      $count=$count + 1;
      if(in_array("fb", $appsList) && isset($_SESSION["fb-userName"])){
        $url="http://xml.csc.kth.se/~marang/DM2517/your-network/showImage-posts.php?postID=".$postID."&fileName=".$fileName;
        $url=urlencode($url);
        $files[] = array( 
          "method" => "POST",
          "relative_url" => "me/photos",
          "body" => "message=".$message."&url=".$url."&privacy={\"value\":\"SELF\"}"
        );
      }
      $list[] = "<media:content url='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage-posts.php?postID=$postID&#38;fileName=$fileName' medium='image' height='$height' width='$width'></media:content>";
    }
    $list=array_reverse($list);
    $media=implode("", $list);
  }else{
    if(in_array("fb", $appsList) && isset($_SESSION["fb-userName"])){
      $files[] = array( 
        "method" => "POST",
        "relative_url" => "me/feed",
        "body" => "message=".$message."&privacy={\"value\":\"SELF\"}"
      );
    }
    $media="<media:content url='' medium='image'></media:content>";
  }
  if(in_array("fb", $appsList) && isset($_SESSION["fb-userName"])){
    $res = $facebook->api('/?batch='.urlencode(json_encode($files)), 'POST');
    $json=json_decode($res[0]['body'], true);
    if (isset($json['post_id'])){
      $fb_id=$json['post_id'];
    }else{
      $fb_id=$json['id'];
    }
    mysqli_query($link, "UPDATE posts SET fb_id='$fb_id' WHERE id='$postID'");
  }
?>
<?php include 'prefix.php';?>
<?php 
  $user=$id;
?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
    >
  <channel>
    <title>Upload post</title>
    <link>http://www.facebook.com/</link>
    <description>Facebook user feed</description>
    <dc:language>en-us</dc:language>
    <?php
      $dateSt=date("c");
      echo "<dc:date>$dateSt</dc:date>
";
    ?>
    <dc:creator>marang@kth.se</dc:creator>
    <category>FBPostsSyndicationFeed</category>
    <syn:updatePeriod>daily</syn:updatePeriod>
    <syn:updateFrequency>1</syn:updateFrequency>
    <syn:updateBase>2006-01-01T00:00+00:00</syn:updateBase>
    <image>
      <url>https://graph.facebook.com/<?php echo $user; ?>/picture?type=normal</url>
      <title>Profile image</title>
      <link>https://graph.facebook.com/<?php echo $user; ?>/picture?type=normal</link>
    </image>
    <?php
      $dateStamp=date('Y-m-d \a\t h:i \a\g\o', strtotime($dateStamp));
      if (isset($_SESSION["fb-userName"])){
        $userName=$_SESSION['fb-userName'];
        $thumbnail="<media:thumbnail url='https://graph.facebook.com/$user/picture?type=normal' height='75' width='75' />";
      }else{
        $userName=$_SESSION['yn-userName'];
        $thumbnail="<media:thumbnail url='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=$id' height='75' width='75' />";
      }
      $com='';
      if(in_array("fb", $appsList) && isset($_SESSION["fb-userName"])){
        $com="<comments>http://xml.csc.kth.se/~marang/DM2517/your-network/fb-comments-feed.php?postID=$postID&#38;fb_id=$fb_id&#38;access_token=$access_token</comments>";
      }else{
        $com="<comments>http://xml.csc.kth.se/~marang/DM2517/your-network/fb-comments-feed.php?postID=$postID&#38;only_yn=1</comments>";
      }
      echo "<item>
          <title>$postID</title>
          <link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
          <description>
            <![CDATA[$message]]>
          </description>
          <author>$userName</author>
          <guid isPermaLink='false'>$postID</guid>
          <category>null</category>
          <dc:date>$dateStamp</dc:date>
          $com
          $thumbnail
          <media:group>
            $media
          </media:group>
        </item>
    ";
    ?>
  </channel>
</rss>
<?php include 'upload-post-postfix.php';?>