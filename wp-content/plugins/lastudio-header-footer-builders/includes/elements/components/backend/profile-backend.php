<!-- modal profile edit -->
<div class="lahfb-modal-wrap lahfb-modal-edit" data-element-target="profile">

	<div class="lahfb-modal-header">
		<h4><?php esc_html_e( 'Profile Settings', 'lastudio-header-footer-builder' ); ?></h4>
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
					<a href="#socials">
						<span><?php esc_html_e( 'Socials', 'lastudio-header-footer-builder' ); ?></span>
					</a>
				</li>
				<li class="lahfb-tab">
					<a href="#styling">
						<span><?php esc_html_e( 'Styling', 'lastudio-header-footer-builder' ); ?></span>
					</a>
				</li>
				<li class="lahfb-tab">
					<a href="#classID">
						<span><?php esc_html_e( 'Class & ID', 'lastudio-header-footer-builder' ); ?></span>
					</a>
				</li>
			</ul> <!-- end .lahfb-tabs-list -->

			<!-- general -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#general">

				<?php

					lahfb_image( array(
						'title'			=> esc_html__( 'Image', 'lastudio-header-footer-builder' ),
						'id'			=> 'avatar',
					));

					lahfb_textfield( array(
						'title'			=> esc_html__( 'Name', 'lastudio-header-footer-builder' ),
						'id'			=> 'profile_name',
						'default'		=> 'David Hamilton James',
						'placeholder'	=> true,
					));

				?>

			</div> <!-- end general -->

			<!-- socials -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#socials">

				<?php

					lahfb_switcher( array(
						'title'			=> esc_html__( 'Socials', 'lastudio-header-footer-builder' ),
						'id'			=> 'socials',
						'default'		=> 'true',
					));

					// Social text 1
					lahfb_textfield( array(
						'title'			=> esc_html__( '1st Social Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_text_1',
						'default'		=> 'Facebook',
					));

					// Social link 1
					lahfb_textfield( array(
						'title'			=> esc_html__( '1st Social URL', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_url_1',
						'default'		=> 'https://www.facebook.com/',
					));

				?>
				
				<div class="w-col-sm-12 lahfb-line-divider"></div>

				<?php

					// Social text 2
					lahfb_textfield( array(
						'title'			=> esc_html__( '2st Social Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_text_2',
					));

					// Social link 2
					lahfb_textfield( array(
						'title'			=> esc_html__( '2st Social URL', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_url_2',
					));

				?>

				<div class="w-col-sm-12 lahfb-line-divider"></div>

				<?php

					// Social text 3
					lahfb_textfield( array(
						'title'			=> esc_html__( '3st Social Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_text_3',
					));

					// Social link 3
					lahfb_textfield( array(
						'title'			=> esc_html__( '3st Social URL', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_url_3',
					));

				?>

				<div class="w-col-sm-12 lahfb-line-divider"></div>

				<?php

					// Social text 4
					lahfb_textfield( array(
						'title'			=> esc_html__( '4st Social Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_text_4',
					));

					// Social link 4
					lahfb_textfield( array(
						'title'			=> esc_html__( '4st Social URL', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_url_4',
					));

				?>

				<div class="w-col-sm-12 lahfb-line-divider"></div>

				<?php

					// Social text 5
					lahfb_textfield( array(
						'title'			=> esc_html__( '5st Social Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_text_5',
					));

					// Social link 5
					lahfb_textfield( array(
						'title'			=> esc_html__( '5st Social URL', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_url_5',
					));

				?>

				<div class="w-col-sm-12 lahfb-line-divider"></div>

				<?php

					// Social text 6
					lahfb_textfield( array(
						'title'			=> esc_html__( '6st Social Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_text_6',
					));

					// Social link 6
					lahfb_textfield( array(
						'title'			=> esc_html__( '6st Social URL', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_url_6',
					));

				?>

				<div class="w-col-sm-12 lahfb-line-divider"></div>

				<?php

					// Social text 7
					lahfb_textfield( array(
						'title'			=> esc_html__( '7st Social Text', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_text_7',
					));

					// Social link 7
					lahfb_textfield( array(
						'title'			=> esc_html__( '7st Social URL', 'lastudio-header-footer-builder' ),
						'id'			=> 'social_url_7',
					));

				?>

			</div> <!-- socials -->

			<!-- styling -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#styling">

				<?php
					lahfb_styling_tab( array(
						'Image' => array(
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
						),
						'Name' => array(
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
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
						),
						'Socials Text' => array(
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
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
						),
						'Socials Box' => array(
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'position' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
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
					) );
				?>

			</div> <!-- end #styling -->

			<!-- classID -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#classID">

				<?php

					lahfb_textfield( array(
						'title'			=> esc_html__( 'Extra class', 'lastudio-header-footer-builder' ),
						'id'			=> 'extra_class',
					));

				?>

			</div> <!-- end #classID -->

		</div>
	</div> <!-- end lahfb-modal-contents-wrap -->

	<div class="lahfb-modal-footer">
		<input type="button" class="lahfb_close button" value="<?php esc_html_e( 'Close', 'lastudio-header-footer-builder' ); ?>">
		<input type="button" class="lahfb_save button button-primary" value="<?php esc_html_e( 'Save Changes', 'lastudio-header-footer-builder' ); ?>">
	</div>

</div> <!-- end lahfb-elements -->