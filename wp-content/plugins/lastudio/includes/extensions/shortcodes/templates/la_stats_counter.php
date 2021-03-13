<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$icon_type = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypo = $icon_linecons = $icon_monosocial = '';
$icon_image_id = $title = $value = $spacer = $spacer_position = $line_style = $line_width = $line_height = $line_color = '';
$icon_pos = $icon_style = $icon_size = $icon_width = $icon_padding = $icon_color_type = $icon_color = $icon_color2 = $icon_bg_type = $icon_bg = $icon_bg2 = '';
$el_class  = $use_gfont_title = $title_font = $title_fz = $title_lh = $title_color = '';
$use_gfont_value = $value_font = $value_fz = $value_lh = $value_color = $css = '';
$prefix = $suffix = $el_id = $el_class_value = $el_class_heading = '';

$la_fix_css = array();

$atts = shortcode_atts( array(
    'icon_pos' => 'top',
    'icon_style' => 'simple',
    'icon_size' => 30,
    'icon_width' => 30,
    'icon_padding' => 0,
    'icon_color_type' => 'simple',
    'icon_color' => '',
    'icon_color2' => '',
    'icon_bg_type' => 'simple',
    'icon_bg' => '',
    'icon_bg2' => '',
    'icon_type' => 'fontawesome',
    'icon_fontawesome' => 'fa fa-info-circle',
    'icon_openiconic' => '',
    'icon_typicons' => '',
    'icon_entypo' => '',
    'icon_linecons' => '',
    'icon_monosocial' => 'vc-mono vc-mono-fivehundredpx',
    'icon_la_icon_outline' => 'la-icon design-2_image',
    'icon_nucleo_glyph' => 'nc-icon-glyph nature_bear',
    'icon_image_id' => '',
    'title' => '',
    'value' => 1250,
    'prefix' => '',
    'suffix' => '',
    'spacer' => 'none',
    'spacer_position' => 'top',
    'line_style' => 'solid',
    'line_width' => '',
    'line_height' => 1,
    'line_color' => '',
    'el_class' => '',
    'title__typography' => '',
    'use_gfont_title' => '',
    'title_font' => '',
    'title_fz' => '',
    'title_lh' => '',
    'title_color' => '',
    'value__typography' => '',
    'use_gfont_value' => '',
    'value_font' => '',
    'value_fz' => '',
    'value_lh' => '',
    'value_color' => '',
    'css' => '',
    'el_id' => '',
    'el_class_value' => '',
    'el_class_heading' => '',
), $atts );

extract($atts);

$unique_id =  !empty($el_id) ? esc_attr($el_id) : uniqid('la_stats_counter_');
$_tmp_class = 'js-el la-stats-counter wpb_content_element';
$_tmp_class .= ' icon-pos-' . $icon_pos;
if($spacer == 'line'){
    $_tmp_class .= ' spacer-position-' . $spacer_position;
}

$class_to_filter = $_tmp_class . la_shortcode_custom_css_class( $css, ' ' ) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );
$css_class = $class_to_filter;

$wapIconCssStyle = $iconCssStyle = array();

$_icon_html = '';

