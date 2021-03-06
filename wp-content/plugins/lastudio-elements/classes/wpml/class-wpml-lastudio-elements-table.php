<?php

/**
 * Class WPML_LaStudio_Elements_Table
 */
class WPML_LaStudio_Elements_Table extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'table_body';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'cell_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'cell_text':
				return esc_html__( 'LaStudio Table: Cell Text', 'lastudio-elements' );

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
			case 'cell_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
