<?php
/**
 * Slider start template
 */

$settings = $this->get_settings_for_display();
$class_array[] = 'lastudio-slider__items';
$class_array[] = 'sp-slides';

$classes = implode( ' ', $class_array );

?>
<span class="lastudio-slider-loader"></span>
<div class="slider-pro">
	<div class="<?php echo $classes; ?>">