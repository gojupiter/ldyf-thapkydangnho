<?php
namespace Lastudio_Elements\Modules\DownloadButton\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Download_Button Widget
 */
class Download_Button extends Lastudio_Widget {

    public function get_name() {
        return 'lastudio-download-button';
    }

    protected function get_widget_title() {
        return esc_html__( 'Download Button', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-24';
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'download_file',
            array(
                'label'       => esc_html__( 'Download ID', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array( 'active' => true, ),
                'description' => esc_html__( 'Here you can specify ID of media library element which will be downloaded on button click', 'lastudio-elements' ),
                'default'     => array(),
            )
        );

        $this->add_control(
            'download_label',
            array(
                'label'       => esc_html__( 'Download Link Text', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Download', 'lastudio-elements' ),
                'description' => esc_html__( 'You can use %size% placeholder to show formatted file size in label', 'lastudio-elements' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'download_sub_label',
            array(
                'label'       => esc_html__( 'Download Link Sub Text', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( '%size%', 'lastudio-elements' ),
                'description' => esc_html__( 'You can use %size% placeholder to show formatted file size in label', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'download_icon',
            array(
                'label'       => esc_html__( 'Download Link Icon', 'lastudio-elements' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'file'        => '',
                'default'     => 'fa fa-download',
            )
        );

        $this->end_controls_section();

        $css_scheme = apply_filters(
            'lastudio-elements/download-button/css-scheme',
            array(
                'button'          => '.lastudio-download',
                'icon'            => '.lastudio-download__icon',
                'button_label'    => '.lastudio-download__label',
                'button_sublabel' => '.lastudio-download__sub-label',
            )
        );

        $this->start_controls_section(
            'section_action_button_style',
            array(
                'label'      => esc_html__( 'Button', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'button_icon_size',
            array(
                'label' => esc_html__( 'Icon Size', 'lastudio-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 7,
                        'max' => 90,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ':before' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'download_icon_position',
            array(
                'label'   => esc_html__( 'Download Link Icon', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'left'   => esc_html__( 'Left', 'lastudio-elements' ),
                    'right'  => esc_html__( 'Right', 'lastudio-elements' ),
                    'top'    => esc_html__( 'Top', 'lastudio-elements' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-elements' ),
                ),
                'default' => 'left',
            )
        );

        $this->add_responsive_control(
            'button_alignment',
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
                    '{{WRAPPER}} ' . $css_scheme['button'] . ', {{WRAPPER}} .lastudio-download__text' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_icon_margin',
            array(
                'label'      => esc_html__( 'Icon Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'button_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_control(
            'button_icon',
            array(
                'label'     => esc_html__( 'Button Icon', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'button_icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'button_label',
            array(
                'label'     => esc_html__( 'Button Label', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button_label'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['button_label'],
            )
        );

        $this->add_control(
            'button_sublabel',
            array(
                'label'     => esc_html__( 'Button Sub-label', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'button_sub_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button_sublabel'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_sub_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['button_sublabel'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'button_bg_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'button_hover_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'button_hover_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_control(
            'button_icon_hover',
            array(
                'label'     => esc_html__( 'Button Icon', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'button_hover_icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'button_label_hover',
            array(
                'label'     => esc_html__( 'Button Label', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_label'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_hover_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_label'],
            )
        );

        $this->add_control(
            'button_hover_sublabel',
            array(
                'label'     => esc_html__( 'Button Sub-label', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'button_hover_sub_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_sublabel'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_hover_sub_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover '. $css_scheme['button_sublabel'],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
     * Replace placeholders in label
     *
     * @param  string $text Input text.
     * @return string
     */
    protected function __format_label( $text = '', $id = 0 ) {

        if ( ! $id ) {
            return $text;
        }

        if ( false !== strpos( $text, '%size%' ) ) {
            $size = lastudio_elements_download_handler()->get_file_size( $id );
            $text = str_replace( '%size%', $size, $text );
        }

        return $text;
    }

    protected function render() {

        $this->__context = 'render';

        $this->__open_wrap();
        include $this->__get_global_template( 'index' );
        $this->__close_wrap();
    }

}