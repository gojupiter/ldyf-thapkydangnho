<?php
/**
 * Plugin Name: LaStudio Elements For Elementor
 * Plugin URI:  https://la-studioweb.com
 * Description: This plugin use only for LA-Studio theme with Elementor Page Builder
 * Version:     1.0.9
 * Author:      LaStudio
 * Author URI:  https://la-studioweb.com
 * Text Domain: lastudio-elements
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * @package lastudio-elements
 * @author  LaStudio
 * @license GPL-2.0+
 * @copyright  2018, LaStudio
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'LASTUDIO_ELEMENTS_VER', '1.0.9' );
define( 'LASTUDIO_ELEMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LASTUDIO_ELEMENTS_BASE', plugin_basename( __FILE__ ) );
define( 'LASTUDIO_ELEMENTS_URL', plugins_url( '/', __FILE__ ) );
define( 'LASTUDIO_ELEMENTS_ELEMENTOR_VERSION_REQUIRED', '3.1.0' );
define( 'LASTUDIO_ELEMENTS_PHP_VERSION_REQUIRED', '5.6' );

require_once LASTUDIO_ELEMENTS_PATH . 'includes/helper-functions.php';
require_once LASTUDIO_ELEMENTS_PATH . 'plugin.php';
require_once LASTUDIO_ELEMENTS_PATH . 'classes/class-lastudio-elements-tools.php';
require_once LASTUDIO_ELEMENTS_PATH . 'classes/class-lastudio-elements-utility.php';
require_once LASTUDIO_ELEMENTS_PATH . 'classes/class-lastudio-elements-shortcodes.php';
require_once LASTUDIO_ELEMENTS_PATH . 'classes/class-lastudio-elements-wpml.php';

/**
 *
 */
if(!function_exists('lastudio_elementor_recreate_editor_file')){
    function lastudio_elementor_recreate_editor_file(){

        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        $wp_upload_dir = wp_upload_dir( null, false );
        $target_source_folder = $wp_upload_dir['basedir'] . '/elementor';
        $target_source_file = $target_source_folder . '/editor.min.js';
        $remote_source_file = plugin_dir_path( LASTUDIO_ELEMENTS_PATH ) . 'elementor/assets/js/editor.min.js';
        if($wp_filesystem->exists($remote_source_file)){
            $file_content = $wp_filesystem->get_contents($remote_source_file);
            $string_search = array(
                '["desktop","tablet","mobile"]'
            );
            $string_replace = array(
                '["desktop","laptop","tablet","width800","mobile"]'
            );
            $new_content = str_replace($string_search, $string_replace, $file_content);

            $new_content2 = preg_replace(
                '/stylesheet\.addDevice\((.*)\)},addStyleRules/',
                'stylesheet.addDevice("mobile",0).addDevice("width800",elementorFrontend.config.breakpoints.md).addDevice("tablet",elementorFrontend.config.breakpoints.width800).addDevice("laptop",elementorFrontend.config.breakpoints.lg).addDevice("desktop",elementorFrontend.config.breakpoints.xl)},addStyleRules',
                $new_content
            );

            if(empty($new_content2)){
                $tmp1 = explode('stylesheet.addDevice', $new_content);
                $tmp2 = explode('},addStyleRules', $new_content);
                $new_content = $tmp1[0] . 'stylesheet.addDevice("mobile",0).addDevice("width800",elementorFrontend.config.breakpoints.md).addDevice("tablet",elementorFrontend.config.breakpoints.width800).addDevice("laptop",elementorFrontend.config.breakpoints.lg).addDevice("desktop",elementorFrontend.config.breakpoints.xl)},addStyleRules' . $tmp2[1];
            }
            else{
                $new_content = $new_content2;
            }

            if(!$wp_filesystem->is_dir($target_source_folder)){
                if(! wp_mkdir_p( $target_source_folder )){
                    return new WP_Error( 'lastudio_elementor.cannot_put_contents', __( 'Could not open the file for fetching', 'lastudio-elements' ) );
                }
            }

            if($wp_filesystem->put_contents($target_source_file, $new_content)){
                update_option('lastudio-elementor-has-init-js', true);
                return true;
            }
            else{
                update_option('lastudio-elementor-has-init-js', false);
                return new WP_Error( 'lastudio_elementor.cannot_put_contents', __( 'Could not open the file for fetching', 'lastudio-elements' ) );
            }
        }
        else{
            return new WP_Error( 'lastudio_elementor.resource_not_exists', __( 'Resource does not exist', 'lastudio-elements' ) );
        }

    }
}
add_action('lastudio_elementor_recreate_editor_file', 'lastudio_elementor_recreate_editor_file');
add_action('elementor/core/files/clear_cache', 'lastudio_elementor_recreate_editor_file');

