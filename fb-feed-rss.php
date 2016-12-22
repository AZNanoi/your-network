<?php 
  require 'fb-conf.php';
  $access_token = $_SESSION["fb_access_token"];
  $facebook->setAccessToken($access_token);
  $access_token=$facebook->getAccessToken();
  $response=$facebook->api('/me?fields=name,posts.limit(3){created_time,id,message,story,link,full_picture,attachments{media,subattachments{media}}}', 'GET');
  $user=$response['id'];
?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
      xmlns:yn="http://xml.csc.kth.se/~marang/DM2517/your-network/yn-comment.dtd"
    >
  <channel>
    <title><?php echo $response['name']; ?></title>
    <link>http://www.facebook.com/</link>
    <description>Facebook user feed</description>
    <dc:language>en-us</dc:language>
    <?php
      $dateStamp=date("c");
      echo "<dc:date>$dateStamp</dc:date>";
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
      $r_posts=$response['posts']['data'];
      require("dbc.php");
      $strid=strval($user);
      $query="SELECT * FROM posts WHERE uid='$strid' ORDER BY created_time DESC LIMIT 3";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_all($result,MYSQLI_ASSOC);
      $m_response=array_merge($db_response, $r_posts);
      function date_compare($a, $b)
        {
            $t1 = strtotime($a['created_time']);
            $t2 = strtotime($b['created_time']);
            if ($t1==$t2) return 0;
            return ($t1<$t2)?1:-1;
        }
      usort($m_response, 'date_compare');
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

      for ($i=0; $i < sizeof($m_response); $i++) {
        if (substr($m_response[$i]['id'], 0, 2)=='yn'){
          $serialized[] = $m_response[$i];
        }else{
          $test = isInArray($m_response[$i]['id'], $db_response);
          if ($test == false) {
            $serialized[] = $m_response[$i];
          }
        }
      }
      
      foreach($serialized as $post) {
        $itemID=$post['id'];
        $itemID_split=explode('_', $itemID);
        $c_id=$itemID_split[0];
        if($c_id=='yn'){
          $row=$post;
          $userName=$response['name'];
          $postID=$itemID;
          $message=$row['message'];
          $album=$row['album'];
          $publishDate=$row['created_time'];
          $dateStamp=date('Y-m-d \a\t h:i \a\g\o', strtotime($publishDate));
          require("dbc.php");
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
          $com='';
          if($row['fb_id']!='null'){
            $fb_id=$row['fb_id'];
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
        <category>$album</category>
        <dc:date>$dateStamp</dc:date>
        $com
        <media:thumbnail url='https://graph.facebook.com/$user/picture?type=normal'/>
        <media:group>
          $media
        </media:group>
      </item>
  ";
        mysqli_free_result($result2);
        }else{
          $authorID=$c_id;
          $author=$response['name'];
          if (($response['id'])==$authorID){
          $story='';
          $link='http://www.w3schools.com/';
          $full_picture='';
          $message='';
          $height='';
          $width='';
          $postData=$post;
          if (isset($postData['message'])){
            $message=$postData['message'];
            $message=utf8_encode($message);
          }
          $created_time=$postData['created_time'];
          $created_time=date('Y-m-d \a\t h:i \a\g\o', strtotime($created_time));
          if (isset($postData['story'])){
            $strlen=strlen($author);
            $story=$postData['story'];
            $story=substr($story, $strlen);
            $story=utf8_encode($story);
          }
          if (isset($postData['link'])){
            $link=$postData['link'];
          }
          if (isset($postData['full_picture'])){
            $full_picture=$postData['full_picture'];
            list($width, $height)=getimagesize($full_picture);
            $full_picture = preg_replace('/\&/', '&amp;', $full_picture);
          }
          $media="<media:content url='$full_picture' medium='image' height='$height' width='$width'></media:content>";
          if (isset($postData['attachments'])){
            $data=$postData['attachments']['data'];
            if (isset($data[0]['subattachments'])){
              $s_data=$data[0]['subattachments']['data'];
              $media="";
              foreach($s_data as $att){
                $src=$att['media']['image']['src'];
                $src=preg_replace('/\&/', '&#38;', $src);
                $height=$att['media']['image']['height'];
                $width=$att['media']['image']['width'];
                $media .= "<media:content url='$src' medium='image' height='$height' width='$width'></media:content>";
              }
            }
          }
          echo "<item>
        <title>$story</title>
        <link>http://www.w3schools.com/</link>
        <description>
          <![CDATA[$message]]>
        </description>
        <author>$author</author>
        <guid isPermaLink='false'>$itemID</guid>
        <category>album</category>
        <dc:date>$created_time</dc:date>
        <comments>http://xml.csc.kth.se/~marang/DM2517/your-network/fb-comments-feed.php?postID=$itemID&#38;access_token=$access_token</comments>
        <media:thumbnail url='https://graph.facebook.com/$authorID/picture?type=normal'/>
        <media:group>
          $media
        </media:group>
      </item>
      ";
          }
        }
        
      }
    mysqli_free_result($result);
    ?>
  </channel>
</rss>
<?php require "postfix.php"; ?>