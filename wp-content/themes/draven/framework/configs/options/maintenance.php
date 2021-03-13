<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * Blog settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function draven_options_section_maintenance( $sections )
{
    $sections['maintenance'] = array(
        'name' => 'maintenance_panel',
        'title' => esc_html_x('Maintenance', 'admin-view', 'draven'),
        'icon' => 'fa fa-lock',
        'fields' => array(
            array(
                'id'        => 'enable_maintenance',
                'type'      => 'radio',
                'default'   => 'no',
                'class'     => 'la-radio-style',
                'title'     => esc_html_x('Enable Maintenance Mode', 'admin-view', 'draven'),
                'desc'      => esc_html_x('Turn on to make your website to be private', 'admin-view', 'draven'),
                'options'   => array(
                    'no'    => esc_html_x('No', 'admin-view', 'draven'),
                    'yes'   => esc_html_x('Yes', 'admin-view', 'draven')
                )
            ),
            array(
                'id'        => 'maintenance_page',
                'type'      => 'select',
                'title'     => esc_html_x('Maintenance Page', 'admin-view', 'draven'),
                'options'   => 'pages',
                'desc'      => esc_html_x('If you do not select a page, it will be redirected to the login page', 'admin-view', 'draven'),
                'query_args'    => array(
                    'posts_per_page'  => -1
                ),
                'default_option' => esc_html_x('Select a page', 'admin-view', 'draven'),
                'dependency'   => array( 'enable_maintenance_yes', '==', 'true' )
            )
        )
    );
    return $sections;
}