<form method="post" action="">

<h3><?php _e('Appearance','userpro-msg'); ?></h3>
<table class="form-table">
	
	<tr valign="top">
		<th scope="row"><label for="msg_notification"><?php _e('New Message Notification','userpro'); ?></label></th>
		<td>
			<select name="msg_notification" id="msg_notification" class="chosen-select" style="width:300px">
				<option value="r" <?php selected('r', userpro_msg_get_option('msg_notification')); ?>><?php _e('Show at bottom right','userpro-msg'); ?></option>
				<option value="l" <?php selected('l', userpro_msg_get_option('msg_notification')); ?>><?php _e('Show at bottom left','userpro-msg'); ?></option>
			</select>
		</td>
	</tr>

</table>

<h3><?php _e('Messaging Options','userpro-msg'); ?></h3>
<table class="form-table">
	
	<tr valign="top">
		<th scope="row"><label for="msg_privacy"><?php _e('Global Messaging Privacy','userpro'); ?></label></th>
		<td>
			<select name="msg_privacy" id="msg_privacy" class="chosen-select" style="width:300px">
				<option value="public" <?php selected('public', userpro_msg_get_option('msg_privacy')); ?>><?php _e('Open to all','userpro-msg'); ?></option>
				<option value="mutual" <?php selected('mutual', userpro_msg_get_option('msg_privacy')); ?>><?php _e('Mutual Followers','userpro-msg'); ?></option>
				<option value="none" <?php selected('none', userpro_msg_get_option('msg_privacy')); ?>><?php _e('Disable Messaging','userpro-msg'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="email_notifications"><?php _e('Turn on e-mail notifications','userpro'); ?></label></th>
		<td>
			<select name="email_notifications" id="msg_privacy" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_msg_get_option('email_notifications')); ?>><?php _e('Enabled','userpro-msg'); ?></option>
				<option value="0" <?php selected('0', userpro_msg_get_option('email_notifications')); ?>><?php _e('Disabled','userpro-msg'); ?></option>
			</select>
			<span class="description"><?php _e('Send an e-mail notification when someone receives a new message. This is a global option, so be careful. Privacy settings are still under development.','userpro-msg'); ?></span>
		</td>
	</tr>
	
</table>

<h3><?php _e('Automated Welcome Message','userpro-msg'); ?></h3>
<table class="form-table">
	
	<tr valign="top">
		<th scope="row"><label for="msg_auto_welcome"><?php _e('Send an automated message to new users','userpro'); ?></label></th>
		<td>
			<select name="msg_auto_welcome" id="msg_auto_welcome" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_msg_get_option('msg_auto_welcome')); ?>><?php _e('Enabled','userpro-msg'); ?></option>
				<option value="0" <?php selected('0', userpro_msg_get_option('msg_auto_welcome')); ?>><?php _e('Disabled','userpro-msg'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="msg_auto_welcome_id"><?php _e('Admin User ID','userpro-msg'); ?></label></th>
		<td>
			<input type="text" name="msg_auto_welcome_id" id="msg_auto_welcome_id" value="<?php echo userpro_msg_get_option('msg_auto_welcome_id'); ?>" class="regular-text" />
			<span class="description"><?php _e('This is used to tell the user who has sent them the message. e.g. Enter user ID for the account who welcome users, like your own (admin) user ID.','userpro-msg'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="msg_auto_welcome_text"><?php _e('Message Body','userpro'); ?></label></th>
		<td><textarea name="msg_auto_welcome_text" id="msg_auto_welcome_text" class="large-text code" rows="3"><?php echo stripslashes( esc_attr(userpro_msg_get_option('msg_auto_welcome_text')) ); ?></textarea></td>
	</tr>
	
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','userpro-msg'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','userpro-msg'); ?>"  />
</p>

</form>