<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class LaStudio_Theme_Options_Field_Ace_Editor extends LaStudio_Theme_Options_Field_Base {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );

	}

	public function element_id($extra_name = ''){
		$element_id      = ( isset( $this->field['id'] ) ) ? $this->field['id'] : '';
		return ( isset( $this->field['name'] ) ) ? $this->field['name'] . $extra_name : $this->unique .'_'. $element_id .'_'. $extra_name;
	}

	public function output(){
        if ( ! isset( $this->field['mode'] ) ) {
			$this->field['mode'] = 'javascript';
		}
		if ( ! isset( $this->field['theme'] ) ) {
			$this->field['theme'] = 'monokai';
		}

		$params = array(
			'minLines' => 10,
			'maxLines' => 30,
		);

		if ( isset( $this->field['args'] ) && ! empty( $this->field['args'] ) && is_array( $this->field['args'] ) ) {
			$params = wp_parse_args( $this->field['args'], $params );
		}

		$extra_attribues = ' data-editor="'.esc_attr($this->element_id('editor')).'"';
		$extra_attribues .= ' data-mode="'.esc_attr($this->field['mode']).'"';
		$extra_attribues .= ' data-theme="'.esc_attr($this->field['theme']).'"';

		echo $this->element_before();
		echo '<div class="ace-wrapper">';
		echo '<input type="hidden" class="localize_data" value="'.htmlspecialchars( wp_json_encode( $params ) ).'"/>';
		echo '<textarea id="'.esc_attr($this->element_id()).'_textarea" name="'. esc_attr($this->element_name()) .'"'. $this->element_class('ace-editor hidden ') . $this->element_attributes() . $extra_attribues .'>'. $this->element_value() .'</textarea>';
		echo '<pre id="'.esc_attr($this->element_id('editor')).'" class="ace-editor-area">'.htmlspecialchars( $this->element_value() ).'</pre>';
		echo '</div>';
		echo $this->element_after();
	}
}