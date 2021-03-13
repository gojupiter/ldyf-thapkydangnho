<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * MetaBox
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function draven_metaboxes_section_header( $sections )
{
    $sections['header'] = array(
        'name'      => 'header',
        'title'     => esc_html_x('Header', 'admin-view', 'draven'),
        'icon'      => 'laicon-header',
        'fields'    => array(
            array(
                'id'            => 'hide_header',
                'type'          => 'radio',
                'default'       => 'no',
                'class'         => 'la-radio-style',
                'title'         => esc_html_x('Hide header', 'admin-view', 'draven'),
                'options'       => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'            => 'header_layout',
                'type'          => 'select',
                'class'         => 'chosen',
                'title'         => esc_html_x('Header Layout', 'admin-view', 'draven'),
                'desc'          => esc_html_x('Controls the layout of the header.', 'admin-view', 'draven'),
                'default'       => 'inherit',
                'options'       => Draven_Options::get_config_header_layout_opts(false, true),
                'dependency'    => array( 'hide_header_no', '==', 'true' )
            ),
            array(
                'id'            => 'header_transparency',
                'type'          => 'radio',
                'default'       => 'inherit',
                'class'         => 'la-radio-style',
                'title'         => esc_html_x('Enable Header Transparency', 'admin-view', 'draven'),
                'options'       => Draven_Options::get_config_radio_opts(),
                'dependency'    => array( 'hide_header_no', '==', 'true' )
            ),
            array(
                'id'            => 'header_sticky',
                'type'          => 'radio',
                'default'       => 'inherit',
                'class'         => 'la-radio-style',
                'title'         => esc_html_x('Enable Header Sticky', 'admin-view', 'draven'),
                'options'       => array(
                    'inherit'   => esc_html_x('Inherit', 'admin-view', 'draven'),
                    'no'        => esc_html_x('Disable', 'admin-view', 'draven'),
                    'auto'      => esc_html_x('Activate when scroll up', 'admin-view', 'draven'),
                    'yes'       => esc_html_x('Activate when scroll up & down', 'admin-view', 'draven')
                ),
                'dependency'    => array( 'hide_header_no', '==', 'true' )
            )
        )
    );
    return $sections;
}