/**
 * Remove the option state to override js file
 * @since 1.0.2.6
 */
if(!function_exists('lastudio_elements_check_elementor_upgrade_completed')){
    function lastudio_elements_check_elementor_upgrade_completed( $upgrader_object, $options ) {
        // The path to our plugin's main file
        $our_plugin = 'elementor/elementor.php';
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == $our_plugin ) {
                    // Set a transient to record that our plugin has just been updated
                    do_action('lastudio_elementor_recreate_editor_file');
                }
            }
        }
    }
    add_action( 'upgrader_process_complete', 'lastudio_elements_check_elementor_upgrade_completed', 10, 2 );
}


/**
 * Check if Elementor is installed
 *
 * @since 1.0
 *
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {
    function _is_elementor_installed() {
        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();
        return isset( $installed_plugins[ $file_path ] );
    }
}

/**
 * Shows notice to user if Elementor plugin
 * is not installed or activated or both
 *
 * @since 1.0
 *
 */
function lastudio_elements_fail_load() {
    $plugin = 'elementor/elementor.php';

    if ( _is_elementor_installed() ) {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
        $message = __( 'LaStudio Elements requires Elementor plugin to be active. Please activate Elementor to continue.', 'lastudio-elements' );
        $button_text = __( 'Activate Elementor', 'lastudio-elements' );

    } else {
        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }

        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
        $message = sprintf( __( 'LaStudio Elements requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', 'lastudio-elements' ), '<strong>', '</strong>' );
        $button_text = __( 'Install Elementor', 'lastudio-elements' );
    }

    $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

    printf( '<div class="error"><p>%1$s</p>%2$s</div>', esc_html( $message ), $button );
}

/**
 * Shows notice to user if
 * Elementor version if outdated
 *
 * @since 1.0
 *
 */
function lastudio_elements_out_of_date() {
    if ( ! current_user_can( 'update_plugins' ) ) {
        return;
    }

    $message = __( 'LaStudio Elements requires Elementor version at least ' . LASTUDIO_ELEMENTS_ELEMENTOR_VERSION_REQUIRED . '. Please update Elementor to continue.', 'lastudio-elements' );

    printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );
}

/**
 * Shows notice to user if minimum PHP
 * version requirement is not met
 *
 * @since 1.0
 *
 */
function lastudio_elements_fail_php() {
    $message = __( 'LaStudio Elements requires PHP version ' . LASTUDIO_ELEMENTS_PHP_VERSION_REQUIRED .'+ to work properly. The plugins is deactivated for now.', 'lastudio-elements' );

    printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );

    if ( isset( $_GET['activate'] ) )
        unset( $_GET['activate'] );
}

/**
 * Deactivates the plugin
 *
 * @since 1.0
 */
