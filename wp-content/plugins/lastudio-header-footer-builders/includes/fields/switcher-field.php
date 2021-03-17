<?php

/**
 * Header Builder - Switcher Field.
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
 * Switcher field function.
 *
 * @since	1.0.0
 */
function lahfb_switcher( $settings ) {

	$title				= isset( $settings['title'] ) ? $settings['title'] : '' ;
	$id					= isset( $settings['id'] ) ? $settings['id'] : '' ;
	$default			= isset( $settings['default'] ) ? $settings['default'] : 'false' ;
	$uniqid				= uniqid( rand() );
	$dependency			= isset( $settings['dependency'] ) ? $settings['dependency'] : '' ;
	$data_dependency	= '';
	$dependency_class	= '';

	if ( $dependency ) :
		$dependency_class = ' lahfb-dependency';
		$data_dependency = ' data-dependency="' . esc_attr( json_encode( $dependency ) ) . '"';
	endif;

	$output = '
		<div class="lahfb-field w-col-sm-12' . esc_attr( $dependency_class ) . '"' . $data_dependency . '>
			<h5>' . $title . '</h5>
			<div class="lahfb-switcher">
				<input type="checkbox" class="lahfb-field-input lahfb-switcher-field" id="lahfb-switcher-' . esc_attr( $uniqid ) . '" data-field-name="' . esc_attr( $id ) . '" data-field-std="' . $default . '">
				<label for="lahfb-switcher-' . esc_attr( $uniqid ) . '"></label>
			</div>
		</div>
	';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}
