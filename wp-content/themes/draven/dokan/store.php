<?php

$store_user         = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info         = $store_user->get_shop_info();
$map_location       = $store_user->get_location();
$store_address      = dokan_get_seller_short_address( $store_user->get_id(), false );
$social_fields      = dokan_get_social_profile_fields();
$social_info        = $store_user->get_social_profiles();

$enable_theme_store_sidebar = dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' );

$main_css_class = 'col-md-12 col-xs-12 site-content';
if($enable_theme_store_sidebar == 'on'){
    $main_css_class = 'col-md-9 col-xs-12 site-content';
}

get_header();
?>
<?php do_action( 'draven/action/before_render_main' ); ?>

<div class="profile-frame-wrapper wpb_row section-page-header<?php if($store_user->get_banner()){ echo ' has-img'; } ?>">
    <div class="container">
        <div class="page-header-inner">
            <div class="row">
                <div class="col-xs-12">
                    <div class="profile-frame">
                        <div class="profile-info-box">
                            <div class="profile-info-summery-wrapper">
                                <div class="profile-info-summery">
                                    <div class="profile-info-head">
                                        <div class="profile-img">
                                            <?php echo get_avatar( $store_user->get_id(), 250, '', $store_user->get_shop_name() ); ?>
                                        </div>
                                    </div>
                                    <div class="profile-info clearfix">
                                        <h1 class="store-name h2"><?php echo esc_html( $store_user->get_shop_name() ); ?></h1>
                                        <div class="profile-info-bottom">
                                            <ul class="dokan-store-info">
                                                <?php if ( !empty( $store_user->get_phone() ) ) { ?>
                                                    <li class="dokan-store-phone">
                                                        <i class="fa fa-phone"></i>
                                                        <a href="tel:<?php echo esc_html( $store_user->get_phone() ); ?>"><?php echo esc_html( $store_user->get_phone() ); ?></a>
                                                    </li>
                                                <?php } ?>

                                                <?php if ( $store_user->show_email() == 'yes' ) { ?>
                                                    <li class="dokan-store-email">
                                                        <i class="fa fa-envelope-o"></i>
                                                        <a href="mailto:<?php echo antispambot( $store_user->get_email() ); ?>"><?php echo antispambot( $store_user->get_email() ); ?></a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ( isset( $store_address ) && !empty( $store_address ) ) { ?>
                                                    <li class="dokan-store-address"><i class="fa fa-map-marker"></i>
                                                        <?php echo draven_render_variable($store_address); ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>

                                            <?php if ( $social_fields ) { ?>
                                                <div class="store-social-wrapper">
                                                    <ul class="store-social">
                                                        <?php foreach( $social_fields as $key => $field ) { ?>
                                                            <?php if ( !empty( $social_info[ $key ] ) ) { ?>
                                                                <li>
                                                                    <a href="<?php echo esc_url( $social_info[ $key ] ); ?>" target="_blank"><i class="fa fa-<?php echo esc_attr($field['icon']); ?>"></i></a>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main" class="site-main">
    <div class="container">
        <div class="row">
            <main id="site-content" class="<?php echo esc_attr($main_css_class)?>">
                <div class="site-content-inner">

                    <?php do_action( 'draven/action/before_render_main_inner' );?>

                    <div class="page-content">
                        <?php

                        do_action( 'draven/action/before_render_main_content' );

                        ?>

                        <div id="dokan-primary" class="dokan-single-store">

                            <div id="dokan-content" class="store-page-wrap">

                                <?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

                            </div>
                        </div>


                        <?php if ( have_posts() ) : ?>

                            <?php do_action('woocommerce_before_shop_loop'); ?>


                            <div id="la_shop_products" class="la-shop-products">
                                <div class="la-ajax-shop-loading"><div class="la-ajax-loading-outer"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div>
                                <?php

                                wc_set_loop_prop( 'is_main_loop', true );

                                woocommerce_product_loop_start();

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

                                woocommerce_product_loop_end();

                                /**
                                 * Hook: woocommerce_after_shop_loop.
                                 *
                                 * @hooked woocommerce_pagination - 10
                                 */
                                do_action( 'woocommerce_after_shop_loop' );

                                ?>
                            </div>

                        <?php else : ?>

                            <div class="wc-toolbar-container"><div class="hidden wc-toolbar wc-toolbar-top clearfix"></div></div>
                            <div id="la_shop_products" class="la-shop-products no-products-found">
                                <div class="la-ajax-shop-loading"><div class="la-ajax-loading-outer"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div>
                                <?php

                                /**
                                 * Hook: woocommerce_no_products_found.
                                 *
                                 * @hooked wc_no_products_found - 10
                                 */
                                do_action( 'woocommerce_no_products_found' );
                                ?>
                            </div>

                        <?php endif;

                        do_action( 'draven/action/after_render_main_content' );

                        ?>
                    </div>

                    <?php do_action( 'draven/action/after_render_main_inner' );?>
                </div>
            </main>
            <!-- #site-content -->
            <?php if($enable_theme_store_sidebar == 'on'): ?>
                <aside id="sidebar_primary" class="dokan-store-sidebar col-md-3 col-xs-12">
                    <div class="dokan-widget-area widget-collapse sidebar-inner">
                        <?php do_action( 'dokan_sidebar_store_before', $store_user->data, $store_info ); ?>
                        <?php
                        if ( ! dynamic_sidebar( 'sidebar-store' ) ) {

                            $args = array(
                                'before_widget' => '<div class="widget">',
                                'after_widget'  => '</div>',
                                'before_title'  => '<h4 class="widget-title">',
                                'after_title'   => '</h4>',
                            );

                            if ( class_exists( 'Dokan_Store_Location' ) ) {
                                the_widget( 'Dokan_Store_Category_Menu', array( 'title' => __( 'Store Category', 'draven' ) ), $args );

                                if ( dokan_get_option( 'store_map', 'dokan_general', 'on' ) == 'on'  && !empty( $map_location ) ) {
                                    the_widget( 'Dokan_Store_Location', array( 'title' => __( 'Store Location', 'draven' ) ), $args );
                                }

                                if ( dokan_get_option( 'contact_seller', 'dokan_general', 'on' ) == 'on' ) {
                                    the_widget( 'Dokan_Store_Contact_Form', array( 'title' => __( 'Contact Vendor', 'draven' ) ), $args );
                                }
                            }

                        }
                        ?>
                        <?php do_action( 'dokan_sidebar_store_after', $store_user->data, $store_info ); ?>
                    </div>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- .site-main -->
<?php do_action( 'draven/action/after_render_main' ); ?>
<?php get_footer();?>