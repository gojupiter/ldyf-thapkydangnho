<?php
/**
 * Loop item contnet
 */

if ( 'yes' !== $this->get_attr( 'show_excerpt' ) ) {
	$this->render_meta( 'content_related', 'lastudio-content-fields', array( 'before', 'after' ) );
	return;
}

$this->render_meta( 'content_related', 'lastudio-content-fields', array( 'before' ) );

lastudio_elements_utility()->get_content( array(
	'length'       => intval( $this->get_attr( 'excerpt_length' ) ),
	'content_type' => 'post_excerpt',
	'html'         => '<div %1$s>%2$s</div>',
	'class'        => 'entry-excerpt',
	'echo'         => true,
), 'post', get_the_ID()  );


$this->render_meta( 'content_related', 'lastudio-content-fields', array( 'after' ) );
