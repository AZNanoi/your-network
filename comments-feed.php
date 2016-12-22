<?php header("Content-type: text/xml; charset=utf-8"); ?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
    >
  <channel>
  	<?php
  		$postID=$_GET['postID'];
  		echo "<title>Comments on: $postID</title>";
  	?>
  	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
  	<description>Comments on actual post</description>
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
    <?php
    	ini_set("display_errors", "1");
		error_reporting(E_ALL);
    	require("dbc.php");
    	$postID=$_GET['postID'];
    	$postID=mysqli_escape_string($link, $postID);
    	$query="SELECT * FROM comments WHERE postID='$postID' ORDER BY created_time ASC";
    	if (($result=mysqli_query($link, $query)) === false) {
        	printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
        	exit();
      	}
      	while ($row=$result->fetch_object()) {
      		$commenter=$row->commenter;
      		$commenter_ID=$row->commenter_ID;
      		$message=$row->message;
      		$created_time=$row->created_time;
      		echo "
    <item>
      	<title>By: $commenter</title>
      	<link>http://xml.csc.kth.se/~marang/DM2517/your-network</link>
      	<description>
        	<![CDATA[$message]]>
      	</description>
      	<author>$commenter</author>
      	<guid isPermaLink='false'>$commenter_ID</guid>
      	<dc:date>$created_time</dc:date>
        <media:thumbnail url='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id=$commenter_ID' height='75' width='75' />
    </item>
    ";
      	}
      	mysqli_free_result($result);
    ?>
  </channel>
</rss>