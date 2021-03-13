<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * The main theme class.
 */
class Draven {

    public static $template_dir_path = '';

    public static $template_dir_url = '';

    public static $stylesheet_dir_path = '';

    public static $stylesheet_dir_url = '';

    public static $instance = null;

    public static $version = '1.0';

    private static $original_option_name = 'draven_options';

    private static $option_name = '';

    public static $lang = '';

    public static $lang_applied = false;

    private static $language_is_all = false;

    public static $is_updating  = false;

    public static $c_page_id = false;

    public static $c_context = array();

    private $options;

    private $settings;

    private $images;

    private $layout;

    private $breadcrumbs;

    private $favorite;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Shortcut method to get the settings.
     */

    public function images(){
        return $this->images;
    }

    public function breadcrumbs(){
        return $this->breadcrumbs;
    }

    public function settings() {
        return $this->settings;
    }

    public function options(){
        return $this->options;
    }

    public function layout(){
        return $this->layout;
    }

    public function favorite(){
        return $this->favorite;
    }

    /**
     * The class constructor
     */
    private function __construct() {
        // Add a non-persistent cache group.
        wp_cache_add_non_persistent_groups( 'draven' );

        // Set static vars.
        if ( '' === self::$template_dir_path ) {
            self::$template_dir_path = get_template_directory();
        }
        if ( '' === self::$template_dir_url ) {
            self::$template_dir_url = get_template_directory_uri();
        }
        if ( '' === self::$stylesheet_dir_path ) {
            self::$stylesheet_dir_path = get_stylesheet_directory();
        }
        if ( '' === self::$stylesheet_dir_url ) {
            self::$stylesheet_dir_url = get_stylesheet_directory_uri();
        }

        $this->set_is_updating();

        // Multilingual handling.
        self::multilingual_options();

        // Make sure that $option_name is set.
        if ( empty( self::$option_name ) ) {
            self::$option_name = self::get_option_name();
        }

        // Instantiate secondary classes.

        $this->settings       = new Draven_Setting();
        $this->images         = new Draven_Images();
        $this->layout         = new Draven_Layout();
        $this->options        = new Draven_Options();
        $this->favorite       = new Draven_FavoritePosts();

        new Draven_Init();
        new Draven_Blog();
        new Draven_Widget_CssClass();

        add_action( 'wp', array( $this, 'set_page_id' ) );
        add_action( 'wp', array( $this, 'set_current_context' ) );
        add_action( 'wp', array( $this, 'build_crumbs'));

        add_filter('jpeg_quality', array( $this->images, 'jpeg_quality' ), 99, 1);

        do_action( 'draven_core_loaded' );
    }

    public function build_crumbs(){
        $args = array();
        if( $this->settings()->get('google_rich_snippets', 'no') == 'yes' ){
            if(function_exists('wc') && is_object(wc()->structured_data)){
                add_action('draven/action/breadcrumbs/before_render_html', array(wc()->structured_data, 'generate_breadcrumblist_data'), 10);
            }
        }
        $this->breadcrumbs = new Draven_Breadcrumbs( $args );
    }

    public function set_is_updating() {
        if ( ! self::$is_updating && $_GET && isset( $_GET['draven_update'] ) && '1' == $_GET['draven_update'] ) {
            self::$is_updating = true;
        }
    }

    /**
     * Gets the theme version.
     *
     * @since 1.0
     *
     * @return string
     */
    public static function get_theme_version() {
        return self::$version;
    }

    /**
     * Gets the normalized theme version.
     *
     * @since 1.0
     *
     * @return string
     */
    public static function get_normalized_theme_version() {
        $theme_version = self::$version;
        $theme_version_array = explode( '.', $theme_version );

        if ( isset( $theme_version_array[2] ) && '0' === $theme_version_array[2] ) {
            $theme_version = $theme_version_array[0] . '.' . $theme_version_array[1];
        }

        return $theme_version;
    }

    /**
     * Gets the current page ID.
     *
     * @return string The current page ID.
     */
    public function get_page_id() {
        return self::$c_page_id;
    }

    /**
     * Sets the current page ID.
     *
     * @uses self::c_page_id
     */
    public function set_page_id() {
        self::$c_page_id = $this->c_page_id();
    }

