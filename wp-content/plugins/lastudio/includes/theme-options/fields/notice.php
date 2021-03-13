<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Notice
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Notice extends LaStudio_Theme_Options_Field_Base {

  public function output() {

    echo $this->element_before();
    echo '<div class="la-notice la-'. $this->field['class'] .'">'. $this->field['content'] .'</div>';
    echo $this->element_after();

  }

}