if($icon_pos != 'none'){

    if(function_exists('vc_icon_element_fonts_enqueue')){
        vc_icon_element_fonts_enqueue( $icon_type );
    }

    if($icon_style == 'simple'){
        $icon_width = null;
    }
    if(!empty($icon_size)){
        $iconCssStyle[] = 'line-height:' . $icon_size . 'px';
        $iconCssStyle[] = 'font-size:' . $icon_size . 'px';
        if(!empty($icon_width)){
            $iconCssStyle[] = 'width:' . $icon_width . 'px';
            $iconCssStyle[] = 'height:' . $icon_width . 'px';
        }else{
            $iconCssStyle[] = 'width:' . $icon_size . 'px';
            $iconCssStyle[] = 'height:' . $icon_size . 'px';
        }
    }
    if(!empty($icon_width)){
        $__padding_tmp = intval(($icon_width - $icon_size) / 2);
        $iconCssStyle[] = 'padding:' . $__padding_tmp . 'px';
    }
    if($icon_style != 'simple' && !empty($icon_bg)){
        if($icon_bg_type == 'gradient'){
            $css_class .= ' iconbg-gradient';
            $wapIconCssStyle[] = 'background-color: ' . $icon_bg;
            $wapIconCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $icon_bg . ' 0%, ' . $icon_bg2 . ' 50%,' . $icon_bg . ' 100%)';
            $wapIconCssStyle[] = 'background-image: linear-gradient(to right, ' . $icon_bg . ' 0%, ' . $icon_bg2 . ' 50%,' . $icon_bg . ' 100%)';

        }else{
            $wapIconCssStyle[] = 'background-color: ' . $icon_bg ;
        }

    }
    if($icon_style == 'advanced'){
        $wapIconCssStyle[] = 'border-radius:' . $icon_border_radius . 'px';
        $iconCssStyle[] = 'border-radius:' . $icon_border_radius . 'px';
        if(!empty($icon_padding)){
            $wapIconCssStyle[] = 'padding:'. intval($icon_padding) . 'px';
        }
    }
    if(!empty($icon_color)){
        if($icon_color_type == 'gradient'){
            $iconCssStyle[] = 'color: ' . $icon_color;
            $iconCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $icon_color . ' 0%, ' . $icon_color2 . ' 50%,' . $icon_color . ' 100%)';
            $iconCssStyle[] = 'background-image: linear-gradient(to right, ' . $icon_color . ' 0%, ' . $icon_color2 . ' 50%,' . $icon_color . ' 100%)';
            $iconCssStyle[] = '-webkit-background-clip: text';
            $iconCssStyle[] = '-webkit-text-fill-color: transparent';
            $css_class .= ' icontext-gradient';
        }else{
            $iconCssStyle[] = 'color:' . $icon_color;
        }
    }
    if(!empty($icon_border_style)){
        $wapIconCssStyle[] = 'border-style:' . $icon_border_style;
        $wapIconCssStyle[] = 'border-width:' . $icon_border_width . 'px';
        $wapIconCssStyle[] = 'border-color:' . $icon_border_color;
    }
}

if($icon_type == 'custom'){
    if( $__icon_html = wp_get_attachment_image($icon_image_id, 'full') ) {
        $_icon_html = '<span>' . $__icon_html . '</span>';
    }
}else{
    $iconClass = isset( ${'icon_' . $icon_type} ) ? esc_attr( ${'icon_' . $icon_type} ) : 'fa fa-adjust';
    $_icon_html = '<span><i class="'.esc_attr($iconClass).'"></i></span>';
}

$inner_html = '';
$spacer_html = $icon_html = $value_html = $title_html = '';

if($spacer == 'line'){
    $lineHtmlAtts = '';
    $lineCssInline = array();
    $parentLineCssInline = array();
    $parentLineCssInline[] = "height:{$line_height}px";
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
        '<div class="la-separator" style="%s"><span class="la-line js-el" style="%s" %s></span></div>',
        esc_attr( implode(';', $parentLineCssInline) ),
        esc_attr( implode(';', $lineCssInline) ),
        $lineHtmlAtts
    );
}

if(!empty($_icon_html)){
    $icon_html .= '<div class="box-icon-inner '.($icon_type == 'custom' ? 'type-img' : 'type-icon').'">';
        $icon_html .= '<div class="wrap-icon">';
            $icon_html .= '<div class="box-icon box-icon-style-'. $icon_style .'">';
                $icon_html .= $_icon_html;
            $icon_html .= '</div>';
        $icon_html .= '</div>';
    $icon_html .= '</div>';
}

