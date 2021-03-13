<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type'          => 'checkbox',
        'heading'       => __('Enable Product Viewer', 'lastudio'),
        'param_name'    => 'product_viewer',
        'value'         => array(
            __('Yes', 'lastudio') => 'true'
        )
    ),
    array(
        'type'          => 'attach_image',
        'heading'       => __('Image', 'lastudio'),
        'param_name'    => 'image',
        'description'   => __('Choose your image that will show the hotspots. <br/> You can then click on the image in the preview area to add your hotspots in the desired locations.', 'lastudio')
    ),
    array(
        'type'          => 'la_hotspot_image_preview',
        'heading'       => __('Preview', 'lastudio'),
        'param_name'    => 'preview',
        'description'   => __("Click to add - Drag to move - Edit content below<br/> Note: this preview will not reflect hotspot style choices or show tooltips. <br/>This is only used as a visual guide for positioning.", 'lastudio')
    ),
    array(
        'type'          => 'textarea_html',
        'heading'       => __('Hotspots', 'lastudio'),
        'param_name'    => 'content'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Extra Class name', 'lastudio'),
        'group'         => __('Style', 'lastudio'),
        'param_name' 	=> 'el_class',
        'description' 	=> __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'lastudio')
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'lastudio'),
        'heading'       => __('Color', 'lastudio'),
        'admin_label'   => true,
        'param_name'    => 'color',
        'default'       => 'primary',
        'description'   => __('Choose the color which the hotspot will use', 'lastudio'),
        'value'         => array(
            'Primary' => 'primary'
        )
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'lastudio'),
        'heading'       => __('Hotspot Icon', 'lastudio'),
        'description'   => __('The icon that will be shown on the hotspots', 'lastudio'),
        'param_name'    => 'hotspot_icon',
        'admin_label'   => true,
        'value'         => array(
            __('Plus Sign', 'lastudio') => 'plus_sign',
            __('Numerical', 'lastudio') => 'numerical',
            __('Custom Title', 'lastudio') => 'custom_title',
        )
    ),
    array(
        'type'			=> 'textfield',
        'save_always'   => true,
        'group'         => __('Style', 'lastudio'),
        'heading'       => __('Start number', 'lastudio'),
        'description'   => __('The number that will begin on the hotspots', 'lastudio'),
        'param_name'    => 'start_number',
        'value' 		=> '1',
        'dependency'	=> array(
            'element' => 'hotspot_icon',
            'value' => 'numerical'
        )
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'lastudio'),
        'heading'       => __('Tooltip Functionality', 'lastudio'),
        'param_name'    => 'tooltip',
        'description'   => __('Select how you want your tooltips to display to the user', 'lastudio'),
        'value'         => array(
            __('Show On Hover', 'lastudio')    => 'hover',
            __('Show On Click', 'lastudio')    => 'click',
            __('Always Show', 'lastudio')      => 'always_show'
        )
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'lastudio'),
        'heading'       => __('Tooltip Shadow', 'lastudio'),
        'param_name'    => 'tooltip_shadow',
        'description'   => __('Select the shadow size for your tooltip', 'lastudio'),
        'value'         => array(
            __('None', 'lastudio')         => 'none',
            __('Small Depth', 'lastudio')  => 'small_depth',
            __('Medium Depth', 'lastudio') => 'medium_depth',
            __('Large Depth', 'lastudio')  => 'large_depth'
        )
    ),
    array(
        'type'          => 'checkbox',
        'heading'       => __('Enable Animation', 'lastudio'),
        'param_name'    => 'animation',
        'group'         => __('Style', 'lastudio'),
        'description'   => __('Turning this on will make your hotspots animate in when the user scrolls to the element', 'lastudio'),
        'value'         => array(
            __('Yes, please', 'lastudio') => 'true'
        )
    )
);


return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Image With Hotspots', 'lastudio'),
        'base'			=> 'la_image_with_hotspots',
        'icon'          => 'icon-wpb-single-image',
        'category'  	=> __('La Studio', 'lastudio'),
        'description'   => __('Add Hotspots On Your Image', 'lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_image_with_hotspots'
);