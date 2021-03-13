<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Design','lastudio'),
        'param_name' => 'style',
        'value' => array(
            __('Style 01','lastudio') => '1',
            __('Style 02','lastudio') => '2',
            __('Style 03','lastudio') => '3',
            __('Style 04','lastudio') => '4',
            __('Style 05','lastudio') => '5',
            __('Style 06','lastudio') => '6',
            __('Style 07','lastudio') => '7',
            __('Style 08','lastudio') => '8',
            __('Style 09','lastudio') => '9',
            __('Style 10','lastudio') => '10',
        ),
        'std' => '1',
        'admin_label' => true
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Choose member', 'lastudio' ),
        'param_name' => 'ids',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 1,
            'auto_focus'     => true,
            'display_inline' => true
        ),
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'lastudio' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' )
    ),
    LaStudio_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'lastudio')
    )),
    LaStudio_Shortcodes_Helper::getParamItemSpace(array(
        'std' => 'default'
    )),
    LaStudio_Shortcodes_Helper::fieldElementID(array(
        'param_name' 	=> 'el_id'
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass()
);

$carousel = LaStudio_Shortcodes_Helper::paramCarouselShortCode(false);
$slides_column_idx = LaStudio_Shortcodes_Helper::getParamIndex( $carousel, 'slides_column');
if($slides_column_idx){
    unset($carousel[$slides_column_idx]);
}

$shortcode_params = array_merge( $shortcode_params, $carousel);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Testimonials', 'lastudio'),
        'base'			=> 'la_testimonial',
        'icon'          => 'la-wpb-icon la_testimonial',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display the testimonial','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_testimonial'
);