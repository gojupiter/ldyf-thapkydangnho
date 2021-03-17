<?php

function lahfb_styling_tab( $params = array() ) {

	if ( ! $params ) return;

	$screens = array(
		'all'	=> array(
			'list'	=> 'all_list_items',
			'panel'	=> 'all_panel_items',
		),
		'tablets'	=> array(
			'list'	=> 'tablets_list_items',
			'panel'	=> 'tablets_panel_items',
		),
		'mobiles'	=> array(
			'list'	=> 'mobiles_list_items',
			'panel'	=> 'mobiles_panel_items',
		),
	);

	// $list_items = $panel_items = '';

	foreach ( $screens as $screen => $vars ) :

		${$vars['list']} = ${$vars['panel']} = '';

		foreach ( $params as $el_title => $el_partials ) :

			$el_href = str_replace( '-', '_', sanitize_title( $el_title ) );

			${$vars['list']} .= '
				<li class="lahfb-tab">
					<a href="#' . $el_href . '">
						<span>' . esc_html( $el_title ) . '</span>
					</a>
				</li>
			';

			${$vars['panel']} .= '<div class="lahfb-tab-panel lahfb-styling-group-panel" data-id="#' . $el_href . '">';
				foreach ( $el_partials as $el_atts ) :
					switch ( $el_atts['property'] ) :

                        // typography
                        case 'row_layout':
                            ${$vars['panel']} .= lahfb_select( array(
                                'title'			=> esc_html__( 'Row Layout', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
                                'default'       => 'auto',
                                'options'		=> array(
                                    'auto'	    => esc_html__( 'Auto', 'lastudio-header-footer-builder' ),
                                    '4-4-4'	    => esc_html__( '4/12 - 4/12 - 4/12', 'lastudio-header-footer-builder' ),
                                    '3-6-3'	    => esc_html__( '3/12 - 6/12 - 3/12', 'lastudio-header-footer-builder' ),
                                    '2-8-2'	    => esc_html__( '2/12 - 8/12 - 2/12', 'lastudio-header-footer-builder' ),
                                    '5-2-5'	    => esc_html__( '5/12 - 2/12 - 5/12', 'lastudio-header-footer-builder' ),
                                    '1-10-1'	=> esc_html__( '1/12 - 10/12 - 1/12', 'lastudio-header-footer-builder' ),
                                    '2-6-2'	    => esc_html__( '20% - 60% - 20%', 'lastudio-header-footer-builder' ),
                                    '25-5-25'	=> esc_html__( '25% - 50% - 25%', 'lastudio-header-footer-builder' ),
                                    '3-4-3'	    => esc_html__( '30% - 40% - 30%', 'lastudio-header-footer-builder' ),
                                    '35-3-35'	=> esc_html__( '35% - 30% - 35%', 'lastudio-header-footer-builder' ),
                                    '4-2-4'	    => esc_html__( '40% - 20% - 40%', 'lastudio-header-footer-builder' ),
                                    '45-1-45'	=> esc_html__( '45% - 10% - 45%', 'lastudio-header-footer-builder' ),
                                    '1-8-1'	    => esc_html__( '10% - 80% - 10%', 'lastudio-header-footer-builder' )
                                ),
                                'get'			=> true,
                            ));
                            break;

						// typography
						case 'color':
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color', 'lastudio-header-footer-builder' ),
								'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'get'			=> true,
							));
						break;

						case 'color_hover':
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color Hover', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'get'			=> true,
							));
						break;

						case 'fill':
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'get'			=> true,
							));
						break;

						case 'fill_hover':
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color Hover', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'get'			=> true,
							));
						break;

						case 'font_size':
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Font Size', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
						break;

						case 'font_weight':
							${$vars['panel']} .= lahfb_custom_select( array(
								'title'			=> esc_html__( 'Font Weight', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'300'	=> '300',
									'400'	=> '400',
									'500'	=> '500',
									'600'	=> '600',
									'700'	=> '700',
									'800'	=> '800',
									'900'	=> '900',
									''		=> '<i class="fa fa-ban"></i>',
								),
								// 'default'	=> '',
								'get'			=> true,
							));
						break;

						case 'font_style':
							${$vars['panel']} .= lahfb_custom_select( array(
								'title'			=> esc_html__( 'Font Style', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'normal' => '<i class="fa fa-ban"></i>',
									'italic' => '<span style="font-style:italic;font-family: serif;">T</span>',
								),
								'get'			=> true,
							));
						break;

						case 'text_align':
							${$vars['panel']} .= lahfb_custom_select( array(
								'title'			=> esc_html__( 'Text Align', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									''		  => '<i class="fa fa-ban"></i>',
									'left'	  => '<i class="fa fa-align-left"></i>',
									'center'  => '<i class="fa fa-align-center"></i>',
									'right'	  => '<i class="fa fa-align-right"></i>',
									'justify' => '<i class="fa fa-align-justify"></i>',
								),
								'get'			=> true,
							));
						break;

						case 'text_transform':
							${$vars['panel']} .= lahfb_custom_select( array(
								'title'			=> esc_html__( 'Text Transform', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'none'			=> '<i class="fa fa-ban"></i>',
									'uppercase'		=> 'TT',
									'capitalize'	=> 'Tt',
									'lowercase'		=> 'tt',
								),
								'get'			=> true,
							));
						break;

						case 'text_decoration':
							${$vars['panel']} .= lahfb_custom_select( array(
								'title'			=> esc_html__( 'Text Decoration', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'none'			=> '<i class="fa fa-ban"></i>',
									'underline'		=> '<u>T</u>',
									'line-through'	=> '<span style="text-decoration: line-through">T</span>',
								),
								'get'			=> true,
							));
						break;

						case 'width':
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Width', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
						break;

						case 'height':
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Height', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
						break;

						case 'line_height':
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Line Height', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
						break;

						case 'letter_spacing':
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Letter Spacing', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
						break;

						case 'overflow':
							${$vars['panel']} .= lahfb_select( array(
								'title'			=> esc_html__( 'Overflow', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									''	  	  => '',
									'auto'	  => esc_html__( 'Auto', 'lastudio-header-footer-builder' ),
									'hidden'  => esc_html__( 'Hidden', 'lastudio-header-footer-builder' ),
									'inherit' => esc_html__( 'Inherit', 'lastudio-header-footer-builder' ),
									'initial' => esc_html__( 'Initial', 'lastudio-header-footer-builder' ),
									'overlay' => esc_html__( 'Overlay', 'lastudio-header-footer-builder' ),
									'visible' => esc_html__( 'Visible', 'lastudio-header-footer-builder' ),
								),
								'get'			=> true,
							));
						break;

						case 'word_break':
							${$vars['panel']} .= lahfb_select( array(
								'title'			=> esc_html__( 'Word Break', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									''	  			=> '',
									'break-all'		=> esc_html__( 'Break All', 'lastudio-header-footer-builder' ),
									'break-word'	=> esc_html__( 'Break Word', 'lastudio-header-footer-builder' ),
									'inherit'		=> esc_html__( 'Inherit', 'lastudio-header-footer-builder' ),
									'initial'		=> esc_html__( 'Initial', 'lastudio-header-footer-builder' ),
									'normal'		=> esc_html__( 'Normal', 'lastudio-header-footer-builder' ),
								),
								'get'			=> true,
							));
						break;

						// background
						case 'background_color':
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Background Color', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'get'			=> true,
							));
						break;

						case 'background_color_hover':
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Background Hover Color', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'get'			=> true,
							));
						break;

						case 'background_image':
							${$vars['panel']} .= lahfb_image( array(
								'title'			=> esc_html__( 'Background Image', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'get'			=> true,
							));
						break;

						case 'background_position':
							${$vars['panel']} .= lahfb_select( array(
								'title'			=> esc_html__( 'Background Position', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'left top'		=> esc_html__( 'Left Top', 'lastudio-header-footer-builder' ),
									'left center'	=> esc_html__( 'Left Center', 'lastudio-header-footer-builder' ),
									'left bottom'	=> esc_html__( 'Left Bottom', 'lastudio-header-footer-builder' ),
									'center top'	=> esc_html__( 'Center Top', 'lastudio-header-footer-builder' ),
									'center center'	=> esc_html__( 'Center Center', 'lastudio-header-footer-builder' ),
									'center bottom'	=> esc_html__( 'Center Bottom', 'lastudio-header-footer-builder' ),
									'right top'		=> esc_html__( 'Right Top', 'lastudio-header-footer-builder' ),
									'right center'	=> esc_html__( 'Right Center', 'lastudio-header-footer-builder' ),
									'right bottom'	=> esc_html__( 'Right Bottom', 'lastudio-header-footer-builder' ),
								),
								'default'		=> 'center center',
								'get'			=> true,
							));
						break;

						case 'background_repeat':
							${$vars['panel']} .= lahfb_select( array(
								'title'			=> esc_html__( 'Background Repeat', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'options'		=> array(
									'repeat'	=> esc_html__( 'Repeat'	, 'lastudio-header-footer-builder' ),
									'repeat-x'	=> esc_html__( 'Repeat x', 'lastudio-header-footer-builder' ),
									'repeat-y'	=> esc_html__( 'Repeat y', 'lastudio-header-footer-builder' ),
									'no-repeat'	=> esc_html__( 'No Repeat', 'lastudio-header-footer-builder' ),
								),
								'default'		=> 'no-repeat',
								'get'			=> true,
							));
						break;

						case 'background_cover':
							${$vars['panel']} .= lahfb_switcher( array(
								'title'			=> esc_html__( 'Background Cover ?', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'default'		=> 'true',
								'get'			=> true,
							));
						break;

						// box
						case 'margin':
							${$vars['panel']} .= '<div class="lahfb-field lahfb-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Margin', 'lastudio-header-footer-builder' ) . '</h5>';
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
						break;

						case 'padding':
							${$vars['panel']} .= '<div class="lahfb-field lahfb-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Padding', 'lastudio-header-footer-builder' ) . '</h5>';
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
                                'get'			=> true,
                            ));
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
                                'get'			=> true,
                            ));
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
                                'get'			=> true,
                            ));
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
                                'get'			=> true,
                            ));
							${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
						break;

						case 'border_radius':
							${$vars['panel']} .= '<div class="lahfb-field lahfb-box-wrap lahfb-box-border-radius-wrap w-col-sm-12"><h5>' . esc_html__( 'Border Radius', 'lastudio-header-footer-builder' ) . '</h5>';
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name('top_left_radius', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name('top_right_radius', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name('bottom_right_radius', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name('bottom_left_radius', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
						break;

						case 'border':
							${$vars['panel']} .= lahfb_select( array(
								'title'			=> esc_html__( 'Border Style', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_style', $screen, $el_href),
								'options'		=> array(
									''			=> '',
									'none'		=> esc_html__( 'None', 'lastudio-header-footer-builder' ),
									'solid'		=> esc_html__( 'Solid', 'lastudio-header-footer-builder' ),
									'dotted'	=> esc_html__( 'Dotted', 'lastudio-header-footer-builder' ),
									'dashed'	=> esc_html__( 'Dashed', 'lastudio-header-footer-builder' ),
									'double'	=> esc_html__( 'Double', 'lastudio-header-footer-builder' ),
									'groove'	=> esc_html__( 'Groove', 'lastudio-header-footer-builder' ),
									'ridge'		=> esc_html__( 'Ridge', 'lastudio-header-footer-builder' ),
									'inset'		=> esc_html__( 'Inset', 'lastudio-header-footer-builder' ),
									'outset'	=> esc_html__( 'Outset', 'lastudio-header-footer-builder' ),
									'initial'	=> esc_html__( 'Initial', 'lastudio-header-footer-builder' ),
									'inherit'	=> esc_html__( 'Inherit', 'lastudio-header-footer-builder' ),
								),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Border Color', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_color', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= '<div class="lahfb-field lahfb-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Border Width', 'lastudio-header-footer-builder' ) . '</h5>';
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
						break;

						case 'float':
							${$vars['panel']} .= lahfb_custom_select( array(
								'title'			=> esc_html__( 'Floating', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'default'		=> 'left',
								'options'		=> array(
									'left'	=> 'left',
									'right'	=> 'right',
								),
								'get'			=> true,
							));
						break;

						case 'position_property':
							${$vars['panel']} .= lahfb_custom_select( array(
								'title'			=> esc_html__( 'Position', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'], $screen, $el_href),
								'default'		=> 'static',
								'options'		=> array(
									'static'	=> 'static',
									'absolute'	=> 'absolute',
									'relative'	=> 'relative',
								),
								'get'			=> true,
							));
						break;

						case 'position':
							${$vars['panel']} .= '<div class="lahfb-field lahfb-box-wrap w-col-sm-6"><h5>' . esc_html__( 'Position', 'lastudio-header-footer-builder' ) . '</h5>';
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
                                'get'			=> true,
                            ));
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
                                'get'			=> true,
                            ));
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
                                'get'			=> true,
                            ));
                            ${$vars['panel']} .= lahfb_textfield( array(
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
                                'get'			=> true,
                            ));
							${$vars['panel']} .= '</div>';
							${$vars['panel']} .= '<div class="lahfb-field lahfb-help-content-wrap w-col-sm-12">';
							${$vars['panel']} .= lahfb_help( array(
								'title'			=> esc_html__( 'Help to use calc', 'lastudio-header-footer-builder' ),
								'id'			=> $el_atts['property'] . '_help_calc_' . $screen . '_el_' . $el_href,
								'default'		=> '
									To make this element stay center, all you need is using calc code as following:<br>
									calc(50% - half width)<br>
									For Example:<br>
									Width = 40px<br>
									Left = calc(50% - 20px)
								',
								'get'			=> true,
							));
							${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
						break;

						case 'box_shadow':
							${$vars['panel']} .= '<div class="lahfb-field lahfb-shadow-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Box Shadow', 'lastudio-header-footer-builder' ) . '</h5>';
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'X offset', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_xoffset', $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Y offset', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_yoffset', $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Blur', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_blur', $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Spread', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_spread', $screen, $el_href),
								'options'		=> array(
									'px'	=> 'px',
									'em'	=> 'em',
									'%'		=> '%',
								),
								'default_unit'	=> 'px',
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_switcher( array(
								'title'			=> esc_html__( 'Inset Shadow Status', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_status', $screen, $el_href),
								'default'       => 'false',
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Shadow Color', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_color', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
						break;

						case 'gradient':
							${$vars['panel']} .= '<div class="lahfb-field lahfb-gradient-wrap w-col-sm-12"><h5>' . esc_html__( 'Gradient', 'lastudio-header-footer-builder' ) . '</h5>';
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color 1', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_color1', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color 2', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_color2', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color 3', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_color3', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_colorpicker( array(
								'title'			=> esc_html__( 'Color 4', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_color4', $screen, $el_href),
								'get'			=> true,
							));
							${$vars['panel']} .= lahfb_number_unit( array(
								'title'			=> esc_html__( 'Direction', 'lastudio-header-footer-builder' ),
                                'id'			=> lahfb_pretty_property_name($el_atts['property'] . '_direction', $screen, $el_href),
								'options'		=> array(
									'deg'	=> 'deg',
								),
								'default_unit'	=> 'deg',
								'get'			=> true,
							));
							${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
						break;

					endswitch;
				endforeach;
			${$vars['panel']} .= '</div>';

		endforeach;

	endforeach;

	?>

	<ul class="lahfb-tabs-list lahfb-styling-screens wp-clearfix">
		<li class="lahfb-tab">
			<a href="#all">
				<i class="fa fa-desktop"></i>
				<span><?php esc_html_e( 'Desktop', 'lastudio-header-footer-builder' ); ?></span>
			</a>
		</li>
		<li class="lahfb-tab">
			<a href="#tablets">
				<i class="fa fa-tablet"></i>
				<span><?php esc_html_e( 'Tablets', 'lastudio-header-footer-builder' ); ?></span>
			</a>
		</li>
		<li class="lahfb-tab">
			<a href="#mobiles">
				<i class="fa fa-mobile"></i>
				<span><?php esc_html_e( 'Mobiles', 'lastudio-header-footer-builder' ); ?></span>
			</a>
		</li>
	</ul>

	<!-- all devices -->
	<div class="lahfb-tab-panel lahfb-styling-screen-panel wp-clearfix" data-id="#all">

		<ul class="lahfb-tabs-list lahfb-styling-groups wp-clearfix"><?php echo '' . $all_list_items; ?></ul>
		<?php echo '' . $all_panel_items; ?>

	</div> <!-- end all devices -->

	<!-- tablets devices -->
	<div class="lahfb-tab-panel lahfb-styling-screen-panel" data-id="#tablets">

		<ul class="lahfb-tabs-list lahfb-styling-groups wp-clearfix"><?php echo '' . $tablets_list_items; ?></ul>
		<?php echo '' . $tablets_panel_items; ?>

	</div> <!-- end tablets devices -->

	<!-- mobiles devices -->
	<div class="lahfb-tab-panel lahfb-styling-screen-panel" data-id="#mobiles">

		<ul class="lahfb-tabs-list lahfb-styling-groups wp-clearfix"><?php echo '' . $mobiles_list_items; ?></ul>
		<?php echo '' . $mobiles_panel_items; ?>

	</div> <!-- end mobiles devices -->

	<?php

}
