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

function changeSubmitter(e, element){
  $(element).parent().parent().data("action", element.value);
}

function edit_post(e, element){
  e.preventDefault();
  var old=$(element).find('input[name="oldMessage"]').val();
  var action=$(element).data("action");
  if (action == 'Cancel'){
    $(element).parent().append(old);
    $(element).parent().find("span").remove();
    $(element).parent().find("form").remove();
  }else if (action == 'Edit'){
    var message=$(element).find('textarea').val();
    var postID=$(element).parent().parent().parent().parent().attr('id');
    $.post("edit-post.php",{"message":message, "postID":postID},
      function(data){
        if (data=='updated'){
          $(element).parent().append(message);
          $(element).parent().find("span").remove();
          $(element).parent().find("form").remove();
        }else{
          $(element).parent().find("span").remove();
          $("<span style='color:red'>You are not authorized to edit this post! Or an error occured!</span>").insertBefore(element);
        }
      }
    );
  }
}

function display_pEditor(e, element){
  e.preventDefault();
  var node=$(element).parent().parent().parent();
  var old=$.trim(node.find('.message').html());
  node.find('.message').empty();
  var editor='<form method="post" onsubmit="edit_post(event, this);" data-action="none"><div id="textField" class="f-l" style="width:100%;"><textarea name="status_message" placeholder="Write a status" class="status_textarea br15 f-l b0" style="min-width: 100%; max-width: 100%; min-height:75px;"></textarea></div><div style="display:block;"><input onclick="changeSubmitter(event,this);" name="postSubmit" value="Cancel" class="blue_button_submit br10 b0 c_b" type="submit" style="margin-top:0;"></input><input onclick="changeSubmitter(event,this);" name="postSubmit" value="Edit" class="blue_button_submit br10 b0 c_b" type="submit" style="margin-top:0;"></input></div><input type="hidden" name="oldMessage" value="'+old+'"/></form>';
  node.find('.message').append(editor);
  node.find('textarea').val(old);
}

function modify_share(element){
  var selected=element.dataset.selected;
  if (selected=='false'){
    element.dataset.selected='true';
    var app=element.dataset.app;
    if(app=='fb'){
      $(element).css("background-color", "#486BB4");
    }else if(app=='tw'){
      $(element).css("background-color", "#54ACEE");
    }else if(app=='inst'){
      $(element).css("background-color", "#D0BC8F");
    }else{
      $(element).css("background-color", "#FF0084");
    }
    
  }else{
    element.dataset.selected='false';
    $(element).removeAttr("style");
  }
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
          var node = document.getElementById(postID+'_likes');
          var e = node.childNodes[1];
          var parent = $(node).parent().parent().parent().parent();
          if (xmlhttp.responseText=="removed"){
            e.innerHTML = parseInt(e.innerHTML)-1;
            e.dataset.yn = parseInt(e.dataset.yn)-1;
            document.getElementById(postID+'_button').removeAttribute("style");
            if (parent.children().length>1){
              if (parent.find(".l").length){
                parent.find(".l").find("a.yn").text(e.dataset.yn);
              }
            }
          }else if (xmlhttp.responseText=="added"){
            e.innerHTML = parseInt(e.innerHTML)+1;
            e.dataset.yn = parseInt(e.dataset.yn)+1;
            document.getElementById(postID+'_button').style.backgroundPosition = "2px -31px";
            if (parent.children().length>1){
              if (parent.find(".l").length){
                parent.find(".l").find("a.yn").text(e.dataset.yn);
              }
            }
          }
        }
      }
    };
    xmlhttp.open("GET","store-likes.php?postID="+postID,true);
    xmlhttp.send();
    return false;
}

function switchClick(e, postID){
  e.preventDefault();
  $("#"+postID+"_comments>a").click();
}

function viewApps(element){
  var postID=element.dataset.postid;
  $("#"+postID+"_comWrapperOri").css("display", "block");
  $("#"+postID+"_comWrapper").css("display", "none");
  var displayed=element.dataset.displayed;
  if(displayed=="false"){
    var kind=element.dataset.kind;
    var yn=element.dataset.yn;
    var fb=element.dataset.fb;
    $.post("viewApps.php",{"yn":yn, "fb":fb, "kind":kind, "postID":postID},
      function(data){
        $("#viewApps").remove();
        $(element).parent().parent().parent().parent().after(data);
        if(kind=="l"){
          $("div#viewApps tr:first a").css("background-image", 'url("images/sub_lbb.png")');
        }
        $("#viewApps").toggle(500);
        element.dataset.displayed="true";
        $(element).parent().siblings().find("a").attr("data-displayed", "false");
      }
    );
  }else{
    $("#viewApps").toggle(500);
    element.dataset.displayed="false";
  }
}

