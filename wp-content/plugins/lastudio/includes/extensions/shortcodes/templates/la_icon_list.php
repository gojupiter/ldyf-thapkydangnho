<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 * Shortcode attributes
 * @var $el_class
 */

$el_class = '';

$atts = shortcode_atts(array(
    'el_class' => ''
), $atts );
extract( $atts );
$el_class = LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$css_class = "clearfix la-lists-icon " . $el_class;
?>
<div class="<?php echo esc_attr($css_class)?>">
    <?php echo LaStudio_Shortcodes_Helper::remove_js_autop($content);?>
</div>