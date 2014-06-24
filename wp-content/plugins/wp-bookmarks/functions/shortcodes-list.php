<?php

	/* Registers and display the shortcode */
	add_shortcode('collections', 'collections' );
	function collections( $args=array() ) {
		global $wpb;

		/* arguments */
		$defaults = array(

		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		
		return $wpb->bookmarks( $args );
	
	}