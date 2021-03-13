<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$atts = LaStudio_Shortcodes_WooCommerce::render_default_atts( $atts );

$loop_name = $atts['scenario'];
unset($atts['scenario']);

$shortcode = new LaStudio_Shortcodes_WooCommerce($atts, 'product_category');

echo $shortcode->get_content();