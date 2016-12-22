<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Your Network</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="layout.css"/>
<link rel="stylesheet" type="text/css" href="yn-feed-style.css"/>
</head>
<body>
  <div id="header">
    <div class="header-wrap">
      <div class="logo">
        <img src="images/logo.png" width="70px" height="70px"></img>
        <span style="font-size:30pt; color:white; font-weight:bold; vertical-align: 50%;">Your Network</span>
      </div>
      <div class="login">
      <form action="checkYNlogin.php" autocomplete="off" method="post">
        <table type="border-collapse: collapse;">
          <tr>
            <td width="210px">Email:</td>
            <td width="190px">Password:</td>
            <td width="60px" rowspan="3"><input type="submit" value="Log In" class="submit-rounded-button"/></td>
          </tr>
          <tr>
            <td><input name="userEmail" type="email" required="required" class="login-input"/></td>
            <td><input name="userPassword" type="password" required="required" class="login-input"/></td>
          </tr>
          <tr>
            <td colspan="2"><a href="forgot-login.php" class="fgLogin">Forgot your login name/password?</a></td>
          </tr>
        </table>
      </form>
      </div>
    </div>
  </div>
  <div id="content-bg"></div>
  <div id="wrapper">
    <div id="leftContent" style="float:left; width:200px; margin-top:15px;">
      <div style="width:100px; height=70px;">
        <img src="showImage.php?id=3" width="100" height="70" style="border-radius:10px;">
        <br/>
        <span class="subtitle_font">Ah Zau Marang</span>
      </div>
      <form action="yn-feed.php" autocomplete="off" enctype="multipart/form-data" method="post">
        <input type="file" name="mediaFile"/>
        <input type="submit" name="profilePicSubmit" value="Upload"/>
      </form>
      <?php
        if(isset($_POST['profilePicSubmit'])){
          require("dbc.php");
          $id=3;
          $fileData=mysqli_real_escape_string($link, file_get_contents($_FILES["mediaFile"]["tmp_name"]));
          $fileType=mysqli_real_escape_string($link, $_FILES["mediaFile"]["type"]);
          mysqli_query($link, "UPDATE profiles SET image='$fileData', imageType='$fileType' WHERE id='$id'");
          echo "Uploaded profile picture!";
        }
        else{
          echo "Only images or videos are allowed!";
        }
      ?>
    </div>
    <div id="feedContainer">
      <div class="updateStatus">
        <div style="padding:15px;">
          <span class="subtitle_font">Update Status</span>
        </div>
        <form action="yn-feed.php" autocomplete="off" enctype="multipart/form-data" method="post">
          <div style="float:left; width:100px; height:90px; padding-left:15px; padding-top:5px;">
            <img src="showImage.php?id=3" width="100px" height="70px" style="border-radius:10px;"/>
          </div>
          <div style="float:left; width:450px; height:130px; margin-left:15px;">
            <textarea name="status_message" rows="8" cols="53" placeholder="Write a status" class="status_textarea"></textarea>
          </div>
          <div style="float:left; width:95%; height:80px; padding-left:15px; padding-right:15px;">
            <hr/>
            <div style="float:left; width:130px; height:50px; ">
              <span class="subtitle_font" style="font-size:15px;">Add Photos/Videos:</span>
            </div>
            <div style="float:left; width:420px; height:50px; margin-left:15px; ">
              <input type="file" name="mediaFile" multiple="multiple"/><br/>
              <?php
                if(isset($_POST['submit'])){
                  require("dbc.php");
                  $id=1;
                  $message=$_POST["status_message"];
                  $fileName=mysqli_real_escape_string($link, $_FILES["mediaFile"]["name"]);
                  $fileData=mysqli_real_escape_string($link, file_get_contents($_FILES["mediaFile"]["tmp_name"]));
                  $fileType=mysqli_real_escape_string($link, $_FILES["mediaFile"]["type"]);
                  $tag1=$_POST["search"];
                  $tag2=$_POST["tag2"];
                  $tags="$tag1,$tag2";
                  $dateStamp=date('c');
                  mysqli_query($link, "INSERT INTO posts VALUES('$id', '$message', '$fileData', '$tags', '$dateStamp', 'null', '$fileName')");
                  echo $_POST["tag1"]."Uploaded";
                }
                else{
                  echo "Only images or videos are allowed!";
                }
              ?>
            </div>
          </div>
          <div style="float:left; width:95%; height:110px; padding-left:15px; padding-right:15px;">
            <hr/>
            <div style="float:left; width:130px; height:50px;">
              <span class="subtitle_font" style="font-size:15px;">Tag Friends:</span>
            </div>
            <div style="float:left; width:420px;">
              <input type="text" name="search" value="" class="search-input" style="height:25px; border:0; border-radius:10px;" placeholder="Find Friend"></input>
              <br/>
              <input type="button" name="tag1" value="Ah Zau Marang" class="blue_button"/>
              <input type="button" name="tag2" value="Helen Jonsson" class="blue_button"/>
            </div>
          </div>
          <div style="float:left; width:95%; padding-left:15px; padding-right:15px;">
            <div style="width:100px; float:right;">
              <input type="submit" name="submit" value="Post" class="blue_button" style="color:white; width:70px; height:25px;" />
            </div>
            <div style="width:200px; float:right; margin-right:100px; padding-top:10px;">
              <span class="subtitle_font">Share:</span>
              <img src="images/fb_logo.png" width="30px" height="27px"/>
              <img src="images/tw_logo.png" width="30px" height="27px"/>
              <img src="images/inst_logo.png" width="30px" height="27px"/>
              <img src="images/flickr.png" width="30px" height="27px"/>
            </div>
          </div>          
        </form>
      </div>
      <div class="newsFeed">
        <div class="post">
          <div style="position:relative; float:right; padding-top:7px; padding-right:10px; opacity:0.6;">
            <img src="images/fb_stamp.png" width="27px" height="35px"/>
          </div>
          <div class="message">
            <div class="profilePic">
              <img src="showImage.php?id=3" width="140px" height="90px" style="border-radius:10px;"/>
            </div>
            <span class="font-link" style="font-size:18px;">Helen Jonsson</span><span class="font-standard"><span style="line-height:0.7;"> added a new photo<br/>
            in the album: <a href="" class="subtitle_font">Last Summer...</a><br/></span>
            <span class="font-time">16hrs ago</span><br/>
            <div style="padding-top:5px;">
            In the blue sky just a few specks of gray
