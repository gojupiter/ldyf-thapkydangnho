<!-- modal login edit -->
<div class="lahfb-modal-wrap lahfb-modal-edit" data-element-target="login">

	<div class="lahfb-modal-header">
		<h4><?php esc_html_e( 'Login Modal Settings', 'lastudio-header-footer-builder' ); ?></h4>
		<i class="fa fa-times"></i>
	</div>

	<div class="lahfb-modal-contents-wrap">
		<div class="lahfb-modal-contents w-row">

			<ul class="lahfb-tabs-list lahfb-element-groups wp-clearfix">
				<li class="lahfb-tab w-active">
					<a href="#general">
						<span><?php esc_html_e( 'General', 'lastudio-header-footer-builder' ); ?></span>
					</a>
				</li>
				<li class="lahfb-tab">
					<a href="#styling">
						<span><?php esc_html_e( 'Styling', 'lastudio-header-footer-builder' ); ?></span>
					</a>
				</li>
				<li class="lahfb-tab">
					<a href="#extra-class">
						<span><?php esc_html_e( 'Extra Class', 'lastudio-header-footer-builder' ); ?></span>
					</a>
				</li>
			</ul> <!-- end .lahfb-tabs-list -->

			<!-- general -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#general">

				<?php

					lahfb_select( array(
						'title'			=> esc_html__( 'Type', 'lastudio-header-footer-builder' ),
						'id'			=> 'login_type',
						'default'		=> 'icon',
						'options'		=> array(
							'text'		=> esc_html__( 'Text', 'lastudio-header-footer-builder' ),
							'icon' 		=> esc_html__( 'Icon', 'lastudio-header-footer-builder' ),
							'icon_text' => esc_html__( 'Icon & Text', 'lastudio-header-footer-builder' ),
						),
						'dependency'	=> array(
							'text'  	=> array( 'login_text', 'login_text_icon' ),
							'icon_text'	=> array( 'login_text', 'login_text_icon' )
						),
					));

					// Login Text
					lahfb_textfield( array(
						'title'			=> esc_html__( 'Login Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'login_text',
						'default'		=> 'Login / Register',
					));

					// Login Icon
					lahfb_switcher( array(
						'title'			=> esc_html__( 'Icon floating on the right', 'lastudio-header-footer-builder' ),
						'id'			=> 'login_text_icon',
						'default'		=> 'false',
					));

					lahfb_select( array(
						'title'			=> esc_html__( 'Open Login Form', 'lastudio-header-footer-builder' ),
						'id'			=> 'open_form',
						'default'		=> 'modal',
						'options'		=> array(
							'modal'		 => esc_html__( 'Modal', 'lastudio-header-footer-builder' ),
							'dropdown' 	 => esc_html__( 'Dropdown', 'lastudio-header-footer-builder' ),
						),
						'dependency'	=> array(
							'dropdown'  => array( 'show_arrow' ),
						),
					));

					lahfb_switcher( array(
						'title'			=> esc_html__( 'Showing Arrow', 'lastudio-header-footer-builder' ),
						'id'			=> 'show_arrow',
						'default'		=> 'true',
					));

					lahfb_switcher( array(
						'title'			=> esc_html__( 'Showing avatar instead of login icon after login', 'lastudio-header-footer-builder' ),
						'id'			=> 'show_avatar',
						'default'		=> 'true',
					));

					// Tooltip Text
					lahfb_switcher( array(
						'title'         => esc_html__( 'Show Tooltip Text ?', 'lastudio-header-footer-builder' ),
						'id'            => 'show_tooltip',
						'default'       => 'false',
						'dependency'	=> array(
							'true'  => array( 'tooltip_text', 'tooltip_position' ),
						),
					));

					lahfb_textfield( array(
						'title'			=> esc_html__( 'Tooltip Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'tooltip_text',
						'default'		=> 'Tooltip Text',
					));

					lahfb_select( array(
						'title'			=> esc_html__( 'Select Tooltip Position', 'lastudio-header-footer-builder' ),
						'id'			=> 'tooltip_position',
						'default'		=> 'tooltip-on-bottom',
						'options'		=> array(
							'tooltip-on-top'	=> esc_html__( 'Top', 'lastudio-header-footer-builder' ),
							'tooltip-on-bottom' => esc_html__( 'Bottom', 'lastudio-header-footer-builder' ),
						),
					));

                    lahfb_icon( array(
                        'title'			=> esc_html__( 'Select your desired icon', 'lastudio-header-footer-builder' ),
                        'id'			=> 'login_icon',
                    ));

				?>

			</div> <!-- end general -->

			

			<!-- styling -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#styling">

				<?php
					lahfb_styling_tab( array(
						'Text' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'font_weight' ),
							array( 'property' => 'font_style' ),
							array( 'property' => 'text_align' ),
							array( 'property' => 'text_transform' ),
							array( 'property' => 'text_decoration' ),
							array( 'property' => 'line_height' ),
							array( 'property' => 'letter_spacing' ),
							array( 'property' => 'overflow' ),
							array( 'property' => 'word_break' ),
						),
						'Icon' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'margin' ),
						),
						'Box' => array(
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
						),
						'Form' => array(
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
							array( 'property' => 'position' ),
						),
						'Tooltip' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'font_weight' ),
							array( 'property' => 'font_style' ),
							array( 'property' => 'letter_spacing' ),
							array( 'property' => 'overflow' ),
							array( 'property' => 'word_break' ),
						),
					) );
				?>

			</div> <!-- end #styling -->

			<!-- extra-class -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#extra-class">

				<?php

					lahfb_textfield( array(
						'title'			=> esc_html__( 'Extra class', 'lastudio-header-footer-builder' ),
						'id'			=> 'extra_class',
					));

				?>

			</div> <!-- end #extra-class -->

		</div>
	</div> <!-- end lahfb-modal-contents-wrap -->

	<div class="lahfb-modal-footer">
		<input type="button" class="lahfb_close button" value="<?php esc_html_e( 'Close', 'lastudio-header-footer-builder' ); ?>">
		<input type="button" class="lahfb_save button button-primary" value="<?php esc_html_e( 'Save Changes', 'lastudio-header-footer-builder' ); ?>">
	</div>

</div> <!-- end lahfb-elements -->