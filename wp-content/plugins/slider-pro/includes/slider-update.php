<?php

define('SLIDERPRO_UPDATE_API', 'http://api.bqworks.com/slider-pro/legacy-api/');

// check if notifications are enabled
add_filter('pre_set_site_transient_update_plugins', 'sliderpro_update_check');
add_filter('plugins_api', 'sliderpro_update_info', 10, 3);
add_action('in_plugin_update_message-slider-pro/slider-pro.php', 'sliderpro_update_notification_message');


/**
* When the update cycle runs, if there is any slider update available append its information
*/
function sliderpro_update_check($transient) {
	if (empty($transient->checked))
		return $transient;
	
	$slug = 'slider-pro/slider-pro.php';
	$current_version = $transient->checked[$slug];
	$purchase_code = get_option( 'slider_pro_purchase_code', '' );
	$purchase_code_status = get_option( 'slider_pro_purchase_code_status', '0' );
	
	$args = array(
		'action' => 'update-check',
		'slug' => $slug,
		'purchase_code' => $purchase_code,
		'purchase_code_status' => $purchase_code_status
	);

	$response = sliderpro_api_request($args);
	
	if ($response !== false && version_compare($current_version, $response->new_version, '<'))		
		$transient->response[$slug] = $response;
	
	return $transient;
}


/**
* Display the information about the slider
*/
function sliderpro_update_info($false, $action, $args) {
	$slug = 'slider-pro/slider-pro.php';
	
	// return if the slider-pro plugin info is not requested
	if (!isset($args->slug) || $args->slug != $slug)
		return $false;
	
	$purchase_code = get_option( 'slider_pro_purchase_code', '' );
	$purchase_code_status = get_option( 'slider_pro_purchase_code_status', '0' );

	$args = array(
		'action' => 'plugin-info',
		'slug' => $slug,
		'purchase_code' => $purchase_code,
		'purchase_code_status' => $purchase_code_status
	);

	$response = sliderpro_api_request($args);
	
	if ($response !== false)		
		return $response;
	else
		return $false;

}


/**
* Display the update notification message
* Appends a custom message, if any, to the default message
*/
function sliderpro_update_notification_message() {
	$message = get_transient('slider_pro_update_notification_message');
	$purchase_code_status = get_option( 'slider_pro_purchase_code_status', '0' );

	// if the message has expired, interrogate the server
	if (!$message) {
		$args = array(
			'action' => 'notification-message',
			'slug' => 'slider-pro/slider-pro.php'
		);
		
		$response = sliderpro_api_request($args);
		
		if ($response !== false) {
			$message .= $response->notification_message;
			
			// store the message in a transient for 12 hours
			set_transient('slider_pro_update_notification_message', $message, 60 * 60 * 12);
		}
	}

	if ( $purchase_code_status !== '1' ) {
		$message = 
			__( ' To activate automatic updates, you need to enter your purchase code ', 'slider_pro' ) . 
			'<a href="' . admin_url( 'admin.php?page=slider_pro_plugin_options' ) . '">' . 
				__( 'here', 'slider_pro' ) . 
			'</a>.<br/> ' . 
			$message;
	}
	
	echo $message;
}

function sliderpro_verify_purchase_code( $purchase_code ) {
	$purchase_code = get_option( 'slider_pro_purchase_code', '' );

	$args = array(
		'action' => 'verify-purchase',
		'slug' => 'slider-pro/slider-pro.php',
		'purchase_code' => $purchase_code
	);

	$response = sliderpro_api_request( $args );

	delete_site_transient( 'update_plugins' );
	delete_transient( 'slider_pro_update_notification_message' );

	if ( $response !== false && isset( $response->is_valid ) ) {
		if ( $response->is_valid === 'yes' ) {
			return 'yes';
		} else {
			return 'no';
		}
	} else {
		return 'error';
	}
}


/**
* Makes requests to the server's update API
*/
function sliderpro_api_request($args) {
	$request = wp_remote_post(SLIDERPRO_UPDATE_API, array('body' => $args));
	
	if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200)
		return false;
	
	$response = unserialize(wp_remote_retrieve_body($request));
	
	if (is_object($response))
		return $response;
	else
		return false;
}


?>