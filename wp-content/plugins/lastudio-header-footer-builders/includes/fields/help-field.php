<?php

/**
 * Header Builder - Help Field.
 *
 * @author	LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

/**
 * Help field function.
 *
 * @since	1.0.0
 */
function lahfb_help( $settings ) {

	$title		 = isset( $settings['title'] ) ? $settings['title'] : '';
	$id			 = isset( $settings['id'] ) ? $settings['id'] : '';
	$default	 = isset( $settings['default'] ) ? $settings['default'] : '';

	$output = '
		<div class="lahfb-field w-col-sm-12">
			<h5>' . $title . '</h5>
			<div>' . $default . '</div>
		</div>
	';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}
