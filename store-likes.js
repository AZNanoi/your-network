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
    			document.getElementById(postID+'_likes').innerHTML = xmlhttp.responseText;
    			document.getElementById(postID+'_button').style.backgroundPosition = "2px -31px";
    		}
    	}
    };
    xmlhttp.open("GET","store-likes.php?postID="+postID,true);
    xmlhttp.send();
    return false;
}