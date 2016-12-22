$(document).ready(function(){                
    $(window).bind('scroll',fetchMore);
});

fetchMore = function (){
	if ( $(window).scrollTop() + $(window).height() >= $(document).height() - 500 ){
        $(window).unbind('scroll',fetchMore);
        var ID=$(".post:last").attr("id");
		$('div#last_post_loader').html('<img src="images/loader.gif" style="display:block; margin-left:auto; margin-right:auto; padding-top:15px;"/>');
        $.post("fetch-posts.php?last_post_id="+ID,{'action':'moreReviews','start':$('.review').length,'step':5 },
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