<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );


$show_page_title = apply_filters( 'woocommerce_show_page_title', true );

?><div class="wc_page_description">
	<?php if ( $show_page_title ) : ?>
		<header class="woocommerce-products-header">
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
	<?php if ( $show_page_title ) : ?>
		</header>
	<?php endif; ?>
    </div>
<?php

$item_gap = Draven()->settings()->get('shop_item_space', 'default');

if($item_gap == 'zero'){
	$item_gap = 0;
}
$catalog_column = Draven()->settings()->get('woocommerce_catalog_columns', Draven()->settings()->get('woocommerce_shop_page_columns'));
$catalog_column = shortcode_atts(
	array(
		'xlg'	=> 2,
		'lg' 	=> 2,
		'md' 	=> 2,
		'sm' 	=> 1,
		'xs' 	=> 1,
		'mb' 	=> 1
	),
	$catalog_column
);
$catalog_class = array('catalog-grid-1 products grid-items');
$catalog_class[] = 'grid-space-' . $item_gap;
$catalog_class[] = draven_render_grid_css_class_from_columns($catalog_column);

if ( draven_wc_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked wc_print_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	?>
	<div id="la_shop_products" class="la-shop-products woocommerce">
		<div class="la-ajax-shop-loading"><div class="la-ajax-loading-outer"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div>
		<div class="product-categories-wrapper"><ul class="<?php echo esc_attr(implode(' ', $catalog_class)); ?>"><?php echo woocommerce_maybe_show_product_subcategories(''); ?></ul></div>
	<?php

	wc_set_loop_prop( 'is_main_loop', true );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 *
			 * @hooked WC_Structured_Data::generate_product_data() - 10
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
	?>
	</div>
	<?php
}
else {

	?>
	<div class="wc-toolbar-container"><div class="hidden wc-toolbar wc-toolbar-top clearfix"></div></div>
	<div id="la_shop_products" class="la-shop-products no-products-found woocommerce">
		<div class="la-ajax-shop-loading"><div class="la-ajax-loading-outer"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div>
		<div class="product-categories-wrapper"><ul class="<?php echo esc_attr(implode(' ', $catalog_class)); ?>"><?php echo woocommerce_maybe_show_product_subcategories(''); ?></ul></div>
	<?php

	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
	?>
	</div>
	<?php
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
