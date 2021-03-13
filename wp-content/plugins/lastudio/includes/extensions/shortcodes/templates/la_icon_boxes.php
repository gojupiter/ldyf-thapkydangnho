<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$la_fix_css = array();
$icon_type = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypo = $icon_linecons = $icon_monosocial = $icon_image_id = $title = '';
$icon_pos = $icon_style = $icon_size = $icon_width = $icon_color = $icon_bg = $icon_border_style = $icon_border_width = $icon_border_color = $icon_border_radius = $el_class = $css = '';
$icon_la_svg = '';
$icon_padding = '';
$custom_number = '';
$output = '';
$title_class = $desc_class = '';

$read_more = $link = '';

$icon_color_type = $icon_bg_type = $icon_color2 = $icon_bg2 = '';

$icon_h_color = $icon_h_color2 = $icon_h_bg = $icon_h_bg2 = $icon_h_border_color = '';

$use_gfont_title = $title_font = $title_fz = $title_lh = $title_color = '';
$use_gfont_desc = $desc_font = $desc_fz = $desc_lh = $desc_color = '';

$atts = shortcode_atts(array(
    'icon_type' => 'fontawesome',
    'icon_fontawesome' => 'fa fa-info-circle',
    'icon_openiconic' => '',
    'icon_typicons' => '',
    'icon_entypo' => '',
    'icon_linecons' => '',
    'icon_monosocial' => 'vc-mono vc-mono-fivehundredpx',
    'icon_la_icon_outline' => 'la-icon design-2_image',
    'icon_nucleo_glyph' => 'nc-icon-glyph nature_bear',
    'icon_la_svg' => '',
    'icon_image_id' => '',
    'custom_number' => '',
    'title' => '',
    'read_more' => 'none',
    'link' => '',
    'icon_pos' => 'default',
    'icon_style' => 'simple',
    'icon_size' => 30,
    'icon_width' => 30,
    'icon_padding' => 0,
    'icon_color_type' => 'simple',
    'icon_color' => '',
    'icon_h_color' => '',
    'icon_color2' => '',
    'icon_h_color2' => '',
    'icon_bg_type' => 'simple',
    'icon_bg' => '',
    'icon_h_bg' => '',
    'icon_bg2' => '',
    'icon_h_bg2' => '',
    'icon_border_style' => '',
    'icon_border_width' => 1,
    'icon_border_color' => '',
    'icon_h_border_color' => '',
    'icon_border_radius' => 500,
    'el_class' => '',
    'title_class' => '',
    'desc_class' => '',
    'title__typography' => '',
    'use_gfont_title' => '',
    'title_font' => '',
    'title_fz' => '',
    'title_lh' => '',
    'title_color' => '',
    'desc__typography' => '',
    'use_gfont_desc' => '',
    'desc_font' => '',
    'desc_fz' => '',
    'desc_lh' => '',
    'desc_color' => '',
    'css' => ''
), $atts );

extract( $atts );

$a_link_open = '';
$a_link_close = '';
$a_link_text = $title;

if ( ! empty( $link ) ) {
    $link = la_build_link_from_atts( $link );
    $a_link_open = '<a href="' . esc_attr( $link['url'] ) . '"' . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' ) . ( $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '' ) . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' ) . '>';
    $a_link_close = '</a>';
    if(!empty($link['title'])){
        $a_link_text = $link['title'];
    }
}

if(empty($icon_h_color)){
    $icon_h_color = $icon_color;
}
if(empty($icon_h_color2)){
    $icon_h_color2 = $icon_color2;
}
if(empty($icon_h_bg)){
    $icon_h_bg = $icon_bg;
}
if(empty($icon_h_bg2)){
    $icon_h_bg2 = $icon_bg2;
}
if(empty($icon_h_border_color)){
    $icon_h_border_color = $icon_border_color;
}

