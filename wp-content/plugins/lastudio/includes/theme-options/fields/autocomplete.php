<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Autocomplete Posts, Pages, Post types
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Autocomplete extends LaStudio_Theme_Options_Field_Base {

    public function __construct( $field, $value = '', $unique = '' ) {
        parent::__construct( $field, $value, $unique );
    }

    public function output() {

        $value = $this->element_value();
        $query = $values = '';

        if ( isset( $this->field['query_args'] ) ) {
            $query = wp_json_encode(array(
                'action'        => 'la-fw-autocomplete',
                'query_args'    => $this->field['query_args'],
                'elm_name'      => $this->element_name()
            ));
        }

        if ( ! empty( $value ) ) {
            if ( is_array( $value ) ) {
                foreach ( $value as $id ) {
                    $values .= '<div id="' . $id . '"><input name="' . $this->element_name() . '[]" value="' . $id . '" /><span> ' . get_the_title( $id ) . '<i class="fa fa-remove"></i></span></div>';
                }
            } else {
                $values .= '<div id="' . $value . '"><input name="' . $this->element_name() . '" value="' . $value . '" /><span> ' . get_the_title( $value ) . '<i class="fa fa-remove"></i></span></div>';
            }
        }

        echo $this->element_before();
        echo '<div class="la-autocomplete" data-query=\'' . $query . '\'>';
        echo '<input type="text"'. $this->element_class() . $this->element_attributes() .' />';
        echo '<i class="fa fa-codevz"></i>';
        echo '<div class="ajax_items"></div>';
        echo '<div class="selected_items">' . $values . '</div>';
        echo '</div>';
        echo $this->element_after();

    }

}