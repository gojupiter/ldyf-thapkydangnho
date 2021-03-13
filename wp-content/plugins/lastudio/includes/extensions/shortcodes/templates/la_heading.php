<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$la_fix_css = array();

$output = $line_class = '';
$title = $tag = $alignment = $spacer = $spacer_position = $line_style = $line_width = $line_height = $line_color = $el_class = $title_class = $subtitle_class = $css = '';
$use_gfont_title = $title_font = $title_fz = $title_lh = $title_color = $use_gfont_subtitle = $subtitle_font = $subtitle_fz = $subtitle_lh = $subtitle_color = '';

$spacer_html = '';
$heading_html = '';
$subheading_html = '';

$atts = shortcode_atts(array(
    'title' => '',
    'tag' => 'h2',
    'alignment' => 'center',
    'spacer' => 'none',
    'spacer_position' => 'top',
    'line_style' => 'solid',
    'line_width' => '',
    'line_height' => 1,
    'line_color' => '',
    'el_class' => '',
    'title_class' => '',
    'subtitle_class' => '',
    'line_class' => '',
    'title__typography' => '',
    'use_gfont_title' => '',
    'title_font' => '',
    'title_fz' => '',
    'title_lh' => '',
    'title_color' => '',
    'subtitle__typography' => '',
    'use_gfont_subtitle' => '',
    'subtitle_font' => '',
    'subtitle_fz' => '',
    'subtitle_lh' => '',
    'subtitle_color' => '',
    'css' => ''
), $atts );
extract( $atts );

$_tmp_class = "la-headings text-{$alignment}";
if($spacer == 'line'){
    if($spacer_position == 'left' || $spacer_position == 'right'){
        $_tmp_class .= " spacer-position-separator sp_at-{$spacer_position}";
    }
    else{
        $_tmp_class .= " spacer-position-{$spacer_position}";
    }
}

$title_class = 'js-el heading-tag la-unit-responsive' . LaStudio_Shortcodes_Helper::getExtraClass($title_class);
$subtitle_class = 'js-el subheading-tag la-unit-responsive' . LaStudio_Shortcodes_Helper::getExtraClass($subtitle_class);
$class_to_filter = $_tmp_class . la_shortcode_custom_css_class( $css, ' ' ) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );
$css_class = $class_to_filter;

$unique_id = uniqid('la_heading_');

if($spacer == 'line'){
    $lineHtmlAtts = '';
    $lineCssInline = array();
    $parentLineCssInline = array();
    $parentLineCssInline[] = "height:{$line_height}px";
    if($spacer_position == 'separator' || $spacer_position == 'left' || $spacer_position == 'right'){
        $parentLineCssInline[] = "margin-top:{$line_height}px";
    }
    if(!empty($line_width)){
        $lineHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target'		=> "#{$unique_id} .la-line",
            'media_sizes' 	=> array(
                'width' 	=> $line_width,
            )
        ));

        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target'		=> "#{$unique_id} .la-line",
            'media_sizes' 	=> array(
                'width' 	=> $line_width,
            )
        ));
    }
    $lineCssInline[] = "border-style:{$line_style}";
    $lineCssInline[] = "border-width:{$line_height}px 0 0";
    $lineCssInline[] = "border-color:{$line_color}";
    $spacer_html = sprintf(
        '<div class="la-separator %s" style="%s"><span class="la-line js-el la-unit-responsive" style="%s" %s></span></div>',
        esc_attr( LaStudio_Shortcodes_Helper::getExtraClass($line_class) ),
        esc_attr( implode(';', $parentLineCssInline) ),
        esc_attr( implode(';', $lineCssInline) ),
        $lineHtmlAtts
    );
}

if(!empty($title)){
    $titleHtmlAtts = '';
    $titleCssInline = array();
    if(!empty($title_fz) || !empty( $title_lh)){
        $titleHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .heading-tag',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .heading-tag',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            ),
        ));
    }
    if(!empty($title_color)){
        $titleCssInline[] = "color:{$title_color}";
    }
    if(!empty($use_gfont_title)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($title_font);
        if(isset($gfont_data['style'])){
            $titleCssInline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }
    $heading_html = sprintf(
        '<%1$s class="%2$s" style="%3$s" %4$s>%5$s</%1$s>',
        $tag,
        $title_class,
        esc_attr( implode(';', $titleCssInline) ),
        $titleHtmlAtts,
        esc_html($title)
    );
}

if(!empty($content)){
    $subtitleHtmlAtts = '';
    $subtitleCssInline = array();
    if(!empty($subtitle_fz) || !empty( $subtitle_lh)){
        $subtitleHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .subheading-tag',
            'media_sizes' => array(
                'font-size' => $subtitle_fz,
                'line-height' => $subtitle_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .subheading-tag',
            'media_sizes' => array(
                'font-size' => $subtitle_fz,
                'line-height' => $subtitle_lh
            ),
        ));
    }
    if(!empty($subtitle_color)){
        $subtitleCssInline[] = "color:{$subtitle_color}";
    }
    if(!empty($use_gfont_subtitle)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($subtitle_font);
        if(isset($gfont_data['style'])){
            $subtitleCssInline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }
    $subheading_html = sprintf(
        '<div class="%1$s" style="%2$s" %3$s>%4$s</div>',
        $subtitle_class,
        esc_attr( implode(';', $subtitleCssInline) ),
        $subtitleHtmlAtts,
        LaStudio_Shortcodes_Helper::remove_js_autop($content,true)
    );
}
?>
<div id="<?php echo esc_attr($unique_id)?>" class="<?php echo esc_attr($css_class);?>"><?php
    if($spacer_position == 'top'){
        echo $spacer_html;
    }
    if($spacer_position == 'separator' || $spacer_position == 'left' || $spacer_position == 'right'){
        echo '<div class="heading-with-line">';
        echo $spacer_html;
        if(empty($heading_html)){
            echo $subheading_html;
            $subheading_html = '';
        }
    }
    echo $heading_html;
    if($spacer_position == 'separator' || $spacer_position == 'left' || $spacer_position == 'right'){
        echo $spacer_html;
        echo '</div>';
    }
    if($spacer_position == 'middle'){
        echo $spacer_html;
    }
    echo $subheading_html;
    if($spacer_position == 'bottom'){
        echo $spacer_html;
    }
?></div>
<?php LaStudio_Shortcodes_Helper::renderResponsiveMediaStyleTags($la_fix_css); ?>