    /**
     * Gets the current page ID.
     *
     * @return bool|int
     */
    private function c_page_id() {
        $object_id = get_queried_object_id();

        $c_page_id = false;

        if ( get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) && is_home() ) {
            $c_page_id = get_option( 'page_for_posts' );
        }
        else {
            // Use the $object_id if available.
            if ( isset( $object_id ) ) {
                $c_page_id = $object_id;
            }
            // If we're not on a singular post, set to false.
            if ( ! is_singular() ) {
                $c_page_id = false;
            }
            // Front page is the posts page.
            if ( isset( $object_id ) && 'posts' == get_option( 'show_on_front' ) && is_home() ) {
                $c_page_id = $object_id;
            }
            // The woocommerce shop page.
            if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
                $c_page_id = get_option( 'woocommerce_shop_page_id' );
            }
        }

        return $c_page_id;
    }

    public function get_current_context(){
        return self::$c_context;
    }

    public function set_current_context(){

        $all_context = $this->get_all_context();
        $context = $all_context['true'];
        $woo_condition = array(
            'is_cart',
            'is_checkout',
            'is_wc_endpoint_url',
            'is_account_page'
        );

        foreach($woo_condition as $item){
            if(in_array( $item, $context )){
                $context[] = 'is_woocommerce';
                break;
            }
        }

        $coreTypeAllows = array(
            'la_team_member' => array(
                'single'    => 'is_single_la_team_member',
                'archive'   => 'is_archive_la_team_member',
                'tax'       => 'is_tax_la_team_member'
            )
        );


        if(is_singular()){
            foreach($coreTypeAllows as $pt_key => $pt_opts){
                if(is_singular($pt_key)){
                    $context[] = $pt_opts['single'];
                    break;
                }
            }
        }
        elseif(is_archive()){
            if(is_post_type_archive()){
                foreach($coreTypeAllows as $pt_key => $pt_opts){
                    if(is_post_type_archive($pt_key)){
                        $context[] = $pt_opts['archive'];
                        break;
                    }
                }
            }
            elseif(is_tax()){
                foreach($coreTypeAllows as $pt_key => $pt_opts){
                    if(empty($pt_opts['taxonomy'])){
                        continue;
                    }
                    $_need_break = false;
                    $__taxonomy = $pt_opts['taxonomy'];

                    foreach($__taxonomy as $k => $v){
                        if(is_tax($k)){
                            $context[] = $v;
                            $_need_break = true;
                            break;
                        }
                    }
                    if($_need_break){
                        $context[] = $pt_opts['tax'];
                        break;
                    }
                }
            }

        }

        self::$c_context = array_unique($context);
    }

    private function get_all_context(){
        $conds =  array(
            'is_404',
            'is_admin',
            'is_archive',
            'is_attachment',
            'is_author',
            'is_blog_admin',
            'is_category',
            'is_comment_feed',
            'is_customize_preview',
            'is_date',
            'is_day',
            'is_embed',
            'is_feed',
            'is_front_page',
            'is_home',
            'is_main_network',
            'is_main_site',
            'is_month',
            'is_network_admin',
            'is_page',
            'is_page_template',
            'is_paged',
            'is_post_type_archive',
            'is_preview',
            'is_robots',
            'is_rtl',
            'is_search',
            'is_single',
            'is_singular',
            'is_ssl',
            'is_sticky',
            'is_tag',
            'is_tax',
            'is_time',
            'is_trackback',
            'is_user_admin',
            'is_year',
            'is_woocommerce',
            'is_shop',
            'is_product_taxonomy',
            'is_product_category',
            'is_product_tag',
            'is_product',
            'is_cart',
            'is_checkout',
            'is_account_page',
            'is_wc_endpoint_url'
        );
        $true = $false = $na = array();

        foreach ( $conds as $cond ) {
            if ( function_exists( $cond ) ) {
                if ( ( 'is_sticky' === $cond ) and !get_post( $id = null ) ) {
                    # Special case for is_sticky to prevent PHP notices
                    $false[] = $cond;
                }
                else if ( ! is_multisite() and in_array( $cond, array( 'is_main_network', 'is_main_site' ) ) ) {
                    # Special case for multisite conditionals to prevent them from being annoying on single site installs
                    $na[] = $cond;
                }
                else {
                    if ( call_user_func( $cond ) ) {
                        $true[] = $cond;
                    }
                    else {
                        $false[] = $cond;
                    }
                }
            }
            else {
                $na[] = $cond;
            }
        }
        return compact( 'true', 'false', 'na' );
    }

    public static function multilingual_options() {
        // Set the self::$lang.
        if ( ! in_array( Draven_Multilingual::get_active_language(), array( '', 'en', 'all' ) ) ) {
            self::$lang = '_' . Draven_Multilingual::get_active_language();
        }
        // Make sure the options are copied if needed.
        if ( ! in_array( self::$lang, array( '', 'en', 'all' ) ) && ! self::$lang_applied ) {
            // Set the $option_name property.
            self::$option_name = self::get_option_name();
            // Get the options without using a language (defaults).
            $original_options = get_option( self::$original_option_name, array() );
            // Get options with a language.
            $options = get_option( self::$original_option_name . self::$lang, array() );
            // If we're not currently performing a migration and the options are not set
            // then we must copy the default options to the new language.
            if ( ! self::$is_updating && ! empty( $original_options ) && empty( $options ) ) {
                update_option( self::$original_option_name . self::$lang, get_option( self::$original_option_name ) );
            }
            // Modify the option_name to include the language.
            self::$option_name  = self::$original_option_name . self::$lang;
            // Set $lang_applied to true. Makes sure we don't do the above more than once.
            self::$lang_applied = true;
        }
    }

    /**
     * Get the private $option_name.
     * If empty returns the original_option_name.
     *
     * @return string
     */
    public static function get_option_name() {
        if ( empty( self::$option_name ) ) {
            return self::$original_option_name;
        }
        return self::$option_name;
    }

    /**
     * Get the private $original_option_name.
     *
     * @return string
     */
    public static function get_original_option_name() {
        return self::$original_option_name;
    }

    /**
     * Change the private $option_name.
     *
     * @param  false|string $option_name The option name to use.
     */
    public static function set_option_name( $option_name = false ) {
        if ( false !== $option_name && ! empty( $option_name ) ) {
            self::$option_name = $option_name;
        }
    }

    /**
     * Change the private $language_is_all property.
     *
     * @static
     * @access public
     * @param bool $is_all Whether we're on the "all" language option or not.
     * @return null|void
     */
    public static function set_language_is_all( $is_all ) {
        if ( true === $is_all ) {
            self::$language_is_all = true;
            return;
        }
        self::$language_is_all = false;
    }

    /**
     * Get the private $language_is_all property.
     *
     * @static
     * @access public
     * @return bool
     */
    public static function get_language_is_all() {
        return self::$language_is_all;
    }
}