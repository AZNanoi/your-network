var e = document.querySelector("#"+postID+'_likes a');
          alert(e.innerHTML);
          if (xmlhttp.responseText=="removed"){
            e.innerHTML = parseInt(e.innerHTML)-1;
            e.dataset.yn = parseInt(e.dataset.yn)-1;
            document.getElementById(postID+'_button').removeAttribute("style");
          }else if (xmlhttp.responseText=="added"){
            e.innerHTML = parseInt(e.innerHTML)+1;
            e.dataset.yn = parseInt(e.dataset.yn)+1;
            document.getElementById(postID+'_button').style.backgroundPosition = "2px -31px";
          }

          var e = node.childNodes[1];
          var parent = node.parent().parent().parent().parent();
          if (parent.children().length>1){
              if (parent.find(".l").length){
                parent.find(".l").find("a").innerHTML=e.dataset.yn;
              }
            }