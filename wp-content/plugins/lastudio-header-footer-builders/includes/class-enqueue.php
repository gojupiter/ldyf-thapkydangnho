<?php
/**
 * Header Builder - Enqueue Class.
 *
 * @author  LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHFB_Enqueue' ) ) :

    class LAHFB_Enqueue {

        /**
         * Instance of this class.
         *
         * @since	1.0.0
         * @access	private
         * @var		LAHFB_Enqueue
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

            // Enqueue editor scripts
            add_action( 'admin_enqueue_scripts',    array( $this, 'editor_scripts'), 1 );

            // Enqueue frontend scripts
            add_action( 'wp_enqueue_scripts',       array( $this, 'frontend_scripts' ) );

            add_action( 'wp_head',                  array( $this, 'prepare_dynamic_style'), 100 );
        }

        /**
         * Enqueue editor scripts.
         *
         * @since	1.0.0
         */
        public function editor_scripts() {

            if ( LAHFB_Helper::is_backend_builder() ) {
                // admin utilities
                wp_enqueue_media();

                $script_dependencies = array(
                    'jquery',
                    'wp-color-picker',
                    'jquery-ui-sortable',
                    'jquery-ui-droppable',
                    'wp-color-picker-alpha'
                );

                wp_register_script( 'wp-color-picker-alpha', LAHFB_Helper::get_file_uri( 'assets/js/wp-color-picker-alpha.js' ), array( 'wp-color-picker' ), '2.1.3', true );
                // JavaScripts
                wp_register_script( 'lahfb-nicescroll-script', LAHFB_Helper::get_file_uri( 'assets/js/jquery.nicescroll.js' ) , $script_dependencies, null, true );
                wp_enqueue_script( 'lahfb-editor-scripts', LAHFB_Helper::get_file_uri('assets/src/editor/editor.min.js' ), array('lahfb-nicescroll-script'), LAHFB::VERSION, true );

                $header_preset_key = !empty($_GET['prebuild_header']) ? esc_attr($_GET['prebuild_header']) : '';
                $frontend_components = LAHFB_Helper::get_data_frontend_components();

                $uniqid = uniqid();

                $default_components = LAHFB_Helper::get_default_components($uniqid);

                $localize_data = array(
                    'nonce'                 => wp_create_nonce( 'lahfb-nonce' ),
                    'ajaxurl'               => admin_url( 'admin-ajax.php', 'relative' ),
                    'assets_url'            => LAHFB_Helper::get_file_uri( 'assets/' ),
                    'prebuilds_url'         => LAHFB_Helper::get_file_uri( 'includes/prebuilds/headers/' ),
                    'components'            => LAHFB_Helper::get_only_components_from_settings($frontend_components),
                    'editor_components'     => LAHFB_Helper::get_only_panels_from_settings($frontend_components),
                    'prebuild_header_key'   => LAHFB_Helper::is_prebuild_header_exists($header_preset_key) ? $header_preset_key : '',
                    'frontend_components'   => $frontend_components,
                    'default_data'          => array(
                        'uniqid'                => $uniqid,
                        'components'            => LAHFB_Helper::get_only_components_from_settings($default_components),
                        'editor_components'     => LAHFB_Helper::get_only_panels_from_settings($default_components),
                        'frontend_components'   => $default_components
                    ),
                    'backend_setting_page'  => admin_url( 'admin.php?page=lastudio_header_builder_setting' ),
                    'i18n'                  => array(
                        'save_text'                 => esc_attr__('Save Changes', 'lastudio-header-footer-builder'),
                        'clear_data_text'           => esc_attr__('Are you sure?', 'lastudio-header-footer-builder'),
                        'saved_text'                => esc_attr__('Saved', 'lastudio-header-footer-builder'),
                        'horizontal_header_text'    => esc_attr__('Horizontal Header', 'lastudio-header-footer-builder'),
                        'vertical_header_text'      => esc_attr__('Vertical Header', 'lastudio-header-footer-builder'),
                    )
                );
                wp_localize_script( 'lahfb-editor-scripts', 'lahfb_localize', $localize_data );

                // Styles
                wp_enqueue_style( 'lahfb-editor-styles', LAHFB_Helper::get_file_uri( 'assets/src/editor/css/editor.css' ), array(),LAHFB::VERSION );
            }

        }

        /**
         * Enqueue frontend scripts.
         *
         * @since	1.0.0
         */
        public function frontend_scripts() {
            $load_assets_frontend = apply_filters('LAHFB/load_assets_frontend', true);
            if($load_assets_frontend){
                $asset_url = apply_filters('LAHFB/asset_url', LAHFB::get_url());

                // JavaScripts
                wp_register_script( 'lahfb-jquery-plugins', $asset_url . 'assets/src/frontend/jquery-plugins.js' , array( 'jquery' ), LAHFB::VERSION, true );
                wp_enqueue_script( 'lahfb-frontend-scripts', $asset_url . 'assets/src/frontend/frontend.js', array( 'lahfb-jquery-plugins' ), LAHFB::VERSION, true );
                // Styles
                wp_enqueue_style( 'lahfb-frontend-styles', $asset_url . 'assets/src/frontend/header-builder.css' , array(), LAHFB::VERSION );
            }
        }

        public function prepare_dynamic_style(){
            if(!isset($_GET['lastudio_header_builder'])){
                printf('<style id="lahfb-frontend-styles-inline-css"></style>');
            }

        }

        public function dynamic_styles(){
            $header_preset_key = !empty($_GET['prebuild_header']) ? esc_attr($_GET['prebuild_header']) : '';
            $dynamic_styles = apply_filters( 'LAHFB/get_dynamic_styles', LAHFB_Helper::get_dynamic_styles(LAHFB_Helper::is_prebuild_header_exists($header_preset_key) ? $header_preset_key : '') );
            printf('<style id="lahfb-frontend-styles-inline-css">%s</style>', $dynamic_styles);
        }

    }

endif;