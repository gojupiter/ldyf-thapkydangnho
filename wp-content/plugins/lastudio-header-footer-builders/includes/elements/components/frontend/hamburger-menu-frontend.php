<?php

function lahfb_hamburger_menu( $atts, $uniqid, $once_run_flag ) {

	extract( LAHFB_Helper::component_atts( array(
		'menu'				=> '',
		'hamburger_type'	=> 'toggle',
		'hamburger_icon'	=> 'fa fa-bars',
		'hm_style'			=> 'light',
		'toggle_from'		=> 'right',
		'image_logo'		=> '',
		'socials'			=> 'true',
		'search'			=> 'true',
		'placeholder'		=> 'Search ...',
		'content'			=> 'false',
		'text_content'		=> '',
		'copyright'			=> 'Copyright',
		'extra_class'		=> '',
		'extra_class_panel' => '',
	), $atts ));

	$out = $menu_out = '';
    $dark_wrap       = ( $hm_style == 'dark' ) ? 'dark-wrap' : 'light-wrap' ;
	$menu_style		 = ( $hm_style == 'dark' ) ? 'hm-dark' : '' ;
	$copyright 		 = $copyright ? $copyright : '' ;
	$hamburger_type  = $hamburger_type ? $hamburger_type : 'toggle' ;
    $menu_list_style = ( $hamburger_type == 'toggle' ) ? 'toggle-menu' : 'full-menu';
	$placeholder	 = ! empty( $placeholder ) ? $placeholder : '' ;
	$image_logo		 = $image_logo ? wp_get_attachment_url( $image_logo ) : '' ;

	if(!empty($image_logo) && function_exists('jetpack_photon_url')){
        $image_logo = jetpack_photon_url($image_logo);
    }

	if($hamburger_icon == '4line' || $hamburger_icon == '3line'){
	    $hamburger_icon = 'fa fa-bars';
    }

    $hamburger_icon	 = ! empty( $hamburger_icon ) ? '<i class="' . lahfb_rename_icon($hamburger_icon) . '" ></i>' : '' ;

	if ( $hamburger_type == 'toggle' ){
		$toggle_from = ( $toggle_from == 'right' ) ? 'toggle-right' : 'toggle-left';
	} else {
		$toggle_from = '';
	}

    if ( ! empty( $menu ) && is_nav_menu($menu) ) {

        $menu_out = wp_nav_menu( array(
            'menu'          => $menu,
            'container'     => 'nav',
            'container_class' => 'hamburger-main',
            'menu_class'    => 'hamburger-nav ' . $menu_list_style,
            'depth'         => '5',
            'fallback_cb'   => array( 'LAHFB_Nav_Walker', 'fallback' ),
            'items_wrap'    => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'echo'          => false,
            'walker'		=> new LAHFB_Nav_Walker
        ) );
    }
    else{
        $menu_out = '<div class="lahfb-element">span>' . esc_html__( 'Your menu is empty or not selected! ', 'lastudio-header-footer-builder' ) . '<a href="https://codex.wordpress.org/Appearance_Menus_Screen" class="sf-with-ul hcolorf" target="_blank">' . esc_html__( 'How to config a menu', 'lastudio-header-footer-builder' ) . '</a></span></div>';
    }

	// styles
	if ( $once_run_flag ) :

		$dynamic_style = '';
        $dynamic_style .= lahfb_styling_tab_output($atts,
            'Hamburger Icon Color',
            '.hbgm_' . esc_attr($uniqid) . ' .hamburger-op-icon',
            '.hbgm_' . esc_attr($uniqid) . ' .hamburger-op-icon:hover');
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Hamburger Icon Box', '.lahfb-element.hbgm_' . esc_attr( $uniqid ) .' > a' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Hamburger Box', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . '' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Menu Box', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Menu Item', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li > a ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li > a','.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li:hover > a ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li:hover > a' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Current Menu Item', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current > a ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current > a','.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current:hover > a ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current:hover > a' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Current Item Shape', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current > a:after ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current > a:after','.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current:hover > a:after ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li.current:hover > a:after' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Submenu Item', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li ul li a ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li ul li a','.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li ul li:hover a ,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-nav > li ul li:hover a' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Elements Box', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-elements,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-elements' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Content', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-elements .lahmb-text-content *,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-elements .lahmb-text-content *','.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-elements .lahmb-text-content:hover *,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-elements .lahmb-text-content:hover *' );

		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Socials', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-social-icons a i,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-social-icons a i, .hamburger-menu-wrap .hamburger-social-icons .socialfollow-name a','.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-social-icons a:hover i,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-social-icons a:hover i,.la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-social-icons .socialfollow-name a:hover' );

		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Copyright', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-copyright,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-copyright', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-copyright:hover,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-copyright:hover' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Search Input', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' #hamburger-menu-search-form input[type="text"],.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' #hamburger-menu-search-form input[type="text"]' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Search Box', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' #hamburger-menu-search-form,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' #hamburger-menu-search-form' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Logo Box', '.lahfb-body .hamburger-menu-wrap-' . esc_attr( $uniqid ) . ' .hamburger-logo-image-wrap,.hamburger-type-full .la-hamburger-wrap-' . esc_attr( $uniqid ) . ' .hamburger-logo-image-wrap' );

        if ( $dynamic_style ) :
            LAHFB_Helper::set_dynamic_styles( $dynamic_style );
        endif;

	endif;

	// extra class
	$extra_class = $extra_class ? ' ' . $extra_class : '';

	// render
    $out .= '<div class="lahfb-element lahfb-icon-wrap lahfb-hamburger-menu ' . esc_attr( $extra_class ) . ' hamburger-type-' . $hamburger_type . ' ' . $dark_wrap . ' hbgm_'.esc_attr( $uniqid ).'"><a href="#" data-id="'.esc_attr( $uniqid ).'" class="la-hamburger-icon-trigger lahfb-icon-element close-button hcolorf hamburger-op-icon">'.$hamburger_icon.'</a>';
	if ( $hamburger_type == 'full' ) :
		$out .= '
		<div class="la-hamburger-wrap-' . esc_attr( $uniqid ) . ' la-hamburger-wrap la-hamuburger-bg ' . esc_attr( $menu_style ) . ' aligncenter ' . esc_attr($extra_class_panel) .'">
			<div class="hamburger-full-wrap">
			    <a href="javascript:;" class="btn-close-hamburger-menu-full"><i class="fa fa-times"></i></a>
				<div class="lahfb-hamburger-top">';
					$out .= $menu_out;
					$out .= '
				</div>
				<div class="lahfb-hamburger-bottom hamburger-elements">';
                    if ( ! empty( $image_logo ) ) {
                        $out .= '<div class="hamburger-logo-image-wrap"><img class="hamburger-logo-image" src="' . esc_url( $image_logo ) . '" alt="'. get_bloginfo('name') .'"></div>';
                    }
					if ( $content == 'true' ) {
                        ob_start();
                        echo '<div class="lahmb-text-content">' . LAHFB_Helper::remove_js_autop(LAHFB_Helper::render_string($text_content), true) . '</div>';
                        $out .= ob_get_clean();
                    }

					if ( $socials == 'true' ) {
                        ob_start();
                        echo '<div class="hamburger-social-icons">';
                        do_action('LaStudio_Builder/display_socials');
                        echo '</div>';
                        $out .= ob_get_clean();
                    }
					$out .= '
				</div>
			</div>
		</div>';
	endif;

	if ( $once_run_flag ) {
        if ($hamburger_type == 'toggle') {
            $out .= '<div class="hamburger-menu-wrap la-hamuburger-bg hamburger-menu-content ' . esc_attr($menu_style) . ' hamburger-menu-wrap-' . esc_attr($uniqid) . ' ' . $toggle_from . ' ' . esc_attr($extra_class_panel) . '">
			    <a href="javascript:;" class="btn-close-hamburger-menu"><i class="fa fa-times"></i></a>
				<div class="hamburger-menu-main">
					<div class="lahfb-hamburger-top">';
            if (!empty($image_logo)) {
                $out .= '<div class="hamburger-logo-image-wrap"><img class="hamburger-logo-image" src="' . esc_url($image_logo) . '" alt="' . get_bloginfo('name') . '"></div>';
            }

            $out .= $menu_out;

            if ($search == 'true') :
                $out .= '<form id="hamburger-menu-search-form" role="search" action="' . esc_url(home_url('/')) . '" method="get" >
								<div class="hamburger-menu-search-content">
									<input name="s" type="text" class="hamburger-search-text-box" placeholder="' . LAHFB_Helper::render_string($placeholder) . '" >
									<i class="dl-icon-search2 hamburger-menu-search-icon"></i>
								</div>
							</form>';
            endif;

            $out .= '</div>';

            $out .= '<div class="lahfb-hamburger-bottom hamburger-elements">';
            if ($content == 'true') {
                $out .= '<div class="lahmb-text-content">' . LAHFB_Helper::remove_js_autop(LAHFB_Helper::render_string($text_content), true) . '</div>';
            }

            if ($socials == 'true') {
                ob_start(); ?>
                <div class="hamburger-social-icons"><?php do_action('LaStudio_Builder/display_socials'); ?></div>
                <?php
                $out .= ob_get_contents();
                ob_end_clean();
            }

            if (!empty($copyright)) {
                $out .= '<div class="lahfb-hamburger-bottom hamburger-copyright">' . LAHFB_Helper::render_string($copyright) . '</div>';
            }
            $out .= '</div>'; // Close .hamburger-elements
            $out .= '</div>'; // Close .hamburger-menu-main

            $out .= '</div>';
        }
    }

	$out .= '</div>';

	return $out;
}

LAHFB_Helper::add_element( 'hamburger-menu', 'lahfb_hamburger_menu' );
