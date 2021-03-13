<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

if ( class_exists('WPBakeryShortCodesContainer') && !class_exists( 'WPBakeryShortCode_la_animation_block' ) ) {
    class WPBakeryShortCode_la_animation_block extends WPBakeryShortCodesContainer{

    }
}

$shortcode_params = array(

    LaStudio_Shortcodes_Helper::fieldCssAnimation(array(
        'settings' => array(
            'type' => array(
                'in',
                'other',
                'infinite'
            )
        )
    )),

    array(
        'type' => 'la_number',
        'heading' => __('Animation Duration', 'lastudio'),
        'param_name' => 'animation_duration',
        'value' => 1,
        'min' => 0.1,
        'max' => 100,
        'suffix' => 's',
        'description' => __('How long the animation effect should last. Decides the speed of effect.', 'lastudio'),
    ),
    array(
        'type' => 'la_number',
        'heading' => __('Animation Delay', 'lastudio'),
        'param_name' => 'animation_delay',
        'value' => 0,
        'min' => 0.1,
        'max' => 100,
        'suffix' => 's',
        'description' => __('Delays the animation effect for seconds you enter above.', 'lastudio'),
    ),
    array(
        'type' => 'la_number',
        'heading' => __('Animation Repeat Count', 'lastudio'),
        'param_name' => 'animation_iteration_count',
        'value' => 1,
        'min' => 0,
        'max' => 100,
        'suffix' => '',
        'description' => __('The animation effect will repeat to the count you enter above. Enter 0 if you want to repeat it infinitely.', 'lastudio'),
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Hide Elements Until Delay','lastudio'),
        'description' => __('If set to yes, the elements inside block will stay hidden until animation starts (depends on delay settings above).', 'lastudio'),
        'param_name' => 'opacity',
        'value' => array(
            __('Yes','lastudio') => 'yes',
            __('No','lastudio') => 'no',
        ),
        'std' => 'yes',
        'admin_label' => true
    ),

    LaStudio_Shortcodes_Helper::fieldExtraClass(),
    LaStudio_Shortcodes_Helper::fieldCssClass(array(
        'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
    ))
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Animation Block', 'lastudio'),
        'base'			=> 'la_animation_block',
        'icon'          => 'la-wpb-icon la_animation_block',
        'class'         => 'la_animation_block',
        'as_parent'     => array('except' => array('la_animation_block')),
        'content_element' => true,
        'controls'      => 'full',
        'show_settings_on_create' => true,
        'category'  	=> __('La Studio', 'lastudio'),
        'description'   => __('Apply animations everywhere', 'lastudio'),
        'params' 		=> $shortcode_params,
        'js_view'       => 'VcColumnView',
        'html_template' => plugin_dir_path( dirname(__FILE__) ) . 'templates/la_animation_block.php'
    ),
    'la_animation_block'
);