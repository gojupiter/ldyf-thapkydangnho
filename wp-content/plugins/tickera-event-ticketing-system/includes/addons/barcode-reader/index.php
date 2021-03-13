<?php

/*
Plugin Name: Tickera - Barcode Reader Add-on
Plugin URI: http://tickera.com/
Description: Add Barcode Reader support to Tickera plugin
Author: Tickera.com
Author URI: http://tickera.com/
Version: 1.3
Text Domain: bcr
Domain Path: /languages/

Copyright 2020 Tickera (http://tickera.com/)
*/

if (!defined('ABSPATH'))
exit; // Exit if accessed directly

if (!class_exists('TC_Barcode_Reader_Core')) {

  class TC_Barcode_Reader_Core {

    var $version = '1.3';
    var $title = 'Barcode Reader';
    var $name = 'tc_barcode_reader';
    var $dir_name = 'barcode-reader';
    var $location = 'plugins';
    var $plugin_dir = '';
    var $plugin_url = '';

    function __construct() {


      if (class_exists('TC')) {//Check if Tickera plugin is active / main Tickera class exists
        global $tc;

        //$this->init_vars();

        $this->plugin_dir = $tc->plugin_dir. 'includes/addons/' . $this->dir_name . '/';
        $this->plugin_url = plugins_url('/', __FILE__);

        add_filter('tc_admin_capabilities', array($this, 'append_capabilities'));
        add_filter('tc_staff_capabilities', array($this, 'append_capabilities'));
        add_action($tc->name . '_add_menu_items_after_ticket_templates', array($this, 'add_admin_menu_item_to_tc'));
        add_action('admin_enqueue_scripts', array($this, 'admin_header'));
        add_action('wp_ajax_check_in_barcode', array($this, 'check_in_barcode'));
        add_action('wp_ajax_nopriv_check_in_barcode', array($this, 'check_in_barcode'));
      }
    }

    function check_in_barcode() {//Waiting for ajax calls to check barcode
      if (isset($_POST['api_key']) && isset($_POST['barcode']) && defined('DOING_AJAX') && DOING_AJAX) {

        $api_key = new TC_API_Key($_POST['api_key']);
        $checkin = new TC_Checkin_API($api_key->details->api_key, apply_filters('tc_checkin_request_name', 'tickera_scan'), 'return', $_POST['barcode'], false);
        $checkin_result = $checkin->ticket_checkin(false);
        if (is_numeric($checkin_result) && $checkin_result == 403) {//permissions issue
          echo $checkin_result;
          exit;
        } else {
          if ($checkin_result['status'] == 1) {//success
            echo 1;
            exit;
          } else {//fail
            echo 2;
            exit;
          }
        }
      }
    }

    function append_capabilities($capabilities) {//Add additional capabilities to staff and admins
      $capabilities['manage_' . $this->name . '_cap'] = 1;
      return $capabilities;
    }

    function add_admin_menu_item_to_tc() {//Add additional menu item under Tickera admin menu
      global $first_tc_menu_handler;
      $handler = 'ticket_templates';
      add_submenu_page($first_tc_menu_handler, __($this->title, 'tc'), __($this->title, 'tc'), 'manage_' . $this->name . '_cap', $this->name, $this->name . '_admin_core');
      eval("function " . $this->name . "_admin_core() {require_once( '" . $this->plugin_dir . "/includes/admin-pages/" . $this->name . ".php');}");
      do_action($this->name . '_add_menu_items_after_' . $handler);
    }

    function admin_header() {//Add scripts and CSS for the plugin
      wp_enqueue_script($this->name . '-admin', $this->plugin_url . 'js/admin.js', array('jquery'), false, false);
      wp_localize_script($this->name . '-admin', 'tc_barcode_reader_vars', array(
        'admin_ajax_url' => admin_url('admin-ajax.php'),
        'message_barcode_default' => __('Select input field and scan a barcode located on the ticket.', 'tc'),
        'message_checking_in' => __('Checking in...', 'tc'),
        'message_insufficient_permissions' => __('Insufficient permissions. This API key cannot check in this ticket.', 'tc'),
        'message_barcode_status_error' => __('Ticket code is wrong or expired.', 'tc'),
        'message_barcode_status_success' => __('Ticket has been successfully checked in.', 'tc'),
        'message_barcode_status_error_exists' => __('Ticket does not exist.', 'tc'),
        'message_barcode_api_key_not_selected' => sprintf(__('Please create and select an %s in order to check in the ticket.', 'tc'), '<a href="' . admin_url('admin.php?page=tc_settings&tab=api') . '">' . __('API Key', 'tc') . '</a>'),
        'message_barcode_cannot_be_empty' => __('Ticket code cannot be empty', 'tc'),
      ));
      wp_enqueue_style($this->name . '-admin', $this->plugin_url . 'css/admin.css', array(), $this->version);
    }

  }

}

if (!function_exists('is_plugin_active_for_network')) {
  require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

$tc_barcode_reader_core = new TC_Barcode_Reader_Core();
?>
