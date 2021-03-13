<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Shortcode attributes
 * @var $style
 * @var $el_class
 */

$style = $el_class = '';

$atts = shortcode_atts( array(

), $atts );

extract( $atts );
$el_class = LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$css_class = "wpb_content_element la-timeline-wrap clearfix style-{$style}" . $el_class;
?>
<div class="<?php echo esc_attr($css_class)?>">
    <div class="timeline-line"><span></span></div>
    <div class="timeline-wrapper">
        <?php echo LaStudio_Shortcodes_Helper::remove_js_autop($content);?>
    </div>
</div>
