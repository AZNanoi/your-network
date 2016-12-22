<?php header("Content-type: text/xml; charset=utf-8"); ?>
<?php 
  // Display all warnings
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  session_start();
  $postID=$_GET['postID'];
  $access_token=$_GET['access_token'];
  $share_count=0;
  if (substr($postID, 0,2) != 'yn'){
    $file='https://graph.facebook.com/'.$postID.'?fields=shares&access_token='.$access_token;
    $json_object=file_get_contents($file);
    $postData=json_decode($json_object);
    if(array_key_exists('shares', $postData)){
      $share_count=$postData->shares->count;
    }
  }
  echo "<shareCount>$share_count</shareCount>";
?>