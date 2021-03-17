<?php
/**
 * Images list item template
 */
$settings   = $this->get_settings_for_display();
$preset     = $settings['preset'];
$perPage    = $settings['per_page'];
$is_more_button = $settings['view_more_button'];

$item_instance = 'item-instance-' . $this->item_counter;

$more_item = ( $this->item_counter >= $perPage && filter_var( $is_more_button, FILTER_VALIDATE_BOOLEAN ) ) ? true : false;

$el_class = $this->__loop_item( array('item_el_class'), '%s' );

$this->add_render_attribute( $item_instance, 'class', array(
	'lastudio-portfolio__item',
	! $more_item ? 'visible-status' : 'hidden-status',
) );

if ( !empty($el_class) ) {
    $this->add_render_attribute( $item_instance, 'class', $el_class );
}

if ( 'justify' == $settings['layout_type'] ) {
	$this->add_render_attribute( $item_instance, 'class', $this->get_justify_item_layout() );
}

if ( 'masonry' == $settings['layout_type'] ) {
	$this->add_render_attribute( $item_instance, 'data-width', $this->get_masonry_item_width() );
	$this->add_render_attribute( $item_instance, 'data-height', $this->get_masonry_item_height() );
}

$this->add_render_attribute( $item_instance, 'data-slug', $this->get_item_slug_list() );

$link_instance = 'link-instance-' . $this->item_counter;

$link_css_class = array('lastudio-images-layout__link');

if ( 'list' != $settings['layout_type'] && $preset == 'type-1') {
    $link_css_class[] =  'img-perspective';
}

if ( 'list' == $settings['layout_type'] && $settings['preset_list'] == 'list-type-5') {
    $link_css_class[] =  'img-perspective';
}

$this->add_render_attribute( $link_instance, 'class', $link_css_class);

$item_image_url = $this->__loop_item( array( 'item_image', 'url' ), '%s' );
$item_button_url = $this->__loop_item( array( 'item_button_url', 'url' ), '%s' );

if(empty($item_button_url)){
    $item_button_url = $item_image_url;
}

$this->add_render_attribute( $link_instance, 'href', $item_button_url );

?>
<article <?php echo $this->get_render_attribute_string( $item_instance ); ?>>
	<div class="lastudio-portfolio__inner">
		<a <?php echo $this->get_render_attribute_string( $link_instance ); ?>>
			<div class="lastudio-portfolio__image la-lazyload-image" data-background-image="<?php echo esc_url($item_image_url); ?>">
				<?php echo $this->__loop_image_item(); ?>
				<div class="lastudio-portfolio__image-loader"><span></span></div>
			</div>
		</a>
		<div class="lastudio-portfolio__content">
			<div class="lastudio-portfolio__content-inner">
                <div class="lastudio-portfolio__content-inner2"><?php
				$title_tag = $this->__get_html( 'title_html_tag', '%s' );
				echo $this->__loop_item( array( 'item_title' ), '<' . $title_tag . ' class="lastudio-portfolio__title">%s</' . $title_tag . '>' );
				echo $this->__get_item_category();
				echo $this->__loop_item( array( 'item_desc' ), '<p class="lastudio-portfolio__desc">%s</p>' );
				echo $this->__generate_item_button(); ?></div></div>
		</div>
	</div>
</article><?php

$this->item_counter++;