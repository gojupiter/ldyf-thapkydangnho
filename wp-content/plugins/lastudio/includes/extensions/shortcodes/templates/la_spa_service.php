<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$banner_id = $style = $banner_link = $title = $el_class = $el_class1 = $el_class2 = $title__typography = $use_gfont_title = $title_font = $title_fz = $title_lh = $title_color = $content__typography = $use_gfont_content = $content_font = $content_fz = $content_lh = $content_color = $el_id = "";

$bg_color = '';

$la_fix_css = array();

$atts = shortcode_atts(array(
    'banner_id' => '',
    'style' => '1',
    'banner_link' => '',
    'title' => '',
    'el_class' => '',
    'el_id' => '',
    'el_class1' => '',
    'el_class2' => '',
    'title__typography' => '',
    'use_gfont_title' => '',
    'title_font' => '',
    'title_fz' => '',
    'title_lh' => '',
    'title_color' => '',
    'content__typography' => '',
    'use_gfont_content' => '',
    'content_font' => '',
    'content_fz' => '',
    'content_lh' => '',
    'content_color' => '',
    'bg_color' => '',
), $atts );

$title_html = $content_html = '';


$a_href = $a_title = $a_target = '';
$a_attributes = array();

$la_fix_css = array();

extract( $atts );


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
    $a_title = $title;
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
    $unique_id = uniqid('la_service_');
}

$_tmp_class = array(
    'la-service-box',
    'service-type-' . $style
);

$css_class = implode(' ', $_tmp_class) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );


if(!empty($title)){
    $el_class1 = 'js-el b-title b-title1' .  LaStudio_Shortcodes_Helper::getExtraClass($el_class1);
    $title_html_attr = '';
    $title_css_inline = array();
    if(!empty($title_fz) || !empty($title_lh)){
        $title_html_attr = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .b-title1',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .b-title1',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            ),
        ));
    }
    if(!empty($title_color)){
        $title_css_inline[] = "color:{$title_color}";
    }
    if(!empty($use_gfont_title)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($title_font);
        if(isset($gfont_data['style'])){
            $title_css_inline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }

    if($use_link){
        $title =  sprintf(
            '<a %s>%s</a>',
            $a_attributes,
            esc_html($title)
        );
    }

    $title_html = sprintf(
        '<%1$s class="%2$s" style="%3$s" %4$s>%5$s</%1$s>',
        'div',
        $el_class1,
        esc_attr( implode(';', $title_css_inline) ),
        $title_html_attr,
        $title
    );
}

if(!empty($content)){
    $el_class2 = 'js-el b-title b-title2' .  LaStudio_Shortcodes_Helper::getExtraClass($el_class2);
    $content_html_attr = '';
    $content_css_inline = array();
    if(!empty($content_fz) || !empty($content_lh)){
        $content_html_attr = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .b-title2',
            'media_sizes' => array(
                'font-size' => $content_fz,
                'line-height' => $content_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .b-title2',
            'media_sizes' => array(
                'font-size' => $content_fz,
                'line-height' => $content_lh
            ),
        ));
    }
    if(!empty($content_color)){
        $content_css_inline[] = "color:{$content_color}";
    }
    if(!empty($use_gfont_content)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($content_font);
        if(isset($gfont_data['style'])){
            $content_css_inline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }

    $content = '<p>' . $content . '</p>';

    $content_html = sprintf(
        '<%1$s class="%2$s" style="%3$s" %4$s>%5$s</%1$s>',
        'div',
        $el_class2,
        esc_attr( implode(';', $content_css_inline) ),
        $content_html_attr,
        $content
    );
}


?>
<div id="<?php echo esc_attr($unique_id)?>" class="<?php echo esc_attr($css_class);?>">
    <div class="box-inner">
        <?php
        if(!empty($banner_id) && wp_attachment_is_image($banner_id)){
            list ($banner_image_src, $banner_image_width, $banner_image_height) = wp_get_attachment_image_src( $banner_id, 'full' );
            $banner_image_src = str_replace(array('http://','https://'), '//', $banner_image_src);
            $banner_image_width = absint($banner_image_width);
            $banner_image_height = absint($banner_image_height);
        ?>
        <div class="banner--image">
            <div class="la-lazyload-image" data-background-image="<?php echo esc_attr($banner_image_src) ?>"<?php
            if($banner_image_height > 0 && $banner_image_height > 0){
                $percent = round( ($banner_image_height/$banner_image_width) * 100, 2 );
                echo ' style="padding-bottom: ' . $percent . '%"';
            }
            ?>><?php
                if($use_link){
                    echo sprintf(
                        '<a class="item--overlay" %s><span class="hidden"><span>%s</span></span></a>',
                        $a_attributes,
                        esc_html($a_title)
                    );
                }
                ?></div>
        </div>
        <?php } ?>
        <div class="banner--info"<?php
        if( !empty($bg_color) ) {
            echo ' style="background-color: '. esc_attr($bg_color) .';"';
        }
        ?>>
            <?php echo $title_html; ?>
            <div class="banner--info-content">
                <?php
                echo $content_html;
                if($use_link){
                    echo sprintf(
                        '<p><a class="banner--btn" %s>%s</a></p>',
                        $a_attributes,
                        esc_html($a_title)
                    );
                }
                ?>
            </div>
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
</div>
<?php LaStudio_Shortcodes_Helper::renderResponsiveMediaStyleTags($la_fix_css); ?>