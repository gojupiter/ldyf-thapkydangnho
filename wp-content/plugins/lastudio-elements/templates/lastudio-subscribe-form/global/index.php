<?php
/**
 * Subscribe Form main template
 */

$submit_button_text = $this->get_settings( 'submit_button_text' );
$submit_placeholder = $this->get_settings( 'submit_placeholder' );
$layout             = $this->get_settings( 'layout' );
$button_icon        = $this->get_settings( 'button_icon' );
$use_icon           = $this->get_settings( 'add_button_icon' );

$this->add_render_attribute( 'main-container', 'class', array(
	'lastudio-subscribe-form',
	'lastudio-subscribe-form--' . $layout . '-layout',
) );

$this->add_render_attribute( 'main-container', 'data-settings', $this->generate_setting_json() );

$instance_data = apply_filters( 'lastudio-elements/subscribe-form/input-instance-data', array(), $this );

$instance_data = json_encode( $instance_data );

$this->add_render_attribute( 'form-input',
	array(
		'class'       => array(
			'lastudio-subscribe-form__input lastudio-subscribe-form__mail-field',
		),
		'type'               => 'email',
		'name'               => 'email',
		'placeholder'        => $submit_placeholder,
		'data-instance-data' => htmlspecialchars( $instance_data ),
	)
);

$icon_html = '';

if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
	$icon_html = sprintf( '<i class="lastudio-subscribe-form__submit-icon %s"></i>', $button_icon );
}

?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
	<form method="POST" action="#" class="lastudio-subscribe-form__form">
		<div class="lastudio-subscribe-form__input-group">
			<div class="lastudio-subscribe-form__fields">
				<input <?php echo $this->get_render_attribute_string( 'form-input' ); ?>><?php
					$this->generate_additional_fields();
				?></div>
			<?php echo sprintf( '<a class="lastudio-subscribe-form__submit elementor-button elementor-size-md" href="#">%s<span class="lastudio-subscribe-form__submit-text">%s</span></a>', $icon_html, $submit_button_text ); ?>
		</div>
		<div class="lastudio-subscribe-form__message"><div class="lastudio-subscribe-form__message-inner"><span></span></div></div>
	</form>
</div>
