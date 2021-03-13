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
function draven_metaboxes_section_product( $sections )
{
    $sections['product'] = array(
        'name'      => 'product',
        'title'     => esc_html_x('Product', 'admin-view', 'draven'),
        'icon'      => 'laicon-file',
        'fields'    => array(
            array(
                'id'                => 'product_video_url',
                'type'              => 'text',
                'title'             => esc_html_x('Product Video URL', 'admin-view', 'draven')
            ),
            array(
                'id'                => 'product_badges',
                'type'              => 'group',
                'wrap_class'        => 'group-disable-clone',
                'title'             => esc_html_x('Custom Badges', 'admin-view', 'draven'),
                'button_title'      => esc_html_x('Add Badge','admin-view', 'draven'),
                'accordion_title'   => 'text',
                'max_item'          => 3,
                'fields'            => array(
                    array(
                        'id'            => 'text',
                        'type'          => 'text',
                        'default'       => 'New',
                        'title'         => esc_html_x('Badge Text', 'admin-view', 'draven')
                    ),
                    array(
                        'id'            => 'bg',
                        'type'          => 'color_picker',
                        'default'       => '',
                        'title'         => esc_html_x('Custom Badge Background Color', 'admin-view', 'draven')
                    ),
                    array(
                        'id'            => 'color',
                        'type'          => 'color_picker',
                        'default'       => '',
                        'title'         => esc_html_x('Custom Badge Text Color', 'admin-view', 'draven')
                    ),
                    array(
                        'id'            => 'el_class',
                        'type'          => 'text',
                        'default'       => '',
                        'title'         => esc_html_x('Extra CSS class for badge', 'admin-view', 'draven')
                    )
                )
            ),
        )
    );
    return $sections;
}