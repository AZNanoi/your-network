<?php header("Content-type: text/xml; charset=utf-8"); 
// Display all warnings
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
    >
  <channel>
    <title>Your Network</title>
    <link>http://xml.csc.kth.se/~marang/DM2517/your-network?id=1015712959</link>
    <?php
      $userName=$_GET['userName'];
      echo "<description>$userName</description>";
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
      <url>http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=3</url>
      <title>Profile image</title>
      <link>http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=3</link>
    </image>
    <?php
      require("dbc.php");
      $id=$_GET['userID'];
      $strid=strval($id);
      echo $strid;
      $query="SELECT * FROM posts WHERE id='$strid' ORDER BY publishDate DESC";
      if (($result=mysqli_query($link, $query)) === false) {
        printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        exit();
      }
      while ($row=$result->fetch_object()) {
        $postID=$row->postID;
        $message=$row->message;
        $tags=$row->tags;
        $album=$row->album;
        $imageType=$row->imageType;
        $fileName=$row->fileName;
        $publishDate=$row->publishDate;
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
      <media:thumbnail url='https://graph.facebook.com/$id/picture?type=normal' height='75' width='75' />
      <media:group>
        <media:content url='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage-posts.php?postID=$postID' medium='image'></media:content>
      </media:group>
    </item>
";
      }
      mysqli_free_result($result);
    ?>
  </channel>
</rss>