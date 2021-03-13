<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 * Shortcode attributes
 * @var $title
 * @var $content
 * @var $time_link_apply
 * @var $time_link
 * @var $time_read_text
 * @var $el_class
 */

$css_animation = $sub_title = $title = $time_link_apply = $time_link = $time_read_text = $el_class = '';
$dot_color = '';

$atts = shortcode_atts( array(

), $atts );

extract( $atts );

$el_class = LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$css_class = "timeline-block" . $el_class;
if(!empty($css_animation) && 'none' != $css_animation){
    $css_class .= ' wpb_animate_when_almost_visible la-animation animated';
}
//parse link
$attributes = $a_href = $a_title = $a_target = '';
$time_link = ( '||' === $time_link ) ? '' : $time_link;
$time_link = la_build_link_from_atts( $time_link );
$use_link = false;
if ( strlen( $time_link['url'] ) > 0 ) {
    $use_link = true;
    $a_href = $time_link['url'];
    $a_title = $time_link['title'];
    $a_target = $time_link['target'];
}

if ( $use_link ) {
    $attributes[] = "href='" . esc_url( trim( $a_href ) ) . "'";
    $attributes[] = "title='" . esc_attr( trim( $a_title ) ) . "'";
    if ( ! empty( $a_target ) ) {
        $attributes[] = "target='" . esc_attr( trim( $a_target ) ) . "'";
    }
    $attributes = implode( ' ', $attributes );
}

?>
<div class="<?php echo esc_attr($css_class)?>" data-animation-class="<?php echo esc_attr($css_animation)?>">
    <div class="timeline-dot"<?php echo $dot_color ? 'style="background-color:'.esc_attr($dot_color).'"' : ''?>></div>
    <div class="timeline-arrow"></div>
    <div class="timeline-content-wrapper">
        <div class="timeline-content">
            <?php
                if(!empty($sub_title)){
                    printf('<div class="timeline-subtitle">%s</div>', $sub_title);
                }
            ?>
            <h3 class="timeline-title"><?php
                if($time_link_apply == 'title' && $use_link){
                    echo "<a {$attributes}>{$title}</a>";
                }else{
                    echo $title;
                }
                ?></h3>
            <div class="timeline-entry"><?php echo LaStudio_Shortcodes_Helper::remove_js_autop($content);?></div>
            <?php if($time_link_apply == 'more' && $use_link && !empty($time_read_text) ){
                echo "<div class='readmore-link'><a {$attributes}>{$time_read_text}</a></div>";
            }?>
            <?php if($time_link_apply == 'box' && $use_link){
                echo "<a {$attributes} class='readmore-box'></a>";
            }?>
        </div>
    </div>
</div>