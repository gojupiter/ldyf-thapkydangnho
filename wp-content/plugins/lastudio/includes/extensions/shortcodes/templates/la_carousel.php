<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$centermode = $autowidth = $slider_type = $item_space = $css_ad_carousel = $item_animation = $el_class = '';


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
    'css_ad_carousel' => '',
    'item_animation' => 'none'
), $atts ) );


$uid = uniqid( rand() );

$design_style = la_shortcode_custom_css_class( $css_ad_carousel, ' ' );

$el_class = LaStudio_Shortcodes_Helper::getExtraClass($el_class);

$wrap_data = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts);

$elem_css = 'la-carousel-wrapper la_carousel_' . $slider_type . $design_style . $el_class;

$slick_css_class = '';
if(!empty($item_animation) && $item_animation != 'none' && $centermode != 'yes' && $autowidth != 'yes' && $slider_type == 'horizontal'){
    $slick_css_class = ' laslick_has_animation laslick_'. $item_animation;
}

?>
<div id="la_carousel_<?php echo esc_attr($uid)?>" class="<?php echo esc_attr($elem_css) ?>" data-gutter="<?php echo esc_attr($item_space)?>">
    <div data-la_component="AutoCarousel" class="js-el la-slick-slider<?php echo $slick_css_class; ?>" <?php echo $wrap_data ?>>
        <?php
        la_fw_override_shortcodes( $content );
        echo LaStudio_Shortcodes_Helper::remove_js_autop( $content );
        la_fw_restore_shortcodes();
        ?>
    </div>
</div><?php

if(!wp_doing_ajax()){
?>
<style data-la_component="InsertCustomCSS">
    #la_carousel_<?php echo esc_attr($uid)?>{
        margin-left: -<?php echo absint($item_space); ?>px;
        margin-right: -<?php echo absint($item_space); ?>px;
    }
    #la_carousel_<?php echo esc_attr($uid)?> .la-item-wrap.slick-slide{
        padding-left: <?php echo absint($item_space); ?>px;
        padding-right: <?php echo absint($item_space); ?>px;
    }
</style>
<?php
}
else{
    ?>
<span data-la_component="InsertCustomCSS" class="js-el hidden">
    #la_carousel_<?php echo esc_attr($uid)?>{
        margin-left: -<?php echo absint($item_space); ?>px;
        margin-right: -<?php echo absint($item_space); ?>px;
    }
    #la_carousel_<?php echo esc_attr($uid)?> .la-item-wrap.slick-slide{
        padding-left: <?php echo absint($item_space); ?>px;
        padding-right: <?php echo absint($item_space); ?>px;
    }
</span>
    <?php
}