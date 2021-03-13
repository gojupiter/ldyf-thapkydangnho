<?php if ( ! defined( 'ABSPATH' ) ) { die; }

add_filter('LaStudio_Builder/option_builder_name', 'draven_set_option_builder_name');
if(!function_exists('draven_set_option_builder_name')){
    function draven_set_option_builder_name( $var = ''){
        return 'draven';
    }
}

add_filter('LaStudio_Builder/option_builder_key', 'draven_set_option_builder_key');
if(!function_exists('draven_set_option_builder_key')){
    function draven_set_option_builder_key( $var = ''){
        return 'labuider_for_draven';
    }
}

add_filter('LaStudio_Builder/logo_id', 'draven_set_logo_for_builder');
if(!function_exists('draven_set_logo_for_builder')){
    function draven_set_logo_for_builder( $logo_id ) {
        return Draven()->settings()->get('logo', $logo_id);
    }
}

add_filter('LaStudio_Builder/logo_transparency_id', 'draven_set_logo_transparency_for_builder');
if(!function_exists('draven_set_logo_transparency_for_builder')){
    function draven_set_logo_transparency_for_builder( $logo_id ) {
        return Draven()->settings()->get('logo_transparency', $logo_id);
    }
}

add_action('LaStudio_Builder/display_socials', 'draven_set_social_for_builder');
if(!function_exists('draven_set_social_for_builder')){
    function draven_set_social_for_builder(){
        $social_links = Draven()->settings()->get('social_links', array());
        if(!empty($social_links)){
            echo '<div class="social-media-link style-default">';
            foreach($social_links as $item){
                if(!empty($item['link']) && !empty($item['icon'])){
                    $title = isset($item['title']) ? $item['title'] : '';
                    printf(
                        '<a href="%1$s" class="%2$s" title="%3$s" target="_blank" rel="nofollow"><i class="%4$s"></i></a>',
                        esc_url($item['link']),
                        esc_attr(sanitize_title($title)),
                        esc_attr($title),
                        esc_attr($item['icon'])
                    );
                }
            }
            echo '</div>';
        }
    }
}

add_filter('LaStudio/global_loop_variable', 'draven_set_loop_variable');
if(!function_exists('draven_set_loop_variable')){
    function draven_set_loop_variable( $var = ''){
        return 'draven_loop';
    }
}


add_filter('lastudio-elements/advanced-map/api', 'draven_add_googlemap_api');
if(!function_exists('draven_add_googlemap_api')){
    function draven_add_googlemap_api( $key = '' ){
        return Draven()->settings()->get('google_key', $key);
    }
}

add_filter('draven/filter/page_title', 'draven_override_page_title_bar_title', 10, 2);
if(!function_exists('draven_override_page_title_bar_title')){
    function draven_override_page_title_bar_title( $title, $args ){

        $context = (array) Draven()->get_current_context();

        if(in_array('is_singular', $context)){
            $custom_title = Draven()->settings()->get_post_meta( get_queried_object_id(), 'page_title_custom');
            if(!empty( $custom_title) ){
                return sprintf($args['page_title_format'], $custom_title);
            }
        }

        if(in_array('is_tax', $context) || in_array('is_category', $context) || in_array('is_tag', $context)){
            $custom_title = Draven()->settings()->get_term_meta( get_queried_object_id(), 'page_title_custom');
            if(!empty( $custom_title) ){
                return sprintf($args['page_title_format'], $custom_title);
            }
        }

        if(in_array('is_shop', $context) && function_exists('wc_get_page_id') && ($shop_page_id = wc_get_page_id('shop')) && $shop_page_id){
            $custom_title = Draven()->settings()->get_post_meta( $shop_page_id, 'page_title_custom');
            if(!empty( $custom_title) ){
                return sprintf($args['page_title_format'], $custom_title);
            }
        }

        return $title;
    }
}

add_action( 'pre_get_posts', 'draven_set_posts_per_page_for_portfolio_cpt' );
if(!function_exists('draven_set_posts_per_page_for_portfolio_cpt')){
    function draven_set_posts_per_page_for_portfolio_cpt( $query ) {
        if ( !is_admin() && $query->is_main_query() ) {
            if( post_type_exists('la_portfolio') && (is_post_type_archive( 'la_portfolio' ) || is_tax(get_object_taxonomies( 'la_portfolio' ))) ){
                $pf_per_page = (int) Draven()->settings()->get('portfolio_per_page', 9);
                $query->set( 'posts_per_page', $pf_per_page );
            }
        }
    }
}

