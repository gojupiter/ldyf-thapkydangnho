<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    LaStudio
 * @subpackage LaStudio/includes
 * @author     Duy Pham <dpv.0990@gmail.com>
 */
class LaStudio {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      LaStudio_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $extensions = array();

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'LASTUDIO_VERSION' ) ) {
			$this->version = LASTUDIO_VERSION;
		}
		else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'lastudio';

		$this->extensions = get_option('la_extension_available', array(
			'swatches' => true,
			'360' => true,
			'content_type' => true
		));

		// register autoloader
		spl_autoload_register( array( $this, 'autoload' ) );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_extension_hooks();

	}

	public function autoload( $class ) {
		// check if class is in same namespace, if not return
		if ( strpos( $class, 'LaStudio_Theme_Options_Field' ) !== 0 ) {
			return;
		}

		$class = str_replace('LaStudio_Theme_Options_Field_', '', $class);

		// make the class name lowercase and replace underscores with dashes
		$class = strtolower( str_replace( '_', '-', $class ) );
		// build path to class file
		$path = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/theme-options/';

		if($class == 'base'){
			$path = $path .'base.php';
		}
		else{
			$path = $path . 'fields/' . $class . '.php';
		}

		// include file if it exists
		if ( file_exists( $path ) ) {
			include_once ( $path );
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - LaStudio_Loader. Orchestrates the hooks of the plugin.
	 * - LaStudio_i18n. Defines internationalization functionality.
	 * - LaStudio_Admin. Defines all hooks for the admin area.
	 * - LaStudio_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$plugin_dir_path = plugin_dir_path( dirname( __FILE__ ) );

		/**
		 * Load public functions
		 */

		require_once $plugin_dir_path . 'public/lastudio-functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $plugin_dir_path . 'includes/class-lastudio-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once $plugin_dir_path . 'includes/class-lastudio-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once $plugin_dir_path . 'admin/class-lastudio-admin.php';

		require_once $plugin_dir_path . 'admin/class-lastudio-theme-options.php';

		require_once $plugin_dir_path . 'admin/class-lastudio-postmeta-revisions.php';

		require_once $plugin_dir_path . 'admin/class-lastudio-metabox.php';

		require_once $plugin_dir_path . 'admin/class-lastudio-content-type.php';

		//require_once $plugin_dir_path . 'admin/class-lastudio-shortcode-manager.php';

		require_once $plugin_dir_path . 'admin/class-lastudio-taxonomy.php';

		require_once $plugin_dir_path . 'admin/class-lastudio-theme-customize.php';

		require_once $plugin_dir_path . 'public/class-lastudio-cache-helper.php';

		/**
		 * Load Extensions
		 */

		require_once $plugin_dir_path . 'admin/class-lastudio-woocommerce-import-export.php';

		/**
		 * Shortcodes
		 */
		//require_once $plugin_dir_path . 'includes/extensions/shortcodes/class-lastudio-shortcodes.php';

		/**
		 * Load WooCommerce ThreeSixty
		 */
		if(!empty($this->extensions['360'])) {
			require_once $plugin_dir_path . 'includes/extensions/threesixty/class-lastudio-woocommerce-threesixty.php';
		}

		/**
		 * Swatches
		 */

		if(!empty($this->extensions['swatches'])){
			require_once $plugin_dir_path . 'includes/extensions/swatch/class-lastudio-swatch-attribute-configuration-object.php';
			require_once $plugin_dir_path . 'includes/extensions/swatch/class-lastudio-swatch-term.php';
			require_once $plugin_dir_path . 'includes/extensions/swatch/class-lastudio-swatch-product-term.php';
			require_once $plugin_dir_path . 'includes/extensions/swatch/class-lastudio-swatch.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $plugin_dir_path . 'public/class-lastudio-public.php';

		$this->loader = new LaStudio_Loader();

		LaStudio_Cache_Helper::init();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the LaStudio_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new LaStudio_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new LaStudio_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 999 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 999 );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $plugin_admin, 'admin_customize_enqueue' );

		/** Load Sanitize & Validate For Theme Options **/

		$this->loader->add_action( 'la_sanitize_text', $plugin_admin, 'sanitize_text' );
		$this->loader->add_action( 'la_sanitize_textarea', $plugin_admin, 'sanitize_textarea' );
		$this->loader->add_action( 'la_sanitize_checkbox', $plugin_admin, 'sanitize_checkbox' );
		$this->loader->add_action( 'la_sanitize_switcher', $plugin_admin, 'sanitize_checkbox' );
		$this->loader->add_action( 'la_sanitize_image_select', $plugin_admin, 'sanitize_image_select' );
		$this->loader->add_action( 'la_sanitize_group', $plugin_admin, 'sanitize_group' );
		$this->loader->add_action( 'la_sanitize_title', $plugin_admin, 'sanitize_title' );
		$this->loader->add_action( 'la_sanitize_clean', $plugin_admin, 'sanitize_clean' );


		$this->loader->add_action( 'la_validate_email', $plugin_admin, 'validate_email' );
		$this->loader->add_action( 'la_validate_numeric', $plugin_admin, 'validate_numeric' );
		$this->loader->add_action( 'la_validate_required', $plugin_admin, 'validate_required' );


		$this->loader->add_action( 'admin_footer', $plugin_admin, 'render_admin_footer' );
		$this->loader->add_action( 'customize_controls_print_footer_scripts', $plugin_admin, 'render_admin_footer' );

		//$this->loader->add_action( 'admin_footer', $this, 'render_svg_icon_as_css_inline' );
		//$this->loader->add_action( 'customize_controls_print_footer_scripts', $this, 'render_svg_icon_as_css_inline' );

		/** Register Ajax Action **/

		$this->loader->add_action( 'wp_ajax_la-fw-get-icons', $plugin_admin, 'ajax_get_icons' );
		$this->loader->add_action( 'wp_ajax_la-fw-autocomplete', $plugin_admin, 'ajax_autocomplete' );
		$this->loader->add_action( 'wp_ajax_la-export-options', $plugin_admin, 'ajax_export_options' );

		//$this->loader->add_filter('vc_after_init', $plugin_admin, 'add_el_class_field_to_easy_mc_shortcode', 100);
		//$this->loader->add_filter('shortcode_atts_yikes-mailchimp', $plugin_admin, 'add_global_var_for_easy_mc_shortcode', 100, 3);
		$this->loader->add_filter('yikes-mailchimp-form-container-class', $plugin_admin, 'add_el_class_to_ouput_easy_mc_shortcode', 100);
		$this->loader->add_action('yikes-mailchimp-before-form', $plugin_admin, 'unset_global_var_when_call_success_esy_mc_shortcode');

		$PostMeta_Revisions = new LaStudio_PostMeta_Revisions();

        // Actions
        //
        // When restoring a revision, also restore that revisions's revisioned meta.
        $this->loader->add_action( 'wp_restore_post_revision', $PostMeta_Revisions, '_wp_restore_post_revision_meta' , 10, 2 );
        // When creating or updating an autosave, save any revisioned meta fields.
        $this->loader->add_action( 'wp_creating_autosave', $PostMeta_Revisions , '_wp_autosave_post_revisioned_meta_fields' );
        $this->loader->add_action( 'wp_before_creating_autosave', $PostMeta_Revisions, '_wp_autosave_post_revisioned_meta_fields' );
        // When creating a revision, also save any revisioned meta.
        $this->loader->add_action( '_wp_put_post_revision', $PostMeta_Revisions, '_wp_save_revisioned_meta_fields'  );
        //Filters
        // When revisioned post meta has changed, trigger a revision save.
        $this->loader->add_filter( 'wp_save_post_revision_post_has_changed', $PostMeta_Revisions, '_wp_check_revisioned_meta_fields_have_changed', 10, 3 );

        // Add the revisioned meta to get_post_metadata for preview meta data.
        $this->loader->add_filter( 'get_post_metadata', $PostMeta_Revisions, '_wp_preview_meta_filter' , 10, 4 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new LaStudio_Public( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'admin_footer', $plugin_public, 'render_dlicon' );
		//$this->loader->add_action( 'customize_controls_print_footer_scripts', $plugin_public, 'render_dlicon' );
		//$this->loader->add_action( 'wp_footer', $plugin_public, 'render_dlicon', 999 );

		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'allowed_http_origins', $plugin_public, 'allowed_http_origins', 20, 1 );

//		$this->loader->add_filter( 'vc_enqueue_font_icon_element', $this, 'add_fonts_to_visual_composer' );
//		$this->loader->add_filter( 'vc_iconpicker-type-la_icon_outline', $this, 'get_la_icon_outline_font_icon' );
//		$this->loader->add_filter( 'vc_iconpicker-type-nucleo_glyph', $this, 'get_nucleo_glyph_font_icon' );
//		$this->loader->add_filter( 'vc_iconpicker-type-la_svg', $this, 'get_la_svg_icon' );

		add_filter( 'wp_calculate_image_srcset_meta', '__return_empty_array' );
		add_filter( 'wp_calculate_image_sizes', '__return_empty_array',  99 );
		add_filter( 'wp_calculate_image_srcset', '__return_empty_array',  99 );
		remove_filter( 'the_content', 'wp_make_content_images_responsive' );

		if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
			$this->loader->add_action('script_loader_tag', $plugin_public, 'add_async', 20, 3);
			$this->loader->add_action('style_loader_tag', $plugin_public, 'remove_style_attr', 20, 2);
		}

		$this->loader->add_action('woocommerce_loaded', $plugin_public, 'remove_woocommerce_hook');

		$this->loader->add_action( 'widgets_init', $plugin_public, 'widgets_init', 15 );
	}

	/**
	 * Register all of the hook related to the extenstion
	 * @since 1.0.0
	 * @access private
	 */
	private function define_extension_hooks() {
		/**
		 * Register content types
		 */

        if(!empty($this->extensions['content_type'])) {

            $post_types = array(
                'la_team_member' => array(
                    'label' => __('Team Member', 'lastudio'),
                    'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
                    'menu_icon' => 'dashicons-groups',
                    'public' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'menu_position' => 8,
                    'show_in_admin_bar' => false,
                    'show_in_nav_menus' => false,
                    'can_export' => true,
                    'has_archive' => false,
                    'exclude_from_search' => true,
                    'publicly_queryable' => true,
                    'rewrite' => array('slug' => 'team-member')
                )
            );
            $taxonomies = array(

            );
            $content_types = new LaStudio_Content_Type($post_types, $taxonomies);

            $this->loader->add_action('init', $content_types, 'setup_filters', 8);
            $this->loader->add_action('init', $content_types, 'register_content_type');
            $this->loader->add_filter('single_template', $content_types, 'single_template');
            $this->loader->add_filter('archive_template', $content_types, 'archive_template', 10);
            $this->loader->add_filter('taxonomy_template', $content_types, 'taxonomy_template', 10);
        }

		/**
		 * Register Shortcodes
		 */

		/*
		$shortcode_extension = new LaStudio_Shortcodes();
		$this->loader->add_action( 'init', $shortcode_extension, 'create_shortcode', 8);
		$this->loader->add_action( 'init', $shortcode_extension, 'load_dependencies', 8);
		$this->loader->add_action( 'init', $shortcode_extension, 'remove_old_woocommerce_shortcode', 20 );

		$this->loader->add_action( 'wp_ajax_la_get_shortcode_loader_by_ajax', $shortcode_extension, 'ajax_render_shortcode' );
		$this->loader->add_action( 'wp_ajax_nopriv_la_get_shortcode_loader_by_ajax', $shortcode_extension, 'ajax_render_shortcode' );
		$this->loader->add_action( 'vc_after_init', $shortcode_extension, 'vc_after_init' );
		$this->loader->add_filter( 'vc_param_animation_style_list', $shortcode_extension, 'vc_param_animation_style_list' );

		$this->loader->add_action( 'vc_load_shortcode', $shortcode_extension, 'remove_admin_on_vc_inline_mode', 10 );

		*/

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if(is_plugin_active('woocommerce/woocommerce.php')){
			/**
			 * Load WooCommerce ThreeSixty
			 */
			if(!empty($this->extensions['360'])) {
				$threesixty_extension = new LaStudio_WooCommerce_Threesixty();
				$this->loader->add_action('woocommerce_product_write_panel_tabs', $threesixty_extension, 'threesixty_product_write_panel_tabs', 99);
				$this->loader->add_action('woocommerce_product_data_panels', $threesixty_extension, 'threesixty_product_data_panel_wrap', 99);
				$this->loader->add_action('woocommerce_process_product_meta', $threesixty_extension, 'threesixty_save_product_meta', 1, 2);
				$this->loader->add_action('edit_attachment', $threesixty_extension, 'threesixty_save_embed_video');
				$this->loader->add_action('wp', $threesixty_extension, 'threesixty_replace_product_image');
				//$this->loader->add_filter( 'attachment_fields_to_edit', $threesixty_extension, 'threesixty_add_embed_video', 20, 2);
			}
			/**
			 * Load WooCommerce Swatches
			 */
			if(!empty($this->extensions['swatches'])) {
				$swatches = new LaStudio_Swatch();
				$this->loader->add_action( 'created_term', $swatches, 'saveTermMeta', 10, 3 );
				$this->loader->add_action( 'edit_term', $swatches, 'saveTermMeta', 10, 3 );
				$this->loader->add_action( 'wp_ajax_la_swatch_admin_load_thumbnails', $swatches, 'admin_load_variation_gallery' );
				$this->loader->add_action( 'woocommerce_save_product_variation', $swatches, 'save_gallery_for_product_variation', 10, 2 );
				$this->loader->add_action( 'admin_menu', $swatches, 'admin_menu' );
				$this->loader->add_action( 'admin_init', $swatches, 'admin_init', 99 );
				$this->loader->add_action( 'woocommerce_product_write_panel_tabs', $swatches, 'product_write_panel_tabs', 99 );
				$this->loader->add_action( 'woocommerce_product_data_panels', $swatches, 'product_data_panel_wrap', 99 );
				$this->loader->add_action( 'woocommerce_process_product_meta', $swatches, 'process_meta_box', 1, 2 );
				$this->loader->add_action( 'wp_ajax_la_swatch_get_product_variations', $swatches, 'get_product_variations' );
				$this->loader->add_action( 'wp_ajax_nopriv_la_swatch_get_product_variations', $swatches, 'get_product_variations' );
				$this->loader->add_action( 'woocommerce_delete_product_transients', $swatches, 'on_deleted_transient', 10, 1 );
				$this->loader->add_action( 'wp_ajax_la_render_swatches_panel', $swatches, 'ajax_render_swatches_panel' );
				$this->loader->add_filter( 'woocommerce_available_variation', $swatches, 'add_additional_into_variation_json', 10, 3 );
				$this->loader->add_action( 'widgets_init', $swatches, 'init_swatches_widget', 15 );

				$this->loader->add_action( 'template_redirect', $swatches, 'flush_all_gallery_cache', 15 );

				$this->loader->add_filter( 'woocommerce_dropdown_variation_attribute_options_html', $swatches, 'override_output_dropdown_variation_attribute_options', 101, 3 );

			}

			$wc_import_export = new LaStudio_WooCommerce_Import_Export();
			$this->loader->add_filter( 'woocommerce_product_export_skip_meta_keys', $wc_import_export, 'export_skip_meta_keys', 10, 1 );
			$this->loader->add_filter( 'woocommerce_product_export_product_default_columns', $wc_import_export, 'export_product_default_columns', 10, 1 );
			$this->loader->add_filter( 'woocommerce_product_export_product_column_lastudio_enable_360', $wc_import_export, 'export_product_column_threesixty', 10, 3 );
			$this->loader->add_filter( 'woocommerce_product_export_product_column_lastudio_swatch_type', $wc_import_export, 'export_product_column_swatch_type', 10, 3 );
			$this->loader->add_filter( 'woocommerce_product_export_product_column_lastudio_swatch_data', $wc_import_export, 'export_product_column_swatch_data', 10, 3 );
			$this->loader->add_filter( 'woocommerce_csv_product_import_mapping_default_columns', $wc_import_export, 'import_mapping_default_columns', 10, 1 );
			$this->loader->add_filter( 'woocommerce_csv_product_import_mapping_options', $wc_import_export, 'import_mapping_options', 10, 1 );
			$this->loader->add_filter( 'woocommerce_product_import_pre_insert_product_object', $wc_import_export, 'import_pre_insert_product_object', 10, 2 );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    LaStudio_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function add_fonts_to_visual_composer( $font_name ){

		global $la_external_icon_font;

		if(!is_array($la_external_icon_font)){
			$la_external_icon_font = array();
		}

		$asset_font_without_domain = apply_filters('LaStudio/filter/assets_font_url', untrailingslashit(plugin_dir_url( dirname(__FILE__) )));

		$font_face_html = '';

		if( 'la_icon_outline' == $font_name ){
			wp_enqueue_style('la-icon-outline');
			if( false && !isset($la_external_icon_font[$font_name])){
				$font_face_html .= "@font-face {
					font-family: 'LaStudio Outline';
					src: url('{$asset_font_without_domain}/public/fonts/nucleo-outline.eot');
					src: url('{$asset_font_without_domain}/public/fonts/nucleo-outline.eot') format('embedded-opentype'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-outline.woff2') format('woff2'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-outline.woff') format('woff'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-outline.ttf') format('truetype'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-outline.svg') format('svg');
					font-weight: 400;
					font-style: normal
				}";
				$la_external_icon_font[$font_name] = $font_name;
			}
		}
		if( 'nucleo_glyph' == $font_name ){
			wp_enqueue_style('font-nucleo-glyph');
			if( false && !isset($la_external_icon_font[$font_name])){
				$font_face_html .= "@font-face {
					font-family: 'Nucleo Glyph';
					src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot');
					src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot') format('embedded-opentype'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff2') format('woff2'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff') format('woff'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.ttf') format('truetype'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.svg') format('svg');
					font-weight: 400;
					font-style: normal
				}";
				$la_external_icon_font[$font_name] = $font_name;
			}
		}
		if( !empty( $font_face_html ) ) {

			if(!wp_doing_ajax()){
				printf(
					'<style data-la_component="InsertCustomCSS">%s</style>',
					$font_face_html
				);
			}
			else{
				printf(
					'<span data-la_component="InsertCustomCSS" class="js-el hidden">%s</span>',
					$font_face_html
				);
			}
		}
	}

	public function get_la_icon_outline_font_icon($icons = array()) {
		$transient_name = 'la_get_icon_library_outline_font_' . LaStudio_Cache_Helper::get_transient_version('outline_font');
		$results = get_transient($transient_name);
		if(empty($results)){
			$json_file = plugin_dir_path(dirname(__FILE__)) . 'public/fonts/font-la-icon-outline-object.json';
			if(file_exists($json_file)){
				$file_data = @file_get_contents( $json_file );
				if( !is_wp_error( $file_data ) ) {
					$results = json_decode( $file_data, true);
					if(!empty($results)){
						set_transient( $transient_name, $results, DAY_IN_SECONDS * 30 );
					}
				}
			}
		}
		if(empty($results)){
			$results = array();
		}
		return !empty($results) ? array_merge($icons, $results) : $icons;
	}

	public function get_nucleo_glyph_font_icon( $icons = array() ) {
		$transient_name = 'la_get_icon_library_nucleo_glyph_icon_' . LaStudio_Cache_Helper::get_transient_version('nucleo_glyph_icon');
		$results = get_transient($transient_name);
		if(empty($results)){
			$json_file = plugin_dir_path(dirname(__FILE__)) . 'public/fonts/font-nucleo-glyph-object.json';
			if(file_exists($json_file)){
				$file_data = @file_get_contents( $json_file );
				if( !is_wp_error( $file_data ) ) {
					$results = json_decode( $file_data, true);
					if(!empty($results)){
						set_transient( $transient_name, $results, DAY_IN_SECONDS * 30 );
					}
				}
			}
		}
		return !empty($results) ? array_merge($icons, $results) : $icons;
	}

	public function get_la_svg_icon( $icons = array() ) {
		$transient_name = 'la_get_icon_library_la_svg_' . LaStudio_Cache_Helper::get_transient_version('la_svg');
		$results = get_transient($transient_name);
		if(empty($results)){
			$json_file = plugin_dir_path(dirname(__FILE__)) . 'public/fonts/la-svg.json';
			if(file_exists($json_file)){
				$file_data = @file_get_contents( $json_file );
				if( !is_wp_error( $file_data ) ) {
					$results = json_decode( $file_data, true);
					if(!empty($results)){
						set_transient( $transient_name, $results, DAY_IN_SECONDS * 30 );
					}
				}
			}
		}
		return !empty($results) ? array_merge($icons, $results) : $icons;
	}

	public function render_svg_icon_as_css_inline(){
		$svg_root_url = apply_filters('LaStudio/filter/assets_font_url', untrailingslashit(plugin_dir_url( dirname(__FILE__) )));
		$svg_icons = $this->get_la_svg_icon();
		if(!empty($svg_icons)){
			echo '<style type="text/css">';
			foreach($svg_icons as $icons){
				foreach($icons as $icon){
					foreach($icon as $k => $v){
						$css_class = str_replace('la-svg ', '', $k);
						$icon_path = str_replace('la-svg-', '', $css_class);
						echo '.' . $css_class . ':before{';
						echo 'background-image:url("';
						echo $svg_root_url . '/public/svg/' . $icon_path . '.svg';
						echo '")';
						echo '}';
					}
				}
			}
			echo '</style>';
		}
	}

}