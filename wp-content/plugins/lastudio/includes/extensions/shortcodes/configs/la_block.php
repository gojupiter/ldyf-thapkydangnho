<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$shortcode_params = array(
    array(
        'type' => 'autocomplete',
        'heading' => __( 'Select identificator', 'lastudio' ),
        'param_name' => 'id',
        'description' => __( 'Input block ID or block title to see suggestions', 'lastudio' ),
    ),
    array(
        'type' => 'hidden',
        'param_name' => 'name',
    ),
    LaStudio_Shortcodes_Helper::fieldExtraClass(),
);


return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('La Custom Block', 'lastudio'),
        'base'			=> 'la_block',
        'icon'          => 'la-wpb-icon la_block',
        'category'  	=> __('La Studio', 'lastudio'),
        'description'   => __('Displays the custom block', 'lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_block'
);