add_filter('yith_wc_social_login_icon', 'draven_override_yith_wc_social_login_icon', 10, 3);
if(!function_exists('draven_override_yith_wc_social_login_icon')){
    function draven_override_yith_wc_social_login_icon($social, $key, $args){
        if(!is_admin()){
            $social = sprintf(
                '<a class="%s" href="%s">%s</a>',
                'social_login ywsl-' . esc_attr($key) . ' social_login-' . esc_attr($key),
                $args['url'],
                isset( $args['value']['label'] ) ? $args['value']['label'] : $args['value']
            );
        }
        return $social;
    }
}

add_action('wp', 'draven_hook_maintenance');
if(!function_exists('draven_hook_maintenance')){
    function draven_hook_maintenance(){
        wp_reset_postdata();
        $enable_private = Draven()->settings()->get('enable_maintenance', 'no');
        if($enable_private == 'yes'){
            if(!is_user_logged_in()){
                $page_id = Draven()->settings()->get('maintenance_page');
                if(empty($page_id)){
                    wp_redirect(wp_login_url());
                    exit;
                }
                else{
                    $page_id = absint($page_id);
                    if(!is_page($page_id)){
                        wp_redirect(get_permalink($page_id));
                        exit;
                    }
                }
            }
        }
    }
}

add_filter('widget_archives_args', 'draven_modify_widget_archives_args');
if(!function_exists('draven_modify_widget_archives_args')){
    function draven_modify_widget_archives_args( $args ){
        if(isset($args['show_post_count'])){
            unset($args['show_post_count']);
        }
        return $args;
    }
}
if(isset($_GET['la_doing_ajax'])){
    remove_action('template_redirect', 'redirect_canonical');
}
add_filter('woocommerce_redirect_single_search_result', '__return_false');


add_filter('draven/filter/breadcrumbs/items', 'draven_theme_setup_breadcrumbs_for_dokan', 10, 2);
if(!function_exists('draven_theme_setup_breadcrumbs_for_dokan')){
    function draven_theme_setup_breadcrumbs_for_dokan( $items, $args ){
        if (  function_exists('dokan_is_store_page') && dokan_is_store_page() ) {
            $store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
            if( count($items) > 1 ){
                unset($items[(count($items) - 1)]);
            }
            $items[] = sprintf(
                '<div class="la-breadcrumb-item"><span class="%2$s">%1$s</span></div>',
                esc_attr($store_user->get_shop_name()),
                'la-breadcrumb-item-link'
            );
        }

        return $items;
    }
}


add_filter('draven/filter/show_page_title', 'draven_filter_show_page_title', 10, 1 );
add_filter('draven/filter/show_breadcrumbs', 'draven_filter_show_breadcrumbs', 10, 1 );

if(!function_exists('draven_filter_show_page_title')){
    function draven_filter_show_page_title( $show ){
        $context = Draven()->get_current_context();
        if( !empty( array_intersect( $context, array( 'is_product_taxonomy', 'is_shop' ) ) ) && Draven()->settings()->get('archive_product_hide_page_title', 'no') == 'yes' ){
            return false;
        }

        if(!empty( array_intersect( $context, array( 'is_archive_la_portfolio', 'is_tax_la_portfolio' ) ) ) && Draven()->settings()->get('archive_portfolio_hide_page_title', 'no') == 'yes' ){
            return false;
        }

        if( is_singular('post') && Draven()->settings()->get('post_single_hide_page_title', 'no') == 'yes' ){
            return false;
        }

        if( in_array( 'is_product', $context ) && Draven()->settings()->get('product_single_hide_page_title', 'no') == 'yes' ) {
            return false;
        }

        if( in_array( 'is_single_la_portfolio', $context ) && Draven()->settings()->get('portfolio_single_hide_page_title', 'no') == 'yes' ){
            return false;
        }
        return $show;
    }
}

