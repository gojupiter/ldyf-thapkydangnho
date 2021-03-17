<?php

function lahfb_cart( $atts, $uniqid, $once_run_flag ) {

	extract( LAHFB_Helper::component_atts( array(
		'cart_icon'         => 'dl-icon-cart4',
		'show_tooltip'	    => 'false',
        'tooltip'	        => 'Cart',
        'tooltip_position'	=> 'tooltip-on-bottom',
		'extra_class'	    => '',
	), $atts ));



    $out = '';
    if(strlen($cart_icon) < 2){
        $cart_icon = 'dl-icon-cart4';
    }

    $icon = lahfb_rename_icon($cart_icon);

    // tooltip
    $tooltip_text   = ! empty( $tooltip_text ) ? $tooltip_text : '' ;
    $tooltip = $tooltip_class = '';
    if ( $show_tooltip == 'true' && $tooltip_text ) :
        
        $tooltip_position   = ( isset( $tooltip_position ) && $tooltip_position ) ? $tooltip_position : 'tooltip-on-bottom';
        $tooltip_class      = ' lahfb-tooltip ' . $tooltip_position;
        $tooltip            = ' data-tooltip=" ' . esc_attr( LAHFB_Helper::render_string($tooltip_text) ) . ' "';

    endif;

    $cart_count = '';

    // styles
    if ( $once_run_flag ) :

        $dynamic_style = '';
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Icon', '#lastudio-header-builder .cart_' . esc_attr( $uniqid ) . ' > .la-cart-modal-icon > i', '#lastudio-header-builder .cart_' . esc_attr( $uniqid ) . ':hover > .la-cart-modal-icon i'  );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Counter', '#lastudio-header-builder .cart_' . esc_attr( $uniqid ) . ' .header-cart-count-icon' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Box', '#lastudio-header-builder .cart_' . esc_attr( $uniqid ) . '' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Tooltip', '#lastudio-header-builder .cart_' . esc_attr( $uniqid ) .'.lahfb-tooltip[data-tooltip]:before' );

        if ( $dynamic_style ) :
            LAHFB_Helper::set_dynamic_styles( $dynamic_style );
        endif;
    endif;

    // extra class
    $extra_class = $extra_class ? ' ' . $extra_class : '';

    $cart_url = '';
    if (LAHFB_Helper::is_frontend_builder()) {
        $cart_count = 0;
    } else {
        if(function_exists('WC')){
            $cart_count = !WC()->cart->is_empty() ? WC()->cart->get_cart_contents_count() : 0;
            $cart_url = wc_get_cart_url();
        }
        else{
            $cart_count = 0;
        }
    }

    // render
    $out .= '
        <div class="lahfb-element lahfb-icon-wrap lahfb-cart' . esc_attr( $tooltip_class . $extra_class ) . ' lahfb-header-woo-cart-toggle cart_'.esc_attr( $uniqid ).'"' . $tooltip . '>
            <a href="' . esc_url($cart_url) . '" class="la-cart-modal-icon lahfb-icon-element hcolorf "><span class="header-cart-count-icon colorb component-target-badget la-cart-count" data-cart_count= ' . $cart_count . ' >';
                $out .=  $cart_count;
    $out .= '</span><i class="'.$icon.'"></i>
            </a>';
    $out .= '</div>';
    return $out;

}

LAHFB_Helper::add_element( 'cart', 'lahfb_cart' );