In the evening of a beautiful day Though last night 
it rained and more rain on the way And that more 
rain is needed 'twould be fair to say On a gum tree 
in the park the white backed magpie sing
He sings all year round from the Summer to Spring 
But in late Winter and Spring he even sings at night So nice to hear him piping in the moonlight Spring it is with us and Summer is near And beautiful weather for the time of year Such beauty the poets and the artists inspire Of talking of Nature could one ever tire Her green of September Mother Nature wear And the perfumes of blossoms in the evening air.
            </div>
          </span>
          </div>
          <div class="mediaContent">
            <img src="images/photo1.jpg" width="580px" height="350px"/>
          </div>
          <div class="socialSpace">
            <hr/>
            <div style="width:100%; height:70px;">
              <div style="float:left; width:300px;">
                Tags:
                <a href="#" class="font-link">Ah Zau Marang</a>, <a href="#" class="font-link">Saw Naw</a>
              </div>
              <div style="float:right;">
                <table>
                  <tr>
                    <td width="50px" height="45px"><img src="images/profile-photo-b.png" width="35px" height="28px"></td>
                    <td width="50px"><img src="images/comment-button-b.png" width="35px" height="30px"></td>
                    <td><img src="images/share-button-b.png" width="30px" height="30px"></td>
                  </tr>
                  <tr>
                    <td><span class="font-link" style="color:#F16521;">1235</span></td>
                    <td><span class="font-link" style="color:#F16521;">500</span></td>
                    <td><span class="font-link" style="color:#F16521;">125</span></td>
                  </tr>
                </table>
              </div>
            </div>
            <hr/>
            <div style="padding-bottom:10px;">
              <span class="subtitle_font" style="font-size:16px;">View 500 previous comments</span>
            </div>
            <div class="comment" style="width:100%;">
              <div style="float:left; width:90px;">
                <img src="images/profile-photo.png" width="75px" height="50px"/>
              </div>
              <table style="border-collapse: collapse;">
                <tr>
                  <td>
                    <span class="font-link">Helen Josson</span><span class="font-time"> (2h)</span><br/>
                    <div>
                      <span class="font-standard">
                        In the blue sky just a few specks of gray In the evening of a beautiful day
                        In the blue sky just a few specks of gray In the evening of a beautiful day
                        In the blue sky just a few specks of gray In the evening of a beautiful day
                      </span>
                    </div>
                    <div style="float:left; width:80px; height:20px; padding-top:5px; padding-bottom:5px;">
                      <div style="float:left;">
                      <img src="images/profile-photo-b.png" width="20px" height="18px">
                      </div>
                      <div style="float:left; padding:7px;">
                        <span class="font-link" style="color:#F16521; line-height:0.5;">25</span>
                      </div>
                    </div>
                    <div style=" float:left; height:20px; padding-top:5px;">
                      <div style="float:left;">
                      <img src="images/profile-photo-b.png" width="20px" height="18px">
                      </div>
                      <div style="float:left; padding:7px;">
                        <span class="font-link" style="color:#F16521; line-height:0.5;">25</span>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="comment" style="width:100%;">
              <div style="float:left; width:90px;">
                <img src="images/profile-photo.png" width="75px" height="50px"/>
              </div>
              <table style="border-collapse: collapse;">
                <tr>
                  <td>
                    <span class="font-link">Helen Josson</span><span class="font-time"> (2h)</span><br/>
                    <div>
                      <span class="font-standard">
                        In the blue sky just a few specks of gray In the evening of a beautiful day
                      </span>
                    </div>
                    <div style="float:left; width:80px; height:20px; padding-top:5px; padding-bottom:5px;">
                      <div style="float:left;">
                      <img src="images/profile-photo-b.png" width="20px" height="18px">
                      </div>
                      <div style="float:left; padding:7px;">
                        <span class="font-link" style="color:#F16521; line-height:0.5;">25</span>
                      </div>
                    </div>
                    <div style=" float:left; height:20px; padding-top:5px;">
                      <div style="float:left;">
                      <img src="images/profile-photo-b.png" width="20px" height="18px">
                      </div>
                      <div style="float:left; padding:7px;">
                        <span class="font-link" style="color:#F16521; line-height:0.5;">25</span>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="comment" style="width:100%;">
              <div style="float:left; width:90px;">
                <img src="images/profile-photo.png" width="75px" height="50px"/>
              </div>
              <table style="border-collapse: collapse;">
                <tr>
                  <td>
                    <span class="font-link">Helen Josson</span><span class="font-time"> (2h)</span><br/>
                    <div>
                      <span class="font-standard">
                        In the blue sky just a few specks of gray In the evening of a beautiful day
                      </span>
                    </div>
                    <div style="float:left; width:80px; height:20px; padding-top:5px; padding-bottom:5px;">
                      <div style="float:left;">
                      <img src="images/profile-photo-b.png" width="20px" height="18px">
                      </div>
                      <div style="float:left; padding:7px;">
                        <span class="font-link" style="color:#F16521; line-height:0.5;">25</span>
                      </div>
                    </div>
                    <div style=" float:left; height:20px; padding-top:5px;">
                      <div style="float:left;">
                      <img src="images/profile-photo-b.png" width="20px" height="18px">
                      </div>
                      <div style="float:left; padding:7px;">
                        <span class="font-link" style="color:#F16521; line-height:0.5;">25</span>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="comment" style="width:100%;">
              <div style="float:left; width:90px;">
                <img src="images/profile-photo.png" width="75px" height="50px"/>
              </div>
              <table style="border-collapse: collapse;">
                <tr>
                  <td>
                    <textarea name="status_message" rows="2" cols="55" placeholder="Give a comment" class="status_textarea"></textarea>
                  </td>
                </tr>
              </table>
            </div>
          <div>
        </div>
        
      </div>

    </div>
  </div>
</body>
</html>