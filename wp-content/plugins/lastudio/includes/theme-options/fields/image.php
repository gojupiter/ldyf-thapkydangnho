<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Image extends LaStudio_Theme_Options_Field_Base {
  
  public function output(){

    echo $this->element_before();

    $preview = '';
    $value   = $this->element_value();
    $add     = ( ! empty( $this->field['add_title'] ) ) ? $this->field['add_title'] : __( 'Add Image', 'lastudio' );
    $hidden  = ( empty( $value ) ) ? ' hidden' : '';

    if( ! empty( $value ) ) {
      $attachment = wp_get_attachment_image_src( $value, 'thumbnail' );
      $preview    = $attachment[0];
      if(!empty($preview) && is_ssl() && strtolower( substr( $preview, 0, 7 ) ) == 'http://'){
        $preview = 'https://' . substr( $preview, 7 );
      }
    }

    echo '<div class="la-image-preview'. $hidden .'"><div class="la-preview"><i class="fa fa-times la-remove"></i><img src="'. esc_url($preview) .'" alt="preview" /></div></div>';
    echo '<a href="#" class="button button-primary la-add">'. $add .'</a>';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';

    echo $this->element_after();

  }

}
