<?php 
	global $smof_data; 
	global $current_user;
	get_currentuserinfo();
	$display_name = $current_user->display_name;
	$avatar = get_avatar( $current_user->ID, 36 );
?>
<div class="header-v2">
	<?php if($smof_data['header_left_content'] != 'Leave Empty' || $smof_data['header_right_content'] != 'Leave Empty'): ?>
	<div class="header-social">
		<div class="avada-row">
			<div class="alignleft">
				<?php
				if($smof_data['header_left_content'] == 'Contact Info') {
					get_template_part('framework/headers/header-info');
				} elseif($smof_data['header_left_content'] == 'Social Links') {
					get_template_part('framework/headers/header-social');
				} elseif($smof_data['header_left_content'] == 'Navigation') {
					get_template_part('framework/headers/header-menu');
				}
				?>
			</div>
			<div class="middle-search">
				<?php echo do_shortcode('[ajaxy-live-search show_category="1" show_post_category="1" post_types="post" label="Search" iwidth="180" delay="500" width="315" url="http://localhost/gv/?s=%s" credits="1" border="1px solid #eee"]'); ?>
			</div>
			<div class="alignright">
				<?php
				if($data['header_right_content'] == 'Contact Info') {
					get_template_part('framework/headers/header-info');
				} elseif($data['header_right_content'] == 'Social Links') {
					get_template_part('framework/headers/header-social');
				} elseif($data['header_right_content'] == 'Navigation') {
					get_template_part('framework/headers/header-menu');
				}
				?>
				<?php 
				if(!is_user_logged_in()) { 
				?>
					<a href="<?php echo get_site_url();?>/login/" class="btn btn-primary btn-lg"><i class="fa fa-sign-in fa-lg login"></i> Log In</a>
					<a href="<?php echo get_site_url();?>/register/" class="btn btn-success btn-lg"><i class="fa fa-pencil fa-lg signup"></i> Register</a>
				<?php 
				} else {
				?>
						<nav id="colorNav">
							<ul>
								<li class="green">
									<div class="btn-group user-wrapper">
										<button type="button" class="btn btn-primary welcome">Welcome, <?php echo $display_name; ?></button>
										<button type="button" class="btn btn-primary avatar">
											<?php
												echo $avatar;
											 ?>
										</button>
									</div>
									
									<ul>
										<li><a href="<?php echo get_site_url();?>/video-submission/">Post new Video</a></li>
										<li><a href="<?php echo get_site_url();?>/video-manager/">Video Manager</a></li>
										<li><a href="<?php echo get_site_url();?>/profile/edit">Edit Profile</a></li>
										<li><a href="<?php echo get_site_url();?>/logout/">Log Out</a></li>
									</ul>
								</li>

								<!-- More menu items -->

							</ul>
						</nav>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<header id="header">
		<div class="avada-row" style="padding-top:<?php echo $smof_data['margin_header_top']; ?>;padding-bottom:<?php echo $smof_data['margin_header_bottom']; ?>;">
			<div class="logo" data-margin-right="<?php echo $smof_data['margin_logo_right']; ?>" data-margin-left="<?php echo $smof_data['margin_logo_left']; ?>" data-margin-top="<?php echo $smof_data['margin_logo_top']; ?>" data-margin-bottom="<?php echo $smof_data['margin_logo_bottom']; ?>" style="margin-right:<?php echo $smof_data['margin_logo_right']; ?>;margin-top:<?php echo $smof_data['margin_logo_top']; ?>;margin-left:<?php echo $smof_data['margin_logo_left']; ?>;margin-bottom:<?php echo $smof_data['margin_logo_bottom']; ?>;">
				<a href="<?php bloginfo('url'); ?>">
					<img src="<?php echo $smof_data['logo']; ?>" alt="<?php bloginfo('name'); ?>" class="normal_logo" />
					<?php if($smof_data['logo_retina'] && $smof_data['retina_logo_width'] && $smof_data['retina_logo_height']): ?>
					<?php
					$pixels ="";
					if(is_numeric($smof_data['retina_logo_width']) && is_numeric($smof_data['retina_logo_height'])):
					$pixels ="px";
					endif; ?>
					<img src="<?php echo $smof_data["logo_retina"]; ?>" alt="<?php bloginfo('name'); ?>" style="width:<?php echo $smof_data["retina_logo_width"].$pixels; ?>;max-height:<?php echo $smof_data["retina_logo_height"].$pixels; ?>; height: auto !important" class="retina_logo" />
					<?php endif; ?>
				</a>
			</div>
			<?php if($smof_data['ubermenu']): ?>
			<nav id="nav-uber">
			<?php else: ?>
			<nav id="nav" class="nav-holder">
			<?php endif; ?>
				<?php get_template_part('framework/headers/header-main-menu'); ?>
			</nav>
			<?php if(tf_checkIfMenuIsSetByLocation('main_navigation')): ?>
			<div class="mobile-nav-holder main-menu"></div>
			<?php endif; ?>
		</div>
	</header>
</div>