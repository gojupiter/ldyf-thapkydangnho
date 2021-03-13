<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Upload
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Upload extends LaStudio_Theme_Options_Field_Base {

  public function output() {

    echo $this->element_before();

    if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }

    $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : 'image';
    $button_title = ( isset( $button_title ) ) ? $button_title : __( 'Upload', 'lastudio' );
    $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : __( 'Upload', 'lastudio' );
    $insert_title = ( isset( $insert_title ) ) ? $insert_title : __( 'Use Image', 'lastudio' );

    echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
    echo '<a href="#" class="button la-add" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a>';

    echo $this->element_after();

  }
}
