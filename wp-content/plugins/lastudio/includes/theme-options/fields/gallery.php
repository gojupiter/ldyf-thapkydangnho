<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Gallery
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Gallery extends LaStudio_Theme_Options_Field_Base {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo $this->element_before();

    $value  = $this->element_value();
    $add    = ( ! empty( $this->field['add_title'] ) ) ? $this->field['add_title'] : __( 'Add Gallery', 'lastudio' );
    $edit   = ( ! empty( $this->field['edit_title'] ) ) ? $this->field['edit_title'] : __( 'Edit Gallery', 'lastudio' );
    $clear  = ( ! empty( $this->field['clear_title'] ) ) ? $this->field['clear_title'] : __( 'Clear', 'lastudio' );
    $hidden = ( empty( $value ) ) ? ' hidden' : '';

    echo '<ul>';

    if( ! empty( $value ) ) {

      $values = explode( ',', $value );

      foreach ( $values as $id ) {
        $attachment = wp_get_attachment_image_src( $id, 'thumbnail' );
        echo '<li><img src="'. $attachment[0] .'" alt="" /></li>';
      }

    }

    echo '</ul>';
    echo '<a href="#" class="button button-primary la-add">'. $add .'</a>';
    echo '<a href="#" class="button la-edit'. $hidden .'">'. $edit .'</a>';
    echo '<a href="#" class="button la-warning-primary la-remove'. $hidden .'">'. $clear .'</a>';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class() . $this->element_attributes() .'/>';

    echo $this->element_after();

  }

}
