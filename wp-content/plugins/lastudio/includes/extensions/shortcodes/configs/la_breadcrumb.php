<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    LaStudio_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Breadcrumbs', 'lastudio'),
        'base'			=> 'la_breadcrumb',
        'icon'          => 'la-wpb-icon la_breadcrumb',
        'category'  	=> __('La Studio', 'lastudio'),
        'description'   => __('Display the breadcrumbs', 'lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_breadcrumb'
);