if(!function_exists('draven_filter_show_breadcrumbs')){
    function draven_filter_show_breadcrumbs( $show ){
        $context = Draven()->get_current_context();
        if( !empty( array_intersect( $context, array( 'is_product_taxonomy', 'is_shop' ) ) ) && Draven()->settings()->get('archive_product_hide_breadcrumb', 'no') == 'yes' ){
            return false;
        }

        if(!empty( array_intersect( $context, array( 'is_archive_la_portfolio', 'is_tax_la_portfolio' ) ) ) && Draven()->settings()->get('archive_portfolio_hide_breadcrumb', 'no') == 'yes' ){
            return false;
        }

        if( is_singular('post') && Draven()->settings()->get('post_single_hide_breadcrumb', 'no') == 'yes' ){
            return false;
        }

        if( in_array( 'is_product', $context ) && Draven()->settings()->get('product_single_hide_breadcrumb', 'no') == 'yes' ){
            return false;
        }

        if( in_array( 'is_single_la_portfolio', $context ) && Draven()->settings()->get('portfolio_single_hide_breadcrumb', 'no') == 'yes' ){
            return false;
        }
        return $show;
    }
}


add_filter('LaStudio/swatches/args/show_option_none', 'draven_allow_translate_woo_text_in_swatches', 10, 1);
if(!function_exists('draven_allow_translate_woo_text_in_swatches')){
    function draven_allow_translate_woo_text_in_swatches( $text ){
        return esc_html_x( 'Choose an option', 'front-view', 'draven' );
    }
}

if(!function_exists('draven_get_relative_url')){
    function draven_get_relative_url( $url ) {
        return draven_is_external_resource( $url ) ? $url : str_replace( array( 'http://', 'https://' ), '//', $url );
    }
}
if(!function_exists('draven_is_external_resource')){
    function draven_is_external_resource( $url ) {
        $wp_base = str_replace( array( 'http://', 'https://' ), '//', get_home_url( null, '/', 'http' ) );
        return strstr( $url, '://' ) && strstr( $wp_base, $url );
    }
}

if (!function_exists('draven_wpml_object_id')) {
    function draven_wpml_object_id( $element_id, $element_type = 'post', $return_original_if_missing = false, $ulanguage_code = null ) {
        if ( function_exists( 'wpml_object_id_filter' ) ) {
            return wpml_object_id_filter( $element_id, $element_type, $return_original_if_missing, $ulanguage_code );
        } elseif ( function_exists( 'icl_object_id' ) ) {
            return icl_object_id( $element_id, $element_type, $return_original_if_missing, $ulanguage_code );
        } else {
            return $element_id;
        }
    }
}

/**
 * Override page title bar from global settings
 * What we need to do now is
 * 1. checking in single content types
 *  1.1) post
 *  1.2) product
 *  1.3) portfolio
 * 2. checking in archives
 *  2.1) shop
 *  2.2) portfolio
 *
 * TIPS: List functions will be use to check
 * `is_product`, `is_single_la_portfolio`, `is_shop`, `is_woocommerce`, `is_product_taxonomy`, `is_archive_la_portfolio`, `is_tax_la_portfolio`
 */

