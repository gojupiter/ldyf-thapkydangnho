<?php
/**
 * Portfolio template
 */

$settings = $this->get_settings_for_display();

$preset        = $settings['preset'];
$layout        = $settings['layout_type'];
$columns       = $settings['columns'];
$columnsLaptop = $settings['columns_laptop'];
$columnsTablet = $settings['columns_tablet'];
$columnsWidth800 = $settings['columns_width800'];
$columnsMobile = $settings['columns_mobile'];
$columns640 = $settings['columns_mobile'];

if( $layout == 'list' ) {
    $preset = $settings['preset_list'];
}

$this->add_render_attribute( 'main-container', 'class', array(
	'lastudio-portfolio',
	'layout-type-' . $layout,
	'preset-' . $preset,
) );

if ( 'masonry' == $layout || 'grid' == $layout ) {
	$this->add_render_attribute( 'main-container', 'class', array(
		'layout-desktop-column-' . $columns,
		! empty( $columnsLaptop ) ? 'layout-laptop-column-' . $columnsLaptop : '',
		! empty( $columnsTablet ) ? 'layout-tablet-column-' . $columnsTablet : '',
		! empty( $columnsWidth800 ) ? 'layout-width800-column-' . $columnsWidth800 : '',
		! empty( $columnsMobile ) ? 'layout-mobile-column-' . $columnsMobile : '',
		! empty( $columns640 ) ? 'layout-width640-column-' . $columnsMobile : '',
	) );
}

if('masonry' == $layout){
    if(!empty($settings['enable_custom_masonry_layout'])){
        $this->add_render_attribute( 'main-container', 'class', 'advancedMasonry');
    }
    if(isset($settings['container_width']['size'])){
        $this->add_render_attribute( 'main-container', 'data-container-width', $settings['container_width']['size'] );
    }
    if(isset($settings['masonry_item_width']['size'])){
        $this->add_render_attribute( 'main-container', 'data-item-width', $settings['masonry_item_width']['size'] );
    }
    if(isset($settings['masonry_item_height']['size'])){
        $this->add_render_attribute( 'main-container', 'data-item-height', $settings['masonry_item_height']['size'] );
    }
}

if( 'grid' != $layout ) {
    $this->add_render_attribute( 'main-container', 'data-settings', $this->generate_setting_json() );
}

?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>><?php

    if( 'grid' == $layout || 'list' == $layout ) {

        $slider_options = $this->generate_carousel_setting_json();
        if(!empty($slider_options)){
            echo sprintf('<div class="lastudio-carousel elementor-slick-slider" data-slider_options="%1$s" dir="%2$s">', $slider_options, is_rtl() ? 'rtl' : 'ltr');
        }
        $this->__get_global_looped_template( 'portfolio', 'image_list' );
        if(!empty($slider_options)) {
            echo '</div>';
        }
    }
    else{
        $this->render_filters();
        $this->__get_global_looped_template( 'portfolio', 'image_list' );
        $this->render_view_more_button();
    }
    ?>
</div>
