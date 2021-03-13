<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$shortcode_params = array(
    array(
        'type' => 'textfield',
        'heading' => __('Heading', 'lastudio'),
        'param_name' => 'title',
        'admin_label' => true
    ),
    array(
        'type' => 'textarea_html',
        'heading' => __('Sub Heading(Optional)', 'lastudio'),
        'param_name' => 'content'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Heading tag','lastudio'),
        'param_name' => 'tag',
        'value' => array(
            __('Default','lastudio') => 'h2',
            __('H1','lastudio') => 'h1',
            __('H3','lastudio') => 'h3',
            __('H4','lastudio') => 'h4',
            __('H5','lastudio') => 'h5',
            __('H6','lastudio') => 'h6',
            __('DIV','lastudio') => 'div',
            __('p','lastudio') => 'p',
        ),
        'default' => 'h2',
        'description' => __('Default is H2', 'lastudio'),
    ),
    array(
        'type'  => 'dropdown',
        'heading' => __('Alignment','lastudio'),
        'param_name'    => 'alignment',
        'value' => array(
            __('Center','lastudio')	    =>	'center',
            __('Left','lastudio')	    =>	'left',
            __('Right','lastudio')	    =>	'right',
            __('Inline','lastudio')	    =>	'inline'
        ),
        'default' => 'left',
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
        'dependency' => array(
            'element'   => 'alignment',
            'value'     => array('center', 'left', 'right' )
        )
    ),
    array(
        'type'  => 'dropdown',
        'heading' => __('Separator Position','lastudio'),
        'param_name'    => 'spacer_position',
        'value' => array(
            __('Top','lastudio')	                        =>	'top',
            __('Bottom','lastudio')	                    =>	'bottom',
            __('Left', 'lastudio')	                        =>	'left',
            __('Right', 'lastudio')	                    =>	'right',
            __('Between Heading & Subheading', 'lastudio') =>	'middle',
            __('Title between separator', 'lastudio')	    =>	'separator'
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
    LaStudio_Shortcodes_Helper::fieldExtraClass(),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra Class for heading', 'lastudio'),
        'param_name' 	=> 'title_class',
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra Class for subheading', 'lastudio'),
        'param_name' 	=> 'subtitle_class',
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra Class for Line', 'lastudio'),
        'param_name' 	=> 'line_class',
        'dependency' => array(
            'element'   => 'spacer',
            'value'     => 'line'
        )
    ))
);

$title_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont();
$desc_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('subtitle', __('Subheading', 'lastudio'));

$shortcode_params = array_merge( $shortcode_params, $title_google_font_param, $desc_google_font_param, array(LaStudio_Shortcodes_Helper::fieldCssClass()));

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('La Heading', 'lastudio'),
        'base'			=> 'la_heading',
        'icon'          => 'la-wpb-icon fa fa-font la_heading',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Awesome heading styles.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_heading'
);