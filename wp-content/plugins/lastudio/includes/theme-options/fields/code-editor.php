<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class LaStudio_Theme_Options_Field_Code_Editor extends LaStudio_Theme_Options_Field_Base {

    public function element_id(){
        $element_id      = ( isset( $this->field['id'] ) ) ? $this->field['id'] : '';
        if(!empty($element_id)){
            return ' id="la_code_editor_id_'. $element_id .'"';
        }
        return '';

    }

    public function output(){

        echo $this->element_before();

        if ( ! isset( $this->field['editor_setting'] ) ) {
            echo '<textarea name="'. $this->element_name() .'"'. $this->element_class() . $this->element_attributes() .'>'. $this->element_value() .'</textarea>';
        }
        else{
            /*
            $settings = array(
                'type' => 'text/html',
                'codemirror' => array(
                    'indentUnit' => 2,
                    'tabSize' => 2,
                )
            );
            */
            $settings = wp_enqueue_code_editor( $this->field['editor_setting'] );
            echo '<input type="hidden" class="localize_data" value="'.esc_attr(wp_json_encode($settings)).'"/>';
            echo '<textarea'. $this->element_id() .' name="'. $this->element_name() .'"'. $this->element_class('la-field-code-editor-texarea') . $this->element_attributes() .'>'. $this->element_value() .'</textarea>';
        }
        echo $this->element_after();
    }
}