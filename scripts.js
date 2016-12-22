			<script language="javascript">
     			function pressed(e){
					if ((window.event ? event.keyCode : e.which) == 13){
	   					document.forms[2].submit();
	   					return false;
	   				}
	   				else{
	   					return true;
	   				}
   	 			}
    		</script>
<textarea name='comment_message' rows='2' cols='55' placeholder='Give a comment' class='status_textarea' onkeydown="pressed(event)"><xsl:value-of select="''"/></textarea>

<a href="JavaScript:void(0);" onclick="function()">Whatever!</a>

<script type="text/javascript">
function confirm_delete() {
    var delete_confirmed=confirm("Are you sure you want to delete this file?");

    if (delete_confirmed==true) {
       // the php code :) can't expose mine ^_^
    } else { 
       // this one returns the user if he/she clicks no :)
       document.location.href = 'whatever.php';
    }
}
</script>

// every page with logout button
<?php
        // get the full url of current page
        $page = $_SERVER['PHP_SELF'];
        // find position of the last '/'
        $file_name_begin_pos = strripos($page, "/");
        // get substring from position to end 
        $file_name = substr($page, ++$fileNamePos);
    }
?>

// the logout link in your html
<a href="logout.php?redirect_to=<?=$file_name?>">Log Out</a>

// logout.php page
<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    $page = "index.php";
    if(isset($_GET["redirect_to"])){
        $file = $_GET["redirect_to"];
        if ($file == "user.php"){
            // if redirect to is a restricted page, redirect to index
            $file = "index.php";
        }
    }
    header("Location: $file");
?>


function thisfunction(postID){
          alert(postID);
            if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
            xmlhttp.open("GET","store-likes.php?postID="+postID,true);
            xmlhttp.send();
            return false;
        }
        $(function(){
        $('.like-button').click(function(){
            var $this = $(this);
            var p1 = $this.data('postid');
            thisfunction(p1);
        });
    });