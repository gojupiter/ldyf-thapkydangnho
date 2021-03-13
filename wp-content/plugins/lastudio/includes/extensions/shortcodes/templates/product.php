<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$atts = shortcode_atts(array(
    'id' => '',
    'sku' => '',
    'layout' => 'grid',
    'list_style' => 'default',
    'grid_style' => '1',
    'enable_custom_image_size' => '',
    'disable_alt_image' => '',
    'img_size' => 'shop_catalog',
    'enable_ajax_loader' => '',
    'el_class' => ''
), $atts );

$ids = $atts['id'];
unset($atts['id']);
$atts['ids'] = $ids;

$shortcode = new LaStudio_Shortcodes_WooCommerce($atts, 'product');

echo $shortcode->get_content();