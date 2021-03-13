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
function draven_metaboxes_section_footer( $sections )
{

    $footer_link = sprintf('<a href="%s">%s</a>', add_query_arg(array('post_type' => 'elementor_library', 'elementor_library_type' => 'footer'), admin_url('edit.php')), __('here', 'draven'));

    $sections['footer'] = array(
        'name'      => 'footer',
        'title'     => esc_html_x('Footer', 'admin-view', 'draven'),
        'icon'      => 'laicon-footer',
        'fields'    => array(
            array(
                'id'            => 'hide_footer',
                'type'          => 'radio',
                'default'       => 'no',
                'class'         => 'la-radio-style',
                'title'         => esc_html_x('Hide Footer', 'admin-view', 'draven'),
                'options'       => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'            => 'footer_layout',
                'type'          => 'select',
                'class'         => 'chosen',
                'default'       => '',
                'title'         => esc_html_x('Footer Layout', 'admin-view', 'draven'),
                'default_option' => esc_html_x('Select a layout', 'admin-view', 'draven'),
                'options'       => Draven_Options::get_config_footer_layout_opts(),
                'desc'          => sprintf( __('You can manage footer layout on %s', 'draven'), $footer_link ),
            )
        )
    );
    return $sections;
}