<!DOCTYPE html>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
</head>
<body>
<script type="text/javascript">
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      console.log(response.authResponse.accessToken);
      testAPI(response.authResponse.accessToken);
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1689741877905468',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI(accessToken) {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      var img = document.createElement("IMG");
          img.src = "http://graph.facebook.com/"+response.id+"/picture";
      document.getElementById('status3').appendChild(img);
      console.log(JSON.stringify(response));
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';

    });

    var counter = 0;
    FB.api('/me/albums?fields=id,name', function(response) {
      for (var i=0; i<response.data.length; i++) {
        var album = response.data[i];
        if (album.name == 'Profile Pictures'){
          FB.api('/'+album.id+'/photos', function(photos){
            if (photos && photos.data && photos.data.length){
              for (var j=0; j<photos.data.length; j++){
                counter ++;
                var photo = photos.data[j];
                // photo.picture contain the link to picture
                var image = document.createElement('img');
                image.src = 'https://graph.facebook.com/' + photo.id + '/picture?access_token=' + accessToken;
                document.body.appendChild(image);
              }
              document.getElementById('status5').innerHTML =counter;
            }
          });
          break;
        }
      }

    });

  }

  function logoutFB() {
    FB.logout(function(response) {
      console.log('Logging out.... ');
      document.getElementById('status2').innerHTML = 'You are now logged out!';
    });
  }

</script>


<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<fb:login-button scope="public_profile,user_posts" onlogin="checkLoginState();">
</fb:login-button>

<div id="status">
</div>

<a href="http://xml.csc.kth.se/~marang/DM2517/your-network/index2.php" onclick="logoutFB()">Log out</a>

<div id="status2">
</div>

<div class="fb-post" data-href="https://www.facebook.com/10206906971365551/posts/10206568031052255" data-width="500"></div>

<div id="status3">
</div>

<div id="status4">
</div>

<div id="status5">
</div>

<div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><div class="fb-post" data-href="https://www.facebook.com/AhZauNanoi/posts/10206597939119938" data-width="500"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/AhZauNanoi/posts/10206597939119938"><p>Graduation &#xfe51a; ceremony at Karlstad University &#xfe347;</p>Posted by <a href="https://www.facebook.com/AhZauNanoi">Ah Zau Marang</a> on&nbsp;<a href="https://www.facebook.com/AhZauNanoi/posts/10206597939119938">Saturday, October 3, 2015</a></blockquote></div></div>

<div id="status6">
</div>
<div id="status7">
</div>

</body>
</html>