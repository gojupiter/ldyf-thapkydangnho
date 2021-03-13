<?php

add_filter( 'LAHFB/get_dynamic_styles', 'draven_override_builder_dynamic_style' );
function draven_override_builder_dynamic_style( $styles ){
    if(!isset($_GET['lastudio_header_builder'])){
        $header_layout = Draven()->layout()->get_header_layout();
        if(!empty($header_layout) && $header_layout != 'inherit' && LAHFB_Helper::is_prebuild_header_exists($header_layout)){
            $styles = LAHFB_Helper::get_dynamic_styles($header_layout);
        }
    }
    return $styles;
}

if(!function_exists('draven_override_yikes_mailchimp_page_data')){
    function draven_override_yikes_mailchimp_page_data($page_data, $form_id){
        $new_data = new stdClass();
        if(isset($page_data->ID)){
            $new_data->ID = $page_data->ID;
        }
        return $new_data;
    }
    add_filter('yikes-mailchimp-page-data', 'draven_override_yikes_mailchimp_page_data', 10, 2);
}

if(!function_exists('draven_override_theme_default')){
    function draven_override_theme_default(){
        $header_layout = Draven()->layout()->get_header_layout();
        $title_layout = Draven()->layout()->get_page_title_bar_layout();
        if($header_layout == 'default' && (empty($title_layout) || $title_layout == 'hide') && !is_404()) :
            ?>
            <div class="page-title-section">
                <?php
                echo Draven()->breadcrumbs()->get_title();
                do_action('draven/action/breadcrumbs/render_html');
                ?>
            </div>
            <?php
        endif;
    }
    add_action('draven/action/before_render_main_inner', 'draven_override_theme_default');
}

if(!function_exists('draven_override_dokan_main_query')){
    function draven_override_dokan_main_query( $query ) {
        if(function_exists('dokan_is_store_page') && dokan_is_store_page() && isset($query->query['term_section'])){
            $query->set('posts_per_page', 0);
            WC()->query->product_query( $query );
        }
    }
    add_action('pre_get_posts', 'draven_override_dokan_main_query', 11);
}


if(!function_exists('draven_dokan_dashboard_wrap_before')){
    function draven_dokan_dashboard_wrap_before(){
        echo '<div id="main" class="site-main"><div class="container"><div class="row"><main id="site-content" class="col-md-12 col-xs-12 site-content">';
    }
    add_filter('dokan_dashboard_wrap_before', 'draven_dokan_dashboard_wrap_before');
}

if(!function_exists('draven_dokan_dashboard_wrap_after')){
    function draven_dokan_dashboard_wrap_after(){
        echo '</main></div></div></div>';
    }
    add_filter('dokan_dashboard_wrap_after', 'draven_dokan_dashboard_wrap_after');
}


/**
 * @desc: adding the custom badges to product
 * @since: 1.0.3
 */

if(!function_exists('draven_add_custom_badge_for_product')){
    function draven_add_custom_badge_for_product(){
        global $product;
        $product_badges = Draven()->settings()->get_post_meta( $product->get_id(), 'product_badges' );
        if(empty($product_badges)){
            return;
        }
        $_tmp_badges = array();
        foreach($product_badges as $badge){
            if(!empty($badge['text'])){
                $_tmp_badges[] = $badge;
            }
        }
        if(empty($_tmp_badges)){
            return;
        }
        foreach($_tmp_badges as $i => $badge){
            $attribute = array();
            if(!empty($badge['bg'])){
                $attribute[] = 'background-color:' . esc_attr($badge['bg']);
            }
            if(!empty($badge['color'])){
                $attribute[] = 'color:' . esc_attr($badge['color']);
            }
            $el_class = ($i%2==0) ? 'odd' : 'even';
            if(!empty($badge['el_class'])){
                $el_class .= ' ';
                $el_class .= $badge['el_class'];
            }

            echo sprintf(
                '<span class="la-custom-badge %1$s" style="%3$s"><span>%2$s</span></span>',
                esc_attr($el_class),
                esc_html($badge['text']),
                (!empty($attribute) ? esc_attr(implode(';', $attribute)) : '')
            );
        }
    }
    add_action( 'woocommerce_before_shop_loop_item_title', 'draven_add_custom_badge_for_product', 9 );
    add_action( 'woocommerce_before_single_product_summary', 'draven_add_custom_badge_for_product', 9 );
}

/**
 * @desc: kick-off the function when theme has new version
 * @since: 1.0.0
 */
if(!function_exists('draven_hook_update_the_theme')){
    function draven_hook_update_the_theme(){
        $current_version = get_option('draven_opts_db_version', false);
        if( class_exists('LaStudio_Cache_Helper') && version_compare( '1.0.0', $current_version) > 0 ) {
            LaStudio_Cache_Helper::get_transient_version('icon_library', true);
            $current_version = '1.0.0';
            update_option('draven_opts_db_version', $current_version);
        }
    }
    add_action( 'after_setup_theme', 'draven_hook_update_the_theme', 0 );
}

