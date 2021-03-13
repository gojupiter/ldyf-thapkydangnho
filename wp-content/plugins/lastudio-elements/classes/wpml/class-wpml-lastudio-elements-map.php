<?php

/**
 * Class WPML_LaStudio_Elements_Advanced_Map
 */
class WPML_LaStudio_Elements_Advanced_Map extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'pins';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'pin_address', 'pin_desc' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'pin_address':
				return esc_html__( 'LaStudio Map: Pin Address', 'lastudio-elements' );

			case 'pin_desc':
				return esc_html__( 'LaStudio Map: Pin Description', 'lastudio-elements' );

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
			case 'pin_address':
				return 'LINE';

			case 'pin_desc':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
