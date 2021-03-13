<?php
namespace Lastudio_Elements\Modules\CircleProgress\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Button Widget
 */
class Circle_Progress extends Lastudio_Widget {

    public function get_name() {
        return 'lastudio-circle-progress';
    }

    protected function get_widget_title() {
        return esc_html__( 'Circle Progress', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-3';
    }


    public function get_script_depends() {
        return [
            'jquery-numerator',
            'lastudio-elements'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_values',
            array(
                'label' => esc_html__( 'Values', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'values_type',
            array(
                'label'   => esc_html__( 'Progress Values Type', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'percent',
                'options' => array(
                    'percent'  => esc_html__( 'Percent', 'lastudio-elements' ),
                    'absolute' => esc_html__( 'Absolute', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'percent_value',
            array(
                'label'      => esc_html__( 'Current Percent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 50,
                ),
                'range'      => array(
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'condition' => array(
                    'values_type' => 'percent',
                ),
            )
        );

        $this->add_control(
            'absolute_value_curr',
            array(
                'label'     => esc_html__( 'Current Value', 'lastudio-elements' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 50,
                'condition' => array(
                    'values_type' => 'absolute',
                ),
            )
        );

        $this->add_control(
            'absolute_value_max',
            array(
                'label'     => esc_html__( 'Max Value', 'lastudio-elements' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 100,
                'condition' => array(
                    'values_type' => 'absolute',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_size',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-elements' ),
            )
        );

        $this->add_responsive_control(
            'circle_size',
            array(
                'label'      => esc_html__( 'Circle Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'default'    => array(
                    'unit' => 'px',
                    'size' => 185,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 600,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .circle-progress-bar' => 'max-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .circle-progress' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .position-in-circle'  => 'height: {{SIZE}}{{UNIT}}',

                ),
                'render_type' => 'template',
            )
        );

        $this->add_responsive_control(
            'value_stroke',
            array(
                'label'      => esc_html__( 'Value Stoke Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'default'    => array(
                    'unit' => 'px',
                    'size' => 7,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 300,
                    ),
                ),
            )
        );

        $this->add_responsive_control(
            'bg_stroke',
            array(
                'label'      => esc_html__( 'Background Stoke Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'default'    => array(
                    'unit' => 'px',
                    'size' => 7,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 300,
                    ),
                ),
            )
        );

        $this->add_control(
            'duration',
            array(
                'label'   => esc_html__( 'Animation Duration', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1000,
                'min'     => 100,
                'step'    => 100,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'prefix',
            array(
                'label'       => esc_html__( 'Value Number Prefix', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => '+',
            )
        );

        $this->add_control(
            'suffix',
            array(
                'label'       => esc_html__( 'Value Number Suffix', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '%',
                'placeholder' => '%',
            )
        );

        $this->add_control(
            'thousand_separator',
            array(
                'label'     => esc_html__( 'Show Thousand Separator in Value', 'lastudio-elements' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'label_on'  => esc_html__( 'Show', 'lastudio-elements' ),
                'label_off' => esc_html__( 'Hide', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => esc_html__( 'Counter Title', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'subtitle',
            array(
                'label'   => esc_html__( 'Counter Subtitle', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'percent_position',
            array(
                'label'   => esc_html__( 'Percent Position', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'in-circle',
                'options' => array(
                    'in-circle'  => esc_html__( 'Inside of Circle', 'lastudio-elements' ),
                    'out-circle' => esc_html__( 'Outside of Circle', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'labels_position',
            array(
                'label'   => esc_html__( 'Label Position', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'out-circle',
                'options' => array(
                    'in-circle'  => esc_html__( 'Inside of Circle', 'lastudio-elements' ),
                    'out-circle' => esc_html__( 'Outside of Circle', 'lastudio-elements' ),
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_progress_style',
            array(
                'label'      => esc_html__( 'Progress Circle Style', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'bg_stroke_type',
            array(
                'label'       => esc_html__( 'Background Stroke Type', 'lastudio-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'color' => array(
                        'title' => esc_html__( 'Classic', 'lastudio-elements' ),
                        'icon'  => 'fa fa-paint-brush',
                    ),
                    'gradient' => array(
                        'title' => esc_html__( 'Gradient', 'lastudio-elements' ),
                        'icon'  => 'fa fa-barcode',
                    ),
                ),
                'default'     => 'color',
                'label_block' => false,
                'render_type' => 'ui',
            )
        );

        $this->add_control(
            'val_bg_color',
            array(
                'label'     => esc_html__( 'Background Stroke Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e6e9ec',
                'condition' => array(
                    'bg_stroke_type' => array( 'color' ),
                ),
            )
        );

        $this->add_control(
            'val_bg_gradient_color_a',
            array(
                'label'     => esc_html__( 'Background Stroke Color A', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#54595f',
                'condition' => array(
                    'bg_stroke_type' => array( 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'val_bg_gradient_color_b',
            array(
                'label'     => esc_html__( 'Background Stroke Color B', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#858d97',
                'condition' => array(
                    'bg_stroke_type' => array( 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'val_bg_gradient_angle',
            array(
                'label'     => esc_html__( 'Background Stroke Gradient Angle', 'lastudio-elements' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 45,
                'min'       => 0,
                'max'       => 360,
                'step'      => 0,
                'condition' => array(
                    'bg_stroke_type' => array( 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'val_stroke_type',
            array(
                'label'       => esc_html__( 'Value Stroke Type', 'lastudio-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'color' => array(
                        'title' => esc_html__( 'Classic', 'lastudio-elements' ),
                        'icon'  => 'fa fa-paint-brush',
                    ),
                    'gradient' => array(
                        'title' => esc_html__( 'Gradient', 'lastudio-elements' ),
                        'icon'  => 'fa fa-barcode',
                    ),
                ),
                'default'     => 'color',
                'label_block' => false,
                'render_type' => 'ui',
            )
        );

        $this->add_control(
            'val_stroke_color',
            array(
                'label'     => esc_html__( 'Value Stroke Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6ec1e4',
                'condition' => array(
                    'val_stroke_type' => array( 'color' ),
                ),
            )
        );

        $this->add_control(
            'val_stroke_gradient_color_a',
            array(
                'label'     => esc_html__( 'Value Stroke Color A', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6ec1e4',
                'condition' => array(
                    'val_stroke_type' => array( 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'val_stroke_gradient_color_b',
            array(
                'label'     => esc_html__( 'Value Stroke Color B', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#b6e0f1',
                'condition' => array(
                    'val_stroke_type' => array( 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'val_stroke_gradient_angle',
            array(
                'label'     => esc_html__( 'Value Stroke Angle', 'lastudio-elements' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 45,
                'min'       => 0,
                'max'       => 360,
                'step'      => 1,
                'condition' => array(
                    'val_stroke_type' => array( 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'circle_fill_color',
            array(
                'label'     => esc_html__( 'Circle Fill Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .circle-progress__meter' => 'fill: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'line_endings',
            array(
                'label'   => esc_html__( 'Progress Line Endings', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'butt',
                'options' => array(
                    'butt'  => esc_html__( 'Flat', 'lastudio-elements' ),
                    'round' => esc_html__( 'Rounded', 'lastudio-elements' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .circle-progress__value' => 'stroke-linecap: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'circle_box_shadow',
                'label'    => esc_html__( 'Circle Box Shadow', 'lastudio-elements' ),
                'selector' => '{{WRAPPER}} .circle-progress',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_style',
            array(
                'label'      => esc_html__( 'Content Style', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'number_style',
            array(
                'label'     => esc_html__( 'Number Styles', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'number_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .circle-counter .circle-val' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'number_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .circle-counter .circle-val',
            )
        );

        $this->add_responsive_control(
            'number_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .circle-counter .circle-val' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'number_prefix_font_size',
            array(
                'label'      => esc_html__( 'Prefix Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'number_prefix_gap',
            array(
                'label'      => esc_html__( 'Prefix Gap (px)', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    'body:not(.rtl) {{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'margin-right: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'margin-left: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'number_prefix_alignment',
            array(
                'label'       => esc_html__( 'Prefix Alignment', 'lastudio-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default'     => 'center',
                'options'     => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'number_suffix_font_size',
            array(
                'label'      => esc_html__( 'Suffix Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'number_suffix_gap',
            array(
                'label'      => esc_html__( 'Suffix Gap (px)', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    'body:not(.rtl) {{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'margin-left: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'margin-right: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'number_suffix_alignment',
            array(
                'label'       => esc_html__( 'Suffix Alignment', 'lastudio-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default'     => 'center',
                'options'     => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'title_style',
            array(
                'label'     => esc_html__( 'Title Styles', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .circle-counter .circle-counter__title' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .circle-counter .circle-counter__title',
            )
        );

        $this->add_responsive_control(
            'title_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .circle-counter .circle-counter__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'subtitle_style',
            array(
                'label'     => esc_html__( 'Subtitle Styles', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'subtitle_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .circle-counter .circle-counter__subtitle' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .circle-counter .circle-counter__subtitle',
            )
        );

        $this->add_responsive_control(
            'subtitle_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .circle-counter .circle-counter__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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