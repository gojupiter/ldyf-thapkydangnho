<?php

/**
 * Class WPML_LaStudio_Elements_Subscribe_Form
 */
class WPML_LaStudio_Elements_Subscribe_Form extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'additional_fields';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'placeholder' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'placeholder':
				return esc_html__( 'LaStudio Subscribe Form: Additional Field Placeholder', 'lastudio-elements' );

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
			case 'placeholder':
				return 'LINE';

			default:
				return '';
		}
	}

}