function lastudio_elements_deactivate() {
    deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Load theme textdomain
 *
 * @since 1.0
 *
 */
function lastudio_elements_load_plugin_textdomain() {
    load_plugin_textdomain( 'lastudio-elements', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * Assigns category to LaStudio Elements
 *
 * @since 1.0
 *
 */
function lastudio_elements_add_root_category() {
    \Elementor\Plugin::instance()->elements_manager->add_category(
        'lastudio',
        array(
            'title' => esc_html__( 'LaStudio Elements', 'lastudio-elements' ),
            'icon'  => 'font',
        ),
        1 );
}


function lastudio_elements_override_frontend_after_register_scripts(){
    wp_deregister_script('jquery-slick');
}

function lastudio_elements_override_editor_before_enqueue_scripts( $src, $handler ){
    if($handler == 'elementor-editor'){
        $wp_upload_dir = wp_upload_dir( null, false );
        return $wp_upload_dir['baseurl'] . '/elementor/editor.min.js';
    }
    return $src;
}

function lastudio_elements_override_editor_wp_head(){
    ?>
    <script type="text/template" id="tmpl-elementor-control-responsive-switchers">
        <div class="elementor-control-responsive-switchers">
            <#
            var devices = responsive.devices || [ 'desktop', 'laptop', 'tablet', 'width800',  'mobile' ];

            _.each( devices, function( device ) { #>
            <a class="elementor-responsive-switcher elementor-responsive-switcher-{{ device }}" data-device="{{ device }}">
                <i class="eicon-device-{{ device }}"></i>
            </a>
            <# } );
            #>
        </div>
    </script>
    <?php
}

add_action( 'plugins_loaded', 'lastudio_elements_init', 0 );

function lastudio_elements_init() {

    // Notice if the Elementor is not active
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'lastudio_elements_fail_load' );
        return;
    }

    // Check for required Elementor version
    if ( ! version_compare( ELEMENTOR_VERSION, LASTUDIO_ELEMENTS_ELEMENTOR_VERSION_REQUIRED, '>=' ) ) {
        add_action( 'admin_notices', 'lastudio_elements_out_of_date' );
        add_action( 'admin_init', 'lastudio_elements_deactivate' );
        return;
    }

    // Check for required PHP version
    if ( ! version_compare( PHP_VERSION, LASTUDIO_ELEMENTS_PHP_VERSION_REQUIRED, '>=' ) ) {
        add_action( 'admin_notices', 'lastudio_elements_fail_php' );
        add_action( 'admin_init', 'lastudio_elements_deactivate' );
        return;
    }

    lastudio_elements_shortocdes()->init();

    add_action( 'init', 'lastudio_elements_load_plugin_textdomain' );

    add_action( 'elementor/init', 'lastudio_elements_add_root_category' );


    /** Override */

    add_action('elementor/frontend/after_register_scripts', 'lastudio_elements_override_frontend_after_register_scripts' );

    if(defined('ELEMENTOR_VERSION')){

        $wp_upload_dir = wp_upload_dir( null, false );
        $target_source_file = $wp_upload_dir['basedir'] . '/elementor/editor.min.js';
        if(!file_exists($target_source_file)){
            do_action('lastudio_elementor_recreate_editor_file');
        }

        add_action('script_loader_src', 'lastudio_elements_override_editor_before_enqueue_scripts', 10, 2);
        add_action('elementor/editor/wp_head', 'lastudio_elements_override_editor_wp_head' );

        $need_include = get_option('lastudio_has_elementor_error', false);

        if(empty($need_include)){
            require LASTUDIO_ELEMENTS_PATH . 'override/includes/base/controls-stack.php';
            require LASTUDIO_ELEMENTS_PATH . 'override/core/files/css/base.php';
            require LASTUDIO_ELEMENTS_PATH . 'override/core/responsive/files/frontend.php';
            require LASTUDIO_ELEMENTS_PATH . 'override/core/responsive/responsive.php';
        }

    }
}

function lastudio_grab_shutdown_handler_if_has_elementor_error() {
    $last_error = error_get_last();
    if (isset ($last_error['type']) && $last_error['type'] == E_ERROR) {
        if(strpos($last_error['file'], 'wp-content/plugins/elementor/') !== false || strpos($last_error['file'], 'wp-content/plugins/lastudio-elements/') !== false){
            update_option('lastudio_has_elementor_error', 'has_error');
        }
    }
}
register_shutdown_function ('lastudio_grab_shutdown_handler_if_has_elementor_error');

add_action('lastudio_elementor_fix_error', function (){
    delete_option('lastudio_has_elementor_error');
});

add_action('admin_notices', 'lastudio_admin_notices_if_has_elementor_error');

function lastudio_admin_notices_if_has_elementor_error(){
    $has_error = get_option('lastudio_has_elementor_error', false);
    if(!empty($has_error)){
        $msg = sprintf('The latest version of %1$s is incompatible with %2$s plugin,<br/> Please downgrade %1$s plugin to the previous version, then reactivate %2$s plugin and wait for updated version of the theme', '<strong>Elementor</strong>', '<strong>LaStudio Elements For Elementor</strong>');
        echo sprintf('<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error la-has-elementor-error', $msg);
    }
}

/**
 * Enable white labeling setting form after re-activating the plugin
 *
 * @since 1.0.1
 * @modified 1.0.2.8
 * @return void
 */
function lastudio_elements_plugin_activation() {
    lastudio_elementor_recreate_editor_file();
    do_action('lastudio_elementor_fix_error');
}
register_activation_hook( __FILE__, 'lastudio_elements_plugin_activation' );

if(!function_exists('lastudio_elements_fix_elementor_override_core_file')){
    function lastudio_elements_fix_elementor_override_core_file(){
        $msg = [];
        $elementor_path = WP_PLUGIN_DIR . '/elementor/';
        $target_path = WP_PLUGIN_DIR . '/lastudio-elements/override/';
        $override = [
            'core-files-css-base' => [
                'source' => $elementor_path . 'core/files/css/base.php',
                'target' => $target_path . 'core/files/css/base.php',
                'find' => [
                    '->add_device( \'tablet\', $breakpoints[\'md\'] )',
                    '->add_device( \'desktop\', $breakpoints[\'lg\'] )'
                ],
                'replace' => [
                    '->add_device( \'width800\', $breakpoints[\'md\'] )->add_device( \'tablet\', $breakpoints[\'width800\'] )',
                    '->add_device( \'laptop\', $breakpoints[\'lg\'] )->add_device( \'desktop\', $breakpoints[\'xl\'] )'
                ]
            ],
            'core-responsive-files-frontend' => [
                'source' => $elementor_path . 'core/responsive/files/frontend.php',
                'target' => $target_path . 'core/responsive/files/frontend.php',
                'find' => [
                    'ELEMENTOR_SCREEN_([A-Z]+)_([A-Z]+)'
                ],
                'replace' => [
                    'ELEMENTOR_SCREEN_([A-Z0-9]+)_([A-Z]+)'
                ]
            ],
            'core-responsive-responsive' => [
                'source' => $elementor_path . 'core/responsive/responsive.php',
                'target' => $target_path . 'core/responsive/responsive.php',
                'find' => [
                    '\'xl\' => 1440,',
                    '\'xxl\' => 1600,'
                ],
                'replace' => [
                    '\'xl\' => 1600,',
                    '\'xxl\' => 1825,\'width800\' => 800,'
                ]
            ],
            'includes-base-controlsstack' => [
                'source' => $elementor_path . 'includes/base/controls-stack.php',
                'target' => $target_path . 'includes/base/controls-stack.php',
                'find' => [
                    'const RESPONSIVE_MOBILE = \'mobile\';',
                    'self::RESPONSIVE_MOBILE,'
                ],
                'replace' => [
                    'const RESPONSIVE_MOBILE = \'mobile\';const RESPONSIVE_LAPTOP = \'laptop\';const RESPONSIVE_WIDTH800 = \'width800\';',
                    'self::RESPONSIVE_MOBILE,self::RESPONSIVE_LAPTOP,self::RESPONSIVE_WIDTH800,'
                ]
            ]
        ];
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
            if(!defined('FS_METHOD')){
                define('FS_METHOD', 'direct');
            }
        }
        foreach ($override as $k => $file){
            $status = false;
            if($wp_filesystem->exists($file['source'])){
                $file_content = $wp_filesystem->get_contents($file['source']);
                $new_content = str_replace( $file['find'], $file['replace'], $file_content );
                if($wp_filesystem->put_contents($file['target'], $new_content)){
                    $status = true;
                }
            }
            $msg[$k] = $status;
        }

        $result = array_filter( $msg, function ( $val ) {
            return !$val;
        } );

        if(count($result) > 0){
            return false;
        }
        else{
            return true;
        }
    }
}

add_action('admin_menu', function (){
    add_submenu_page(
        'tools.php',
        esc_html__('Fix LaStudio Elements', 'lastudio-elements'),
        esc_html__('Fix LaStudio Elements', 'lastudio-elements'),
        'manage_options',
        'fix-lastudio-elements',
        'lastudio_elements_admin_menu_fix_cb'
    );
});

if(!function_exists('lastudio_elements_admin_menu_fix_cb')){
    function lastudio_elements_admin_menu_fix_cb(){
?>
        <div class="wrap">
            <br/>
            <h1><?php esc_html_e('Fix LaStudio Elements', 'la-studio') ?></h1>
            <br/>
            <?php
            if(isset($_POST['lasf_fix_elem'])){
                $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
                if(wp_verify_nonce($nonce, 'lastudio_elements_fix')){
                    $result = lastudio_elements_fix_elementor_override_core_file();
                    if($result){
                        do_action('lastudio_elementor_recreate_editor_file');
                        echo sprintf('<p>%s</p>', __('All done!, please re-active `LaStudio Elements For Elementor` plugin', 'lastudio-elements'));
                    }
                    else{
                        $msg = __('An error has occurred please contact support %s', 'lastudio-elements');
                        echo '<p>'. sprintf($msg, '<a href="https://support.la-studioweb.com/" target="_blank">https://support.la-studioweb.com/</a>') .'</p>';
                    }
                }
                else{
                    echo sprintf('<p>%s</p>', __('Invalid Nonce', 'lastudio-elements'));
                }
            }
            else{
                ?>
                <form method="post">
                    <button name="lasf_fix_elem" value="yes" id="lasf_fix_elem" class="button button-primary" type="submit"><?php esc_html_e('Fix now!', 'lastudio-elements') ?></button>
                    <?php wp_nonce_field('lastudio_elements_fix'); ?>
                </form>
                <?php
            }
            ?>
        </div>
        <?php
    }
}