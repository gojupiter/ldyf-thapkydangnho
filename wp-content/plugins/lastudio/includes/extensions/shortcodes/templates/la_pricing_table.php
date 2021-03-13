<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$la_fix_css = array();

$style = $show_icon = $icon_type = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypo = $icon_linecons = $icon_monosocial = $icon_la_icon_outline = $icon_nucleo_glyph = $icon_image_id = $package_title = $package_desc = $package_price = $price_period = $features = $button_text = $button_link = $is_highlight = $custom_badge = $shortcode_id = $el_class = $icon__typography = $icon_lh = $icon_fz = $icon_color = $icon_bg_color = $package_title__typography = $use_gfont_package_title = $package_title_font = $package_title_fz = $package_title_lh = $package_title_color = $package_price__typography = $use_gfont_package_price = $package_price_font = $package_price_fz = $package_price_lh = $package_price_color = $price_period__typography = $use_gfont_price_period = $price_period_font = $price_period_fz = $price_period_lh = $price_period_color = $package_desc__typography = $use_gfont_package_desc = $package_desc_font = $package_desc_fz = $package_desc_lh = $package_desc_color = $package_features__typography = $use_gfont_package_features = $package_features_font = $package_features_fz = $package_features_lh = $package_features_color = $package_features_highlight_color = $icon__package_button = $use_gfont_package_button = $package_button_font = $package_button_fz = $package_button_lh = $package_button_color = $package_button_bg_color = $package_button_hover_color = $package_button_hover_bg_color = '';
$icon_la_svg = '';

$atts = shortcode_atts( array(
    'style' => '1',
    'show_icon' => 'no',
    'icon_type' => 'fontawesome',
    'icon_fontawesome' => 'fa fa-info-circle',
    'icon_openiconic' => '',
    'icon_typicons' => '',
    'icon_entypo' => '',
    'icon_linecons' => '',
    'icon_monosocial' => 'vc-mono vc-mono-fivehundredpx',
    'icon_la_icon_outline' => 'la-icon design-2_image',
    'icon_nucleo_glyph' => 'nc-icon-glyph nature_bear',
    'icon_la_svg'   => '',
    'icon_image_id' => '',
    'package_title' => '',
    'package_desc' => '',
    'package_price' => '',
    'price_period' => '',
    'features' => '',
    'button_text' => 'View More',
    'button_link' => '',
    'is_highlight' => '',
    'custom_badge' => 'Recommended',
    'shortcode_id' => '',
    'el_class' => '',
    'icon__typography' => '',
    'icon_lh' => '',
    'icon_fz' => '',
    'icon_color' => '',
    'icon_bg_color' => '',
    'package_title__typography' => '',
    'use_gfont_package_title' => '',
    'package_title_font' => '',
    'package_title_fz' => '',
    'package_title_lh' => '',
    'package_title_color' => '',
    'package_price__typography' => '',
    'use_gfont_package_price' => '',
    'package_price_font' => '',
    'package_price_fz' => '',
    'package_price_lh' => '',
    'package_price_color' => '',
    'price_period__typography' => '',
    'use_gfont_price_period' => '',
    'price_period_font' => '',
    'price_period_fz' => '',
    'price_period_lh' => '',
    'price_period_color' => '',
    'package_desc__typography' => '',
    'use_gfont_package_desc' => '',
    'package_desc_font' => '',
    'package_desc_fz' => '',
    'package_desc_lh' => '',
    'package_desc_color' => '',
    'package_features__typography' => '',
    'use_gfont_package_features' => '',
    'package_features_font' => '',
    'package_features_fz' => '',
    'package_features_lh' => '',
    'package_features_color' => '',
    'package_features_highlight_color' => '',
    'icon__package_button' => '',
    'use_gfont_package_button' => '',
    'package_button_font' => '',
    'package_button_fz' => '',
    'package_button_lh' => '',
    'package_button_color' => '',
    'package_button_bg_color' => '',
    'package_button_hover_color' => '',
    'package_button_hover_bg_color' => ''
), $atts );

extract( $atts );

$unique_id = $shortcode_id ? $shortcode_id : uniqid('la_pricing_');

$decode_features = urldecode($features);
$features = json_decode($decode_features,true);
if($decode_features == '[{}]'){
    $features = array();
}

