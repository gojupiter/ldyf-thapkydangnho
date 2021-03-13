<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Shortcode attributes
 * @var $top
 * @var $left
 * @var $position
 */

$top = $left = $position = $product_id = $title = '';


$atts = shortcode_atts( array(
    'top'           => '',
    'left'          => '',
    'position'      => '',
    'product_id'    => '',
    'title'         => ''
), $atts );

extract( $atts );

$styles = array();
$styles[] = 'top:' . $top;
$styles[] = 'left:' . $left;

$la_hotspot_param = isset($GLOBALS['la_hotspot_param']) && !empty($GLOBALS['la_hotspot_param']) ? $GLOBALS['la_hotspot_param'] : array();

$hotspot_icon = !empty($la_hotspot_param['icon']) ? $la_hotspot_param['icon'] : 'plus_sign';
$tooltip_func = !empty($la_hotspot_param['tooltip_func']) ? $la_hotspot_param['tooltip_func'] : 'hover';
$index = !empty($la_hotspot_param['index']) ? absint($la_hotspot_param['index']) : 1;
$product_viewer = !empty($la_hotspot_param['product_viewer']) && $la_hotspot_param['product_viewer'] ? true : false;

$tooltip_content_class = (empty($content)) ? 'nttip empty-tip' : 'nttip';
if($product_viewer && !empty($product_id) && get_post_type($product_id) == 'product'){
    $tooltip_content_class .= ' product-viewer';
}
?>
<div class="la_hotspot_wrap" style="<?php echo esc_attr(join(';', $styles))?>">
    <div class="la_hotspot <?php echo $tooltip_func;?>">
        <span><?php
            if($hotspot_icon == 'custom_title'){
                echo $title;
            }
            else{
                if($index < 10){
                    echo '0';
                }
                echo $index;
            }
    ?></span>
    </div>
    <div class="<?php echo esc_attr($tooltip_content_class) ?>" data-tooltip-position="<?php echo esc_attr($position) ?>">
        <div class="inner">
            <?php if($product_viewer && !empty($product_id) && get_post_type($product_id) == 'product'):?>
                <?php if( function_exists('wc_get_product') && ($product = wc_get_product( $product_id )) && $product): ?>
                    <div class="public-hotspot-info__product-image-holder">
                        <div class="public-hotspot-info__product-image-inner">
                            <a class="public-hotspot-info__product-image" target="_blank" href="<?php echo esc_url( get_post_permalink( $product_id ) ) ?>"><?php echo $product->get_image(); ?></a>
                        </div>
                    </div>
                    <a class="public-hotspot-info__btn-buy" target="_blank" href="<?php echo esc_url( get_post_permalink( $product_id ) ) ?>">
                        <span class="btn_txt"><?php esc_html_e('BUY','lastudio') ?></span>
                        <span class="btn_ico"><i class="dl-icon-right"></i></span>
                    </a>
                    <div class="public-hotspot-info__first-line">
                        <div class="public-hotspot-info__price"><?php echo $product->get_price_html(); ?></div>
                    </div>
                    <div class="public-hotspot-info__second-line">
                        <a target="_blank" href="<?php echo esc_url( get_post_permalink( $product_id ) ) ?>"><?php echo $product->get_title(); ?></a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="la_hotspot--title"><?php echo $title; ?></div>
                <div class="la_hotspot--desc"><?php echo LaStudio_Shortcodes_Helper::remove_js_autop($content);?></div>
            <?php endif; ?>
            <a href="#" class="tipclose"><i class="fa fa-times"></i></a>
        </div>
    </div>
</div><?php

$index = $index + 1;
$GLOBALS['la_hotspot_param'] = array(
    'index'         => $index,
    'icon'          => $hotspot_icon,
    'tooltip_func'  => $tooltip_func,
    'product_viewer'=> $product_viewer
);