<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();

$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );


$product_design = Draven()->settings()->get('woocommerce_product_page_design', 1);

$single_product_image_size = 'single';
if($product_design == 3 || $product_design == 4){
	$single_product_image_size = 'full';
	$wrapper_classes[] = 'la-woo-product-gallery';
	$wrapper_classes[] = 'no-slider-script';
	$wrapper_classes[] = 'force-disable-slider-script';
}
else{
	$wrapper_classes[] = 'la-woo-product-gallery';
}
?>
<div class="product--large-image clearfix<?php echo ( ($product_design == 3 || $product_design == 4) ? ' force-disable-slider-script' : '') ?>">
	<div data-product_id="<?php the_ID()?>" class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
		<div class="woocommerce-product-gallery__actions">
			<?php
			$video_link = Draven()->settings()->get_post_meta($product->get_id(), 'product_video_url');
			if(!empty($video_link)){
				printf('<a class="video-link-popup la-popup" href="%s" rel="nofollow"><span><i class="dlicon media-1_button-play"></i></span></a>', esc_url($video_link));
			}
			?>
		</div>
		<figure class="woocommerce-product-gallery__wrapper">
			<?php

			if(function_exists('wc_get_gallery_image_html')){
				$html  = wc_get_gallery_image_html( $post_thumbnail_id, true );
			}
			else{
				if ( has_post_thumbnail() ) {
					$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
					$attributes = array(
						'title'                   => get_post_field( 'post_excerpt', $post_thumbnail_id ),
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],
						'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
					);
					$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $product->get_id(), 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= get_the_post_thumbnail( $product->get_id(), $single_product_image_size, $attributes );
					$html .= '</a></div>';
				}
				else {
					$html  = '<div class="woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder">';
					$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html_x( 'Awaiting product image', 'front-view', 'draven' ) );
					$html .= '</div>';
				}
			}

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

			do_action( 'woocommerce_product_thumbnails' );
			?>
		</figure>
		<div class="la_woo_loading"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
	</div>
	<div id="la_woo_thumbs" class="la-woo-thumbs"><div class="la-thumb-inner"></div></div>
</div>