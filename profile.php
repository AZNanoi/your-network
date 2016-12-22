<?php header("Content-type: text/xml; charset=utf-8"); 
// Display all warnings
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Capture XML output
ob_start();
session_start();
$id=$_SESSION["userID"];
$userName=$_SESSION["yn-userName"];
$profileID=$_GET['profileID'];
$profile_userName=$_GET['profile_userName'];
?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
      xmlns:yn="http://xml.csc.kth.se/~marang/DM2517/your-network/yn-comment.dtd"
    >
  <channel>
    <title><?php echo $profile_userName;?></title>
    <link>http://xml.csc.kth.se/~marang/DM2517/your-network?id=1015712959</link>
    <?php
      echo "<description>$profile_userName"."'s profile</description>";
    ?>
    <dc:language>en-us</dc:language>
    <?php
      $dateStamp=date("c");
      echo "<dc:date>$dateStamp</dc:date>
";
    ?>
    <dc:creator>marang@kth.se</dc:creator>
    <category>PostsSyndicationFeed</category>
    <syn:updatePeriod>daily</syn:updatePeriod>
    <syn:updateFrequency>1</syn:updateFrequency>
    <syn:updateBase>2006-01-01T00:00+00:00</syn:updateBase>
    <image>
      <url>http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=<?php echo $profileID; ?></url>
      <title>Profile image</title>
      <link>http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=<?php echo $profileID; ?></link>
    </image>
    <?php
      require("dbc.php");
      $query="SELECT * FROM profiles JOIN posts ON (posts.uid=profiles.id) WHERE uid='$profileID' ORDER BY created_time DESC LIMIT 3";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      while ($row=$result->fetch_object()) {
        $uid=$row->uid;
        if($uid == $id){
          $owned = 'true';
        }else{
          $owned = 'false';
        }
        $userName=$row->firstname.' '.$row->lastname;
        $postID=$row->id;
        $message=$row->message;
        $tags=$row->tags;
        $album=$row->album;
        $publishDate=$row->created_time;
        $timeStamp=strtotime($publishDate);
        $dateStamp=date('c', $timeStamp);

        $query2="SELECT fileName, width, height FROM post_photos WHERE id='$postID' ORDER BY fileName ASC";
          if (($result2=mysqli_query($link, $query2)) === false) {
            printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
            exit();
          }
          $media='';
          if(mysqli_num_rows($result2)>0){
            while ($line=$result2->fetch_object()) {
              $fileName=$line->fileName;
              $width=$line->width;
              $height=$line->height;
              $media .= "<media:content url='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage-posts.php?postID=$postID&#38;fileName=$fileName' medium='image' height='$height' width='$width'></media:content>";
            }
          }else{
            $media="<media:content url='' medium='image'></media:content>";
          }

        echo "<item>
      <title>$postID</title>
      <link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
      <description>
        <![CDATA[$message]]>
      </description>
      <author>$userName</author>
      <yn:owned>$owned</yn:owned>
      <guid isPermaLink='false'>$postID</guid>
      <category>$album</category>
      <dc:date>$dateStamp</dc:date>
      <comments>http://xml.csc.kth.se/~marang/DM2517/your-network/fb-comments-feed.php?postID=$postID&#38;only_yn=1</comments>
      <media:thumbnail url='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=$uid' height='75' width='75' />
      <media:group>
          $media
        </media:group>
    </item>
";
      mysqli_free_result($result2);
      }
      mysqli_free_result($result);
    ?>
  </channel>
</rss>
<?php include 'profile-postfix.php';?>