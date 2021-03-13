<?php

/**
 * Class WPML_LaStudio_Elements_Brands
 */
class WPML_LaStudio_Elements_Brands extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'brands_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_name', 'item_desc' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_name':
				return esc_html__( 'LaStudio Brands: Company Name', 'lastudio-elements' );

			case 'item_desc':
				return esc_html__( 'LaStudio Brands: Company Description', 'lastudio-elements' );

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
			case 'item_name':
				return 'LINE';

			case 'item_desc':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
