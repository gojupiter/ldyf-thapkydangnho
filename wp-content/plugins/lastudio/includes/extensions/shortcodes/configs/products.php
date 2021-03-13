<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$attributes_tax = wc_get_attribute_taxonomies();
$attributes = array();
foreach ( $attributes_tax as $attribute ) {
    $attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
}

$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Scenario','lastudio'),
        'param_name' => 'scenario',
        'value' => array(
            __('Recent Products','lastudio') => 'recent_products',
            __('Featured Products','lastudio') => 'featured_products',
            __('Sale Products','lastudio') => 'sale_products',
            __('Best Selling Products','lastudio') => 'best_selling_products',
            __('Specific Categories','lastudio') => 'product_categories',
            __('Specific products','lastudio') => 'products',
            __('Attribute Display','lastudio') => 'product_attribute',
            __('Top Rated Products','lastudio') => 'top_rated_products'
        ),
        'std' => 'recent_products',
        'admin_label' => true,
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
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
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
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'autocomplete',
        'heading' => __( 'Tags IN ( optional )', 'lastudio' ),
        'param_name' => 'tag',
        'settings' => array(
            'multiple' => true,
            'sortable' => true,
        ),
        'save_always' => true,
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Attribute', 'lastudio' ),
        'param_name' => 'attribute',
        'value' => $attributes,
        'save_always' => true,
        'description' => __( 'List of product taxonomy attribute', 'lastudio' ),
        'dependency' => array(
            'element'   => 'scenario',
            'value'     => array('product_attribute')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'checkbox',
        'heading' => __( 'Filter', 'lastudio' ),
        'param_name' => 'filter',
        'value' => array( 'empty' => 'empty' ),
        'save_always' => true,
        'description' => __( 'Taxonomy values', 'lastudio' ),
        'dependency' => array(
            'callback' => 'laWoocommerceProductAttributeFilterDependencyCallback',
        ),
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
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
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
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'autocomplete',
        'heading' => __( 'Products', 'lastudio' ),
        'param_name' => 'ids',
        'settings' => array(
            'multiple' => true,
            'sortable' => true,
            'unique_values' => true
        ),
        'save_always' => true,
        'description' => __( 'Enter List of Products', 'lastudio' ),
        'dependency' => array(
            'element'   => 'scenario',
            'value'     => array( 'products' )
        ),
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
        'param_name' => 'skus',
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'lastudio'),
        'param_name' => 'paged',
        'value' => '1',
        'group' 		=> __('Data Setting', 'lastudio')
    ),
);

$layout_setting = LaStudio_Shortcodes_Helper::fieldWooCommerceLayoutSetting();

$carousel = LaStudio_Shortcodes_Helper::paramCarouselShortCode(false);
$slides_column_idx = LaStudio_Shortcodes_Helper::getParamIndex( $carousel, 'slides_column');
if($slides_column_idx){
    unset($carousel[$slides_column_idx]);
}


$shortcode_params = array_merge( $shortcode_params,$layout_setting,$carousel);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Products', 'lastudio'),
        'base'			=> 'products',
        'icon'          => 'icon-wpb-woocommerce',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Allowing fetch products by ids, SKUs, categories, attributes, and more, replacing the need for multiples shortcodes','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'products'
);