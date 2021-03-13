<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'tgmpa_register', 'draven_register_required_plugins' );


if(!function_exists('lasf_get_plugin_source')){
    function lasf_get_plugin_source( $new, $initial, $plugin_name, $type = 'source'){
        if(isset($new[$plugin_name], $new[$plugin_name][$type]) && version_compare($initial[$plugin_name]['version'], $new[$plugin_name]['version']) < 0 ){
            return $new[$plugin_name][$type];
        }
        else{
            return $initial[$plugin_name][$type];
        }
    }
}

if(!function_exists('draven_register_required_plugins')){

	function draven_register_required_plugins() {

	    $initial_required = array(
	        'lastudio' => array(
	            'source'    => 'https://la-studioweb.com/file-resouces/draven/plugins/lastudio/2.0.9/lastudio.zip',
                'version'   => '2.0.9'
            ),
            'lastudio-elements' => array(
	            'source'    => 'https://la-studioweb.com/file-resouces/draven/plugins/lastudio-elements_v1.0.8.zip',
                'version'   => '1.0.8'
            ),
            'lastudio-header-footer-builders' => array(
	            'source'    => 'https://la-studioweb.com/file-resouces/draven/plugins/lastudio-header-footer-builders_v1.0.5.zip',
                'version'   => '1.0.5'
            ),
            'revslider' => array(
                'source'    => 'https://la-studioweb.com/file-resouces/shared/plugins/revslider/6.3.6/revslider.zip',
                'version'   => '6.3.6'
            ),
            'draven-demo-data' => array(
                'source'    => 'https://la-studioweb.com/file-resouces/draven/plugins/draven-demo-data/1.0.3.1/draven-demo-data.zip',
                'version'   => '1.0.3.1'
            )
        );

	    $from_option = get_option('draven_required_plugins_list', $initial_required);

		$plugins = array();

		$plugins['lastudio'] = array(
			'name'					=> esc_html_x('LA-Studio Core', 'admin-view', 'draven'),
			'slug'					=> 'lastudio',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio'),
            'required'				=> true,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio', 'version')
		);

		$plugins['lastudio-header-footer-builders'] = array(
			'name'					=> esc_html_x('LA-Studio Header Builder', 'admin-view', 'draven'),
			'slug'					=> 'lastudio-header-footer-builders',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio-header-footer-builders'),
            'required'				=> true,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio-header-footer-builders', 'version')
		);

        $plugins['elementor'] = array(
            'name' 					=> esc_html_x('Elementor Page Builder', 'admin-view', 'draven'),
            'slug' 					=> 'elementor',
            'required' 				=> true,
            'version'				=> '3.1.0'
        );

		$plugins['lastudio-elements'] = array(
			'name'					=> esc_html_x('LaStudio Elements For Elementor', 'admin-view', 'draven'),
			'slug'					=> 'lastudio-elements',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio-elements'),
            'required'				=> true,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio-elements', 'version')
		);

		$plugins['woocommerce'] = array(
			'name'     				=> esc_html_x('WooCommerce', 'admin-view', 'draven'),
			'slug'     				=> 'woocommerce',
			'version'				=> '4.9.2',
			'required' 				=> false
		);

        $plugins['draven-demo-data'] = array(
            'name'					=> esc_html_x('Draven Package Demo Data', 'admin-view', 'draven'),
            'slug'					=> 'draven-demo-data',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'draven-demo-data'),
            'required'				=> false,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'draven-demo-data', 'version')
        );

		$plugins['envato-market'] = array(
			'name'     				=> esc_html_x('Envato Market', 'admin-view', 'draven'),
			'slug'     				=> 'envato-market',
			'source'   				=> 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			'required' 				=> false,
			'version' 				=> '2.0.6'
		);

		$plugins['contact-form-7'] = array(
			'name' 					=> esc_html_x('Contact Form 7', 'admin-view', 'draven'),
			'slug' 					=> 'contact-form-7',
			'required' 				=> false
		);

		$plugins['revslider'] = array(
			'name'					=> esc_html_x('Slider Revolution', 'admin-view', 'draven'),
			'slug'					=> 'revslider',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'revslider'),
            'required'				=> false,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'revslider', 'version')
		);

        $plugins[] = array(
            'name'     				=> esc_html_x('Smash Balloon Social Photo Feed', 'admin-view', 'toro'),
            'slug'     				=> 'instagram-feed',
            'required' 				=> false,
        );

		$config = array(
			'id'           				=> 'draven',
			'default_path' 				=> '',
			'menu'         				=> 'tgmpa-install-plugins',
			'has_notices'  				=> true,
			'dismissable'  				=> true,
			'dismiss_msg'  				=> '',
			'is_automatic' 				=> false,
			'message'      				=> ''
		);

		tgmpa( $plugins, $config );

	}

}
