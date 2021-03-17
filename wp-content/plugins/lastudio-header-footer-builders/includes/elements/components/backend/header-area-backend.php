<!-- modal header area -->
<div class="lahfb-modal-wrap lahfb-modal-edit" data-element-target="header-area">

	<div class="lahfb-modal-header">
		<h4><?php esc_html_e( 'Header Area', 'lastudio-header-footer-builder' ); ?></h4>
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
					<a href="#transparency_styling">
						<span><?php esc_html_e( 'Transparency Styling', 'lastudio-header-footer-builder' ); ?></span>
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

					lahfb_switcher( array(
						'title'			=> esc_html__( 'Full With Container', 'lastudio-header-footer-builder' ),
						'id'			=> 'full_container',
					));

					lahfb_switcher( array(
						'title'			=> esc_html__( 'Container Padding', 'lastudio-header-footer-builder' ),
						'id'			=> 'container_padd',
						'default'		=> 'true', 
					));

					lahfb_select( array(
						'title'			=> esc_html__( 'Content Position', 'lastudio-header-footer-builder' ),
						'id'			=> 'content_position',
						'default'		=> 'middle',
						'options'		=> array(
							'top'	 => esc_html__( 'Top', 'lastudio-header-footer-builder' ),
							'middle' => esc_html__( 'Middle', 'lastudio-header-footer-builder' ),
							'bottom' => esc_html__( 'Bottom', 'lastudio-header-footer-builder' ),
						)
					));

                    lahfb_styling_tab( array(
                        'Row Layout' => array(
                            array( 'property' => 'row_layout' )
                        )
                    ) );
				?>

			</div> <!-- end general -->

			<!-- transparency_styling -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#transparency_styling">

				<?php
					lahfb_styling_tab( array(
                        'Transparency Background' => array(
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
							array( 'property' => 'gradient' )
						),
						'Transparency Text Color' => array(
							array( 'property' => 'color' )
						),
                        'Transparency Link Color' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' )
						)
					) );
				?>

			</div> <!-- end #transparency_styling -->

            <!-- styling -->
			<div class="lahfb-tab-panel lahfb-group-panel" data-id="#styling">

				<?php
					lahfb_styling_tab( array(
						'Typography' => array(
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
						'Background' => array(
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
							array( 'property' => 'gradient' ),
						),
						'Box' => array(
							array( 'property' => 'height' ),
							array( 'property' => 'width' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
							array( 'property' => 'box_shadow' ),
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

					lahfb_textfield( array(
						'title'			=> esc_html__( 'Extra ID', 'lastudio-header-footer-builder' ),
						'id'			=> 'extra_id',
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