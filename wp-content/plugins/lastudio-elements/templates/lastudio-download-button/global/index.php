<?php
	$settings = $this->get_settings_for_display();
	$position = $settings['download_icon_position'];
?>
<a class="elementor-button elementor-size-md lastudio-download icon-position-<?php echo $position; ?>" href="<?php echo lastudio_elements_download_handler()->get_download_link( $settings['download_file'] ); ?>"><?php

	$format = '<i class="lastudio-download__icon %s icon-' . $position . '"></i>';

	$this->__html( 'download_icon', $format );

	$label    = $this->__get_html( 'download_label' );
	$sublabel = $this->__get_html( 'download_sub_label' );

	if ( $label || $sublabel ) {

		echo '<span class="lastudio-download__text">';

		printf(
			'<span class="lastudio-download__label">%s</span>',
			$this->__format_label( $label, $settings['download_file'] )
		);

		printf(
			'<small class="lastudio-download__sub-label">%s</small>',
			$this->__format_label( $sublabel, $settings['download_file'] )
		);

		echo '</span>';
	}

?></a>