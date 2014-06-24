<form method="post" action="">

<h3><?php _e('General Settings','userpro-fav'); ?></h3>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="auto_bookmark"><?php _e('Automatically add bookmark widget after post content','userpro-fav'); ?></label></th>
		<td>
			<select name="auto_bookmark" id="auto_bookmark" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_fav_get_option('auto_bookmark')); ?>><?php _e('Yes','userpro-fav'); ?></option>
				<option value="0" <?php selected('0', userpro_fav_get_option('auto_bookmark')); ?>><?php _e('No','userpro-fav'); ?></option>
			</select>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="include_post_types[]"><?php _e('Enable these post types','userpro-fav'); ?></label></th>
		<td>
			<select name="include_post_types[]" id="include_post_types[]" multiple="multiple" class="chosen-select" style="width:300px" data-placeholder="<?php _e('Choose post types','userpro-fav'); ?>">
				<?php
				$array = userpro_admin_fav_get_posttypes();
				foreach($array as $k=>$v) {
				?>
				<option value="<?php echo $k; ?>" <?php userpro_is_selected($k, userpro_fav_get_option('include_post_types') ); ?>><?php echo $v; ?></option>
				<?php } ?>
			</select>
			<span class="description"><?php _e('Select here the post types that can be bookmarked (If you enable auto insertion of bookmark widget)','userpro-fav'); ?></span>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="exclude_ids"><?php _e('Exclude these IDs','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="exclude_ids" id="exclude_ids" value="<?php echo userpro_fav_get_option('exclude_ids'); ?>" class="regular-text" />
			<span class="description"><?php _e('For automatic widget insertion, this can exclude the post IDs you specify here (seperated by comma) regardless of any other settings.','userpro'); ?></span>
		</td>
	</tr>
	
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','userpro-fav'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','userpro-fav'); ?>"  />
</p>

<h3><?php _e('Bookmark Shortcode Settings','userpro-fav'); ?></h3>
<p><?php _e('These settings can be overridden by shortcode options. They are for general bookmark template that appears on your content. The bookmark sidebar widget may override some settings to make it look perfect in sidebar.','userpro-fav'); ?></p>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="width"><?php _e('Bookmark Widget Width','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="width" id="width" value="<?php echo userpro_fav_get_option('width'); ?>" class="regular-text" />
			<span class="description"><?php _e('e.g. 250px, 50%, 300px, etc.','userpro'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="widgetized"><?php _e('Widgetized Look','userpro-fav'); ?></label></th>
		<td>
			<select name="widgetized" id="widgetized" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_fav_get_option('widgetized')); ?>><?php _e('Yes','userpro-fav'); ?></option>
				<option value="0" <?php selected('0', userpro_fav_get_option('widgetized')); ?>><?php _e('No','userpro-fav'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="align"><?php _e('Default Alignment','userpro-fav'); ?></label></th>
		<td>
			<select name="align" id="align" class="chosen-select" style="width:300px">
				<option value="left" <?php selected('left', userpro_fav_get_option('align')); ?>><?php _e('Left','userpro-fav'); ?></option>
				<option value="right" <?php selected('right', userpro_fav_get_option('align')); ?>><?php _e('Right','userpro-fav'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="inline"><?php _e('Inline Display','userpro-fav'); ?></label></th>
		<td>
			<select name="inline" id="inline" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_fav_get_option('inline')); ?>><?php _e('Yes','userpro-fav'); ?></option>
				<option value="0" <?php selected('0', userpro_fav_get_option('inline')); ?>><?php _e('No','userpro-fav'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="no_top_margin"><?php _e('Disable top margin','userpro-fav'); ?></label></th>
		<td>
			<select name="no_top_margin" id="no_top_margin" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_fav_get_option('no_top_margin')); ?>><?php _e('Yes','userpro-fav'); ?></option>
				<option value="0" <?php selected('0', userpro_fav_get_option('no_top_margin')); ?>><?php _e('No','userpro-fav'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="no_bottom_margin"><?php _e('Disable bottom margin','userpro-fav'); ?></label></th>
		<td>
			<select name="no_bottom_margin" id="no_bottom_margin" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_fav_get_option('no_bottom_margin')); ?>><?php _e('Yes','userpro-fav'); ?></option>
				<option value="0" <?php selected('0', userpro_fav_get_option('no_bottom_margin')); ?>><?php _e('No','userpro-fav'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="pct_gap"><?php _e('Gap between buttons (%)','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="pct_gap" id="pct_gap" value="<?php echo userpro_fav_get_option('pct_gap'); ?>" class="regular-text" />
			<span class="description"><?php _e('This is used if your bookmark widget width is fluid using percentages.','userpro'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="px_gap"><?php _e('Gap between buttons (px)','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="px_gap" id="px_gap" value="<?php echo userpro_fav_get_option('px_gap'); ?>" class="regular-text" />
			<span class="description"><?php _e('This is used if your bookmark widget width is fixed using pixels.','userpro'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="remove_bookmark"><?php _e('Text for "Remove Bookmark"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="remove_bookmark" id="remove_bookmark" value="<?php echo userpro_fav_get_option('remove_bookmark'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="dialog_bookmarked"><?php _e('Text for "Bookmark has been added"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="dialog_bookmarked" id="dialog_bookmarked" value="<?php echo userpro_fav_get_option('dialog_bookmarked'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="dialog_unbookmarked"><?php _e('Text for "Bookmark has been removed"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="dialog_unbookmarked" id="dialog_unbookmarked" value="<?php echo userpro_fav_get_option('dialog_unbookmarked'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="default_collection"><?php _e('Text for "Default Collection"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="default_collection" id="default_collection" value="<?php echo userpro_fav_get_option('default_collection'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="add_to_collection"><?php _e('Text for "Add to Collection"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="add_to_collection" id="add_to_collection" value="<?php echo userpro_fav_get_option('add_to_collection'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="new_collection"><?php _e('Text for "New Collection"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="new_collection" id="new_collection" value="<?php echo userpro_fav_get_option('new_collection'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="new_collection_placeholder"><?php _e('Text for "New Collection Placeholder"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="new_collection_placeholder" id="new_collection_placeholder" value="<?php echo userpro_fav_get_option('new_collection_placeholder'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="add_new_collection"><?php _e('Text for "Submit New Collection"','userpro-fav'); ?></label></th>
		<td>
			<input type="text" name="add_new_collection" id="add_new_collection" value="<?php echo userpro_fav_get_option('add_new_collection'); ?>" class="regular-text" />
		</td>
	</tr>
	
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','userpro-fav'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','userpro-fav'); ?>"  />
</p>

</form>