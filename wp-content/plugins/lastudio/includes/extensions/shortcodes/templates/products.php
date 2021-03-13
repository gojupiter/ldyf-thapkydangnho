<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$atts = LaStudio_Shortcodes_WooCommerce::render_default_atts( $atts );

$loop_name = $atts['scenario'];
unset($atts['scenario']);

if($loop_name == 'product_categories'){
    $loop_name = 'product_category';
}

$shortcode = new LaStudio_Shortcodes_WooCommerce($atts, $loop_name);

echo $shortcode->get_content();