<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:rss="http://purl.org/rss/1.0/"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
                xmlns="http://www.w3.org/1999/xhtml"
                xmlns:media="http://search.yahoo.com/mrss/"
                xmlns:yn="http://xml.csc.kth.se/~marang/DM2517/your-network/yn-comment.dtd"
                version="1.0">
<xsl:output doctype-system="string" indent="yes" encoding="UTF-8"/>
<xsl:param name="userName"/>
<xsl:param name="userID"/>
<xsl:param name="access_token"/>
<xsl:template match="rss">
  <html>
    <head>
      <link rel="shortcut icon" href="images/icon_logo.png"/>
      <title><xsl:value-of select="channel/title"/></title>
      <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" type="text/css" href="yn-feed-style.css"/>
      <script src="jquery-1.11.3.js" defer="defer"><xsl:value-of select="''"/></script>
      <script src="jquery.fs.dropper.js" defer="defer"><xsl:value-of select="''"/></script>
      <script src="yn-scripts.js" defer="defer"><xsl:value-of select="''"/></script>
    </head>
    <body>
      <div id="header" class="lr">
        <div class="header-wrap lr m_lr">
          <div class="logo f-l">
            <img src="images/logo.png" width="30px" height="30px"/>
            <span class="f_logo">Your Network</span>
          </div>
          <div class="searchContainer">
            <input type="text" class="search" id="searchid" placeholder="Search for people" /> 
            <div id="searchResult"><xsl:value-of select="''"/></div>
          </div>
          <div class="r-logo">
            <a href="http://xml.csc.kth.se/~marang/DM2517/your-network/" style="text-decoration:none; color:white;">My profile</a>
            <a href="public-feed.php"><img src="images/logo.png"/></a>
            <img src="images/fb_logo.png"/>
            <img src="images/tw_logo.png"/>
            <img src="images/inst_logo.png"/>
            <a href="http://xml.csc.kth.se/~marang/DM2517/your-network/logout.php" style="text-decoration:none; color:white;">Log out</a>
          </div>
        </div>
      </div>
      <div id="wrapper" class="lr m_lr">
          <div id="leftContent" class="f-l">
            <div id="profile-thumbnail" class="p-pic br10">
              <img src="{channel/image/url}" class="p-pic br10" style="z-index:-2"/>
              <span style="display:none;">Update profile picture</span>
            </div>
            <span class="subtitle_font"><xsl:value-of select="channel/title"/></span>
          </div>
          <div id="feedContainer">
            <span class="subtitle_font" style="font-size:18px;"><xsl:value-of select="channel/description"/></span>
            <div id="statusForm" class="updateStatus br15">
              <div style="padding:15px;">
                <span class="subtitle_font">Update Status</span>
              </div>
              <form id="uploadPost" autocomplete="off" enctype="multipart/form-data" method="post">
                <div class="p-margin f-l">
                  <img src="{channel/image/url}" class="p-pic br10"/>
                </div>
                <div id="textField" class="s_1 f-l">
                  <textarea name="status_message" placeholder="Write a status" class="status_textarea br15 f-l b0" style="min-width: 95%; max-width: 95%; min-height:75px;"><xsl:value-of select="''"/></textarea>
                </div>
                <div id="addPic" class="p-margin f-l" style="width:95%;">
                  <a href="javascript:void(0);" onclick="addPhoto();" class="subtitle_font">Add Photos/Videos:</a>
                </div>
                <div id="addTag" class="p-margin f-l">
                  <a href="javascript:void(0);" onclick="addTag();" class="subtitle_font">Tag Friends:</a>
                </div>
                <div class="s-box" style="margin-top:-15px;">
                  <div style="width:70px; float:right;">
                    <input type="submit" name="postSubmit" value="Post" class="blue_button_submit br10 b0 p_b"/>
                  </div>
                  <div class="p-share p-share-m">
                    <span class="subtitle_font">Share:</span>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-fb" data-selected="false" data-app="fb"><xsl:value-of select="''"/></a>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-tw" data-selected="false" data-app="tw"><xsl:value-of select="''"/></a>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-inst" data-selected="false" data-app="inst"><xsl:value-of select="''"/></a>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-fr" data-selected="false" data-app="fr"><xsl:value-of select="''"/></a>
                  </div>
                </div>
              </form>
            </div>
            
            <div class="newsFeed">
              <xsl:for-each select=".//item">
                <xsl:sort select="dc:date" order="descending"/>
                <xsl:apply-templates select="."/>
              </xsl:for-each>
              <div id="last_post_loader" class="br15"></div>
            </div>
          </div>
      </div>
    </body>
  </html>
