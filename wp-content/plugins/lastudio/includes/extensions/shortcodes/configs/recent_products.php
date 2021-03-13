<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(

    array(
        'type'          => 'hidden',
        'param_name'    => 'scenario',
        'value'         => 'recent_products',
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'autocomplete',
        'heading' => __( 'Categories', 'lastudio' ),
        'param_name' => 'category',
        'settings' => array(
            'multiple' => true,
            'sortable' => true,
        ),
        'save_always' => true,
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Operator','lastudio'),
        'param_name' => 'operator',
        'value' => array(
            __('IN','lastudio') => 'IN',
            __('NOT IN','lastudio') => 'NOT IN',
            __('AND','lastudio') => 'AND',
        ),
        'std' => 'IN',
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Order by', 'lastudio' ),
        'param_name' => 'orderby',
        'value' => array(
            '',
            __( 'Date', 'lastudio' ) => 'date',
            __( 'Menu order', 'lastudio' ) => 'menu_order',
            __( 'Random', 'lastudio' ) => 'rand',
            __( 'Popularity', 'lastudio' ) => 'popularity',
            __( 'Rating', 'lastudio' ) => 'rating',
            __( 'Title', 'lastudio' ) => 'title'
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' 		=> __('Data Setting', 'lastudio')
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
        'group' 		=> __('Data Setting', 'lastudio')
    ),


    array(
        'type' => 'la_number',
        'heading' => __('Total items', 'lastudio'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'lastudio'),
        'param_name' => 'per_page',
        'value' => 12,
        'min' => -1,
        'max' => 1000,
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'lastudio'),
        'param_name' => 'paged',
        'value' => '1',
        'group' 		=> __('Data Setting', 'lastudio')
    )
);


$carousel = LaStudio_Shortcodes_Helper::paramCarouselShortCode(false);
$layout_setting = LaStudio_Shortcodes_Helper::fieldWooCommerceLayoutSetting();
$slides_column_idx = LaStudio_Shortcodes_Helper::getParamIndex( $carousel, 'slides_column');

if($slides_column_idx){
    unset($carousel[$slides_column_idx]);
}

$shortcode_params = array_merge( $shortcode_params, $layout_setting, $carousel);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Recent products', 'lastudio'),
        'base'			=> 'recent_products',
        'icon'          => 'icon-wpb-woocommerce',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display Recent Products.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'recent_products'
);