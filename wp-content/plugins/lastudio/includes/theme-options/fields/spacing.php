<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Number
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Spacing extends LaStudio_Theme_Options_Field_Base {

    public $options = array();

    public function output() {
	    echo $this->element_before();

        $option_defaults = array(
            'top'       => true,
            'bottom'    => true,
            'left'      => false,
            'right'     => false,
        );

        $value_defaults = array(
            'top'       => '',
            'bottom'    => '',
            'left'      => '',
            'right'     => '',
        );

        $this->options = wp_parse_args( isset($this->field['options']) ? $this->field['options'] : array() , $option_defaults );
        $this->value    = wp_parse_args( $this->element_value(), $value_defaults );


        if($this->options['top'] || $this->options['bottom'] || $this->options['left'] || $this->options['right']){
            $la_attr = '';
            if(!empty( $this->field['attributes']['data-customize-setting-link'] )){
                $la_attr .= ' data-la-customize-setting-link="' . $this->field['attributes']['data-customize-setting-link'] . '"';
                $la_attr .= ' data-la-customize-setting-key="'. (( isset( $this->field['id'] ) ) ? $this->field['id'] : '') .'"';
                unset($this->field['attributes']['data-customize-setting-link']);
            }
            echo '<div' . $this->element_class('la-parent-fields') . $la_attr .'>';
            echo '<fieldset>';
            if($this->options['top']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_top',
                    'type'            => 'text',
                    'name'            => $this->element_name('[top]'),
                    'value'           => $this->value['top'],
                    'default'         => ( isset( $this->field['default']['top'] ) ) ? $this->field['default']['top'] : '',
                    'before'          => '<label for="'.esc_attr($this->field['id'].'_top').'"><i class="fa fa-long-arrow-up"></i></label>',
                    'class'         => 'la-child-field',
                    'attributes'      => array(
                        'data-atts'     => 'top'
                    )
                ) );
            }
            if($this->options['bottom']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_bottom',
                    'type'            => 'text',
                    'name'            => $this->element_name('[bottom]'),
                    'value'           => $this->value['bottom'],
                    'default'         => ( isset( $this->field['default']['bottom'] ) ) ? $this->field['default']['bottom'] : '',
                    'before'          => '<label for="'.esc_attr($this->field['id'].'_bottom').'"><i class="fa fa-long-arrow-down"></i></label>',
                    'class'         => 'la-child-field',
                    'attributes'      => array(
                        'data-atts'     => 'bottom'
                    )
                ) );
            }
            if($this->options['left']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_left',
                    'type'            => 'text',
                    'name'            => $this->element_name('[left]'),
                    'value'           => $this->value['left'],
                    'default'         => ( isset( $this->field['default']['left'] ) ) ? $this->field['default']['left'] : '',
                    'before'          => '<label for="'.esc_attr($this->field['id'].'_left').'"><i class="fa fa-long-arrow-left"></i></label>',
                    'class'         => 'la-child-field',
                    'attributes'      => array(
                        'data-atts'     => 'left'
                    )
                ) );
            }
            if($this->options['right']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_right',
                    'type'            => 'text',
                    'name'            => $this->element_name('[right]'),
                    'value'           => $this->value['right'],
                    'default'         => ( isset( $this->field['default']['right'] ) ) ? $this->field['default']['right'] : '',
                    'before'          => '<label for="'.esc_attr($this->field['id'].'_right').'"><i class="fa fa-long-arrow-right"></i></label>',
                    'class'         => 'la-child-field',
                    'attributes'      => array(
                        'data-atts'     => 'right'
                    )
                ) );
            }
            echo ( isset( $this->field['unit'] ) ) ? '<em>'. $this->field['unit'] .'</em>' : '';
            echo '</fieldset>';
            echo '</div>';
        }

	    echo $this->element_after();

    }

}