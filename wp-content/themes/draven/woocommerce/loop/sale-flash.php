<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

if( $product->is_on_sale() ){

	$message = esc_html_x( 'Sale!', 'front-view', 'draven' );

	$save_percentage = 0;
	$saving_price = 0;
	$min_price = 0;

	if ( $product->has_child() ) {
		if( $product->is_type('grouped') ) {
			$children = array_filter( array_map( 'wc_get_product', $product->get_children() ), 'wc_products_array_filter_visible_grouped' );
			$child_prices = array();
			foreach ( $children as $child ) {
				if ( '' !== $child->get_price() ) {
					$child_prices[] = $child->get_price();
				}
			}
			if(!empty($child_prices)){
				$min_price = min($child_prices);
			}
		}

		if( $product->is_type('variable') ) {
			$variation_prices = $product->get_variation_prices();
			$min_price = $product->get_variation_sale_price();
			foreach( $variation_prices['price'] as $variation_id => $price ) {
				$regular_price = $variation_prices['regular_price'][$variation_id];
				$saving_price_tmp = $regular_price - $price;
				if( $saving_price_tmp > $saving_price ) {
					$saving_price = $saving_price_tmp;
				}
				$save_percentage_tmp =  round( ( ( $saving_price_tmp / $regular_price ) * 100 ) , 1 );
				if($save_percentage_tmp > $save_percentage){
					$save_percentage = $save_percentage_tmp;
				}
			}
		}

		$text = apply_filters('draven/filter/product/sale_badge_title', esc_html_x( 'Save up to', 'front-view', 'draven' ), $product);

	}
	else {

		// Fetch prices for simple products
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();

		if( $regular_price != '' && $sale_price != '' && $regular_price > $sale_price ) {
			$save_percentage = round((( ( $regular_price - $sale_price ) / $regular_price ) * 100),1) ;
			$saving_price = $regular_price - $sale_price;
			if( $sale_price > $min_price){
				$min_price = $sale_price;
			}
		}

		$text = apply_filters('draven/filter/product/sale_badge_title', esc_html_x( 'Save', 'front-view', 'draven' ), $product);

	}

	// Only modify badge if saving amount is larger than 0

	if( $save_percentage > 0 && $saving_price > 0 ) {
		$message = sprintf('<span class="save-percentage"><span class="hidden">%s</span><span>%s</span></span>', $text, $save_percentage . '%' );
		$saving_price = wc_price( $saving_price );
		$saving_price_r = apply_filters('draven/filter/product/sale_badge_price', sprintf( _x( ' %s', 'front-view', 'draven' ), $saving_price ), $saving_price, $product);
		$message .= sprintf('<span class="save-total"><span class="hidden">%s</span>%s</span>', $text, $saving_price_r );
	}
	if( $min_price > 0 ){
		$message .= sprintf('<span class="save-sale-price"><span class="hidden">%s</span>%s</span>', esc_html_x( 'Only', 'front-view', 'draven' ), wc_price( $min_price ) );
	}

	echo apply_filters( 'woocommerce_sale_flash', '<span class="la-custom-badge onsale">' . $message . '</span>', $post, $product );
}