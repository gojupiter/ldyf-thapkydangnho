<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Content
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Content extends LaStudio_Theme_Options_Field_Base {

  public function output() {

    echo $this->element_before();
    echo $this->field['content'];
    echo $this->element_after();

  }

}
