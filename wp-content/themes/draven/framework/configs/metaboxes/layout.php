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
function draven_metaboxes_section_layout( $sections )
{
    $sections['layout'] = array(
        'name'      => 'layout',
        'title'     => esc_html_x('Layout', 'admin-view', 'draven'),
        'icon'      => 'laicon-tools',
        'fields'    => array(
            array(
                'id'        => 'layout',
                'type'      => 'image_select',
                'title'     => esc_html_x('Layout', 'admin-view', 'draven'),
                'desc'      => esc_html_x('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'admin-view', 'draven'),
                'default'   => 'inherit',
                'radio'     => true,
                'options'   => Draven_Options::get_config_main_layout_opts(true, true)
            ),
            array(
                'id'        => 'small_layout',
                'type'      => 'radio',
                'class'     => 'la-radio-style elm-show-pagenow-post',
                'default'   => 'inherit',
                'title'     => esc_html_x('Enable Small Layout', 'admin-view', 'draven'),
                'dependency' => array('layout_col-1c', '==', 'true'),
                'options'   => array(
                    'inherit'        => esc_html_x('Inherit', 'admin-view', 'draven'),
                    'on'        => esc_html_x('On', 'admin-view', 'draven'),
                    'off'       => esc_html_x('Off', 'admin-view', 'draven')
                )
            ),
            array(
                'id'        => 'main_full_width',
                'type'      => 'radio',
                'class'     => 'la-radio-style',
                'default'   => 'inherit',
                'title'     => esc_html_x('100% Main Width', 'admin-view', 'draven'),
                'desc'      => esc_html_x('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'draven'),
                'options'   => Draven_Options::get_config_radio_opts()
            ),
            array(
                'id'            => 'main_space',
                'type'          => 'spacing',
                'title'         => esc_html_x('Custom Main Space', 'admin-view', 'draven'),
                'desc'          => esc_html_x('Leave empty if you not need', 'admin-view', 'draven'),
                'unit' 	        => 'px'
            ),
            array(
                'id'            => 'sidebar',
                'type'          => 'select',
                'title'         => esc_html_x('Override Sidebar', 'admin-view', 'draven'),
                'desc'          => esc_html_x('Select sidebar that will display on this page.', 'admin-view', 'draven'),
                'class'         => 'chosen',
                'options'       => 'sidebars',
                'default_option'=> esc_html_x('Inherit', 'admin-view', 'draven'),
                'dependency'    => array('layout_col-1c', '!=', '1')
            ),
            array(
                'id'        => 'body_class',
                'type'      => 'text',
                'title'     => esc_html_x('Custom Body CSS Class', 'admin-view', 'draven')
            ),
        )
    );
    return $sections;
}