if(!function_exists('draven_override_page_title_bar_from_context')){
    function draven_override_page_title_bar_from_context( $value, $key, $context ){

        $array_key_allow = array(
            'page_title_bar_style',
            'page_title_bar_layout',
            'page_title_font_size',
            'page_title_bar_background',
            'page_title_bar_heading_color',
            'page_title_bar_text_color',
            'page_title_bar_link_color',
            'page_title_bar_link_hover_color',
            'page_title_bar_spacing',
            'page_title_bar_spacing_desktop_small',
            'page_title_bar_spacing_tablet',
            'page_title_bar_spacing_mobile'
        );

        $array_key_alternative = array(
            'page_title_font_size',
            'page_title_bar_background',
            'page_title_bar_heading_color',
            'page_title_bar_text_color',
            'page_title_bar_link_color',
            'page_title_bar_link_hover_color',
            'page_title_bar_spacing',
            'page_title_bar_spacing_desktop_small',
            'page_title_bar_spacing_tablet',
            'page_title_bar_spacing_mobile'
        );

        /**
         * Firstly, we need to check the `$key` input
         */
        if( !in_array($key, $array_key_allow) ){
            return $value;
        }

        /**
         * Secondary, we need to check the `$context` input
         */
        if( !in_array('is_singular', $context) && !in_array('is_woocommerce', $context) && !in_array('is_archive_la_portfolio', $context) && !in_array('is_tax_la_portfolio', $context)){
            return $value;
        }

        if( !is_singular(array('product', 'post', 'la_portfolio')) && !in_array('is_product_taxonomy', $context) && !in_array('is_shop', $context) ) {
            return $value;
        }


        $func_name = 'get_post_meta';
        $queried_object_id = get_queried_object_id();

        if( in_array('is_product_taxonomy', $context) || in_array('is_tax_la_portfolio', $context) ){
            $func_name = 'get_term_meta';
        }

        if(in_array('is_shop', $context)){
            $queried_object_id = Draven_WooCommerce::$shop_page_id;
        }

        if ( 'page_title_bar_layout' == $key ) {
            $page_title_bar_layout = Draven()->settings()->$func_name($queried_object_id, $key);
            if($page_title_bar_layout && $page_title_bar_layout != 'inherit'){
                return $page_title_bar_layout;
            }
        }

        if( 'yes' == Draven()->settings()->$func_name($queried_object_id, 'page_title_bar_style') && in_array($key, $array_key_alternative) ){
            return $value;
        }

        $key_override = $new_key = false;

        if( in_array('is_product', $context) ){
            $key_override = 'single_product_override_page_title_bar';
            $new_key = 'single_product_' . $key;
        }
        elseif( in_array('is_single_la_portfolio', $context) ) {
            $key_override = 'single_portfolio_override_page_title_bar';
            $new_key = 'single_portfolio_' . $key;
        }
        elseif( is_singular('post') ) {
            $key_override = 'single_post_override_page_title_bar';
            $new_key = 'single_post_' . $key;
        }
        elseif( in_array('is_single_la_portfolio', $context) ) {
            $key_override = 'single_portfolio_override_page_title_bar';
            $new_key = 'single_portfolio_' . $key;
        }
        elseif ( in_array('is_shop', $context) || in_array('is_product_taxonomy', $context) ) {
            $key_override = 'woo_override_page_title_bar';
            $new_key = 'woo_' . $key;
        }
        elseif ( in_array('is_archive_la_portfolio', $context) || in_array('is_tax_la_portfolio', $context) ) {
            $key_override = 'archive_portfolio_override_page_title_bar';
            $new_key = 'archive_portfolio_' . $key;
        }

        if(false != $key_override){
            if( 'on' == Draven()->settings()->get($key_override, 'off') ){
                return Draven()->settings()->get($new_key, $value);
            }
        }

        return $value;
    }

    add_filter('draven/setting/get_setting_by_context', 'draven_override_page_title_bar_from_context', 10, 3);
}

/**
 * This function allow get property of `woocommerce_loop` inside the loop
 * @since 1.0.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */

if(!function_exists('draven_get_wc_loop_prop')){
    function draven_get_wc_loop_prop( $prop, $default = ''){
        return isset( $GLOBALS['woocommerce_loop'], $GLOBALS['woocommerce_loop'][ $prop ] ) ? $GLOBALS['woocommerce_loop'][ $prop ] : $default;
    }
}

/**
 * This function allow set property of `woocommerce_loop`
 * @since 1.0.0
 * @param string $prop Prop to set.
 * @param string $value Value to set.
 */

if(!function_exists('draven_set_wc_loop_prop')){
    function draven_set_wc_loop_prop( $prop, $value = ''){
        if(isset($GLOBALS['woocommerce_loop'])){
            $GLOBALS['woocommerce_loop'][ $prop ] = $value;
        }
    }
}

/**
 * This function allow get property of `draven_loop` inside the loop
 * @since 1.0.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */

if(!function_exists('draven_get_theme_loop_prop')){
    function draven_get_theme_loop_prop( $prop, $default = ''){
        return isset( $GLOBALS['draven_loop'], $GLOBALS['draven_loop'][ $prop ] ) ? $GLOBALS['draven_loop'][ $prop ] : $default;
    }
}

if(!function_exists('draven_set_theme_loop_prop')){
    function draven_set_theme_loop_prop( $prop, $value = '', $force = false){
        if($force && !isset($GLOBALS['draven_loop'])){
            $GLOBALS['draven_loop'] = array();
        }
        if(isset($GLOBALS['draven_loop'])){
            $GLOBALS['draven_loop'][ $prop ] = $value;
        }
    }
}

if(!function_exists('draven_convert_legacy_responsive_column')){
    function draven_convert_legacy_responsive_column( $columns = array() ) {
        $legacy = array(
            'xlg'	=> '',
            'lg' 	=> '',
            'md' 	=> '',
            'sm' 	=> '',
            'xs' 	=> '',
            'mb' 	=> 1
        );
        $new_key = array(
            'mb'    =>  'xs',
            'xs'    =>  'sm',
            'sm'    =>  'md',
            'md'    =>  'lg',
            'lg'    =>  'xl',
            'xlg'   =>  'xxl'
        );
        if(empty($columns)){
            $columns = $legacy;
        }
        $new_columns = array();
        foreach($columns as $k => $v){
            if(isset($new_key[$k])){
                $new_columns[$new_key[$k]] = $v;
            }
        }
        if(empty($new_columns['xs'])){
            $new_columns['xs'] = 1;
        }
        return $new_columns;
    }
}

