<?php

if (!function_exists('la_log')) {
    function la_log($log) {
        if (true === WP_DEBUG) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

if(!function_exists('lastudio_elements_template_path')){
    function lastudio_elements_template_path(){
        return apply_filters( 'lastudio-elements/template-path', 'lastudio-elements/' );
    }
}

if(!function_exists('lastudio_elements_get_template')){
    function lastudio_elements_get_template( $name = null ){
        $template = locate_template( lastudio_elements_template_path() . $name );

        if ( ! $template ) {
            $template = LASTUDIO_ELEMENTS_PATH  . 'templates/' . $name;
        }
        if ( file_exists( $template ) ) {
            return $template;
        } else {
            return false;
        }
    }
}

if(!function_exists('lastudio_elements_get_loading_icon')){
    function lastudio_elements_get_loading_icon(){
        return '<div class="la-shortcode-loading"><div class="content"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>';
    }
}


if(!function_exists('lastudio_elements_add_setting_panel_for_available_widgets')){
    function lastudio_elements_add_setting_panel_for_available_widgets( $sections, $settings, $unique ){
        $modules = [
            'advanced-carousel' => 'LaStudio Advanced Carousel',
            'advanced-map' => 'LaStudio Advanced Map',
            'animated-box' => 'LaStudio Animated Box',
            'animated-text' => 'LaStudio Animated Text',
            'audio' => 'LaStudio Audio Player',
            'banner' => 'LaStudio Banner',
            'brands' => 'LaStudio Brands',
            'button' => 'LaStudio Button',
            'circle-progress' => 'LaStudio Circle Progress',
            'countdown-timer' => 'LaStudio Countdown Timer',
            'dropbar'  => 'LaStudio Dropbar',
            'headline' => 'LaStudio Headline',
            'horizontal-timeline' => 'LaStudio Horizontal Timeline',
            'image-comparison' => 'LaStudio Image Comparison',
            'images-layout' => 'LaStudio Images Layout',
            'inline-svg' => 'LaStudio Inline SVG',
            'instagram-gallery' => 'LaStudio Instagram',
            'portfolio' => 'LaStudio Portfolio',
            'posts' => 'LaStudio Posts',
            'price-list' => 'LaStudio Price List',
            'pricing-table' => 'LaStudio Pricing Table',
            'progress-bar' => 'LaStudio Progress Bar',
            'scroll-navigation' => 'LaStudio Scroll Navigation',
            'services' => 'LaStudio Services',
            'slider' => 'LaStudio Slider',
            'subscribe-form' => 'LaStudio Subscribe',
            'table' => 'LaStudio Table',
            'tabs-widget' => 'LaStudio Tabs',
            'team-member' => 'LaStudio Team Member',
            'testimonials' => 'LaStudio Testimonials',
            'timeline' => 'LaStudio Vertical Timeline',
            'video' => 'LaStudio Video Player'
        ];

        if(isset($settings['menu_slug']) && $settings['menu_slug'] == 'theme_options'){
            $sections[] = array(
                'name' => 'la_elementor_modules_panel',
                'title' => esc_html_x('Elementor Available Widgets', 'admin-view', 'lastudio-elements'),
                'icon' => 'fa fa-lock',
                'fields' => array(
                    array(
                        'id'       => 'lastudio_elementor_modules',
                        'type'     => 'checkbox',
                        'title'    => esc_html_x('Available Widgets', 'admin-view', 'lastudio-elements'),
                        'desc'  => esc_html_x('List of widgets that will be available when editing the page', 'admin-view', 'lastudio-elements'),
                        'options'  => $modules,
                        'default' => array_keys($modules)
                    )
                )
            );
        }
        return $sections;
    }
}

add_filter('LaStudio/framework_option/sections', 'lastudio_elements_add_setting_panel_for_available_widgets', 20, 3);


if(!function_exists('lastudio_elements_validate_save_available_widgets')){
    function lastudio_elements_validate_save_available_widgets( $request ){
        if(isset($request['lastudio_elementor_modules'])){
            $default_modules = [
                'advanced-carousel' => false,
                'advanced-map' => false,
                'animated-box' => false,
                'animated-text' => false,
                'audio' => false,
                'banner' => false,
                'brands' => false,
                'button' => false,
                'circle-progress' => false,
                'countdown-timer' => false,
                'dropbar'  => false,
                'headline' => false,
                'horizontal-timeline' => false,
                'image-comparison' => false,
                'images-layout' => false,
                'inline-svg' => false,
                'instagram-gallery' => false,
                'portfolio' => false,
                'posts' => false,
                'price-list' => false,
                'pricing-table' => false,
                'progress-bar' => false,
                'scroll-navigation' => false,
                'services' => false,
                'slider' => false,
                'subscribe-form' => false,
                'table' => false,
                'tabs-widget' => false,
                'team-member' => false,
                'testimonials' => false,
                'timeline' => false,
                'video' => false
            ];

            $la_widget_available = !empty($request['lastudio_elementor_modules']) ? $request['lastudio_elementor_modules'] : array();

            if(!empty($la_widget_available)){
                foreach ($la_widget_available as $module){
                    if(isset($default_modules[$module])){
                        $default_modules[$module] = true;
                    }
                }
            }
            else{
                if(!get_option('lastudio_elementor_modules_has_init', false)){
                    $default_modules = [
                        'advanced-carousel' => true,
                        'advanced-map' => true,
                        'animated-box' => true,
                        'animated-text' => true,
                        'audio' => true,
                        'banner' => true,
                        'brands' => true,
                        'button' => true,
                        'circle-progress' => true,
                        'countdown-timer' => true,
                        'dropbar'  => true,
                        'headline' => true,
                        'horizontal-timeline' => true,
                        'image-comparison' => true,
                        'images-layout' => true,
                        'inline-svg' => true,
                        'instagram-gallery' => true,
                        'portfolio' => true,
                        'posts' => true,
                        'price-list' => true,
                        'pricing-table' => true,
                        'progress-bar' => true,
                        'scroll-navigation' => true,
                        'services' => true,
                        'slider' => true,
                        'subscribe-form' => true,
                        'table' => true,
                        'tabs-widget' => true,
                        'team-member' => true,
                        'testimonials' => true,
                        'timeline' => true,
                        'video' => true
                    ];

                    update_option('la_extension_available', array(
                        'swatches' => true,
                        '360' => true,
                        'content_type' => true
                    ));
                    update_option('lastudio_elementor_modules', $default_modules);
                    update_option('lastudio_elementor_modules_has_init', true);
                }
            }
            update_option('lastudio_elementor_modules', $default_modules);
        }
        return $request;
    }
}

add_filter('la_validate_save', 'lastudio_elements_validate_save_available_widgets', 20);

if(!function_exists('lastudio_elements_get_widgets_black_list')){
    function lastudio_elements_get_widgets_black_list( $black_list ){
        $new_black_list = array(
            'WP_Widget_Calendar',
            'WP_Widget_Pages',
            'WP_Widget_Archives',
            'WP_Widget_Media_Audio',
            'WP_Widget_Media_Image',
            'WP_Widget_Media_Gallery',
            'WP_Widget_Media_Video',
            'WP_Widget_Meta',
            'WP_Widget_Text',
            'WP_Widget_RSS',
            'WP_Widget_Custom_HTML',
            'RevSliderWidget',
            'LaStudio_Widget_Recent_Posts',
            'LaStudio_Widget_Product_Sort_By',
            'LaStudio_Widget_Price_Filter_List',
            'LaStudio_Widget_Product_Tag',
            'WP_Widget_Recent_Posts',
            'WP_Widget_Recent_Comments',
            'WC_Widget_Cart',
            'WC_Widget_Layered_Nav_Filters',
            'WC_Widget_Layered_Nav',
            'WC_Widget_Price_Filter',
            'WC_Widget_Product_Categories',
            'WC_Widget_Product_Search',
            'WC_Widget_Product_Tag_Cloud',
            'WC_Widget_Products',
            'WC_Widget_Recently_Viewed',
            'WC_Widget_Top_Rated_Products',
            'WC_Widget_Recent_Reviews',
            'WC_Widget_Rating_Filter'
        );

        return $new_black_list;
    }
}

add_filter('elementor/widgets/black_list', 'lastudio_elements_get_widgets_black_list', 20);

add_action('after_setup_theme', function(){
    if(!get_option('lastudio_elementor_modules_has_init', false)){
        $default_modules = [
            'advanced-carousel' => true,
            'advanced-map' => true,
            'animated-box' => true,
            'animated-text' => true,
            'audio' => true,
            'banner' => true,
            'brands' => true,
            'button' => true,
            'circle-progress' => true,
            'countdown-timer' => true,
            'dropbar'  => true,
            'headline' => true,
            'horizontal-timeline' => true,
            'image-comparison' => true,
            'images-layout' => true,
            'inline-svg' => true,
            'instagram-gallery' => true,
            'portfolio' => true,
            'posts' => true,
            'price-list' => true,
            'pricing-table' => true,
            'progress-bar' => true,
            'scroll-navigation' => true,
            'services' => true,
            'slider' => true,
            'subscribe-form' => true,
            'table' => true,
            'tabs-widget' => true,
            'team-member' => true,
            'testimonials' => true,
            'timeline' => true,
            'video' => true
        ];

        update_option('lastudio_elementor_modules', $default_modules);
        update_option('lastudio_elementor_modules_has_init', true);
    }

}, 99);

if(!function_exists('lastudio_elements_get_enabled_modules')){
    function lastudio_elements_get_enabled_modules(){
        $enable_modules = get_option('lastudio_elementor_modules', array());
        $tmp = array();
        if(!empty($enable_modules)){
            foreach ($enable_modules as $module => $active ){
                if(!empty($active)){
                    $tmp[] = 'lastudio-' . $module;
                }
            }
        }
        return $tmp;
    }
}