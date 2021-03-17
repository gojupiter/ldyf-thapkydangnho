<?php
/**
 * Header Builder - Element Class.
 *
 * @author	LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHFB_Element' ) ) :

    class LAHFB_Element {

		/**
		 * Instance of this class.
         *
		 * @since	1.0.0
		 * @access	private
		 * @var		LAHFB_Element
		 */
		private static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since	1.0.0
		 * @return	object
		 */
		public static function get_instance() {

			if ( self::$instance === null ) {
				self::$instance = new self();
            }

			return self::$instance;

		}

		/**
		 * Constructor.
		 *
		 * @since	1.0.0
		 */
		public function __construct() {

			// Load frontend output
            $this->load_frontend();

		}

		/**
		 * Load frontend.
		 *
		 * @since	1.0.0
		 */
		public function load_frontend() {

			foreach ( glob( LAHFB_Helper::get_file( 'includes/elements/components/frontend/*.php' ) ) as $file ) {
				include_once $file;
            }

        }

    }

endif;
