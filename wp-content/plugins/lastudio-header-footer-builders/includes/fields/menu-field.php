<?php

/**
 * Header Builder - Menu Field.
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
 * Menu field function.
 *
 * @since	1.0.0
 */
function lahfb_menu( $settings ) {

	$title	= isset( $settings['title'] ) ? $settings['title'] : '';
	$id		= isset( $settings['id'] ) ? $settings['id'] : '';
	$menus	= wp_get_nav_menus();
	$option = '';

	if ( ! empty( $menus ) ) :
		$option .= '<select class="lahfb-field-input lahfb-field-select lahfb-field-menu" data-field-name="' . esc_attr( $id ) . '">';
		foreach ( $menus as $item ) :
			$option .= '<option value="' . esc_attr( $item->term_id ) . '">' . esc_html( $item->name ) . '</option>';
		endforeach;
		$option .= '</select>';
	else :
		$option .= '<span class="lahfb-field-input lahfb-field-select lahfb-field-menu" data-field-name="' . esc_attr( $id ) . '">' . esc_html__( 'No items of this type were found.', 'lastudio-header-footer-builder' ) . '</span>';
	endif;

	$output = '
		<div class="lahfb-field w-col-sm-12">
			<h5>' . $title . '</h5>
			<div class="lahfb-dropdown">
				' . $option . '
			</div>
		</div>
	';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}
