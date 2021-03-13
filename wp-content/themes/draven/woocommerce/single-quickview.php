<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


while ( have_posts() ) : the_post();

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}

$product_design = 2;
$class = 'la-p-single-wrap la-p-single-'. $product_design;
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $class, get_the_ID() ); ?>>

    <div class="row la-single-product-page">
        <div class="col-xs-12 col-md-6 p-left product-main-image">
            <div class="p---large">
                <?php
                /**
                 * woocommerce_before_single_product_summary hook.
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );

                ?>
            </div>
        </div><!-- .product--images -->
        <div class="col-xs-12 col-md-6 p-right product--summary">
            <div class="la-custom-pright">
                <div class="summary entry-summary">

                    <?php
                    /**
                     * woocommerce_single_product_summary hook.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 50
                     */
                    do_action( 'woocommerce_single_product_summary' );

                    woocommerce_template_single_sharing();

                    ?>
                </div>
            </div>
        </div><!-- .product-summary -->
    </div>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>

<?php endwhile; ?>