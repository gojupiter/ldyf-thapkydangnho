<?php

/**
 * Header Builder - Image Field.
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
 * Image field function.
 *
 * @since	1.0.0
 */
function lahfb_image( $settings ) {

	$title		 = isset( $settings['title'] ) ? $settings['title'] : '';
	$id			 = isset( $settings['id'] ) ? $settings['id'] : '';
	$placeholder = isset( $settings['placeholder'] ) ? ' lahfb-placeholder lahfb-img-placeholder' : '';

	$output = '
		<div class="lahfb-field w-col-sm-12' . esc_attr( $placeholder ) . '">
			<h5>' . $title . '</h5>
			<div class="lahfb-attach-image">
				<input type="hidden" class="lahfb-field-input lahfb-attach-image' . esc_attr( $placeholder ) . '" data-field-name="' . esc_attr( $id ) . '">
				<span class="lahfb-preview-image"></span>
				<button type="button" class="lahfb-add-image">' . esc_html__( 'Upload', 'lastudio-header-footer-builder' ) . '</button>
				<button type="button" class="lahfb-remove-image">' . esc_html__( 'Remove', 'lastudio-header-footer-builder' ) . '</button>
			</div>
		</div>
	';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}
