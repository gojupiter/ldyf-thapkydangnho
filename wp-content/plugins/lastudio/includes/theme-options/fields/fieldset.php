<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Fieldset
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Fieldset extends LaStudio_Theme_Options_Field_Base {

    public function output() {

        echo $this->element_before();

        echo '<div class="la-inner">';

        foreach ( $this->field['fields'] as $field ) {

            $field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
            $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
            $field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
            $unique_id     = $this->unique .'['. $this->field['id'] .']';

            if ( ! empty( $this->field['un_array'] ) ) {
                if(isset($this->field['child_value'])) {
                    $child_value = (isset($this->field['child_value'][$field_id])) ? $this->field['child_value'][$field_id] : '';
                    echo la_fw_add_element( $field, $child_value, $this->unique );
                }else{
                    echo la_fw_add_element( $field, '', $this->unique );
                }
            } else {
                echo la_fw_add_element( $field, $field_value, $unique_id );
            }

        }

        echo '</div>';

        echo $this->element_after();

    }

}
