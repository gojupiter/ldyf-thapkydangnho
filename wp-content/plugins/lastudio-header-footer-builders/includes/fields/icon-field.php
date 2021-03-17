<?php

/**
 * Header Builder - Icon Field.
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
 * Icon field function.
 *
 * @since	1.0.0
 */
function lahfb_icon( $settings ) {

	$title		= isset( $settings['title'] ) ? $settings['title'] : '';
	$id			= isset( $settings['id'] ) ? $settings['id'] : '';
	$default	= isset( $settings['default'] ) ? $settings['default'] : '';
	$icon_collection	= isset( $settings['icon_collection'] ) ? $settings['icon_collection'] : '';
	// get icons
	$icons		= lahfb_icons($icon_collection);

	$output = '
		<div class="lahfb-field lahfb-field-icons-wrap w-col-sm-12">
			<h5>' . $title . '</h5>
			<input type="hidden" class="lahfb-field-input lahfb-icon-field" data-field-name="' . esc_attr( $id ) . '" data-field-std="' . $default . '">
			' . $icons . '
		</div>';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}
