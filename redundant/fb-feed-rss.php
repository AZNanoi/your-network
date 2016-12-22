<?php include 'prefix.php';?>
<?php 
  require 'fb-conf.php';
  // Get User ID
  $user = $facebook->getUser();
  $access_token = $_SESSION["fb_access_token"];
  // set it into the facebook object ....
  $facebook->setAccessToken($access_token);
  $access_token=$facebook->getAccessToken();
  if ($user) {
    try {
      $user_profile=$facebook->api('/me');
      $response=$facebook->api('/me/feed?fields=created_time,id', 'GET');
    } catch (FacebookApiException $e) {
      error_log($e);
      $user = null;
    }
  }
?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
    >
  <channel>
    <title><?php echo $user_profile['name']; ?></title>
    <link>http://www.facebook.com/</link>
    <description>Facebook user feed</description>
    <dc:language>en-us</dc:language>
    <?php
      $dateStamp=date("c");
      echo "<dc:date>$dateStamp</dc:date>
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
      $response=$response['data'];
      require("dbc.php");
      $strid=strval($user);
      $query="SELECT id,created_time FROM posts WHERE uid='$strid' ORDER BY created_time DESC";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      $db_response=mysqli_fetch_all($result,MYSQLI_ASSOC);
      $m_response=array_merge($response,$db_response);
      function date_compare($a, $b)
        {
            $t1 = strtotime($a['created_time']);
            $t2 = strtotime($b['created_time']);
            if ($t1==$t2) return 0;
            return ($t1<$t2)?-1:1;
        }
      usort($m_response, 'date_compare');
      $m_response=array_reverse($m_response);
      $eNum=count($m_response);
      if($eNum > 2){
        $s_response=array_slice($m_response,0,2);
      }
      foreach($s_response as $post) {
        $itemID=$post['id'];
        $itemID_split=explode('_', $itemID);
        $c_id=$itemID_split[0];
        if($c_id=='yn'){
          $query2="SELECT * FROM posts WHERE id='$itemID'";
          if (($result2=mysqli_query($link, $query2)) === false) {
            printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
            exit();
          }
          while ($row=$result2->fetch_object()) {
            $userName=$user_profile['name'];
            $postID=$itemID;
            $message=$row->message;
            $tags=$row->tags;
            $album=$row->album;
            $imageType=$row->imageType;
            $fileName=$row->fileName;
            $publishDate=$row->created_time;
            $dateStamp=date('Y-m-d \a\t h:i \a\g\o', strtotime($publishDate));
          echo "<item>
        <title>$fileName</title>
        <link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
        <description>
          <![CDATA[$message]]>
        </description>
        <author>$userName</author>
        <guid isPermaLink='false'>$postID</guid>
        <category>$album</category>
        <dc:date>$dateStamp</dc:date>
        <comments>http://xml.csc.kth.se/~marang/DM2517/your-network/comments-feed.php?postID=$postID</comments>
        <media:thumbnail url='https://graph.facebook.com/$user/picture?type=normal' height='75' width='75' />
        <media:group>
          <media:content url='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage-posts.php?postID=$postID' medium='image'></media:content>
        </media:group>
      </item>
  ";
          }
          mysqli_free_result($result2);
        }else{
          $authorID=$c_id;
          $author_profile=$facebook->api('/'.$authorID, 'GET');
          $author=$author_profile['name'];
          if (($user_profile['id'])==$authorID){
          $story='';
          $link='http://www.w3schools.com/';
          $full_picture='';
          $message='';
          $height='';
          $width='';
          $file='https://graph.facebook.com/'.$itemID.'?fields=message,created_time,story,link,full_picture,attachments&access_token='.$access_token;
          $json_object=file_get_contents($file);
          $postData=json_decode($json_object);
          if (isset($postData->message)){
            $message=$postData->message;
          }
          $created_time=$postData->created_time;
          $created_time=date('Y-m-d \a\t h:i \a\g\o', strtotime($created_time));
          if (isset($postData->story)){
            $strlen=strlen($author);
            $story=$postData->story;
            $story=substr($story, $strlen);
          }
          if (isset($postData->link)){
            $link=$postData->link;
          }
          if (isset($postData->full_picture)){
            $full_picture=$postData->full_picture;
            $full_picture = preg_replace('/\&/', '&amp;', $full_picture);
          }
          $media="<media:content url='$full_picture' medium='image' height='$height' width='$width'></media:content>";
          if (isset($postData->attachments)){
            $data=$postData->attachments->data;
            if (isset($data[0]->subattachments)){
              $s_data=$data[0]->subattachments->data;
              $height=$s_data[0]->media->image->height;
              $width=$s_data[0]->media->image->width;
              $media="";
              foreach($s_data as $att){
                $src=$att->media->image->src;
                $src=preg_replace('/\&/', '&#38;', $src);
                $media .= "<media:content url='$src' medium='image' height='$height' width='$width'></media:content>";
              }
            }else{
              $height=$data[0]->media->image->height;
              $width=$data[0]->media->image->width;
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
<?php include 'postfix.php';?>