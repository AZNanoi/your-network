<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:rss="http://purl.org/rss/1.0/"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
                xmlns="http://www.w3.org/1999/xhtml"
                xmlns:media="http://search.yahoo.com/mrss/"
                version="1.0">
<xsl:output indent="yes"/>
<xsl:param name="userName"/>
<xsl:param name="userID"/>

<xsl:template match="rss">
	<html>
		<head>
			<link rel="shortcut icon" href="images/logo.png"/>
			<title><xsl:value-of select="channel/title"/></title>
			<meta charset="UTF-8"/>
			<link rel="stylesheet" type="text/css" href="layout.css"/>
			<link rel="stylesheet" type="text/css" href="yn-feed-style.css"/>		
			<script type="text/javascript">
				function storeLike(postID){
    				if (window.XMLHttpRequest) {
    					// code for IE7+, Firefox, Chrome, Opera, Safari
    					var xmlhttp=new XMLHttpRequest();
  					} else {  // code for IE6, IE5
    					var xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  					}
  					xmlhttp.onreadystatechange=function(){
    					if(xmlhttp.readyState==4){
        					if (xmlhttp.status==200){
                				document.getElementById(postID).innerHTML = xmlhttp.responseText;
                				document.getElementById(postID+'_button').style.backgroundPosition = "2px -31px";
            				}
        				}
    				};
    				xmlhttp.open("GET","store-likes.php?postID="+postID,true);
    				xmlhttp.send();
    				return false;
				}
			</script>
		</head>
		<body>
			<div id="header" style="height:40px; position: fixed; z-index:1000;">
				<div class="header-wrap">
					<div class="logo" style="margin-top:5px;">
						<img src="images/logo.png" width="30px" height="30px"/>
						<span style="font-size:15pt; color:white; font-weight:bold; vertical-align: 50%;">Your Network</span>
					</div>
					<div style="float:right; margin-top:5px;">
						<img src="images/fb_logo.png" width="30px" height="30px" style="border-radius:15px;"/>
						<img src="images/tw_logo.png" width="30px" height="30px" style="border-radius:15px;"/>
						<img src="images/inst_logo.png" width="30px" height="30px" style="border-radius:15px;"/>
						<img src="images/logo.png" width="30px" height="30px" style="border-radius:15px;"/>
					</div>
				</div>
			</div>
			<div id="wrapper" style="top:50px;">
				<div id="leftContent" style="float:left; width:200px; margin-top:15px;">
			      <div style="width:100px; height=70px;">
			        <img src="{channel/image/url}" width="100" height="70" style="border-radius:10px;"/>
			        <br/>
			        <span class="subtitle_font"><xsl:value-of select="$userName"/></span>
			      </div>
			      <form action="store-data.php?id=3" autocomplete="off" enctype="multipart/form-data" method="post">
			        <input type="file" name="mediaFile"/>
			        <input type="submit" name="profilePicSubmit" value="Upload"/>
			      </form>
			    </div>
			    <div id="feedContainer">
			      <div class="updateStatus">
			        <div style="padding:15px;">
			          <span class="subtitle_font">Update Status</span>
			        </div>
			        <form action="store-data.php?id=3" autocomplete="off" enctype="multipart/form-data" method="post">
			          <div style="float:left; width:100px; height:90px; padding-left:15px; padding-top:5px;">
			            <img src="{channel/image/url}" width="100px" height="70px" style="border-radius:10px;"/>
			          </div>
			          <div style="float:left; width:450px; height:130px; margin-left:15px;">
			            <textarea name="status_message" rows="8" cols="53" placeholder="Write a status" class="status_textarea"><xsl:value-of select="''"/></textarea>
			          </div>
			          <div style="float:left; width:95%; height:80px; padding-left:15px; padding-right:15px;">
			            <hr/>
			            <div style="float:left; width:130px; height:50px; ">
			              <span class="subtitle_font" style="font-size:15px;">Add Photos/Videos:</span>
			            </div>
			            <div style="float:left; width:420px; height:50px; margin-left:15px; ">
			              <input type="file" name="mediaFile" multiple="multiple"/><br/>
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
			            <div style="width:70px; float:right;">
			              <input type="submit" name="postSubmit" value="Post" class="blue_button_submit"/>
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
			      	<xsl:for-each select="channel/item">
			      		<xsl:apply-templates select="."/>
			      	</xsl:for-each>
			      </div>
			    </div>
			</div>
		</body>
	</html>
</xsl:template>

