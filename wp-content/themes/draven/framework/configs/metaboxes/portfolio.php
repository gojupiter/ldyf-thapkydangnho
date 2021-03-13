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
function draven_metaboxes_section_portfolio( $sections )
{
    $sections['portfolio'] = array(
        'name'      => 'portfolio',
        'title'     => esc_html_x('Portfolio', 'admin-view', 'draven'),
        'icon'      => 'laicon-file',
        'fields'    => array(
            array(
                'id'        => 'short_description',
                'type'      => 'textarea',
                'title'     => esc_html_x('Short Description', 'admin-view', 'draven')
            ),
            array(
                'id'        => 'portfolio_design',
                'type'      => 'select',
                'title'     => esc_html_x('Portfolio Single Design', 'admin-view', 'draven'),
                'options'    => array(
                    'inherit' => esc_html_x('Inherit', 'admin-view', 'draven'),
                    '1' => esc_html_x('Design 01', 'admin-view', 'draven'),
                    '2' => esc_html_x('Design 02', 'admin-view', 'draven'),
                    'use_vc' => esc_html_x('Show only post content', 'admin-view', 'draven')
                )
            ),
            array(
                'id'        => 'gallery',
                'type'      => 'gallery',
                'title'     => esc_html_x('Gallery', 'admin-view', 'draven')
            ),
            array(
                'id'        => 'client',
                'type'      => 'text',
                'title'     => esc_html_x('Client Name', 'admin-view', 'draven')
            ),
            array(
                'id'        => 'timeline',
                'type'      => 'text',
                'title'     => esc_html_x('Timeline', 'admin-view', 'draven')
            ),
            array(
                'id'        => 'location',
                'type'      => 'text',
                'title'     => esc_html_x('Location', 'admin-view', 'draven')
            ),
            array(
                'id'        => 'website',
                'type'      => 'text',
                'title'     => esc_html_x('Website', 'admin-view', 'draven')
            )
        )
    );
    return $sections;
}