$unique_id = uniqid('la_icon_boxes_');
$_tmp_class = 'la-sc-icon-boxes wpb_content_element';
if($icon_type == 'custom'){
    $_tmp_class .= ' icon-type-img';
}
elseif($icon_type == 'number'){
    $_tmp_class .= ' icon-type-number';
}
else{
    $_tmp_class .= ' icon-type-normal';
}

$_tmp_class .= ' icon-pos-' . $icon_pos;
$_tmp_class .= ' icon-style-' . $icon_style;
$_tmp_class .= ' ib-link-' . $read_more;

$title_class = 'h5 js-el la-unit-responsive icon-heading' . LaStudio_Shortcodes_Helper::getExtraClass($title_class);
$desc_class = 'js-el la-unit-responsive box-description' . LaStudio_Shortcodes_Helper::getExtraClass($desc_class);
$class_to_filter = $_tmp_class . la_shortcode_custom_css_class( $css, ' ' ) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );
$css_class = $class_to_filter;

if( $icon_pos != 'hidden' ) {
    if (function_exists('vc_icon_element_fonts_enqueue')) {
        vc_icon_element_fonts_enqueue($icon_type);
    }
}

if($icon_style == 'simple'){
    $icon_width = null;
}

$wapIconCssStyle = $iconCssStyle = array();
$wapIconHoverCssStyle = $iconHoverCssStyle = array();
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

        $wapIconHoverCssStyle[] = 'background-color: ' . $icon_h_bg;
        $wapIconHoverCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $icon_h_bg . ' 0%, ' . $icon_h_bg2 . ' 50%,' . $icon_h_bg . ' 100%)';
        $wapIconHoverCssStyle[] = 'background-image: linear-gradient(to right, ' . $icon_h_bg . ' 0%, ' . $icon_h_bg2 . ' 50%,' . $icon_h_bg . ' 100%)';

    }else{
        $wapIconCssStyle[] = 'background-color: ' . $icon_bg ;
        $wapIconHoverCssStyle[] = 'background-color: ' . $icon_h_bg ;
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
        $iconCssStyle[] = 'background-clip: text';
        $iconCssStyle[] = '-webkit-text-fill-color: transparent';
        $css_class .= ' icontext-gradient';

        $iconHoverCssStyle[] = 'color: ' . $icon_h_color;
        $iconHoverCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $icon_h_color . ' 0%, ' . $icon_h_color2 . ' 50%,' . $icon_h_color . ' 100%)';
        $iconHoverCssStyle[] = 'background-image: linear-gradient(to right, ' . $icon_h_color . ' 0%, ' . $icon_h_color2 . ' 50%,' . $icon_h_color . ' 100%)';
    }else{
        $iconCssStyle[] = 'color:' . $icon_color;
        $iconHoverCssStyle[] = 'color:' . $icon_h_color;
    }
}
if(!empty($icon_border_style)){
    $wapIconCssStyle[] = 'border-style:' . $icon_border_style;
    $wapIconCssStyle[] = 'border-width:' . $icon_border_width . 'px';
    $wapIconCssStyle[] = 'border-color:' . $icon_border_color;
    $wapIconHoverCssStyle[] = 'border-color:' . $icon_h_border_color;
}

$_icon_html = '';
if( $icon_pos != 'hidden' ) {
    if ($icon_type == 'custom') {
        if ($__icon_html = wp_get_attachment_image($icon_image_id, 'full')) {
            $_icon_html = '<span>' . $__icon_html . '</span>';
        }
    }
    elseif ($icon_type == 'number') {
        $_icon_html = '<span><b class="type-number">' . $custom_number . '</b></span>';
    }
    else {
        if ($icon_type == 'la_svg') {
            $svg_path = plugin_dir_url(dirname(dirname(dirname(dirname(__FILE__)))));
            $svg_path .= 'public/svg/' . str_replace('la-svg la-svg-', '', $icon_la_svg) . '.svg';
            $_icon_html = '<span data-la_component="InlineSVG" class="js-el la-svg" data-svg="' . $svg_path . '" data-type="oneByOne" data-hover="#' . $unique_id . '"></span>';
        }
        else {
            $iconClass = isset(${'icon_' . $icon_type}) ? esc_attr(${'icon_' . $icon_type}) : 'fa fa-adjust';
            $_icon_html = '<span><i class="' . esc_attr($iconClass) . '"></i></span>';
        }
    }
}

