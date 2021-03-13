<?php

/**
 * Class WPML_LaStudio_Elements_Slider
 */
class WPML_LaStudio_Elements_Slider extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'item_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_title', 'item_subtitle', 'item_desc', 'item_button_primary_text', 'item_button_secondary_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'LaStudio Slider: Slide Title', 'lastudio-elements' );

			case 'item_subtitle':
				return esc_html__( 'LaStudio Slider: Slide Subtitle', 'lastudio-elements' );

			case 'item_desc':
				return esc_html__( 'LaStudio Slider: Slide Description', 'lastudio-elements' );

			case 'item_button_primary_text':
				return esc_html__( 'LaStudio Slider: Slide Button Primary Text', 'lastudio-elements' );

			case 'item_button_secondary_text':
				return esc_html__( 'LaStudio Slider: Slide Button Secondary Text', 'lastudio-elements' );

			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'item_title':
				return 'LINE';

			case 'item_subtitle':
				return 'LINE';

			case 'item_desc':
				return 'VISUAL';

			case 'item_button_primary_text':
				return 'LINE';

			case 'item_button_secondary_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