/*
 * @desc: custom block after add-to-cart on single product page
 * @since: 1.0.0
 */
if(!function_exists('draven_custom_block_after_add_cart_form_on_single_product')){
    function draven_custom_block_after_add_cart_form_on_single_product(){
        if(is_active_sidebar('s-p-after-add-cart')){
            echo '<div class="extradiv-after-frm-cart">';
            dynamic_sidebar('s-p-after-add-cart');
            echo '</div>';
            echo '<div class="clearfix"></div>';
        }
    }
    add_action('woocommerce_single_product_summary', 'draven_custom_block_after_add_cart_form_on_single_product', 51);
}

if(!function_exists('draven_override_portfolio_content_type_args')){
    function draven_override_portfolio_content_type_args( $args, $post_type_name ) {
        if($post_type_name == 'la_portfolio'){
            $label = esc_html(Draven()->settings()->get('portfolio_custom_name'));
            $label2 = esc_html(Draven()->settings()->get('portfolio_custom_name2'));
            $slug = sanitize_title(Draven()->settings()->get('portfolio_custom_slug'));
            if(!empty($label)){
                $args['label'] = $label;
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }

        return $args;
    }
    add_filter('register_post_type_args', 'draven_override_portfolio_content_type_args', 99, 2);
}

if(!function_exists('draven_override_portfolio_tax_type_args')){
    function draven_override_portfolio_tax_type_args( $args, $tax_name ) {

        if( $tax_name == 'la_portfolio_category' ) {
            $label = esc_html(Draven()->settings()->get('portfolio_cat_custom_name'));
            $label2 = esc_html(Draven()->settings()->get('portfolio_cat_custom_name2'));
            $slug = sanitize_title(Draven()->settings()->get('portfolio_cat_custom_slug'));
            if(!empty($label)){
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }
        else if( $tax_name == 'la_portfolio_skill' ) {
            $label = esc_html(Draven()->settings()->get('portfolio_skill_custom_name'));
            $label2 = esc_html(Draven()->settings()->get('portfolio_skill_custom_name2'));
            $slug = sanitize_title(Draven()->settings()->get('portfolio_skill_custom_slug'));
            if(!empty($label)){
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }

        return $args;
    }
    add_filter('register_taxonomy_args', 'draven_override_portfolio_tax_type_args', 99, 2);
}



/*
 * @desc: Ensure that a specific theme is never updated. This works by removing the theme from the list of available updates.
 * @since: 1.0.1
 */

add_filter('http_request_args', 'draven_hidden_theme_update_from_repository', 10, 2);
if(!function_exists('draven_hidden_theme_update_from_repository')){
    function draven_hidden_theme_update_from_repository( $response, $url ){
        $pos = strpos($url, '//api.wordpress.org/themes/update-check');
        if($pos === 5 || $pos === 6){
            $themes = json_decode( $response['body']['themes'], true );
            if(isset($themes['themes']['draven'])){
                unset($themes['themes']['draven']);
            }
            if(isset($themes['themes']['draven-child'])){
                unset($themes['themes']['draven-child']);
            }
            $response['body']['themes'] = json_encode( $themes );
        }
        return $response;
    }
}


add_shortcode('la_wishlist', function( $atts, $content ){
    ob_start();
    if(function_exists('wc_print_notices')){
        get_template_part('templates/la_wishlist');
    }
    return ob_get_clean();
});
add_shortcode('la_compare', function( $atts, $content ){
    ob_start();
    if(function_exists('wc_print_notices')){
        get_template_part('templates/la_compare');
    }
    return ob_get_clean();
});


/**
 * Add Instagram API
 * @since 1.0.8
 */

add_filter('lastudio-elements/instagram/access_token', 'draven_add_instagram_api_access_token');
if(!function_exists('draven_add_instagram_api_access_token')){
    function draven_add_instagram_api_access_token( $key = '' ){
        return Draven()->settings()->get('instagram_token', $key);
    }
}
/**
 * Add support for Elementor Pro locations
 * @since 1.0.8
 */
add_action( 'elementor/theme/register_locations', 'draven_register_elementor_locations' );
if(!function_exists('draven_register_elementor_locations')){
    function draven_register_elementor_locations( $elementor_theme_manager ){
        $elementor_theme_manager->register_all_core_location();
    }
}
/**
 * Add related posts via hook
 * @since 1.0.8
 */
add_action('draven/action/after_render_main_container', 'draven_add_related_posts_into_single_blog');
if(!function_exists('draven_add_related_posts_into_single_blog')){
    function draven_add_related_posts_into_single_blog(){
        if(!is_singular('post')){
            return;
        }

        $enable_related = Draven()->settings()->get('blog_related_posts', 'off');
        $related_style = Draven()->settings()->get('blog_related_design', 1);
        $max_related = (int) Draven()->settings()->get('blog_related_max_post', 1);
        $related_by = Draven()->settings()->get('blog_related_by', 'category');

        if($enable_related == 'on') {
            wp_reset_postdata();
            $related_args = array(
                'posts_per_page' => $max_related,
                'post__not_in' => array(get_the_ID())
            );
            if ($related_by == 'random') {
                $related_args['orderby'] = 'rand';
            }
            if ($related_by == 'category') {
                $cats = wp_get_post_terms(get_the_ID(), 'category');
                if (is_array($cats) && isset($cats[0]) && is_object($cats[0])) {
                    $related_args['category__in'] = array($cats[0]->term_id);
                }
            }
            if ($related_by == 'tag') {
                $tags = wp_get_post_terms(get_the_ID(), 'tag');
                if (is_array($tags) && isset($tags[0]) && is_object($tags[0])) {
                    $related_args['tag__in'] = array($tags[0]->term_id);
                }
            }
            if ($related_by == 'both') {
                $cats = wp_get_post_terms(get_the_ID(), 'category');
                if (is_array($cats) && isset($cats[0]) && is_object($cats[0])) {
                    $related_args['category__in'] = array($cats[0]->term_id);
                }
                $tags = wp_get_post_terms(get_the_ID(), 'tag');
                if (is_array($tags) && isset($tags[0]) && is_object($tags[0])) {
                    $related_args['tag__in'] = array($tags[0]->term_id);
                }
            }

            $related_query = new WP_Query($related_args);

            if ($related_query->have_posts()) { ?>
                <div class="row-related-posts related-posts-design-<?php echo esc_attr($related_style); ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="la-related-posts">
                                    <div class="row block_heading">
                                        <div class="col-xs-12">
                                            <h2 class="block_heading--title"><span><?php echo esc_html_x('Related Post', 'front-view', 'draven'); ?></span></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="la-related-posts">
                                    <?php

                                    draven_set_theme_loop_prop('loop_name', 'related_posts', true);
                                    draven_set_theme_loop_prop('loop_layout', 'grid');
                                    draven_set_theme_loop_prop('loop_style', $related_style);
                                    draven_set_theme_loop_prop('excerpt_length', 18);
                                    draven_set_theme_loop_prop('title_tag', 'h3');
                                    draven_set_theme_loop_prop('height_mode', '16-9');
                                    draven_set_theme_loop_prop('item_space', 'default');

                                    $responsive_column = array(
                                        'xlg' => 2,
                                        'lg' => 2,
                                        'md' => 2,
                                        'sm' => 2,
                                        'xs' => 2,
                                        'mb' => 1
                                    );

                                    $slidesToShow = array(
                                        'desktop'           => 2,
                                        'laptop'            => 2,
                                        'tablet'            => 2,
                                        'tablet_portrait'   => 2,
                                        'mobile'            => 2,
                                        'mobile_portrait'   => 1
                                    );

                                    $options  = array(
                                        'slidesToShow'   => $slidesToShow,
                                        'prevArrow'=> '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                                        'nextArrow'=> '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                                        'rtl' => is_rtl()
                                    );

                                    $slider_configs = 'data-slider_config="'. esc_attr( json_encode($options) ) .'"';

                                    draven_set_theme_loop_prop('slider_configs', $slider_configs);
                                    draven_set_theme_loop_prop('responsive_column', $responsive_column);
                                    draven_set_theme_loop_prop('image_size', 'post-thumbnail');
                                    draven_set_theme_loop_prop('is_main_loop', false);

                                    get_template_part('templates/posts/start', 'related');

                                    while ($related_query->have_posts()) {
                                        $related_query->the_post();
                                        get_template_part('templates/posts/loop');
                                    }

                                    get_template_part('templates/posts/end');

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }

            wp_reset_postdata();
        }

    }
}

/**
 * Add comments to page via hook
 * @since 1.0.8
 */

add_action('draven/action/after_render_main_inner', 'draven_add_comments_section_into_page');
if(!function_exists('draven_add_comments_section_into_page')){
    function draven_add_comments_section_into_page(){
        if(!is_singular('page')){
            return;
        }
        wp_reset_postdata();
        if ( comments_open() || get_comments_number() ) :
            echo '<div class="clearfix"></div><div class="single-post-detail padding-top-30">';
            comments_template();
            echo '</div>';
        endif;
    }
}

if(!function_exists('draven_wc_add_qty_control_plus')){
    function draven_wc_add_qty_control_plus(){
        echo '<span class="qty-plus">+</span>';
    }
}

if(!function_exists('draven_wc_add_qty_control_minus')){
    function draven_wc_add_qty_control_minus(){
        echo '<span class="qty-minus">-</span>';
    }
}

add_action('woocommerce_after_quantity_input_field', 'draven_wc_add_qty_control_plus');
add_action('woocommerce_before_quantity_input_field', 'draven_wc_add_qty_control_minus');

add_filter('lastudio/fix_stretched_section', '__return_false');
add_action('elementor/frontend/after_enqueue_scripts', function (){
    $stretched_section_container = '#page > .site-inner';
    wp_add_inline_script( 'elementor-frontend', 'try{elementorFrontendConfig.kit.stretched_section_container="'.$stretched_section_container.'";}catch(e){}', 'before' );
});