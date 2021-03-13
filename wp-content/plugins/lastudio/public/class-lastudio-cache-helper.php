<?php

defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    LaStudio
 * @subpackage LaStudio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    LaStudio
 * @subpackage LaStudio/public
 * @author     Your Name <email@example.com>
 */

class LaStudio_Cache_Helper {

    public static $cache_groups = array();

    public static $transient_groups = array();

    /**
     * Hook in methods.
     */
    public static function init() {
        add_action( 'delete_version_transients', array( __CLASS__, 'delete_version_transients' ) );
        add_action( 'wp', array( __CLASS__, 'prevent_caching' ) );

        add_action( 'set_object_terms', array( __CLASS__, 'set_object_terms' ), 10, 6 );
        add_action( 'transition_post_status', array( __CLASS__, 'transition_post_status' ), 10, 3 );

        add_action( 'init', array( __CLASS__, 'force_delete_transients' ), 0 );
    }

    /**
     * Get prefix for use with wp_cache_set. Allows all cache in a group to be invalidated at once.
     *
     * @param  string $group Group of cache to get.
     * @return string
     */
    public static function get_cache_prefix( $group ) {

        self::set_transient_group_name($group, 'cache');

        // Get cache key - uses cache key wc_orders_cache_prefix to invalidate when needed.
        $prefix = wp_cache_get( 'la_' . $group . '_cache_prefix', $group );

        if ( false === $prefix ) {
            $prefix = 1;
            wp_cache_set( 'la_' . $group . '_cache_prefix', $prefix, $group );
        }

        return 'la_cache_' . $prefix . '_';
    }

    /**
     * Increment group cache prefix (invalidates cache).
     *
     * @param string $group Group of cache to clear.
     */
    public static function incr_cache_prefix( $group ) {
        wp_cache_incr( 'la_' . $group . '_cache_prefix', 1, $group );
    }


    /**
     * Prevent caching on certain pages
     */
    public static function prevent_caching() {

        if ( ! is_blog_installed() ) {
            return;
        }

        if ( is_page() ) {
            global $post;
            if(has_shortcode($post->post_content,'la_wishlist') || has_shortcode($post->post_content,'la_compare')){
                self::set_nocache_constants();
                nocache_headers();
            }
        }
    }


    /**
     * Get transient version.
     *
     * When using transients with unpredictable names, e.g. those containing an md5.
     * hash in the name, we need a way to invalidate them all at once.
     *
     * When using default WP transients we're able to do this with a DB query to.
     * delete transients manually.
     *
     * With external cache however, this isn't possible. Instead, this function is used.
     * to append a unique string (based on time()) to each transient. When transients.
     * are invalidated, the transient version will increment and data will be regenerated.
     *
     * Raised in issue https://github.com/woocommerce/woocommerce/issues/5777.
     * Adapted from ideas in http://tollmanz.com/invalidation-schemes/.
     *
     * @param  string  $group   Name for the group of transients we need to invalidate.
     * @param  boolean $refresh true to force a new version.
     * @return string transient version based on time(), 10 digits.
     */
    public static function get_transient_version( $group, $refresh = false ) {

        self::set_transient_group_name($group);

        $transient_name  = $group . '-transient-version';
        $transient_value = get_transient( $transient_name );

        if ( false === $transient_value || true === $refresh ) {
            self::delete_version_transients( $transient_value );

            $transient_value = time();

            set_transient( $transient_name, $transient_value );
        }
        return $transient_value;
    }

    /**
     * When the transient version increases, this is used to remove all past transients to avoid filling the DB.
     *
     * Note; this only works on transients appended with the transient version, and when object caching is not being used.
     *
     * @since  2.3.10
     * @param string $version Version of the transient to remove.
     */
    public static function delete_version_transients( $version = '' ) {
        if ( ! wp_using_ext_object_cache() && ! empty( $version ) ) {
            global $wpdb;

            $limit    = apply_filters( 'lastudio_delete_version_transients_limit', 1000 );
            $affected = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_id LIMIT %d;", '\_transient\_%' . $version, $limit ) ); // WPCS: cache ok, db call ok.

            // If affected rows is equal to limit, there are more rows to delete. Delete in 10 secs.
            if ( $affected === $limit ) {
                wp_schedule_single_event( time() + 10, 'delete_version_transients', array( $version ) );
            }
        }
    }

    /**
     * Set constants to prevent caching by some plugins.
     *
     * @param  mixed $return Value to return. Previously hooked into a filter.
     * @return mixed
     */
    public static function set_nocache_constants( $return = true ) {
        la_maybe_define_constant( 'DONOTCACHEPAGE', true );
        la_maybe_define_constant( 'DONOTCACHEOBJECT', true );
        la_maybe_define_constant( 'DONOTCACHEDB', true );
        return $return;
    }

    public static function set_transient_group_name( $group_name, $type = 'transient' ){
        $options = get_option('la_manager_transient_group_name', array());
        $group_type = !empty($options[$type]) ? $options[$type] : array();
        if(empty($group_type) || ( !empty($group_type) && !in_array($group_name, $group_type))){
            $group_type[] = $group_name;
            $options[$type] = $group_type;
            update_option('la_manager_transient_group_name', $options);
        }
    }

    /**
     * @param string $type : all, transient, cache
     */
    public static function get_transient_group_name($type = 'all'){
        $options = get_option('la_manager_transient_group_name', array());
        if( $type == 'all' ){
            return $options;
        }
        elseif( isset($options[$type]) ) {
            return $options[$type];
        }
        else{
            return array();
        }
    }

    /**
     * When a post status changes.
     *
     * @param string $new_status
     * @param string $old_status
     * @param object $post
     */
    public static function transition_post_status( $new_status, $old_status, $post ) {
        if ( 'publish' === $new_status || 'publish' === $old_status ) {
            self::delete_shortcode_query_transients($post->post_type);
        }
    }
    /**
     * Delete transients when terms are set.
     *
     * @param int $object_id
     * @param mixed $terms
     * @param array $tt_ids
     * @param string $taxonomy
     * @param mixed $append
     * @param array $old_tt_ids
     */
    public static function set_object_terms( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ) {
        $post_type = get_post_type( $object_id );
        self::delete_shortcode_query_transients( $post_type );
    }

    /**
     * @param string $group_name: shortcode_query, post-type-name
     */
    public static function delete_shortcode_query_transients( $group_name = 'shortcode_query' ){

        $posttype_allow = array(
            'post',
            'la_portfolio',
            'la_block',
            'la_team_member',
            'la_testimonial',
            'lpm_playlist',
            'la_playlist',
            'la_video',
            'ld_release',
            'la_release'
        );

        $posttype_allow[] = 'shortcode_query';

        if(!empty($group_name) && in_array($group_name, $posttype_allow)){
            self::get_transient_version($group_name, true);
        }
    }

    public static function force_delete_transients(){
        if(is_admin() && !empty($_GET['la_force_delete_transients'])){
            $transients = self::get_transient_group_name('transient');
            if(!empty($transients)){
                foreach($transients as $group_name){
                    self::get_transient_version($group_name, true);
                }
                update_option('la_manager_transient_group_name', array());
            }
        }
    }
}