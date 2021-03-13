<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$feed_type = $hashtag = $location_id = $user_id = $sort_by = $limit = $image_size = $el_class = '';
$enable_carousel = $column = $item_space = $output = '';
$image_aspect_ration = '';

$atts = shortcode_atts( array(
    'feed_type' => 'user',
    'hashtag' => '',
    'location_id' => '',
    'user_id' => '',
    'sort_by' => 'none',
    'column' => '',
    'item_space' => 'default',
    'enable_carousel' => '',
    'limit' => 5,
    'image_size' => 'thumbnail',
    'image_aspect_ration' => '11',
    'el_class' => '',
    'slider_type' => 'horizontal',
    'slide_to_scroll' => 'all',
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
    'autowidth' => ''
), $atts );

extract( $atts );

$unique_id = uniqid('la_instagram_feed');

$loopCssClass = array('la-loop','la-instagram-loop');

$responsive_column = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($column);

$carousel_configs = false;

if($enable_carousel == 'yes'){

    $carousel_configs = ' data-la_component="AutoCarousel" ';
    $carousel_configs .= LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts, 'column');
    $loopCssClass[] = 'la-instagram-slider';
}
else{
    $loopCssClass[] = 'grid-items';
    $loopCssClass[] = LaStudio_Shortcodes_Helper::renderResponsiveCssColumns($responsive_column);
}

$loopCssClass[] = 'grid-space-' . $item_space;
$loopCssClass[] = 'image-as-' . $image_aspect_ration;

?>
<div data-la_component="InstagramFeed" id="<?php echo $unique_id?>" class="js-el la-instagram-feeds<?php echo LaStudio_Shortcodes_Helper::getExtraClass( $el_class ); ?>" data-feed_config="<?php echo esc_attr(wp_json_encode(array(
    'get' => $feed_type,
    'tagName' => $hashtag,
    'locationId' => $location_id,
    'userId' => $user_id,
    'sortBy' => $sort_by,
    'limit' => $limit,
    'resolution' => $image_size,
    'template' => '<div class="grid-item"><div class="instagram-item"><a target="_blank" href="{{link}}" title="{{caption}}" style="background-image: url({{image}});" class="thumbnail"><span class="item--overlay"><i class="fa fa-instagram"></i></span><span class="instagram-info"><span class="instagram-like"><i class="fa fa-heart"></i>{{likes}}</span><span class="instagram-comments"><i class="fa fa-comments"></i>{{comments}}</span></span></a></div></div>'
)))?>">
    <div class="instagram-feed-inner">
        <div class="<?php echo esc_attr(implode(' ', $loopCssClass)) ?>"<?php
        if($carousel_configs){
            echo $carousel_configs;
        }
        ?>>
        </div>
        <div class="la-shortcode-loading"><div class="content"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>
    </div>
</div>