<?php

/**
 * Class WPML_LaStudio_Elements_Price_List
 */
class WPML_LaStudio_Elements_Price_List extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'price_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_title', 'item_price', 'item_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'LaStudio Price List: Item Title', 'lastudio-elements' );

			case 'item_price':
				return esc_html__( 'LaStudio Price List: Item Price', 'lastudio-elements' );

			case 'item_text':
				return esc_html__( 'LaStudio Price List: Item Description', 'lastudio-elements' );

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
			case 'item_price':
			case 'item_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
