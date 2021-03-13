<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}


/**
 * Header settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function draven_options_section_header($sections)
{
    $header_opts = array();

    if(class_exists('LAHFB')){
        $header_opts[] = array(
            'type'    => 'content',
            'class'   => 'info',
            'content' => sprintf(
                '<a class="button button-primary" href="%s"><i class="dashicons dashicons-external"></i>%s</a>',
                admin_url('themes.php?page=lastudio_header_builder_setting'),
                esc_html__('Open Header Builder', 'draven')
            )
        );
    }

    $header_opts[] = array(
        'id' => 'header_transparency',
        'type' => 'radio',
        'default' => 'no',
        'class' => 'la-radio-style',
        'title' => esc_html_x('Header Transparency', 'admin-view', 'draven'),
        'options' => Draven_Options::get_config_radio_opts(false)
    );

    $header_opts[] = array(
        'id' => 'header_sticky',
        'type' => 'radio',
        'default' => 'no',
        'class' => 'la-radio-style',
        'title' => esc_html_x('Enable Header Sticky', 'admin-view', 'draven'),
        'options' => array(
            'no' => esc_html_x('Disable', 'admin-view', 'draven'),
            'auto' => esc_html_x('Activate when scroll up', 'admin-view', 'draven'),
            'yes' => esc_html_x('Activate when scroll up & down', 'admin-view', 'draven')
        )
    );

    $sections['header'] = array(
        'name' => 'header_panel',
        'title' => esc_html_x('Header', 'admin-view', 'draven'),
        'icon' => 'fa fa-arrow-up',
        'sections' => array(
            array(
                'name' => 'header_layout_sections',
                'title' => esc_html_x('General', 'admin-view', 'draven'),
                'icon' => 'fa fa-cog',
                'fields' => $header_opts
            ),

            array(
                'name' => 'header_mobile_styling_sections',
                'title' => esc_html_x('Mobile Footer Bar', 'admin-view', 'draven'),
                'icon' => 'fa fa-paint-brush',
                'fields' => array(
                    array(
                        'id' => 'enable_header_mb_footer_bar',
                        'type' => 'radio',
                        'default' => 'no',
                        'class' => 'la-radio-style',
                        'title' => esc_html_x('Enable Mobile Footer Bar?', 'admin-view', 'draven'),
                        'options' => array(
                            'no' => esc_html_x('Hide', 'admin-view', 'draven'),
                            'yes' => esc_html_x('Yes', 'admin-view', 'draven')
                        )
                    ),

                    array(
                        'id' => 'header_mb_footer_bar_component',
                        'type' => 'group',
                        'wrap_class' => 'group-disable-clone',
                        'title' => esc_html_x('Header Mobile Footer Bar Component', 'admin-view', 'draven'),
                        'button_title' => esc_html_x('Add Icon Component ', 'admin-view', 'draven'),
                        'dependency' => array('enable_header_mb_footer_bar_yes', '==', true),
                        'accordion_title' => 'type',
                        'max_item' => 4,
                        'fields' => array(
                            array(
                                'id' => 'type',
                                'type' => 'select',
                                'title' => esc_html_x('Type', 'admin-view', 'draven'),
                                'options' => array(
                                    'dropdown_menu' => esc_html_x('Dropdown Menu', 'admin-view', 'draven'),
                                    'text' => esc_html_x('Custom Text', 'admin-view', 'draven'),
                                    'link_icon' => esc_html_x('Icon with link', 'admin-view', 'draven'),
                                    'search_1' => esc_html_x('Search box style 01', 'admin-view', 'draven'),
                                    'cart' => esc_html_x('Cart Icon', 'admin-view', 'draven'),
                                    'wishlist' => esc_html_x('Wishlist Icon', 'admin-view', 'draven'),
                                    'compare' => esc_html_x('Compare Icon', 'admin-view', 'draven')
                                )
                            ),
                            array(
                                'id' => 'icon',
                                'type' => 'icon',
                                'default' => 'fa fa-share',
                                'title' => esc_html_x('Custom Icon', 'admin-view', 'draven'),
                                'dependency' => array('type', '!=', 'search_1|primary_menu')
                            ),
                            array(
                                'id' => 'text',
                                'type' => 'text',
                                'title' => esc_html_x('Custom Text', 'admin-view', 'draven'),
                                'dependency' => array('type', 'any', 'text,link_text')
                            ),
                            array(
                                'id' => 'link',
                                'type' => 'text',
                                'default' => '#',
                                'title' => esc_html_x('Link (URL)', 'admin-view', 'draven'),
                                'dependency' => array('type', '!=', 'search_1|primary_menu')
                            ),
                            array(
                                'id' => 'menu_id',
                                'type' => 'select',
                                'title' => esc_html_x('Select the menu', 'admin-view', 'draven'),
                                'class' => 'chosen',
                                'options' => 'tags',
                                'query_args' => array(
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'taxonomies' => 'nav_menu',
                                    'hide_empty' => false
                                ),
                                'dependency' => array('type', '==', 'dropdown_menu')
                            ),
                            array(
                                'id' => 'el_class',
                                'type' => 'text',
                                'default' => '',
                                'title' => esc_html_x('Extra CSS class for item', 'admin-view', 'draven')
                            )
                        )
                    ),
                    array(
                        'id' => 'enable_header_mb_footer_bar_sticky',
                        'type' => 'radio',
                        'default' => 'always',
                        'class' => 'la-radio-style',
                        'title' => esc_html_x('Header Mobile Footer Bar Sticky', 'admin-view', 'draven'),
                        'dependency' => array('enable_header_mb_footer_bar_yes', '==', true),
                        'options' => array(
                            'always' => esc_html_x('Always Display', 'admin-view', 'draven'),
                            'up' => esc_html_x('Display when scroll up', 'admin-view', 'draven'),
                            'down' => esc_html_x('Display when scroll down', 'admin-view', 'draven')
                        )
                    )
                )
            )
        )
    );
    return $sections;
}