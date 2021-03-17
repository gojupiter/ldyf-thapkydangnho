<?php
/**
 * Header Builder - Frontend Builder Class.
 *
 * @author  LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHFB_Frontend_Builder' ) ) :
    class LAHFB_Frontend_Builder {

		/**
		 * Instance of this class.
         *
		 * @since	1.0.0
		 * @access	private
		 * @var		LAHFB_Frontend_Builder
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
            add_action( 'init', array( $this, 'frontend_action') );
            add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		    if ( ! LAHFB_Helper::is_frontend_builder() ) {
				return;
			}

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 500 );
			add_action( 'admin_init', array( $this, 'render' ) );
			add_filter( 'show_admin_bar', '__return_false' );
		}

		public function add_menu_page(){
            add_theme_page('Header Builder', 'Header Builder', 'manage_options', 'lastudio_header_builder_setting', array( $this, 'render_option_page') );
        }

		/**
		 * Register hidden page in WP Admin.
		 *
		 * Create /wp-admin/admin.php?page=lastudio_header_builder page.
		 * Page has no menu item in WP Admin Panel.
		 *
		 * @since	1.0.0
		 */
		public function admin_menu() {
			add_dashboard_page( '', '', 'manage_options', 'lastudio_header_builder', '' );
		}

		public function render_option_page(){
?>
            <div class="la-framework la-option-framework">
                <div class="la-header">
                    <h1><?php esc_html_e('Header Builder') ?></h1>
                </div>
                <div class="la-body la-show-all">
                    <div class="la-content">
                        <div class="la-sections">
                            <?php LAHFB_Helper::get_template( 'editor.tpl.php' ); ?>
                        </div>
                    </div>
                </div>
                <footer class="la-footer">
                    <div class="la-block-left">Powered by <a href="https://themeforest.net/user/la-studio/portfolio?ref=LA-Studio">LA-Studio</a></div>
                </footer>
            </div>
<?php
        }

		/**
		 * Register hidden page in WP Admin.
		 *
		 * Create /wp-admin/admin.php?page=lastudio_header_builder page.
		 * Page has no menu item in WP Admin Panel.
		 *
		 * @since	1.0.0
		 */
		public function admin_body_class($classes) {
			return $classes . ' lastudio-frontend-builder-wrap ';
		}

		/**
		 * Render.
		 *
		 * @since	1.0.0
		 */
		public function render() {
			ob_start();

			// WordPress Administration Bootstrap
			require_once(ABSPATH . 'wp-admin/admin.php');
			include(ABSPATH . 'wp-admin/admin-header.php');

			// Stupid hack for Wordpress alerts and warnings

            echo '<style>.lastudio-frontend-builder-wrap .wrap > *,.lastudio-frontend-builder-wrap .wrap{ display: none }</style>';

			echo '<div class="wrap hidden" style="height:0;overflow:hidden; display: none"><h2></h2></div>';

			$current_url = add_query_arg(array('lastudio_header_builder'=>'inline_mode'), get_home_url());

			if(!empty($_GET['prebuild_header'])){
                $current_url = add_query_arg(array('prebuild_header'=> esc_attr($_GET['prebuild_header'])), $current_url);
            }

			echo '<iframe id="LaStudo_Iframe" src="' . esc_url($current_url) . '" style="border: 0; width: 100%; height: 100%"></iframe>';

            // Editor HTML
            LAHFB_Helper::get_template( 'editor.tpl.php');

			include(ABSPATH . 'wp-admin/admin-footer.php');
			return;
		}

		public function frontend_action(){
		    if( !is_admin() && isset($_GET['lastudio_header_builder']) && $_GET['lastudio_header_builder'] == 'inline_mode' ){
                add_filter( 'show_admin_bar', '__return_false');
            }
        }

	}
endif;
