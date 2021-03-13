<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$number = $orderby = $order = $hide_empty = $ids = $columns = $el_class = $output = '';

$style = $enable_custom_image_size = $img_size = $enable_carousel = $item_space = '';

$atts = shortcode_atts(array(
    'ids' => '',
    'number' => '',
    'orderby' => '',
    'order' => '',
    'hide_empty' => '',
    'columns' => '',
    'item_space' => 'default',
    'style' => '1',
    'enable_carousel' => '',
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
    'autowidth' => '',
    'item_animation' => ''
), $atts);

extract($atts);

$css_class = 'woocommerce' . LaStudio_Shortcodes_Helper::getExtraClass($el_class);

if(!empty($ids)){
    $ids = explode( ',', $ids );
    $ids = array_map( 'trim', $ids );
}

$hide_empty = ( $hide_empty == 1 || $hide_empty == true) ? 1 : 0;

$number = absint($number);

// get terms and workaround WP bug with parents/pad counts
$args = array(
    'number'     => $number,
    'taxonomy'   => 'product_cat',
    'orderby'    => $orderby,
    'order'      => $order,
    'hide_empty' => $hide_empty,
    'include'    => $ids,
    'pad_counts' => true
);

if(!empty($ids) && empty($orderby)){
    $args['orderby'] = 'include';
}

$product_categories = get_terms( $args );


if ( $hide_empty ) {
    foreach ( $product_categories as $key => $category ) {
        if ( $category->count == 0 ) {
            unset( $product_categories[ $key ] );
        }
    }
}

if ( $number > 0 ) {
    $product_categories = array_slice( $product_categories, 0, $number );
}


$columns        = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($columns);
$loopCssClass = array();
$loopCssClass[] = 'products';
$loopCssClass[] = 'grid-items';
$loopCssClass[] = 'catalog-grid-' . $style;
$loopCssClass[] = 'grid-space-' . $item_space;
$carousel_configs = false;
if($enable_carousel == 'yes'){

    $carousel_configs = ' data-la_component="AutoCarousel" ';
    $carousel_configs .= LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts);
    $loopCssClass[] = 'js-el la-slick-slider';
    if(!empty($item_animation) && $item_animation != 'none' && $centermode != 'yes' && $autowidth != 'yes' && $slider_type == 'horizontal'){
        $loopCssClass[] = ' laslick_has_animation laslick_'. $item_animation;
    }
}

foreach( $columns as $screen => $value ){
    $loopCssClass[]  =  sprintf('%s-grid-%s-items', $screen, $value);
}


ob_start();

if ( $product_categories ) {

    printf(
        '<div class="product-categories-wrapper"><ul class="%s"%s>',
        esc_attr(implode(' ', $loopCssClass)),
        $carousel_configs ? $carousel_configs : ''
    );
    foreach ( $product_categories as $category ) {
        wc_get_template( 'content-product_cat.php', array(
            'category' => $category
        ) );
    }
    printf('</ul></div>');
}

echo '<div class="'. esc_attr($css_class) .'">' . ob_get_clean() . '</div>';