<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'autocomplete',
        'heading' => __( 'Select identificator', 'lastudio' ),
        'param_name' => 'product_id',
        'description' => __( 'Input product ID or product SKU or product title to see suggestions', 'lastudio' ),
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'heading'       => __('Position', 'lastudio'),
        'param_name'    => 'position',
        'value'         => array(
            __('Top', 'lastudio')      => 'top',
            __('Right', 'lastudio')    => 'right',
            __('Bottom', 'lastudio')   => 'bottom',
            __('Left', 'lastudio')     => 'left'
        )
    ),
    array(
        'type'          => 'textfield',
        'heading'       => __('Left', 'lastudio'),
        'param_name'    => 'left'
    ),
    array(
        'type'          => 'textfield',
        'heading'       => __('Top', 'lastudio'),
        'param_name'    => 'top'
    ),
    array(
        'type'			=> 'textfield',
        'heading'		=> __('Title', 'lastudio'),
        'param_name'	=> 'title'
    ),
    array(
        'type'          => 'textarea_html',
        'heading'       => __('Content', 'lastudio'),
        'param_name'    => 'content'
    )
);


return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'                      => __('LA Hotspot', 'lastudio'),
        'base'                      => 'la_hotspot',
        'allowed_container_element' => 'vc_row',
        'content_element'           => false,
        'params' 		            => $shortcode_params
    ),
    'la_hotspot'
);