<?php

function lahfb_search( $atts, $uniqid, $once_run_flag ) {

	extract( LAHFB_Helper::component_atts( array(
		'type'				=> 'simple',
		'icon_type'			=> 'la',
		'search_icon'	    => 'fa fa-search',
		'placeholder_text'	=> 'Search',
		'show_tooltip'		=> 'false',
		'tooltip_text'		=> 'Search',
		'tooltip_position'	=> 'tooltip-on-bottom',
		'top_arrow'			=> 'false',
		'searchbox_icon'	=> 'false',
		'text_beside_icon'	=> '',
		'text_before_form'	=> '',
		'extra_class'		=> '',
        'is_product_search' => 'false'
	), $atts ));

	// var_dump($atts);

	$out = '';

	$extra_html = '';


	if($is_product_search == 'true'){
        $extra_html = '<input type="hidden" value="product" name="post_type" />';
    }
    $extra_html .= '<button class="search-button" type="submit"><i class="'.esc_attr($search_icon).'"></i></button>';

	// login
	$placeholder_text 	= ! empty( $placeholder_text ) ? LAHFB_Helper::render_string($placeholder_text) : esc_html__( 'Search' , 'lastudio-header-footer-builder' );
	$text_beside_icon 	= ! empty( $text_beside_icon ) ? '<span class="search-toggle-txt">' . $text_beside_icon . '</span>' : '';
	$text_beside_icon	= ( ! empty( $text_beside_icon ) && $type == 'toggle' ) ? $text_beside_icon : '';

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
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Icon', '#lastudio-header-builder .search_' . esc_attr( $uniqid ) . ' > a > i, #lastudio-header-builder .search_' . esc_attr( $uniqid ) . ' > a > i:before, #lastudio-header-builder .search_' . esc_attr( $uniqid ) . ' form .search-button', '#lastudio-header-builder .search_' . esc_attr( $uniqid ) . ':hover > a > i, #lastudio-header-builder .search_' . esc_attr( $uniqid ) . ':hover form .search-button, #lastudio-header-builder .search_' . esc_attr( $uniqid ) . ':hover a i:before'  );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Custom Text', '#lastudio-header-builder .search_' . esc_attr( $uniqid ) . ' > a > span.search-toggle-txt, #lastudio-header-builder .search_' . esc_attr( $uniqid ) . ' > a:hover > span.search-toggle-txt'  );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Background', '#lastudio-header-builder .search_' . esc_attr( $uniqid ) . '' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Box', '#lastudio-header-builder .search_' . esc_attr( $uniqid ). '' );

        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Tooltip', '#lastudio-header-builder .search_' . esc_attr( $uniqid ) . '.lahfb-tooltip[data-tooltip]:before' );

        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Search Form', '#lastudio-header-builder .search_' . esc_attr( $uniqid ). ' > .lahfb-search-form-box,.header-search-full-wrap,.main-slide-toggle #header-search-modal, .main-slide-toggle #header-search-modal .header-search-content,#lastudio-header-builder .search_' . esc_attr( $uniqid ). ' > .header-search-simple-wrap' );

        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Search Form Input', '#lastudio-header-builder .search_' . esc_attr( $uniqid ). ' > .lahfb-search-form-box .search-text-box,#lastudio-header-builder .search_' . esc_attr( $uniqid ). ' > .header-search-simple-wrap .search-text-box,.header-search-full-wrap > form input,#header-search-modal .search-field' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Full Page Search', 'body .mfp-ready.mfp-bg.full-search' );

        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Arrow', '#lastudio-header-builder .search_' . esc_attr( $uniqid ). ' > .lahfb-search-form-box:before' );

        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Search Box Icon', '#lastudio-header-builder .search_' . esc_attr( $uniqid ). ' form .search-button' );
        


		if ( $dynamic_style ) :
			LAHFB_Helper::set_dynamic_styles( $dynamic_style );
		endif;

		if ( $top_arrow == 'true' ) :
			LAHFB_Helper::set_dynamic_styles( '#lastudio-header-builder .lahfb-search-form-box:after, #lastudio-header-builder .lahfb-search-form-box:before { display: none; }' );
		endif;

		if ( $searchbox_icon == 'true' ) :
			LAHFB_Helper::set_dynamic_styles( '#lastudio-header-builder .lahfb-search form .search-button { display: none; }' );
		endif;


	endif;

	// extra class
	$extra_class = $extra_class ? ' ' . $extra_class : '' ;
	$toggle_trigger = ( $type == 'toggle' ) ? 'lahfb-icon-element-toggle' : 'lahfb-icon-element-slide' ;

	if ( $type == 'toggle') {
		$toggle_trigger = 'lahfb-icon-element-toggle js-search_trigger_toggle' ;
	}
	elseif ( $type == 'slide' ) {
		$toggle_trigger = 'lahfb-icon-element-slide js-search_trigger_slide' ;
	}
	elseif ( $type == 'full' ) {
		$toggle_trigger = 'lahfb-icon-element-full js-search_trigger_full' ;
	}
	else {
		$toggle_trigger = 'simple' ;
	}

	$search_icon_html = '<i class="'.esc_attr($search_icon).'"></i>';

    $search_form_html = '<form class="search-form" role="search" action="' . esc_url(home_url( '/' )) . '" method="get" >
            <input autocomplete="off" name="s" type="text" class="search-field" placeholder="' . $placeholder_text . '">'. $extra_html .'
        </form>';

	// render
	$out .= '
		<div class="lahfb-element lahfb-icon-wrap lahfb-search ' . esc_attr( $tooltip_class . $extra_class ) . ' lahfb-header-' . $type . ' search_'.esc_attr( $uniqid ).'"' . $tooltip . '>';

			if ( $type != 'simple' ) {
				$out .= '<a href="#" class="lahfb-icon-element ' . $toggle_trigger . ' hcolorf ">' . $search_icon_html . $text_beside_icon .'</a>';
				$out .= '';
			}
			
			if ( $type == 'toggle' ) {
			    $out .= '<div id="lahfb-search-form-box" class="lahfb-search-form-box js-contentToggle__content">'. $search_form_html .'</div>';
			}
			elseif ( $type == 'slide' ) {
				$out .= '<div class="header-search-modal-wrap">';
				if ( $once_run_flag ) {
				    $out .= '<div id="header-search-modal" class="la-header-search"><div class="header-search-content container"><div class="col-md-12">'.$search_form_html.'</div></div></div>';
                }
				$out .= '</div>';
			}
			elseif ( $type == 'simple' ) {
				$out .= '<div class="header-search-simple-wrap">'.$search_form_html.'</div>';
			}
			elseif ( $type == 'full' ) {
				$out .= '<div class="header-search-full-wrap"><p class="searchform-fly-text">'.LAHFB_Helper::render_string($text_before_form).'</p>'.$search_form_html.'</div>';
			}

	$out .= '
		</div>';

	return $out;

}

LAHFB_Helper::add_element( 'search', 'lahfb_search' );
