jQuery(document).ready(function($) {
	var linkEnabled = true;
	$('body').on('click','.vid-feeling',function(event){
		if(!linkEnabled){
			return;
		}
		linkEnabled = false;
		event.preventDefault();
		heart = $(this);
		status = heart.data("status");
		post_id = heart.data("post_id");
		$this = $(this);
		
		if(status=="like") {
			var liked_number = +$('span.like_count').html();
			
			if($(this).hasClass('liked')) {
				$(this).removeClass('liked');
				//$('span.like_count').html(--liked_number);
			}
			else { 
				$(this).addClass('liked').next().removeClass('disliked');
				//$('span.like_count').html(++liked_number);
			}
		} else {
			var disliked_number = +$('span.dislike_count').html();
		
			if($(this).hasClass('disliked')) {
				$(this).removeClass('disliked');
				//$('span.dislike_count').html(--disliked_number);
			}
			else { 
				$(this).addClass('disliked').prev().removeClass('liked');
				//$('span.dislike_count').html(++disliked_number);
			}
		}
		
		//heart.html("<i class='fa fa-heart'></i>&nbsp;<i class='fa fa-cog fa-spin'></i>");
		$.ajax({
			type: "post",
			url: ajax_var.url,
			data: "action=jm-post-like&nonce="+ajax_var.nonce+"&jm_post_like=&post_id="+post_id+"&status="+status,
			dataType: 'json',
			success: function(result){
				console.log(result);
				var status = result[0],
					count = result[1],
					oppositeStatus_count =  result[2];
				if(status == 0) {
					if( count.indexOf( "already" ) !== -1 )
					{
						var lecount = count.replace("already","");
						if (lecount == 0)
						{
							//var lecount = "Like";
						}
						heart.prop('title', 'Like');
						//heart.removeClass("liked");
						$('.like_count').html(lecount);
					}
					else
					{
						heart.prop('title', 'Unlike');
						//heart.addClass("liked");
						$('.like_count').html(count);
					}
					$('.dislike_count').html(oppositeStatus_count);
					
				} else {
					if( count.indexOf( "already" ) !== -1 )
					{
						var lecount = count.replace("already","");
						if (lecount == 0)
						{
							//var lecount = "Like";
						}
						heart.prop('title', 'Like');
						//heart.removeClass("liked");
						$('.dislike_count').html(lecount);
					}
					else
					{
						heart.prop('title', 'Unlike');
						//heart.addClass("liked");
						$('.dislike_count').html(count);
					}
					$('.like_count').html(oppositeStatus_count);
				}
				linkEnabled = true;
				var like_count    = +$('span.like_count').html(),
					dislike_count = +$('span.dislike_count').html(),
					total_count   = like_count + dislike_count,
					bar_width;
				if(total_count > 0) {
					bar_width = (like_count/total_count)*100;
					$(".like-bar span").animate({width:bar_width+'%'}, 500);
				} else {
					$(".like-bar span").animate({width:0}, 500);
				}
			}
		});
	
	});
});
