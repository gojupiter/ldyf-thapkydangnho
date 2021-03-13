<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$shortcode_params = array(
    array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Count', 'lastudio' ),
        'description' => esc_html__( 'Leave empty to display all', 'lastudio' ),
        'param_name' => 'count',
    ),

    array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Link to Single Event Page', 'lastudio' ),
        'param_name' => 'link',
        'value' => array(
            'false' => esc_html__( 'No', 'lastudio' ),
            'true' => esc_html__( 'Yes', 'lastudio' ),
        ),
    ),

    array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Display Past Events', 'lastudio' ),
        'param_name' => 'past',
        'value' => array(
            'false' => esc_html__( 'No', 'lastudio' ),
            'true' => esc_html__( 'Yes', 'lastudio' ),
        ),
    ),

    array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Artist Slug', 'lastudio' ),
        'param_name' => 'artist',
    ),

    LaStudio_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Event List', 'lastudio'),
        'base'			=> 'lastudio_event_list',
        'icon'          => 'fa fa-calendar',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display your event list from the LaStudio Events plugin','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'lastudio_event_list'
);