<xsl:template match="item">
	<xsl:variable name="postID" select="guid"/>
	<xsl:variable name="like_items" select="document('http://xml.csc.kth.se/~marang/DM2517/your-network/likes-feed.php')"/>
	<div class='post'>
        <div style='position:relative; float:right; padding-top:7px; padding-right:10px; opacity:0.6;'>
          <img src='images/fb_stamp.png' width='27px' height='35px'/>
        </div>
        <div class='message'>
          <div class='profilePic'>
          	<img src='{media:thumbnail/@url}' width='140px' height='90px' style='border-radius:10px;'/>
          </div>
          <span class='font-link' style='font-size:18px;'><xsl:value-of select="author"/></span>
          <span class='font-standard'>
            <span style='line-height:0.7;'> added a new photo<br/>
            in the album: <a href='' class='subtitle_font'><xsl:value-of select="category"/></a><br/></span>
            <span class='font-time'><xsl:value-of select="dc:date"/>hrs ago</span><br/>
            <div style='padding-top:5px;'><xsl:value-of select="description"/></div>
          </span>
        </div>
        <div class='mediaContent'>
          <img src='{media:content/@url}' width='580px' height='350px'/>
        </div>
        <div class='socialSpace'>
            <hr/>
            <div style='width:100%; height:70px;'>
              <div style='float:left; width:300px;'>
                Tags:
                <a href='#' class='font-link'>Ah Zau Marang</a>, <a href='#' class='font-link'>Saw Naw</a>
              </div>
              <div style='float:right;'>
                <table style="text-align:center;">
                  <tr>
                    <td width='50px' height='45px'>
                    	<a href='javascript:void(0);' class='like-button' id="{$postID}_button" data-postid="{$postID}" onclick="storeLike(this.getAttribute('data-postid'));">
                    		<xsl:if test="$like_items//item[description=$postID and guid=$userID]">
                    			<xsl:attribute name="style">background-position: 2px -31px;</xsl:attribute>
                    		</xsl:if>
                    	</a>
                    </td>
                    <td width='50px'>
                    	<a href='#' class='comment-button'></a>
                    </td>
                    <td>
                    	<a href='#' class='share-button'></a>
                    </td>
                  </tr>
                  <tr class='font-link' style='color:#F16521;'>
                    <td id="{$postID}">
                    	<xsl:value-of select="count($like_items//item[description=$postID])"/>
                    </td>
                    <td>500</td>
                    <td>125</td>
                  </tr>
                </table>
              </div>
            </div>
            <hr/>
            <div style='padding-bottom:10px;'>
              <span class='subtitle_font' style='font-size:16px;'>View 500 previous comments</span>
            </div>
            <xsl:variable name="file" select="comments"/>
            <xsl:variable name="comment_items" select="document($file)"/>
        	<xsl:for-each select="$comment_items/rss/channel/item">
            	<div class='comment' style='width:100%;'>
              		<div style='float:left; width:90px;'>
                		<img src='http://xml.csc.kth.se/~marang/DM2517/your-network/showImage.php?id={guid}' width='75px' height='50px'/>
              		</div>
              		<table style='border-collapse: collapse;'>
                		<tr>
                  		<td>
                    		<span class='font-link'><xsl:value-of select="author"/></span><span class='font-time'> (<xsl:value-of select="dc:date"/>)</span><br/>
                    		<div>
                      		<span class='font-standard'><xsl:value-of select="description"/></span>
                    		</div>
                    		<div style='float:left; width:80px; height:20px; padding-top:5px; padding-bottom:5px;'>
                      		<div style='float:left;'>
                      		<img src='images/profile-photo-b.png' width='20px' height='18px'/>
                      		</div>
                      		<div style='float:left; padding:7px;'>
                        		<span class='font-link' style='color:#F16521; line-height:0.5;'>25</span>
                      		</div>
                    		</div>
                    		<div style=' float:left; height:20px; padding-top:5px;'>
                      		<div style='float:left;'>
                      		<img src='images/comment-button-b.png' width='20px' height='18px'/>
                      		</div>
                      		<div style='float:left; padding:7px;'>
                        		<span class='font-link' style='color:#F16521; line-height:0.5;'>25</span>
                      		</div>
                    		</div>
                  		</td>
                		</tr>
              		</table>
            	</div>
        	</xsl:for-each>
            <div class='comment' style='width:100%;'>
              <div style='float:left; width:90px;'>
                <img src='images/profile-photo.png' width='75px' height='50px'/>
              </div>
              <table style='border-collapse: collapse;'>
                <tr>
                  <td>
                  	<form action="store-data.php?id=3&#38;postID={guid}&#38;commentSubmit=1" method="post">
                    	<input type="text" name='comment_message' style="width:465px; height:50px;" placeholder='Give a comment' class='status_textarea' ></input>
                    </form>
                  </td>
                </tr>
              </table>
            </div>
        </div>
    </div>
</xsl:template>


</xsl:stylesheet>