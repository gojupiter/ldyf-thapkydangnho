<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$banner_id = $style = $banner_link = $title_1 = $title_2 = $title_3 = $el_class = $el_class1 = $el_class2 = $el_class3 = $title_1__typography = $use_gfont_title_1 = $title_1_font = $title_1_fz = $title_1_lh = $title_1_color = $title_2__typography = $use_gfont_title_2 = $title_2_font = $title_2_fz = $title_2_lh = $title_2_color = $title_3__typography = $use_gfont_title_3 = $title_3_font = $title_3_fz = $title_3_lh = $title_3_color = "";

$height = $height_mode = $overlay_bg_color = $overlay_hover_bg_color = $el_id = '';

$la_fix_css = array();

$atts = shortcode_atts(array(
    'banner_id' => '',
    'height_mode' => 'original',
    'height' => '',
    'overlay_bg_color' => '',
    'overlay_hover_bg_color' => '',
    'style' => '1',
    'el_id' => '',
    'banner_link' => '',
    'title_1' => '',
    'title_2' => '',
    'title_3' => '',
    'el_class' => '',
    'el_class1' => '',
    'el_class2' => '',
    'el_class3' => '',
    'title_1__typography' => '',
    'use_gfont_title_1' => '',
    'title_1_font' => '',
    'title_1_fz' => '',
    'title_1_lh' => '',
    'title_1_color' => '',
    'title_2__typography' => '',
    'use_gfont_title_2' => '',
    'title_2_font' => '',
    'title_2_fz' => '',
    'title_2_lh' => '',
    'title_2_color' => '',
    'title_3__typography' => '',
    'use_gfont_title_3' => '',
    'title_3_font' => '',
    'title_3_fz' => '',
    'title_3_lh' => '',
    'title_3_color' => ''
), $atts );

$title_1_html = $title_2_html = $title_3_html = '';


$a_href = $a_title = $a_target = '';
$a_attributes = array();

$la_fix_css = array();

extract( $atts );

if(empty($banner_id) || !wp_attachment_is_image($banner_id)){
    return;
}

//parse link
$banner_link = ( '||' === $banner_link ) ? '' : $banner_link;
$banner_link = la_build_link_from_atts( $banner_link );

$use_link = false;
if ( strlen( $banner_link['url'] ) > 0 ) {
    $use_link = true;
    $a_href = $banner_link['url'];
    $a_title = $banner_link['title'];
    $a_target = $banner_link['target'];
}

if(empty($a_title)){
    $a_title = $title_1;
}

if ( $use_link ) {
    $a_attributes[] = 'href="' . esc_url( trim( $a_href ) ) . '"';
    $a_attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
    if ( ! empty( $a_target ) ) {
        $a_attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
    }
    $a_attributes = implode( ' ', $a_attributes );
}

if(!empty($el_id)){
    $unique_id = $el_id;
}
else{
    $unique_id = uniqid('la_banner_box_');
}

$_tmp_class = array(
    'wpb_content_element',
    'la-banner-box',
    'banner-type-' . $style
);

$_tmp_class[] = $unique_id;


$el_class3 = 'js-el b-title b-title3' .  LaStudio_Shortcodes_Helper::getExtraClass($el_class3);

$css_class = implode(' ', $_tmp_class) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );


if(!empty($title_1)){
    $el_class1 = 'js-el b-title b-title1' .  LaStudio_Shortcodes_Helper::getExtraClass($el_class1);
    $title_1_html_attr = '';
    $title_1_css_inline = array();
    if(!empty($title_1_fz) || !empty($title_1_lh)){
        $title_1_html_attr = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .b-title1',
            'media_sizes' => array(
                'font-size' => $title_1_fz,
                'line-height' => $title_1_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .b-title1',
            'media_sizes' => array(
                'font-size' => $title_1_fz,
                'line-height' => $title_1_lh
            ),
        ));
    }
    if(!empty($title_1_color)){
        $title_1_css_inline[] = "color:{$title_1_color}";
    }
    if(!empty($use_gfont_title_1)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($title_1_font);
        if(isset($gfont_data['style'])){
            $title_1_css_inline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }
    $title_1_html = sprintf(
        '<%1$s class="%2$s" style="%3$s" %4$s>%5$s</%1$s>',
        'div',
        $el_class1,
        esc_attr( implode(';', $title_1_css_inline) ),
        $title_1_html_attr,
        esc_html($title_1)
    );
}

if(!empty($title_2)){
    $el_class2 = 'js-el b-title b-title2' .  LaStudio_Shortcodes_Helper::getExtraClass($el_class2);
    $title_2_html_attr = '';
    $title_2_css_inline = array();
    if(!empty($title_2_fz) || !empty($title_2_lh)){
        $title_2_html_attr = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .b-title2',
            'media_sizes' => array(
                'font-size' => $title_2_fz,
                'line-height' => $title_2_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .b-title2',
            'media_sizes' => array(
                'font-size' => $title_2_fz,
                'line-height' => $title_2_lh
            ),
        ));
    }
    if(!empty($title_2_color)){
        $title_2_css_inline[] = "color:{$title_2_color}";
    }
    if(!empty($use_gfont_title_2)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($title_2_font);
        if(isset($gfont_data['style'])){
            $title_2_css_inline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }
    $title_2_html = sprintf(
        '<%1$s class="%2$s" style="%3$s" %4$s>%5$s</%1$s>',
        'div',
        $el_class2,
        esc_attr( implode(';', $title_2_css_inline) ),
        $title_2_html_attr,
        esc_html($title_2)
    );
}

