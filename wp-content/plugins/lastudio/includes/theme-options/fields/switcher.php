<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Switcher extends LaStudio_Theme_Options_Field_Base {

  public function output() {

    echo $this->element_before();
    $label = ( isset( $this->field['label'] ) ) ? '<div class="la-text-desc">'. $this->field['label'] . '</div>' : '';
    echo '<label><input type="checkbox" name="'. $this->element_name() .'" value="1"'. $this->element_class() . $this->element_attributes() . checked( $this->element_value(), 1, false ) .'/><em data-on="'. __( 'on', 'lastudio' ) .'" data-off="'. __( 'off', 'lastudio' ) .'"></em><span></span></label>' . $label;
    echo $this->element_after();

  }

}