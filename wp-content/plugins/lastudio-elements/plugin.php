<?php
namespace Lastudio_Elements;


if ( ! defined( 'ABSPATH' ) ) {	exit; } // Exit if accessed directly

/**
 * Main class plugin
 */
class LastudioPlugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	/**
	 * @var array
	 */
	private $_localize_settings = [];


    /**
     * Check if processing elementor widget
     *
     * @var boolean
     */
    private $is_elementor_ajax = false;

	/**
	 * @return string
	 */
	public function get_version() {
		return LASTUDIO_ELEMENTS_VER;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'lastudio-elements' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'lastudio-elements' ), '1.0.0' );
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function _includes() {
		require LASTUDIO_ELEMENTS_PATH . 'includes/modules-manager.php';
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);

		$filename = LASTUDIO_ELEMENTS_PATH . $filename . '.php';


		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	public function get_localize_settings() {
		return $this->_localize_settings;
	}

	public function add_localize_settings( $setting_key, $setting_value = null ) {


		if ( is_array( $setting_key ) ) {
			$this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );

			return;
		}

		if ( ! is_array( $setting_value ) || ! isset( $this->_localize_settings[ $setting_key ] ) || ! is_array( $this->_localize_settings[ $setting_key ] ) ) {
			$this->_localize_settings[ $setting_key ] = $setting_value;

			return;
		}

		$this->_localize_settings[ $setting_key ] = array_replace_recursive( $this->_localize_settings[ $setting_key ], $setting_value );
	}

	public function enqueue_custom_icons(){
        wp_enqueue_style(
            'lastudio-dlicon',
            LASTUDIO_ELEMENTS_URL . 'assets/css/lib/dlicon/dlicon.css',
            false,
            LASTUDIO_ELEMENTS_VER
        );

        $asset_font_without_domain = LASTUDIO_ELEMENTS_URL . 'assets/css/lib/dlicon';
        wp_add_inline_style(
            'lastudio-dlicon',
            "@font-face {
                    font-family: 'dliconoutline';
                    src: url('{$asset_font_without_domain}/dlicon.woff2') format('woff2'),
                         url('{$asset_font_without_domain}/dlicon.woff') format('woff'),
                         url('{$asset_font_without_domain}/dlicon.ttf') format('truetype');
                    font-weight: 400;
                    font-style: normal
                }"
        );
    }

    /**
	 * Enqueue frontend styles
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_styles() {

        wp_enqueue_style(
            'lastudio-elements',
            LASTUDIO_ELEMENTS_URL . 'assets/css/lastudio-elements.css' ,
            false,
            LASTUDIO_ELEMENTS_VER
        );

        if ( is_rtl() ) {
            wp_enqueue_style(
                'lastudio-elements-rtl',
                LASTUDIO_ELEMENTS_URL . 'assets/css/lastudio-elements-rtl.css' ,
                false,
                LASTUDIO_ELEMENTS_VER
            );
        }

        $default_theme_enabled = apply_filters( 'lastudio-elements/assets/css/default-theme-enabled', true );

        // Register vendor slider-pro.css styles (https://github.com/bqworks/slider-pro)
        wp_register_style(
            'lastudio-slider-pro-css',
            LASTUDIO_ELEMENTS_URL . 'assets/css/lib/slider-pro/slider-pro.min.css',
            false,
            '1.3.0'
        );

        // Register vendor juxtapose-css styles
        wp_register_style(
            'lastudio-juxtapose-css',
            LASTUDIO_ELEMENTS_URL . 'assets/css/lib/juxtapose/juxtapose.css' ,
            false,
            '1.3.0'
        );

        if ( ! $default_theme_enabled ) {
            return;
        }

        wp_enqueue_style(
            'lastudio-elements-skin',
            LASTUDIO_ELEMENTS_URL . 'assets/css/lastudio-elements-skin.css' ,
            false,
            LASTUDIO_ELEMENTS_VER
        );

	}

    /**
	 * Enqueue frontend scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_scripts() {

        $google_api_key = apply_filters('lastudio-elements/advanced-map/api', '');

        if ( ! empty( $google_api_key ) && ( empty( $api_disabled ) ) ) {

            wp_register_script(
                'google-maps-api',
                add_query_arg(
                    array( 'key' => $google_api_key ),
                    'https://maps.googleapis.com/maps/api/js'
                ),
                false,
                false,
                true
            );
        }

        // Register vendor masonry.pkgd.min.js script
        wp_register_script(
            'lastudio-masonry-js',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lib/masonry-js/masonry.pkgd.min.js' ,
            array(),
            '4.2.1',
            true
        );

        wp_register_script(
            'lastudio-ease-pack',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lib/easing/EasePack.min.js'  ,
            null,
            null,
            true
        );

        wp_register_script(
            'lastudio-tween-max',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lib/easing/TweenMax.min.js'  ,
            null,
            null,
            true
        );

        // Register vendor anime.js script (https://github.com/juliangarnier/anime)
        wp_register_script(
            'lastudio-anime-js',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lib/anime-js/anime.min.js' ,
            [],
            '2.2.0',
            true
        );

        // Register vendor salvattore.js script (https://github.com/rnmp/salvattore)
        wp_register_script(
            'lastudio-salvattore',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lib/salvattore/salvattore.min.js',
            [],
            '1.0.9.1',
            true
        );

        // Register vendor slider-pro.js script (https://github.com/bqworks/slider-pro)
        wp_register_script(
            'lastudio-slider-pro',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lib/slider-pro/jquery.sliderPro.min.js' ,
            [],
            '1.3.0',
            true
        );

        // Register vendor juxtapose.js script
        wp_register_script(
            'lastudio-juxtapose',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lib/juxtapose/juxtapose.min.js',
            [],
            '1.3.0',
            true
        );

        // Register vendor tablesorter.js script (https://github.com/Mottie/tablesorter)
        wp_register_script(
            'jquery-tablesorter',
            LASTUDIO_ELEMENTS_URL .'assets/js/lib/tablesorter/jquery.tablesorter.min.js',
            [ 'jquery' ],
            '2.30.7',
            true
        );

        wp_register_script(
            'lastudio-elements',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lastudio-elements.js' ,
            [ 'jquery', 'elementor-frontend' ],
            LASTUDIO_ELEMENTS_VER,
            true
        );

	}

	public function enqueue_frontend_localize_script(){
        $this->add_localize_settings('messages', array(
            'invalidMail' => esc_html__( 'Please specify a valid e-mail', 'lastudio-elements' ),
        ));

        $this->add_localize_settings('ajaxurl', esc_url( admin_url( 'admin-ajax.php' ) ));

        wp_localize_script(
            'lastudio-elements',
            'lastudioElements',
            apply_filters( 'lastudio-elements/frontend/localize-data', $this->get_localize_settings() )
        );
    }

    /**
	 * Enqueue editor styles
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_editor_styles() {

	    $this->enqueue_custom_icons();

		wp_enqueue_style(
			'lastudio-elements-editor',
			LASTUDIO_ELEMENTS_URL . 'assets/css/editor.css',
			[],
			LASTUDIO_ELEMENTS_VER
		);

        wp_enqueue_style(
            'lastudio-elements-font',
            LASTUDIO_ELEMENTS_URL . 'assets/css/lib/lastudioelements-font/css/lastudioelements.css' ,
            [],
            LASTUDIO_ELEMENTS_VER
        );
	}

    /**
	 * Enqueue editor scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_editor_scripts() {

        wp_enqueue_script(
            'lastudio-elements-editor',
            LASTUDIO_ELEMENTS_URL . 'assets/js/lastudio-elements-editor.js' ,
            ['jquery'],
            LASTUDIO_ELEMENTS_VER,
            true
        );

        wp_localize_script('lastudio-elements-editor', 'LaCustomBPFE', [
            'laptop' => [
                'name' => __( 'Laptop', 'lastudio-elements' ),
                'text' => __( 'Preview for 1366px', 'lastudio-elements' )
            ],
            'width800' => [
                'name' => __( 'Tablet Portrait', 'lastudio-elements' ),
                'text' => __( 'Preview for 768px', 'lastudio-elements' )
            ],
            'tablet' => [
                'name' => __( 'Tablet Landscape', 'lastudio-elements' ),
                'text' => __( 'Preview for 1024px', 'lastudio-elements' )
            ]
        ]);

	}

	public function enqueue_panel_scripts() {}

	public function enqueue_panel_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

    public function enqueue_admin_scripts() {
        wp_enqueue_style(
            'lastudio-elements-admin',
            LASTUDIO_ELEMENTS_URL . 'assets/css/lastudio-elements-admin.css',
            [],
            LASTUDIO_ELEMENTS_VER
        );
    }

	public function elementor_init() {
		$this->_modules_manager = new Modules_Manager();

		// Add element category in panel
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'lastudio', // This is the name of your addon's category and will be used to group your widgets/elements in the Edit sidebar pane!
			[
                'title' => esc_html__( 'LaStudio Elements', 'lastudio-elements' ),
                'icon'  => 'font'
			],
			1
		);
	}

    /**
     * Rewrite core controls.
     *
     * @param  object $controls_manager Controls manager instance.
     * @return void
     */
    public function rewrite_controls( $controls_manager ) {

        $controls = array(
            $controls_manager::ICON => '\Lastudio_Elements\Controls\Control_Icon',
        );

        foreach ( $controls as $control_id => $class_name ) {

            $controls_manager->unregister_control( $control_id );
            $controls_manager->register_control( $control_id, new $class_name() );
        }

    }

    /**
     * Add new controls.
     *
     * @param  object $controls_manager Controls manager instance.
     * @return void
     */
    public function add_controls( $controls_manager ) {

        $grouped = array(
            'lastudio-box-style' => '\Lastudio_Elements\Controls\Group_Control_Box_Style',
        );

        foreach ( $grouped as $control_id => $class_name ) {
            $controls_manager->add_group_control( $control_id, new $class_name() );
        }

    }

    public function remove_default_image_sizes( $sizes ) {
        if (($key = array_search('medium_large', $sizes)) !== false) {
            unset($sizes[$key]);
        }
        return $sizes;
    }

	protected function add_actions() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );

        add_action( 'elementor/controls/controls_registered', [ $this, 'rewrite_controls' ], 10 );
        add_action( 'elementor/controls/controls_registered', [ $this, 'add_controls' ], 10 );


        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_frontend_scripts' ] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_localize_script' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_custom_icons' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_custom_icons' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

        add_filter( 'intermediate_image_sizes', [ $this, 'remove_default_image_sizes' ], 500 );

        add_action( 'wp_ajax_elementor_render_widget', [ $this, 'set_elementor_ajax' ], 10, -1 );

        if(!defined('ELEMENTOR_PRO_VERSION')) {
            add_filter('single_template', [ $this, 'single_elementor_library_template' ] );
        }
        add_action( 'elementor/preview/enqueue_styles', function() {
            wp_enqueue_style( 'lastudio-slider-pro-css' );
        } );

        add_filter('elementor/icons_manager/additional_tabs', [ $this, 'add_dlicon_library' ]);

        add_action('elementor/element/column/layout/before_section_end', [$this, 'modify_column_widget'], 99, 2);

        add_action('elementor/element/editor-preferences/preferences/before_section_end', [ $this, 'modify_panel_backend' ], 10, 2);
	}

	public function single_elementor_library_template( $template ){
        if(is_singular('elementor_library')){
            return LASTUDIO_ELEMENTS_PATH . 'single-elementor_library.php';
        }
        return $template;
    }

    /**
     * Set $this->is_elementor_ajax to true on Elementor AJAX processing
     *
     * @return  void
     */
    public function set_elementor_ajax() {
        $this->is_elementor_ajax = true;
    }

    /**
     * Check if we currently in Elementor mode
     *
     * @return void
     */
    public function in_elementor() {

        $result = false;

        if ( wp_doing_ajax() ) {
            $result = $this->is_elementor_ajax;
        } elseif ( \Elementor\Plugin::instance()->editor->is_edit_mode()
            || \Elementor\Plugin::instance()->preview->is_preview_mode() ) {
            $result = true;
        }

        /**
         * Allow to filter result before return
         *
         * @var bool $result
         */
        return apply_filters( 'lastudio-elements/in-elementor', $result );
    }

    /**
     * Check if we currently in Elementor editor mode
     *
     * @return void
     */
    public function is_edit_mode() {

        $result = false;

        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
            $result = true;
        }

        /**
         * Allow to filter result before return
         *
         * @var bool $result
         */
        return apply_filters( 'lastudio-elements/is-edit-mode', $result );
    }

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->_includes();
		$this->add_actions();
	}

	public function add_dlicon_library( $tabs ){

        $tabs['dlicon'] = [
            'name' => 'dlicon',
            'label' => __( 'LA-Studio Icons', 'elementor' ),
            'url' =>  LASTUDIO_ELEMENTS_URL . 'assets/css/lib/dlicon/dlicon.css',
            'enqueue' => [ LASTUDIO_ELEMENTS_URL . 'assets/css/lib/dlicon/dlicon.css' ],
            'prefix' => '',
            'displayPrefix' => 'dlicon',
            'labelIcon' => 'dlicon design_shapes',
            'ver' => '1.0.0',
            'fetchJson' => LASTUDIO_ELEMENTS_URL . 'assets/css/lib/dlicon/dlicon.json',
            'native' => false
        ];

        return $tabs;
    }

    public function modify_column_widget( $element, $args ){
        $element->update_responsive_control(
            '_inline_size',
            [
                'device_args' => [
                    'laptop' => [
                        'max' => 100,
                        'required' => false,
                    ],
                    'width800' => [
                        'max' => 100,
                        'required' => false,
                    ],
                    'tablet' => [
                        'max' => 100,
                        'required' => false,
                    ],
                    'mobile' => [
                        'max' => 100,
                        'required' => false,
                    ],
                ],
                'min_affected_device' => [
                    'desktop' => 'laptop',
                    'laptop' => 'laptop'
                ],
            ]
        );
    }

    public function modify_panel_backend( $element, $args ){
        $element->add_control(
            'lastudio_fix_small_browser',
            [
                'label' => __( 'Fix Small Browser', 'lastudio' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => __( 'Force set up minimum width for Elementor Preview', 'lastudio' ),
            ]
        );
    }

}


if ( ! defined( 'LASTUDIO_ELEMENTS_TESTS' ) ) {
    // In tests we run the instance manually.
    LastudioPlugin::instance();
}