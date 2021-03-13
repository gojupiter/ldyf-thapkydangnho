<?php
/**
 * Loop start template
 */
$options = $this->get_advanced_carousel_options();

?>
<div class="lastudio-carousel lastudio-carousel elementor-slick-slider" data-slider_options="<?php echo htmlspecialchars( json_encode( $options ) ); ?>" dir="ltr">