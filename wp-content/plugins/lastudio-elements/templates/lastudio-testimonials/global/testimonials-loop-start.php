<?php
/**
 * Testimonials start template
 */

$data_settings = '';

$use_comment_corner = $this->get_settings( 'use_comment_corner' );

if( $this->get_settings('enable_carousel') == 'true' ) {
    $class_array[] = 'enable-carousel';
    $class_array[] = 'lastudio-testimonials__instance';
    $class_array[] = 'elementor-slick-slider';
    $data_settings = $this->generate_setting_json();
}
else{
    $class_array[] = 'lastudio-testimonials__row';
    $class_array[] = 'col-row';
}

if ( filter_var( $use_comment_corner, FILTER_VALIDATE_BOOLEAN ) ) {
	$class_array[] = 'lastudio-testimonials--comment-corner';
}

$classes = implode( ' ', $class_array );

$dir = is_rtl() ? 'rtl' : 'ltr';

?>
<div class="<?php echo $classes; ?>" <?php echo $data_settings; ?> dir="<?php echo $dir; ?>">