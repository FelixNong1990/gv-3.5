<?php

class userpro_vk_api {

	var $vk;
	var $vk_config;
	function __construct() {

		add_action('init', array(&$this, 'load_vk'), 9);
		
		add_action('init', array(&$this, 'authorize_vk'), 10);
		
		add_action('userpro_social_connect_buttons', array(&$this, 'vk_button'), 9);
		
	}
	
	/* Load VK */
	function load_vk(){
		if ( userpro_vk_get_option('vk_connect') == 1 && userpro_vk_get_option('vk_api_id') && userpro_vk_get_option('vk_api_secret')  ) {
			require_once( userpro_vk_path . 'lib/VK.php');
			require_once( userpro_vk_path . 'lib/VKException.php');
			
			$this->vk_config = array(
				'app_id'        => userpro_vk_get_option('vk_api_id'),
				'api_secret'    => userpro_vk_get_option('vk_api_secret'),
				'redirect_uri'  => $this->redirect_uri(),
				'api_settings'  => 'photos,wall'
			);
			
			$this->vk = new VK( $this->vk_config['app_id'], $this->vk_config['api_secret'] );
			
			if (isset($_SESSION['vk_token'])) {

			}
				
		}
	}
	
	/* Authorize VK */
	function authorize_vk(){
		global $userpro;
		if ( userpro_vk_get_option('vk_connect') == 1 && userpro_vk_get_option('vk_api_id') && userpro_vk_get_option('vk_api_secret')  ) {
		
			$vk = $this->load_vk();
			
			if ( isset($_REQUEST['code']) && isset($_REQUEST['upslug']) && $_REQUEST['upslug'] == 'vk' ) {
			
				if (isset($_SESSION['vk_token'])) {
					$access_token = $_SESSION['vk_token'];
				} else {
					$vk_token = $this->vk->getAccessToken($_REQUEST['code'], $this->vk_config['redirect_uri']);
					$_SESSION['vk_token'] = $vk_token;
					$access_token = $_SESSION['vk_token'];
				}

				$result = $this->vk->api('getProfiles', array(
					'uids' => $access_token['user_id'],
					'fields' => 'uid, first_name, last_name, nickname, screen_name, photo_big',
				));

				$user_info = $result['response'][0];
				
				// user information is ready!
				extract($user_info);
				if (isset($user_info) && is_array($user_info)){
			
						$users = get_users(array(
							'meta_key'     => 'userpro_vk_id',
							'meta_value'   => $uid,
							'meta_compare' => '='
						));
						if (isset($users[0]->ID) && is_numeric($users[0]->ID) ){
							$returning = $users[0]->ID;
							$returning_user_login = $users[0]->user_login;
						} else {
							$returning = '';
						}

						if (userpro_is_logged_in()) {
						
							$this->update_vk_id( get_current_user_id(), $uid );
							wp_redirect( $userpro->permalink() );
							
						} else {
							if ( $returning != '' ) {
								
								userpro_auto_login( $returning_user_login, true );		
								wp_redirect( $userpro->permalink() );
							
							} else {
							
								$user_pass = wp_generate_password( $length=12, $include_standard_special_chars=false );
								$unique_user = $userpro->unique_user('vk', $user_info);
								$user_id = $this->new_user( $unique_user, $user_pass, '', $user_info, $type='vk' );
								userpro_auto_login( $unique_user, true );
								wp_redirect( $userpro->permalink() );
								
							}
						}
				}
			}
		
		}
	}
	
	/* Callback url */
	function redirect_uri(){
		global $post;
		if ( is_front_page() ) :
			$page_url = home_url();
			else :
			$page_url = 'http';
		if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" )
			$page_url .= "s";
				$page_url .= "://";
				if ( $_SERVER["SERVER_PORT"] != "80" )
			$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
				else
			$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			endif;
			
		if ( !isset($_REQUEST['code']) ) {
			$page_url = add_query_arg('upslug', 'vk', $page_url);
		}
		$page_url = remove_query_arg( array( 'code' ), $page_url );
		return esc_url( $page_url );
	}
	
