<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Slider
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Slider extends LaStudio_Theme_Options_Field_Base {

    public function output() {

        $options = array(
            'step'  => ( empty( $this->field['options']['step'] ) ) ? 1 : $this->field['options']['step'],
            'unit'  => ( empty( $this->field['options']['unit'] ) ) ? '' : $this->field['options']['unit'],
            'min'   => ( empty( $this->field['options']['min'] ) ) ? 0 : $this->field['options']['min'],
            'max'   => ( empty( $this->field['options']['max'] ) ) ? 200 : $this->field['options']['max']
        );

        echo $this->element_before();
        echo '<input data-slider=\'' . wp_json_encode( $options ) . '\' type="'. $this->element_type() .'" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
        echo '<div class="la-slider"><div></div></div>';
        echo $this->element_after();
    }
}
