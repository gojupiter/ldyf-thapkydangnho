<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Shortcodes_Helper{

    public static function remove_js_autop($content, $autop = false){
        if ( $autop ) {
            $content = preg_replace( '/<\/?p\>/', "\n", $content );
            $content = preg_replace( '/<p[^>]*><\\/p[^>]*>/', "", $content );
            $content = wpautop( $content . "\n" );
        }
        return do_shortcode( shortcode_unautop( $content ) );
    }

    public static function fieldIconType($dependency = array(), $emptyIcon = false){
        return array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Icon library', 'lastudio' ),
                'value' => array(
                    __( 'Font Awesome', 'lastudio' ) => 'fontawesome',
                    __( 'Open Iconic', 'lastudio' ) => 'openiconic',
                    __( 'Typicons', 'lastudio' ) => 'typicons',
                    __( 'Entypo', 'lastudio' ) => 'entypo',
                    __( 'Linecons', 'lastudio' ) => 'linecons',
                    __( 'Mono Social', 'lastudio' ) => 'monosocial',
                    __( 'LaStudio Icon Outline', 'lastudio' ) => 'la_icon_outline',
                    __( 'Nucleo Glyph', 'lastudio' ) => 'nucleo_glyph',
                    __( 'LaStudio SVG', 'lastudio' ) => 'la_svg',
                    __( 'Custom Image', 'lastudio') => 'custom',
                ),
                'param_name' => 'icon_type',
                'description' => __( 'Select icon library.', 'lastudio' ),
                'dependency' => $dependency
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_fontawesome',
                'value' => 'fa fa-info-circle',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'fontawesome',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_openiconic',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'openiconic',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'openiconic',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_typicons',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'typicons',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'typicons',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_entypo',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'entypo',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'entypo',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_linecons',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'linecons',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'linecons',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_monosocial',
                'value' => 'vc-mono vc-mono-fivehundredpx',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'monosocial',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'monosocial',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_la_icon_outline',
                'value' => 'la-icon design-2_image',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'la_icon_outline',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'la_icon_outline',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_nucleo_glyph',
                'value' => 'nc-icon-glyph nature_bear',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'nucleo_glyph',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'nucleo_glyph',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'lastudio' ),
                'param_name' => 'icon_la_svg',
                'value' => '',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'la_svg',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'la_svg',
                )
            ),
            array(
                'type' => 'attach_image',
                'heading' => __('Upload the custom image icon', 'lastudio'),
                'param_name' => "icon_image_id",
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'custom',
                ),
            )
        );
    }

    public static function fieldColumn($options = array()){
        return array_merge(array(
            'type' 			=> 'la_column',
            'heading' 		=> __('Column', 'lastudio'),
            'param_name' 	=> 'column',
            'unit'			=> '',
            'media'			=> array(
                'xlg'	=> 1,
                'lg'	=> 1,
                'md'	=> 1,
                'sm'	=> 1,
                'xs'	=> 1,
                'mb'	=> 1
            )
        ), $options);
    }

    public static function fieldImageSize($options = array()){
        return array_merge(
            array(
                'type' 			=> 'textfield',
                'heading' 		=> __('Image size', 'lastudio'),
                'param_name' 	=> 'img_size',
                'value'			=> 'thumbnail',
                'description' 	=> __('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'lastudio'),
            ),
            $options
        );
    }

    public static function fieldCssAnimation($options = array()){
        return array_merge(
            array(
                'type' => 'animation_style',
                'heading' => __( 'CSS Animation', 'lastudio' ),
                'param_name' => 'css_animation',
                'value' => 'none',
                'settings' => array(
                    'type' => array(
                        'in',
                        'other',
                    ),
                ),
                'description' => __( 'Select initial loading animation for element.', 'lastudio' ),
            ),
            $options
        );
    }

    public static function fieldExtraClass($options = array()){
        return array_merge(
            array(
                'type' 			=> 'textfield',
                'heading' 		=> __('Extra Class name', 'lastudio'),
                'param_name' 	=> 'el_class',
                'description' 	=> __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'lastudio')
            ),
            $options
        );
    }

    public static function fieldElementID($options = array()){
        return array_merge(
            array(
                'type' => 'textfield',
                'heading' => __( 'Element ID', 'lastudio' ),
                'param_name' => 'shortcode_id',
                'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'lastudio' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            ),
            $options
        );
    }

    public static function fieldCssClass($options = array()){
        return array_merge(
            array(
                'type' 			=> 'css_editor',
                'heading' 		=> __('CSS box', 'lastudio'),
                'param_name' 	=> 'css',
                'group' 		=> __('Design Options', 'lastudio')
            ),
            $options
        );
    }

    public static function getColumnFromShortcodeAtts( $atts ){
        $array = array(
            'xlg'	=> 1,
            'lg' 	=> 1,
            'md' 	=> 1,
            'sm' 	=> 1,
            'xs' 	=> 1,
            'mb' 	=> 1
        );
        $atts = explode(';',$atts);
        if(!empty($atts)){
            foreach($atts as $val){
                $val = explode(':',$val);
                if(isset($val[0]) && isset($val[1])){
                    if(isset($array[$val[0]])){
                        $array[$val[0]] = absint($val[1]);
                    }
                }
            }
        }
        return $array;
    }

    public static function fieldTitleGFont( $name = 'title', $title = 'Title',  $dependency = array() ){
        $group = __('Typography', 'lastudio');
        $array = array();
        $array[] = array(
            'type' 			=> 'la_heading',
            'param_name' 	=> $name . '__typography',
            'text' 			=> $title . __(' settings', 'lastudio'),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' => 'checkbox',
            'heading' => __( 'Use google fonts family?', 'lastudio' ),
            'param_name' => 'use_gfont_' . $name,
            'value' => array( __( 'Yes', 'lastudio' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'lastudio' ),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' 			=> 'google_fonts',
            'param_name' 	=> $name . '_font',
            'dependency' 	=> array(
                'element' => 'use_gfont_' . $name,
                'value' => 'yes',
            ),
            'group' 		=> $group
        );
        $array[] = array(
            'type' 			=> 'la_column',
            'heading' 		=> __('Font size', 'lastudio'),
            'param_name' 	=> $name . '_fz',
            'unit' 			=> 'px',
            'media' => array(
                'xlg'	=> '',
                'lg'    => '',
                'md'    => '',
                'sm'    => '',
                'xs'	=> '',
                'mb'	=> ''
            ),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' 			=> 'la_column',
            'heading' 		=> __('Line Height', 'lastudio'),
            'param_name' 	=> $name . '_lh',
            'unit' 			=> 'px',
            'media' => array(
                'xlg'	=> '',
                'lg'    => '',
                'md'    => '',
                'sm'    => '',
                'xs'	=> '',
                'mb'	=> ''
            ),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' 			=> 'colorpicker',
            'param_name' 	=> $name . '_color',
            'heading' 		=> __('Color', 'lastudio'),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        return $array;
    }

    public static function getResponsiveMediaCss( $args = array(), $css = null){
        $content = '';
        if(!empty($args) && !empty($args['target']) && !empty($args['media_sizes'])){
            $content .=  ' data-la_component="UnitResponsive" ';
            $content .=  " data-el_target='".esc_attr($args['target'])."' ";
            $content .=  " data-el_media_sizes='".esc_attr(wp_json_encode($args['media_sizes']))."' ";

            if(!is_null($css)){
                foreach( $args['media_sizes'] as $css_attribute => $items ){
                    $media_sizes =  explode(';', $items);
                    if(!empty($media_sizes)){
                        foreach($media_sizes as $value ){
                            $tmp = explode(':', $value);
                            if(!empty($tmp[1])){
                                if(!isset($css[$tmp[0]])){
                                    $css[$tmp[0]] = '';
                                }
                                $css[$tmp[0]] .= $args['target'] . '{' . $css_attribute . ':'. $tmp[1] .'}';
                            }
                        }
                    }
                }
            }

        }
        return $content;
    }

    public static function renderResponsiveMediaCss(&$css = array(), $args = array()){

        if(!empty($args) && !empty($args['target']) && !empty($args['media_sizes'])){
            $target = $args['target'];
            foreach( $args['media_sizes'] as $css_attribute => $items ){
                $media_sizes =  explode(';', $items);
                if(!empty($media_sizes)){
                    foreach($media_sizes as $value ){
                        $tmp = explode(':', $value);
                        if(!empty($tmp[1])){
                            if(!isset($css[$tmp[0]])){
                                $css[$tmp[0]] = '';
                            }
                            $css[$tmp[0]] .= $target . '{' . $css_attribute . ':'. $tmp[1] .'}';
                        }
                    }
                }
            }
        }

    }

    public static function renderResponsiveMediaStyleTags( $custom_css = array() ){
        $output = '';
        if(function_exists('vc_is_inline') && vc_is_inline() && !empty($custom_css)){
            foreach($custom_css as $media => $value){
                switch($media){
                    case 'lg':
                        $output .= $value;
                        break;
                    case 'xlg':
                        $output .= '@media (min-width: 1824px){'.$value.'}';
                        break;
                    case 'md':
                        $output .= '@media (max-width: 1199px){'.$value.'}';
                        break;
                    case 'sm':
                        $output .= '@media (max-width: 991px){'.$value.'}';
                        break;
                    case 'xs':
                        $output .= '@media (max-width: 767px){'.$value.'}';
                        break;
                    case 'mb':
                        $output .= '@media (max-width: 575px){'.$value.'}';
                        break;
                }
            }
        }
        if(!empty($output)){
            echo '<style type="text/css">'.$output.'</style>';
        }
    }

    public static function parseGoogleFontAtts( $value ){
        $fields = array();
        $styles = array();
        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        } else {
            $subsets = '';
        }
        $value = la_parse_multi_attribute($value);
        if(isset($value['font_family']) && isset($value['font_style'])){
            $google_fonts_family = explode( ':', $value['font_family'] );
            $styles[] = 'font-family:' . $google_fonts_family[0];
            $google_fonts_styles = explode( ':', $value['font_style'] );
            $styles[] = 'font-weight:' . $google_fonts_styles[1];
            $styles[] = 'font-style:' . $google_fonts_styles[2];
            $fields['font_url'] = '//fonts.googleapis.com/css?family=' . rawurlencode($value['font_family']) . $subsets;
            $fields['font_family'] = la_build_safe_css_class($value['font_family']);

            $fields['style'] = implode(';',$styles);
        }
        return $fields;
    }

    public static function getImageSizeFormString($size, $default = 'thumbnail'){
        if(empty($size)){
            return $default;
        }
        $ignore = array(
            'thumbnail',
            'thumb',
            'medium',
            'large',
            'full'
        );
        if(false !== strpos($size, 'la_')){
            return $size;
        }
        $_wp_additional_image_sizes = wp_get_additional_image_sizes();
        if(is_string($size) && (in_array($size, $ignore) || (!empty($_wp_additional_image_sizes[$size]) && is_array($_wp_additional_image_sizes[$size]) ))){
            return $size;
        }
        else{
            preg_match_all( '/\d+/', $size, $thumb_matches );
            if ( isset( $thumb_matches[0] ) ) {
                $thumb_size = array();
                if ( count( $thumb_matches[0] ) > 1 ) {
                    $thumb_size[] = $thumb_matches[0][0]; // width
                    $thumb_size[] = $thumb_matches[0][1]; // height
                } elseif ( count( $thumb_matches[0] ) > 0 && count( $thumb_matches[0] ) < 2 ) {
                    $thumb_size[] = $thumb_matches[0][0]; // width
                    $thumb_size[] = 0; //$thumb_matches[0][0]; // height
                } else {
                    $thumb_size = $default;
                }
            }else{
                $thumb_size = $default;
            }
            return $thumb_size;
        }
    }

    public static function getLoadingIcon(){
        return '<div class="la-shortcode-loading"><div class="content"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>';
    }

    public static function getExtraClass( $el_class ) {
        $output = '';
        if ( '' !== $el_class ) {
            $output = ' ' . str_replace( '.', '', $el_class );
        }

        return $output;
    }

    public static function paramCarouselShortCode( $full_control = true ){
        if($full_control){
            $general_name = esc_html__('General', 'lastudio');
            $dependency = array();
        }else{
            $general_name = esc_html__('Slider Setting', 'lastudio');
            $dependency =  array(
                'element' => 'enable_carousel',
                'value' => 'yes'
            );
        }
        $params = array(
            array(
                'type'       => 'dropdown',
                'heading'    => __( 'Slider Type', 'lastudio' ),
                'param_name' => 'slider_type',
                'value'      => array(
                    esc_html__('Horizontal', 'lastudio')            => 'horizontal',
                    esc_html__('Vertical', 'lastudio')              => 'vertical'
                ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            array(
                'type'       => 'dropdown',
                'heading'    => __( 'Slides to Scroll', 'lastudio' ),
                'param_name' => 'slide_to_scroll',
                'value'      => array(
                    esc_html__('All visible', 'lastudio') => 'all',
                    esc_html__('One at a Time', 'lastudio') => 'single'
                ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            LaStudio_Shortcodes_Helper::fieldColumn(array(
                'heading' 		=> __('Items to Show', 'lastudio'),
                'param_name' 	=> 'slides_column',
                'group'      => $general_name,
                'dependency' => $dependency
            )),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Infinite loop', 'lastudio'),
                'description'	=> __( 'Restart the slider automatically as it passes the last slide.', 'lastudio' ),
                'param_name' 	=> 'infinite_loop',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'group'      => $general_name,
                'dependency' => $dependency

            ),
            array(
                'type'        => 'la_number',
                'heading'     => __( 'Transition speed', 'lastudio' ),
                'param_name'  => 'speed',
                'value'       => '300',
                'min'         => '100',
                'max'         => '10000',
                'step'        => '100',
                'suffix'      => 'ms',
                'description' => __( 'Speed at which next slide comes.', 'lastudio' ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Autoplay Slides', 'lastudio'),
                'param_name' 	=> 'autoplay',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            array(
                'type'       => 'la_number',
                'heading'    => __( 'Autoplay Speed', 'lastudio' ),
                'param_name' => 'autoplay_speed',
                'value'      => '5000',
                'min'        => '100',
                'max'        => '10000',
                'step'       => '10',
                'suffix'     => 'ms',
                'dependency' => array(
                    'element' => 'autoplay', 'value' => 'yes'
                ),
                'group'      => $general_name
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Navigation Arrows', 'lastudio'),
                'description' 	=> __( 'Display next / previous navigation arrows', 'lastudio' ),
                'param_name' 	=> 'arrows',
                'value' 		=> array(
                    __('Show', 'lastudio') => 'yes'
                ),
                'group'      	=> 'Navigation',
                'dependency' => $dependency
            ),
            array(
                'type'       => 'dropdown',
                'heading'    => __( 'Arrow Style', 'lastudio' ),
                'param_name' => 'arrow_style',
                'value'      => array(
                    'Default'           => 'default',
                    'Circle Background' => 'circle-bg',
                    'Square Background' => 'square-bg',
                    'Circle Border'     => 'circle-border',
                    'Square Border'     => 'square-border',
                ),
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Background Color', 'lastudio' ),
                'param_name' => 'arrow_bg_color',
                'dependency' => array(
                    'element' => 'arrow_style',
                    'value'   => array( 'circle-bg', 'square-bg' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Border Color', 'lastudio' ),
                'param_name' => 'arrow_border_color',
                'dependency' => array(
                    'element' => 'arrow_style',
                    'value'   => array( 'circle-border', 'square-border' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_number',
                'heading'    => __( 'Border Size', 'lastudio' ),
                'param_name' => 'border_size',
                'value'      => '2',
                'min'        => '1',
                'max'        => '100',
                'step'       => '1',
                'suffix'     => 'px',
                'dependency' => array(
                    'element' => 'arrow_style',
                    'value'   => array( 'circle-border', 'square-border' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Arrow Color', 'lastudio' ),
                'param_name' => 'arrow_color',
                'value'      => '#333333',
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_number',
                'heading'    => __( 'Arrow Size', 'lastudio' ),
                'param_name' => 'arrow_size',
                'value'      => '24',
                'min'        => '10',
                'max'        => '75',
                'step'       => '1',
                'suffix'     => 'px',
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_slides_navigation',
                'heading'    => __( "Select icon for 'Next Arrow'", 'lastudio' ),
                'param_name' => 'next_icon',
                'value'      => 'dl-icon-right',
                'dependency' => array(
                    'element' => 'arrows',
                    'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_slides_navigation',
                'heading'    => __( "Select icon for 'Previous Arrow'", 'lastudio' ),
                'param_name' => 'prev_icon',
                'value'      => 'dl-icon-left',
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),

            array(
                'type' 			=> 'textfield',
                'heading' 		=> __( 'Custom Navigation Carousel Element', 'lastudio' ),
                'param_name' 	=> 'custom_nav',
                'description' 	=> 'Enter classname OR ID of Navigation, Ex: ".navigation_carousel", "#navigation_carousel"',
                'dependency' 	=> array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group' 		=> 'Navigation'
            ),

            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Dots Navigation', 'lastudio'),
                'description' 	=> __( 'Display dot navigation', 'lastudio' ),
                'param_name' 	=> 'dots',
                'value' 		=> array(
                    __('Show', 'lastudio') => 'yes'
                ),
                'group'      	=> 'Navigation',
                'dependency' => $dependency
            ),

            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Color of dots', 'lastudio' ),
                'param_name' => 'dots_color',
                'value'      => '#333333',
                'dependency' => array(
                    'element' => 'dots', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_slides_navigation',
                'heading'    => __( "Select icon for 'Navigation Dots'", 'lastudio' ),
                'param_name' => 'dots_icon',
                'value'      => 'fa fa-circle',
                'dependency' => array(
                    'element' => 'dots', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Draggable Effect', 'lastudio'),
                'description' 	=> __( 'Allow slides to be draggable', 'lastudio' ),
                'param_name' 	=> 'draggable',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'std'           => 'yes',
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Touch Move', 'lastudio'),
                'description' 	=> __( 'Enable slide moving with touch', 'lastudio' ),
                'param_name' 	=> 'touch_move',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'std'           => 'yes',
                'dependency'  => array(
                    'element' => 'draggable', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Advanced'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('RTL Mode', 'lastudio'),
                'description' 	=> __( 'Turn on RTL mode', 'lastudio' ),
                'param_name' 	=> 'rtl',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Adaptive Height', 'lastudio'),
                'description' 	=> __('Turn on Adaptive Height', 'lastudio' ),
                'param_name' 	=> 'adaptive_height',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Pause on hover', 'lastudio'),
                'description' 	=> __('Pause the slider on hover', 'lastudio' ),
                'param_name' 	=> 'pauseohover',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'dependency'  => array(
                    'element' => 'autoplay', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Advanced'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Center mode', 'lastudio'),
                'description' 	=> __("Enables centered view with partial prev/next slides. <br>Animations do not work with center mode.<br>Slides to scroll -> 'All Visible' do not work with center mode.", 'lastudio'),
                'param_name' 	=> 'centermode',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Item Auto Width', 'lastudio'),
                'description' 	=> __('Variable width slides', 'lastudio' ),
                'param_name' 	=> 'autowidth',
                'value' 		=> array(
                    __('Yes', 'lastudio') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            )
        );
        if($full_control){
            $params[] = array(
                'type'       => 'la_number',
                'heading'    => __( 'Space between two items', 'lastudio' ),
                'param_name' => 'item_space',
                'value'      => '15',
                'min'        => 0,
                "max"        => '1000',
                'step'       => 1,
                'suffix'     => 'px',
                'group'      => 'Advanced'
            );
            $params[] = array(
                'type'       => 'textfield',
                'heading'    => __( 'Extra Class', 'lastudio' ),
                'param_name' => 'el_class',
                'group'      => esc_html__('General', 'lastudio')
            );
            $params[] = array(
                'type'             => 'css_editor',
                'heading'          => __( 'Css', 'lastudio' ),
                'param_name'       => 'css_ad_carousel',
                'group'            => __( 'Design ', 'lastudio' )
            );
        }

        $params[] = LaStudio_Shortcodes_Helper::fieldCssAnimation(array(
            'settings' => array(
                'type' => array(
                    'carousel'
                )
            ),
            'group'            => __( 'Item Animation ', 'lastudio' ),
            'param_name' => 'item_animation',
            'dependency' => $dependency
        ));

        return $params;
    }

    public static function getParamItemSpace($options = array()){
        return array_merge(array(
            'type' => 'dropdown',
            'heading' => __( 'Item Space', 'lastudio' ),
            'value' => array (
                __( 'Default', 'lastudio' ) => 'default',
                __( '0px', 'lastudio' ) => '0',
                __( '5px', 'lastudio' ) => '5',
                __( '10px', 'lastudio' ) => '10',
                __( '15px', 'lastudio' ) => '15',
                __( '20px', 'lastudio' ) => '20',
                __( '25px', 'lastudio' ) => '25',
                __( '30px', 'lastudio' ) => '30',
                __( '35px', 'lastudio' ) => '35',
                __( '40px', 'lastudio' ) => '40',
                __( '45px', 'lastudio' ) => '45',
                __( '50px', 'lastudio' ) => '50',
                __( '55px', 'lastudio' ) => '55',
                __( '60px', 'lastudio' ) => '60',
                __( '65px', 'lastudio' ) => '65',
                __( '70px', 'lastudio' ) => '70',
                __( '75px', 'lastudio' ) => '75',
                __( '80px', 'lastudio' ) => '80'
            ),
            'param_name' => 'item_space',
            'std' => '30'
        ), $options);
    }

    public static function getParamIndex($array, $attr){
        foreach ($array as $index => $entry) {
            if ($entry['param_name'] == $attr) {
                return $index;
            }
        }
        return -1;
    }

    public static function getParamCarouselShortCode( $atts, $param_column = 'columns' ){
        $slider_type    = $slide_to_scroll = $speed = $infinite_loop = $autoplay = $autoplay_speed = '';
        $lazyload       = $arrows = $dots = $dots_icon = $next_icon = $prev_icon = $dots_color = $draggable = $touch_move = '';
        $rtl            = $arrow_color = $arrow_size = $arrow_style = $arrow_bg_color = $arrow_border_color = $border_size = $el_class = '';
        $slides_column = $autowidth = $css_ad_carousel = $pauseohover = $centermode = $adaptive_height = $custom_nav = '';

        extract( shortcode_atts( array(
            'slider_type' => 'horizontal',
            'slide_to_scroll' => 'all',
            'slides_column' => '',
            'infinite_loop' => '',
            'speed' => '300',
            'autoplay' => '',
            'autoplay_speed' => '5000',
            'arrows' => '',
            'arrow_style' => 'default',
            'arrow_bg_color' => '',
            'arrow_border_color' => '',
            'border_size' => '2',
            'arrow_color' => '#333333',
            'arrow_size' => '24',
            'next_icon' => 'dl-icon-right',
            'prev_icon' => 'dl-icon-left',
            'custom_nav' => '',
            'dots' => '',
            'dots_color' => '#333333',
            'dots_icon' => 'fa fa-circle',
            'draggable' => 'yes',
            'touch_move' => 'yes',
            'rtl' => '',
            'adaptive_height' => '',
            'pauseohover' => '',
            'centermode' => '',
            'autowidth' => '',
            'item_space' => '15',
            'el_class' => '',
            'css_ad_carousel' => ''
        ), $atts ) );

        if(isset($atts[$param_column])){
            $slides_column = $atts[$param_column];
        }

        $slides_column = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($slides_column);

        $custom_dots = $arr_style = $wrap_data = '';


        if ( $slide_to_scroll == 'all' ) {
            $slide_to_scroll = $slides_column['xlg'];
        } else {
            $slide_to_scroll = 1;
        }

        $setting_obj = array();
        $setting_obj['slidesToShow'] = absint($slides_column['xlg']);
        $setting_obj['slidesToScroll'] = absint($slide_to_scroll);


        $arr_style .= 'color:' . $arrow_color . ';';
        $arr_style .= 'font-size:' . $arrow_size . 'px;';
        $arr_style .= 'width:' . $arrow_size . 'px;';
        $arr_style .= 'height:' . $arrow_size . 'px;';
        $arr_style .= 'line-height:' . $arrow_size . 'px;';

        if ( $arrow_style == "circle-bg" || $arrow_style == "square-bg" ) {
            $arr_style .= "background:" . $arrow_bg_color . ";";
        } elseif ( $arrow_style == "circle-border" || $arrow_style == "square-border" ) {
            $arr_style .= "border:" . $border_size . "px solid " . $arrow_border_color . ";";
        }

        if ( $dots == 'yes' ) {
            $setting_obj['dots'] = true;
        } else {
            $setting_obj['dots'] = false;
        }
        if ( $autoplay == 'yes' ) {
            $setting_obj['autoplay'] = true;
        }
        if ( $autoplay_speed !== '' ) {
            $setting_obj['autoplaySpeed'] = absint($autoplay_speed);
        }
        if ( $speed !== '' ) {
            $setting_obj['speed'] = absint($speed);
        }
        if ( $infinite_loop == 'yes' ) {
            $setting_obj['infinite'] = true;
        } else {
            $setting_obj['infinite'] = false;
        }
        if ( $lazyload == 'yes' ) {
            $setting_obj['lazyLoad'] = true;
        }

        if(strpos($prev_icon,'dlicon-') !== false){
            $prev_icon = 'dl-icon-left';
        }
        if(strpos($next_icon,'dlicon-') !== false){
            $next_icon = 'dl-icon-right';
        }

        if ( is_rtl() ) {
            $setting_obj['rtl'] = true;
            if ( $arrows == 'yes' ) {
                $setting_obj['arrows'] = true;
                $setting_obj['nextArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-next ' . esc_attr($arrow_style) . '"><i class="'.esc_attr($prev_icon).'"></i></button>';
                $setting_obj['prevArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-prev ' . esc_attr($arrow_style) . '"><i class="'.esc_attr($next_icon).'"></i></button>';
            } else {
                $setting_obj['false'] = false;
            }
        } else {
            if ( $arrows == 'yes' ) {
                $setting_obj['arrows'] = true;
                $setting_obj['nextArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-next ' . esc_attr($arrow_style) . '"><i class="'.esc_attr($next_icon).'"></i></button>';
                $setting_obj['prevArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-prev ' . esc_attr($arrow_style) . '"><i class="'.esc_attr($prev_icon).'"></i></button>';
            } else {
                $setting_obj['arrows'] = false;
            }
        }

        if ( $draggable == 'yes' ) {
            $setting_obj['swipe'] = true;
            $setting_obj['draggable'] = true;
        } else {
            $setting_obj['swipe'] = false;
            $setting_obj['draggable'] = false;
        }

        if ( $touch_move == 'yes' ) {
            $setting_obj['touchMove'] = true;
        } else {
            $setting_obj['touchMove'] = false;
        }

        if ( $rtl == 'yes' ) {
            $setting_obj['rtl'] = true;
        }

        if ( $slider_type == 'vertical' ) {
            $setting_obj['vertical'] = true;
        }

        if ( $pauseohover == 'yes' ) {
            $setting_obj['pauseOnHover'] = true;
        } else {
            $setting_obj['pauseOnHover'] = false;
        }

        if ( $centermode == 'yes' ) {
            $setting_obj['centerMode'] = true;
            $setting_obj['centerPadding'] = '20%';
        }

        if ( $autowidth == 'yes' ) {
            $setting_obj['variableWidth'] = true;
            $wrap_data .= ' aria-autowidth="true"';
        }

        if ( $adaptive_height == 'yes' ) {
            $setting_obj['adaptiveHeight'] = true;
        }

        $setting_obj['responsive'] = array(
            array(
                'breakpoint' => 1824,
                'settings' => array(
                    'slidesToShow' => $slides_column['lg'],
                    'slidesToScroll' => $slides_column['lg']
                )
            ),
            array(
                'breakpoint' => 1200,
                'settings' => array(
                    'slidesToShow' => $slides_column['md'],
                    'slidesToScroll' => $slides_column['md']
                )
            ),
            array(
                'breakpoint' => 992,
                'settings' => array(
                    'slidesToShow' => $slides_column['sm'],
                    'slidesToScroll' => $slides_column['sm']
                )
            ),
            array(
                'breakpoint' => 768,
                'settings' => array(
                    'slidesToShow' => $slides_column['xs'],
                    'slidesToScroll' => $slides_column['xs']
                )
            ),
            array(
                'breakpoint' => 576,
                'settings' => array(
                    'slidesToShow' => $slides_column['mb'],
                    'slidesToScroll' => $slides_column['mb']
                )
            )
        );

        $setting_obj['pauseOnDotsHover'] = true;

        if(!empty($custom_nav)){
            $setting_obj['_appendArrows'] = esc_attr($custom_nav);
        }

        if ( $dots == 'yes' ) {
            if ( $dots_icon !== 'off' && $dots_icon !== '' ) {
                if ( $dots_color !== 'off' && $dots_color !== '' ) {
                    $custom_dots = ' style="color:' . esc_attr( $dots_color ) . ';"';
                }
                if(strpos($dots_icon,'dlicon-') !== false){
                    $dots_icon = 'fa fa-circle';
                }
                $wrap_data .= 'data-slick_customPaging="'. esc_attr('<span'.$custom_dots.'><i class="'.$dots_icon.'"></i></span>') .'" ';
            }
        }

        $wrap_data .= 'data-slider_config="'. esc_attr(wp_json_encode($setting_obj)) .'"';

        return $wrap_data;
    }

    public static function convertLegacyCssColumns( $columns = array() ){
        $legacy = array(
            'xlg'	=> '',
            'lg' 	=> '',
            'md' 	=> '',
            'sm' 	=> '',
            'xs' 	=> '',
            'mb' 	=> 1
        );
        $new_key = array(
            'mb'    =>  'xs',
            'xs'    =>  'sm',
            'sm'    =>  'md',
            'md'    =>  'lg',
            'lg'    =>  'xl',
            'xlg'   =>  'xxl'
        );
        if(empty($columns)){
            $columns = $legacy;
        }
        $new_columns = array();
        foreach($columns as $k => $v){
            if(isset($new_key[$k])){
                $new_columns[$new_key[$k]] = $v;
            }
        }
        if(empty($new_columns['xs'])){
            $new_columns['xs'] = 1;
        }
        return $new_columns;
    }

    public static function renderResponsiveCssColumns( $columns, $merge = true ) {
        if($merge){
            $columns = self::convertLegacyCssColumns( $columns );
        }
        $classes = array();
        foreach($columns as $k => $v){
            if(empty($v)){
                continue;
            }
            if($k == 'xs'){
                $classes[] = 'block-grid-' . $v;
            }
            else{
                $classes[] = $k . '-block-grid-' . $v;
            }
        }
        return join(' ', $classes);
    }

    public static function fieldWooCommerceLayoutSetting(){

        return array(
            array(
                'type' => 'dropdown',
                'heading' => __('Layout','lastudio'),
                'param_name' => 'layout',
                'value' => array(
                    __('List','lastudio')      => 'list',
                    __('Grid','lastudio')      => 'grid',
                    __('Masonry','lastudio')   => 'masonry',
                ),
                'std'   => 'grid',
                'group' 		=> __('Layout Setting', 'lastudio')
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Style','lastudio'),
                'param_name' => 'list_style',
                'value' => la_get_product_list_style(),
                'dependency' => array(
                    'element'   => 'layout',
                    'value'     => 'list'
                ),
                'std' => 'default',
                'group' 		=> __('Layout Setting', 'lastudio')
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Style','lastudio'),
                'param_name' => 'grid_style',
                'value' => la_get_product_grid_style(),
                'dependency' => array(
                    'element'   => 'layout',
                    'value'     => 'grid'
                ),
                'std' => '1',
                'group' 		=> __('Layout Setting', 'lastudio')
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Style','lastudio'),
                'param_name' => 'masonry_style',
                'value' => la_get_product_grid_style(),
                'dependency' => array(
                    'element'   => 'layout',
                    'value'     => 'masonry'
                ),
                'std' => '1',
                'group' 		=> __('Layout Setting', 'lastudio')
            ),
            self::getParamItemSpace(array(
                'std' => 'default',
                'dependency' => array(
                    'element'   => 'layout',
                    'value'     => array('grid','masonry')
                ),
                'group' 		=> __('Layout Setting', 'lastudio')
            )),

            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __( 'Enable Custom Image Size', 'lastudio' ),
                'param_name' 	=> 'enable_custom_image_size',
                'value' 		=> array( __( 'Yes', 'lastudio' ) => 'yes' ),
                'group' 		=> __('Layout Setting', 'lastudio')
            ),

            self::fieldImageSize(array(
                'value'			=> 'shop_catalog',
                'dependency' => array(
                    'element'   => 'enable_custom_image_size',
                    'value'     => 'yes'
                ),
                'group' 		=> __('Layout Setting', 'lastudio')
            )),

            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __( 'Disable alternative image ', 'lastudio' ),
                'param_name' 	=> 'disable_alt_image',
                'value' 		=> array( __( 'Yes', 'lastudio' ) => 'yes' ),
                'group' 		=> __('Layout Setting', 'lastudio')
            ),

            array(
                'type' => 'dropdown',
                'heading' => __( 'Column Type', 'lastudio' ),
                'param_name' => 'column_type',
                'value' => array(
                    __( 'Default', 'lastudio' ) => 'default',
                    __( 'Custom', 'lastudio' ) => 'custom'
                ),
                'save_always' => true,
                'dependency' => array(
                    'element'   => 'layout',
                    'value'     => array('masonry')
                ),
                'group' 		=> __('Layout Setting', 'lastudio')
            ),

            array(
                'type' => 'la_number',
                'heading' => __('Container Width', 'lastudio'),
                'param_name' => 'base_container_w',
                'description' => __('This value will determine the number of items per row', 'lastudio'),
                'value' => 1170,
                'min' => 800,
                'max' => 1920,
                'suffix' => 'px',
                'dependency'    => array(
                    'element'   => 'column_type',
                    'value'     => 'custom'
                ),
                'group' => __('Layout Setting', 'lastudio')
            ),

            array(
                'type' => 'la_number',
                'heading' => __('Item Width', 'lastudio'),
                'param_name' => 'base_item_w',
                'description' => __('Set your item default width', 'lastudio'),
                'value' => 300,
                'min' => 100,
                'max' => 1920,
                'suffix' => 'px',
                'dependency'        => array(
                    'element'   => 'column_type',
                    'value'     => 'custom'
                ),
                'group' => __('Layout Setting', 'lastudio')
            ),

            array(
                'type' => 'la_number',
                'heading' => __('Item Height', 'lastudio'),
                'description' => __('Set your item default height', 'lastudio'),
                'param_name' => 'base_item_h',
                'value' => 300,
                'min' => 100,
                'max' => 1920,
                'suffix' => 'px',
                'dependency'        => array(
                    'element'   => 'column_type',
                    'value'     => 'custom'
                ),
                'group' => __('Layout Setting', 'lastudio')
            ),

            array(
                'type' 			=> 'la_column',
                'heading' 		=> __('[Mobile] Items to show', 'lastudio'),
                'param_name' 	=> 'mb_columns',
                'unit'			=> '',
                'media'			=> array(
                    'md'	=> 2,
                    'sm'	=> 2,
                    'xs'	=> 1,
                    'mb'	=> 1
                ),
                'dependency'        => array(
                    'element'   => 'column_type',
                    'value'     => 'custom'
                ),
                'group' => __('Layout Setting', 'lastudio')
            ),

            array(
                'type'       => 'checkbox',
                'heading'    => __( 'Enable Custom Item Setting', 'lastudio' ),
                'param_name' => 'custom_item_size',
                'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
                'dependency'        => array(
                    'element'   => 'column_type',
                    'value'     => 'custom'
                ),
                'group' => __('Layout Setting', 'lastudio')
            ),

            array(
                'type' => 'param_group',
                'param_name' => 'item_sizes',
                'heading' => __( 'Item Sizes', 'lastudio' ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Width','lastudio'),
                        'description' 	=> __('it will occupy x width of base item width ( example: this item will be occupy 2x width of base width you need entered "2")', 'lastudio'),
                        'param_name' => 'w',
                        'admin_label' => true,
                        'value' => array(
                            __('1/2x width','lastudio')    => '0.5',
                            __('1x width','lastudio')      => '1',
                            __('1.5x width','lastudio')    => '1.5',
                            __('2x width','lastudio')      => '2',
                            __('2.5x width','lastudio')    => '2.5',
                            __('3x width','lastudio')      => '3',
                            __('3.5x width','lastudio')    => '3.5',
                            __('4x width','lastudio')      => '4',
                        ),
                        'std' => '1'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Height','lastudio'),
                        'description' 	=> __('it will occupy x height of base item height ( example: this item will be occupy 2x height of base height you need entered "2")', 'lastudio'),
                        'param_name' => 'h',
                        'admin_label' => true,
                        'value' => array(
                            __('1/2x height','lastudio')    => '0.5',
                            __('1x height','lastudio')      => '1',
                            __('1.5x height','lastudio')    => '1.5',
                            __('2x height','lastudio')      => '2',
                            __('2.5x height','lastudio')    => '2.5',
                            __('3x height','lastudio')      => '3',
                            __('3.5x height','lastudio')    => '3.5',
                            __('4x height','lastudio')      => '4',
                        ),
                        'std' => '1'
                    )
                ),
                'dependency' => array(
                    'element'   => 'custom_item_size',
                    'value'     => 'yes'
                ),
                'group' => __('Layout Setting', 'lastudio')
            ),

            self::fieldColumn(array(
                'heading' 		=> __('Items to show', 'lastudio'),
                'param_name' 	=> 'columns',
                'dependency' => array(
                    'callback' => 'laWoocommerceProductColumnsDependencyCallback',
                ),
                'group' 		=> __('Layout Setting', 'lastudio')
            )),

            array(
                'type' => 'dropdown',
                'heading' => __( 'Display Style', 'lastudio' ),
                'param_name' => 'display_style',
                'value' => array(
                    __( 'Show All', 'lastudio' ) => 'all',
                    __( 'Load more button', 'lastudio' ) => 'load-more',
                    __( 'Pagination', 'lastudio' ) => 'pagination',
                ),
                'std' => 'all',
                'save_always' => true,
                'description' => __('Select display style', 'lastudio'),
                'group' 		=> __('Layout Setting', 'lastudio')
            ),

            array(
                'type' => 'textfield',
                'heading' => __( 'Load more text', 'lastudio' ),
                'param_name' => 'load_more_text',
                'dependency' => array(
                    'element'   => 'display_style',
                    'value'     => 'load-more'
                ),
                'group' 		=> __('Layout Setting', 'lastudio')
            ),

            array(
                'type'       => 'checkbox',
                'heading'    => __('Enable slider', 'lastudio' ),
                'param_name' => 'enable_carousel',
                'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
                'dependency' => array(
                    'element'   => 'layout',
                    'value'     => 'grid'
                ),
                'group' 		=> __('Layout Setting', 'lastudio')
            ),

            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Ajax Loading', 'lastudio' ),
                'param_name' => 'enable_ajax_loader',
                'value' => array( __( 'Yes', 'lastudio' ) => 'yes' ),
                'group' 		=> __('Layout Setting', 'lastudio')
            ),

            self::fieldElementID(array(
                'group' 		=> __('Layout Setting', 'lastudio')
            )),

            self::fieldExtraClass(array(
                'group' 		=> __('Layout Setting', 'lastudio')
            ))
        );

    }
}