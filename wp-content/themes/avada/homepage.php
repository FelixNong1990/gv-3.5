<?php
// Template Name: Home
global $shortname;
global $wpdb;
get_header(); ?>

<?php //$my_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'hello-world'"); echo $my_id;?>
<?php
//print_r(get_page_by_slug('register'))
?>

<?php
	/*$args = array(
	    'post_type' => 'post'
	);

	$post_query = new WP_Query($args);
	if($post_query->have_posts() ) {
		while($post_query->have_posts() ) {
	    	$post_query->the_post();
	    	$id = get_the_ID();
	    	echo '<h2>'.$id.'</h2>';
	    	//update_post_meta( $post_id, '_userpro_edit_restrict', 'none' );
	    }
	}*/

	// global $wpdb;
	// $results = $wpdb->get_results("SELECT * FROM gv_posts where post_status='publish' and post_type='post'",ARRAY_A);
	// foreach ($results as $key=>$val) {
	// 	$post_id = $val['ID'];
	// 	update_post_meta( $post_id, '_userpro_edit_restrict', 'none' );
	// }
	/*echo "<pre>";
	print_r($results);
	echo "</pre>";*/
?>

<div id="content" class="full-width">
	<?php //echo slider_pro(1); ?>
	<div class="fullwidth-box" style="padding-top: 0px; padding-bottom: 0px;">
		<div class="advanced-slider slider-pro video-slider" id="slider-pro-1" tabindex="0" style="width: 100%; height: 430px;">
			<div class="slides">
				<?php
					$catquery = new WP_Query( array('category_name'=> 'featured' , 'posts_per_page' => 5) );
					while($catquery->have_posts()) : $catquery->the_post();
					$post_id = get_the_ID();
					$ratings = get_post_meta( $post_id, '_kksr_avg' , true );
					$per = ($ratings/5)*100;
					$views = getPostViews($post_id);
					$author_id=$post->post_author;
					$author_url = get_author_posts_url($author_id);
					$published_date = get_the_time('F j, Y', $post_id);
					
					$url = get_post_meta($post_id, 'post_meta_embed_code', true); 
					$video_info = getVideoInfo($url);
					$video_id   = $video_info['video_id'];
					$video_provider   = $video_info['video_provider'];
					
					// Check whether current video provider is youtube or vimeo then get the thumnail source and video duration
					if($video_provider == 'youtube') {
						$link = "https://gdata.youtube.com/feeds/api/videos/". $video_id;
						$doc = new DOMDocument;
						$doc->load($link);
						$title = $doc->getElementsByTagName("title")->item(0)->nodeValue;
						$duration = $doc->getElementsByTagName('duration')->item(0)->getAttribute('seconds');
						$src = 'http://i1.ytimg.com/vi/' . $video_id . '/0.jpg';
						$href = 'http://www.youtube.com/watch?v=' . $video_id . '&rel=0&controls=1&showinfo=0&theme=light';
					} else if($video_provider == 'vimeo') {
						$file_contents = file_get_contents("http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/" . $video_id, true);
						$vimeo_info    = json_decode($file_contents,true);
						$duration      = $vimeo_info['duration'];
						$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . $video_id . ".php"));
						$src = $hash[0]['thumbnail_large'];
						$href = 'http://vimeo.com/' . $video_id;
					}
				?>
				<div class="slide">
					<div class="layer static" data-width="65%" data-height="100%" data-horizontal="-5" data-vertical="-2">
						<a class="video" href="<?php echo $href; ?>">
							<img src="<?php echo $src; ?>" width="100%" height="100%"/>
						</a>
					</div>
					<div class="layer static slider-description" data-horizontal="65%" data-vertical="-2" data-width="35%" data-height="100%">
						<div class="slider-right">
							<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="post-details-slider">
								<div class="post-details-rating" title="Rating: <?php echo $ratings; ?>">
									<div class="post-details-rating-score" style="width:<?php echo $per; ?>%"></div>
								</div>
							</div>
							<div class="post-details-slider">
								<i class="fa fa-user fa-lg"> </i>
								<span>Posted by <a class="post-details-author" title="Posts by <?php the_author(); ?>" href="<?php echo $author_url; ?>"><?php the_author(); ?></a></span>
							</div>
							<div class="post-details-slider">
								<i class="fa fa-calendar fa-lg"> </i>
								<span><?php echo $published_date; ?></span>
							</div>
							<div class="post-details-slider">
								<i class="fa fa-eye fa-lg"> </i>
								<span><?php echo $views; ?> Views</span>
							</div>
							<p>
								<?php echo strip_tags(limit_excerpt(get_the_content(),20)); ?>
							</p>
							<div class="more-link-slider">
								<a href="<?php the_permalink(); ?>" class="btn btn-primary">More details <i class="fa fa-arrow-right"></i></a>
							</div>
						</div>
					</div>
				</div>
				<?php 
					endwhile;
					wp_reset_query();
				?>
			</div>
		</div>

		<!--<div id="layerslider_1" class="ls-wp-container" style="width:1000px;height:430px;margin:0 auto;margin-bottom: 0px; wwhite-space: normal;">
			<div class="ls-slide" data-ls=" transition2d: all;">
				<div class="ls-l" style="top:0px;left:0px;white-space: nowrap;">
					<iframe width="560" height="430" src="//www.youtube.com/embed/7Ee9u0hyz6A" frameborder="0" allowfullscreen>
					</iframe>
				</div>
				<h1 class="ls-l" style="top:5px;left:660px;white-space: nowrap;" data-ls="delayin:1000;">Dota 2 Guide - Invoker</h1>
				<div class="ls-l" style="top:48px;left:576px;" data-ls="delayin:2000;easingin:easeInOutExpo;easingout:easeInSine;">
					Test
				</div>
			</div>
			<div class="ls-slide" data-ls=" transition2d: all;">
				<div class="ls-l" style="top:0px;left:0px;white-space: nowrap;">
					<iframe width="560" height="430" src="//www.youtube.com/embed/BXO1J3y2i6w" frameborder="0" allowfullscreen>
					</iframe>
				</div>
				<h1 class="ls-l" style="top:5px;left:660px;white-space: nowrap;" data-ls="delayin:1000;">Dota 2 Guide - Juggernaut</h1>
				<div class="ls-l" style="top:48px;left:576px;" data-ls="delayin:2000;easingin:easeInOutExpo;easingout:easeInSine;">
					Test
				</div>
			</div>
			<div class="ls-slide" data-ls=" transition2d: all;">
				<div class="ls-l" style="top:0px;left:0px;white-space: nowrap;">
					<iframe width="560" height="430" src="//www.youtube.com/embed/4esVjFQB_94" frameborder="0" allowfullscreen>
					</iframe>
				</div>
				<h1 class="ls-l" style="top:5px;left:660px;white-space: nowrap;" data-ls="delayin:1000;">Dota 2 Guide - Invoker</h1>
				<div class="ls-l" style="top:48px;left:576px;" data-ls="delayin:2000;easingin:easeInOutExpo;easingout:easeInSine;">
					Test
				</div>
			</div>
		</div>
	
		<?php //layerslider(1) ?>
		<p style="margin-bottom: 40px">Test</p>
		<?php //jnewsticker_display(0) ?>
	
		<div id="layerslider" class="ls-wp-container ls-container ls-borderlessdark" style="width: 1000px; height: 430px;">
			<div class="ls-inner" style="width: 1000px; height: 430px;">
				<div class="ls-slide" data-ls="transition2d: all;">
					<div class="ls-l ls-video-layer ls-videohack">
						<iframe width="1000" height="430" src="//www.youtube.com/embed/EPv4tQ9xTtg" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
				<div class="ls-slide" data-ls="transition2d: all;">
					<div class="ls-l ls-video-layer ls-videohack">
						<iframe width="1000" height="430" src="//www.youtube.com/embed/ombJB_vUoYE" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
				<div class="ls-slide" data-ls=" transition2d: all;">
					<div class="ls-l ls-video-layer ls-videohack">
						<iframe width="1000" height="430" src="//www.youtube.com/embed/AwRgsqDFP1k" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>-->

		<?php
		    // Create array of all categories
			$gameArr = array('dota'=>"DOTA",'lol'=>"LEAGUE of LEGENDS",'smite'=>"SMITE",'starcraft'=>"StarCraft II",'cod'=>"Call of Duty",'yolo'=>"YOLO");
			$i = 0;
			foreach($gameArr as $gameArr=>$val) {
				$i++;
		?>
		<div class="cat-box">
			<h2 class="light-title title">
				<a class="title-link" href="<?php echo site_url() . '/category/' . $gameArr; ?>"><?php echo $val; ?></a>
			</h2>
			<div class="smart-control pull-right">
				
			</div>
		</div>
		<div class="owl-carousel">
			<?php
				$lpIdArr = array();
				$vidIdArr = array();
				$titleArr = array();
				$args = array( 'numberposts' => '8', 'category_name' => $gameArr);
				$recent_posts = wp_get_recent_posts( $args );
				foreach( $recent_posts as $recent ){
					//echo get_post_meta($recent['ID'], 'pyre_video', true);
					array_push($lpIdArr, $recent);
				};
				
				foreach( $lpIdArr as $lpIdArr ){
					$url = get_post_meta($lpIdArr['ID'], 'post_meta_embed_code', true);
					if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
						$video_id = $match[1];
						array_push($vidIdArr, $video_id);
					};

					$title = get_the_title($lpIdArr['ID']);
					array_push($titleArr, $title);
				};

				$n = 0;
				$catquery = new WP_Query( array('category_name'=> $gameArr , 'posts_per_page' => 8) );
				while($catquery->have_posts()) : $catquery->the_post();
				$post_id = get_the_ID();
				$views = getPostViews($post_id);
				$author_id=$post->post_author;
				$author_url = get_author_posts_url($author_id);
				$post_like_count = getTotalLike($post_id) ?: 0;
				$post_dislike_count = getTotalDislike($post_id) ?: 0;
				$published_date = get_the_time('F j, Y', $post_id);
			?>
			<div class="item">
				<div class="box">
					<div class="he-wrap tpl4">

						<img width="243" height="165" src="http://i1.ytimg.com/vi/<?php echo $vidIdArr[$n]; ?>/mqdefault.jpg">

						<div class="he-view">
							<div class="bg">
								<div class="a0" data-animate="fadeIn"></div><div class="a0" data-animate="fadeInUp"></div><div class="a0" data-animate="fadeInDown"></div><div class="a0" data-animate="fadeInUp"></div><div class="a0" data-animate="fadeInDown"></div>
							</div>
							<div class="content">
								<h3 class="info-title a0" data-animate="rotateInLeft" title="<?php echo $titleArr[$n]; ?>"><a href="<?php the_permalink(); ?>"><?php echo $titleArr[$n]; ?></a></h3>
								<div class="detail">
									<ul class="meta-left a1" data-animate="zoomInLeft">
										<li class="meta-author"><a title="View all videos of <?php the_author(); ?>" href="<?php echo $author_url; ?>"><span class="fa fa-user"></span><?php the_author(); ?></a></li>
										<li class="meta-date"><span class="fa fa-calendar"></span><?php echo $published_date; ?></li>
										<li class="meta-view">
											<span class="fa fa-eye"></span>
											<?php 
												if($views == 0) {
													echo " No view";
												} elseif($views == 1) {
													echo " One view";
												} else {
													echo $views . " views";
												}
											?>
										</li>
									</ul>

									<ul class="meta-right a1" data-animate="zoomInRight">
										<li class="rating tn_like">
											<span class="fa fa-thumbs-o-up"></span>
											<?php 
												echo $post_like_count;
											?>
										</li>
										<li class="rating tn_dislike">
											<span class="fa fa-thumbs-o-down"></span>
											<?php 
												echo $post_dislike_count;
											?>
										</li>
										<?php 
											$commentsNum = get_comments_number();
											if($commentsNum != 1) $commentsTitle = $commentsNum . ' Comments';
											else $commentsTitle = $commentsNum . ' Comment';
										?>
										<li class="meta-comment"><a class="cmt-link" title="<?php echo $commentsTitle; ?>" href="<?php comments_link(); ?>"><span class="fa fa-comment"></span><?php echo get_comments_number(); ?></a></li>
									</ul>
								</div>
								<a href="<?php the_permalink(); ?>" class="more a1" data-animate="fadeIn">Watch Video <i style="margin-left: 3px;" class="fa fa-play"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
				$n++;
				endwhile;
				wp_reset_query();
			?>		
		</div>
	<?php } ?>
	</div>

</div>

<?php get_footer(); ?>
