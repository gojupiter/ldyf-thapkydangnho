<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'checkbox',
        'heading' => __('Select element to appear', 'lastudio'),
        'param_name' => 'advanced_opts',
        'value' => array(
            __('Tags','lastudio') => 'tag',
            __('Client','lastudio') => 'client',
            __('Category','lastudio') => 'category',
            __('Date','lastudio') => 'date',
            __('Share','lastudio') => 'share'
        )
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'lastudio'),
        'param_name' 	=> 'tag_label',
        'value' 		=> 'Tags',
        'group' 		=> 'Tags'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'lastudio'),
        'param_name' 	=> 'client_label',
        'group' 		=> 'Client',
        'value' 		=> 'Client'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Value', 'lastudio'),
        'param_name' 	=> 'client_value',
        'group' 		=> 'Client'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'lastudio'),
        'param_name' 	=> 'category_label',
        'group' 		=> 'Category',
        'value' 		=> 'Category'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'lastudio'),
        'param_name' 	=> 'date_label',
        'group' 		=> 'Date',
        'value' 		=> 'Date'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Value', 'lastudio'),
        'param_name' 	=> 'date_value',
        'group' 		=> 'Date'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'lastudio'),
        'param_name' 	=> 'share_label',
        'group' 		=> 'Share',
        'value' 		=> 'SHARE'
    ),
    LaStudio_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Portfolio Information', 'lastudio'),
        'base'			=> 'la_portfolio_info',
        'icon'          => 'la-wpb-icon la_portfolio_info',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display portfolio information with la-studio themes style.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_portfolio_info'
);