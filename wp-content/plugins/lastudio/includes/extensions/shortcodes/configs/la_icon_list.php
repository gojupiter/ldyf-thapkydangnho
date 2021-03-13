<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( !class_exists( 'WPBakeryShortCode_la_icon_list' ) ) {
	class WPBakeryShortCode_la_icon_list extends WPBakeryShortCodesContainer{

	}
}

return apply_filters(
	'LaStudio/shortcodes/configs',
	array(
		'name'			=> __('Icon Lists', 'lastudio'),
		'base'			=> 'la_icon_list',
		'icon'          => 'la-wpb-icon la_icon_list',
		'category'  	=> __('La Studio', 'lastudio'),
		'description' 	=> __('Displays the list icon','lastudio'),
		'as_parent'         => array('only' => 'la_icon_list_item'),
		'content_element'   => true,
		'is_container'      => false,
		'show_settings_on_create' => false,
		'params' 		=> array(
			LaStudio_Shortcodes_Helper::fieldExtraClass()
		),
		'js_view' => 'VcColumnView',
		'html_template' => plugin_dir_path( dirname(__FILE__) ) . 'templates/la_icon_list.php'
	),
    'la_icon_list'
);