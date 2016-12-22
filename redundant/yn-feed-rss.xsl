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
<xsl:param name="access_token"/>
<xsl:param name="fetch_num"/>
<xsl:template match="rss">
	<html>
		<head>
			<link rel="shortcut icon" href="images/logo.png"/>
			<title><xsl:value-of select="channel/title"/></title>
			<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="layout.css"/>
			<link rel="stylesheet" type="text/css" href="yn-feed-style.css"/>
			<script src="store-likes.js"><xsl:value-of select="''"/></script>
      <script src="fb-login.js"><xsl:value-of select="''"/></script>
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
            <a href="http://xml.csc.kth.se/~marang/DM2517/your-network/logout.php">Log out</a>
					</div>
				</div>
			</div>
			<div id="wrapper" style="top:50px;">
				<div id="leftContent" style="float:left; width:200px; margin-top:15px;">
			      <div style="width:100px; height=70px;">
			        <img src="{channel/image/url}" width="100" height="70" style="border-radius:10px;"/>
			        <br/>
			        <span class="subtitle_font"><xsl:value-of select="channel/title"/></span>
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
			        <form action="store-data.php" autocomplete="off" enctype="multipart/form-data" method="post">
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
              <xsl:variable name="xmlfile">
                <xsl:text>http://xml.csc.kth.se/~marang/DM2517/your-network/yn-feed-rss.php?userID=</xsl:text><xsl:value-of select="$userID"/><xsl:text>&#38;userName=</xsl:text><xsl:value-of select="$userName"/>
              </xsl:variable>
              <xsl:for-each select="(/ | document($xmlfile))//item">
                <xsl:sort select="dc:date" order="descending"/>
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
  <xsl:variable name="fb-likes-feed">
    <xsl:text>http://xml.csc.kth.se/~marang/DM2517/your-network/fb-likes-feed.php?postID=</xsl:text><xsl:value-of select="$postID"/><xsl:text>&#38;access_token=</xsl:text><xsl:value-of select="$access_token"/>
  </xsl:variable>
  <xsl:variable name="fb-share-feed">
    <xsl:text>http://xml.csc.kth.se/~marang/DM2517/your-network/fb-share-feed.php?postID=</xsl:text><xsl:value-of select="$postID"/><xsl:text>&#38;access_token=</xsl:text><xsl:value-of select="$access_token"/>
  </xsl:variable>
  <xsl:variable name="read-tags">
    <xsl:text>http://xml.csc.kth.se/~marang/DM2517/your-network/read-tags.php?postID=</xsl:text><xsl:value-of select="$postID"/><xsl:text>&#38;access_token=</xsl:text><xsl:value-of select="$access_token"/>
  </xsl:variable>
	<xsl:variable name="fb_like_items" select="document($fb-likes-feed)/rss/channel"/>
  <xsl:variable name="fb_share_items" select="document($fb-share-feed)"/>
  <xsl:variable name="file" select="comments"/>
  <xsl:variable name="comment_items" select="document($file)"/>
  <xsl:variable name="like_items" select="document('http://xml.csc.kth.se/~marang/DM2517/your-network/likes-feed.php')"/>
  <xsl:variable name="tag_items" select="document($read-tags)"/>
	<div class='post'>
        <div style='position:relative; float:right; padding-top:7px; padding-right:10px; opacity:0.6;'>
          <xsl:choose>
            <xsl:when test="substring($postID,1,2)='yn'">
              <img src='images/yn_stamp.png' width='27px' height='35px'/>
            </xsl:when>
            <xsl:otherwise>
              <img src='images/fb_stamp.png' width='27px' height='35px'/>
            </xsl:otherwise>
          </xsl:choose>
        </div>
        <div class='message'>
          <div class='profilePic'>
          	<img src='{media:thumbnail/@url}' width='90px' height='70px' style='border-radius:10px;'/>
          </div>
          <span class='font-link' style='font-size:18px;'><xsl:value-of select="author"/></span>
          <span class='font-standard'>
            <xsl:choose>
              <xsl:when test="substring($postID,1,2)!='yn'">
                <span style='line-height:0.7;'><xsl:value-of select="title"/><br/></span>
              </xsl:when>
              <xsl:otherwise>
                <span style='line-height:0.7;'> added a new photo<br/>
                in the album: <a href='' class='subtitle_font'><xsl:value-of select="category"/></a><br/>
                </span>
              </xsl:otherwise>
            </xsl:choose>
            <span class='font-time'><xsl:value-of select="dc:date"/></span><br/>
            <div style='padding-top:5px;'><xsl:value-of select="description"/></div>
          </span>
        </div>
        <xsl:variable name="media_src" select="media:group/media:content/@url"/>
        <xsl:if test='$media_src!=""'>
          <xsl:for-each select="media:group/media:content">
            <xsl:variable name="media_url" select="./@url"/>
            <xsl:variable name="width" select="./@width"/>
            <xsl:variable name="height" select="./@height"/>
            <xsl:choose>
              <xsl:when test="position()=1">
                <div class='mediaContent'>
                  <xsl:choose>
                    <xsl:when test="$height > $width">
                      <img src='{$media_url}' width="280" height="auto"/>
                    </xsl:when>
                    <xsl:otherwise>
                      <img src='{$media_url}' width="580" height="auto"/>
                    </xsl:otherwise>
                  </xsl:choose>
                </div>
              </xsl:when>
              <xsl:otherwise>
                <div class='mc_gallery'>
                  <xsl:choose>
                    <xsl:when test="$height > $width">
                      <div class="mc-g-box1" style="width:80px;">
                        <img src='{$media_url}' width="100%" height="auto" style="position:relative;"/>
                      </div>
                    </xsl:when>
                    <xsl:otherwise>
                      <div class="mc-g-box1">
                        <img src='{$media_url}' width="140%" height="auto" style="left:-15%;top:-10%;position:relative;"/>
                      </div>
                    </xsl:otherwise>
                  </xsl:choose>
                </div>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </xsl:if>
        <div class='socialSpace'>
            <div style='width:100%; height:70px;'>
              <div style='float:left; width:300px;'>
                Tags:
                <xsl:if test="$tag_items/items/tag/@id!='null'">
                  <xsl:for-each select="$tag_items/items/tag">
                    <xsl:if test="position()>1">
                      <xsl:text>,</xsl:text>
                    </xsl:if>
                    <a href='#' class='font-link'><xsl:value-of select="."/></a>
                  </xsl:for-each>
                </xsl:if>
              </div>
              <div style='float:right;'>
                <table style="text-align:center;">
                  <tr>
                    <td width='50px' height='45px'>
                    	<a href='javascript:void(0);' class='like-button' id="{$postID}_button" data-postid="{$postID}" onclick="storeLike(this.getAttribute('data-postid'));">
                    		<xsl:if test="$fb_like_items/dc:creator=true or $like_items//item[description=$postID and guid=$userID]">
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
                      <xsl:choose>
                        <xsl:when test="substring($postID,1,2)='yn'">
                          <xsl:value-of select="count($like_items//item[description=$postID])"/>
                        </xsl:when>
                        <xsl:otherwise>
                          <xsl:value-of select="$fb_like_items/description"/>
                        </xsl:otherwise>
                      </xsl:choose>
                    </td>
                    <td><xsl:value-of select="count($comment_items/rss/channel/item)"/></td>
                    <td><xsl:value-of select="$fb_share_items/shareCount"/></td>
                  </tr>
                </table>
              </div>
            </div>
            <hr/>
            <xsl:if test="$comment_items/rss/channel/item/comments">
              <xsl:variable name="eNum" select="$comment_items/rss/channel/item/comments"/>
              <div style='padding-bottom:10px;'>
                <span class='subtitle_font' style='font-size:16px;'>View <xsl:value-of select="$eNum"/> previous 
                <xsl:choose>
                  <xsl:when test="number($eNum) > 1">
                    comments
                  </xsl:when>
                  <xsl:otherwise>
                    comment
                  </xsl:otherwise>
                </xsl:choose>
                </span>
              </div>
            </xsl:if>
        	  <xsl:for-each select="$comment_items/rss/channel/item">
            	<div class='comment' style='width:100%;'>
              		<div style='float:left; width:70px;'>
                		<img src='{media:thumbnail/@url}' width='50px' height='50px' style='border-radius:10px;'/>
              		</div>
              		<table style='border-collapse: collapse;'>
                		<tr>
                  		<td>
                    		<span class='font-link'><xsl:value-of select="author"/></span><span class='font-time'> (<xsl:value-of select="dc:date"/>)</span><br/>
                    		<div>
                      		<span class='font-standard'><xsl:value-of select="description"/></span><br/>
                          <xsl:if test="media:content">
                            <img src='{media:content/@url}' alt="image" width='100px' height='100px'/>
                          </xsl:if>
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
              <div style='float:left; width:70px;'>
                <img src='images/profile-photo.png' width='50px' height='50px' style='border-radius:10px;'/>
              </div>
              <table style='border-collapse: collapse;'>
                <tr>
                  <td>
                  	<form action="store-data.php?id=3&#38;postID={guid}&#38;commentSubmit=1" method="post">
                    	<input type="text" name='comment_message' style="width:485px; height:50px;" placeholder='Give a comment' class='status_textarea' ></input>
                    </form>
                  </td>
                </tr>
              </table>
            </div>
        </div>
    </div>
</xsl:template>


</xsl:stylesheet>