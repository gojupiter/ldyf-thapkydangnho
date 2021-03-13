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
function draven_metaboxes_section_member( $sections )
{
    $sections['member'] = array(
        'name'      => 'member',
        'title'     => esc_html_x('Member Information', 'admin-view', 'draven'),
        'icon'      => 'laicon-file',
        'fields'    => array(
            array(
                'id'    => 'role',
                'type'  => 'text',
                'title' => esc_html_x('Role', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'facebook',
                'type'  => 'text',
                'title' => esc_html_x('Facebook URL', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'twitter',
                'type'  => 'text',
                'title' => esc_html_x('Twitter URL', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'pinterest',
                'type'  => 'text',
                'title' => esc_html_x('Pinterest URL', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'linkedin',
                'type'  => 'text',
                'title' => esc_html_x('LinkedIn URL', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'dribbble',
                'type'  => 'text',
                'title' => esc_html_x('Dribbble URL', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'google_plus',
                'type'  => 'text',
                'title' => esc_html_x('Google Plus URL', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'youtube',
                'type'  => 'text',
                'title' => esc_html_x('Youtube URL', 'admin-view', 'draven'),
            ),
            array(
                'id'    => 'email',
                'type'  => 'text',
                'title' => esc_html_x('Email Address', 'admin-view', 'draven'),
            )
        )
    );
    return $sections;
}