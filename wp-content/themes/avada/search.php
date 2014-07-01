<?php get_header(); ?>
	<?php
	$sidebar_exists = true;
	$container_class = '';
	$post_class = '';
	$content_class = '';
	if($smof_data['search_sidebar'] == 'None') {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
		$content_class= 'full-width';
		$sidebar_exists = false;
	} elseif($smof_data['search_sidebar_position'] == 'Left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
	} elseif($smof_data['search_sidebar_position'] == 'Right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
	}

	if($smof_data['search_layout'] == 'Large Alternate') {
		$post_class = 'large-alternate';
	} elseif($smof_data['search_layout'] == 'Medium Alternate') {
		$post_class = 'medium-alternate';
	} elseif($smof_data['search_layout'] == 'Grid') {
		$post_class = 'grid-post';
		$container_class = sprintf( 'grid-layout grid-layout-%s', $smof_data['blog_grid_columns'] );
	} elseif($smof_data['search_layout'] == 'Timeline') {
		$post_class = 'timeline-post';
		$container_class = 'timeline-layout';
		if($smof_data['search_sidebar'] != 'None') {
			$container_class = 'timeline-layout timeline-sidebar-layout';
		}
	}
	?>
	<div id="content" class="<?php echo $content_class; ?>" style="<?php echo $content_css; ?>">
		<?php
		if($smof_data['search_results_per_page']) {
			$page_num = $paged;
			if ($pagenum='') { $pagenum = 1; }
				global $query_string;
				//query_posts($query_string.'&posts_per_page='.$smof_data['search_results_per_page'].'&paged='.$page_num);
		} ?>

		<?php if ( have_posts() && strlen( trim(get_search_query()) ) != 0 ) : ?>
		<div class="search-page-search-form">
			<div class="search-page-search-form">
				<!--<h2><?php //echo __('Need a new search?', 'Avada'); ?></h2>-->
				<!--<p><?php //echo __('If you didn\'t find what you were looking for, try a new search!', 'Avada'); ?></p>-->
				<!--<form id="searchform" class="seach-form" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
					<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e( 'Search ...', 'Avada' ); ?>"/>
					<input type="submit" id="searchsubmit" value="&#xf002;" />
				</form>-->
				
				<form id="searchform" class="seach-form" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
					<div class="search-table">
						<div class="search-field">
							<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e( 'Search ...', 'Avada' ); ?>"/>
						</div>
						<div class="search-button">
							<input type="submit" id="searchsubmit" value="&#xf002;" />
						</div>
					</div>
				</form>
				
			</div>
			<?php
				if($wp_query->found_posts > 1):
			?>
			<div class="sort">
				<div class="lft_sort">
					<span class="sortby_label">Sort by:</span>
					<select id="order_by" style="width: 100px;">
						<option value="relevance">Relevance</option>
						<option value="post_date">Date</option>
						<option value="post_views_count">Views</option>
						<option value="ratings">Ratings</option>
					</select>
					<span class="sort_label">Sort:</span>
					<select id="order">
						<option value="desc">Descending</option>
						<option value="asc">Ascending</option>
					</select>
				</div>
				<div class="results_count">
					<?php 
						$results_count = $wp_query->found_posts; 
						if($results_count == 1) {
					?>
							<p><?php echo 'Found ' . $results_count . ' result'; ?></p>
					<?php
						} else {
					?>
							<p><?php echo 'Found ' . $results_count . ' results'; ?></p>
					<?php
						}
					?>
					
				</div>
			</div>
			<?php
				endif;
			?>
			<script>
				function getQueryVariable(variable) {
				   var query = window.location.search.substring(1);
				   var vars = query.split("&");
				   for (var i=0;i<vars.length;i++) {
						   var pair = vars[i].split("=");
						   if(pair[0] == variable){return pair[1];}
				   }
				   return(false);
				}
				
				jQuery(function($) {
					$("#order_by,#order").select2({minimumResultsForSearch: -1});
					var queryOrderByVal = getQueryVariable("orderby"),
						queryOrderVal = getQueryVariable("order");
					$('#s2id_order,.sort_label').toggle($('#order_by').val() != 'relevance');
					if(queryOrderByVal && queryOrderByVal) {
						$('#s2id_order,.sort_label').toggle(queryOrderByVal != 'relevance');
						$("#order_by").select2('val', queryOrderByVal);
						$("#order").select2('val', queryOrderVal);
						$('#order_by').val(queryOrderByVal);
						$('#order').val(queryOrderVal);
					};
					$('#order_by,#order').change(function() {
						var orderByVal = $('#order_by').val(),
							orderVal = $('#order').val(),
							href = "<?php echo get_bloginfo('url') . '?s=' . get_search_query() . '&orderby='; ?>" + orderByVal + "&order=" + orderVal;
						window.location.replace(href);
					});
				});
			</script>		
		</div>		
		<?php if($smof_data['search_layout'] == 'Timeline'): ?>
			<div class="timeline-icon"><i class="icon-comments-alt"></i></div>
			<?php endif; ?>
			<div id="posts-container" class="<?php echo $container_class; ?> clearfix">
				<?php
				$post_count = 1;

				$prev_post_timestamp = null;
				$prev_post_month = null;
				$first_timeline_loop = false;

				while(have_posts()): the_post();
					$post_timestamp = strtotime($post->post_date);
					$post_month = date('n', $post_timestamp);
					$post_year = get_the_date('o');
					$current_date = get_the_date('o-n');
				?>
				<?php if($smof_data['search_layout'] == 'Timeline'): ?>
				<?php if($prev_post_month != $post_month): ?>
					<div class="timeline-date"><h3 class="timeline-title"><?php echo get_the_date($smof_data['timeline_date_format']); ?></h3></div>
				<?php endif; ?>
				<?php endif; ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class($post_class.getClassAlign($post_count).' post clearfix'); ?>>
					<?php if($smof_data['search_layout'] == 'Medium Alternate'): ?>
					<div class="date-and-formats">
						<div class="date-box">
							<span class="date"><?php the_time($smof_data['alternate_date_format_day']); ?></span>
							<span class="month-year"><?php the_time($smof_data['alternate_date_format_month_year']); ?></span>
						</div>
						<div class="format-box">
							<?php
							switch(get_post_format()) {
								case 'gallery':
									$format_class = 'images';
									break;
								case 'link':
									$format_class = 'link';
									break;
								case 'image':
									$format_class = 'image';
									break;
								case 'quote':
									$format_class = 'quotes-left';
									break;
								case 'video':
									$format_class = 'film';
									break;
								case 'audio':
									$format_class = 'headphones';
									break;
								case 'chat':
									$format_class = 'bubbles';
									break;
								default:
									$format_class = 'pen';
									break;
							}
							?>
							<i class="icon-<?php echo $format_class; ?>"></i>
						</div>
					</div>
					<?php endif; ?>
					<?php
					if(!$smof_data['search_featured_images']):
					if($smof_data['legacy_posts_slideshow']) {
						get_template_part('legacy-slideshow');
					} else {
						get_template_part('new-slideshow');
					}
					endif;
					?>
					<div class="post-content-container">
						<?php if($smof_data['search_layout'] == 'Timeline'): ?>
						<div class="timeline-circle"></div>
						<div class="timeline-arrow"></div>
						<?php endif; ?>
						<?php if($smof_data['search_layout'] != 'Large Alternate' && $smof_data['search_layout'] != 'Medium Alternate' && $smof_data['search_layout'] != 'Grid'  && $smof_data['search_layout'] != 'Timeline'): ?>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php endif; ?>
						<?php if($smof_data['search_layout'] == 'Large Alternate'): ?>
						<div class="date-and-formats">
							<div class="date-box">
								<span class="date"><?php the_time($smof_data['alternate_date_format_day']); ?></span>
								<span class="month-year"><?php the_time($smof_data['alternate_date_format_month_year']); ?></span>
							</div>
							<div class="format-box">
								<?php
								switch(get_post_format()) {
									case 'gallery':
										$format_class = 'images';
										break;
									case 'link':
										$format_class = 'link';
										break;
									case 'image':
										$format_class = 'image';
										break;
									case 'quote':
										$format_class = 'quotes-left';
										break;
									case 'video':
										$format_class = 'film';
										break;
									case 'audio':
										$format_class = 'headphones';
										break;
									case 'chat':
										$format_class = 'bubbles';
										break;
									default:
										$format_class = 'pen';
										break;
								}
								?>
								<i class="icon-<?php echo $format_class; ?>"></i>
							</div>
						</div>
						<?php endif; ?>
						<div class="post-content">
							
