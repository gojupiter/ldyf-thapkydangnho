<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * Additional code settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function draven_options_section_additional_code( $sections )
{
    $sections['additional_code'] = array(
        'name'          => 'additional_code_panel',
        'title'         => esc_html_x('Additional Code', 'admin-view', 'draven'),
        'icon'          => 'fa fa-code',
        'fields'        => array(
            array(
                'id'        => 'google_key',
                'type'      => 'text',
                'title'     => esc_html_x('Google Maps APIs Key', 'admin-view', 'draven'),
                'desc'      => esc_html_x('Type your Google Maps APIs Key here.', 'admin-view', 'draven')
            ),
            array(
                'id'        => 'la_custom_css',
                'type'      => 'code_editor',
                'editor_setting'    => array(
                    'type' => 'text/css',
                    'codemirror' => array(
                        'indentUnit' => 2,
                        'tabSize' => 2
                    )
                ),
                'wrap_class'=> 'hidden-on-customize',
                'class'     => 'la-customizer-section-large',
                'title'     => esc_html_x('Custom CSS', 'admin-view', 'draven'),
                'desc'      => esc_html_x('Paste your custom CSS code here.', 'admin-view', 'draven'),
            ),
            array(
                'id'        => 'header_js',
                'type'      => 'code_editor',
                'editor_setting'    => array(
                    'type' => 'text/javascript',
                    'codemirror' => array(
                        'indentUnit' => 2,
                        'tabSize' => 2
                    )
                ),
                'title'     => esc_html_x('Header Javascript Code', 'admin-view', 'draven'),
                'desc'      => esc_html_x('Paste your custom JS code here. The code will be added to the header of your site.', 'admin-view', 'draven')
            ),
            array(
                'id'        => 'footer_js',
                'type'      => 'code_editor',
                'editor_setting'    => array(
                    'type' => 'text/javascript',
                    'codemirror' => array(
                        'indentUnit' => 2,
                        'tabSize' => 2
                    )
                ),
                'title'     => esc_html_x('Footer Javascript Code', 'admin-view', 'draven'),
                'desc'     => esc_html_x('Paste your custom JS code here. The code will be added to the footer of your site.', 'admin-view', 'draven')
            )
        )
    );
    return $sections;
}