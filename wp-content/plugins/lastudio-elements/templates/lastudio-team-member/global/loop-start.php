<?php
/**
 * team-member loop start template
 */

$classes = array(
	'lastudio-team-member',
	'col-row',
	'preset-'. $this->get_attr( 'preset' ),
	lastudio_elements_tools()->gap_classes( $this->get_attr( 'columns_gap' ), $this->get_attr( 'rows_gap' ) ),
);

$equal = $this->get_attr( 'equal_height_cols' );

if ( $equal ) {
	$classes[] = 'lastudio-equal-cols';
}

?>
<div class="<?php echo implode( ' ', $classes ); ?>">