<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


$icon_type = LaStudio_Shortcodes_Helper::fieldIconType(array(
	'element' => 'icon_pos',
	'value'	=> array('top','left','right')
));

$field_icon_settings = array(
	array(
		'type'	=> 'dropdown',
		'heading'	=> __('Icon Position', 'lastudio'),
		'param_name' => 'icon_pos',
		'value'	=> array(
			__('No display', 'lastudio')	=> 'none',
			__('Icon at Top', 'lastudio') => 'top',
			__('Icon at Left', 'lastudio') => 'left',
			__('Icon at Right', 'lastudio') => 'right'
		),
		'std' => 'top',
		'description' => __('Select icon position. Icon box style will be changed according to the icon position.', 'lastudio')
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
		'group' => __('Icon Settings', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
	),

	array(
		'type' => 'la_number',
		'heading' => __('Icon Size', 'lastudio'),
		'param_name' => 'icon_size',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
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
		'group' 	=> __('Icon Settings', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Color', 'lastudio'),
		'param_name'=> 'icon_color',
		'group' 	=> __('Icon Settings', 'lastudio'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
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
		'heading' 	=> __('Icon Background Color #2', 'lastudio'),
		'param_name'=> 'icon_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Icon Settings', 'lastudio')
	),
);

$field_icon_settings = array_merge($field_icon_settings, $icon_type);

$shortcode_params = array(
	array(
		'type' => 'textfield',
		'heading' => __('Title', 'lastudio'),
		'param_name' => 'title',
		'admin_label' => true,
	),
	array(
		'type' => 'la_number',
		'heading' => __('Value', 'lastudio'),
		'param_name' => 'value',
		'value' => 1250,
		'min' => 0,
		'suffix' => '',
		'description' => __('Enter number for counter without any special character. You may enter a decimal number. Eg 12.76', 'lastudio')
	),
	array(
		'type' => 'textfield',
		'heading' => __('Value Prefix', 'lastudio'),
		'param_name' => 'prefix'
	),
	array(
		'type' => 'textfield',
		'heading' => __('Value Suffix', 'lastudio'),
		'param_name' => 'suffix'
	),
	array(
		'type'  => 'dropdown',
		'heading' => __('Separator','lastudio'),
		'param_name'    => 'spacer',
		'value' => array(
			__('No Separator','lastudio')	=>	'none',
			__('Line','lastudio')	        =>	'line',
		),
		'default' => 'none',
	),
	array(
		'type'  => 'dropdown',
		'heading' => __('Separator Position','lastudio'),
		'param_name'    => 'spacer_position',
		'value' => array(
			__('Top','lastudio')		 	=>	'top',
			__('Bottom','lastudio')		=>	'bottom',
			__('Between Value & Title','lastudio')	=>	'middle'
		),
		'default' => 'top',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type'      => 'dropdown',
		'heading'   => __('Line Style', 'lastudio'),
		'param_name'    => 'line_style',
		'value'         => array(
			__('Solid', 'lastudio') => 'solid',
			__('Dashed', 'lastudio') => 'dashed',
			__('Dotted', 'lastudio') => 'dotted',
			__('Double', 'lastudio') => 'double'
		),
		'default' => 'solid',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type' 			=> 'la_column',
		'heading' 		=> __('Line Width', 'lastudio'),
		'param_name' 	=> 'line_width',
		'unit'			=> 'px',
		'media'			=> array(
			'xlg'	=> '',
			'lg'	=> '',
			'md'	=> '',
			'sm'	=> '',
			'xs'	=> '',
			'mb'	=> ''
		),
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type' => 'la_number',
		'heading' => __('Line Height', 'lastudio'),
		'param_name' => 'line_height',
		'value' => 1,
		'min' => 1,
		'suffix' => 'px',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type' => 'colorpicker',
		'heading' => __('Line Color', 'lastudio'),
		'param_name' => 'line_color',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	LaStudio_Shortcodes_Helper::fieldElementID(array(
		'param_name' 	=> 'el_id'
	)),
	LaStudio_Shortcodes_Helper::fieldExtraClass(),
	LaStudio_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra class name for value', 'lastudio'),
		'param_name' 	=> 'el_class_value'
	)),
	LaStudio_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra Class name for heading', 'lastudio'),
		'param_name' 	=> 'el_class_heading'
	)),
);

$title_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont();
$value_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('value', __('Value', 'lastudio'));
$shortcode_params = array_merge( $field_icon_settings, $shortcode_params, $value_google_font_param, $title_google_font_param, array(LaStudio_Shortcodes_Helper::fieldCssClass()) );

return apply_filters(
	'LaStudio/shortcodes/configs',
	array(
		'name'			=> __('Stats Counter', 'lastudio'),
		'base'			=> 'la_stats_counter',
		'icon'          => 'la-wpb-icon la_stats_counter',
		'category'  	=> __('La Studio', 'lastudio'),
		'description' 	=> __('Your milestones, achievements, etc.','lastudio'),
		'params' 		=> $shortcode_params
	),
    'la_stats_counter'
);