<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'textfield',
        'heading' => __( 'Text', 'lastudio' ),
        'param_name' => 'title',
        'value' => __( 'Text on the button', 'lastudio' ),
        'admin_label' => true
    ),
    array(
        'type' => 'vc_link',
        'heading' => __( 'URL (Link)', 'lastudio' ),
        'param_name' => 'link',
        'description' => __( 'Add link to button.', 'lastudio' )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Style', 'lastudio' ),
        'description' => __( 'Select button display style.', 'lastudio' ),
        'param_name' => 'style',
        'value' => array(
            __( 'Flat', 'lastudio' ) => 'flat',
            __( 'Outline', 'lastudio' ) => 'outline'
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Border width', 'lastudio' ),
        'description' => __( 'Select border width.', 'lastudio' ),
        'param_name' => 'border_width',
        'value' => array(
            __( '1px', 'lastudio' ) => '1',
            __( '2px', 'lastudio' ) => '2',
            __( '3px', 'lastudio' ) => '3'
        ),
        'dependency' => array(
            'element' => 'style',
            'value' => 'outline'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Shape', 'lastudio' ),
        'description' => __( 'Select button shape.', 'lastudio' ),
        'param_name' => 'shape',
        'value' => array(
            __( 'Rounded', 'lastudio' ) => 'rounded',
            __( 'Square', 'lastudio' ) => 'square',
            __( 'Round', 'lastudio' ) => 'round'
        ),
        'std' => 'square'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Color', 'lastudio' ),
        'param_name' => 'color',
        'description' => __( 'Select button color.', 'lastudio' ),
        'value' => array(
                __( 'Black', 'lastudio' ) => 'black',
                __( 'Primary', 'lastudio' ) => 'primary',
                __( 'Three', 'lastudio' ) => 'three',
                __( 'White', 'lastudio' ) => 'white',
                __( 'White 2', 'lastudio' ) => 'white2',
                __( 'Gray', 'lastudio' ) => 'gray'
        ),
        'std' => 'black'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Size', 'lastudio' ),
        'param_name' => 'size',
        'description' => __( 'Select button display size.', 'lastudio' ),
        'std' => 'md',
        'value' => array(
            'Mini' => 'xs',
            'Small' => 'sm',
            'Normal' => 'md',
            'Large' => 'lg',
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Alignment', 'lastudio' ),
        'param_name' => 'align',
        'description' => __( 'Select button alignment.', 'lastudio' ),
        'value' => array(
            __( 'Inline', 'lastudio' ) => 'inline',
            __( 'Left', 'lastudio' ) => 'left',
            __( 'Right', 'lastudio' ) => 'right',
            __( 'Center', 'lastudio' ) => 'center',
        ),
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Extra class name', 'lastudio' ),
        'param_name' => 'el_class',
        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'lastudio' ),
    ),
);


return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('La Button', 'lastudio'),
        'base'			=> 'la_btn',
        'icon'          => 'icon-wpb-ui-button',
        'category'  	=> __('La Studio', 'lastudio'),
        'description'   => __('Eye catching button', 'lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_btn'
);