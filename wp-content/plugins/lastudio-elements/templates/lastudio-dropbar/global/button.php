<?php
/**
 * Dropbar button template
 */

$settings = $this->get_settings_for_display();

$this->add_render_attribute( 'button', 'class', 'lastudio-dropbar__button' );

if ( isset( $settings['button_hover_animation'] ) && $settings['button_hover_animation'] ) {
	$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . esc_attr( $settings['button_hover_animation'] ) );
}
?>

<button <?php $this->print_render_attribute_string( 'button' ); ?>><?php
	$this->__html( 'button_before_icon', '<i class="lastudio-dropbar__button-icon lastudio-dropbar__button-icon--before %s"></i>' );
	$this->__html( 'button_text', '<span class="lastudio-dropbar__button-text">%s</span>' );
	$this->__html( 'button_after_icon', '<i class="lastudio-dropbar__button-icon lastudio-dropbar__button-icon--after %s"></i>' );
?></button>
