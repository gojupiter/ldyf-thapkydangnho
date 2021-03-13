<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * Page title bar settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */

function draven_options_section_page_title_bar_auto_detect( $key = 'default', $desc = '', $dependency = array() ){

    $base_options = array();

    $key_allows = array(
        'default' => array(
            'key' => ''
        ),
        'woocommerce' => array(
            'key' => 'woo_'
        ),
        'single_product' => array(
            'key' => 'single_product_'
        ),
        'single_post' => array(
            'key' => 'single_post_'
        )
    );

    if(!array_key_exists($key, $key_allows)){
        return $base_options;
    }

    $base_options = array(
        array(
            'id'            => $key_allows[$key]['key'] . 'page_title_font_size',
            'type'          => 'responsive',
            'title'         => esc_html_x('Page Title Font Size', 'admin-view', 'draven'),
            'info'          => esc_html_x('Enter the font size (e.g: 20px )', 'admin-view', 'draven'),
            'desc'          => $desc,
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_background',
            'type'      => 'background',
            'title'     => esc_html_x('Background', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_heading_color',
            'type'      => 'color_picker',
            'default'   => empty($dependency) ? Draven_Options::get_color_default('header_color') : '',
            'title'     => esc_html_x('Heading Color', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_text_color',
            'type'      => 'color_picker',
            'default'   => empty($dependency) ? Draven_Options::get_color_default('body_color') : '',
            'title'     => esc_html_x('Text Color', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_link_color',
            'type'      => 'color_picker',
            'default'   => empty($dependency) ? Draven_Options::get_color_default('body_color') : '',
            'title'     => esc_html_x('Link Color', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_link_hover_color',
            'type'      => 'color_picker',
            'default'   => empty($dependency) ? Draven_Options::get_color_default('primary_color') : '',
            'title'     => esc_html_x('Link Hover Color', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_spacing',
            'type'      => 'spacing',
            'title'     => esc_html_x('Spacing', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc,
            'unit' 	    => 'px',
            'default'   => array(
                'top' => empty($dependency) ? 40 : '',
                'bottom' => empty($dependency) ? 40 : ''
            )
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_spacing_desktop_small',
            'type'      => 'spacing',
            'title'     => esc_html_x('Spacing', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc . esc_html_x(' on Desktop Small', 'admin-view', 'draven'),
            'unit' 	    => 'px',
            'default'   => array(
                'top' => empty($dependency) ? 40 : '',
                'bottom' => empty($dependency) ? 40 : ''
            )
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_spacing_tablet',
            'type'      => 'spacing',
            'title'     => esc_html_x('Spacing', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc . esc_html_x(' on Tablet', 'admin-view', 'draven'),
            'unit' 	    => 'px',
            'default'   => array(
                'top' => empty($dependency) ? 25 : '',
                'bottom' => empty($dependency) ? 25 : ''
            )
        ),
        array(
            'id'        => $key_allows[$key]['key'] . 'page_title_bar_spacing_mobile',
            'type'      => 'spacing',
            'title'     => esc_html_x('Spacing', 'admin-view', 'draven'),
            'dependency'=> $dependency,
            'desc'      => $desc . esc_html_x(' on Mobile', 'admin-view', 'draven'),
            'unit' 	    => 'px',
            'default'   => array(
                'top' => empty($dependency) ? 25 : '',
                'bottom' => empty($dependency) ? 25 : ''
            )
        )
    );

    return $base_options;

}

function draven_options_section_page_title_bar( $sections ) {

    $page_title_layout = array(
        'hide' => esc_html_x('Do not display', 'admin-view', 'draven')
    );
    $page_title_layout = $page_title_layout + Draven_Options::get_config_page_title_bar_opts(false);

    $desc_prefix = array(
        'woocommerce' => esc_html_x('WooCommerce', 'admin-view', 'draven'),
        'single_product' => esc_html_x('Single Product', 'admin-view', 'draven'),
        'single_post' => esc_html_x('Single Post', 'admin-view', 'draven')
    );

    $desc_for_default           = esc_html_x('For page title bar', 'admin-view', 'draven');
    $desc_for_woo               = '[' . $desc_prefix['woocommerce'] . '] ' . $desc_for_default;
    $desc_for_single_post       = '[' . $desc_prefix['single_post'] . '] ' . $desc_for_default;
    $desc_for_single_product    = '[' . $desc_prefix['single_product'] . '] ' . $desc_for_default;

    $field_for_default = array_merge(
        array(
            array(
                'id'            => 'page_title_bar_layout',
                'type'          => 'select',
                'class'         => 'chosen',
                'title'         => esc_html_x('Select Layout', 'admin-view', 'draven'),
                'desc'          => $desc_for_default,
                'options'       => $page_title_layout
            ),
            array(
                'id'            => 'enable_page_title_subtext',
                'type'          => 'radio',
                'default'       => 'no',
                'class'         => 'la-radio-style',
                'title'         => esc_html_x('Replace breadcrumb by custom text', 'admin-view', 'draven'),
                'desc'          => $desc_for_default,
                'options'       => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'            => 'page_title_custom_subtext',
                'type'          => 'text',
                'title'         => esc_html_x('Custom Text', 'admin-view', 'draven'),
                'desc'          => $desc_for_default
            )
        ),
        draven_options_section_page_title_bar_auto_detect('default', $desc_for_default)
    );

    $child_sections = array();

    $child_sections[] = array(
        'name'      => 'page_title_bar_sections',
        'title'     => esc_html_x('Global Page Title', 'admin-view', 'draven'),
        'icon'      => 'fa fa-cog',
        'fields'    => $field_for_default
    );

    /** FOR WOOCOMMERCE */
    $field_for_woo = array_merge(
        array(
            array(
                'id'            => 'woo_override_page_title_bar',
                'type'          => 'radio',
                'class'         => 'la-radio-style',
                'default'       => 'off',
                'title'         => esc_html_x('Enable Override', 'admin-view', 'draven'),
                'desc'          => esc_html_x('Turn on to override all setting page title bar of WooCommerce Settings ( Shop page / Product Category / Product Tags and Search page )', 'admin-view', 'draven'),
                'info'          => esc_html_x('This option will not work with these pages were overwritten', 'admin-view', 'draven'),
                'options'       => Draven_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'archive_product_hide_breadcrumb',
                'type'      => 'radio',
                'class'     => 'la-radio-style',
                'default'   => 'no',
                'title'     => esc_html__('Hide Breadcrumbs', 'draven'),
                'desc'      => $desc_for_woo,
                'options'   => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'archive_product_hide_page_title',
                'type'      => 'radio',
                'class'     => 'la-radio-style',
                'default'   => 'no',
                'title'     => esc_html__('Hide Page Title', 'draven'),
                'desc'      => $desc_for_woo,
                'options'   => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'            => 'woo_page_title_bar_layout',
                'type'          => 'select',
                'class'         => 'chosen',
                'title'         => esc_html_x('Page Title Bar Layout', 'admin-view', 'draven'),
                'desc'          => '[' . $desc_prefix['woocommerce'] . ']',
                'options'       => $page_title_layout,
                'dependency'    => array('woo_override_page_title_bar_on', '==', 'true')
            )
        ),
        draven_options_section_page_title_bar_auto_detect(
            'woocommerce',
            $desc_for_woo,
            array('woo_override_page_title_bar_on|woo_page_title_bar_layout', '==|!=', 'true|hide')
        )
    );

    if(function_exists('WC')) {

        $child_sections[] = array(
            'name' => 'page_title_bar_woo_sections',
            'title' => $desc_prefix['woocommerce'],
            'icon' => 'fa fa-cog',
            'fields' => $field_for_woo
        );
    }

    /** FOR SINGLE POST */
    $field_for_single_post = array_merge(
        array(
            array(
                'id'            => 'single_post_override_page_title_bar',
                'type'          => 'radio',
                'class'         => 'la-radio-style',
                'default'       => 'off',
                'title'         => esc_html_x('Enable Override', 'admin-view', 'draven'),
                'desc'          => esc_html_x('Turn on to override all setting page title bar of Post pages', 'admin-view', 'draven'),
                'info'          => esc_html_x('This option will not work with these pages were overwritten', 'admin-view', 'draven'),
                'options'       => Draven_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'post_single_hide_breadcrumb',
                'type'      => 'radio',
                'class'     => 'la-radio-style',
                'default'   => 'no',
                'title'     => esc_html__('Hide Breadcrumbs', 'draven'),
                'desc'      => $desc_for_single_post,
                'options'   => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'post_single_hide_page_title',
                'type'      => 'radio',
                'class'     => 'la-radio-style',
                'default'   => 'no',
                'title'     => esc_html__('Hide Page Title', 'draven'),
                'desc'      => $desc_for_single_post,
                'options'   => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'            => 'single_post_page_title_bar_layout',
                'type'          => 'select',
                'class'         => 'chosen',
                'title'         => esc_html_x('Page Title Bar Layout', 'admin-view', 'draven'),
                'options'       => $page_title_layout,
                'desc'          => '[' . $desc_prefix['single_post'] . ']',
                'dependency'    => array('single_post_override_page_title_bar_on', '==', 'true')
            )
        ),
        draven_options_section_page_title_bar_auto_detect(
            'single_post',
            $desc_for_single_post,
            array('single_post_override_page_title_bar_on|single_post_page_title_bar_layout', '==|!=', 'true|hide')
        )
    );
    $child_sections[] = array(
        'name'      => 'page_title_bar_single_post_sections',
        'title'     => $desc_prefix['single_post'],
        'icon'      => 'fa fa-cog',
        'fields'    => $field_for_single_post
    );

    /** FOR SINGLE PRODUCT */
    $field_for_single_product = array_merge(
        array(
            array(
                'id'            => 'single_product_override_page_title_bar',
                'type'          => 'radio',
                'class'         => 'la-radio-style',
                'default'       => 'off',
                'title'         => esc_html_x('Enable Override', 'admin-view', 'draven'),
                'desc'          => esc_html_x('Turn on to override all setting page title bar of Single Product', 'admin-view', 'draven'),
                'info'          => esc_html_x('This option will not work with these pages were overwritten', 'admin-view', 'draven'),
                'options'       => Draven_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'product_single_hide_breadcrumb',
                'type'      => 'radio',
                'class'     => 'la-radio-style',
                'default'   => 'no',
                'title'     => esc_html__('Hide Breadcrumbs', 'draven'),
                'desc'      => $desc_for_single_product,
                'options'   => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'product_single_hide_page_title',
                'type'      => 'radio',
                'class'     => 'la-radio-style',
                'default'   => 'no',
                'title'     => esc_html__('Hide Page Title', 'draven'),
                'desc'      => $desc_for_single_product,
                'options'   => Draven_Options::get_config_radio_opts(false)
            ),
            array(
                'id'            => 'single_product_page_title_bar_layout',
                'type'          => 'select',
                'class'         => 'chosen',
                'title'         => esc_html_x('Page Title Bar Layout', 'admin-view', 'draven'),
                'options'       => $page_title_layout,
                'desc'          => '[' . $desc_prefix['single_product'] . ']',
                'dependency'    => array('single_product_override_page_title_bar_on', '==', 'true')
            )
        ),
        draven_options_section_page_title_bar_auto_detect(
            'single_product',
            $desc_for_single_product,
            array('single_product_override_page_title_bar_on|single_product_page_title_bar_layout', '==|!=', 'true|hide')
        )
    );
    $child_sections[] = array(
        'name'      => 'page_title_bar_single_product_sections',
        'title'     => $desc_prefix['single_product'],
        'icon'      => 'fa fa-cog',
        'fields'    => $field_for_single_product
    );


    /** END  */

    $sections['page_title_bar'] = array(
        'name'          => 'page_title_bar_panel',
        'title'         => esc_html_x('Page Title Bar', 'admin-view', 'draven'),
        'icon'          => 'fa fa-sliders',
        'sections'      => $child_sections
    );


    return $sections;
}