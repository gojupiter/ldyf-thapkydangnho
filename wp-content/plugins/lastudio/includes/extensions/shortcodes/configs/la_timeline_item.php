<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

return apply_filters(
	'LaStudio/shortcodes/configs',
	array(
		'name'			=> __('Timeline', 'lastudio'),
		'base'			=> 'la_timeline_item',
		'icon'          => 'la-wpb-icon la_timeline_item',
		'category'  	=> __('La Studio', 'lastudio'),
		'description' 	=> __('Displays the timeline block','lastudio'),
		'as_child'         => array('only' => 'la_timeline'),
		'content_element'   => true,
		'params' 		=> array(
			array(
				"type" => "textfield",
				"heading" => __("Title", 'lastudio'),
				"param_name" => "title",
				"admin_label" => true
			),
			array(
				"type" => "textfield",
				"heading" => __("Sub-Title", 'lastudio'),
				"param_name" => "sub_title",
				"admin_label" => true
			),
			array(
				"type" => "textarea_html",
				"heading" => __("Content", 'lastudio'),
				"param_name" => "content",
			),
			array(
				"type" => "dropdown",
				"heading" => __("Apply link to:", 'lastudio'),
				"param_name" => "time_link_apply",
				"value" => array(
					__("None",'lastudio') => "",
					__("Complete box",'lastudio') => "box",
					__("Box Title",'lastudio') => "title",
					__("Display Read More",'lastudio') => "more",
				),
				"description" => __("Select the element for link.", 'lastudio')
			),
			array(
				"type" => "vc_link",
				"heading" => __("Add Link", 'lastudio'),
				"param_name" => "time_link",
				"dependency" => Array("element" => "time_link_apply","value" => array("more","title","box")),
				"description" => __("Provide the link that will be applied to this timeline.", 'lastudio')
			),
			array(
				"type" => "textfield",
				"heading" => __("Read More Text", 'lastudio'),
				"param_name" => "time_read_text",
				"value" => "Read More",
				"description" => __("Customize the read more text.", 'lastudio'),
				"dependency" => Array("element" => "time_link_apply","value" => array("more")),
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Dot Color', 'lastudio'),
				'param_name' => 'dot_color'
			),
			LaStudio_Shortcodes_Helper::fieldCssAnimation(),
			LaStudio_Shortcodes_Helper::fieldExtraClass()
		),
	),
    'la_timeline_item'
);