if(!empty($title)){
    if(!empty($title_fz) || !empty( $title_lh)){
        $titleHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .stats-heading',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .stats-heading',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            )
        ));
    }else{
        $titleHtmlAtts = '';
    }

    $titleCssInline = array();
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
    $heading_el_class = 'stats-heading js-el'  . LaStudio_Shortcodes_Helper::getExtraClass($el_class_heading);
    $title_html = '<div class="box-heading"><div class="'.esc_attr($heading_el_class).'" style="'. esc_attr( implode(';', $titleCssInline)).'" '.$titleHtmlAtts.'>' . esc_html($title) . '</div></div>';
}
if($value != ''){
    $valueHtmlAtts = '';
    if(!empty($value_fz) || !empty( $value_lh)){
        $valueHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .stats-value',
            'media_sizes' => array(
                'font-size' => $value_fz,
                'line-height' => $value_lh
            )
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .stats-value',
            'media_sizes' => array(
                'font-size' => $value_fz,
                'line-height' => $value_lh
            )
        ));
    }

    $valueHtmlAtts .= ' data-decimal="." data-separator="," data-speed="3"';
    $valueHtmlAtts .= ' data-counter-value="' . esc_attr($value) . '"';
    $valueHtmlAtts .= ' data-value-prefix="' . esc_attr($prefix) . '"';
    $valueHtmlAtts .= ' data-value-suffix="' . esc_attr($suffix) . '"';

    $valueCssInline = array();
    if(!empty($value_color)){
        $valueCssInline[] = "color:{$value_color}";
    }
    if(!empty($use_gfont_value)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($value_font);
        if(isset($gfont_data['style'])){
            $valueCssInline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }
    $value_el_class = 'stats-value js-el'  . LaStudio_Shortcodes_Helper::getExtraClass($el_class_value);
    $value_html = '<div class="'.esc_attr($value_el_class).'" style="'. esc_attr( implode(';', $valueCssInline)).'" '.$valueHtmlAtts.'>' . esc_html($value) . '</div>';
}

switch($spacer_position){
    case 'top';
        $value_html = $spacer_html . $value_html;
        break;
    case 'bottom';
        $title_html .= $spacer_html;
        break;
    case 'middle';
        $value_html .= $spacer_html;
        break;
}

?>
<div data-la_component="CountUp" id="<?php echo $unique_id?>" class="<?php echo esc_attr($css_class);?>">
    <div class="element-inner"><?php
        if($icon_pos == 'top' || $icon_pos == 'left'){
            echo '<div class="box-icon-'. esc_attr($icon_pos) .'">' . $icon_html . '</div>';
        }
        echo '<div class="box-icon-des">' . $value_html . $title_html . '</div>';
        if($icon_pos == 'right'){
            echo '<div class="box-icon-right">' . $icon_html . '</div>';
        }
    ?></div>
</div><?php
if(!empty($iconCssStyle) || !empty($wapIconCssStyle) ){
    if(!wp_doing_ajax()){
?>
<style data-la_component="InsertCustomCSS"><?php
    if(!empty($wapIconCssStyle)){
        echo '#'.$unique_id . '.la-stats-counter .wrap-icon .box-icon{' . implode(';', $wapIconCssStyle) . '}';
    }
    if(!empty($iconCssStyle)){
        echo '#'.$unique_id . '.la-stats-counter .wrap-icon .box-icon span{' . implode(';', $iconCssStyle) . '}';
    }
?></style>
        <?php
    }
    else{
?>
<span data-la_component="InsertCustomCSS" class="js-el hidden"><?php
if(!empty($wapIconCssStyle)){
    echo '#'.$unique_id . '.la-stats-counter .wrap-icon .box-icon{' . implode(';', $wapIconCssStyle) . '}';
}
if(!empty($iconCssStyle)){
    echo '#'.$unique_id . '.la-stats-counter .wrap-icon .box-icon span{' . implode(';', $iconCssStyle) . '}';
}
?></span>
<?php
    }
}
LaStudio_Shortcodes_Helper::renderResponsiveMediaStyleTags($la_fix_css);