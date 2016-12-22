window.fbAsyncInit = function() {
    FB.init({
        appId   : '1689741877905468',
        oauth   : true,
        status  : true, // check login status
        cookie  : true, // enable cookies to allow the server to access the session
        xfbml   : true, // parse XFBML
        version : 'v2.5' // use version 2.2
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

function fb_login(){
    FB.login(function(response) {

        if (response.authResponse) {
            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID
            console.log(JSON.stringify(response));
            console.log(response.authResponse.accessToken);
            top.location.href = "http://xml.csc.kth.se/~marang/DM2517/your-network/fb-login.php";

        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {
        scope: 'public_profile,user_posts,publish_actions,user_photos'
    });
};

function logoutFB() {
  FB.logout(function(response) {
    console.log('Logging out.... ');
  });
}