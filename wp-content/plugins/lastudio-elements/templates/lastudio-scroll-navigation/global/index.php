<?php
/**
 * Scroll Navigation template
 */

$settings = $this->get_settings();
$data_settings = $this->generate_setting_json();
$position = $settings['position'];

$classes_list[] = 'lastudio-scroll-navigation';
$classes_list[] = 'lastudio-scroll-navigation--position-' . $position;
$classes = implode( ' ', $classes_list );
?>

<div class="<?php echo $classes; ?>" <?php echo $data_settings; ?>>
	<?php $this->__get_global_looped_template( 'scroll-navigation', 'item_list' ); ?>
</div>
