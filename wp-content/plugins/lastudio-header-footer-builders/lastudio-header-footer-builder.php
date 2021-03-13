<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://la-studioweb.com/
 * @since             1.0.0
 * @package           LaStudio_Header_Footer_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       LA-Studio Header Builder
 * Plugin URI:        https://la-studioweb.com/
 * Description:       This plugin use only for LA-Studio theme
 * Version:           1.0.3.1
 * Author:            LA-Studio
 * Author URI:        https://la-studioweb.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       labuilder
 * Domain Path:       /languages
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LaStudio_Header_Footer_Builder' ) ) :
    class LaStudio_Header_Footer_Builder {

        /**
		 * Instance of this class.
         *
		 * @since   1.0.0
		 * @access  private
		 * @var     LaStudio_Header_Footer_Builder
		 */
		private static $instance;

		/**
		 * The modules variable holds all modules of the plugin.
		 *
		 * @since	1.0.0
		 * @access	private
		 * @var		object
		 */
		private static $modules = array();

        /**
		 * Main path.
		 *
		 * @since   1.0.0
		 * @access  private
		 * @var     string
		 */
		private static $path;

		/**
		 * Absolute url.
		 *
		 * @since   1.0.0
		 * @access  private
		 * @var     string
		 */
		private static $url;

		/**
		 * The current version of the LaStudio Header Footer Builder.
		 *
		 * @since    1.0.0
		 */
		const VERSION		= '1.0.3.1';

		/**
		 * The LaStudio Header Footer Builder prefix to reference classes inside it.
		 *
		 * @since	1.0.0
		 */
		const CLASS_PREFIX	= 'LAHFB_';

		/**
		 * The LaStudio Header Footer Builder prefix to reference files and prefixes inside it.
		 *
		 * @since	1.0.0
		 */
		const FILE_PREFIX	= 'lahfb-';

        /**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return	object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
            }

			return self::$instance;
		}

        /**
		 * Define the core functionality of the LaStudio Header Footer Builder.
		 *
		 * Load the dependencies.
		 *
		 * @since	1.0.0
		 */
		public function __construct() {
			self::$path	= plugin_dir_path( __FILE__ );
			self::$url	= plugin_dir_url( __FILE__ );


            //add_action('init', array( $this, 'register_my_session' ), 1);

            require_once( self::$path . 'includes/functions/functions.php' );
			require_once( self::$path . 'includes/class-loader.php' );

			self::$modules['LAHFB_Loader']			= LAHFB_Loader::get_instance();
			self::$modules['LAHFB_Helper']			= LAHFB_Helper::get_instance();
			// LAHFB_Helper::clearHeaderData();
			LAHFB_Helper::setHeaderDefaultData();
			self::$modules['LAHFB_Enqueue']			= LAHFB_Enqueue::get_instance();
			self::$modules['LAHFB_Ajax']			= LAHFB_Ajax::get_instance();
			self::$modules['LAHFB_Field']			= LAHFB_Field::get_instance();
			self::$modules['LAHFB_Element']			= LAHFB_Element::get_instance();
			self::$modules['LAHFB_Output']			= LAHFB_Output::get_instance();
			self::$modules['LAHFB_Frontend_Builder']= LAHFB_Frontend_Builder::get_instance();
		}

		/**
		 * Get the LaStudio Header Footer Builder absolute path.
		 *
		 * @since	1.0.0
		 */
		public static function get_path() {
			return self::$path;
		}

		/**
		 * Get the LaStudio Header Footer Builder absolute url.
		 *
		 * @since	1.0.0
		 */
		public static function get_url() {
			return self::$url;
		}

		public function register_my_session(){
            if( !session_id() ) {
                session_start();
            }
        }
    }

	// Create a simple alias
	class_alias( 'LaStudio_Header_Footer_Builder', 'LAHFB' );

endif;

// Run LaStudio Header Footer Builder
add_action('plugins_loaded', array('LAHFB', 'get_instance'));