<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

echo '<div class="la-wc-tabs-wrapper">';

if ( ! empty( $product_tabs ) ) : ?>
    <div class="wc-tabs-outer clearfix">
        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs">
                <?php foreach ( $product_tabs as $key => $product_tab ) : ?><li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                    <a href="#tab-<?php echo esc_attr( $key ); ?>">
                        <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                    </a>
                    </li><?php endforeach; ?>
            </ul>
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <div class="clearfix woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel wc-tab<?php if( $key == 'description' ) { echo ' entry-content'; } ?>" id="tab-<?php echo esc_attr( $key ); ?>">
                    <div class="wc-tab-title"><a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $product_tab['title'] ), $key ); ?></a></div>
                    <div class="tab-content">
                        <?php
                        if ( isset( $product_tab['callback'] ) ) {
                            call_user_func( $product_tab['callback'], $key, $product_tab );
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif;
echo '</div>';
?>