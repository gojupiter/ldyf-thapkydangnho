<?php

/**
 * Class WPML_LaStudio_Elements_Animated_Text
 */
class WPML_LaStudio_Elements_Animated_Text extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'animated_text_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_text':
				return esc_html__( 'LaStudio Animated Text: Item Text', 'lastudio-elements' );

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
			case 'item_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
