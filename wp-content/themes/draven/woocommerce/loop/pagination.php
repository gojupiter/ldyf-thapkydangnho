<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';


$woocommerce_pagination_type = Draven()->settings()->get('woocommerce_pagination_type', 'pagination');
$woocommerce_load_more_text = Draven()->settings()->get('woocommerce_load_more_text');

if ( $total <= 1 ) {
	return;
}

$tmp_text = wc_get_loop_prop('products__loadmore_ajax_text');
if(!empty($tmp_text)){
    $woocommerce_load_more_text = $tmp_text;
}

$nav_classes = array('woocommerce-pagination', 'la-pagination', 'clearfix');
if(!empty($tmp_text) || $woocommerce_pagination_type == 'load_more') {
    $nav_classes[] = 'active-loadmore';
}
if( $total == $current ) {
    $nav_classes[] = 'nothingtoshow';
}
?>
<nav class="<?php echo join(' ', $nav_classes) ?>">
    <div class="la-ajax-loading-outer"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div>
    <div class="products__loadmore_ajax pagination_ajax_loadmore">
        <a href="javascript:;"><span><?php echo esc_html($woocommerce_load_more_text); ?></span></a>
    </div>
	<?php
		echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
			'base'         => $base,
			'format'       => $format,
			'add_args'     => false,
			'current'      => max( 1, $current ),
			'total'        => $total,
			'prev_text'    => '<i class="fa fa-arrow-left"></i>',
			'next_text'    => '<i class="fa fa-arrow-right"></i>',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3
		) ) );
	?>
</nav>