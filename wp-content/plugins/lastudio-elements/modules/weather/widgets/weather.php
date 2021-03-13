<?php
namespace Lastudio_Elements\Modules\Weather\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Weather Widget
 */
class Weather extends Lastudio_Widget {

    public $weather_data = array();

    public $weather_api_url = 'https://query.yahooapis.com/v1/public/yql';

    public function get_name() {
        return 'lastudio-weather';
    }

    protected function get_widget_title() {
        return esc_html__( 'Weather', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-43';
    }

    protected function _register_controls() {
        $css_scheme = apply_filters(
            'lastudio-elements/weather/css-scheme',
            array(
                'title'                 => '.lastudio-weather__title',
                'current_container'     => '.lastudio-weather__current',
                'current_temp'          => '.lastudio-weather__current-temp',
                'current_icon'          => '.lastudio-weather__current-icon .lastudio-weather-icon',
                'current_desc'          => '.lastudio-weather__current-desc',
                'current_details'       => '.lastudio-weather__details',
                'current_details_item'  => '.lastudio-weather__details-item',
                'current_details_icon'  => '.lastudio-weather__details-item .lastudio-weather-icon',
                'current_day'           => '.lastudio-weather__current-day',
                'forecast_container'    => '.lastudio-weather__forecast',
                'forecast_item'         => '.lastudio-weather__forecast-item',
                'forecast_day'          => '.lastudio-weather__forecast-day',
                'forecast_icon'         => '.lastudio-weather__forecast-icon .lastudio-weather-icon',
            )
        );

        $this->start_controls_section(
            'section_weather',
            array(
                'label' => esc_html__( 'Weather', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'location',
            array(
                'label'       => esc_html__( 'Location', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array( 'active' => true, ),
                'placeholder' => esc_html__( 'London, United Kingdom', 'lastudio-elements' ),
                'default'     => esc_html__( 'London, United Kingdom', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'units',
            array(
                'label'   => esc_html__( 'Units', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'metric',
                'options' => array(
                    'metric'   => esc_html__( 'Metric', 'lastudio-elements' ),
                    'imperial' => esc_html__( 'Imperial', 'lastudio-elements' ),
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'title_size',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'  => esc_html__( 'H1', 'lastudio-elements' ),
                    'h2'  => esc_html__( 'H2', 'lastudio-elements' ),
                    'h3'  => esc_html__( 'H3', 'lastudio-elements' ),
                    'h4'  => esc_html__( 'H4', 'lastudio-elements' ),
                    'h5'  => esc_html__( 'H5', 'lastudio-elements' ),
                    'h6'  => esc_html__( 'H6', 'lastudio-elements' ),
                    'div' => esc_html__( 'div', 'lastudio-elements' ),
                ),
                'default' => 'h3',
            )
        );

        $this->add_control(
            'show_country_name',
            array(
                'label'        => esc_html__( 'Show country name', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'show_current_weather',
            array(
                'label'        => esc_html__( 'Show current weather', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default'      => 'true',
                'separator'    => 'before',
            )
        );

        $this->add_control(
            'show_current_weather_details',
            array(
                'label'        => esc_html__( 'Show current weather details', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'condition'    => array(
                    'show_current_weather' => 'true',
                ),
            )
        );

        $this->add_control(
            'show_forecast_weather',
            array(
                'label'        => esc_html__( 'Show forecast weather', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'separator'    => 'before',
            )
        );

        $this->add_control(
            'forecast_count',
            array(
                'label' => esc_html__( 'Number of forecast days', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 5,
                    ),
                ),
                'default' => array(
                    'size' => 5,
                ),
                'condition' => array(
                    'show_forecast_weather' => 'true',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            array(
                'label' => esc_html__( 'Title', 'lastudio-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_responsive_control(
            'title_align',
            array(
                'label' => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'  => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
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
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'title_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'title_text_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_current_style',
            array(
                'label'     => esc_html__( 'Current Weather', 'lastudio-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_current_weather' => 'true',
                ),
            )
        );

        $this->add_control(
            'current_container_heading',
            array(
                'label' => esc_html__( 'Container', 'lastudio-elements' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_responsive_control(
            'current_container_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'current_container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'current_container_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_container'],
            )
        );

        $this->add_control(
            'current_temp_heading',
            array(
                'label'     => esc_html__( 'Temperature', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_temp_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_temp'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_temp_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_temp'],
            )
        );

        $this->add_control(
            'current_icon_heading',
            array(
                'label'     => esc_html__( 'Icon', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_icon'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'current_icon_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'current_desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_desc_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_desc'],
            )
        );

        $this->add_control(
            'current_desc_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_desc'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_current_details_style',
            array(
                'label'     => esc_html__( 'Details Weather', 'lastudio-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_current_weather'         => 'true',
                    'show_current_weather_details' => 'true',
                ),
            )
        );

        $this->add_control(
            'current_details_container_heading',
            array(
                'label' => esc_html__( 'Container', 'lastudio-elements' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_control(
            'current_details_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'current_details_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'current_details_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
            )
        );

        $this->add_control(
            'current_details_items_heading',
            array(
                'label'     => esc_html__( 'Items', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_details_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_details_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_day_typography',
                'label'    => esc_html__( 'Day typography', 'lastudio-elements' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_day'],
            )
        );

        $this->add_control(
            'current_details_item_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details_item'] . ' + ' . $css_scheme['current_details_item'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'current_details_icon_heading',
            array(
                'label'     => esc_html__( 'Icon', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_details_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'current_details_icon_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_forecast_style',
            array(
                'label'     => esc_html__( 'Forecast Weather', 'lastudio-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_forecast_weather' => 'true',
                ),
            )
        );

        $this->add_control(
            'forecast_container_heading',
            array(
                'label' => esc_html__( 'Container', 'lastudio-elements' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_responsive_control(
            'forecast_container_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'forecast_container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'forecast_container_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_container'],
            )
        );

        $this->add_control(
            'forecast_item_heading',
            array(
                'label'     => esc_html__( 'Items', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'forecast_item_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'forecast_item_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_item'],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'forecast_day_typography',
                'label'    => esc_html__( 'Day typography', 'lastudio-elements' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_day'],
            )
        );

        $this->add_responsive_control(
            'forecast_item_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'forecast_item_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'forecast_item_divider',
            array(
                'label'        => esc_html__( 'Divider', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'forecast_item_divider_style',
            array(
                'label' => esc_html__( 'Style', 'lastudio-elements' ),
                'type'  => Controls_Manager::SELECT,
                'options' => array(
                    'solid'  => esc_html__( 'Solid', 'lastudio-elements' ),
                    'double' => esc_html__( 'Double', 'lastudio-elements' ),
                    'dotted' => esc_html__( 'Dotted', 'lastudio-elements' ),
                    'dashed' => esc_html__( 'Dashed', 'lastudio-elements' ),
                ),
                'default' => 'solid',
                'condition' => array(
                    'forecast_item_divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-style: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'forecast_item_divider_weight',
            array(
                'label'   => esc_html__( 'Weight', 'lastudio-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 20,
                    ),
                ),
                'condition' => array(
                    'forecast_item_divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'forecast_item_divider_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'forecast_item_divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'forecast_icon_heading',
            array(
                'label'     => esc_html__( 'Icon', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'forecast_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'forecast_icon_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $this->__context = 'render';

        $this->__open_wrap();

        $this->weather_data = $this->get_weather_data();

        if ( ! empty( $this->weather_data ) ) {
            include $this->__get_global_template( 'index' );
        }

        $this->__close_wrap();
    }

    /**
     * Get weather data.
     *
     * @return array|bool|mixed
     */
    public function get_weather_data() {

        $settings = $this->get_settings_for_display();
        $units    = ! empty( $settings['units'] ) ? $settings['units'] : 'metric';
        $location = $settings['location'];

        if ( empty( $location ) ) {
            return false;
        }

        $transient_key = sprintf( 'lastudio-weather-data-yahoo-%1$s-%2$s', $units, md5( $location ) );

        $data = get_transient( $transient_key );

        if ( ! $data ) {
            // Prepare request data
            $location = esc_attr( $location );
            $units    = ( 'metric' === $units ) ? 'c' : 'f';

            $request_args = array(
                'q'      => urlencode( sprintf( 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="%1$s") AND u="%2$s"', $location, $units ) ),
                'format' => 'json',
                'env'    => 'store://datatables.org/alltableswithkeys',
            );

            $request_url = add_query_arg(
                $request_args,
                $this->weather_api_url
            );

            $request_data = $this->__get_request_data( $request_url );

            if ( ! $request_data ) {
                return false;
            }

            if ( 0 === $request_data['query']['count'] || empty( $request_data['query']['results'] ) || empty( $request_data['query']['results']['channel'] ) ) {

                $message = esc_html__( 'Weather data of this location not found.', 'lastudio-elements' );

                echo $this->get_weather_notice( $message );
                return false;
            }

            $data = $this->prepare_weather_data( $request_data );

            if ( empty( $data ) ) {
                return false;
            }

            set_transient( $transient_key, $data, apply_filters( 'lastudio-elements/weather/cached-time', HOUR_IN_SECONDS ) );
        }

        return $data;
    }

    /**
     * Get request data.
     *
     * @param string $url Request url.
     *
     * @return array|bool
     */
    public function __get_request_data( $url ) {

        $response = wp_remote_get( $url, array( 'timeout' => 30 ) );

        if ( ! $response || is_wp_error( $response ) ) {
            return false;
        }

        $data = wp_remote_retrieve_body( $response );

        if ( ! $data || is_wp_error( $data ) ) {
            return false;
        }

        $data = json_decode( $data, true );

        if ( empty( $data ) ) {
            return false;
        }

        return $data;
    }

    /**
     * Prepare weather data.
     *
     * @param array $request_data Request data.
     *
     * @return array|bool
     */
    public function prepare_weather_data( $request_data = array() ) {

        $api_data = $request_data['query']['results']['channel'];

        $data = array(
            // Location data
            'location' => array(
                'city'    => $api_data['location']['city'],
                'country' => $api_data['location']['country'],
            ),

            // Current data
            'current'  => array(
                'temp'     => round( $api_data['item']['condition']['temp'] ),
                'temp_min' => round( $api_data['item']['forecast'][0]['low'] ),
                'temp_max' => round( $api_data['item']['forecast'][0]['high'] ),
                'code'     => $api_data['item']['condition']['code'],
                'desc'     => $this->get_weather_desc( $api_data['item']['condition']['code'] ),
                'wind'     => array(
                    'speed' => $api_data['wind']['speed'],
                    'deg'   => $api_data['wind']['direction'],
                ),
                'humidity' => $api_data['atmosphere']['humidity'] . '%',
                'pressure' => $api_data['atmosphere']['pressure'],
                'sunrise'  => $api_data['astronomy']['sunrise'],
                'sunset'   => $api_data['astronomy']['sunset'],
                'week_day' => date_i18n( 'l' ),
            ),

            // Forecast data
            'forecast' => array(),
        );

        for ( $i = 1; $i <= 5; $i ++ ) {
            $data['forecast'][] = array(
                'code'     => $api_data['item']['forecast'][ $i ]['code'],
                'desc'     => $this->get_weather_desc( $api_data['item']['forecast'][ $i ]['code'] ),
                'temp_min' => $api_data['item']['forecast'][ $i ]['low'],
                'temp_max' => $api_data['item']['forecast'][ $i ]['high'],
                'week_day' => $this->get_week_day_from_date_format( 'd M Y', $api_data['item']['forecast'][ $i ]['date'] ),
            );
        }

        return $data;
    }

    /**
     * Get weather description.
     *
     * @param int $code Weather code.
     *
     * @return string
     */
    public function get_weather_desc( $code ) {
        $desc = '';

        switch ( $code ) {
            case 0:
                $desc = esc_html_x( 'Tornado', 'Weather description', 'lastudio-elements' );
                break;
            case 1:
                $desc = esc_html_x( 'Tropical storm', 'Weather description', 'lastudio-elements' );
                break;
            case 2:
                $desc = esc_html_x( 'Hurricane', 'Weather description', 'lastudio-elements' );
                break;
            case 3:
                $desc = esc_html_x( 'Severe thunderstorms', 'Weather description', 'lastudio-elements' );
                break;
            case 4:
                $desc = esc_html_x( 'Thunderstorms', 'Weather description', 'lastudio-elements' );
                break;
            case 5:
                $desc = esc_html_x( 'Mixed rain and snow', 'Weather description', 'lastudio-elements' );
                break;
            case 6:
                $desc = esc_html_x( 'Mixed rain and sleet', 'Weather description', 'lastudio-elements' );
                break;
            case 7:
                $desc = esc_html_x( 'Mixed snow and sleet', 'Weather description', 'lastudio-elements' );
                break;
            case 8:
                $desc = esc_html_x( 'Freezing drizzle', 'Weather description', 'lastudio-elements' );
                break;
            case 9:
                $desc = esc_html_x( 'Drizzle', 'Weather description', 'lastudio-elements' );
                break;
            case 10:
                $desc = esc_html_x( 'Freezing rain', 'Weather description', 'lastudio-elements' );
                break;
            case 11:
            case 12:
                $desc = esc_html_x( 'Showers', 'Weather description', 'lastudio-elements' );
                break;
            case 13:
                $desc = esc_html_x( 'Snow flurries', 'Weather description', 'lastudio-elements' );
                break;
            case 14:
                $desc = esc_html_x( 'Light snow showers', 'Weather description', 'lastudio-elements' );
                break;
            case 15:
                $desc = esc_html_x( 'Blowing snow', 'Weather description', 'lastudio-elements' );
                break;
            case 16:
                $desc = esc_html_x( 'Snow', 'Weather description', 'lastudio-elements' );
                break;
            case 17:
                $desc = esc_html_x( 'Hail', 'Weather description', 'lastudio-elements' );
                break;
            case 18:
                $desc = esc_html_x( 'Sleet', 'Weather description', 'lastudio-elements' );
                break;
            case 19:
                $desc = esc_html_x( 'Dust', 'Weather description', 'lastudio-elements' );
                break;
            case 20:
                $desc = esc_html_x( 'Foggy', 'Weather description', 'lastudio-elements' );
                break;
            case 21:
                $desc = esc_html_x( 'Haze', 'Weather description', 'lastudio-elements' );
                break;
            case 22:
                $desc = esc_html_x( 'Smoky', 'Weather description', 'lastudio-elements' );
                break;
            case 23:
                $desc = esc_html_x( 'Blustery', 'Weather description', 'lastudio-elements' );
                break;
            case 24:
                $desc = esc_html_x( 'Windy', 'Weather description', 'lastudio-elements' );
                break;
            case 25:
                $desc = esc_html_x( 'Cold', 'Weather description', 'lastudio-elements' );
                break;
            case 26:
                $desc = esc_html_x( 'Cloudy', 'Weather description', 'lastudio-elements' );
                break;
            case 27:
            case 28:
                $desc = esc_html_x( 'Mostly cloudy', 'Weather description', 'lastudio-elements' );
                break;
            case 29:
            case 30:
                $desc = esc_html_x( 'Partly cloudy', 'Weather description', 'lastudio-elements' );
                break;
            case 31:
                $desc = esc_html_x( 'Clear', 'Weather description', 'lastudio-elements' );
                break;
            case 32:
                $desc = esc_html_x( 'Sunny', 'Weather description', 'lastudio-elements' );
                break;
            case 33:
            case 34:
                $desc = esc_html_x( 'Fair', 'Weather description', 'lastudio-elements' );
                break;
            case 35:
                $desc = esc_html_x( 'Mixed rain and hail', 'Weather description', 'lastudio-elements' );
                break;
            case 36:
                $desc = esc_html_x( 'Hot', 'Weather description', 'lastudio-elements' );
                break;
            case 37:
                $desc = esc_html_x( 'Isolated thunderstorms', 'Weather description', 'lastudio-elements' );
                break;
            case 38:
            case 39:
                $desc = esc_html_x( 'Scattered thunderstorms', 'Weather description', 'lastudio-elements' );
                break;
            case 40:
                $desc = esc_html_x( 'Scattered showers', 'Weather description', 'lastudio-elements' );
                break;
            case 41:
            case 43:
                $desc = esc_html_x( 'Heavy snow', 'Weather description', 'lastudio-elements' );
                break;
            case 42:
                $desc = esc_html_x( 'Scattered snow showers', 'Weather description', 'lastudio-elements' );
                break;
            case 44:
                $desc = esc_html_x( 'Partly cloudy', 'Weather description', 'lastudio-elements' );
                break;
            case 45:
                $desc = esc_html_x( 'Thundershowers', 'Weather description', 'lastudio-elements' );
                break;
            case 46:
                $desc = esc_html_x( 'Snow showers', 'Weather description', 'lastudio-elements' );
                break;
            case 47:
                $desc = esc_html_x( 'Isolated thundershowers', 'Weather description', 'lastudio-elements' );
                break;
            case 3200:
                $desc = esc_html_x( 'Not available', 'Weather description', 'lastudio-elements' );
                break;
        }

        return $desc;
    }

    /**
     * Get week day from date.
     *
     * @param string $format Date format.
     * @param string $date   Date.
     *
     * @return bool|string
     */
    public function get_week_day_from_date_format( $format = '', $date = '' ) {
        $date = date_create_from_format( $format, $date );

        return date_i18n( 'l', date_timestamp_get( $date ) );
    }

    /**
     * Get title html markup.
     *
     * @return string
     */
    public function get_weather_title() {
        $settings = $this->get_settings_for_display();
        $title    = $this->weather_data['location']['city'];
        $tag      = isset( $settings['title_size'] ) ? $settings['title_size'] : 'h3';

        if ( isset( $settings['show_country_name'] ) && 'true' === $settings['show_country_name'] ) {
            $country = $this->weather_data['location']['country'];

            $title = sprintf( '%1$s, %2$s', $title, $country );
        }

        return sprintf( '<%1$s class="lastudio-weather__title">%2$s</%1$s>', $tag, $title );
    }

    /**
     * Get temperature html markup.
     *
     * @param int $temp Temperature value.
     *
     * @return string
     */
    public function get_weather_temp( $temp ) {
        $units     = $this->get_settings_for_display( 'units' );
        $temp_unit = ( 'metric' === $units ) ? '&#176;C' : '&#176;F';

        $format = apply_filters( 'lastudio-elements/weather/temperature-format', '%1$s%2$s' );

        return sprintf( $format, $temp, $temp_unit );
    }

    /**
     * Get wind.
     *
     * @param int $speed Wind speed.
     * @param int $deg   Wind direction, degrees.
     *
     * @return string
     */
    public function get_wind( $speed, $deg ) {
        $units      = $this->get_settings_for_display( 'units' );
        $speed_unit = ( 'metric' === $units ) ? esc_html_x( 'km/h', 'Unit of speed (kilometers/hour)', 'lastudio-elements' ) : esc_html_x( 'mph', 'Unit of speed (miles/hour)', 'lastudio-elements' );

        $direction = '';

        if ( ( $deg >= 0 && $deg <= 11.25 ) || ( $deg > 348.75 && $deg <= 360 ) ) {
            $direction = esc_html_x( 'N', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 11.25 && $deg <= 33.75 ) {
            $direction = esc_html_x( 'NNE', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 33.75 && $deg <= 56.25 ) {
            $direction = esc_html_x( 'NE', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 56.25 && $deg <= 78.75 ) {
            $direction = esc_html_x( 'ENE', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 78.75 && $deg <= 101.25 ) {
            $direction = esc_html_x( 'E', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 101.25 && $deg <= 123.75 ) {
            $direction = esc_html_x( 'ESE', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 123.75 && $deg <= 146.25 ) {
            $direction = esc_html_x( 'SE', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 146.25 && $deg <= 168.75 ) {
            $direction = esc_html_x( 'SSE', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 168.75 && $deg <= 191.25 ) {
            $direction = esc_html_x( 'S', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 191.25 && $deg <= 213.75 ) {
            $direction = esc_html_x( 'SSW', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 213.75 && $deg <= 236.25 ) {
            $direction = esc_html_x( 'SW', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 236.25 && $deg <= 258.75 ) {
            $direction = esc_html_x( 'WSW', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 258.75 && $deg <= 281.25 ) {
            $direction = esc_html_x( 'W', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 281.25 && $deg <= 303.75 ) {
            $direction = esc_html_x( 'WNW', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 303.75 && $deg <= 326.25 ) {
            $direction = esc_html_x( 'NW', 'Wind direction', 'lastudio-elements' );
        } else if ( $deg > 326.25 && $deg <= 348.75 ) {
            $direction = esc_html_x( 'NNW', 'Wind direction', 'lastudio-elements' );
        }

        $format = apply_filters( 'lastudio-elements/weather/wind-format', '%1$s %2$s %3$s' );

        return sprintf( $format, $direction, $speed, $speed_unit );
    }

    /**
     * Get weather notice html markup.
     *
     * @param string $message Message.
     *
     * @return string
     */
    public function get_weather_notice( $message ) {
        return sprintf( '<div class="lastudio-weather-notice">%s</div>', $message );
    }

    /**
     * Get weather svg icon.
     *
     * @param string|int $icon Icon slug or weather code.
     *
     * @return bool|string
     */
    public function get_weather_svg_icon( $icon ) {

        $icon_path = LASTUDIO_ELEMENTS_PATH . "assets/images/weather-icons/{$icon}.svg" ;

        if ( ! file_exists( $icon_path ) ) {
            return false;
        }

        ob_start();

        include $icon_path;

        $svg = ob_get_clean();

        $_classes   = array();
        $_classes[] = 'lastudio-weather-icon';
        $_classes[] = sprintf( 'lastudio-weather-icon-%s', esc_attr( $icon ) );

        $classes = join( ' ', $_classes );

        return sprintf( '<div class="%2$s">%1$s</div>', $svg, $classes );
    }

}