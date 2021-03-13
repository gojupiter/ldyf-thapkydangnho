<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'style',
        'value' => array(
            __('Default','lastudio') => 'default',
            __('Circle','lastudio') => 'circle',
            __('Square','lastudio') => 'square',
            __('Round','lastudio') => 'round',
        ),
        'default' => 'default'
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable Custom Items', 'lastudio' ),
        'param_name' => 'enable_custom_items',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' )
    ),
    array(
        'type'          => 'param_group',
        'heading'       => __( 'Add Item', 'lastudio' ),
        'param_name'    => 'social_items',
        'params'        => array(
            array(
                'type'          => 'textfield',
                'heading'       => __('Title', 'lastudio'),
                'param_name'    => 'title',
                'admin_label'   => true,
                'value'         => ''
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_fontawesome',
                'value' => 'fa fa-facebook',
                'settings' => array(
                    'emptyIcon' => false,
                    'iconsPerPage' => 30
                )
            ),
            array(
                'type'          => 'textfield',
                'heading'       => __('Link', 'lastudio'),
                'param_name'    => 'link',
                'admin_label'   => true,
                'value'         => '#'
            )
        ),
        'dependency' => array(
            'element' => 'enable_custom_items',
            'value' => 'yes',
        )
    ),
    LaStudio_Shortcodes_Helper::fieldExtraClass(),
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Social Media Link', 'lastudio'),
        'base'			=> 'la_social_link',
        'icon'          => 'la_social_link fa fa-share-alt',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display Social Media Link.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_social_link'
);