function fetchApplikes(element){

}

function fetchAppComment(element){
  var app=element.dataset.app;
  var postID=element.dataset.postid;
  var amount=parseInt(element.dataset.num);
  $.post("fetch-app-comment.php",{"app":app, "postID":postID},
    function(data){
      $("#"+postID+"_comWrapper").empty();
      $("#"+postID+"_comWrapperOri").css("display", "none");
      $("#"+postID+"_comWrapper").css("display", "block");
      if(amount>5){
        r=amount-5;
        c="comment";
        if(r>1){
          c="comments";
        }
        m="View "+r+" previous "+c;
        e='<div style="padding-bottom:10px;"><a href="javascript:void(0);" id="'+postID+'" data-app="'+app+'" data-remain="'+r+'" data-viewed="5" class="subtitle_font" onclick="fetchComment(this);" style="font-size:16px;">'+m+'</a></div>';
        $("#"+postID+"_comWrapper").append(e);
      }
      $("#"+postID+"_comWrapper").append(data);
    }
  );
}

function fetchComment(element){
  var postID = element.getAttribute("id");
  var last_com_id = $(element).parent().next().attr('id');
  var app = element.dataset.app;
  var offset = parseInt(element.dataset.viewed);
  var r = parseInt(element.dataset.remain);
  var step = r;
  if (r>=5){
    step = 5;
    nr = r - 5;
  }
  var file="fetch-comments.php";
  var data_list={"postID":postID, "app":app, "last_com_id":last_com_id};
  $.post(file, data_list,
    function(data){
      if (data!=""){
        $(element).parent().after(data);
      }
      if (r<=5){
        $(element).parent().css("display", "none");
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

function storeComment(element, e){
  e.preventDefault();
  var appsList=$(element).find(".p-share a").map( function() {
      if($(this).attr('data-selected')=="true"){
        return $(this).attr('data-app');
      }
    }).get();
  var message=$(element).find("input").val();
  if(message.trim().length){
    var postID=$(element).parent().parent().parent().attr("id");
    $.post("store-comment.php",{"message":message,"postID":postID, "appsList":appsList},
      function(data){
        $(data).insertBefore($(element).parent());
        $(element)[0].reset();
        var newV = parseInt($("#"+postID+"_comments"+">a").text()) + 1;
        var ynV = parseInt($("#"+postID+"_comments"+">a").attr("data-yn")) + 1;
        $("#"+postID+"_comments"+">a").empty();
        $("#"+postID+"_comments"+">a").html(newV);
        $("#"+postID+"_comments"+">a").attr("data-yn", ynV);
      }
    );
  }else{
    $(element).find("input.status_textarea").val("Textbox is empty! Please fill in the text field!");
  }
}

$(document).ready(function(){                
  $(window).bind('scroll',fetchMore);

  $(".search").keyup(function(event){
    event.preventDefault();
    var inputData=$(this).val();
    var data="inputData="+inputData;
    if(inputData!=''){
      $.ajax({
        type: "POST",
        url: "search-person.php",
        data: data,
        cache: false,
        success: function(data){
          $("#searchResult").html(data).show();
        }
      });
    }else{
      $("#searchResult").empty();
    }
  });

  $("#uploadPost").submit(function(event) {
    event.preventDefault();
    var appsList=$(this).find(".p-share a").map( function() {
      if($(this).attr('data-selected')=="true"){
        return $(this).attr('data-app');
      }
    }).get();
    var form = new FormData($('#uploadPost')[0]);
    form.append("appsList", appsList);
    $( "#statusForm" ).fadeTo( "slow" , 0.5);
    var divHeight=$("#statusForm").height();
    $("#statusForm").prepend('<div id="hover" style="position:absolute; width:100%;"><img src="images/loader.gif" class="centerContent"/></div>');
    $("#hover").css("height", divHeight+"px");
    var form = new FormData($('#uploadPost')[0]);
    form.append("appsList", appsList);
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
  }).on("start.dropper", onoffset);

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