<?php
	// Get video infos
	$post_id = get_the_ID();
	$ratings = get_post_meta( $post_id, '_kksr_avg' , true );
	$per = ($ratings/5)*100;
	$views = getPostViews($post_id);
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
	} else if($video_provider == 'vimeo') {
		$file_contents = file_get_contents("http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/" . $video_id, true);
		$vimeo_info    = json_decode($file_contents,true);
		$duration      = $vimeo_info['duration'];
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . $video_id . ".php"));
		$src = $hash[0]['thumbnail_medium'];  
	}
	
	// Convert video duration from seconds to minutes or hours
	if($duration >= 3600) {
		$duration = gmdate("H:i:s", $duration);
	} else {
		$duration = gmdate("i:s", $duration);
	}
	
	// Other vids info
	$author_id=$post->post_author;
	$author_url = get_author_posts_url($author_id);
	
	$post_like_count = getTotalLike($post_id) ?: 0;
	$post_dislike_count = getTotalDislike($post_id) ?: 0;
	$published_date = get_the_time('F j, Y', $post_id);
	$src = getImgSrc($post_id);
?>							
							<article>
								<div class="cover" style="background-image: url(<?php echo $src; ?>)">
									<a href="<?php the_permalink(); ?>"></a>
								</div>
								<header>
								<h1>
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>			
								</a>

								</h1>
								<div class="search-info">
									<div class="firstLine">
										<div class="author">
											<i class="fa fa-user"></i>
											<span class="author_name"><a href="<?php echo $author_url; ?>"><?php the_author(); ?></a></span>
											<span class="straight-sep">|</span>
										</div>
										<div class="published_date">
											<i class="fa fa-calendar"></i>
											<span class="published_date_inner"><?php echo $published_date; ?></span>
											<span class="straight-sep">|</span>
										</div>
										<div class="vid_views">
											<i class="fa fa-eye"></i>
											<span class="vid_views_inner"><?php echo $views; ?></span>
											<span class="straight-sep">|</span>
										</div>
										<div class="vid_categories">
											<span class="vid_cat_icon">
												<i class="fa fa-folder-open-o"></i>
											</span>
											<div class="vid_cat_btn">
											<?php
												$categories = get_the_category($id);
												foreach ($categories as $category) {
												?>
													<a class="btn btn-cyan" href="<?php echo get_category_link($category->cat_ID); ?>" title="View all posts in <?php echo $category->name?>"><?php echo $category->name?></a>
												<?php
												}

											?>
											<span class="straight-sep">|</span>
											</div>
										</div>
										<div class="vid_ratings">
											<span title="Ratings: <?php echo $ratings; ?>" class="post-small-rate">
												<span style="width:<?php echo $per; ?>%"></span>
											</span>
										</div>				
									</div>
									

									<div class="vid_tags">
										<span class="vid_tag_icon">
											<i class="fa fa-tags"></i>
										</span>
										<div class="vid_tag_btn">
										<?php
											$tags = get_the_tags();
											if( $tags ) foreach( $tags as $tag ) {
											?>
												<a href="<?php echo get_tag_link($tag->term_id); ?>" rel="tag" title="View all posts tagged with <?php echo $tag->name?>"><?php echo $tag->name; ?></a>
											<?php
											}

										?>
										</div>
									</div>							
								</div>
								<div class="summary"><?php echo strip_tags(limit_excerpt(get_the_content(),30)); ?></div>
								</header>
							</article>							
							
						</div>
						<div class="fusion-clearfix"></div>
						<?php if(!$smof_data['post_meta']): ?>
						<div class="meta-info">
							<?php if($smof_data['search_layout'] == 'Grid' || $smof_data['search_layout'] == 'Timeline'): ?>
							<?php if($smof_data['search_layout'] != 'Large Alternate' && $smof_data['search_layout'] != 'Medium Alternate'): ?>
							<div class="alignleft">
								<?php if(!$smof_data['post_meta_read']): ?><a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read More', 'Avada'); ?></a><?php endif; ?>
							</div>
							<?php endif; ?>
							<div class="alignright">
								<?php if(!$smof_data['post_meta_comments']): ?><?php comments_popup_link('<i class="icon-bubbles"></i>&nbsp;'.__('0', 'Avada'), '<i class="icon-bubbles"></i>&nbsp;'.__('1', 'Avada'), '<i class="icon-bubbles"></i>&nbsp;'.'%'); ?><?php endif; ?>
							</div>
							<?php else: ?>
							<?php if($smof_data['search_layout'] != 'Large Alternate' && $smof_data['search_layout'] != 'Medium Alternate'): ?>
							<div class="alignleft vcard">
							<?php if(!$smof_data['post_meta_author']): ?><?php echo __('By', 'Avada'); ?> <span class="fn"><?php the_author_posts_link(); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$smof_data['post_meta_date']): ?><span class="updated" style="display:none;"><?php the_modified_time( 'c' ); ?></span><span class="published"><?php the_time($smof_data['date_format']); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$smof_data['post_meta_cats']): ?><?php if(!$smof_data['post_meta_tags']){ echo __('Categories:', 'Avada') . ' '; } ?><?php the_category(', '); ?><span class="sep">|</span><?php endif; ?><?php if(!$smof_data['post_meta_tags']): ?><span class="meta-tags"><?php echo __('Tags:', 'Avada') . ' '; the_tags( '' ); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$smof_data['post_meta_comments']): ?><?php comments_popup_link(__('0 Comments', 'Avada'), __('1 Comment', 'Avada'), '% '.__('Comments', 'Avada')); ?><?php endif; ?>
							</div>
							<?php endif; ?>
							<div class="alignright">
								<?php if(!$smof_data['post_meta_read']): ?><a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read More', 'Avada'); ?></a><?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
				$prev_post_timestamp = $post_timestamp;
				$prev_post_month = $post_month;
				$post_count++;
				endwhile;
				?>
			</div>
			<?php themefusion_pagination($pages = '', $range = 2); ?>
		<?php wp_reset_query(); ?>
	<?php else: ?>
	<div class="post-content">
		<div class="fusion-title title">
			<h2 class="title-heading-left"><?php echo __('Couldn\'t find what you\'re looking for!', 'Avada'); ?></h2><div class="title-sep-container"><div class="title-sep sep-double"></div></div>			
		</div>
		<div class="error_page">
			<div style="text-align: center;">
				<h1 class="oops <?php echo ($sidebar_css != 'display:none') ? 'sidebar-oops' : ''; ?>"><?php echo __('Oops!', 'Avada'); ?></h1>
			</div>
		</div>
	</div>
	<?php endif; ?>
	</div>
	<?php if( $sidebar_exists == true ): ?>
	<?php wp_reset_query(); ?>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>">
	<?php
	if ($smof_data['search_sidebar'] != 'None' && function_exists('dynamic_sidebar')) {
		generated_dynamic_sidebar($smof_data['search_sidebar']);
	}
	?>
	</div>
	<?php endif; ?>
<?php get_footer(); ?>