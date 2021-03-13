<?php

/**
 * Header Builder - Colorpicker Field.
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
 * Colorpicker field function.
 *
 * @since	1.0.0
 */
function lahfb_colorpicker( $settings ) {

	$title		= isset( $settings['title'] ) ? $settings['title'] : '';
	$id			= isset( $settings['id'] ) ? $settings['id'] : '';
	$default	= isset( $settings['default'] ) ? $settings['default'] : '';

	$output = '
		<div class="lahfb-field w-col-sm-6">
			<h5>' . $title . '</h5>
			<input type="text" class="lahfb-field-input lahfb-color-picker jscolor" data-field-name="' . esc_attr( $id ) . '" data-field-std="' . $default . '" data-default-color="' . $default . '" data-alpha="true">
        </div>
    ';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}
