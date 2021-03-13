<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Text
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Text extends LaStudio_Theme_Options_Field_Base {

  public function output(){

    echo $this->element_before();
    echo '<input type="'. $this->element_type() .'" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
    echo $this->element_after();

  }

}