$css_class = array(
    'pricing',
    'wpb_content_element',
    'style-' . $style
);
if($is_highlight == 'yes'){
    $css_class[] = 'pricing__item--featured';
}

$css_class = implode(' ', $css_class) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );


$button_link = ( '||' === $button_link ) ? '' : $button_link;
$button_link = la_parse_multi_attribute( $button_link, array( 'url' => '', 'title' => '', 'target' => '_self', 'rel' => '' ) );

// Build title
$packageTitleCssInline = array();
$packageTitleHtmlAtts = '';
if(!empty($package_title_fz) || !empty($package_title_lh)){
    $packageTitleHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .pricing__item .pricing__title',
        'media_sizes' => array(
            'font-size' => $package_title_fz,
            'line-height' => $package_title_lh
        )
    ));
}
if(!empty($package_title_color)){
    $packageTitleCssInline[] = "color:{$package_title_color}";
}
if(!empty($use_gfont_package_title)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_title_font);
    if(isset($gfont_data['style'])){
        $packageTitleCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build Description
$packageDescCssInline = array();
$packageDescHtmlAtts = '';
if(!empty($package_desc_fz) || !empty($package_desc_lh)){
    $packageDescHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .pricing__item .pricing__desc',
        'media_sizes' => array(
            'font-size' => $package_desc_fz,
            'line-height' => $package_desc_lh
        )
    ));
}
if(!empty($package_desc_color)){
    $packageDescCssInline[] = "color:{$package_desc_color}";
}
if(!empty($use_gfont_package_desc)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_desc_font);
    if(isset($gfont_data['style'])){
        $packageDescCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}


// Build price
$packagePriceCssInline = array();
$packagePriceHtmlAtts = '';
if(!empty($package_price_fz) || !empty($package_price_lh)){
    $packagePriceHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .pricing__item .pricing__price',
        'media_sizes' => array(
            'font-size' => $package_price_fz,
            'line-height' => $package_price_lh
        )
    ));
}
if(!empty($package_price_color)){
    $packagePriceCssInline[] = "color:{$package_price_color}";
}
if(!empty($use_gfont_package_price)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_price_font);
    if(isset($gfont_data['style'])){
        $packagePriceCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build price period
$packagePriceUnitCssInline = array();
$packagePriceUnitHtmlAtts = '';
if(!empty($price_period_fz) || !empty($price_period_lh)){
    $packagePriceUnitHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .pricing__item .pricing__period',
        'media_sizes' => array(
            'font-size' => $price_period_fz,
            'line-height' => $price_period_lh
        )
    ));
}
if(!empty($price_period_color)){
    $packagePriceUnitCssInline[] = "color:{$price_period_color}";
}
if(!empty($use_gfont_price_period)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($use_gfont_price_period);
    if(isset($gfont_data['style'])){
        $packagePriceUnitCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build Package Featured
$packageFeaturesCssInline = array();
$packageFeaturesHtmlAtts = '';
if(!empty($package_features_fz) || !empty($package_features_lh)){
    $packageFeaturesHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .pricing__item .pricing__feature-list',
        'media_sizes' => array(
            'font-size' => $package_features_fz,
            'line-height' => $package_features_lh
        )
    ));
}
if(!empty($package_features_color)){
    $packageFeaturesCssInline[] = "color:{$package_features_color}";
}
if(!empty($use_gfont_package_features)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_features_font);
    if(isset($gfont_data['style'])){
        $packageFeaturesCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build action
$packageActionCssInline = array();
$packageActionHtmlAtts = '';
if(!empty($package_button_fz) || !empty($package_button_lh)){
    $packageActionHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .pricing__item .pricing__action a',
        'media_sizes' => array(
            'font-size' => $package_button_fz,
            'line-height' => $package_button_lh
        )
    ));
}

