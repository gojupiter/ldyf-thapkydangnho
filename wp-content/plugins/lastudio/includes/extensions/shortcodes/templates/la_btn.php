<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $link
 * @var $title
 * @var $color
 * @var $size
 * @var $style
 * @var $el_class
 * @var $align
 * Shortcode class

 */
$link = $title = $color = $size = $style = $el_class = $align = '';
$shape = $border_width = '';
$wrapper_start = $wrapper_end = '';
$use_link = false;
$attributes = array();
$atts = shortcode_atts(array(
    'title' => 'Text on the button',
    'link' => '',
    'style' => 'flat',
    'border_width' => '1',
    'shape' => 'square',
    'color' => 'black',
    'size' => 'md',
    'align' => 'inline',
    'el_class' => ''
), $atts );
extract( $atts );

$button_html = '<span>' . $title . '</span>';

//parse link
$link = ( '||' === $link ) ? '' : $link;
$link = la_build_link_from_atts( $link );
$a_href = $link['url'];
$a_title = $link['title'];
$a_target = $link['target'];
$a_rel = $link['rel'];
if ( ! empty( $a_href ) ) {
    $use_link = true;
}
$wrap_classes = array();
$button_classes = array();
$button_classes[] = 'btn';
$button_classes[] = 'btn-color-' . $color;
$button_classes[] = 'btn-align-' . $align;
$button_classes[] = 'btn-size-' . $size;
$button_classes[] = 'btn-style-' . $style;
$button_classes[] = 'btn-shape-' . $shape;
$button_classes[] = 'btn-brw-' . $border_width;
$el_class = LaStudio_Shortcodes_Helper::getExtraClass( $el_class );
$css_class = implode( ' ', $button_classes ) . $el_class;

$attributes[] = 'class="' . trim( $css_class ) . '"';

if ( $use_link ) {
    $attributes[] = 'href="' . trim( $a_href ) . '"';
    $attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
    if ( ! empty( $a_target ) ) {
        $attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
    }
    if ( ! empty( $a_rel ) ) {
        $attributes[] = 'rel="' . esc_attr( trim( $a_rel ) ) . '"';
    }
}

$wrap_classes[] = 'btn-wrapper';
$wrap_classes[] = 'btn-align-' . $align;

$attributes = implode( ' ', $attributes );
$wrap_classes = implode( ' ', $wrap_classes );


?>
<div class="<?php echo trim( esc_attr( $wrap_classes ) ) ?>">
    <?php
    if ( $use_link ) {
        echo '<a ' . $attributes . '>' . $button_html . '</a>';
    } else {
        echo '<button ' . $attributes . '>' . $button_html . '</button>';
    }
    ?></div>