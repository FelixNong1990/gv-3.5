<?php

	/* Registers and display the shortcode */
	add_shortcode('userpro_bookmarklist', 'userpro_bookmarklist' );
	function userpro_bookmarklist( $args=array() ) {
		global $userpro_fav;

		/* arguments */
		$defaults = array(

		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		
		return $userpro_fav->bookmarks( $args );
	
	}