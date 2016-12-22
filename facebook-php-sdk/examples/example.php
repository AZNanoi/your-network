<?php
/**

This SDK is deprecated.  Please use the new SDK found here: https://github.com/facebook/facebook-php-sdk-v4

*/
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
session_start();
require 'facebook-php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '1689741877905468',
  'secret' => '2937a64e8ac842b0d1855d1e6c5d159c',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    $posts = $facebook->api('/me/posts');
    $postID = $posts->data;
    $fID=$postID[1]->id;
    $_SESSION['userName']=$user_profile['name'];
    $_SESSION['userID']=$user_profile['id'];
    session_write_close();
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();

} else {
  $loginUrl = $facebook->getLoginUrl(array('scope' => 'user_posts'));
}


?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <meta charset="UTF-8">
    <title>php-sdk</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1>php-sdk</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>
<pre><?php print_r($fID); ?>:id</pre>
    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>

    <?php if ($user): ?>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php print_r($user_profile); ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>
    <img src="https://external.xx.fbcdn.net/safe_image.php?d=AQAQ4wQ0yFD1luE9&amp;w=130&amp;h=130&amp;url=http\u00253A\u00252F\u00252Fy.cdn-expressen.se\u00252Fimages\u00252Fba\u00252F0a\u00252Fba0a77487b2641f2a6907650a54ed3dc\u00252F912\u00254070.jpg&amp;cfs=1&amp;sx=29&amp;sy=0&amp;sw=480&amp;sh=480"/>
  </body>
</html>
