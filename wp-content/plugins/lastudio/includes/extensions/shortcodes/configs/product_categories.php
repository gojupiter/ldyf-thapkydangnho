<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'autocomplete',
        'heading' => __( 'Categories', 'lastudio' ),
        'param_name' => 'ids',
        'settings' => array(
            'multiple' => true,
            'sortable' => true,
        ),
        'save_always' => true,
        'description' => __( 'List of product categories', 'lastudio' ),
    ),
    array(
        'type' => 'la_number',
        'heading' => __( 'Maximum Category will be displayed', 'lastudio' ),
        'param_name' => 'number',
        'description' => __( 'The `number` field is used to display the number of products.', 'lastudio' ),
        'min' => 0,
        'max' => 50,
        'default' => 0
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Order by', 'lastudio' ),
        'param_name' => 'orderby',
        'value' => array(
            '',
            __( 'Date', 'lastudio' ) => 'date',
            __( 'ID', 'lastudio' ) => 'ID',
            __( 'Menu order', 'lastudio' ) => 'menu_order',
            __( 'Random', 'lastudio' ) => 'rand',
            __( 'Popularity', 'lastudio' ) => 'popularity',
            __( 'Rating', 'lastudio' ) => 'rating',
            __( 'Title', 'lastudio' ) => 'title'
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Sort order', 'lastudio' ),
        'param_name' => 'order',
        'value' => array(
            '',
            __( 'Descending', 'lastudio' ) => 'DESC',
            __( 'Ascending', 'lastudio' ) => 'ASC',
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
    ),
    array(
        'type' => 'checkbox',
        'heading' => __( 'Hide Empty', 'lastudio' ),
        'param_name' => 'hide_empty',
        'value' => array( __( 'Yes', 'lastudio') => '1' ),
    ),
    LaStudio_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'lastudio'),
        'param_name' 	=> 'columns'
    )),

    LaStudio_Shortcodes_Helper::getParamItemSpace(array(
        'std' => 'default'
    )),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'style',
        'value' => array(
            __('Design 01','lastudio') => '1'
        ),
        'default' => '1'
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'lastudio' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' )
    ),
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
        'name'			=> __('Product Categories', 'lastudio'),
        'base'			=> 'product_categories',
        'icon'          => 'icon-wpb-woocommerce',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display categories','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'product_categories'
);