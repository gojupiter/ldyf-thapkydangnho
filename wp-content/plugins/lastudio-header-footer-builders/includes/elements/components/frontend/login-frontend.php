<?php

function lahfb_login( $atts, $uniqid, $once_run_flag ) {

	extract( LAHFB_Helper::component_atts( array(
		'login_type'		=> 'icon',
		'login_text'		=> 'Login / Register',
		'login_text_icon'	=> '',
		'open_form'		    => 'modal',
		'show_arrow'		=> 'true',
		'show_avatar'		=> 'true',
		'show_tooltip'		=> 'false',
		'tooltip_text'		=> 'Login',
		'tooltip_position'	=> 'tooltip-on-bottom',
		'extra_class'		=> '',
	), $atts ));

	/**
     * login_type
	 * tooltip_text
	 * extra_class
	 */
    global $user_ID, $user_identity;
	$out = $modal = $wrap_class = '';

    $icon_alignment     = $login_text_icon == 'true' ? 'icon-right ' : '';
    $login_text_icon    = $login_type == 'icon_text' ? '<i class="fa fa-user-circle-o"></i>' : '';
    $login_text         = $login_text ? $login_text : '';
	
	
	// tooltip
    $tooltip_text   = ! empty( $tooltip_text ) ? $tooltip_text : '' ;
    $tooltip = $tooltip_class = '';
    if ( $show_tooltip == 'true' && $tooltip_text ) :
        
        $tooltip_position   = ( isset( $tooltip_position ) && $tooltip_position ) ? $tooltip_position : 'tooltip-on-bottom';
        $tooltip_class      = ' lahfb-tooltip ' . $tooltip_position;
        $tooltip            = ' data-tooltip=" ' . esc_attr( $tooltip_text ) . ' "';

    endif;


    if ( $user_ID ) {
        $show_avatar    = $show_avatar == 'true' ? '<span class="la-header-avatar">' . get_avatar( $user_ID, $size = '50') . '</span>' : $login_text_icon;
    }
    else {
        $show_avatar    = $login_type == 'icon' ? '<i class="fa fa-user-circle-o"></i>' : $login_text_icon;
    }
    // login
	if ( is_user_logged_in() ) {
        $login_text = $show_avatar . '<span class="lahfb-login-text-modal">' .  esc_html($user_identity).'</span>';
    } else {
    	$login_text = $show_avatar . '<span class="lahfb-login-text-modal">' . LAHFB_Helper::render_string($login_text) .'</span>' ;
    }

	// styles
	if ( $once_run_flag ) :
		$dynamic_style = '';
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Text', '#lastudio-header-builder .com_login_' . esc_attr( $uniqid ) .' .lahfb-icon-element span','#lastudio-header-builder .com_login_' . esc_attr( $uniqid ) .':hover .lahfb-icon-element span' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Icon', '#lastudio-header-builder .com_login_' . esc_attr( $uniqid ) . ' .lahfb-icon-element i', '#lastudio-header-builder .com_login_' . esc_attr( $uniqid ) . ':hover .lahfb-icon-element i'  );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Box', '#lastudio-header-builder .com_login_' . esc_attr( $uniqid ) .'' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Form', '#lastudio-header-builder .com_login_' . esc_attr( $uniqid ) .' .la-login-form' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Tooltip', '#lastudio-header-builder .com_login_' . esc_attr( $uniqid ) .'.lahfb-tooltip[data-tooltip]:before' );

        if ( $dynamic_style ) :
            LAHFB_Helper::set_dynamic_styles( $dynamic_style );
        endif;
	endif;

	// extra class
	$extra_class = $extra_class ? ' ' . $extra_class : '' ;

    if ( $open_form == 'dropdown' ) {
        $show_arrow = $show_arrow == 'true' ? 'with-arrow' : ' no-arrow';
        $wrap_class = ' login-dropdown-element lahfb-header-dropdown';
    }
    else{
        $show_arrow = '';
    }

	// render
	$out .= '<div class="lahfb-element lahfb-icon-wrap lahfb-login ' . $show_arrow . $wrap_class . esc_attr( $tooltip_class . $extra_class ) . ' com_login_'.esc_attr($uniqid).'" ' . $tooltip . ' ' . $modal . '>';

        if ( $open_form == 'modal' ) {
            $out .= '<a class="la-inline-popup lahfb-modal-element lahfb-modal-target-link" href="#lahfb_login_'.esc_attr($uniqid).'" data-component_name="la-login-popup"></a>';
        }

		$out .= '<div class="' . $icon_alignment . 'lahfb-icon-element hcolorf">';
		if ( $login_type == 'text' || $login_type == 'icon_text' ) {
			$out .=  $login_text;
		}
		else {
			$out .= $show_avatar;
		}
	    $out .= '</div>';

		if( $open_form == 'dropdown' ){
            $out .= '<a class="lahfb-trigger-element js-login_trigger_dropdown" href="#lahfb_login_'.esc_attr($uniqid).'"></a>';
        }

		if( $once_run_flag ) {
            if ( $open_form == 'modal' ) {
                $out .= '<div id="lahfb_login_'.esc_attr($uniqid).'" class="lahfb-modal-login modal-login">';
            }
            elseif ( $open_form == 'dropdown' ) {
                $out .= '<div id="lahfb_login_'.esc_attr($uniqid).'" class="lahfb-modal-login la-element-dropdown">';
            }
            ob_start();
            if ( function_exists( 'lahfb_login_form' ) ) {
                lahfb_login_form();
            }
            $out .= ob_get_contents();
            ob_end_clean();
            $out .= '</div>';
        }

	$out .= '</div>';

	return $out;

}

LAHFB_Helper::add_element( 'login', 'lahfb_login' );
