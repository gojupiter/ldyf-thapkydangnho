<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php
$current_url = add_query_arg(null,null);
$current_url = remove_query_arg( array( 'page', 'paged', 'mode_view', 'la_doing_ajax' ) , $current_url );
$current_url = preg_replace('/\/page\/\d+/','',$current_url);
?>
<div class="wc-ordering">
	<p><span><?php echo esc_html_x('Sort by', 'front-view', 'draven');?></span></p>
	<ul>
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<li<?php if($orderby == $id){ echo ' class="active"'; } ?>><a href="<?php echo esc_url(add_query_arg('orderby',$id,$current_url))?>"><?php echo esc_html( $name ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>