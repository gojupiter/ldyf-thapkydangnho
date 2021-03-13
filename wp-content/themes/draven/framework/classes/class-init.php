<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Draven_Init{

    public function __construct(){
        add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
        add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ) );
        add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ) );
        add_action( 'after_setup_theme', array( $this, 'set_default_options' ) );
        add_action( 'widgets_init', array( $this, 'register_sidebar' ) );
    }

    public function load_textdomain(){
        load_theme_textdomain( 'draven', Draven::$template_dir_path . '/languages' );
        load_child_theme_textdomain( 'draven', Draven::$stylesheet_dir_path . '/languages' );
    }

    public function add_theme_supports(){
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'custom-header' );
        add_theme_support( 'custom-background' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'post-formats', array(
            'quote',
            'image',
            'video',
            'link',
            'audio',
            'gallery'
        ) );

        add_theme_support( 'woocommerce' );

        if(Draven()->settings()->get('woocommerce_gallery_zoom') == 'yes'){
            add_theme_support( 'wc-product-gallery-zoom');
        }
        if(Draven()->settings()->get('woocommerce_gallery_lightbox') == 'yes'){
            add_theme_support( 'wc-product-gallery-lightbox');
        }

        add_theme_support( 'editor-styles' );
        add_editor_style('editor-style.css');
        add_theme_support( 'align-wide' );
        add_theme_support( 'editor-color-palette', array(
            array(
                'name' => esc_attr_x( 'theme primary', 'admin-view', 'draven' ),
                'slug' => 'draven-theme-primary',
                'color' => '#F53E6A',
            ),

            array(
                'name' => esc_attr_x( 'theme secondary', 'admin-view', 'draven' ),
                'slug' => 'draven-theme-secondary',
                'color' => '#2F2F2F',
            ),

            array(
                'name' => esc_attr_x( 'strong magenta', 'admin-view', 'draven' ),
                'slug' => 'strong-magenta',
                'color' => '#a156b4',
            ),
            array(
                'name' => esc_attr_x( 'light grayish magenta', 'admin-view', 'draven' ),
                'slug' => 'light-grayish-magenta',
                'color' => '#d0a5db',
            ),
            array(
                'name' => esc_attr_x( 'very light gray', 'admin-view',  'draven' ),
                'slug' => 'very-light-gray',
                'color' => '#eee',
            ),
            array(
                'name' => esc_attr_x( 'very dark gray', 'admin-view', 'draven' ),
                'slug' => 'very-dark-gray',
                'color' => '#444',
            ),
        ) );
    }

    public function register_nav_menus(){
        register_nav_menus( array(
            'main-nav'   => esc_attr_x( 'Main Navigation', 'admin-view', 'draven' )
        ) );
    }

    public function set_default_options(){
        $check_theme = get_option('draven_has_init', false);
        if(!$check_theme || !get_option(Draven()->get_option_name())){
            update_option(
                Draven()->get_option_name(),
                json_decode('{"layout":"col-1c","body_boxed":"no","body_max_width":"1230","main_full_width":"no","google_rich_snippets":"no","backtotop_btn":"no","text_color":"#535353","heading_color":"#2F2F2F","primary_color":"#F53E6A","secondary_color":"#2F2F2F","three_color":"#A3A3A3","border_color":"#CDCDCD","page_loading_animation":"on","page_loading_style":"3","page_loading_custom":"","body_font_size":"14px","font_source":"1","main_font":{"family":"Poppins","variant":["300","300italic","regular","italic","600","600italic","700","700italic"],"font":"google"},"secondary_font":{"family":"Poppins","variant":["regular","italic","700","700italic"],"font":"google"},"highlight_font":{"family":"Playfair Display","variant":["regular","italic"],"font":"google"},"font_google_code":"","main_google_font_face":"","secondary_google_font_face":"","highlight_google_font_face":"","font_typekit_kit_id":"","main_typekit_font_face":"","secondary_typekit_font_face":"","highlight_typekit_font_face":"","heading_custom_font":"no","header_layout":"default","header_full_width":"no","header_transparency":"no","header_show_cart":"no","header_show_search":"yes","header_show_wishlist":"no","header_show_menu_account":"no","header_show_menu_hamburger":"no","enable_header_top":"no","header_background":{"image":"","repeat":"repeat","position":"left top","attachment":"","size":"","color":"#fff"},"page_title_bar_layout":"1","page_title_bar_heading_color":"#2F2F2F","page_title_bar_text_color":"#535353","page_title_bar_link_color":"#535353","page_title_bar_link_hover_color":"#F53E6A","page_title_bar_spacing":{"top":"35","bottom":"35"},"page_title_bar_spacing_tablet":{"top":"25","bottom":"25"},"page_title_bar_spacing_mobile":{"top":"25","bottom":"25"},"woo_override_page_title_bar":"off","woo_page_title_bar_layout":"hide","pages_sidebar":"","posts_sidebar":"","blog_archive_sidebar":"","search_sidebar":"","products_sidebar":"","shop_sidebar":"","footer_layout":"inherit","layout_blog":"col-2cr","blog_small_layout":"off","page_title_bar_layout_blog_global":"on","blog_design":"grid_3","blog_post_column":{"xlg":"1","lg":"1","md":"1","sm":"1","xs":"1","mb":"1"},"featured_images_blog":"on","blog_thumbnail_size":"870x490","format_content_blog":"off","blog_content_display":"excerpt","blog_excerpt_length":"50","blog_masonry":"off","blog_pagination_type":"pagination","layout_single_post":"col-1c","single_small_layout":"off","page_title_bar_layout_post_single_global":"off","featured_images_single":"on","blog_pn_nav":"off","blog_post_title":"off","blog_author_info":"off","blog_social_sharing_box":"off","blog_related_posts":"off","blog_related_design":"1","blog_related_by":"random","blog_related_max_post":"2","blog_comments":"on","layout_archive_product":"col-1c","catalog_mode":"off","catalog_mode_price":"off","active_shop_filter":"on","hide_shop_toolbar":"off","shop_catalog_display_type":"grid","shop_catalog_grid_style":"1","active_shop_masonry":"off","shop_masonry_column_type":"default","product_masonry_image_size":"shop_catalog","product_masonry_item_width":"270","product_masonry_item_height":"390","woocommerce_shop_page_columns":{"xlg":"4","lg":"4","md":"3","sm":"2","xs":"1","mb":"1"},"woocommerce_shop_masonry_columns":{"xlg":"4","lg":"4","md":"3","sm":"2","xs":"1","mb":"1"},"woocommerce_shop_masonry_custom_columns":{"md":"3","sm":"2","xs":"1","mb":"1"},"shop_masonry_item_gap":"30","enable_shop_masonry_custom_setting":"off","shop_masonry_item_setting":[{"size_name":"1x Width + 1x Height","width":"1","height":"1"}],"product_per_page_allow":"12,15,30","product_per_page_default":"12","woocommerce_enable_crossfade_effect":"on","woocommerce_toggle_grid_list":"on","woocommerce_show_rating_on_catalog":"on","woocommerce_show_quickview_btn":"on","woocommerce_show_wishlist_btn":"off","woocommerce_show_compare_btn":"off","layout_single_product":"col-1c","woocommerce_product_page_design":"2","product_sharing":"off","related_products":"on","related_product_title":"Related Products","related_product_subtitle":"","related_products_columns":{"xlg":"4","lg":"4","md":"3","sm":"2","xs":"2","mb":"1"},"upsell_products":"on","upsell_product_title":"Up-Sells Products","upsell_product_subtitle":"","upsell_products_columns":{"xlg":"4","lg":"4","md":"3","sm":"2","xs":"2","mb":"1"},"crosssell_products":"on","crosssell_product_title":"Related Products","crosssell_product_subtitle":"","crosssell_products_columns":{"xlg":"4","lg":"4","md":"3","sm":"2","xs":"1","mb":"1"},"social_links":{"1":{"title":"Facebook","icon":"fa fa-facebook","link":"#"},"2":{"title":"Twitter","icon":"fa fa-twitter","link":"#"},"3":{"title":"Pinterest","icon":"fa fa-pinterest-p","link":"#"}},"sharing_facebook":true,"sharing_twitter":true,"sharing_google_plus":true,"sharing_pinterest":true,"google_key":"","instagram_token":"","newsletter_popup_delay":"2000","popup_dont_show_text":"Do not show popup anymore","newsletter_popup_show_again":"1","newsletter_popup_content":"","enable_maintenance":"no","pages_global_sidebar":false,"posts_global_sidebar":false,"blog_archive_global_sidebar":false,"products_global_sidebar":false,"shop_global_sidebar":false,"sharing_reddit":false,"sharing_linkedin":false,"sharing_tumblr":false,"sharing_vk":false,"sharing_email":false,"enable_newsletter_popup":false,"only_show_newsletter_popup_on_home_page":false,"disable_popup_on_mobile":false,"show_checkbox_hide_newsletter_popup":false,"header_mb_layout":"2","header_mb_component_1":{"1":{"type":"search_1"},"2":{"type":"primary_menu"}},"footer_space":{"padding_top":"50px","padding_bottom":"10px"},"single_post_thumbnail_size":"full","footer_copyright":"2019 Created by LaStudio"}',true)
            );
            update_option('lastudio_header_layout', 'default');
            update_option('draven_has_init', true);
            update_option('la_extension_available', array(
                'swatches' => true,
                '360' => true,
                'content_type' => true
            ));
            update_option( 'elementor_cpt_support', array( 'page', 'post') );
            update_option( 'elementor_disable_color_schemes', 'yes' );
            update_option( 'elementor_disable_typography_schemes', 'yes' );
            update_option( 'elementor_stretched_section_container', '#page > .site-inner' );
            update_option( 'elementor_page_title_selector', '#section_page_header' );
            update_option( 'elementor_editor_break_lines', 1 );
            update_option( 'elementor_edit_buttons', 'on' );
            update_option( 'elementor_load_fa4_shim', 'yes' );
        }
    }

    public function register_sidebar(){

        register_sidebar(array(
            'name'          => esc_attr_x( 'Sidebar Widget Area', 'admin-view', 'draven' ),
            'id'            => 'sidebar-primary',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>'
        ));
        register_sidebar(array(
            'name'          => esc_attr_x( 'Sidebar Shop Filter', 'admin-view', 'draven' ),
            'id'            => 'sidebar-shop-filter',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>'
        ));

        register_sidebar(array(
            'name'          => esc_attr_x( 'Custom Block Top', 'admin-view', 'draven' ),
            'id'            => 'la-custom-block-top',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>'
        ));
        register_sidebar(array(
            'name'          => esc_attr_x( 'Custom Block Inner Top', 'admin-view', 'draven' ),
            'id'            => 'la-custom-block-inner-top',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>'
        ));
        register_sidebar(array(
            'name'          => esc_attr_x( 'Custom Block Inner Bottom', 'admin-view', 'draven' ),
            'id'            => 'la-custom-block-inner-bottom',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>'
        ));
        register_sidebar(array(
            'name'          => esc_attr_x( 'Custom Block Bottom', 'admin-view', 'draven' ),
            'id'            => 'la-custom-block-bottom',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>'
        ));

        if(function_exists('dokan')){
            register_sidebar(
                array(
                    'name'          => __( 'Dokan Store Sidebar', 'draven' ),
                    'id'            => 'sidebar-store',
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h3 class="widget-title">',
                    'after_title'   => '</h3>',
                )
            );
        }
        register_sidebar(array(
            'name'          => esc_attr_x( 'Custom Block After Add To Cart', 'admin-view', 'draven' ),
            'description'   => esc_attr_x( 'Display custom block on single product page', 'admin-view', 'draven' ),
            'id'            => 's-p-after-add-cart',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>'
        ));

    }
}