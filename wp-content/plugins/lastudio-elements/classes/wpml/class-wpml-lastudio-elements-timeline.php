<?php

/**
 * Class WPML_LaStudio_Elements_Timeline
 */
class WPML_LaStudio_Elements_Timeline extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'cards_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_title', 'item_meta', 'item_desc', 'item_point_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'LaStudio Vertical Timeline: Item Title', 'lastudio-elements' );

			case 'item_meta':
				return esc_html__( 'LaStudio Vertical Timeline: Item Meta', 'lastudio-elements' );

			case 'item_desc':
				return esc_html__( 'LaStudio Vertical Timeline: Item Description', 'lastudio-elements' );

			case 'item_point_text':
				return esc_html__( 'LaStudio Vertical Timeline: Item Point Text', 'lastudio-elements' );

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

			case 'item_meta':
				return 'LINE';

			case 'item_desc':
				return 'VISUAL';

			case 'item_point_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