	/* Get VK button */
	function vk_button(){
		if ( userpro_vk_get_option('vk_connect') == 1 && userpro_vk_get_option('vk_api_id') && userpro_vk_get_option('vk_api_secret')  ) {
			$url = $this->getAuthorizeURL();
		?>
		
		<a href="<?php echo $this->getAuthorizeURL(); ?>" class="userpro-social-vk userpro-tip" title="<?php _e('Login with VK.com','userpro-vk'); ?>"></a>
		
		<?php
		}
	}
	
	/* Get authorization url */
	function getAuthorizeURL(){
	
		$authorize_url = $this->vk->getAuthorizeURL($this->vk_config['api_settings'], $this->vk_config['redirect_uri'], false);
		return $authorize_url;
		
	}
	
	/* Create a new user */
	function new_user($username, $password, $email, $form, $type, $approved=1) {
		global $userpro;
		$user_id = wp_create_user( $username, $password, $email );
		
		$userpro->default_role($user_id, $form);
		
		$this->update_profile_via_vk( $user_id, $form );
		$this->update_profile_pic_vk( $user_id, $form );
		
		if ($approved==1){
			userpro_mail($user_id, 'newaccount', $password, $form );
			do_action('userpro_after_new_registration', $user_id);
		}
		
		return $user_id;
	}
	
	/* update VK ID */
	function update_vk_id($user_id, $id){
		update_user_meta($user_id, 'userpro_vk_id', $id);
	}
	
	/* Update user profile */
	/*
		'uid, first_name, last_name, nickname, screen_name, photo_big'
	*/
	function update_profile_via_vk($user_id, $array) {
		global $userpro;
		extract($array);

		if ( userpro_is_logged_in() && ( $user_id != get_current_user_id() ) && !current_user_can('manage_options') )
			die();
			
		if ($uid) { update_user_meta($user_id, 'userpro_vk_id', $uid); }
		
		/* begin display name */
		if ($nickname) {
			$display_name = $nickname;
		} else if ($first_name && $last_name) {
			$display_name = $first_name . ' ' . $last_name;
		} else if ($screen_name != '') {
			$display_name = $screen_name;
		} else {
			$display_name = $uid;
		}
		
		if ($display_name) {
			if ($userpro->display_name_exists( $display_name )){
				$display_name = $userpro->unique_display_name($display_name);
			}
			$display_name = $userpro->remove_denied_chars($display_name);
			wp_update_user( array('ID' => $user_id, 'display_name' => $display_name ) );
			update_user_meta($user_id, 'display_name', $display_name);
		}
		/* end display name */
		
		if ( $last_name ) {
			update_user_meta($user_id, 'last_name', $last_name );
		}
		
		if ( $first_name ) {
			update_user_meta($user_id, 'first_name', $first_name);
		}
		
		do_action('userpro_after_profile_updated_vk');
		
	}
	
	/* Update profile picture */
	function update_profile_pic_vk($user_id, $form) {
		global $userpro;
		$userpro->do_uploads_dir( $user_id );

		if ($form['photo_big']){
			$unique_id = uniqid();
			$userpro->move_file( $user_id, $form['photo_big'], $unique_id . '.jpg' );
			update_user_meta($user_id, 'profilepicture', $userpro->get_uploads_url($user_id) . $unique_id . '.jpg' );
		}
		
	}
	
	/* Check if user is vk user */
	function is_vk_user($user_id) {
		$usermeta = get_user_meta($user_id, 'userpro_vk_id', true);
		if ($usermeta)
			return true;
		return false;
	}
	
	/* Get/show badge for VK! users */
	function userpro_get_badge($badge,$user_id=null, $tooltip=null) {
		global $userpro;
		switch($badge){
			case 'vk':
				return '<img class="userpro-profile-badge" src="'. userpro_vk_url . 'img/badge-vk.png' . '" alt="" title="'.__('VK.com Linked','userpro').'" />';
				break;
		}
	}
	
}

$userpro_vk = new userpro_vk_api();