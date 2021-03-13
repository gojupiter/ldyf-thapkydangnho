<?php

/**
 * Class WPML_LaStudio_Elements_Image_Comparison
 */
class WPML_LaStudio_Elements_Image_Comparison extends WPML_Elementor_Module_With_Items {

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
		return array( 'item_before_label', 'item_after_label' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_before_label':
				return esc_html__( 'LaStudio Image Comparison: Before Label', 'lastudio-elements' );

			case 'item_after_label':
				return esc_html__( 'LaStudio Image Comparison: After Label', 'lastudio-elements' );

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
			case 'item_before_label':
				return 'LINE';

			case 'item_after_label':
				return 'LINE';

			default:
				return '';
		}
	}

}
