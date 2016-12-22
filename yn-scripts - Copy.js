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

function logoutFB() {
  FB.logout(function(response) {
    console.log('Logging out.... ');
  });
}

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

function fetchComment(element){
  var postID = element.getAttribute("id");
  var offset = parseInt(element.dataset.viewed);
  var r = parseInt(element.dataset.remain);
  var step = r;
  if (r>=5){
    step = 5;
    nr = r - 5;
  }
  var file="fb-comments-feed.php?postID="+postID;
  $.post(file,{"moreReviews":1,"offset":offset, "step":step},
    function(data){
      if (data!=""){
        $(element).parent().after(data);
      }
      if (r<=5){
        $(element).remove();
      }else{
        var m = "comment";
        if (nr>1){
          m = "comments";
        }
        $(element).empty();
        $(element).append("View "+nr+" previous "+m);
        element.dataset.viewed=offset+step;
        element.dataset.remain=nr;
      }
    }
  );
}


$(document).ready(function(){                
  $(window).bind('scroll',fetchMore);

  $("#uploadPost").submit(function(event) {
    event.preventDefault();
    $( "#statusForm" ).fadeTo( "slow" , 0.5);
    var divHeight=$("#statusForm").height();
    $("#statusForm").prepend('<div id="hover" style="position:absolute; width:100%;"><img src="images/loader.gif" class="centerContent"/></div>');
    $("#hover").css("height", divHeight+"px");
    var form = new FormData($('#uploadPost')[0]);
    $.ajax({
       url: "upload-post.php",
       type: "POST",
       data: form,
       cache:false,
       processData: false,
       contentType: false,
       success: function(data) {
          if (data!=""){
            $(".newsFeed").prepend(data);
            $("#uploadPost")[0].reset();
            $("#addPic").remove();
            $("#addTag").remove();
            $("#textField").after('<div id="addPic" class="p-margin f-l" style="width:95%;"><a href="javascript:void(0);" onclick="addPhoto();" class="subtitle_font">Add Photos/Videos:</a></div><div id="addTag" class="p-margin f-l"><a href="javascript:void(0);" onclick="addTag();" class="subtitle_font">Tag Friends:</a></div>');
            $(".s-box").attr("style","margin-top:-15px;");
          }
          $('#hover').remove();
          $( "#statusForm" ).fadeTo( "slow" , 1);
          return false;
       },
       error: function(jqXHR, textStatus, errorMessage) {
           console.log(errorMessage); // Optional
       }
    });
  });

  $('#profile-thumbnail').mouseenter(function() {
   $( '#profile-thumbnail>span' ).css("display", "block");
  });

  $('#profile-thumbnail').mouseleave(function() {
   $( '#profile-thumbnail>span' ).css("display", "none");
  });

  $(document).on('click','div#subImage',function(){
    var src=$(this).find("img").attr("src");
    var viewer=$(this).parent().siblings(".mediaContent").attr("id");
    if ($(this).find("img").attr("class")=="portrait"){
      $("#"+viewer).find("img").attr("style", "max-height: inherit;");
      $(this).find("img").attr("style", "width: 100%; top: -35%;");
    }else{
      $("#"+viewer).find("img").attr("style", "max-width: inherit;");
    }
    $("#"+viewer).find("img").attr("src", src);
    $(this).animate({width:"120px"}, 300);
    var selectedDiv = $(this).siblings(".selected");
    if (selectedDiv.find("img").attr("class")=="landscape"){
      selectedDiv.find("img").attr("style", "height:100%;width:auto;left:-15%;");
    }else{
      selectedDiv.find("img").attr("style", "width:140%;left:-15%;top:-10%;");
    }
    selectedDiv.css("width", "60px");
    selectedDiv.attr("class", "visited");
    $(this).attr("class", "selected");
  });
  
});

fetchMore = function (){
  if ( $(window).scrollTop() + $(window).height() >= $(document).height() - 1200 ){
        $(window).unbind('scroll',fetchMore);
        var ID=$(".post:last").attr("id");
    $('div#last_post_loader').html('<img src="images/loader.gif" style="display:block; margin-left:auto; margin-right:auto; padding-top:15px;"/>');
        $.post("fetch-posts.php?last_post_id="+ID,{'action':'moreReviews','offset':$('.review').length,'step':5 },
          function(data){
        if (data!=""){
          $(".post:last").after(data);
          $(window).bind('scroll',fetchMore);
        }
        $("div#last_post_loader").empty();
      }
    );
    }
}

function addPhoto(){
  $("#addPic").empty();
  var element='<a href="javascript:void(0);" onclick="addPhoto();" class="subtitle_font">Add Photos/Videos:</a><hr/><div id="photoList" class="f-l" style="background-color:white; width:100%; max-height:110px; padding:30px 0px; overflow:auto;"><div id="imgLoader" class="target" width="78" height="100" style="float:left;margin-left:15px;padding-bottom:30px;"></div></div>';
  $("#addPic").append(element);
  $("#addPic").attr("style","width:95%; height:auto;");
  $("#addTag").removeAttr("style");
  $(".s-box").removeAttr("style");
  $(".target").dropper({
  }).on("offset.dropper", onoffset);

  $('.dropper-dropzone').mouseenter(function() {
   $( '.dropper-dropzone>span' ).css("display", "block");
  });

  $('.dropper-dropzone').mouseleave(function() {
   $( '.dropper-dropzone>span' ).css("display", "none");
  });
  return false;
}

function onoffset(e, files){
  console.log(files);
  $.each(files, function(i){
    image_preview(files[i].file).then(function(res){
      var src=res.data;
      var image = '<div style="float:left;width:90px; margin-left:15px; "><img src="'+src+'" width="90px" height="110px" style="border:1px solid rgba(0, 0, 0, .1); thin green;"/></div>';
      $(image).insertBefore('#imgLoader');
    });
  });
  $("#photoList").animate({ scrollTop: $('#photoList').prop("scrollHeight")}, 1000);
}

function image_preview(file){
  var def = new $.Deferred();
  var imgURL = '';
  if (file.type.match('image.*')) {
      //create object url support
      var URL = window.URL || window.webkitURL;
      if (URL !== undefined) {
          imgURL = URL.createObjectURL(file);
          URL.revokeObjectURL(file);
          def.resolve({status: 200, message: 'OK', data:imgURL, error: {}});
      }
      //file reader support
      else if(window.File && window.FileReader)
      {
          var reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onloadend = function () {
              imgURL = reader.result;
              def.resolve({status: 200, message: 'OK', data:imgURL, error: {}});
          }
      }
      else {
          def.reject({status: 1001, message: 'File uploader not supported', data:imgURL, error: {}});
      }
  }
  else
      def.reject({status: 1002, message: 'File type not supported', error: {}});
  return def.promise();
}

function addTag(){
  $("#addTag").empty();
  var element='<hr/><div class="sub-lbox f-l"><a href="javascript:void(0);" onclick="addTag();" class="subtitle_font">Tag Friends:</a></div><div class="f-l"><input type="text" name="search" value="" class="search-input br10 b0" placeholder="Find Friend"></input></div>';
  $("#addTag").append(element);
  $("#addTag").attr("style","width:95%; height:auto;");
  $(".s-box").removeAttr("style");
  return false;
}