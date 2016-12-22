function createPost(){
	var postContent="
	<div class='post'>
		<div style='position:relative; float:right; padding-top:7px; padding-right:10px; opacity:0.6;'>
    		<img src='images/fb_stamp.png' width='27px' height='35px'/>
    	</div>
    </div>
    ";
    alert(postContent);
    $("#newsFeed").append(postContent);
    return true;
}