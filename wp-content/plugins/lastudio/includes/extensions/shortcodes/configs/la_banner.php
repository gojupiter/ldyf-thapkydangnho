<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'attach_image',
        'heading' => __('Upload the banner image', 'lastudio'),
        'param_name' => 'banner_id'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Height Mode','lastudio'),
        'param_name' => 'height_mode',
        'value' => array(
            __('1:1','lastudio') => '1-1',
            __('Original','lastudio') => 'original',
            __('4:3','lastudio') => '4-3',
            __('3:4','lastudio') => '3-4',
            __('16:9','lastudio') => '16-9',
            __('9:16','lastudio') => '9-16',
            __('Custom','lastudio') => 'custom',
        ),
        'std' => 'original',
        'description' => __('Sizing proportions for height and width. Select "Original" to scale image without cropping.', 'lastudio')
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Height', 'lastudio'),
        'param_name' 	=> 'height',
        'value'			=> '',
        'description' 	=> __('Enter custom height.', 'lastudio'),
        'dependency' => array(
            'element'   => 'height_mode',
            'value'     => array('custom')
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Design','lastudio'),
        'param_name' => 'style',
        'value' => array(
            __('Style 1','lastudio') => '1',
            __('Style 2','lastudio') => '2',
            __('Style 3','lastudio') => '3',
            __('Style 4','lastudio') => '4',
            __('Style 5','lastudio') => '5',
            __('Style 6','lastudio') => '6',
            __('Style 7','lastudio') => '7',
            __('Style 8','lastudio') => '8',
            __('Style 9','lastudio') => '9'
        ),
        'std' => '1'
    ),

    array(
        'type' => 'vc_link',
        'heading' => __('Banner Link', 'lastudio'),
        'param_name' => 'banner_link',
        'description' => __('Add link / select existing page to link to this banner', 'lastudio')
    ),


    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 1', 'lastudio' ),
        'param_name' => 'title_1',
        'admin_label' => true
    ),

    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 2', 'lastudio' ),
        'param_name' => 'title_2',
        'admin_label' => true,
        'dependency' => array(
            'element' => 'style',
            'value' => array('1','2','5', '6', '7', '8', '9')
        ),
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 3', 'lastudio' ),
        'param_name' => 'title_3',
        'admin_label' => true,
        'dependency' => array(
            'element' => 'style',
            'value' => array('9')
        ),
    ),

    LaStudio_Shortcodes_Helper::fieldElementID(array(
        'param_name' 	=> 'el_id'
    )),

    LaStudio_Shortcodes_Helper::fieldExtraClass(),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 1', 'lastudio'),
        'param_name' 	=> 'el_class1',
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 2', 'lastudio'),
        'param_name' 	=> 'el_class2',
        'dependency' => array(
            'element' => 'style',
            'value' => array('1','2','5', '6', '7', '8', '9')
        )
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 3', 'lastudio'),
        'param_name' 	=> 'el_class3',
        'dependency' => array(
            'element' => 'style',
            'value' => array('9')
        )
    )),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'overlay_bg_color',
        'heading' 		=> __('Overlay background color', 'lastudio'),
        'group' 		=> 'Design'
    ),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'overlay_hover_bg_color',
        'heading' 		=> __('Overlay hover background color', 'lastudio'),
        'group' 		=> 'Design'
    )
);

$param_fonts_title1 = LaStudio_Shortcodes_Helper::fieldTitleGFont('title_1', __('Title 1', 'lastudio'));
$param_fonts_title2 = LaStudio_Shortcodes_Helper::fieldTitleGFont('title_2', __('Title 2', 'lastudio'), array(
    'element' => 'style',
    'value' => array('1','2','5', '6', '7', '8', '9')
));
$param_fonts_title3 = LaStudio_Shortcodes_Helper::fieldTitleGFont('title_3', __('Title 3', 'lastudio'), array(
    'element' => 'style',
    'value' => array('2','9')
));


$shortcode_params = array_merge( $shortcode_params, $param_fonts_title1, $param_fonts_title2, $param_fonts_title3);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Banner Box', 'lastudio'),
        'base'			=> 'la_banner',
        'icon'          => 'la-wpb-icon la_banner',
        'category'  	=> __('La Studio', 'lastudio'),
        'description'   => __('Displays the banner image with Information', 'lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_banner'
);