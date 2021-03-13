<?php

function lahfb_social( $atts, $uniqid, $once_run_flag ) {

	extract( LAHFB_Helper::component_atts( array(
		'main_social_icon'	=> 'icon',
		'main_icon_text'	=> 'Socials',
		'social_type'		=> 'simple',
		'toggle_text'		=> 'Social Network',
		'has_tooltip'		=> 'false',
		'tooltip_text'		=> 'Socials',
		'tooltip_position'	=> 'tooltip-on-bottom',
		'social_format'		=> 'icon',
		'inline'			=> 'false',
		'dash'				=> 'false',
		'social_icon_1'		=> 'facebook',
		'social_text_1'		=> 'Facebook',
		'social_url_1'		=> 'https://www.facebook.com/',
		'social_icon_2'		=> 'none',
		'social_text_2'		=> '',
		'social_url_2'		=> '',
		'social_icon_3'		=> 'none',
		'social_text_3'		=> '',
		'social_url_3'		=> '',
		'social_icon_4'		=> 'none',
		'social_text_4'		=> '',
		'social_url_4'		=> '',
		'social_icon_5'		=> 'none',
		'social_text_5'		=> '',
		'social_url_5'		=> '',
		'social_icon_6'		=> 'none',
		'social_text_6'		=> '',
		'social_url_6'		=> '',
		'social_icon_7'		=> 'none',
		'social_text_7'		=> '',
		'social_url_7'		=> '',
		'extra_class'		=> '',
		'show_tooltip'		=> 'false',
		'default_icon_bg'	=> 'false',
	), $atts ));

	$out = $link_to = '';

	// login
	$toggle_text 		= $toggle_text ? $toggle_text : '' ;
	$dash 				= $dash == 'true' ? '- ' : '';

	// tooltip
	$tooltip_text		= ! empty( $tooltip_text ) ? $tooltip_text : '' ;
	$tooltip = $tooltip_class = '';
	if ( $show_tooltip == 'true' && $tooltip_text ) :
		
		$tooltip_position 	= ( isset( $tooltip_position ) && $tooltip_position ) ? $tooltip_position : 'tooltip-on-bottom';
		$tooltip_class		= ' lahfb-tooltip ' . $tooltip_position;
		$tooltip			= ' data-tooltip=" ' . esc_attr( LAHFB_Helper::render_string($tooltip_text) ) . ' "';

	endif;

	// Get Social Icons
	$display_socials = '';
	for ($i = 1; $i < 8; $i++) {
		${"social_text_" . $i} 	= ${"social_text_" . $i} ? ${"social_text_" . $i} : '';
		${"social_url_" . $i}  	= ${"social_url_" . $i} ? ${"social_url_" . $i} : '';

		if ( ${"social_icon_" . $i} != 'none' ) {
			$display_socials .= '<div class="header-social-icons social-icon-' . $i . '">';
			if ( ! empty( ${"social_url_" . $i} ) ) {
				$display_socials .= '<a href="' . ${"social_url_" . $i} . '" target="_blank">';
			}
			$display_socials .= $dash;
			if ( $social_format != 'text' ) {

				if ( ${"social_icon_" . $i} == 'feed' ) {
					$display_socials .= '<i class="header-social-icon fa fa-' . ${"social_icon_" . $i} . '"></i>';
				} else {
					$display_socials .= '<i class="header-social-icon fa fa-' . ${"social_icon_" . $i} . '"></i>';
				}
				
			}
			if ( $social_format != 'icon' ) {
				$display_socials .= '<span class="header-social-text">' . ${"social_text_" . $i} . '</span>';
			}
			if ( ! empty( ${"social_url_" . $i} ) ) {
				$display_socials .= '</a>';
			}
			$display_socials .= '</div>';
		}
	}

	// styles
	if ( $once_run_flag ) :
		$dynamic_style = '';
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Menu Icon/Text', '#lastudio-header-builder .social_' . esc_attr( $uniqid ) . '  .la-header-social-icon i:before,#lastudio-header-builder .social_' . esc_attr( $uniqid ) . ' .la-header-social-icon span', '#lastudio-header-builder .social_' . esc_attr( $uniqid ) . ':hover .la-header-social-icon i:before,#lastudio-header-builder .social_' . esc_attr( $uniqid ) . ':hover  .la-header-social-icon span'  );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Box', '#lastudio-header-builder .social_' . esc_attr( $uniqid ). '' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Social Box', '#lastudio-header-builder .social_' . esc_attr( $uniqid ). ' .lastudio-social-icons-box,#header-social-full-wrap-'.esc_attr( $uniqid ).',.main-slide-toggle #header-social-modal' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Social Icon/Text Box', '#lastudio-header-builder .social_' . esc_attr( $uniqid ). ' .lastudio-social-icons-box .header-social-icons a, .lastudio-social-icons-box .header-social-icons a, #header-social-modal .header-social-icons a' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Social Icon', '#lastudio-header-builder .social_' . esc_attr( $uniqid ). ' .lastudio-social-icons-box .header-social-icons a i, .lastudio-social-icons-box .header-social-icons a i, #header-social-modal .header-social-icons a i','#lastudio-header-builder .social_' . esc_attr( $uniqid ). ' .lastudio-social-icons-box .header-social-icons:hover a i, .lastudio-social-icons-box .header-social-icons:hover a i, #header-social-modal .header-social-icons:hover a i' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Social Text', '#lastudio-header-builder .social_' . esc_attr( $uniqid ). ' .lastudio-social-icons-box .header-social-icons a span, .lastudio-social-icons-box .header-social-icons a span, #header-social-modal .header-social-icons a span','#lastudio-header-builder .social_' . esc_attr( $uniqid ). ' .lastudio-social-icons-box .header-social-icons:hover a span, .lastudio-social-icons-box .header-social-icons:hover a span, #header-social-modal .header-social-icons:hover a span' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Tooltip', '#lastudio-header-builder .social_' . esc_attr( $uniqid ) .'.lahfb-tooltip[data-tooltip]:before' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Full Page Social', 'body.la-social-popup.popup_id_' . esc_attr($uniqid) . ' #lightcase-case' );

        if ( $dynamic_style ) :
            LAHFB_Helper::set_dynamic_styles( $dynamic_style );
        endif;

        if ( $inline == 'true' ) :
			LAHFB_Helper::set_dynamic_styles( '#lastudio-header-builder .header-social-icons,#header-social-full-wrap-'.esc_attr( $uniqid ).' .header-social-icons { display: inline-block; }' );
		endif;

	endif;

	// extra class
	$extra_class = $extra_class ? ' ' . $extra_class : '' ;

	if ( $social_type == 'slide' ) {
		$link_to = 'href="#"';
	} elseif ( $social_type == 'full' ) {
		$link_to = 'href="#header-social-full-wrap-'.esc_attr( $uniqid ).'"';
	} elseif ( $social_type == 'dropdown' ) {
		$link_to = 'href="#"';
	}

	if ( $main_social_icon == 'text' && ! empty( $main_icon_text ) ) {
		$text_icon = '<span>' . LAHFB_Helper::render_string($main_icon_text) . '</span>';
	} else {
		$text_icon = $default_icon_bg == 'false' ? '<i class="fa fa-share-alt"></i>' : '';
	}
	
	// render
	$out .= '
	<div class="lahfb-element lahfb-icon-wrap lahfb-social lahfb-social-type-'. esc_attr($social_type) .' ' . esc_attr( $tooltip_class . $extra_class ) . ' lahfb-header-dropdown social_'.esc_attr( $uniqid ).'"' . $tooltip . '>';

	if ( $social_type != 'simple' ) {
		$out .= '<a ' . $link_to . ' class="lahfb-icon-element js-social_trigger_'. esc_attr($social_type) .' hcolorf'. ($social_type == 'full' ? ' la-inline-popup' : '') .'"'.($social_type == 'full' ? ' data-component_name="la-social-popup popup_id_'.esc_attr($uniqid) .'"' : '').'></a>
				 <div class="la-header-social-icon">' . $text_icon . '</div>';
	}

	if ( $social_type == 'simple' ) {
		$out .= '
		<div class="lastudio-social-icons-box header-social-simple-wrap">
			' . $display_socials . '
		</div>';
	}
	elseif ( $social_type == 'slide' ) {
		$out .= '
		<div class="header-social-modal-wrap">
			<div id="header-social-modal" class="la-header-social">
				<div class="header-social-content container">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<h3 class="header-social-modal-title">
							' . LAHFB_Helper::render_string($toggle_text) . '
						</h3>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<div class="socialfollow">
						' . $display_socials . '
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	elseif ( $social_type == 'full' ) {
		$out .= '
		<div id="header-social-full-wrap-'.esc_attr( $uniqid ).'" class="lastudio-social-icons-box header-social-full-wrap">
			' . $display_socials . '
		</div>';
	} elseif ( $social_type == 'dropdown' ) {
		$out .= '
		<div class="lastudio-social-icons-box header-social-dropdown-wrap">
			' . $display_socials . '
		</div>';
	}
		
		
	$out .= '</div>';
	return $out;

}

LAHFB_Helper::add_element( 'social', 'lahfb_social' );
