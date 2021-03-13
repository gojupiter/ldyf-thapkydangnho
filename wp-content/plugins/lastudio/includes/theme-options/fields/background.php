<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Theme_Options_Field_Background extends LaStudio_Theme_Options_Field_Base{

    public function output() {

        echo $this->element_before();

        $value_defaults = array(
            'image'       => '',
            'repeat'      => '',
            'position'    => '',
            'attachment'  => '',
            'size'        => '',
            'color'       => '',
        );

        $this->value  = wp_parse_args( $this->element_value(), $value_defaults );

        if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }

        $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : 'image';
        $button_title = ( isset( $button_title ) ) ? $button_title : __( 'Upload', 'lastudio' );
        $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : __( 'Upload', 'lastudio' );
        $insert_title = ( isset( $insert_title ) ) ? $insert_title : __( 'Use Image', 'lastudio' );

        $la_attr = '';

        if(!empty( $this->field['attributes']['data-customize-setting-link'] )){
            $la_attr .= ' data-la-customize-setting-link="' . $this->field['attributes']['data-customize-setting-link'] . '"';
            $la_attr .= ' data-la-customize-setting-key="'. (( isset( $this->field['id'] ) ) ? $this->field['id'] : '') .'"';
            unset($this->field['attributes']['data-customize-setting-link']);
        }

        echo '<div' . $this->element_class('la-parent-fields') . $la_attr .'>';

        echo '<div class="la-field-upload">';
        echo '<input type="text" name="'. $this->element_name( '[image]' ) .'" value="'. $this->value['image'] .'" class="la-child-field"/>';
        echo '<a href="#" class="button la-add" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a>';
        echo '</div>';

        // background attributes
        echo '<fieldset>';
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'type'            => 'select',
            'name'            => $this->element_name( '[repeat]' ),
            'options'         => array(
                'repeat'        => 'repeat',
                'repeat-x'      => 'repeat-x',
                'repeat-y'      => 'repeat-y',
                'no-repeat'     => 'no-repeat',
                'inherit'       => 'inherit',
            ),
            'attributes'      => array(
                'data-atts'     => 'repeat',
            ),
            'class'         => 'la-child-field',
            'value'           => $this->value['repeat']
        ) );
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'type'            => 'select',
            'name'            => $this->element_name( '[position]' ),
            'options'         => array(
                'left top'      => 'left top',
                'left center'   => 'left center',
                'left bottom'   => 'left bottom',
                'right top'     => 'right top',
                'right center'  => 'right center',
                'right bottom'  => 'right bottom',
                'center top'    => 'center top',
                'center center' => 'center center',
                'center bottom' => 'center bottom'
            ),
            'attributes'      => array(
                'data-atts'     => 'position',
            ),
            'class'         => 'la-child-field',
            'value'           => $this->value['position']
        ) );
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'type'            => 'select',
            'name'            => $this->element_name( '[attachment]' ),
            'options'         => array(
                ''              => 'scroll',
                'fixed'         => 'fixed',
            ),
            'attributes'      => array(
                'data-atts'     => 'attachment',
            ),
            'class'         => 'la-child-field',
            'value'           => $this->value['attachment']
        ) );
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'type'            => 'select',
            'name'            => $this->element_name( '[size]' ),
            'options'         => array(
                ''              => 'size',
                'cover'         => 'cover',
                'contain'       => 'contain',
                'inherit'       => 'inherit',
                'initial'       => 'initial',
            ),
            'attributes'      => array(
                'data-atts'     => 'size',
            ),
            'class'         => 'la-child-field',
            'value'           => $this->value['size']
        ) );
        echo la_fw_add_element( array(
            'pseudo'          => true,
            'id'              => $this->field['id'].'_color',
            'type'            => 'color_picker',
            'name'            => $this->element_name('[color]'),
            'attributes'      => array(
                'data-atts'     => 'bgcolor',
            ),
            'class'         => 'la-child-field',
            'value'           => $this->value['color'],
            'default'         => ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
            'rgba'            => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
        ) );
        echo '</fieldset>';

        echo '</div>';

        echo $this->element_after();

    }
}