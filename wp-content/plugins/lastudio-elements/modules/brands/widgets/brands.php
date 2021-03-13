<?php
namespace Lastudio_Elements\Modules\Brands\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Brands Widget
 */
class Brands extends Lastudio_Widget {
    public function get_name() {
        return 'lastudio-brands';
    }

    protected function get_widget_title() {
        return esc_html__( 'Brands', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-2';
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_general',
            array(
                'label' => esc_html__( 'General', 'lastudio-elements' ),
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 4,
                'options' => lastudio_elements_tools()->get_select_range( 6 ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_brands',
            array(
                'label' => esc_html__( 'Brands', 'lastudio-elements' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Company Logo', 'lastudio-elements' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_name',
            array(
                'label'   => esc_html__( 'Company Name', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_desc',
            array(
                'label'   => esc_html__( 'Company Description', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_url',
            array(
                'label'   => esc_html__( 'Company URL', 'lastudio-elements' ),
                'type'    => Controls_Manager::URL,
                'default' => array(
                    'url' => '#',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'brands_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => array_values( $repeater->get_controls() ),
                'default'     => array(
                    array(
                        'item_image' => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_name'  => esc_html__( 'Company #1', 'lastudio-elements' ),
                        'item_url'   => array(
                            'url' => '#',
                        ),
                    ),
                    array(
                        'item_image' => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_name'  => esc_html__( 'Company #2', 'lastudio-elements' ),
                        'item_url'   => array(
                            'url' => '#',
                        ),
                    ),
                ),
                'title_field' => '{{{ item_name }}}',
            )
        );

        $this->end_controls_section();

        $css_scheme = apply_filters(
            'lastudio-elements/brands/css-scheme',
            array(
                'list'      => '.brands-list',
                'logo'      => '.brands-list .brands-list__item-img',
                'logo_wrap' => '.brands-list .brands-list__item-img-wrap',
                'name'      => '.brands-list .brands-list__item-name',
                'desc'      => '.brands-list .brands-list__item-desc',
            )
        );

        $this->start_controls_section(
            'section_brand_item_style',
            array(
                'label'      => esc_html__( 'Company Item', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'vertical_brands_alignment',
            array(
                'label'       => esc_html__( 'Vertical Alignment', 'lastudio-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-elements' ),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'lastudio-elements' ),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-elements' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['list'] => 'align-items: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_brand_logo_style',
            array(
                'label'      => esc_html__( 'Company Logo', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'logo_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['logo'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'logo_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['logo'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'logo_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['logo'],
            )
        );

        $this->add_responsive_control(
            'logo_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['logo_wrap'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'logo_wrap_style',
            array(
                'label'     => esc_html__( 'Logo Wrapper', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'logo_wrap_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['logo_wrap'],
            )
        );

        $this->add_responsive_control(
            'logo_wrap_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['logo_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'logo_wrap_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['logo_wrap'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'logo_wrap_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['logo_wrap'],
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_brand_title_style',
            array(
                'label'      => esc_html__( 'Company Name', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'  => esc_html__( 'Title Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} ' . $css_scheme['name'],
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_brand_desc_style',
            array(
                'label'      => esc_html__( 'Company Description', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'  => esc_html__( 'Title Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->add_responsive_control(
            'desc_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

    }

    protected function render() {

        $this->__context = 'render';

        $this->__open_wrap();
        include $this->__get_global_template( 'index' );
        $this->__close_wrap();
    }

    protected function _content_template() {

        $this->__context = 'edit';

        $this->__open_wrap();
        include $this->__get_global_template( 'index' );
        $this->__close_wrap();
    }

    public function __open_brand_link( $url_key ) {

        call_user_func( array( $this, sprintf( '__open_brand_link_%s', $this->__context ) ), $url_key );

    }

    public function __open_brand_link_format() {
        return '<a href="%1$s" class="brands-list__item-link"%2$s%3$s>';
    }

    public function __open_brand_link_render( $url_key ) {

        $item = $this->__processed_item;

        if ( empty( $item[ $url_key ]['url'] ) ) {
            return;
        }

        printf(
            $this->__open_brand_link_format(),
            $item[ $url_key ]['url'],
            ( ! empty( $item[ $url_key ]['is_external'] ) ? ' target="_blank"' : '' ),
            ( ! empty( $item[ $url_key ]['nofollow'] ) ? ' rel="nofollow"' : '' )
        );

    }

    public function __open_brand_link_edit( $url_key ) {

        echo '<# if ( item.' . $url_key . '.url ) { #>';
        printf(
            $this->__open_brand_link_format(),
            '{{{ item.' . $url_key . '.url }}}',
            '<# if ( item.' . $url_key . '.is_external ) { #> target="_blank"<# } #>',
            '<# if ( item.' . $url_key . '.nofollow ) { #> rel="nofollow"<# } #>'
        );
        echo '<# } #>';

    }

    public function __close_brand_link( $url_key ) {

        call_user_func( array( $this, sprintf( '__close_brand_link_%s', $this->__context ) ), $url_key );

    }

    public function __close_brand_link_render( $url_key ) {

        $item = $this->__processed_item;

        if ( empty( $item[ $url_key ]['url'] ) ) {
            return;
        }

        echo '</a>';

    }

    public function __close_brand_link_edit( $url_key ) {

        echo '<# if ( item.' . $url_key . '.url ) { #>';
        echo '</a>';
        echo '<# } #>';

    }
}