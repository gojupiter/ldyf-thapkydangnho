<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * The Autoloader class for Draven.
 */
class Draven_Autoload {

    /**
     * The transient name.
     *
     * @static
     * @access private
     * @since 1.0
     * @var string
     */
    private static $transient_name = '';

    /**
     * Stored paths.
     *
     * @static
     * @access private
     * @since 1.0
     * @var array
     */
    private static $cached_paths = array();

    /**
     * Whether the cache needs updating or not.
     *
     * @static
     * @access private
     * @since 1.0
     * @var bool
     */
    private static $update_cache = false;

    /**
     * The class constructor.
     *
     * @access public
     */
    public function __construct( $args = array() ) {

        // Set the transient name.
        if ( empty( self::$transient_name ) ) {
            self::$transient_name = 'draven_autoloader_paths_' . md5( __FILE__ );
        }

        // Get the cached paths array.
        $this->get_cached_paths();

        // Register our autoloader.
        spl_autoload_register( array( $this, 'include_class_file' ) );

        // Update caches.
        add_action( 'shutdown', array( $this, 'update_cached_paths' ) );

        // Make sure caches are reset when needed.

        add_action( 'after_switch_theme', array( $this, 'reset_cached_paths' ) );
        add_action( 'switch_theme', array( $this, 'reset_cached_paths' ) );

    }

    /**
     * Gets the cached paths.
     *
     * @access protected
     * @since 1.0
     * @return void
     */
    protected function get_cached_paths() {

        self::$cached_paths = get_site_transient( self::$transient_name );

    }

    /**
     * Gets the path for a specific class-name.
     *
     * @access protected
     * @since 1.0
     * @param string $class_name The class-name we're looking for.
     * @return false|string      The full path to the class, or false if not found.
     */
    protected function get_path( $class_name ) {

        $paths = array();
        if ( 0 === stripos( $class_name, 'Draven' ) ) {
            $path     = wp_normalize_path( Draven::$template_dir_path . '/framework/classes/' );

            $class_name = str_replace( array( 'Draven_' ), '', $class_name );

            $filename = 'class-' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';

            $paths[] = $path . $filename;

            $exploded = explode( '_', $class_name );
            $levels   = count( $exploded );

            $previous_path = '';
            for ( $i = 0; $i < $levels; $i++ ) {
                $paths[] = $path . $previous_path . strtolower( $exploded[ $i ] ) . '/' . $filename;
                $previous_path .= strtolower( $exploded[ $i ] ) . '/';
            }

            foreach ( $paths as $path ) {
                $path = wp_normalize_path( $path );
                if ( file_exists( $path ) ) {
                    return $path;
                }
            }
        }
        return false;

    }

    /**
     * Get the path & include the file for the class.
     *
     * @access public
     * @since 1.0
     * @param string $class_name The class-name we're looking for.
     * @return void
     */
    public function include_class_file( $class_name ) {

        // If the path is cached, use it & early exit.
        if ( isset( self::$cached_paths[ $class_name ] ) ) {
            include_once self::$cached_paths[ $class_name ];
            return;
        }

        // If we got this far, the path is not cached.
        // We'll need to get it, and add it to the cache.
        $path = $this->get_path( $class_name );

        // If the path was not found, early exit.
        if ( false === $path ) {
            return;
        }

        // Include the path.
        include_once $path;

        // Add path to the array of paths to cache.
        self::$cached_paths[ $class_name ] = $path;
        // Make sure we update the caches.
        self::$update_cache = true;

    }

    /**
     * Update caches if needed.
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function update_cached_paths() {

        // If we don't need to update the caches, early exit.
        if ( false === self::$update_cache ) {
            return;
        }

        // Cache for one hour using transients.
        set_site_transient( self::$transient_name, self::$cached_paths, HOUR_IN_SECONDS );

    }

    /**
     * Reset caches.
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function reset_cached_paths() {

        delete_site_transient( self::$transient_name );

    }
}