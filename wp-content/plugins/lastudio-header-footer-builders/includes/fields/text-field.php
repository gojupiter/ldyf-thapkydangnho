<?php

/**
 * Header Builder - Text Field.
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
 * Text field function.
 *
 * @since	1.0.0
 */
function lahfb_textfield( $settings ) {

	$title		 = isset( $settings['title'] ) ? $settings['title'] : '';
	$id			 = isset( $settings['id'] ) ? $settings['id'] : '';
	$default	 = isset( $settings['default'] ) ? $settings['default'] : '';
	$placeholder = isset( $settings['placeholder'] ) ? ' lahfb-placeholder lahfb-text-placeholder' : '';

	$output = '
		<div class="lahfb-field w-col-sm-12' . esc_attr( $placeholder ) . '">
			<h5>' . $title . '</h5>
			<input type="text" class="lahfb-field-input lahfb-field-textfield' . esc_attr( $placeholder ) . '" data-field-name="' . esc_attr( $id ) . '" data-field-std="' . $default . '">
		</div>
	';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}
