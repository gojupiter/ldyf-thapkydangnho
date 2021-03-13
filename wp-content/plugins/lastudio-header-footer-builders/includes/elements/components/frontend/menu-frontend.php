<?php
function lahfb_menu_f( $atts, $uniqid, $once_run_flag ) {

	extract( LAHFB_Helper::component_atts( array(
		'menu'						=> '',
		'desc_item'					=> 'false',
		'full_menu'					=> 'false',
		'height_100'				=> 'false',
		'extra_class'				=> '',
		'show_mobile_menu'			=> 'true',
		'mobile_menu_display_width' => '',
		'show_parent_arrow'			=> '',
		'parent_arrow_direction'	=> '',
		'show_megamenu'	            => 'false',
        'hamburger_icon'            => '',
        'screen_view_index'         => ''
	), $atts ));

	$out = $parent_arrow = '';

	$desc_item = $desc_item == 'true' ? ' has-desc-item' : '';
	$full_menu = $full_menu == 'true' ? ' full-width-menu' : '';
	$show_mobile_menu_class = $show_mobile_menu == 'false' ? ' la-hide-mobile-menu' : '';

	if(empty($hamburger_icon) || $hamburger_icon == 'none'){
        $hamburger_icon = 'fa fa-bars';
    }

    $hamburger_icon	 = ! empty( $hamburger_icon ) ? '<i class="' . lahfb_rename_icon($hamburger_icon) . '" ></i>' : '' ;


	if( filter_var($show_megamenu, FILTER_VALIDATE_BOOLEAN) ) {
        $desc_item .= ' has-megamenu';
    }

	if ( filter_var($show_parent_arrow, FILTER_VALIDATE_BOOLEAN) ) {
		$parent_arrow = ' has-parent-arrow';

		switch ( $parent_arrow_direction ) {
			case 'top':
				$parent_arrow .= ' arrow-top';
				break;
			case 'right':
				$parent_arrow .= ' arrow-right';
				break;
			case 'bottom':
				$parent_arrow .= ' arrow-bottom';
				break;
			case 'left':
				$parent_arrow .= ' arrow-left';
				break;
		}
	}

	if ( $once_run_flag ) :
		if ( (! empty( $menu ) && is_nav_menu( $menu )) || ( $menu == 'default_menu' ) ) {
			$menu_out = wp_nav_menu( array(
				'menu'			=> $menu,
				'container'		=> false,
				'depth'			=> '5',
                'fallback_cb'   => array( 'LAHFB_Nav_Walker', 'fallback' ),
				'items_wrap'	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'echo'			=> false,
                'show_megamenu' => $show_megamenu,
                'walker'		=> new LAHFB_Nav_Walker()
			));

			if ( $show_mobile_menu == 'true' ) {
				$responsive_menu_out = wp_nav_menu( array(
					'menu'			=> $menu,
					'container'		=> false,
					'menu_class'    => 'responav menu',
					'depth'			=> '5',
                    'fallback_cb'   => array( 'LAHFB_Nav_Walker', 'fallback' ),
					'items_wrap'	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'echo'			=> false,
					'walker'		=> new LAHFB_Nav_Walker()
				));
			}
		}
		else {
			$menu_out = '
				<div class="lahfb-element">
					<span>' . esc_html__( 'Your menu is empty or not selected! ', 'lastudio-header-footer-builder' ) . '<a href="https://codex.wordpress.org/Appearance_Menus_Screen" class="sf-with-ul hcolorf" target="_blank">' . esc_html__( 'How to config a menu', 'lastudio-header-footer-builder' ) . '</a></span>
				</div>
			';

			$responsive_menu_out = $show_mobile_menu == 'true' ? $menu_out : '';
		}

		// styles
		// if ( $mobile_menu_display_width ) :
		// 	LAHFB_Helper::set_dynamic_styles( '@media only screen and ( max-width: ' . $mobile_menu_display_width . ' ) {' . $class . ' { ' . $mobile_style  . '} }' );
		// endif;

		$dynamic_style = '';
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Menu Item', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' > ul > li.menu-item > a,.lahfb-responsive-menu-' . esc_attr( $uniqid ) . ' .responav li.menu-item > a:not(.button)','.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' > ul > li:hover > a,.lahfb-responsive-menu-' . esc_attr( $uniqid ) . ' .responav li.menu-item:hover > a:not(.button)' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Current Menu Item', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu > li.menu-item.current > a, .lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu > li.menu-item > a.active, .lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu ul.sub-menu li.current > a,.lahfb-responsive-menu-' . esc_attr( $uniqid ) . ' .responav li.current-menu-item > a:not(.button)' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Current Item Shape', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu > li.menu-item.current > a:after','.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu > li.current:hover > a:after' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Parent Menu Arrow', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . '.has-parent-arrow > ul > li.menu-item-has-children:before,.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . '.has-parent-arrow > ul > li.mega > a:before' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Menu Icon', '.lahfb-wrap .lahfb-responsive-menu-' . esc_attr( $uniqid ) . ' .responav > li > a > .la-menu-icon, .lahfb-wrap .lahfb-responsive-menu-' . esc_attr( $uniqid ) . ' .responav > li:hover > a > .la-menu-icon, .lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu > li > a .la-menu-icon', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu > li > a:hover .la-menu-icon' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Submenu Menu Icon', '.lahfb-wrap .lahfb-responsive-menu-' . esc_attr( $uniqid ) . ' .responav > li > ul.sub-menu a > .la-menu-icon, .lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu .sub-menu .la-menu-icon', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu .sub-menu li a:hover .la-menu-icon' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Menu Description', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .la-menu-desc' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Menu Badge', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu a span.menu-item-badge' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Submenu Item', '.lahfb-nav-wrap.nav__wrap_' . esc_attr( $uniqid ) . ' .menu ul li.menu-item a' );
		$dynamic_style .= lahfb_styling_tab_output( $atts, 'Submenu Currnet Item', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu ul.sub-menu li.current > a' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Submenu Box', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu > li:not(.mega) ul' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Box', '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ',.nav__res_hm_icon_' . esc_attr( $uniqid ) );

        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Responsive Menu Box', '.lahfb-responsive-menu-' . esc_attr( $uniqid ) );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Responsive Hamburger Icon', '.nav__res_hm_icon_' . esc_attr( $uniqid ) . ' a' );

        if ( $dynamic_style ) {
            LAHFB_Helper::set_dynamic_styles( $dynamic_style );
        }

        if( filter_var($height_100, FILTER_VALIDATE_BOOLEAN) ) {
        	LAHFB_Helper::set_dynamic_styles( '.lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ', .lahfb-wrap .nav__wrap_' . esc_attr( $uniqid ) . ' .menu, .nav__wrap_'.esc_attr( $uniqid ).' .menu > li, .nav__wrap_'.esc_attr( $uniqid ).' .menu > li > a { height: 100%; }' );
        }

	endif;

	// extra class
	$extra_class = $extra_class ? ' ' . $extra_class : '' ;

	// render
	if ( $show_mobile_menu == 'true' ) {
		if ( $once_run_flag ) {
			// responsive menu
			$out .= '
				<div class="lahfb-responsive-menu-wrap lahfb-responsive-menu-' . esc_attr( $uniqid ) . '" data-uniqid="' . esc_attr( $uniqid ) . '">
					<div class="close-responsive-nav">
						<div class="lahfb-menu-cross-icon"></div>
					</div>
					' . $responsive_menu_out . '
				</div>';

			// normal menu
			$out .= '<nav class="lahfb-element lahfb-nav-wrap' . esc_attr( $extra_class ) .  $desc_item . $parent_arrow . $full_menu . $show_mobile_menu_class . ' nav__wrap_'.esc_attr( $uniqid ).'" data-uniqid="' . esc_attr( $uniqid ) . '">' . $menu_out . '</nav>';
		}
		else {
			$out .= '<div class="lahfb-element lahfb-responsive-menu-icon-wrap nav__res_hm_icon_'.esc_attr( $uniqid ).'" data-uniqid="' . esc_attr( $uniqid ) . '"><a href="#">'.$hamburger_icon.'</a></div>';
		}
	}
	else {
		$menu_out = wp_nav_menu( array(
			'menu'			=> $menu,
			'container'		=> false,
			'depth'			=> '5',
            'fallback_cb'   => array( 'LAHFB_Nav_Walker', 'fallback' ),
			'items_wrap'	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'echo'			=> false,
            'show_megamenu' => $show_megamenu,
            'walker'		=> new LAHFB_Nav_Walker()
		));
		// normal menu
		$out .= '<nav class="lahfb-element lahfb-nav-wrap' . esc_attr( $extra_class ) .  $desc_item . $parent_arrow . $full_menu . $show_mobile_menu_class . ' nav__wrap_'.esc_attr( $uniqid ).'" data-uniqid="' . esc_attr( $uniqid ) . '">' . $menu_out . '</nav>';
	}

	return $out;
}

LAHFB_Helper::add_element( 'menu', 'lahfb_menu_f' );