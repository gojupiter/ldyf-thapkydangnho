<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * Popup settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function draven_options_section_popup( $sections ){
    $sections['popup'] = array(
        'name'          => 'popup_panel',
        'title'         => esc_html_x('Newsletter Popup', 'admin-view', 'draven'),
        'icon'          => 'fa fa-check',
        'fields'        => array(
            array(
                'id' => 'enable_newsletter_popup',
                'type' => 'switcher',
                'title' => esc_html_x('Enable Newsletter Popup', 'admin-view', 'draven'),
                'default' => false
            ),
            array(
                'id' => 'popup_max_width',
                'type' => 'text',
                'title' => esc_html_x("Popup Max Width", 'admin-view', 'draven'),
                'default' => 790,
                'dependency' => array('enable_newsletter_popup', '==', 'true')
            ),
            array(
                'id' => 'popup_max_height',
                'type' => 'text',
                'title' => esc_html_x("Popup Max Height", 'admin-view', 'draven'),
                'default' => 430,
                'dependency' => array('enable_newsletter_popup', '==', 'true')
            ),
            array(
                'id'        => 'popup_background',
                'type'      => 'background',
                'title'     => esc_html_x('Popup Background', 'admin-view', 'draven'),
                'dependency' => array('enable_newsletter_popup', '==', 'true')
            ),
            array(
                'id' => 'only_show_newsletter_popup_on_home_page',
                'type' => 'switcher',
                'title' => esc_html_x('Only showing on homepage', 'admin-view', 'draven'),
                'default' => false,
                'dependency' => array('enable_newsletter_popup', '==', 'true')
            ),
            array(
                'id' => 'disable_popup_on_mobile',
                'type' => 'switcher',
                'title' => esc_html_x("Don't show popup on mobile", 'admin-view', 'draven'),
                'default' => false,
                'dependency' => array('enable_newsletter_popup', '==', 'true')
            ),
            array(
                'id' => 'newsletter_popup_delay',
                'type' => 'text',
                'title' => esc_html_x('Popup showing after', 'admin-view', 'draven'),
                'info' => esc_html_x('Show Popup when site loaded after (number) seconds ( 1000ms = 1 second )', 'admin-view', 'draven'),
                'default' => '2000',
                'dependency' => array('enable_newsletter_popup', '==', 'true'),
            ),
            array(
                'id' => 'show_checkbox_hide_newsletter_popup',
                'type' => 'switcher',
                'title' => esc_html_x('Display option "Does not show popup again"', 'admin-view', 'draven'),
                'default' => false,
                'dependency' => array('enable_newsletter_popup', '==', 'true')
            ),
            array(
                'id' => 'popup_dont_show_text',
                'type' => 'text',
                'title' => esc_html_x('Text "Does not show popup again"', 'admin-view', 'draven'),
                'default' => 'Do not show popup anymore',
                'dependency' => array('enable_newsletter_popup|show_checkbox_hide_newsletter_popup', '==|==', 'true|true'),
            ),
            array(
                'id' => 'newsletter_popup_show_again',
                'type' => 'text',
                'title' => esc_html_x('Back display popup after', 'admin-view', 'draven'),
                'info' => esc_html_x('Enter number day', 'admin-view', 'draven'),
                'default' => '1',
                'dependency' => array('enable_newsletter_popup|show_checkbox_hide_newsletter_popup', '==|==', 'true|true'),
            ),
            array(
                'id' => 'newsletter_popup_content',
                'type' => 'wp_editor',
                'title' => esc_html_x('Newsletter Popup Content', 'admin-view', 'draven'),
                'dependency' => array('enable_newsletter_popup', '==', 'true'),
            )
        )
    );
    return $sections;
}
