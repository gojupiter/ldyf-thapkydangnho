<?php
function lahfb_text( $atts, $uniqid, $once_run_flag ) {
	extract( LAHFB_Helper::component_atts( array(
		'is_shortcode'	=> 'false',
		'text'			=> 'This is a text field',
		'link'			=> '',
		'link_new_tab'	=> 'false',
		'extra_class'	=> '',
        'icon'          => ''
	), $atts ));

	$out = '';

	$text			= $text ? $text : '' ;
	$link			= $link ? $link : '' ;
	$link_new_tab	= $link_new_tab == 'true' ? 'target="_blank"' : '' ;

    $icon	 = ! empty( $icon ) && $icon != 'none' ? '<i class="' . lahfb_rename_icon($icon) . '" ></i>' : '' ;

	// styles
	if ( $once_run_flag ) :
		$dynamic_style = '';
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Text', '#lastudio-header-builder .el__text_' . esc_attr( $uniqid ) . ' span' );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Icon', '#lastudio-header-builder .el__text_' . esc_attr( $uniqid ) .' i','#lastudio-header-builder .el__text_' . esc_attr( $uniqid ) .':hover i'  );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Background', '#lastudio-header-builder .el__text_' . esc_attr( $uniqid ) );
        $dynamic_style .= lahfb_styling_tab_output( $atts, 'Box', '#lastudio-header-builder .el__text_' . esc_attr( $uniqid ) );

        if ( $dynamic_style ) :
            LAHFB_Helper::set_dynamic_styles( $dynamic_style );
        endif;
	endif;

	// extra class
	$extra_class = $extra_class ? ' ' . $extra_class : '' ;

	// render
	$out .= '
		<div class="lahfb-element lahfb-element-wrap lahfb-text-wrap lahfb-text' . esc_attr( $extra_class ) . ' el__text_'.esc_attr( $uniqid ).'" id="lahfb-text-' . esc_attr( $uniqid ) . '">';
		$text = str_replace('\"','',$text);
        $text = LAHFB_Helper::render_string($text);
		if ( $is_shortcode == 'true' ) {
			$out .= do_shortcode($text);
		}
		else {
			if ( ! empty ( $link ) ) {
				$out .= '<a href="' . esc_attr( LAHFB_Helper::render_string($link) ) . '" '. $link_new_tab .'>';
			}
            $out .= $icon . '<span>'. $text .'</span>';
			if ( ! empty ( $link ) ) {
				$out .= '</a>';
			}
		}
	$out .= '</div>';

	return $out;
}

LAHFB_Helper::add_element( 'text', 'lahfb_text' );
