<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Group extends LaStudio_Theme_Options_Field_Base {
  
  public function output() {

    echo $this->element_before();

    $fields      = array_values( $this->field['fields'] );
    $last_id     = ( is_array( $this->value ) ) ? max( array_keys( $this->value ) ) : 0;
    $acc_title   = ( isset( $this->field['accordion_title'] ) ) ? $this->field['accordion_title'] : __( 'Adding', 'lastudio' );
    $field_title = ( isset( $fields[0]['title'] ) ) ? $fields[0]['title'] : $fields[1]['title'];
    $field_id    = ( isset( $fields[0]['id'] ) ) ? $fields[0]['id'] : $fields[1]['id'];
    $el_class    = ( isset( $this->field['title'] ) ) ? sanitize_title( $field_title ) : 'no-title';
    $search_id   = la_array_search( $fields, 'id', $acc_title );

    $maximum_item    = ( isset( $this->field['max_item'] ) ) ? absint($this->field['max_item']) : 0;

    if( ! empty( $search_id ) ) {

      $acc_title = ( isset( $search_id[0]['title'] ) ) ? $search_id[0]['title'] : $acc_title;
      $field_id  = ( isset( $search_id[0]['id'] ) ) ? $search_id[0]['id'] : $field_id;

    }

    echo '<div class="la-group la-group-'. $el_class .'-adding hidden">';

      echo '<span class="la_group__actions"><span class="la_group__action la_group__action-clone la-clone-group"><i class="fa fa-clone"></i></span><span class="la_group__action la_group__action-remove la-remove-group"><i class="fa fa-times"></i></span></span><h4 class="la-group-title"><span class="a-title">'. $acc_title .'</span></h4>';
      echo '<div class="la-group-content">';
      foreach ( $fields as $field ) {
        $field['sub']   = true;
        $unique         = $this->unique .'[_nonce]['. $this->field['id'] .']['. $last_id .']';
        $field_default  = ( isset( $field['default'] ) ) ? $field['default'] : '';
        echo la_fw_add_element( $field, $field_default, $unique );
      }
      echo '<div class="la-element la-text-right la-remove"><a href="#" class="button la-warning-primary la-remove-group">'. __( 'Remove', 'lastudio' ) .'</a></div>';
      echo '</div>';

    echo '</div>';

    $la_attr = ' data-max-item-group="'. $maximum_item .'"';
    if(!empty( $this->field['attributes']['data-customize-setting-link'] )){
      $la_attr .= ' data-la-customize-setting-link="' . $this->field['attributes']['data-customize-setting-link'] . '"';
      $la_attr .= ' data-la-customize-setting-key="'. (( isset( $this->field['id'] ) ) ? $this->field['id'] : '') .'"';
      unset($this->field['attributes']['data-customize-setting-link']);
    }

    echo '<div class="la-groups la-accordion" data-accordion-title="'.( isset( $this->field['accordion_title'] ) ? esc_attr($this->field['accordion_title']) : '' ).'"'.$la_attr.'>';

      if( ! empty( $this->value ) ) {

        foreach ( $this->value as $key => $value ) {

          $title = ( isset( $this->value[$key][$field_id] ) ) ? $this->value[$key][$field_id] : '';

          $field_title = ( ! empty( $search_id ) ) ? $acc_title : $field_title;

          echo '<div class="la-group la-group-'. $el_class .'-'. ( $key + 1 ) .'">';
          echo '<span class="la_group__actions"><span class="la_group__action la_group__action-clone la-clone-group"><i class="fa fa-clone"></i></span><span class="la_group__action la_group__action-remove la-remove-group"><i class="fa fa-times"></i></span></span><h4 class="la-group-title"><span class="a-title">'. $title .'</span></h4>';
          echo '<div class="la-group-content">';

          foreach ( $fields as $field ) {
            $field['sub'] = true;
            $unique = $this->unique . '[' . $this->field['id'] . ']['.$key.']';
            $value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';
            echo la_fw_add_element( $field, $value, $unique );
          }

          echo '<div class="la-element la-text-right la-remove"><a href="#" class="button la-warning-primary la-remove-group">'. __( 'Remove', 'lastudio' ) .'</a></div>';
          echo '</div>';
          echo '</div>';

          if($maximum_item > 0 && $key == $maximum_item){
            break;
          }

        }

      }

    echo '</div>';

    echo '<div class="la_group__notice hidden la-field-notice"><div class="la-danger">'. sprintf(__( 'You can not add more than %s', 'lastudio' ), $maximum_item ) .'</div></div>';

    echo '<a href="#" class="button button-primary la-add-group">'. $this->field['button_title'] .'</a>';

    echo $this->element_after();

  }

}
