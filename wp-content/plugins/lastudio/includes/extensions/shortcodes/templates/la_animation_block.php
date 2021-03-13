<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$output = $css_animation = $opacity = $animation_duration = $animation_delay = $animation_iteration_count = $css = $el_class = '';

extract(shortcode_atts(array(
    'css_animation'             => 'none',
    'opacity'                   => 'yes',
    'animation_duration'        => '1',
    'animation_delay'           => '0',
    'animation_iteration_count' => '1',
    'css'                       => '',
    'el_class'                  => ''
),$atts ));

$style = $css_class = '';
$css_class = la_shortcode_custom_css_class( $css, ' ' );

wp_enqueue_style('la-animate-block');

if($opacity == 'yes'){
    $style .= 'opacity:0;';
    $el_class .= ' la-animate-viewport';
}

$inifinite_arr = array("InfiniteRotate", "InfiniteDangle","InfiniteSwing","InfinitePulse","InfiniteHorizontalShake","InfiniteBounce","InfiniteFlash","InfiniteTADA");

if($animation_iteration_count == 0 || in_array($css_animation,$inifinite_arr)){
    $animation_iteration_count = 'infinite';
    $css_animation = 'infinite '.$css_animation;
}
$output .= '<div data-la_component="AnimationBlock" class="js-el la-animation-block '.esc_attr( $el_class . $css_class ).'" data-animate="'. esc_attr( $css_animation ) .'" data-animation-delay="'. esc_attr( $animation_delay ) .'" data-animation-duration="'. esc_attr( $animation_duration ) .'" data-animation-iteration="'. esc_attr( $animation_iteration_count ) .'" style="'.esc_attr( $style ).'">';
$output .= LaStudio_Shortcodes_Helper::remove_js_autop($content);
$output .= '</div>';

echo $output;