</xsl:template>


<xsl:template match="item">
  <xsl:variable name="postID" select="guid"/>
  <xsl:variable name="read-tsl">
    <xsl:text>http://xml.csc.kth.se/~marang/DM2517/your-network/read-tsl.php?summary=1&#38;postID=</xsl:text><xsl:value-of select="$postID"/><xsl:text>&#38;userID=</xsl:text><xsl:value-of select="$userID"/><xsl:text>&#38;access_token=</xsl:text><xsl:value-of select="$access_token"/>
  </xsl:variable>
  <xsl:variable name="file" select="comments"/>
  <xsl:variable name="comment_items" select="document($file)//channel"/>
  <xsl:variable name="tsl_items" select="document($read-tsl)"/>
  <xsl:variable name="cid" select="substring($postID,1,2)"/>
  <div class='post' id="{$postID}">
        <div class='story m_lr'>
          <div class="stamp">
            <xsl:choose>
              <xsl:when test="$cid='yn'">
                <img src='images/yn_stamp.png'/>
              </xsl:when>
              <xsl:otherwise>
                <img src='images/fb_stamp.png'/>
              </xsl:otherwise>
            </xsl:choose>
            <xsl:if test="yn:owned = 'true'">
              <a href="javascript:void(0);" onclick="display_pEditor(event, this);"><img src='images/edit.png' style="display:block; width:25px; height:25px;"/></a>
            </xsl:if>
          </div>
          <div class='profilePic br10 f-l'>
            <img src='{media:thumbnail/@url}'/>
          </div>
          <span class='font-link' style='font-size:18px;'><xsl:value-of select="author"/></span>
          <span class='font-standard'>
            <xsl:choose>
              <xsl:when test="$cid!='yn'">
                <span style='line-height:0.7;'><xsl:value-of select="title"/></span><br/>
              </xsl:when>
              <xsl:otherwise>
                <span style='line-height:0.7;'> added a new photo<br/>
                in the album: <a href='' class='subtitle_font'><xsl:value-of select="category"/></a><br/>
                </span>
              </xsl:otherwise>
            </xsl:choose>
            <span class='font-time'><xsl:value-of select="dc:date"/></span>
            <div class="message"><xsl:value-of select="description"/></div>
          </span>
        </div>
        <xsl:variable name="media_first" select="media:group/media:content[1]"/>
        <xsl:if test='$media_first/@url!=""'>
          <xsl:variable name="width_f" select="$media_first/@width"/>
          <xsl:variable name="height_f" select="$media_first/@height"/>
          <div id="{$postID}_imgViewer" class='mediaContent m_lr'>
            <xsl:choose>
              <xsl:when test="$height_f >= $width_f">
                <img src='{$media_first/@url}' style="max-height:inherit;"/>
              </xsl:when>
              <xsl:otherwise>
                <img src='{$media_first/@url}' style="max-width:inherit;"/>
              </xsl:otherwise>
            </xsl:choose>
          </div>
          <xsl:if test="count(media:group/media:content)>1">
            <div class='mc_gallery m_lr'>
              <xsl:for-each select="media:group/media:content">
                <xsl:variable name="media_url" select="./@url"/>
                <xsl:variable name="width" select="./@width"/>
                <xsl:variable name="height" select="./@height"/>
                <xsl:variable name="picID">
                  <xsl:value-of select="$postID"/><xsl:text>_pic_</xsl:text><xsl:value-of select="position()"/>
                </xsl:variable>
                <xsl:choose>
                  <xsl:when test="position()=1">
                    <xsl:choose>
                        <xsl:when test="$height >= $width">
                          <div id="subImage" class="selected" style="width:120px">
                            <img class="portrait" src='{$media_url}'/>
                          </div>
                        </xsl:when>
                        <xsl:otherwise>
                          <div id="subImage" class="selected" style="width:120px">
                            <img class="landscape" src='{$media_url}' style="width:140%;left:-15%;top:-10%;"/>
                          </div>
                        </xsl:otherwise>
                      </xsl:choose>
                  </xsl:when>
                  <xsl:otherwise>
                      <xsl:choose>
                        <xsl:when test="$height >= $width">
                          <div id="subImage">
                            <img class="portrait" src='{$media_url}' style="width:140%;left:-15%;top:-10%;"/>
                          </div>
                        </xsl:when>
                        <xsl:otherwise>
                          <div id="subImage">
                            <img class="landscape" src='{$media_url}' style="height:100%;width:auto;left:-15%;"/>
                          </div>
                        </xsl:otherwise>
                      </xsl:choose>
                  </xsl:otherwise>
                </xsl:choose>
              </xsl:for-each>
            </div>
          </xsl:if>
        </xsl:if>
        <div class='socialSpace m_lr'>
            <div style='width:100%; overflow: hidden;'>
              <div style='float:left; width:300px;'>
                Tags:
                <xsl:if test="$tsl_items//tag/@id!='null'">
                  <xsl:for-each select="$tsl_items//tag">
                    <xsl:if test="position()>1">
                      <xsl:text>,</xsl:text>
                    </xsl:if>
                    <a href='#' class='font-link'><xsl:value-of select="."/></a>
                  </xsl:for-each>
                </xsl:if>
              </div>
              <div style='float:right; width:180px'>
                <table style="text-align:center; float:right;">
                  <tr>
                    <td width='50px' height='45px'>
                      <a href='javascript:void(0);' class='like-button bt' id="{$postID}_button" data-postid="{$postID}" onclick="storeLike(this.getAttribute('data-postid'));">
                        <xsl:if test="$tsl_items//likeCount/@id='true'">
                          <xsl:attribute name="style">background-position: 2px -31px;</xsl:attribute>
                        </xsl:if>
                      </a>
                    </td>
                    <td width='50px'>
                      <a href="javascript:void(0);" onclick="switchClick(event, this.getAttribute('data-postid'));" data-postid="{$postID}" class='comment-button bt'></a>
                    </td>
                    <td>
                      <a href='#' class='share-button bt'></a>
                    </td>
                  </tr>
                  <tr class='font-link' style='color:#F16521;'>
                    <td id="{$postID}_likes">
                      <a href="javascript:void(0);" onclick="viewApps(this);" data-postid="{$postID}" data-yn="{$tsl_items//likeCount/yn}" data-fb="{$tsl_items//likeCount/fb}" data-tw="0" data-displayed="false" data-kind="l" class="font-link" style="color:#F16521;">
                        <xsl:value-of select="$tsl_items//likeCount/total"/>
                      </a>
                    </td>
                    <td id="{$postID}_comments">
                      <a href="javascript:void(0);" onclick="viewApps(this);" data-postid="{$postID}" data-yn="{$comment_items/yn:ynComCount}" data-fb="{$comment_items/yn:fbComCount}" data-tw="0" data-displayed="false" data-kind="c" class="font-link" style="color:#F16521;">
                        <xsl:value-of select="$comment_items/yn:totalComCount"/>
                      </a>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class='font-link' style="color:#F16521;">
                        <xsl:value-of select="$tsl_items//shareCount"/>
                      </a>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <hr/>
            <div id="{$postID}_comWrapper" style="display:none;"><xsl:value-of select="''"/></div>
            <div id="{$postID}_comWrapperOri">
              <xsl:value-of select="''"/>
              <xsl:if test="$comment_items//comments">
                <xsl:variable name="eNum" select="$comment_items//comments"/>
                <div style='padding-bottom:10px;'>
                  <a href="javascript:void(0);" id="{$postID}" data-app="all" data-remain="{$eNum}" data-viewed="2" class='subtitle_font' onclick="fetchComment(this);" style='font-size:16px;'>View <xsl:value-of select="$eNum"/> previous 
                  <xsl:choose>
                    <xsl:when test="number($eNum) > 1">
                      comments
                    </xsl:when>
                    <xsl:otherwise>
                      comment
                    </xsl:otherwise>
                  </xsl:choose>
                  </a>
                </div>
              </xsl:if>
              <xsl:for-each select="$comment_items/item">
                <xsl:variable name="com_ID" select="guid"/>
                <div id="{$com_ID}" class='comment'>
                    <div class="c-pic f-l">
                      <a href="https://www.facebook.com/{yn:authorID}" target="blank"><img src='{media:thumbnail/@url}'/></a>
                    </div>
                    <div class="com-lb">
                          <a href="https://www.facebook.com/{yn:authorID}" target="blank" class='font-link'><xsl:value-of select="author"/></a><span class='font-time'> (<xsl:value-of select="dc:date"/>)</span>
                          <xsl:choose>
                            <xsl:when test="substring($com_ID,1,2)='yn'">
                              <img src='images/yn_icon.png' style="opacity:0.45;"/>
                            </xsl:when>
                            <xsl:otherwise>
                              <img src='images/fb_icon.png' style="opacity:0.45;"/>
                            </xsl:otherwise>
                          </xsl:choose>
                          <div>
                            <span class='font-standard'><xsl:value-of select="description"/></span>
                            <xsl:if test="media:content">
                              <img src='{media:content/@url}' alt="image" width='100px' height='100px'/>
                            </xsl:if>
                          </div>
                          <div class="c-button f-l" style='width:80px; padding-bottom:5px;'>
                            <div class="f-l">
                            <img src='images/profile-photo-b.png'/>
                            </div>
                            <div class="f-l" style='padding:0px 7px;'>
                              <span class='font-link f1'>0</span>
                            </div>
                          </div>
                          <div class="c-button f-l">
                            <div class="f-l">
                            <img src='images/comment-button-b.png'/>
                            </div>
                            <div class="f-l" style='padding:0px 7px;'>
                              <span class='font-link f1'>0</span>
                            </div>
                          </div>
                    </div>
                </div>
              </xsl:for-each>
            </div>
            <div class='comment' style='width: 100%; overflow: hidden; margin-top: 10px;'>
              <div class="c-pic f-l">
                <img src='images/profile-photo.png'/>
              </div>
              <form onsubmit="storeComment(this, event);" method="post">
                <input type="text" name='comment_message' style="width:460px; height:50px;" placeholder='Give a comment' class='status_textarea br15 f-l b0'></input>
                <div style="width:85px; float:right;">
                  <input type="submit" name="postSubmit" value="Comment" class="blue_button_submit br10 b0 c_b"/>
                </div>
                <xsl:if test="yn:owned = 'true'">
                  <div class="p-share p-share-s">
                    <span class="subtitle_font">Share:</span>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-fb sb-fb-s" data-selected="false" data-app="fb"><xsl:value-of select="''"/></a>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-tw sb-tw-s" data-selected="false" data-app="tw"><xsl:value-of select="''"/></a>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-inst sb-inst-s" data-selected="false" data-app="inst"><xsl:value-of select="''"/></a>
                    <a href="javascript:void(0);" onclick="modify_share(this);" class="sb-fr sb-fr-s" data-selected="false" data-app="fr"><xsl:value-of select="''"/></a>
                  </div>
                </xsl:if>
              </form>
            </div>
        </div>
    </div>
</xsl:template>


</xsl:stylesheet>