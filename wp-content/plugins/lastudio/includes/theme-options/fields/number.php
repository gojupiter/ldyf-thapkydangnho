<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Number
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Number extends LaStudio_Theme_Options_Field_Base {


  public function output() {

    echo $this->element_before();
    $unit = ( isset( $this->field['unit'] ) ) ? '<em>'. $this->field['unit'] .'</em>' : '';
    echo '<input type="number" name="'. $this->element_name() .'" value="'. $this->element_value().'"'. $this->element_class() . $this->element_attributes() .'/>'. $unit;
    echo $this->element_after();

  }

}
