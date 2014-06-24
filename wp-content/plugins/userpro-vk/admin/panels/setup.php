<form method="post" action="">

<h3><?php _e('Setup Credentials','userpro-vk'); ?></h3>
<table class="form-table">
	
	<tr valign="top">
		<th scope="row"><label for="vk_connect"><?php _e('Enable VK.com connect','userpro'); ?></label></th>
		<td>
			<select name="vk_connect" id="vk_connect" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_vk_get_option('vk_connect')); ?>><?php _e('Yes','userpro'); ?></option>
				<option value="0" <?php selected('0', userpro_vk_get_option('vk_connect')); ?>><?php _e('No','userpro'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="vk_api_id"><?php _e('Application ID','userpro'); ?></label></th>
		<td>
			<input type="text" name="vk_api_id" id="vk_api_id" value="<?php echo userpro_vk_get_option('vk_api_id'); ?>" class="regular-text" />
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="vk_api_secret"><?php _e('Secure Key','userpro'); ?></label></th>
		<td>
			<input type="text" name="vk_api_secret" id="vk_api_secret" value="<?php echo userpro_vk_get_option('vk_api_secret'); ?>" class="regular-text" />
		</td>
	</tr>
	
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','userpro'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','userpro'); ?>"  />
</p>

</form>