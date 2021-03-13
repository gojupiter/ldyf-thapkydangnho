<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

if(!class_exists('Draven_WooCommerce')) {

    class Draven_WooCommerce{

        public static $shop_page_id = -1;

        public $image_caches = null;

        public function __construct(){

            if(!class_exists('WooCommerce')) return;

            $this->image_caches = array();

            self::$shop_page_id = wc_get_page_id('shop');

            add_filter('woocommerce_register_post_type_product', array( $this, 'woocommerce_register_post_type_product') );

            add_filter('draven/get_site_layout', array( $this, 'set_site_layout') );

            add_filter('draven/filter/sidebar_primary_name', array( $this, 'set_sidebar_for_shop'), 20 );

            add_action('init', array( $this, 'set_cookie_default' ), 2 );
            add_action('init', array( $this, 'custom_handling_empty_cart' ), 1 );

            add_filter('body_class', array( $this, 'add_body_class' ), 999 );
            add_filter('woocommerce_add_to_cart_fragments', array( $this, 'modify_ajax_cart_fragments'));


            remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
            remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

            remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );


            add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );
            add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10 );

            /**
             * In Plugin
             */
            add_filter('woocommerce_show_page_title', '__return_false');
            add_action('init', array( $this, 'disable_plugin_hooks'));

            add_filter('woocommerce_placeholder_img_src', array( $this, 'change_placeholder') );

            add_action('la_threesixty_before_get_image_array', array( $this, 'add_script_resize_image_in_360') );
            add_action('la_threesixty_after_get_image_array', array( $this, 'remove_script_resize_image_in_360') );

            /** VC Vendors */
            if(class_exists('WC_Vendors')){
                // Add sold by to product loop before add to cart
                if ( WC_Vendors::$pv_options->get_option( 'sold_by' ) ) {
                    remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
                    add_action( 'woocommerce_shop_loop_item_title', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 10 );
                }
            }

            /** For Dokan */
            if(function_exists('dokan')){
                add_filter('is_woocommerce', array( $this, 'filter_is_woocommerce_for_dokan') , 99);
            }


            /**
             * In Loop
             */


            /** FOR CATALOG */

            add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 2);

            add_filter('woocommerce_loop_add_to_cart_args', array( $this, 'woocommerce_loop_add_to_cart_args'), 10, 2 );

            add_filter('subcategory_archive_thumbnail_size', array( $this, 'modify_product_thumbnail_size') );
            add_action('woocommerce_before_subcategory_title', function(){ echo '<div class="cat-img">'; }, 9);
            add_action('woocommerce_before_subcategory_title', array( $this, 'add_script_resize_image_in_loop' ), 9 );
            add_action('woocommerce_before_subcategory_title', array( $this, 'add_shop_now_to_catalog'), 10);
            add_action('woocommerce_before_subcategory_title', array( $this, 'remove_script_resize_image_in_loop' ), 11 );
            add_action('woocommerce_before_subcategory_title', function(){ echo '<span class="item--overlay"></span></div>'; }, 11);
            add_action('woocommerce_shop_loop_subcategory_title', function(){ echo '<div class="cat-information">'; }, 1);
            add_action('woocommerce_shop_loop_subcategory_title', array( $this, 'add_desc_to_catalog'), 11);
            add_action('woocommerce_shop_loop_subcategory_title', array( $this, 'add_shop_now_to_catalog'), 15);
            add_action('woocommerce_shop_loop_subcategory_title', function(){ echo '</div>'; }, 20);

            /** END FOR CATALOG */

            remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
            remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

            remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

            remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

            add_filter('single_product_archive_thumbnail_size', array( $this, 'modify_product_thumbnail_size') );

            add_filter('loop_shop_per_page', array($this,'change_per_page_default'));

            add_action('woocommerce_before_shop_loop', array( $this, 'render_toolbar') );

            add_action('product_cat_class', array( $this, 'add_class_to_product_category_item' ), 10, 3 );
            add_filter('post_class', array( $this, 'add_class_to_product_loop'), 30, 3 );

            add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 1 );
            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'add_script_resize_image_in_loop' ), 5 );
            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'add_badge_stock_into_loop' ), 10 );
            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'add_second_thumbnail_to_loop' ), 15 );
            add_action('woocommerce_before_shop_loop_item_title', function(){ echo '<div class="item--overlay"></div>'; }, 20 );
            add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 30 );
            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'remove_script_resize_image_in_loop' ), 35 );

            add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );
            add_action('woocommerce_after_shop_loop_item_title', array($this, 'render_attribute_in_list'), 11);
            add_action('woocommerce_after_shop_loop_item_title', array( $this, 'shop_loop_item_excerpt' ), 15 );
            add_action('woocommerce_shop_loop_item_title', array( $this, 'add_count_up_timer_in_product_listing' ), 1 );


            add_action('draven/action/shop_loop_item_action_top', function(){ echo '<div class="wrap-addto">'; }, 5 );
            add_action('draven/action/shop_loop_item_action_top', array( $this, 'add_quick_view_btn' ), 10 );
            add_action('draven/action/shop_loop_item_action_top', array( $this, 'add_cart_btn' ), 40 );
            add_action('draven/action/shop_loop_item_action_top', array( $this, 'add_wishlist_btn' ), 20 );
            add_action('draven/action/shop_loop_item_action_top', array( $this, 'add_compare_btn' ), 30 );
            add_action('draven/action/shop_loop_item_action_top', function(){ echo '</div>'; }, 50 );

            add_action('draven/action/add_count_up_timer_in_product_listing', array( $this, 'add_count_up_timer_in_product_listing' ), 1 );

            add_action('draven/action/shop_loop_item_action', function(){ echo '<div class="wrap-addto">'; }, 5 );
            add_action('draven/action/shop_loop_item_action', array( $this, 'add_quick_view_btn' ), 10 );
            add_action('draven/action/shop_loop_item_action', array( $this, 'add_cart_btn' ), 40 );
            add_action('draven/action/shop_loop_item_action', array( $this, 'add_wishlist_btn' ), 30 );
            add_action('draven/action/shop_loop_item_action', array( $this, 'add_compare_btn' ), 30 );
            add_action('draven/action/shop_loop_item_action', function(){ echo '</div>'; }, 50 );

            /**
             * Product Page
             */

            add_filter('woocommerce_gallery_image_size', array( $this, 'woocommerce_gallery_image_size' ) );
            add_filter('woocommerce_single_product_image_thumbnail_html', array( $this, 'woocommerce_single_product_image_thumbnail_html' ), 10, 2 );

            add_action('wp_head', array($this, 'check_condition_show_upsell_crosssel'));

            add_filter('woocommerce_get_availability_text', array( $this, 'modify_availability_text_on_product_page'), 10, 2);

            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
            add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 3);
            add_action('woocommerce_single_product_summary', array( $this, 'add_next_prev_product_to_single' ), 4);
            add_action('woocommerce_single_product_summary', array( $this, 'add_stock_into_single' ), 9);
            add_action('woocommerce_single_product_summary', array( $this, 'add_count_up_timer_to_single' ), 12);

            add_action('woocommerce_single_product_summary', array( $this, 'add_wishlist_btn' ), 45);
            add_action('woocommerce_single_product_summary', array( $this, 'add_compare_btn' ), 45);


            add_action('woocommerce_share', array( $this, 'woocommerce_share' ));


            add_action('woocommerce_single_product_summary', function(){ echo '<div class="clearfix"></div>'; }, 19);

            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
            add_action('woocommerce_single_product_summary', function(){ echo '<div class="clearfix"></div>'; }, 50);

            add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 60);


            add_filter('woocommerce_product_description_heading', '__return_empty_string');
            add_filter('woocommerce_product_additional_information_heading', '__return_empty_string');

            add_filter('woocommerce_product_tabs', array( $this, 'add_custom_tab'));

            if( Draven()->settings()->get('product_single_hide_product_title', 'no') == 'yes'){
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
            }

            add_filter('template_include', array( $this, 'load_quickview_template'), 20 );

            if( ! draven_string_to_bool(Draven()->settings()->get('move_woo_tabs_to_bottom', 'no')) ){
                if(empty($_GET['product_quickview'])){
                    add_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 55);
                }
                remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
            }


            add_action('woocommerce_before_add_to_cart_button', function(){ echo '<div class="wrap-cart-cta">'; }, 1000);
            add_action('woocommerce_after_add_to_cart_button', function(){ echo '</div>'; }, 0);

            /**
             * Cart Page
             */

            add_action('wp', array( $this, 'set_recent_product_category_link' ) );


            remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10);

            if(Draven()->settings()->get('crosssell_products', 'off') == 'on'){
                add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display', 30);
            }

            add_action('woocommerce_cart_actions', array( $this, 'add_more_button_to_cart_from'));

            /**
             * Checkout
             */


            /**
             * Catalog Mode
             */

            if( Draven()->settings()->get('catalog_mode', 'off') == 'on'){
                // In Loop
                add_filter( 'woocommerce_loop_add_to_cart_link', '__return_empty_string', 10 );
                // In Single
                remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30);
                // In Page
                add_action( 'wp', array( $this, 'set_page_when_active_catalog_mode' ) );

                if( Draven()->settings()->get('catalog_mode_price', 'off') == 'on'){

                    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
                    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
                    add_filter('woocommerce_catalog_orderby', array( $this, 'remove_sortby_price_in_toolbar_when_active_catalog' ));
                    add_filter('woocommerce_default_catalog_orderby_options', array( $this, 'remove_sortby_price_in_toolbar_when_active_catalog' ));
                }
            }

            /**
             * Other
             */

            if(class_exists('YITH_WC_Social_Login_Frontend')){
                $yith_wc_login = YITH_WC_Social_Login_Frontend::get_instance();
                remove_action('woocommerce_login_form', array($yith_wc_login, 'social_buttons'), 10);
                add_action('woocommerce_login_form_end', array($yith_wc_login, 'social_buttons'), 10);
            }

            add_action('woocommerce_delete_product_transients', array( $this, 'delete_product_image_transient'), 20, 1 );
            add_action('post_updated', array( $this, 'delete_product_image_transient'), 20, 1);

            add_action('woocommerce_shortcode_before_products_loop', [$this, 'register_wc_hooks_for_elementor']);
            add_action('woocommerce_shortcode_before_current_query_loop', [$this, 'register_wc_hooks_for_elementor']);
            add_action('woocommerce_shortcode_before_la_products_loop', [$this, 'register_wc_hooks_for_elementor']);
        }

        public function register_wc_hooks_for_elementor(){
            remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
            remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
            remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
        }

        public function woocommerce_gallery_image_size( $size ) {
            return 'shop_single';
        }

        public function woocommerce_single_product_image_thumbnail_html( $html, $attachment_id ){

            $thumb_video_link = get_post_meta( $attachment_id, 'videolink', true);
            $new_html = 'data-videolink="'.esc_attr($thumb_video_link).'" ';
            $html = str_replace('<a ', '<a ' . $new_html, $html);

            $full_src         = wp_get_attachment_image_src( $attachment_id, 'full' );
            $overlay = '<span class="g-overlay" style="background-image: url('. esc_url($full_src[0]) .')"></span>';
            $html = str_replace('<img', $overlay. '<img datanolazy="true" ', $html);

            if(!empty($_GET['product_quickview'])){
                return $html;
            }

            return $html;
        }

        public function woocommerce_loop_add_to_cart_args( $args, $product) {
            if(isset($args['attributes'])){
                $args['attributes']['data-product_title'] = $product->get_title();
            }
            if(isset($args['class'])){
                $args['class'] = $args['class'] . ($product->is_purchasable() && $product->is_in_stock() ? '' : ' add_to_cart_button');
            }
            return $args;
        }

        public function delete_product_image_transient( $post_id ) {
            if($post_id > 0){
                delete_transient('latheme_prod_' . $post_id);
            }
        }

        protected function _get_product_image( $product_id, $image_size = '', $image_type = 'img', $with_second = false ){
            $html = '';
            if(empty($product_id) || empty($image_size)){
                return $html;
            }

            $alt_key = $with_second ? 'with_second' : 'feature';
            $transient_name = 'latheme_prod_' . $product_id;

            $cache_from_db = get_transient($transient_name);

            $this->image_caches[$product_id] = $cache_from_db;

            if( !empty($cache_from_db[$image_size][$image_type][$alt_key]) ) {
                return $cache_from_db[$image_size][$image_type][$alt_key];
            }
            else{
                return $html;
            }
        }

        protected function _set_product_image( $product_id, $image_size = '', $image_type = 'img', $with_second = false, $value = '' ){
            if(!empty($product_id) && !empty($image_size) && !empty($value)){
                $alt_key = $with_second ? 'with_second' : 'feature';
                $transient_name = 'latheme_prod_' . $product_id;
                $cache_from_db = !empty($this->image_caches[$product_id]) ? $this->image_caches[$product_id] : array();
                $cache_from_db[$image_size][$image_type][$alt_key] = $value;
                $this->image_caches[$product_id] = $cache_from_db;
                set_transient($transient_name, $cache_from_db, HOUR_IN_SECONDS);
            }
        }

        public function wrapper_start(){

            do_action( 'draven/action/before_render_main' );
            echo '<div id="main" class="site-main">';
            echo '<div class="container">';
            echo '<div class="row">';
            echo '<main id="site-content" class="'. esc_attr(Draven()->layout()->get_main_content_css_class('col-xs-12 site-content')) .'">';
            echo '<div class="site-content-inner">';
            do_action( 'draven/action/before_render_main_inner' );
            echo '<div class="page-content">';
        }

        public function wrapper_end(){

            echo '</div><!-- ./page-content -->';
            do_action( 'draven/action/after_render_main_inner' );
            echo '</div><!-- ./site-content-inner -->';
            echo '</main><!-- ./site-content -->';

            get_sidebar();

            echo '</div><!-- ./row -->';
            echo '</div><!-- ./container -->';
            echo '</div><!-- ./site-main -->';
            do_action( 'draven/action/after_render_main' );
        }

        public function add_body_class($classes){

            return $classes;
        }

        public function set_site_layout($layout){
            if(is_checkout() || is_cart()){
                $layout = 'col-1c';
            }
            if (!is_user_logged_in() && is_account_page()) {
                $layout = 'col-1c';
            }
            return $layout;
        }

        public function set_sidebar_for_shop( $sidebar ) {

            $context = (array) Draven()->get_current_context();

            if( in_array( 'is_woocommerce', $context ) ){

                if(in_array( 'is_archive', $context ) ){

                    $sidebar = Draven()->settings()->get('shop_sidebar', $sidebar);

                    if(Draven()->settings()->get('shop_global_sidebar', false)){
                        /*
                         * Return global sidebar if option will be enable
                         * We don't need more checking in context
                         */
                        return $sidebar;
                    }

                    if(in_array( 'is_shop', $context)){
                        if( ($single_sidebar = Draven()->settings()->get_post_meta( Draven()->get_page_id(), 'sidebar')) && !empty($single_sidebar) ){
                            $sidebar = $single_sidebar;
                        }
                    }
                    if(in_array( 'is_product_taxonomy', $context)){
                        if( ($tax_sidebar = Draven()->settings()->get_term_meta( get_queried_object_id(), 'sidebar')) && !empty($tax_sidebar) ){
                            $sidebar = $tax_sidebar;
                        }
                    }
                }

                elseif(in_array('is_product', $context)){
                    $sidebar = Draven()->settings()->get('products_sidebar', $sidebar);

                    if(Draven()->settings()->get('products_global_sidebar', false)){
                        /*
                         * Return global sidebar if option will be enable
                         * We don't need more checking in context
                         */
                        return $sidebar;
                    }
                    if( ($single_sidebar = Draven()->settings()->get_post_meta( get_the_ID(), 'sidebar')) && !empty($single_sidebar) ){
                        $sidebar = $single_sidebar;
                    }
                }
            }

            return $sidebar;
        }

        public function custom_handling_empty_cart(){
            if (isset($_REQUEST['clear-cart'])) {
                WC()->cart->empty_cart();
            }
        }

        public function woocommerce_register_post_type_product( $args ){

            if( self::$shop_page_id > 0 ){
                $args['labels']['archive_title'] = get_the_title(self::$shop_page_id);
            }
            return $args;
        }

        public function change_placeholder($src){
            return esc_url( get_template_directory_uri() . '/assets/images/wc-placeholder.png' );
        }

        /*
         * Loop
         */

        public function render_toolbar(){
            wc_get_template( 'loop/toolbar.php' );
        }

        public function add_class_to_product_category_item( $classes, $class, $category ){
            $classes[] = 'grid-item';
            return $classes;
        }

        public function add_shop_now_to_catalog(){

        }

        public function add_desc_to_catalog( $category ){

        }

        public function add_class_to_product_loop($classes, $class, $post_id){
            if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
                return $classes;
            }

            $product = wc_get_product( $post_id );

            if ( $product ) {
                $with_second_image = false;
                if( 'on' == Draven()->settings()->get('woocommerce_enable_crossfade_effect') ){
                    $with_second_image = true;
                }

                $disable_second_image = draven_get_wc_loop_prop('disable_alt_image');

                if($disable_second_image){
                    $with_second_image = false;
                }
                if($with_second_image && (($galleries = $product->get_gallery_image_ids()) && !empty($galleries[0]))){

                    $loop_layout = draven_get_wc_loop_prop('loop_layout');
                    $loop_style = draven_get_wc_loop_prop('loop_style');

                    if($loop_layout == 'list' && $loop_style == 'special'){

                    }
                    else{
                        $classes[] = 'thumb-has-effect';
                    }
                }
                else{
                    $classes[] = 'thumb-no-effect';
                }

                $enable_rating = Draven()->settings()->get('woocommerce_show_rating_on_catalog', 'off');
                if(get_option( 'woocommerce_enable_review_rating' ) === 'no'){
                    $enable_rating = 'off';
                }
                $classes[] = 'prod-rating-' . esc_attr(Draven()->settings()->get('woocommerce_show_rating_on_catalog', 'off'));
                if( draven_string_to_bool( $enable_rating ) && apply_filters('draven/shop/force_display_rating', true, $product->get_average_rating()) ) {
                    $classes[] = 'prod-has-rating';
                }
            }

            return $classes;
        }

        public function add_script_resize_image_in_loop(){
            $image_size = draven_get_wc_loop_prop('image_size');
            if(!empty($image_size)) {
                Draven()->images()->before_resize();
            }
        }

        public function remove_script_resize_image_in_loop(){
            $image_size = draven_get_wc_loop_prop('image_size');
            if(!empty($image_size)) {
                Draven()->images()->after_resize();
            }
        }

        public function modify_product_thumbnail_size($size){
            $image_size = draven_get_wc_loop_prop('image_size');
            if(!empty($image_size)) {
                return $image_size;
            }
            return $size;
        }

        public function add_second_thumbnail_to_loop(){

            global $product;

            $with_second_image = false;
            if( 'on' == Draven()->settings()->get('woocommerce_enable_crossfade_effect') ){
                $with_second_image = true;
            }

            $disable_second_image = draven_get_wc_loop_prop('disable_alt_image');
            $loop_layout = draven_get_wc_loop_prop('loop_layout');
            $loop_style = draven_get_wc_loop_prop('loop_style');

            if($disable_second_image){
                $with_second_image = false;
            }
            if( $loop_layout == 'list' && $loop_style == 'special'){
                $with_second_image = false;
            }

            $shop_catalog_size = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );

            $prods_masonry = draven_get_wc_loop_prop('prods_masonry', false);

            $image_type = 'img';
            if($prods_masonry){
                $image_type = 'bg';
            }
            $image_size = $shop_catalog_size;
            if(!empty($shop_catalog_size) && is_array($shop_catalog_size)){
                $image_size = implode('x', $shop_catalog_size);
            }

            $allow_caching = apply_filters('draven/help/allow_product_image_cache', false);
            $use_lazy_load = Draven_Helper::is_enable_image_lazy();

            $html_image_cache = $allow_caching ? $this->_get_product_image( $product->get_id(), $image_size, $image_type, $with_second_image) : '';

            if(empty($html_image_cache)){
                if($prods_masonry){
                    if($image = wp_get_attachment_image_src($product->get_image_id(), $shop_catalog_size)){
                        list( $src, $width, $height ) = $image;
                        $width = absint($width);
                        $height = absint($height);
                        $style = '';
                        if($width > 0 && $height > 0){
                            $style = 'padding-bottom:';
                            $style .= round( ($height/$width) * 100, 2 );
                            $style .= '%;';
                        }

                        $elm_css_class = 'pic-m-fallback pic-m-fallback-first';

                        if($use_lazy_load){
                            $elm_css_class .= ' la-lazyload-image';
                        }
                        else{
                            $style .= sprintf('background-image: url(%s);', esc_url(draven_get_relative_url($src)));
                        }

                        if(!empty($style)){
                            $style = sprintf(' style="%s"', $style);
                        }
                        $html_image_cache = sprintf(
                            '<div class="%s" data-background-image="%s"%s></div>',
                            $elm_css_class,
                            draven_get_relative_url($src),
                            $style
                        );
                    }
                    else{
                        $html_image_cache = '<div class="pic-m-fallback pic-m-fallback-first no-image"></div>';
                    }
                }
                else{
                    $html_image_cache = $product->get_image( $shop_catalog_size );
                }

                if($with_second_image){
                    $ids = $product->get_gallery_image_ids();
                    if(!empty($ids) && isset($ids[0])){
                        if($prods_masonry){
                            if($image = wp_get_attachment_image_src($ids[0], $shop_catalog_size)){
                                list( $src, $width, $height ) = $image;
                                $width = absint($width);
                                $height = absint($height);
                                $style = '';
                                if($width > 0 && $height > 0){
                                    $style = 'padding-bottom:';
                                    $style .= round( ($height/$width) * 100, 2 );
                                    $style .= '%;';
                                }

                                $elm_css_class = 'pic-m-fallback pic-m-fallback-second';

                                if($use_lazy_load){
                                    $elm_css_class .= ' la-lazyload-image';
                                }
                                else{
                                    $style .= sprintf('background-image: url(%s);', esc_url(draven_get_relative_url($src)));
                                }
                                if(!empty($style)){
                                    $style = sprintf(' style="%s"', $style);
                                }

                                $html_image_cache .= sprintf(
                                    '<div class="%s" data-background-image="%s"%s></div>',
                                    $elm_css_class,
                                    draven_get_relative_url($src),
                                    $style
                                );
                            }
                        }
                        else{
                            if( ($alt_image_src = wp_get_attachment_image_url($ids[0], $shop_catalog_size, false) ) && $alt_image_src ) {

                                $style = '';
                                $elm_css_class = 'wp-alt-image';

                                if($use_lazy_load){
                                    $elm_css_class .= ' la-lazyload-image';
                                }
                                else{
                                    $style = sprintf('style="background-image: url(%s);"', draven_get_relative_url($alt_image_src));
                                }

                                $html_image_cache .= sprintf(
                                    '<div class="%s" data-background-image="%s"%s></div>',
                                    $elm_css_class,
                                    draven_get_relative_url($alt_image_src),
                                    $style
                                );
                            }
                        }
                    }
                }

                if($allow_caching){
                    $this->_set_product_image($product->get_id(), $image_size, $image_type, $with_second_image, $html_image_cache);
                }
            }

            echo draven_render_variable($html_image_cache);
        }

        public function add_multi_thumbnail_to_loop(){
            global $product;
            if(($galleries = $product->get_gallery_image_ids()) && !empty($galleries)){
                $i = 0;
                echo '<div class="thumb-multi">';
                foreach($galleries as $gallery){
                    $i++;
                    ?>
                    <a href="<?php the_permalink()?>"><span class="thumb-multi-item" style="background-image: url(<?php echo wp_get_attachment_image_url($gallery, apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' )); ?>)"></span></a>
                    <?php
                    if($i == 2){
                        break;
                    }
                }
                echo  '</div>';
            }
        }

        public function add_badge_stock_into_loop(){
            global $product;
            $availability = $product->get_availability();
            if(!empty($availability['class']) && $availability['class'] == 'out-of-stock' && !empty($availability['availability'])){
                printf('<span class="la-custom-badge badge-out-of-stock">%s</span>', esc_html($availability['availability']));
            }
        }

        public function add_quick_view_btn(){
            if( 'on' == Draven()->settings()->get('woocommerce_show_quickview_btn', 'off') ){
                global $product;
                printf(
                    '<a class="%s" href="%s" data-href="%s" title="%s">%s</a>',
                    'quickview button la-quickview-button',
                    esc_url(get_the_permalink($product->get_id())),
                    esc_url(add_query_arg('product_quickview', $product->get_id(), get_the_permalink($product->get_id()))),
                    esc_attr_x('Quick Shop', 'front-view', 'draven'),
                    esc_attr_x('Quick Shop', 'front-view', 'draven')
                );
            }
        }

        public function add_cart_btn(){
            if( Draven()->settings()->get('catalog_mode', 'off') != 'on' && Draven()->settings()->get('woocommerce_show_addcart_btn', 'on') == 'on' ) {
                woocommerce_template_loop_add_to_cart();
            }
        }

        public function add_compare_btn(){
            global $yith_woocompare, $product;
            if( Draven()->settings()->get('woocommerce_show_compare_btn', 'off') == 'on' ) {
                if ( !empty($yith_woocompare->obj) ) {

                    $action_add = 'yith-woocompare-add-product';

                    $css_class = 'add_compare button';

                    if( $yith_woocompare->obj instanceof YITH_Woocompare_Frontend ){
                        $action_add = $yith_woocompare->obj->action_add;
                        if(!empty($yith_woocompare->obj->products_list) && in_array($product->get_id(), $yith_woocompare->obj->products_list)){
                            $css_class .= ' added';
                        }
                    }
                    $url_args = array('action' => $action_add, 'id' => $product->get_id());
                    $url = apply_filters('yith_woocompare_add_product_url', wp_nonce_url(add_query_arg($url_args), $action_add));

                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s">%s</a>',
                        esc_attr($css_class),
                        esc_url($url),
                        esc_attr_x('Add to Compare','front-view', 'draven'),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr_x('Add to Compare','front-view', 'draven')
                    );
                }
                else{
                    $css_class = 'add_compare button la-core-compare';
                    $url = '#';
                    $text = esc_html_x('Add to Compare','front-view', 'draven');
                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s">%s</a>',
                        esc_attr($css_class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }
            }
        }

        public function add_wishlist_btn(){

            if(Draven()->settings()->get('woocommerce_show_wishlist_btn', 'off') == 'on'){
                global $product;
                if (function_exists('YITH_WCWL')) {
                    $default_wishlists = is_user_logged_in() ? YITH_WCWL()->get_wishlists(array('is_default' => true)) : false;
                    if (!empty($default_wishlists)) {
                        $default_wishlist = $default_wishlists[0]['ID'];
                    }
                    else {
                        $default_wishlist = false;
                    }

                    if (YITH_WCWL()->is_product_in_wishlist($product->get_id(), $default_wishlist)) {
                        $text = esc_html_x('View Wishlist', 'front-view', 'draven');
                        $class = 'add_wishlist la-yith-wishlist button added';
                        $url = YITH_WCWL()->get_wishlist_url('');
                    }
                    else {
                        $text = esc_html_x('Add to Wishlist', 'front-view', 'draven');
                        $class = 'add_wishlist la-yith-wishlist button';
                        $url = add_query_arg('add_to_wishlist', $product->get_id(), YITH_WCWL()->get_wishlist_url(''));
                    }

                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s">%s</a>',
                        esc_attr($class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }

                elseif(class_exists('TInvWL_Public_AddToWishlist')){
                    $wishlist = TInvWL_Public_AddToWishlist::instance();
                    $user_wishlist = $wishlist->user_wishlist($product);
                    if(isset($user_wishlist[0], $user_wishlist[0]['in']) && $user_wishlist[0]['in']){
                        $class = 'add_wishlist button la-ti-wishlist added';
                        $url = tinv_url_wishlist_default();
                        $text = esc_html_x('View Wishlist', 'front-view', 'draven');
                    }
                    else{
                        $class = 'add_wishlist button la-ti-wishlist';
                        $url = '#';
                        $text = esc_html_x('Add to Wishlist', 'front-view', 'draven');
                    }
                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s">%s</a>',
                        esc_attr($class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }

                else{

                    if(Draven_WooCommerce_Wishlist::is_product_in_wishlist($product->get_id())){
                        $class = 'add_wishlist button la-core-wishlist added';
                        $url = draven_get_wishlist_url();
                        $text = esc_html_x('View Wishlist', 'front-view', 'draven');
                    }
                    else{
                        $class = 'add_wishlist button la-core-wishlist';
                        $url = '#';
                        $text = esc_html_x('Add to Wishlist', 'front-view', 'draven');
                    }

                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s">%s</a>',
                        esc_attr($class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }
            }
        }

        public function add_count_up_timer_in_product_listing(){
            global $product;
            if($product->is_on_sale()){
                $sale_price_dates_to = $product->get_date_on_sale_to() && ( $date = $product->get_date_on_sale_to()->getOffsetTimestamp() ) ? $date : '';
                if(!empty($sale_price_dates_to)){
                    ?>
                        <div class="elementor-lastudio-countdown-timer lastudio-elements js-el" data-la_component="CountDownTimer">
                            <div class="lastudio-countdown-timer" data-due-date="<?php echo esc_attr($sale_price_dates_to); ?>">
                            <div class="lastudio-countdown-timer__item item-days">
                            <div class="lastudio-countdown-timer__item-value" data-value="days"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                            <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Days', 'draven') ?></div></div>
                            <div class="lastudio-countdown-timer__item item-hours">
                            <div class="lastudio-countdown-timer__item-value" data-value="hours"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                            <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Hours', 'draven');?></div></div>
                            <div class="lastudio-countdown-timer__item item-minutes">
                            <div class="lastudio-countdown-timer__item-value" data-value="minutes"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                            <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Mins', 'draven'); ?></div></div>
                            <div class="lastudio-countdown-timer__item item-seconds">
                            <div class="lastudio-countdown-timer__item-value" data-value="seconds"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                            <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Secs', 'draven'); ?></div></div>
                            </div>
                        </div>
                    <?php
                }
            }
        }

        public function add_category_in_product_listing(){
            global $product;
            add_filter('get_the_terms', 'draven_exclude_demo_term_in_category');
            echo wc_get_product_category_list($product->get_id(),'<span>, </span>', '<div class="product_item--category-link">', '</div>');
            draven_deactive_filter('get_the_terms', 'draven_exclude_demo_term_in_category');
        }

        public function shop_loop_item_title(){
            the_title( sprintf( '<h3 class="product_item--title"><a href="%s">', esc_url( get_the_permalink() ) ), '</a></h3>' );
        }

        public function render_attribute_in_list(){
            if(class_exists('LaStudio_Swatch')){
                global $product;
                $swatches_instance = new LaStudio_Swatch();
                $swatches_instance->render_attribute_in_product_list_loop($product);
            }
        }

        public function shop_loop_item_excerpt(){

            $is_main_loop = draven_get_wc_loop_prop('is_main_loop', false);
            $loop_layout = draven_get_wc_loop_prop('loop_layout', 'grid');
            if( $is_main_loop || $loop_layout == 'list' ) {
                echo '<div class="item--excerpt">';
                the_excerpt();
                echo '</div>';
            }
        }

        public static function product_per_page(){
            return apply_filters('draven/filter/product_per_page', Draven()->settings()->get('product_per_page_default', 9));
        }

        public static function product_per_page_array(){
            $per_page_array = apply_filters('draven/filter/product_per_page_array', Draven()->settings()->get('product_per_page_allow', ''));
            if(!empty($per_page_array)){
                $per_page_array = explode(',', $per_page_array);
                $per_page_array = array_map('trim', $per_page_array);
                $per_page_array = array_map('absint', $per_page_array);
                asort($per_page_array);
                return $per_page_array;
            }
            else{
                return array();
            }
        }


        public function change_per_page_default($cols){
            $per_page_array = $this->product_per_page_array();
            $per_page = $this->product_per_page();
            if(!empty($per_page_array) && ( in_array($per_page, $per_page_array) || count($per_page_array) == 1  )){
                $cols = $per_page;
            }
            else{
                $cols = $per_page;
            }
            return $cols;
        }

        public function set_cookie_default(){
            if (isset($_GET['per_page']) && $per_page = $_GET['per_page']) {
                add_filter('draven/filter/product_per_page', array( $this, 'get_parameter_per_page'));
            }
        }

        public function get_parameter_per_page($per_page) {
            if (isset($_GET['per_page']) && ($_per_page = $_GET['per_page'])) {
                $param_allow = $this->product_per_page_array();
                if(!empty($param_allow) && in_array($_per_page, $param_allow)){
                    $per_page = $_per_page;
                }
            }
            return $per_page;
        }

        /*
         * Single
         */

        public function add_next_prev_product_to_single(){
            echo '<div class="product-nextprev">';
            $prev = get_previous_post(false,'','product_cat');
            $tpl = '<a href="%1$s" title="%2$s"%4$s>%3$s</a>';
            $qv_tpl = '';
            if(!empty($prev) && isset($prev->ID)){

                $prev_link = get_the_permalink($prev->ID);
                if(isset($_GET['product_quickview'])){
                    $qv_tpl = sprintf('data-href="%1$s" class="la-quickview-button"', add_query_arg('product_quickview', $prev->ID, $prev_link));
                }
                echo sprintf(
                    $tpl,
                    $prev_link,
                    esc_attr(get_the_title($prev->ID)),
                    '<i class="dlicon arrows-1_tail-left"></i>',
                    $qv_tpl
                );
            }
            $next = get_next_post(false,'','product_cat');
            if(!empty($next) && isset($next->ID)){
                $next_link = get_the_permalink($next->ID);
                if(isset($_GET['product_quickview'])){
                    $qv_tpl = sprintf('data-href="%1$s" class="la-quickview-button"', add_query_arg('product_quickview', $next->ID, $next_link));
                }
                echo sprintf(
                    $tpl,
                    $next_link,
                    esc_attr(get_the_title($next->ID)),
                    '<i class="dlicon arrows-1_tail-right"></i>',
                    $qv_tpl
                );
            }
            echo '</div>';
            echo '<div class="clearfix"></div>';
        }

        public function add_count_up_timer_to_single(){
            if(!isset($_GET['product_quickview']) && Draven()->settings()->get('show_product_countdown')){
                global $product;
                if($product->is_on_sale()){
                    $sale_price_dates_to = $product->get_date_on_sale_to() && ( $date = $product->get_date_on_sale_to()->getOffsetTimestamp() ) ? date( 'Y/m/d H:i:s', $date ) : '';
                    if(!empty($sale_price_dates_to)){
                        echo '<div class="product_item--info">';
                        echo do_shortcode('[la_countdown count_style="prod" countdown_opts="sday,shr,smin,ssec" datetime="'. $sale_price_dates_to .'"]');
                        echo '</div>';
                    }
                }
            }
        }

        public function check_condition_show_upsell_crosssel(){
            if ( Draven()->settings()->get('related_products', 'off') != 'on' ) {
                remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
            }
            if ( Draven()->settings()->get('upsell_products', 'off') != 'on' ) {
                remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
            }
        }

        public function add_custom_tab($tabs){
            if ( Draven()->settings()->get('woo_enable_custom_tab', 'off') == 'on' ) {
                $tab_content = Draven()->settings()->get('woo_custom_tab_content', '');
                $tab_title = Draven()->settings()->get('woo_custom_tab_title', '');
                if(!empty($tab_title) && !empty($tab_content)){
                    $tabs['custom_tab'] = array(
                        'title' => $tab_title,
                        'priority' => 40,
                        'callback' => array( $this, 'get_custom_tab_content')
                    );
                }
            }
            return $tabs;
        }

        public function get_custom_tab_content(){
            echo Draven_Helper::remove_js_autop( Draven()->settings()->get('woo_custom_tab_content', ''), true);
        }

        public function add_stock_into_single(){
            global $product;
            echo wc_get_stock_html( $product );
        }

        public function modify_availability_text_on_product_page( $availability, $product ){
            if ( ! $product->is_in_stock() ) {

            } elseif ( $product->managing_stock() && $product->is_on_backorder( 1 ) ) {

            } elseif ( $product->managing_stock() ) {
                $availability = $availability = __( 'in stock', 'draven' );
            }
            return $availability;
        }

        public function add_sku_to_single_product(){
            global $product;
            if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ){
                ?>
                <div class="product_meta-top">
                    <span class="sku_wrapper"><span class="sku"><?php if($sku = $product->get_sku()) { echo draven_render_variable($sku); } else { esc_html__( 'N/A', 'draven' ); } ?></span></span>
                </div>
                <?php
            }
        }

        /*
         * Cart
         */

        public function modify_ajax_cart_fragments( $fragments ){
            $fragments['span.la-cart-count'] = sprintf('<span class="header-cart-count-icon component-target-badget la-cart-count">%s</span>', WC()->cart->get_cart_contents_count());
            $text = '<span class="la-cart-text">'. esc_html_x('%s items','front-view', 'draven') .'</span>';
            $fragments['span.la-cart-text'] = sprintf($text, WC()->cart->get_cart_contents_count());
            $fragments['span.la-cart-total-price'] = sprintf('<span class="la-cart-total-price">%s</span>', WC()->cart->get_cart_total());
            return $fragments;
        }

        public function add_shipping_calculator_form_into_cart(){
            woocommerce_shipping_calculator();
        }

        public function add_coupon_form_into_cart(){
            if ( wc_coupons_enabled() ) : ?>
                <div class="la-coupon-form">
                    <h2><?php echo esc_html_x('Coupon Code', 'front-view', 'draven') ?></h2>
                    <p><?php echo esc_html_x('Enter your coupon code if you have one.','front-view', 'draven')?></p>
                    <div class="la-coupon">
                        <p class="form-row form-row-wide">
                            <input type="text" class="input-text" id="coupon_code_ref" value="" placeholder="<?php echo esc_attr_x( 'Enter your coupon code..', 'front-view', 'draven' ); ?>" />
                        </p>
                        <button type="button" class="button" id="coupon_btn_ref"><?php echo esc_html_x( 'Apply coupon', 'front-view', 'draven' ); ?></button>
                    </div>
                </div>
            <?php endif;
        }

        public function add_more_button_to_cart_from(){
            $category_recent_link = get_transient( 'la_recent_product_category_link' );
            ?>
            <input type="submit" class="button btn-clear-cart" name="clear-cart" value="<?php echo esc_attr_x('Clear Cart', 'front-view', 'draven');?>">
            <?php
        }

        public function set_recent_product_category_link(){
            if(is_shop()){
                delete_transient( 'la_recent_product_category_link' );
                set_transient( 'la_recent_product_category_link', wc_get_page_permalink('shop') , 60*60*12 );
            }
            else if(is_product_taxonomy()){
                delete_transient( 'la_recent_product_category_link' );
                set_transient( 'la_recent_product_category_link', get_term_link(get_queried_object()), 60*60*12 );
            }
        }

        /*
         * Checkout
         */


        /*
         * Catalog Mode
         */
        public function set_page_when_active_catalog_mode(){
            wp_reset_postdata();
            if (is_cart() || is_checkout()) {
                wp_redirect(wc_get_page_permalink('shop'));
                exit;
            }
        }

        public function remove_sortby_price_in_toolbar_when_active_catalog( $array ){
            if( isset($array['price']) ){
                unset( $array['price'] );
            }
            if( isset($array['price-desc']) ){
                unset( $array['price-desc'] );
            }
            return $array;
        }

        /*
         * Other
         */

        public function disable_plugin_hooks() {
            global $yith_woocompare;
            if(function_exists('YITH_WCWL_Frontend')){
                $yith_wcwl_obj = YITH_WCWL_Frontend();
                remove_action('wp_head', array($yith_wcwl_obj, 'add_button'));
            }
            if( !empty($yith_woocompare->obj) && ($yith_woocompare->obj instanceof YITH_Woocompare_Frontend ) ){
                remove_action('woocommerce_single_product_summary', array($yith_woocompare->obj, 'add_compare_link'), 35);
                remove_action('woocommerce_after_shop_loop_item', array($yith_woocompare->obj, 'add_compare_link'), 20);
            }
        }


        public function add_script_resize_image_in_360(){
            Draven()->images()->before_resize();
        }

        public function remove_script_resize_image_in_360(){
            Draven()->images()->after_resize();
        }

        public function woocommerce_share(){
            if(Draven()->settings()->get('product_sharing') == 'on'){
                $post_link = get_permalink();
                $post_title = get_the_title();
                $image = '';
                if(has_post_thumbnail()){
                    $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                }
                echo '<div class="product-share-box">';
                echo sprintf( '<label>%s</label>', esc_html_x('Share on', 'front-end', 'draven') );
                draven_social_sharing($post_link,$post_title,$image);
                echo '</div>';
            }
        }

        public function load_quickview_template( $template ){
            if(is_singular('product') && isset($_GET['product_quickview'])){
                $file     = locate_template( array(
                    'woocommerce/single-quickview.php'
                ) );
                if($file){
                    return $file;
                }
            }
            return $template;
        }

        public function woocommerce_before_shipping_calculator(){
            printf(
                '<div class="la-shipping-form"><h2>%s</h2><p>%s</p>',
                esc_html_x('Calculate Shipping', 'front-view', 'draven'),
                esc_html_x('Estimate your shipping fee *', 'front-view', 'draven')
            );
        }

        public function woocommerce_after_shipping_calculator(){
            echo '</div>';
        }

        public function filter_is_woocommerce_for_dokan( $boolean ) {

            if(function_exists('dokan_is_store_page') && dokan_is_store_page()){
                $boolean = true;
            }

            return $boolean;
        }
    }
}