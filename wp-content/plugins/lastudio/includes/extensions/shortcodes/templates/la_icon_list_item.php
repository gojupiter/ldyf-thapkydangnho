<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Shortcode attributes
 * @var $el_class
 */

$icon_type = $icon_fontawesome = $icon_la_icon_outline = $icon = $icon_color = $el_class = '';

$atts = shortcode_atts(array(
    'icon_type' => 'fontawesome',
    'icon_fontawesome' => '',
    'icon_la_icon_outline' => '',
    'icon' => '',
    'icon_color' => '',
    'el_class' => '',
), $atts );
extract( $atts );
$iconCssStyle = array();
if(!empty($icon_color)){
    $iconCssStyle[] = 'color:' . $icon_color;
}
if(!empty($icon)){
    if(strpos($icon, 'la-icon') !== false) {
        $icon_type = 'la_icon_outline';
        $icon_la_icon_outline = $icon;
    }
    else{
        $icon_fontawesome = $icon;
    }
}

if(function_exists('vc_icon_element_fonts_enqueue')){
    vc_icon_element_fonts_enqueue( $icon_type );
}

$iconClass = isset( ${'icon_' . $icon_type} ) ? esc_attr( ${'icon_' . $icon_type} ) : 'fa fa-check';
$_icon_html = '<span style="'. esc_attr( implode(';', $iconCssStyle) ) .'"><i class="'.esc_attr($iconClass).'"></i></span>';

$el_class = LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$css_class = "la-sc-icon-item " . $el_class;
?>
<div class="<?php echo esc_attr($css_class)?>">
    <?php echo $_icon_html; ?><div><?php echo LaStudio_Shortcodes_Helper::remove_js_autop($content);?></div>
</div>