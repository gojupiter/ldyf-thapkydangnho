<?php
/**
 * Plugin Name:       LA-Studio Core
 * Plugin URI:        https://themeforest.net/user/la-studio/?ref=la-studio
 * Description:       This plugin use only for LA-Studio theme with Elementor
 * Version:           2.0.7.3
 * Author:            LA-Studio
 * Author URI:        https://themeforest.net/user/la-studio/?ref=la-studio
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lastudio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'LASTUDIO_VERSION', '2.0.7.3' );

/**
 * The code that runs during plugin activation.
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lastudio-activator.php';
	LaStudio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lastudio-deactivator.php';
	LaStudio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-lastudio.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lastudio() {
	$plugin = new LaStudio();
	$plugin->run();
}
run_lastudio();
