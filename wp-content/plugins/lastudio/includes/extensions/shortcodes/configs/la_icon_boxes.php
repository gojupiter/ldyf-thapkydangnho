<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


$icon_type = LaStudio_Shortcodes_Helper::fieldIconType();
$icon_type[0]['value'][__( 'Custom Number', 'lastudio') ] = 'number';
$icon_type[] = array(
	'type' => 'textfield',
	'heading' => __('Enter the number', 'lastudio'),
	'param_name' => 'custom_number',
	'dependency' => array(
		'element' => 'icon_type',
		'value' => 'number'
	)
);

$shortcode_params = array(
	array(
		'type' => 'textfield',
		'heading' => __('Heading', 'lastudio'),
		'param_name' => 'title',
		'admin_label' => true,
		'description' => __('Provide the title for this icon boxes.', 'lastudio'),
	),
	array(
		'type' => 'textarea_html',
		'heading' => __('Description', 'lastudio'),
		'param_name' => 'content',
		'description' => __('Provide the description for this icon box.', 'lastudio'),
	),
	// Select link option - to box or with read more text
	array(
		'type' 			=> 'dropdown',
		'heading' 		=> __('Apply link to:', 'lastudio'),
		'param_name' 	=> 'read_more',
		'value' 		=> array(
			__('No Link','lastudio') => 'none',
			__('Complete Box','lastudio') => 'box',
			__('Box Title','lastudio') => 'title',
			__('Icon','lastudio') => 'icon',
			__('Read More','lastudio') => 'read_more'
		)
	),
	// Add link to existing content or to another resource
	array(
		'type' 			=> 'vc_link',
		'heading' 		=> __('Add Link', 'lastudio'),
		'param_name' 	=> 'link',
		'description' 	=> __('Add a custom link or select existing page. You can remove existing link as well.', 'lastudio'),
		'dependency' 	=> array(
			'element' 	=> 'read_more',
			'value' 	=> array('box','title','icon', 'read_more')
		)
	),
	array(
		'type'	=> 'dropdown',
		'heading'	=> __('Icon Position', 'lastudio'),
		'param_name' => 'icon_pos',
		'value'	=> array(
			__('Icon at left with heading', 'lastudio') => 'default',
			__('Icon at Right with heading', 'lastudio') => 'heading-right',
			__('Icon at Left', 'lastudio') => 'left',
			__('Icon at Right', 'lastudio') => 'right',
			__('Icon at Top', 'lastudio') => 'top',
			__('No Display', 'lastudio') => 'hidden',
		),
		'std' => 'default',
		'description' => __('Select icon position. Icon box style will be changed according to the icon position.', 'lastudio'),
		'group' => __('Icon Settings', 'lastudio')
	),

	array(
		'type' => 'dropdown',
		'heading' => __('Icon Styles', 'lastudio'),
		'param_name' => 'icon_style',
		'description' => __('We have given four quick preset if you are in a hurry. Otherwise, create your own with various options.', 'lastudio'),
		'std'	=> 'simple',
		'value' => array(
			__('Simple', 'lastudio') => 'simple',
			__('Circle Background', 'lastudio') => 'circle',
			__('Square Background', 'lastudio') => 'square',
			__('Round Background', 'lastudio') => 'round',
			__('Advanced', 'lastudio') => 'advanced',
		),
		'group' => __('Icon Settings', 'lastudio')
	),

	array(
		'type' => 'la_number',
		'heading' => __('Icon Size', 'lastudio'),
		'param_name' => 'icon_size',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'lastudio')
	),
	array(
		'type' => 'la_number',
		'heading' => __('Icon Box Width', 'lastudio'),
		'param_name' => 'icon_width',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
	),
	array(
		'type' => 'la_number',
		'heading' => __('Icon Padding', 'lastudio'),
		'param_name' => 'icon_padding',
		'value' => 0,
		'min' => 0,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('advanced')
		)
	),
	array(
		'type' 		=> 'dropdown',
		'heading' 	=> __('Icon Color Type', 'lastudio'),
		'param_name'=> 'icon_color_type',
		'std'		=> 'simple',
		'value' 	=> array(
			__('Simple', 'lastudio') => 'simple',
			__('Gradient', 'lastudio') => 'gradient',
		),
		'group' 	=> __('Icon Settings', 'lastudio')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Color', 'lastudio'),
		'param_name'=> 'icon_color',
		'group' 	=> __('Icon Settings', 'lastudio')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Color', 'lastudio'),
		'param_name'=> 'icon_h_color',
		'group' 	=> __('Hover Style', 'lastudio')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Color #2', 'lastudio'),
		'param_name'=> 'icon_color2',
		'group' 	=> __('Icon Settings', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_color_type',
			'value' 	=> array('gradient')
		)
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Color #2', 'lastudio'),
		'param_name'=> 'icon_h_color2',
		'dependency' => array(
			'element' 	=> 'icon_color_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Hover Style', 'lastudio')
	),

	array(
		'type' 		=> 'dropdown',
		'heading' 	=> __('Icon Background Type', 'lastudio'),
		'param_name'=> 'icon_bg_type',
		'std'		=> 'simple',
		'value' 	=> array(
			__('Simple', 'lastudio') => 'simple',
			__('Gradient', 'lastudio') => 'gradient',
		),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
		'group' 	=> __('Icon Settings', 'lastudio')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Background Color', 'lastudio'),
		'param_name'=> 'icon_bg',
		'dependency'=> array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
		'group' 	=> __('Icon Settings', 'lastudio')
	),
	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Background Color', 'lastudio'),
		'param_name'=> 'icon_h_bg',
		'dependency'=> array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
		'group' 	=> __('Hover Style', 'lastudio')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Background Color #2', 'lastudio'),
		'param_name'=> 'icon_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Icon Settings', 'lastudio')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Background Color #2', 'lastudio'),
		'param_name'=> 'icon_h_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Hover Style', 'lastudio')
	),

	array(
		'type' => 'dropdown',
		'heading' => __('Icon Border Style', 'lastudio'),
		'param_name' => 'icon_border_style',
		'value' => array(
			__('None', 'lastudio') => '',
			__('Solid', 'lastudio') => 'solid',
			__('Dashed', 'lastudio') => 'dashed',
			__('Dotted', 'lastudio') => 'dotted',
			__('Double', 'lastudio') => 'double',
		),
		'group' => __('Icon Settings', 'lastudio')
	),
	array(
		'type' => 'la_number',
		'heading' => __('Icon Border Width', 'lastudio'),
		'param_name' => 'icon_border_width',
		'value' => 1,
		'min' => 1,
		'max' => 10,
		'suffix' => 'px',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => __('Icon Settings', 'lastudio')
	),
	array(
		'type' => 'colorpicker',
		'heading' => __('Icon Border Color', 'lastudio'),
		'param_name' => 'icon_border_color',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => __('Icon Settings', 'lastudio')
	),

	array(
		'type' => 'colorpicker',
		'heading' => __('Icon Hover Border Color', 'lastudio'),
		'param_name' => 'icon_h_border_color',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => __('Hover Style', 'lastudio')
	),

	array(
		'type' => 'la_number',
		'heading' => __('Icon Border Radius', 'lastudio'),
		'param_name' => 'icon_border_radius',
		'value' => 500,
		'min' => 1,
		'suffix' => 'px',
		'description' => __('0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('advanced')
		),
		'group' => __('Icon Settings', 'lastudio')
	),



	LaStudio_Shortcodes_Helper::fieldExtraClass(),
	LaStudio_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra Class for heading', 'lastudio'),
		'param_name' 	=> 'title_class',
	)),
	LaStudio_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra Class for description', 'lastudio'),
		'param_name' 	=> 'desc_class',
	))
);

$title_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont();
$desc_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('desc', __('Description', 'lastudio'));

$shortcode_params = array_merge( $icon_type, $shortcode_params, $title_google_font_param, $desc_google_font_param, array(LaStudio_Shortcodes_Helper::fieldCssClass()) );

return apply_filters(
	'LaStudio/shortcodes/configs',
	array(
		'name'			=> __('Icon boxes', 'lastudio'),
		'base'			=> 'la_icon_boxes',
		'icon'          => 'la-wpb-icon la_icon_boxes',
		'category'  	=> __('La Studio', 'lastudio'),
		'description' 	=> __('Adds icon box with custom font icon','lastudio'),
		'params' 		=> $shortcode_params
	),
    'la_icon_boxes'
);