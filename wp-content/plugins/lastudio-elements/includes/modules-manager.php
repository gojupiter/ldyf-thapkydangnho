<?php
namespace Lastudio_Elements;

use Lastudio_Elements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Modules_Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function register_modules() {
		$modules = [
            'advanced-carousel',
            'advanced-map',
            'animated-box',
            'animated-text',
            'audio',
            'banner',
            'brands',
            'button',
            'circle-progress',
            'countdown-timer',
            'dropbar',
            'headline',
            'horizontal-timeline',
            'image-comparison',
            'images-layout',
            'inline-svg',
            'instagram-gallery',
            'portfolio',
            'posts',
            'price-list',
            'pricing-table',
            'progress-bar',
            'scroll-navigation',
            'services',
            'slider',
            'subscribe-form',
            'table',
            'tabs-widget',
            'team-member',
            'testimonials',
            'timeline',
            'video',

            'parallax-section',
            'query-control',
		];

        if(!defined('ELEMENTOR_PRO_VERSION')){
            $modules[] = 'custom-css';
            $modules[] = 'library';
        }

        if(defined('WPCF7_PLUGIN_URL')){
            $modules[] = 'contact-form-7';
        }

        if(class_exists('WooCommerce')){
            $modules[] = 'woocommerce';
        }


        ksort($modules);

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}

	private function require_files() {
		require( LASTUDIO_ELEMENTS_PATH . 'base/module-base.php' );
	}

	public function __construct() {
		$this->require_files();
		$this->register_modules();
	}
}