if(!empty($title_3)){
    $el_class3 = 'js-el b-title b-title3' .  LaStudio_Shortcodes_Helper::getExtraClass($el_class3);
    $title_3_html_attr = '';
    $title_3_css_inline = array();
    if(!empty($title_3_fz) || !empty($title_3_lh)){
        $title_3_html_attr = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .b-title3',
            'media_sizes' => array(
                'font-size' => $title_3_fz,
                'line-height' => $title_3_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .b-title3',
            'media_sizes' => array(
                'font-size' => $title_3_fz,
                'line-height' => $title_3_lh
            ),
        ));
    }
    if(!empty($title_3_color)){
        $title_3_css_inline[] = "color:{$title_3_color}";
    }
    if(!empty($use_gfont_title_3)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($title_3_font);
        if(isset($gfont_data['style'])){
            $title_3_css_inline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }
    $title_3_html = sprintf(
        '<%1$s class="%2$s" style="%3$s" %4$s>%5$s</%1$s>',
        'div',
        $el_class3,
        esc_attr( implode(';', $title_3_css_inline) ),
        $title_3_html_attr,
        esc_html($title_3)
    );
}

?>
<div id="<?php echo esc_attr($unique_id)?>" class="<?php echo esc_attr($css_class);?>">
    <div class="box-inner">
        <?php

        $thumb_css_style = '';
        $thumb_css_class = ' gitem-zone-height-mode-' . $height_mode;
        $banner_image_width = $banner_image_height = 0;

        // Jetpack issue, Photon is not giving us the image dimensions.
        // This snippet gets the dimensions for us.
        add_filter( 'jetpack_photon_override_image_downsize', '__return_true' );
        $image_info = wp_get_attachment_image_src( $banner_id, 'full' );
        remove_filter( 'jetpack_photon_override_image_downsize', '__return_true' );

        list ($banner_image_src, $banner_image_width, $banner_image_height) = $image_info;

        $banner_image_src = wp_get_attachment_image_url($banner_id, 'full');

        $banner_image_src = str_replace(array('http://','https://'), '//', $banner_image_src);
        $banner_image_width = absint($banner_image_width);
        $banner_image_height = absint($banner_image_height);

        if( $banner_image_width > 0 && $banner_image_height > 0 ) {
            $thumb_css_style .= 'padding-top:' . round( ($banner_image_height/$banner_image_width) * 100, 2 ) . '%;';
        }

        if ( 'custom' === $height_mode ) {
            if ( strlen( $height ) > 0 ) {
                if ( preg_match( '/^\d+$/', $height ) ) {
                    $height .= 'px';
                }
                $thumb_css_style .= 'padding-top: ' . $height . ';';
                $thumb_css_class .= ' gitem-hide-img';
            }
        }
        elseif ( 'original' !== $height_mode ) {
            $thumb_css_class .= ' gitem-hide-img gitem-zone-height-mode-auto' . ( strlen( $height_mode ) > 0 ? ' gitem-zone-height-mode-auto-' . $height_mode : '' );
        }

        $use_lazy_load = apply_filters('LaStudio/helper/use_lazy_load', true);

        if($use_lazy_load){
            $thumb_css_class .= ' la-lazyload-image';
        }
        else{
            $thumb_css_style .= sprintf('background-image: url(%s);', esc_url($banner_image_src));
        }

        ?>
        <div class="banner--image">
            <div class="loop__item__thumbnail--bkg<?php echo esc_attr($thumb_css_class); ?>"
                 data-background-image="<?php echo esc_url($banner_image_src); ?>"
                 style="<?php echo esc_attr($thumb_css_style); ?>">
            </div>
        </div>
        <div class="banner--info">
            <?php
                echo $title_1_html . $title_2_html . $title_3_html;
                if($use_link){
                    echo sprintf(
                        '<a class="banner--btn" %s>%s</a>',
                        $a_attributes,
                        esc_html($a_title)
                    );
                }
            ?>
        </div>
        <?php
            if($use_link){
                echo sprintf(
                    '<a class="banner--link-overlay item--overlay" %s><span class="hidden"><span>%s</span></span></a>',
                    $a_attributes,
                    esc_html($a_title)
                );
            }
            else{
                echo '<div class="banner--link-overlay item--overlay"></div>';
            }
        ?>
    </div>
    <?php if(!empty($overlay_bg_color) || !empty($overlay_hover_bg_color)): ?>
    <style>
        <?php if(!empty($overlay_bg_color)): ?>
        .<?php echo esc_attr($unique_id)?>.la-banner-box .banner--image:after{
            background-color: <?php echo $overlay_bg_color ?>;
        }
        <?php endif; ?>
        <?php if(!empty($overlay_hover_bg_color)): ?>
        .<?php echo esc_attr($unique_id)?>.la-banner-box:hover .banner--image:after{
            background-color: <?php echo $overlay_hover_bg_color ?>;
        }
        <?php endif; ?>
    </style>
    <?php endif; ?>
</div>
<?php LaStudio_Shortcodes_Helper::renderResponsiveMediaStyleTags($la_fix_css); ?>