if(!function_exists('draven_render_grid_css_class_from_columns')){
    function draven_render_grid_css_class_from_columns( $columns, $merge = true ) {
        if($merge){
            $columns = draven_convert_legacy_responsive_column( $columns );
        }
        $classes = array();
        foreach($columns as $k => $v){
            if(empty($v)){
                continue;
            }
            if($k == 'xs'){
                $classes[] = 'block-grid-' . $v;
            }
            else{
                $classes[] = $k . '-block-grid-' . $v;
            }
        }
        return join(' ', $classes);
    }
}

if(!function_exists('draven_add_ajax_cart_btn_into_single_product')){
    function draven_add_ajax_cart_btn_into_single_product(){
        global $product;
        if($product->is_type('simple')){
            echo '<input type="hidden" name="add-to-cart" value="'.$product->get_id().'"/>';
        }
    }
    add_action('woocommerce_after_add_to_cart_button', 'draven_add_ajax_cart_btn_into_single_product');
}

if(!function_exists('draven_get_the_excerpt')){
    function draven_get_the_excerpt($length = null){
        ob_start();

        $length = absint($length);

        if(!empty($length)){
            add_filter('excerpt_length', function() use ($length) {
                return $length;
            }, 1012);
        }

        the_excerpt();

        if(!empty($length)) {
            remove_all_filters('excerpt_length', 1012);
        }
        $output = ob_get_clean();

        $output = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $output);

        $output = strip_tags( $output );

        $output = str_replace('&hellip;', '', $output);

        if(!empty(trim($output))){
            $output = sprintf('<p>%s</p>', $output);
        }

        return $output;
    }
}


if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
    function woocommerce_template_loop_product_title() {
        the_title( sprintf( '<h3 class="product_item--title"><a href="%s">', esc_url( get_the_permalink() ) ), '</a></h3>' );
    }
}

if(!function_exists('draven_override_woothumbnail_size_name')){
    function draven_override_woothumbnail_size_name( ) {
        return 'shop_thumbnail';
    }
    add_filter('woocommerce_gallery_thumbnail_size', 'draven_override_woothumbnail_size_name', 0);
}

if(!function_exists('draven_override_woothumbnail_size')){
    function draven_override_woothumbnail_size( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'gallery_thumbnail_image_width', 180 ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = 0;
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_gallery_thumbnail', 'draven_override_woothumbnail_size');
}

if(!function_exists('draven_override_woothumbnail_single')){
    function draven_override_woothumbnail_single( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'single_image_width', get_option( 'woocommerce_single_image_width', 600 ) ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = 0;
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_single', 'draven_override_woothumbnail_single', 0);
}

if(!function_exists('draven_override_filter_woocommerce_format_content')){
    function draven_override_filter_woocommerce_format_content( $format, $raw_string ){
        $format = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $raw_string);
        return apply_filters( 'woocommerce_short_description', $format );
    }
}

add_action('woocommerce_checkout_terms_and_conditions', 'draven_override_wc_format_content_in_terms', 1);
add_action('woocommerce_checkout_terms_and_conditions', 'draven_remove_override_wc_format_content_in_terms', 999);
if(!function_exists('draven_override_wc_format_content_in_terms')){
    function draven_override_wc_format_content_in_terms(){
        add_filter('woocommerce_format_content', 'draven_override_filter_woocommerce_format_content', 99, 2);
    }
}
if(!function_exists('draven_remove_override_wc_format_content_in_terms')){
    function draven_remove_override_wc_format_content_in_terms(){
        draven_deactive_filter('woocommerce_format_content', 'draven_override_filter_woocommerce_format_content', 99);
    }
}


if(!function_exists('draven_wc_product_loop')){
    function draven_wc_product_loop(){
        if(!function_exists('WC')){
            return false;
        }
        return have_posts() || 'products' !== woocommerce_get_loop_display_mode();
    }
}

add_filter('lastudio-elements/assets/css/default-theme-enabled', '__return_false');