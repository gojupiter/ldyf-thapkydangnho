<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Typography
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Responsive extends LaStudio_Theme_Options_Field_Base {


  public $options = array();

  public function output() {


    echo $this->element_before();

    $option_defaults = array(
        'xlg'   => true,
        'lg'    => true,
        'md'    => true,
        'sm'    => true,
        'xs'    => true,
        'mb'    => true
    );

    $value_defaults = array(
        'xlg'   => '',
        'lg'    => '',
        'md'    => '',
        'sm'    => '',
        'xs'    => '',
        'mb'    => ''
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
      echo '<div' . $this->element_class('la-parent-fields') . $la_attr . $this->element_attributes() . '>';

      echo '<fieldset>';

      if($this->options['xlg']){
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'id'              => $this->field['id'].'_xlg',
            'type'            => 'text',
            'name'            => $this->element_name( '[xlg]' ),
            'attributes'      => array(
                'data-child-atts' => 'xlg'
            ),
            'value'           => $this->value['xlg'],
            'class'         => 'la-child-field',
            'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Large Desktop', 'lastudio') .'"><i class="fa fa-desktop"></i></label>'
        ) );
      }

      if($this->options['lg']){
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'id'              => $this->field['id'].'_lg',
            'type'            => 'text',
            'name'            => $this->element_name( '[lg]' ),
            'attributes'      => array(
                'data-child-atts' => 'lg'
            ),
            'value'           => $this->value['lg'],
            'class'         => 'la-child-field',
            'before'          => '<label class="la-tip" data-position="top" data-title="'.esc_attr__('Desktop', 'lastudio').'"><i class="dashicons dashicons-desktop"></i></label>'
        ) );
      }

      if($this->options['md']){
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'id'              => $this->field['id'].'_md',
            'type'            => 'text',
            'name'            => $this->element_name( '[md]' ),
            'attributes'      => array(
              'data-child-atts' => 'md'
            ),
            'value'           => $this->value['md'],
            'class'         => 'la-child-field',
            'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet', 'lastudio') .'"><i class="dashicons dashicons-tablet" style="transform: rotate(90deg);"></i></label>'
        ) );
      }

      if($this->options['sm']){
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'id'              => $this->field['id'].'_sm',
            'type'            => 'text',
            'name'            => $this->element_name( '[sm]' ),
            'attributes'      => array(
                'data-child-atts' => 'sm'
            ),
            'value'           => $this->value['sm'],
            'class'         => 'la-child-field',
            'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet Portrait', 'lastudio') .'"><i class="dashicons dashicons-tablet"></i></label>'
        ) );
      }

      if($this->options['xs']){
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'id'              => $this->field['id'].'_xs',
            'type'            => 'text',
            'name'            => $this->element_name( '[xs]' ),
            'attributes'      => array(
                'data-child-atts' => 'xs'
            ),
            'value'           => $this->value['xs'],
            'class'         => 'la-child-field',
            'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Mobile Landscape', 'lastudio') .'"><i class="dashicons dashicons-smartphone" style="transform: rotate(90deg);"></i></label>'
        ) );
      }

      if($this->options['mb']){
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'id'              => $this->field['id'].'_mb',
            'type'            => 'text',
            'name'            => $this->element_name( '[mb]' ),
            'attributes'      => array(
                'data-child-atts' => 'mb'
            ),
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
