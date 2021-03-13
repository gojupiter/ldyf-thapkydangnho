<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$query_settings = wp_json_encode( array(
	'tag' => $shortcode_tag,
	'atts' => $atts,
	'content' => $content,
) );
?>
<div class="elm-ajax-container-wrapper clearfix">
	<div data-la_component="AjaxLoadShortCode" class="js-el elm-ajax-loader" data-query-settings="<?php echo esc_attr( $query_settings ); ?>" data-request="<?php echo esc_attr( admin_url( 'admin-ajax.php', 'relative' ) ); ?>">
		<?php echo LaStudio_Shortcodes_Helper::getLoadingIcon(); ?>
	</div>
</div>