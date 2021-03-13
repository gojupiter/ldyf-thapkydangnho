<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Require plugins vendor
 */

require_once get_template_directory() . '/plugins/tgm-plugin-activation/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/plugins/plugins.php';

/**
 * Include the main class.
 */

include_once get_template_directory() . '/framework/classes/class-core.php';


Draven::$template_dir_path   = get_template_directory();
Draven::$template_dir_url    = get_template_directory_uri();
Draven::$stylesheet_dir_path = get_stylesheet_directory();
Draven::$stylesheet_dir_url  = get_stylesheet_directory_uri();

/**
 * Include the autoloader.
 */
include_once Draven::$template_dir_path . '/framework/classes/class-autoload.php';

new Draven_Autoload();

/**
 * load functions for later usage
 */

require_once Draven::$template_dir_path . '/framework/functions/functions.php';

new Draven_Multilingual();

if(!function_exists('Draven')){
    function Draven(){
        return Draven::get_instance();
    }
}

Draven();

new Draven_Scripts();

new Draven_Admin();

new Draven_WooCommerce();

new Draven_WooCommerce_Wishlist();

new Draven_WooCommerce_Compare();

/**
 * Set the $content_width global.
 */
global $content_width;
if ( ! is_admin() ) {
    if ( ! isset( $content_width ) || empty( $content_width ) ) {
        $content_width = (int) Draven()->layout()->get_content_width();
    }
}

require_once Draven::$template_dir_path . '/framework/functions/extra-functions.php';

require_once Draven::$template_dir_path . '/framework/functions/update.php';