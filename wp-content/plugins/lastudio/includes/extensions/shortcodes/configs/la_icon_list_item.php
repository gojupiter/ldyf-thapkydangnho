<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

return apply_filters(
	'LaStudio/shortcodes/configs',
	array(
		'name'			=> __('Icon', 'lastudio'),
		'base'			=> 'la_icon_list_item',
		'icon'          => 'la-wpb-icon la_icon_list_item',
		'category'  	=> __('La Studio', 'lastudio'),
		'description' 	=> __('Displays the list icon','lastudio'),
		'as_child'         => array('only' => 'la_icon_list'),
		'content_element'   => true,
		'params' 		=> array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Icon library', 'lastudio' ),
				'value' => array(
					__( 'Font Awesome', 'lastudio' ) => 'fontawesome',
					__( 'LaStudio Icon Outline', 'lastudio' ) => 'la_icon_outline',
				),
				'param_name' => 'icon_type',
				'description' => __( 'Select icon library.', 'lastudio' )
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'lastudio' ),
				'param_name' => 'icon_fontawesome',
				'settings' => array(
					'emptyIcon' => true,
					'iconsPerPage' => 20,
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				)
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'lastudio' ),
				'param_name' => 'icon_la_icon_outline',
				'settings' => array(
					'emptyIcon' => true,
					'type' => 'la_icon_outline',
					'iconsPerPage' => 20,
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'la_icon_outline',
				)
			),
			array(
				'type' => 'hidden',
				'heading' => __('Icon', 'lastudio'),
				'param_name' => 'icon'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Icon Color', 'lastudio'),
				'param_name' => 'icon_color'
			),
			array(
				"type" => "textfield",
				"heading" => __("Content", 'lastudio'),
				"param_name" => "content",
				"admin_label" => true
			),
			LaStudio_Shortcodes_Helper::fieldExtraClass()
		),
	),
    'la_icon_list_item'
);