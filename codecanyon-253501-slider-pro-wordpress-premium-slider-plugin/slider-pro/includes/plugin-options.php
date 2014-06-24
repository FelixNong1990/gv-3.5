<div class="wrap slider-pro">
	<div class="slider-icon"></div>
	<h2><?php _e('Plugin Options', 'slider_pro'); ?></h2>
	
	<?php
	if (isset($_POST['plugin_options_update']))
    	echo '<div id="message" class="updated below-h2"><p>' . __('Plugin options updated.', 'slider_pro') . '</p></div>';
	?>
	
    <form class="plugin-options" name="plugin_options" method="post" action="">
    <?php wp_nonce_field('plugin-options-update', 'plugin-options-nonce'); ?>
    	
    	<table>
    		<tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_enable_timthumb" <?php echo get_option('slider_pro_enable_timthumb') == 1 ? 'checked="checked"' : ''; ?>>
	        		<label><?php _e( 'Enable TimThumb', 'slider_pro' ); ?></label>
	        	</td>
	        	<td>
	            	<p><?php _e( 'This option needs to be enabled if you want the images to be dynamically resized.', 'slider_pro' ); ?> </p>
	            </td>
	        </tr>

	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_use_compressed_scripts" <?php echo get_option('slider_pro_use_compressed_scripts') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Use compressed scripts', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'You can disable this option if you want to use the uncompressed scripts, for debugging or other reasons.', 'slider_pro' ); ?> </p>
	            </td>
	        </tr>
			
			<tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_visual_editor" <?php echo get_option('slider_pro_visual_editor') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Visual editor', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'This option will enable the TinyMCE visual editor for the Caption and Inline HTML sections. If you disable it, only a simple text area will be displayed.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>
	        
	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_show_admin_bar_links" <?php echo get_option('slider_pro_show_admin_bar_links') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Show admin bar links', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'If you don\'t want the Slider PRO links to be displayed in the admin bar, you can disable this option.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>
	        
	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_enqueue_jquery" <?php echo get_option('slider_pro_enqueue_jquery') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Enqueue jQuery', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'The slider will need the jQuery library but if you already load it in your theme you can disable this option.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>
	        
	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_enqueue_jquery_easing" <?php echo get_option('slider_pro_enqueue_jquery_easing') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Enqueue jQuery Easing', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'If you only use the default easing type you can disable this option.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>
	        
	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_enqueue_jquery_mousewheel" <?php echo get_option('slider_pro_enqueue_jquery_mousewheel') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Enqueue jQuery MouseWheel', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'If you use a different plugin for handling the mouse wheel input you can disable this option.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>
	        
	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_generate_xml_file" <?php echo get_option('slider_pro_generate_xml_file') == 1 ? 'checked="checked"' : ''; ?>>
	        		<label><?php _e( 'Generate slider XML file', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'This feature will generate an XML file for each of your sliders allowing you to export/import sliders. If your server doesn\'t have the DOM XML extension installed, you can disable the feature.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>

	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_multisite_path_rewrite" <?php echo get_option('slider_pro_multisite_path_rewrite') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Rewrite MultiSite paths', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'This option needs to be enabled if you use the slider in a WordPress MultiSite environment and TimThumb is enabled. This feature will rewrite the vritual image paths that are used in Multisite to real file paths that are necessary for TimThumb.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>

	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_custom_media_loader" <?php echo get_option('slider_pro_custom_media_loader') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Enable the custom Media Loader', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'Since WordPress 3.5 the slider uses the default media library but you can revert to the previous custom media loader by checking this option.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>

	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_caption_html" <?php echo get_option('slider_pro_caption_html') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Enable the Caption and Inline HTML sections', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'These fields are available by default in slider versions previous to version 3.0. In version 3.0, the Layers section was introduced and is meant to be a replacement for both the Caption and Inline HTML sections.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>

	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_dynamic_slides_featured_filter" <?php echo get_option('slider_pro_dynamic_slides_featured_filter') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Enable dynamic slides "Featured" filter', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'Enables an additional filter for the "Posts Content" slides, allowing you to fetch only posts that were selected from within the post\'s edit page.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>

	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_show_getting_started_message" <?php echo get_option('slider_pro_show_getting_started_message') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Show "Getting Started" message', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'Indicates whether the "Getting Started" message will be displayed in the Sliders page.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>

	        <tr>
	        	<td>
	        		<input type="checkbox" name="slider_pro_display_all_slider_options" <?php echo get_option('slider_pro_display_all_slider_options') == 1 ? 'checked="checked"' : ''; ?>>
	            	<label><?php _e( 'Display all slider options', 'slider_pro' ); ?></label>
	            </td>
	        	<td>
	            	<p><?php _e( 'Indicates whether all the slider\'s options will be visible by default. If disabled, the options will be hidden dy default, and displayed only if they are selected.', 'slider_pro' ); ?></p>
	            </td>
	        </tr>

        	<tr>
	        	<td>
	        		<select name="slider_pro_role_access">
						<?php
							global $sliderpro_role_access;
		                    foreach ($sliderpro_role_access as $key => $value) {
		                        $selected = get_option('slider_pro_role_access') == $key ? 'selected="selected"' : '';
		                        echo "<option $selected>" . $key . "</option>";
		                    }
		                ?>
		            </select>
		            <label><?php _e( 'Role Access', 'slider_pro' ); ?></label>
		        </td>
	        	<td>
            		<p><?php _e( 'Select which role you want to have access to the Slider PRO plugin.', 'slider_pro' ); ?></p>
				</td>
	        </tr>
		</table>
		
        <input type="submit" name="plugin_options_update" class="button-primary" value="<?php _e( 'Update Options', 'slider_pro' ); ?>"/>
    </form>

    <div class="automatic-updates">

	    <h3><?php _e( 'Automatic updates', 'slider_pro' ); ?></h3>
		
		<form action="" method="post" class="purchase-code">
	        <?php wp_nonce_field( 'purchase-code-update', 'purchase-code-nonce' ); ?>
	        
	        <?php
	        	$is_assets_dir = get_option( 'slider_pro_is_assets_dir' );

	            if ( $purchase_code_status === '0' ) {
	                $purchase_code_message_class = 'empty-code';
	                $purchase_code_message = __( 'Please enter your purchase code in order to have access to automatic updates.', 'slider_pro' );
	            } else if ( $purchase_code_status === '1' ) {
	                $purchase_code_message_class = 'valid-code';
	                $purchase_code_message = __( 'The purchase code is valid.', 'slider_pro' );
	            } else if ( $purchase_code_status === '2' ) {
	                $purchase_code_message_class = 'not-valid-code';
	                $purchase_code_message = __( 'The purchase code is not valid.', 'slider_pro' );
	            } else if ( $purchase_code_status === '3' ) {
	                $purchase_code_message_class = 'not-valid-code';
	                $purchase_code_message = __( 'An error occurred during the validation. Please try again later and if the error persists, contact the plugin\'s author.', 'slider_pro' );
	            }

	            if ( $purchase_code_status === '1' && $is_assets_dir != 'yes' ) {
	            	 $purchase_code_message .= ' ' . __( 'You\'re almost ready. Please prepare the plugin for automatic updates, as indicated below.', 'slider_pro' );
	            }
	        ?>

	        <p class="purchase-code-message <?php echo $purchase_code_message_class; ?>"><?php echo $purchase_code_message; ?></p>

	        <label for="purchase-code-field"><?php _e( 'Purchase Code:', 'slider_pro' ); ?></label>
	        <input type="text" id="purchase-code-field" name="purchase_code" class="purchase-code-field" value="<?php echo esc_attr( $purchase_code ); ?>">
	        <input type="submit" name="purchase_code_update" class="button-secondary" value="<?php _e( 'Verify Purchase Code', 'slider_pro' ); ?>" />
	    </form>
		
		<?php
	    	if ( $purchase_code_status === '1' ) {
		?>
				<div class="prepare-plugin">
					<?php
						if ( $is_assets_dir == 'yes' ) {
					?>
							<p class="prepare-plugin-message plugin-ready"><?php _e( 'The plugin is ready for automatic updates. However, as WordPress recommends, it\'s a best practice to backup your site before doing any update.', 'slider_pro' ); ?></p>
					<?php
						} else if ( $is_assets_dir == 'no' ) {
					?>
							<div class="prepare-plugin-message plugin-not-ready">
								<p><?php _e( 'The folders could not be created automatically, so they need to be created manually.', 'slider_pro' ); ?></p>
								<p><?php _e( 'This process is required only once, and all you have to do is create the \'slider-pro-assets\' folder in \'wp-content/plugins/\' and then copy the \'custom\', \'skins\' and \'xml\' folders from \'wp-content/plugins/slider-pro/\' into \'wp-content/plugins/slider-pro-assets/\'. Then, click the \'Prepare plugin for update\' button below again.', 'slider_pro' ); ?></p>
								<p><?php _e( 'If you need assistance with this, please contact <a target="_blank" href="http://support.bqworks.com">support</a>, and we\'ll help you.', 'slider_pro' ); ?></p>
							</div>
					<?php
						}
					?>

					<?php
						if ( empty($is_assets_dir) || $is_assets_dir == false || $is_assets_dir == 'no' ) {
					?>
						    <form action="" method="post">
						    	<h3><?php _e( 'Prepare the plugin for automatic updates', 'slider_pro' ); ?></h3>

						    	<p><?php _e( 'This is required only once, before the first update.', 'slider_pro' ); ?></p>
						    	<p><?php _e( 'In order to preserve your customizations during an update, the plugin needs to move some folders, that contain custom files, outside the main plugin folder.', 'slider_pro' ); ?></p>
						    	<p><?php _e( 'The folders that need to be preserved are:', 'slider_pro' ); ?></p>
						    	<ul>
						    		<li><?php _e( '\'xml\' folder - which contains all the sliders exported as XML files', 'slider_pro' ); ?></li>
						    		<li><?php _e( '\'custom\' folder - which contains the custom CSS and JavaScript', 'slider_pro' ); ?></li>
						    		<li><?php _e( '\'skins\' folder - contains all the skin files', 'slider_pro' ); ?></li>
						    	</ul>
						    	<p><?php _e( 'The plugin will attempt to move the folders for you automatically, if you click the button below. The folders will be moved in \'wp-content/plugins/slider-pro-assets\'. The \'slider-pro-assets\' will be created automatically.', 'slider_pro' ); ?></p>
						    	<p><?php _e( 'If folder permissions on the server don\'t allow the plugin to move the folders automatically, you will need to move them manually, in order to avoid losing customizations.', 'slider_pro' ); ?></p>

						        <?php wp_nonce_field( 'prepare-plugin-update', 'prepare-plugin-nonce' ); ?>

						        <input type="submit" name="prepare_plugin" class="button-secondary" value="<?php _e( 'Prepare plugin for update', 'slider_pro' ); ?>" />
						    </form>
					<?php
						}
					?>
				</div>
		<?php
			}
		?>
	</div>
</div>