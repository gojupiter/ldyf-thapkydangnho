<?php

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
class LaStudio_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LaStudio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LaStudio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_style( 'la-animate-block', plugin_dir_url( __FILE__ ) . 'css/animate-block.min.css', array(), null);
		wp_register_style( 'la-icon-outline', plugin_dir_url( __FILE__ ) . 'css/font-la-icon-outline.min.css', array(), null);
		wp_register_style( 'font-nucleo-glyph', plugin_dir_url( __FILE__ ) . 'css/font-nucleo-glyph.min.css', array(), null);

		$asset_font_without_domain = apply_filters('LaStudio/filter/assets_font_url', untrailingslashit(plugin_dir_url( dirname(__FILE__) )));

		wp_add_inline_style(
			'la-icon-outline',
			"@font-face {
				font-family: 'LaStudio Outline';
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-outline.eot');
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-outline.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.woff2') format('woff2'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.svg') format('svg');
				font-weight: 400;
				font-style: normal
			}"
		);
		wp_add_inline_style(
			'font-nucleo-glyph',
			"@font-face {
				font-family: 'Nucleo Glyph';
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot');
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff2') format('woff2'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.svg') format('svg');
				font-weight: 400;
				font-style: normal
			}"
		);

		if(wp_style_is('font-awesome', 'registered')){
			wp_deregister_style('font-awesome');
		}
		if(wp_style_is('animate-css', 'registered')){
			wp_deregister_style('animate-css');
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LaStudio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LaStudio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lastudio-public.js', array( 'jquery' ), $this->version, false );

	}

	public function render_dlicon(){
		include_once plugin_dir_path( __FILE__ ) . 'partials/dlicon.php';
	}

	public function allowed_http_origins( $allowed_origins ){

		$admin_origin = parse_url( admin_url() );
		$home_origin = parse_url( home_url() );

		$admin_origin_host = $admin_origin[ 'host' ];
		$home_origin_host = $home_origin[ 'host' ];
		if(substr($admin_origin_host, 0, 4) == 'www.'){
			$admin_origin_host = substr($admin_origin_host, 4);
		}
		if(substr($home_origin_host, 0, 4) == 'www.'){
			$home_origin_host = substr($home_origin_host, 4);
		}

		$allowed_origins = array_unique(
			array(
				'http://' . $admin_origin_host,
				'https://' . $admin_origin_host,
				'http://www.' . $admin_origin_host,
				'https://www.' . $admin_origin_host,
				'http://' . $home_origin_host,
				'https://' . $home_origin_host,
				'http://www.' . $home_origin_host,
				'https://www.' . $home_origin_host
			)
		);

		return $allowed_origins;
	}

	/**
	 * Add async to theme javascript file for performance
	 *
	 * @param string $tag The script tag.
	 * @param string $handle The script handle.
	 */

	public function add_async($tag, $handle, $src) {
		$defer_scripts = apply_filters('lastudio/theme/defer_scripts', array(
			//'jquery',
			'googleapis',
			'wp-embed',
			'contact-form-7',
			'tp-tools',
			'revmin',
			'wc-add-to-cart',
			'woocommerce',
			'jquery-blockui',
			'js-cookie',
			'wc-cart-fragments',
			'prettyphoto',
			'jquery-selectbox',
			'jquery-yith-wcwl',
			'photoswipe',
			'photoswipe-ui-default',
			'waypoints',
			'yikes-easy-mc-ajax',
			'form-submission-helpers',
			'wpb_composer_front_js',
			'vc_accordion_script',
			'vc_tta_autoplay_script',
			'vc_tabs_script',

			'wp-mediaelement',
			'jquery-cue',
			'lpm-mejs',
			'lpm-app'
		));

		$async_scripts = apply_filters('lastudio/theme/async_scripts', array());

		$tag = str_replace(" type='text/javascript'", '', $tag);

		if (!empty($defer_scripts) && in_array( strtolower($handle), $defer_scripts ) ) {
			return preg_replace('/(><\/[a-zA-Z][^0-9](.*)>)$/', ' defer $1 ', $tag);
		}

		if (!empty($async_scripts) && in_array( strtolower($handle), $async_scripts ) ) {
			return preg_replace('/(><\/[a-zA-Z][^0-9](.*)>)$/', ' async $1 ', $tag);
		}

		return $tag;
	}

	public function remove_style_attr($tag, $handler) {
		return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
	}

	public function remove_woocommerce_hook(){
		remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
	}

	public function widgets_init(){
		require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-recent-posts.php';
		//require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-contact-info.php';
		//require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-twitter-feed.php';

		register_widget('LaStudio_Widget_Recent_Posts');
		//register_widget('LaStudio_Widget_Contact_Info');
		//register_widget('LaStudio_Widget_Twitter_Feed');

		if(class_exists('WC_Widget')){

			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-product-sort-by.php';
			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-price-filter-list.php';
			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-product-tag.php';

			register_widget('LaStudio_Widget_Product_Sort_By');
			register_widget('LaStudio_Widget_Price_Filter_List');
			register_widget('LaStudio_Widget_Product_Tag');

		}

	}
}
