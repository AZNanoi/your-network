<?php header("Content-type: text/xml; charset=utf-8"); ?>
<?php 
  // Display all warnings
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  session_start();
  require 'facebook-php-sdk/src/facebook.php';
  require 'fb-conf.php';
  // Get User ID
  $user = $facebook->getUser();
  $access_token=$facebook->getAccessToken();
  if ($user) {
    try {
      $user_profile=$facebook->api('/me');
      $response=$facebook->api('/me/feed', 'GET');
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
    <title>Facebook Feed</title>
    <link>http://www.facebook.com/<?php echo $access_token; ?></link>
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
      <url>https://graph.facebook.com/<?php echo $user; ?>/picture</url>
      <title>Profile image</title>
      <link>https://graph.facebook.com/<?php echo $user; ?>/picture</link>
    </image>
    <?php
      foreach($response['data'] as $post) {
        $itemID=$post['id'];
        $itemID_split=explode('_', $itemID);
        $authorID=$itemID_split[0];
        $author_profile=$facebook->api('/'.$authorID, 'GET');
        $author=$author_profile['name'];
        $story=$itemID;
        $link='http://www.w3schools.com/';
        $full_picture='';
        $message='';
        if (($user_profile['id'])==$authorID){
        $file='https://graph.facebook.com/'.$itemID.'?fields=message,created_time,story,link,full_picture&access_token='.$access_token;
        $json_object=file_get_contents($file);
        $postData=json_decode($json_object);
        if (isset($postData->message)){
          $message=$postData->message;
        }
        $created_time=$postData->created_time;
        if (isset($postData->story)){
          $story=$postData->story;
        }
        if (isset($postData->link)){
          $link=$postData->link;
        }
        if (isset($postData->full_picture)){
          $full_picture=$postData->full_picture;
        }
        echo "
    <item>
      <title>$story</title>
      <link>$link</link>
      <description>
        <![CDATA[$message]]>
      </description>
      <author>$author</author>
      <guid isPermaLink='false'>$itemID</guid>
      <category>album</category>
      <dc:date>$created_time</dc:date>
      <comments>http://xml.csc.kth.se/~marang/DM2517/your-network/fb-comments-feed.php?postID=$itemID&access_token=$access_token</comments>
      <media:thumbnail url='https://graph.facebook.com/$authorID/picture' height='75' width='75' />
    </item>
";
      }
      else{
        if (isset($post['message'])){
          $message=$post['message'];
        }
        if (isset($post['created_time'])){
          $created_time=$post['created_time'];
        }
      echo "
    <item>
      <title>$story</title>
      <link>http://www.w3schools.com/</link>
      <description>
        <![CDATA[$message]]>
      </description>
      <author>$author</author>
      <guid isPermaLink='false'>$itemID</guid>
      <dc:date>$created_time</dc:date>
      <media:thumbnail url='https://graph.facebook.com/$authorID/picture' height='75' width='75' />
    </item>
";
      }
      }
    ?>
  </channel>
</rss>