$inner_html = '';
$icon_html = $box_heading_html = $box_content_html = '';

if(!empty($_icon_html)){
    $icon_html .= '<div class="box-icon-inner '.($icon_type == 'custom' ? 'type-img' : 'type-icon').'">';
        $icon_html .= '<div class="wrap-icon">';
            $icon_html .= '<div class="box-icon box-icon-style-'. $icon_style .'">';
                if( $read_more == 'icon' && $a_link_open != '') {
                    $icon_html .= $a_link_open;
                }
                $icon_html .= $_icon_html;
                if( $read_more == 'icon' && $a_link_open != '') {
                    $icon_html .= $a_link_close;
                }
            $icon_html .= '</div>';
        $icon_html .= '</div>';
    $icon_html .= '</div>';
}



if(!empty($title)){
    if(!empty($title_fz) || !empty( $title_lh)){
        $titleHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .icon-heading',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .icon-heading',
            'media_sizes' => array(
                'font-size' => $title_fz,
                'line-height' => $title_lh
            ),
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
    if( $read_more == 'title' && $a_link_open != '') {
        $box_heading_html = '<div class="box-heading"><div class="'.esc_attr($title_class).'" style="'. esc_attr( implode(';', $titleCssInline)).'" '.$titleHtmlAtts.'>' . $a_link_open . esc_html($title) . $a_link_close . '</div></div>';

    }else{
        $box_heading_html = '<div class="box-heading"><div class="'.esc_attr($title_class).'" style="'. esc_attr( implode(';', $titleCssInline)).'" '.$titleHtmlAtts.'>' . esc_html($title) . '</div></div>';
    }
}
if(!empty($content)){
    if(!empty($desc_fz) || !empty( $desc_lh)){
        $descHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
            'target' => '#'. $unique_id.' .box-description',
            'media_sizes' => array(
                'font-size' => $desc_fz,
                'line-height' => $desc_lh
            ),
        ));
        LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
            'target' => '#'. $unique_id.' .box-description',
            'media_sizes' => array(
                'font-size' => $desc_fz,
                'line-height' => $desc_lh
            ),
        ));
    }
    else{
        $descHtmlAtts = '';
    }

    $descCssInline = array();
    if(!empty($desc_color)){
        $descCssInline[] = "color:{$desc_color}";
    }
    if(!empty($use_gfont_title)){
        $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($desc_font);
        if(isset($gfont_data['style'])){
            $descCssInline[] = $gfont_data['style'];
        }
        if(isset($gfont_data['font_url'])){
            wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
        }
    }
    $box_content_html = '<div class="'.esc_attr($desc_class).'" style="'. esc_attr( implode(';', $descCssInline)).'" '.$descHtmlAtts.'>' . LaStudio_Shortcodes_Helper::remove_js_autop($content, true) . '</div>';
}

