<?php

function lahfb_button( $atts, $uniqid, $once_run_flag ) {

	extract( LAHFB_Helper::component_atts( array(
		'text'				=> 'Button',
		'link'				=> 'https://la-studioweb.com/',
		'link_new_tab'		=> 'false',
		'show_tooltip'		=> 'false',
		'tooltip_text'		=> 'Tooltip Text',
		'tooltip_position'	=> 'tooltip-on-bottom',
		'extra_class'		=> '',
	), $atts ));

	$out = $tooltip = $tooltip_class = '';

	$text 			= ! empty( $text ) ? $text : '' ;
	$link 			= ! empty( $link ) ? $link : '' ;
	$link_new_tab 	= $link_new_tab == 'true' ? 'target="_blank"' : '' ;
	
	// tooltip
	$tooltip_text	= ! empty( $tooltip_text ) ? $tooltip_text : '' ;
	$tooltip = $tooltip_class = '';
	if ( $show_tooltip == 'true' && $tooltip_text ) :
		
		$tooltip_position 	= ( isset( $tooltip_position ) && $tooltip_position ) ? $tooltip_position : 'tooltip-on-bottom';
		$tooltip_class		= ' lahfb-tooltip ' . $tooltip_position;
		$tooltip			= ' data-tooltip=" ' . esc_attr( LAHFB_Helper::render_string($tooltip_text) ) . ' "';

	endif;

	// styles
	if ( $once_run_flag ) :

		$dynamic_style = '';
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Button', '#lastudio-header-builder .button_' . esc_attr( $uniqid ) .' a' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Tooltip', '#lastudio-header-builder .button_' . esc_attr( $uniqid ) .'.lahfb-tooltip[data-tooltip]:before' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Tooltip Arrow', '#lastudio-header-builder .button_' . esc_attr( $uniqid ) .'.lahfb-tooltip[data-tooltip]:after' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Box', '#lastudio-header-builder .button_' . esc_attr( $uniqid ) );

		if ( $dynamic_style ) :
			LAHFB_Helper::set_dynamic_styles( $dynamic_style );
		endif;

	endif;

	// extra class
	$extra_class = !empty($extra_class) ? ' ' . $extra_class : '' ;

	// render
	$out .= '
		<div class="lahfb-element lahfb-button' . esc_attr( $tooltip_class . $extra_class ) . ' button_'.esc_attr( $uniqid ).'"' . $tooltip . '>
			<a href="' . LAHFB_Helper::render_string($link) . '" class="lahfb-icon-element" ' . $link_new_tab . '>
				<span class="lahfb-button-text-modal">' . LAHFB_Helper::render_string($text) . '</span>
			</a>
		</div>';

	return $out;

}

LAHFB_Helper::add_element( 'button', 'lahfb_button' );
