<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' 			=> 'la_column',
        'heading' 		=> __('Space Height', 'lastudio'),
        'admin_label'   => true,
        'param_name' 	=> 'height',
        'unit'			=> 'px',
        'media'			=> array(
            'xlg'	=> '',
            'lg'	=> '',
            'md'	=> '',
            'sm'	=> '',
            'xs'	=> '',
            'mb'	=> ''
        )
    ),
    LaStudio_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('LaStudio Space', 'lastudio'),
        'base'			=> 'la_divider',
        'icon'          => 'la-wpb-icon la_divider',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Blank space with custom height.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_divider'
);