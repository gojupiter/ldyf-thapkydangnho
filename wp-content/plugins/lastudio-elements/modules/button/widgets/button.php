<?php
namespace Lastudio_Elements\Modules\Button\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Button Widget
 */
class Button extends Lastudio_Widget {

    public function get_name() {
        return 'lastudio-button';
    }

    protected function get_widget_title() {
        return esc_html__( 'Button', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-28';
    }

    protected function _register_controls() {

        $css_scheme = apply_filters(
            'lastudio-elements/button/css-scheme',
            array(
                'container'    => '.lastudio-button__container',
                'button'       => '.lastudio-button__instance',
                'plane_normal' => '.lastudio-button__plane-normal',
                'plane_hover'  => '.lastudio-button__plane-hover',
                'state_normal' => '.lastudio-button__state-normal',
                'state_hover'  => '.lastudio-button__state-hover',
                'icon_normal'  => '.lastudio-button__state-normal .lastudio-button__icon',
                'label_normal' => '.lastudio-button__state-normal .lastudio-button__label',
                'icon_hover'   => '.lastudio-button__state-hover .lastudio-button__icon',
                'label_hover'  => '.lastudio-button__state-hover .lastudio-button__label',
            )
        );

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'lastudio-elements' ),
            )
        );

        $this->start_controls_tabs( 'tabs_button_content' );

        $this->start_controls_tab(
            'tab_button_content_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'button_icon_normal',
            array(
                'label'       => esc_html__( 'Button Icon', 'lastudio-elements' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'file'        => '',
                'default'     => 'fa fa-circle-o',
            )
        );

        $this->add_control(
            'button_label_normal',
            array(
                'label'       => esc_html__( 'Button Label Text', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Click Me', 'lastudio-elements' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_content_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'button_icon_hover',
            array(
                'label'       => esc_html__( 'Button Icon', 'lastudio-elements' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'file'        => '',
                'default'     => 'fa fa-check-circle-o',
            )
        );

        $this->add_control(
            'button_label_hover',
            array(
                'label'       => esc_html__( 'Button Label Text', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Go', 'lastudio-elements' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_url',
            array(
                'label' => esc_html__( 'Button Link', 'lastudio-elements' ),
                'type' => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'default' => array(
                    'url' => '#',
                ),
                'separator' => 'before',
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-elements' ),
            )
        );

        $effects = apply_filters(
            'lastudio-elements/button/effects',
            array(
                'effect-0'  => esc_html__( 'None', 'lastudio-elements' ),
                'effect-1'  => esc_html__( 'Fade', 'lastudio-elements' ),
                'effect-2'  => esc_html__( 'Up Slide', 'lastudio-elements' ),
                'effect-3'  => esc_html__( 'Down Slide', 'lastudio-elements' ),
                'effect-4'  => esc_html__( 'Right Slide', 'lastudio-elements' ),
                'effect-5'  => esc_html__( 'Left Slide', 'lastudio-elements' ),
                'effect-6'  => esc_html__( 'Up Scale', 'lastudio-elements' ),
                'effect-7'  => esc_html__( 'Down Scale', 'lastudio-elements' ),
                'effect-8'  => esc_html__( 'Top Diagonal Slide', 'lastudio-elements' ),
                'effect-9'  => esc_html__( 'Bottom Diagonal Slide', 'lastudio-elements' ),
                'effect-10' => esc_html__( 'Right Rayen', 'lastudio-elements' ),
                'effect-11' => esc_html__( 'Left Rayen', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'hover_effect',
            array(
                'label'   => esc_html__( 'Hover Effect', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'effect-0',
                'options' => $effects,
            )
        );

        $this->add_control(
            'use_button_icon',
            array(
                'label'        => esc_html__( 'Use Icon?', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'button_icon_position',
            array(
                'label'   => esc_html__( 'Icon Position', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'left'   => esc_html__( 'Left', 'lastudio-elements' ),
                    'top'    => esc_html__( 'Top', 'lastudio-elements' ),
                    'right'  => esc_html__( 'Right', 'lastudio-elements' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-elements' ),
                ),
                'default'     => 'left',
                'render_type' => 'template',
                'condition' => array(
                    'use_button_icon' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_button_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'custom_size',
            array(
                'label'        => esc_html__( 'Custom Size', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->add_responsive_control(
            'button_custom_width',
            array(
                'label'      => esc_html__( 'Custom Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 40,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'custom_size' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'button_custom_height',
            array(
                'label'      => esc_html__( 'Custom Height', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'custom_size' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'button_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['container'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_general_styles' );

        $this->start_controls_tab(
            'tab_general_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'normal_button_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'normal_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_responsive_control(
            'normal_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['state_normal'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'normal_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_general_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'hover_button_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'hover_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_button_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['state_hover'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Plane Style Section
         */
        $this->start_controls_section(
            'section_button_plane_style',
            array(
                'label'      => esc_html__( 'Plane', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_plane_styles' );

        $this->start_controls_tab(
            'tab_plane_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'normal_plane_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['plane_normal'],
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'normal_plane_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['plane_normal'],
            )
        );

        $this->add_responsive_control(
            'normal_plane_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['plane_normal'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'normal_plane_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['plane_normal'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_plane_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'plane_hover_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['plane_hover'],
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_2,
                        ),
                    ),
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'plane_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['plane_hover'],
            )
        );

        $this->add_responsive_control(
            'plane_hover_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['plane_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'hover_plane_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['plane_hover'],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Icon Style Section
         */
        $this->start_controls_section(
            'section_button_icon_style',
            array(
                'label'      => esc_html__( 'Icon', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_icon_styles' );

        $this->start_controls_tab(
            'tab_icon_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'normal_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_normal'] . ' i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'normal_icon_font_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_normal'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'normal_icon_box_width',
            array(
                'label'      => esc_html__( 'Icon Box Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'normal_icon_box_height',
            array(
                'label'      => esc_html__( 'Icon Box Height', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'normal_icon_box_color',
            array(
                'label' => esc_html__( 'Icon Box Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'normal_icon_box_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['icon_normal'],
            )
        );

        $this->add_control(
            'normal_icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'normal_icon_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'hover_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_icon_font_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_icon_box_width',
            array(
                'label'      => esc_html__( 'Icon Box Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_icon_box_height',
            array(
                'label'      => esc_html__( 'Icon Box Height', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'hover_icon_box_color',
            array(
                'label' => esc_html__( 'Icon Box Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'hover_icon_box_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['icon_hover'],
            )
        );

        $this->add_control(
            'hover_icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_icon_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Label Style Section
         */
        $this->start_controls_section(
            'section_button_label_style',
            array(
                'label'      => esc_html__( 'Label', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_label_styles' );

        $this->start_controls_tab(
            'tab_label_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'normal_label_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['label_normal'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'normal_label_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['label_normal'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_label_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'hover_label_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['label_hover'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'hover_label_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['label_hover'],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'label_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['label_normal'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['label_hover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'label_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-elements' ),
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
                    '{{WRAPPER}} ' . $css_scheme['label_normal'] => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['label_hover'] => 'text-align: {{VALUE}};',
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

}