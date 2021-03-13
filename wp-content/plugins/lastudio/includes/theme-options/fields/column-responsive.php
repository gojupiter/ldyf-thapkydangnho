<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Number
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Column_Responsive extends LaStudio_Theme_Options_Field_Base {


    public $options = array();

    public function output() {

        echo $this->element_before();

        $option_defaults = array(
            'xlg'   => array('1'=>'1' ,'2'=>'2', '3'=>'3' ,'4'=>'4' ,'5'=>'5' ,'6'=>'6'),
            'lg'    => array('1'=>'1' ,'2'=>'2', '3'=>'3' ,'4'=>'4' ,'5'=>'5' ,'6'=>'6'),
            'md'    => array('1'=>'1' ,'2'=>'2', '3'=>'3' ,'4'=>'4' ,'5'=>'5' ,'6'=>'6'),
            'sm'    => array('1'=>'1' ,'2'=>'2', '3'=>'3' ,'4'=>'4' ,'5'=>'5' ,'6'=>'6'),
            'xs'    => array('1'=>'1' ,'2'=>'2', '3'=>'3' ,'4'=>'4' ,'5'=>'5' ,'6'=>'6'),
            'mb'    => array('1'=>'1' ,'2'=>'2', '3'=>'3' ,'4'=>'4' ,'5'=>'5' ,'6'=>'6')
        );

        $value_defaults = array(
            'xlg'   => '1',
            'lg'    => '1',
            'md'    => '1',
            'sm'    => '1',
            'xs'    => '1',
            'mb'    => '1'
        );

        $this->options = wp_parse_args( isset($this->field['options']) ? $this->field['options'] : array() , $option_defaults );
        $this->value    = wp_parse_args( $this->element_value(), $value_defaults );

        if($this->options['xlg'] || $this->options['lg'] || $this->options['md'] || $this->options['sm'] || $this->options['xs'] || $this->options['mb'] ){

            $la_attr = '';
            if(!empty( $this->field['attributes']['data-customize-setting-link'] )){
                $la_attr .= ' data-la-customize-setting-link="' . $this->field['attributes']['data-customize-setting-link'] . '"';
                $la_attr .= ' data-la-customize-setting-key="'. (( isset( $this->field['id'] ) ) ? $this->field['id'] : '') .'"';
                unset($this->field['attributes']['data-customize-setting-link']);
            }
            echo '<div' . $this->element_class('la-parent-fields') . $la_attr .'>';

            echo '<fieldset>';

	        if($this->options['xlg']){
		        echo la_fw_add_element( array(
			        'pseudo'          => true,
			        'id'              => $this->field['id'].'_xlg',
			        'type'            => 'select',
			        'name'            => $this->element_name( '[xlg]' ),
			        'options'         => $this->options['xlg'],
			        'value'           => $this->value['xlg'],
                    'class'         => 'la-child-field',
			        'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Large Desktop', 'lastudio') .'"><i class="fa fa-desktop"></i></label>'
		        ) );
	        }

            if($this->options['lg']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_lg',
                    'type'            => 'select',
                    'name'            => $this->element_name( '[lg]' ),
                    'options'         => $this->options['lg'],
                    'value'           => $this->value['lg'],
                    'class'         => 'la-child-field',
                    'before'          => '<label class="la-tip" data-position="top" data-title="'.esc_attr__('Desktop', 'lastudio').'"><i class="dashicons dashicons-desktop"></i></label>'
                ) );
            }

            if($this->options['md']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_md',
                    'type'            => 'select',
                    'name'            => $this->element_name( '[md]' ),
                    'options'         => $this->options['md'],
                    'value'           => $this->value['md'],
                    'class'         => 'la-child-field',
                    'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet', 'lastudio') .'"><i class="dashicons dashicons-tablet" style="transform: rotate(90deg);"></i></label>'
                ) );
            }

            if($this->options['sm']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_sm',
                    'type'            => 'select',
                    'name'            => $this->element_name( '[sm]' ),
                    'options'         => $this->options['sm'],
                    'value'           => $this->value['sm'],
                    'class'         => 'la-child-field',
                    'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet Portrait', 'lastudio') .'"><i class="dashicons dashicons-tablet"></i></label>'
                ) );
            }

            if($this->options['xs']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_xs',
                    'type'            => 'select',
                    'name'            => $this->element_name( '[xs]' ),
                    'options'         => $this->options['xs'],
                    'value'           => $this->value['xs'],
                    'class'         => 'la-child-field',
                    'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Mobile Landscape', 'lastudio') .'"><i class="dashicons dashicons-smartphone" style="transform: rotate(90deg);"></i></label>'
                ) );
            }

            if($this->options['mb']){
                echo la_fw_add_element( array(
                    'pseudo'          => true,
                    'id'              => $this->field['id'].'_mb',
                    'type'            => 'select',
                    'name'            => $this->element_name( '[mb]' ),
                    'options'         => $this->options['mb'],
                    'value'           => $this->value['mb'],
                    'class'         => 'la-child-field',
                    'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Mobile', 'lastudio') .'"><i class="dashicons dashicons-smartphone"></i></label>'
                ) );
            }

            echo '</fieldset>';

            echo '</div>';
        }

        echo $this->element_after();

    }

}