switch($icon_pos){
    case 'top':
        $inner_html .= '<div class="box-icon-top">';
            $inner_html .= $icon_html;
        $inner_html .= '</div>';
        $inner_html .= '<div class="box-contents">';
            $inner_html .= $box_heading_html . $box_content_html;
        $inner_html .= '</div>';
        break;
    case 'left':
        $inner_html .= '<div class="box-icon-left">';
            $inner_html .= $icon_html;
        $inner_html .= '</div>';
        $inner_html .= '<div class="box-contents">';
            $inner_html .= $box_heading_html . $box_content_html;
        $inner_html .= '</div>';
        break;
    case 'right':
        $inner_html .= '<div class="box-contents">';
            $inner_html .= $box_heading_html . $box_content_html;
        $inner_html .= '</div>';
        $inner_html .= '<div class="box-icon-right">';
            $inner_html .= $icon_html;
        $inner_html .= '</div>';
        break;
    case 'heading-right':
        $inner_html .= '<div class="box-heading-top">';
            $inner_html .= $box_heading_html;
            $inner_html .= '<div class="box-icon-heading">';
                $inner_html .= $icon_html;
            $inner_html .= '</div>';
        $inner_html .= '</div>';
        $inner_html .= '<div class="box-contents">';
            $inner_html .= $box_content_html;
        $inner_html .= '</div>';
        break;
    case 'hidden':
        $inner_html .= '<div class="box-heading-top">';
            $inner_html .= $box_heading_html;
        $inner_html .= '</div>';
        $inner_html .= '<div class="box-contents">';
            $inner_html .= $box_content_html;
        $inner_html .= '</div>';
        break;
    default:
        $inner_html .= '<div class="box-heading-top">';
            $inner_html .= '<div class="box-icon-heading">';
                $inner_html .= $icon_html;
            $inner_html .= '</div>';
            $inner_html .= $box_heading_html;
        $inner_html .= '</div>';
        $inner_html .= '<div class="box-contents">';
            $inner_html .= $box_content_html;
        $inner_html .= '</div>';

}

?>
<div id="<?php echo esc_attr($unique_id)?>" class="<?php echo esc_attr($css_class)?>">
    <div class="icon-boxes-inner"><?php echo $inner_html;?><?php
        if($a_link_open != ''){
            if($read_more == 'box' || $read_more == 'read_more'){
                echo $a_link_open . esc_html($a_link_text) . $a_link_close;
            }
        }
    ?></div>
</div>
<?php

if(!empty($iconCssStyle) || !empty($wapIconCssStyle)|| !empty($iconHoverCssStyle)|| !empty($wapIconHoverCssStyle) ){
    if(!wp_doing_ajax()){
        ?>
<style data-la_component="InsertCustomCSS"><?php
    if(!empty($wapIconCssStyle)){
        echo '#'.$unique_id . '.la-sc-icon-boxes .wrap-icon .box-icon{' . implode(';', $wapIconCssStyle) . '}';
    }
    if(!empty($iconCssStyle)){
        echo '#'.$unique_id . '.la-sc-icon-boxes .wrap-icon .box-icon span{' . implode(';', $iconCssStyle) . '}';
    }
    if(!empty($wapIconHoverCssStyle)){
        echo '#'.$unique_id . '.icon-type-normal:hover .wrap-icon .box-icon{' . implode(';', $wapIconHoverCssStyle) . '}';
    }
    if(!empty($iconHoverCssStyle)){
        echo '#'.$unique_id . '.icon-type-normal:hover .wrap-icon .box-icon span{' . implode(';', $iconHoverCssStyle) . '}';
    }
    ?>
</style>
        <?php
    }
    else{
        ?>
<span data-la_component="InsertCustomCSS" class="js-el hidden"><?php
    if(!empty($wapIconCssStyle)){
        echo '#'.$unique_id . '.la-sc-icon-boxes .wrap-icon .box-icon{' . implode(';', $wapIconCssStyle) . '}';
    }
    if(!empty($iconCssStyle)){
        echo '#'.$unique_id . '.la-sc-icon-boxes .wrap-icon .box-icon span{' . implode(';', $iconCssStyle) . '}';
    }
    if(!empty($wapIconHoverCssStyle)){
        echo '#'.$unique_id . '.icon-type-normal:hover .wrap-icon .box-icon{' . implode(';', $wapIconHoverCssStyle) . '}';
    }
    if(!empty($iconHoverCssStyle)){
        echo '#'.$unique_id . '.icon-type-normal:hover .wrap-icon .box-icon span{' . implode(';', $iconHoverCssStyle) . '}';
    }
    ?>
</span>
        <?php
    }
}
LaStudio_Shortcodes_Helper::renderResponsiveMediaStyleTags($la_fix_css);