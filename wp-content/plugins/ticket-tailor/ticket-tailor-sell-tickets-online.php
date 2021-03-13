<?php
/*
Plugin Name: Ticket Tailor - Sell Tickets Online
Plugin URI: http://www.tickettailor.com/
Description: Embed your Ticket Tailor box office to sell tickets online via your Wordpress website.
Author: Zimma Ltd.
Version: 1.9
Author URI: http://www.tickettailor.com/
*/

register_activation_hook(__FILE__, 'tickettailor_activate');
add_action('admin_init', 'tickettailor_redirect');

function tickettailor_activate() {
    add_option('tickettailor_do_activation_redirect', true);
}

function tickettailor_redirect() {
    if (get_option('tickettailor_do_activation_redirect', false)) {
        delete_option('tickettailor_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("options-general.php?page=ticket-tailor-box-office");
        }
    }
}

add_shortcode('tt-event', 'tt_event_load');

function tt_event_load($atts) {

    if(!isset($atts['url'])) return "<p>Error: No URL set for Ticket Tailor widget</p>";

    $url = $atts['url'];
    $minimal = false;
    $bg_fill = true;
    $show_logo = true;
    $inherit_ref_from_url_param = '';
    $ref = 'website_widget';

    if(isset($atts['minimal'])) $minimal = $atts['minimal'];
    if(isset($atts['bg_fill'])) $bg_fill = $atts['bg_fill'];
    if(isset($atts['inherit_ref_from_url_param'])) $inherit_ref_from_url_param = $atts['inherit_ref_from_url_param'];
    if(isset($atts['ref'])) $ref = $atts['ref'];

    return '
      <div class="tt-widget">
        <script src="https://cdn.tickettailor.com/js/widgets/min/widget.js"
          data-url="'.$url.'"
          data-type="inline"
          data-inline-minimal="'.$minimal.'"
          data-inline-show-logo="'.$show_logo.'"
          data-inline-bg-fill="'.$bg_fill.'"
          data-inline-inherit-ref-from-url-param="'.$inherit_ref_from_url_param.'"
          data-inline-ref="'.$ref.'">
        </script>
      </div>
    ';
}


add_action( 'admin_menu', 'ticket_tailor_box_office_menu' );

function ticket_tailor_box_office_menu() {
    add_options_page( 'Ticket Tailor Box Office Options', 'Ticket Tailor Box Office', 'manage_options', 'ticket-tailor-box-office', 'ticket_tailor_box_office_options' );
}


function ticket_tailor_box_office_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    ?>
    <div class="wrap">
    <h2>Ticket Tailor Box Office Plugin</h2>
    <p>The Ticket Tailor Box Office Plugin allows you to sell tickets directly from your Wordpress site by embedding our booking widgets in to your pages.</p>
    <p>To use this you will need a Ticket Tailor account. If you don't have one, <a href="http://www.tickettailor.com/?rf=wp_plugin">sign up for one here</a>.</p>
    <h3>Add a booking widget to your blog posts</h3>
    <p>Simply add a code snippet to your blog post. To get the code snippet, follow these steps:
     <ol>
        <li><a href="https://www.tickettailor.com/login/" target="_blank">login to your Ticket Tailor account</a>,</li>
        <li>click <strong>Box office setup</strong>,</li>
        <li>click <strong>Promote</strong> from the top nav,</li>
        <li>click <strong>Website embed codes</strong> from the side nav,</li>
        <li>under the heading <strong>Set up a booking widget</strong></li>
        <li>choose the widgets options you want and check it looks as you want it to in the preview,</li>
        <li>click the <strong>Get the Wordpress code</strong> link and copy the code,</li>
        <li>paste the code in to your Wordpress blog post where you want it to appear.</li>
     </ol></p>
    </div>
<?php
}