if(!empty($use_gfont_package_button)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_button_font);
    if(isset($gfont_data['style'])){
        $packageActionCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build Package Icon
$iconInnerHTML = '';
$packageIconCssInline = array();
$packageIconHtmlAtts = '';
if($icon_type == 'custom'){
    if( $__icon_html = wp_get_attachment_image($icon_image_id, 'full') ) {
        $iconInnerHTML = $__icon_html;
    }
}
else{

    if(!empty( ${'icon_' . $icon_type} )){

        if ($icon_type == 'la_svg') {
            $svg_path = plugin_dir_url(dirname(dirname(dirname(dirname(__FILE__)))));
            $svg_path .= 'public/svg/' . str_replace('la-svg la-svg-', '', $icon_la_svg) . '.svg';
            $iconInnerHTML = '<span data-la_component="InlineSVG" class="js-el la-svg" data-svg="' . $svg_path . '" data-type="oneByOne" data-hover="#' . $unique_id . '"></span>';
        }
        else {
            $iconClass = isset(${'icon_' . $icon_type}) ? esc_attr(${'icon_' . $icon_type}) : 'fa fa-adjust';
            $iconInnerHTML = '<span><i class="' . esc_attr($iconClass) . '"></i></span>';
        }

        if(function_exists('vc_icon_element_fonts_enqueue')){
            vc_icon_element_fonts_enqueue( $icon_type );
        }
    }
}
$packageIconHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
    'target' => '#' . $unique_id . ' .pricing__item .pricing__wrap_icon .pricing__icon',
    'media_sizes' => array(
        'font-size' => $icon_fz,
        'line-height' => $icon_lh,
        'width' => $icon_lh,
        'height' => $icon_lh
    )
));

if(!empty($icon_color)){
    $packageIconCssInline[] = "color:{$icon_color}";
}

?>
<div id="<?php echo esc_attr($unique_id);?>" class="<?php echo esc_attr($css_class)?>">
    <div class="pricing__item">
        <?php
        if($is_highlight){
            echo sprintf('<div class="pricing__badge"><span>%s</span></div>', esc_html($custom_badge));
        }

        if($show_icon == 'yes' && !empty($iconInnerHTML)){
            echo sprintf('<div class="pricing__wrap_icon"><div class="pricing__icon js-el" style="%s"%s>%s</div></div>',
                esc_attr( implode(';', $packageIconCssInline) ),
                $packageIconHtmlAtts,
                $iconInnerHTML
            );
        }

        echo sprintf(
            '<div class="pricing__title js-el" style="%s"%s>%s</div>',
            esc_attr(join(';',$packageTitleCssInline)),
            $packageTitleHtmlAtts,
            esc_html($package_title)
        );

        echo sprintf(
            '<div class="pricing__desc js-el" style="%s"%s>%s</div>',
            esc_attr(join(';', $packageDescCssInline)),
            $packageDescHtmlAtts,
            $package_desc
        );

        if(!empty($package_price) || !empty($price_period)){
            $supPrice = preg_replace('/[^0-9\.\,]+/', '<sup class="pricing__currency">$0</sup>', $package_price);
            echo sprintf(
                '<div class="pricing__price-box"><div class="pricing__price js-el" style="%s"%s>%s</div><span class="pricing__period js-el" style="%s"%s>%s</span></div>',
                esc_attr( join(';', $packagePriceCssInline) ),
                $packagePriceHtmlAtts,
                $supPrice,
                esc_attr( join(';', $packagePriceUnitCssInline) ),
                $packagePriceUnitHtmlAtts,
                $price_period
            );
        }

        if(count($features) > 0){
            $featuresInnerHTML = '<ul>';
            foreach ($features as $feature){
                $featuresInnerHTML .= sprintf('<li class="pricing__feature"><span>%s</span>%s</li>',
                    ( !empty($feature['highlight']) ? '<strong>'.$feature['highlight'].' </strong>' : '' ) . ( !empty($feature['text']) ? $feature['text'] : ''),
                    ( !empty($feature['icon']) ? '<i class="'.esc_attr($feature['icon']).'"></i>' : '')
                );
            }
            $featuresInnerHTML .= '</ul>';

            echo sprintf(
                '<div class="pricing__feature-list js-el" style="%s"%s>%s</div>',
                esc_attr( implode(';', $packageFeaturesCssInline) ),
                $packageFeaturesHtmlAtts,
                $featuresInnerHTML
            );
        }

        if(!empty($button_link['url'])){
            echo sprintf('<div class="pricing__action"><a class="js-el" href="%s" target="%s" title="%s" style="%s"%s><span>%s</span></a></div>',
                esc_url($button_link['url']),
                esc_attr($button_link['target']),
                esc_attr($button_link['title']),
                esc_attr( join(';', $packageActionCssInline) ),
                $packageActionHtmlAtts,
                esc_html($button_text)
            );
        }

        ?>
    </div>
</div>