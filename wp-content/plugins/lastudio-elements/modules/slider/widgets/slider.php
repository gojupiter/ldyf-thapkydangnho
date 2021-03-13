<?php
namespace Lastudio_Elements\Modules\Slider\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;
use Lastudio_Elements\Controls\Group_Control_Box_Style;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Slider Widget
 */
class Slider extends Lastudio_Widget {

    public function get_name() {
        return 'lastudio-slider';
    }

    protected function get_widget_title() {
        return esc_html__( 'Slider', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-7';
    }

    public function get_script_depends() {
        return [
            'imagesloaded',
            'lastudio-slider-pro',
            'lastudio-elements'
        ];
    }

    public function get_style_depends() {
        return [
            'lastudio-slider-pro-css'
        ];
    }

    protected function _register_controls() {

        $css_scheme = apply_filters(
            'lastudio-elements/slider/css-scheme',
            array(
                'instance'            => '.lastudio-slider',
                'instance_slider'     => '.lastudio-slider .slider-pro',
                'navigation'          => '.lastudio-slider .sp-arrows',
                'pagination'          => '.lastudio-slider .sp-buttons',
                'icon'                => '.lastudio-slider__icon',
                'title'               => '.lastudio-slider__title',
                'subtitle'            => '.lastudio-slider__subtitle',
                'desc'                => '.lastudio-slider__desc',
                'buttons_wrapper'     => '.lastudio-slider__button-wrapper',
                'primary_button'      => '.lastudio-slider__button--primary',
                'secondary_button'    => '.lastudio-slider__button--secondary',
                'overlay'             => '.lastudio-slider .sp-image-container:after',
                'fullscreen'          => '.lastudio-slider .sp-full-screen-button',
                'thumbnails'          => '.lastudio-slider .sp-thumbnails-container',
                'thumbnail_container' => '.lastudio-slider .sp-thumbnail-container',
            )
        );

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'lastudio-elements' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio-elements' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_icon',
            array(
                'label'       => esc_html__( 'Icon', 'lastudio-elements' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'file'        => '',
            )
        );

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_subtitle',
            array(
                'label'   => esc_html__( 'Subtitle', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );


        $repeater->add_control(
            'item_desc',
            array(
                'label'   => esc_html__( 'Description', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_button_primary_url',
            array(
                'label'   => esc_html__( 'Primary Button URL', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ),
                ),
            )
        );

        $repeater->add_control(
            'item_button_primary_text',
            array(
                'label'   => esc_html__( 'Primary Button Text', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'More', 'lastudio-elements' ),
            )
        );

        $repeater->add_control(
            'item_button_secondary_url',
            array(
                'label'   => esc_html__( 'Secondary Button URL', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ),
                ),
            )
        );

        $repeater->add_control(
            'item_button_secondary_text',
            array(
                'label'   => esc_html__( 'Secondary Button Text', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'More', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'item_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => array_values( $repeater->get_controls() ),
                'default'     => array(
                    array(
                        'item_image'                 => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'                 => esc_html__( 'Slide #1', 'lastudio-elements' ),
                        'item_subtitle'              => esc_html__( 'SubTitle', 'lastudio-elements' ),
                        'item_desc'                  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                        'item_button_primary_url'    => '#',
                        'item_button_primary_text'   => esc_html__( 'Button #1', 'lastudio-elements' ),
                        'item_button_secondary_ulr'  => '#',
                        'item_button_secondary_text' => esc_html__( 'Button #2', 'lastudio-elements' ),
                    ),
                    array(
                        'item_image'                 => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'                 => esc_html__( 'Slide #2', 'lastudio-elements' ),
                        'item_subtitle'              => esc_html__( 'SubTitle', 'lastudio-elements' ),
                        'item_desc'                  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                        'item_button_primary_url'    => '#',
                        'item_button_primary_text'   => esc_html__( 'Button #1', 'lastudio-elements' ),
                        'item_button_secondary_ulr'  => '#',
                        'item_button_secondary_text' => esc_html__( 'Button #2', 'lastudio-elements' ),
                    ),
                    array(
                        'item_image'                 => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'                 => esc_html__( 'Slide #3', 'lastudio-elements' ),
                        'item_subtitle'              => esc_html__( 'SubTitle', 'lastudio-elements' ),
                        'item_desc'                  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                        'item_button_primary_url'    => '#',
                        'item_button_primary_text'   => esc_html__( 'Button #1', 'lastudio-elements' ),
                        'item_button_secondary_ulr'  => '#',
                        'item_button_secondary_text' => esc_html__( 'Button #2', 'lastudio-elements' ),
                    ),

                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'    => 'slider_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                'default' => 'large',
            )
        );

        $this->add_control(
            'slider_navigation',
            array(
                'label'        => esc_html__( 'Use navigation?', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'slider_navigation_on_hover',
            array(
                'label'        => esc_html__( 'Indicates whether the arrows will fade in only on hover', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'false',
                'condition' => array(
                    'slider_navigation' => 'true',
                ),
            )
        );

        $this->add_control(
            'slider_pagination',
            array(
                'label'        => esc_html__( 'Use pagination?', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'false',
            )
        );

        $this->add_control(
            'slider_autoplay',
            array(
                'label'        => esc_html__( 'Use autoplay?', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'slider_autoplay_delay',
            array(
                'label'   => esc_html__( 'Autoplay delay(ms)', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 5000,
                'min'     => 2000,
                'max'     => 10000,
                'step'    => 100,
                'condition' => array(
                    'slider_autoplay' => 'true',
                ),
            )
        );

        $this->add_control(
            'slide_autoplay_on_hover',
            array(
                'label'   => esc_html__( 'Autoplay On Hover', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'pause',
                'options' => array(
                    'none'  => esc_html__( 'None', 'lastudio-elements' ),
                    'pause' => esc_html__( 'Pause', 'lastudio-elements' ),
                    'stop'  => esc_html__( 'Stop', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'slider_fullScreen',
            array(
                'label'        => esc_html__( 'Display fullScreen button?', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'slider_shuffle',
            array(
                'label'        => esc_html__( 'Indicates if the slides will be shuffled', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'false',
            )
        );

        $this->add_control(
            'slider_loop',
            array(
                'label'        => esc_html__( 'Indicates if the slides will be looped', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'slider_fade_mode',
            array(
                'label'        => esc_html__( 'Use fade effect?', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'false',
            )
        );

        $this->add_control(
            'slide_distance',
            array(
                'label' => esc_html__( ' Between Slides Distance', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 10,
                ),
            )
        );

        $this->add_control(
            'slide_duration',
            array(
                'label'   => esc_html__( 'Slide Duration(ms)', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 500,
                'min'     => 100,
                'max'     => 5000,
                'step'    => 100,
            )
        );

        $this->add_control(
            'thumbnails',
            array(
                'label'        => esc_html__( 'Display thumbnails?', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'thumbnail_width',
            array(
                'label'   => esc_html__( 'Thumbnail width(px)', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 120,
                'min'     => 20,
                'max'     => 300,
                'step'    => 1,
                'condition' => array(
                    'thumbnails' => 'true',
                ),
            )
        );

        $this->add_control(
            'thumbnail_height',
            array(
                'label'   => esc_html__( 'Thumbnail height(px)', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 80,
                'min'     => 20,
                'max'     => 300,
                'step'    => 1,
                'condition' => array(
                    'thumbnails' => 'true',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_slider_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'slider_width',
            array(
                'label' => esc_html__( 'Slider Width(%)', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    '%' => array(
                        'min' => 50,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'unit' => '%',
                    'size' => 100,
                ),
            )
        );

        $this->add_responsive_control(
            'slider_height',
            array(
                'label' => esc_html__( 'Slider Height(px)', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'vh',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 300,
                        'max' => 1000,
                    ),
                    'vh' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 600,
                ),
            )
        );

        $this->add_responsive_control(
            'slider_container_width',
            array(
                'label' => esc_html__( 'Slider Container Width(%)', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array(
                    '%', 'px',
                ),
                'range' => array(
                    '%' => array(
                        'min' => 20,
                        'max' => 100,
                    ),
                    'px' => array(
                        'min' => 200,
                        'max' => 1000,
                    ),
                ),
                'default' => array(
                    'unit' => '%',
                    'size' => 100,
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance_slider'] . ' .lastudio-slider__content-inner' => 'max-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} ' . $css_scheme['pagination'] => 'max-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'slide_image_scale_mode',
            array(
                'label'   => esc_html__( 'Image Scale Mode', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'exact',
                'options' => array(
                    'exact'   => esc_html__( 'Cover', 'lastudio-elements' ),
                    'contain' => esc_html__( 'Contain', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'slider_background',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_2,
                        ),
                    ),
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['instance_slider'] . ' .lastudio-slider__item',
                'condition' => array(
                    'slide_image_scale_mode' => 'contain',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Container Style Section
         */
        $this->start_controls_section(
            'section_slider_container_style',
            array(
                'label'      => esc_html__( 'Container', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'container_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'container_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'container_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
            )
        );

        $this->add_control(
            'container_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] . ' .lastudio-slider__item'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'container_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
            )
        );

        $this->end_controls_section();

        /**
         * Overlay Style Section
         */
        $this->start_controls_section(
            'section_images_layout_overlay_style',
            array(
                'label'      => esc_html__( 'Overlay', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_2,
                        ),
                    ),
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['overlay'],
            )
        );

        $this->add_control(
            'overlay_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio-elements' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.2,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['overlay'] => 'opacity: {{VALUE}};',
                ),

            )
        );

        $this->end_controls_section();

        /**
         * Navigation Style Section
         */
        $this->start_controls_section(
            'section_slider_navigation_style',
            array(
                'label'      => esc_html__( 'Navigation', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'slider_navigation_icon_arrow',
            array(
                'label'   => esc_html__( 'Arrow Icon', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fa fa-angle-left',
                'options' => lastudio_elements_tools()->get_available_prev_arrows_list(),
                'condition' => array(
                    'slider_navigation' => 'true',
                ),
            )
        );

        $this->start_controls_tabs( 'navigation_style_tabs' );

        $this->start_controls_tab(
            'tab_normal_navigation_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'normal_navigation_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'normal_navigation_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'normal_navigation_font_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'normal_navigation_size',
            array(
                'label'      => esc_html__( 'Box Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'normal_navigation_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '0px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow',
            )
        );

        $this->add_control(
            'normal_navigation_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'normal_navigation_box_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation']. ' .sp-arrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'normal_navigation_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hover_navigation_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'hover_navigation_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'hover_navigation_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_navigation_font_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_navigation_size',
            array(
                'label'      => esc_html__( 'Box Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'hover_navigation_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '0px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover',
            )
        );

        $this->add_control(
            'hover_navigation_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'hover_navigation_box_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['navigation']. ' .sp-arrow:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'hover_navigation_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Pagination Style Section
         */
        $this->start_controls_section(
            'section_pagination_style',
            array(
                'label'      => esc_html__( 'Pagination', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_pagination_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'pagination_style',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button',
                'fields_options' => array(
                    'color' => array(
                        'default' => '#fff',
                    ),
                ),
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_pagination_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'pagination_style_hover',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button:hover',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_pagination_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'pagination_style_active',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button.sp-selected-button',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'pagination_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['pagination'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'pagination_dots_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'pagination_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['pagination'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'pagination_container_offset',
            array(
                'label'   => esc_html__( 'Pagination Container Offset', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -500,
                'max'     => 500,
                'step'    => 1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['pagination'] => 'margin-top: {{VALUE}}px;',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Thumbnails Style Section
         */
        $this->start_controls_section(
            'section_thumbnails_style',
            array(
                'label'      => esc_html__( 'Thumbnails', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'thumbnail_item_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'   => 'before',
                'render_type' => 'template',
            )
        );

        $this->add_control(
            'thumbnails_container_offset',
            array(
                'label'   => esc_html__( 'Thumbnails Container Offset', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -500,
                'max'     => 500,
                'step'    => 1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['thumbnails'] => 'margin-top: {{VALUE}}px;',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_thumbnails_style' );

        $this->start_controls_tab(
            'tab_thumbnails_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'thumbnails_normal_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':before',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'thumbnails_normal_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':before',
                'fields_options' => array(
                    'border' => array(
                        'default' => '',
                    ),
                    'width' => array(
                        'default' => array(
                            'top'      => '2',
                            'right'    => '2',
                            'bottom'   => '2',
                            'left'     => '2',
                            'isLinked' => true,
                        ),
                    ),
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumbnails_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'thumbnails_hover_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':hover:before',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'thumbnails_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '2px',
                'default'     => '2px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':hover:before',
                'fields_options' => array(
                    'border' => array(
                        'default' => 'solid',
                    ),
                    'width' => array(
                        'default' => array(
                            'top'      => '2',
                            'right'    => '2',
                            'bottom'   => '2',
                            'left'     => '2',
                            'isLinked' => true,
                        ),
                    ),
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_2,
                        ),
                    ),
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumbnails_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'thumbnails_active_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . '.sp-selected-thumbnail:before',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'thumbnails_active_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '2px',
                'default'     => '2px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . '.sp-selected-thumbnail:before',
                'fields_options' => array(
                    'border' => array(
                        'default' => 'solid',
                    ),
                    'width' => array(
                        'default' => array(
                            'top'      => '2',
                            'right'    => '2',
                            'bottom'   => '2',
                            'left'     => '2',
                            'isLinked' => true,
                        ),
                    ),
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Fullscreen Style Section
         */
        $this->start_controls_section(
            'section_slider_fullscreen_style',
            array(
                'label'      => esc_html__( 'Fullscreen', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'slider_fullscreen_icon',
            array(
                'label'       => esc_html__( 'Icon', 'lastudio-elements' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'file'        => '',
                'default'     => 'fa fa-arrows-alt',
            )
        );

        $this->add_control(
            'fullscreen_icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['fullscreen'] . ' i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'fullscreen_icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'fullscreen_icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['fullscreen'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'fullscreen_icon_size',
            array(
                'label'      => esc_html__( 'Icon Box Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'fullscreen_icon_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['fullscreen'],
            )
        );

        $this->add_control(
            'fullscreen_icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'fullscreen_icon_box_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'fullscreen_icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['fullscreen'],
            )
        );

        $this->end_controls_section();

        /**
         * Icon Style Section
         */
        $this->start_controls_section(
            'section_slider_icon_style',
            array(
                'label'      => esc_html__( 'Icon', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-slider-icon-inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => esc_html__( 'Icon Box Size', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-slider-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'icon_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-slider-icon-inner',
            )
        );

        $this->add_control(
            'icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-slider-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_box_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-slider-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-slider-icon-inner',
            )
        );

        $this->add_responsive_control(
            'icon_box_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['icon'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_slider_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'slider_title_color',
            array(
                'label'  => esc_html__( 'Title Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'slider_title_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_responsive_control(
            'slider_title_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_title_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_title_container_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_title_wax_width',
            array(
                'label' => esc_html__( 'Max Width', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    '%' => array(
                        'min' => 20,
                        'max' => 100,
                    ),
                    'px' => array(
                        'min' => 300,
                        'max' => 1000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );


        $this->end_controls_section();

        /**
         * SubTitle Style Section
         */
        $this->start_controls_section(
            'section_slider_subtitle_style',
            array(
                'label'      => esc_html__( 'Subtitle', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'slider_subtitle_color',
            array(
                'label'  => esc_html__( 'Subtitle Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'slider_subtitle_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} ' . $css_scheme['subtitle'],
            )
        );

        $this->add_responsive_control(
            'slider_subtitle_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['subtitle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_subtitle_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['subtitle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_subtitle_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['subtitle'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_subtitle_container_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['subtitle'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_subtitle_wax_width',
            array(
                'label' => esc_html__( 'Max Width', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    '%' => array(
                        'min' => 20,
                        'max' => 100,
                    ),
                    'px' => array(
                        'min' => 300,
                        'max' => 1000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['subtitle'] => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Desc Style Section
         */
        $this->start_controls_section(
            'section_slider_desc_style',
            array(
                'label'      => esc_html__( 'Description', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'slider_desc_color',
            array(
                'label'  => esc_html__( 'Description Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'slider_desc_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->add_responsive_control(
            'slider_desc_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_desc_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_desc_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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

        $this->add_responsive_control(
            'slider_desc_container_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_desc_wax_width',
            array(
                'label' => esc_html__( 'Max Width', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    '%' => array(
                        'min' => 20,
                        'max' => 100,
                    ),
                    'px' => array(
                        'min' => 300,
                        'max' => 1000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Action Button #1 Style Section
         */
        $this->start_controls_section(
            'section_action_button_style',
            array(
                'label'      => esc_html__( 'Action Button', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'slider_action_button_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['buttons_wrapper'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'section_action_primary_button_heading',
            array(
                'label'     => esc_html__( 'Action Button #1', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->start_controls_tabs( 'tabs_primary_button_style' );

        $this->start_controls_tab(
            'tab_primary_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'primary_button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'primary_button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'primary_button_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['primary_button'],
            )
        );

        $this->add_responsive_control(
            'primary_button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'primary_button_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'primary_button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'primary_button_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['primary_button'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'primary_button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['primary_button'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_primary_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'primary_button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'primary_button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'primary_button_hover_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['primary_button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'primary_button_hover_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'primary_button_hover_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'primary_button_hover_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'primary_button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'primary_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'section_action_secondary_button_heading',
            array(
                'label'     => esc_html__( 'Action Button #2', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->start_controls_tabs( 'tabs_secondary_button_style' );

        $this->start_controls_tab(
            'tab_secondary_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'secondary_button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'secondary_button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'secondary_button_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['secondary_button'],
            )
        );

        $this->add_responsive_control(
            'secondary_button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'secondary_button_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'secondary_button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'secondary_button_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['secondary_button'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'secondary_button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['secondary_button'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_secondary_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'secondary_button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'secondary_button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'secondary_button_hover_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['secondary_button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'secondary_button_hover_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'secondary_button_hover_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'secondary_button_hover_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'secondary_button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'secondary_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
     * Generate setting json
     *
     * @return string
     */
    public function generate_setting_json() {
        $module_settings = $this->get_settings();

        $settings = array(
            'sliderWidth'           => $module_settings['slider_width'],
            'sliderHeight'          => $module_settings['slider_height'],
            'sliderHeightLaptop'    => $module_settings['slider_height_laptop'],
            'sliderHeightTablet'    => $module_settings['slider_height_tablet'],
            'sliderHeight800'       => $module_settings['slider_height_width800'],
            'sliderHeight640'       => $module_settings['slider_height_mobile'],
            'sliderHeightMobile'    => $module_settings['slider_height_mobile'],
            'sliderNavigation'      => filter_var( $module_settings['slider_navigation'], FILTER_VALIDATE_BOOLEAN ),
            'sliderNavigationIcon'  => $module_settings['slider_navigation_icon_arrow'],
            'sliderNaviOnHover'     => filter_var( $module_settings['slider_navigation_on_hover'], FILTER_VALIDATE_BOOLEAN ),
            'sliderPagination'      => filter_var( $module_settings['slider_pagination'], FILTER_VALIDATE_BOOLEAN ),
            'sliderAutoplay'        => filter_var( $module_settings['slider_autoplay'], FILTER_VALIDATE_BOOLEAN ),
            'sliderAutoplayDelay'   => $module_settings['slider_autoplay_delay'],
            'sliderAutoplayOnHover' => $module_settings['slide_autoplay_on_hover'],
            'sliderFullScreen'      => filter_var( $module_settings['slider_fullScreen'], FILTER_VALIDATE_BOOLEAN ),
            'sliderFullscreenIcon'  => $module_settings['slider_fullscreen_icon'],
            'sliderShuffle'         => filter_var( $module_settings['slider_shuffle'], FILTER_VALIDATE_BOOLEAN ),
            'sliderLoop'            => filter_var( $module_settings['slider_loop'], FILTER_VALIDATE_BOOLEAN ),
            'sliderFadeMode'        => filter_var( $module_settings['slider_fade_mode'], FILTER_VALIDATE_BOOLEAN ),
            'slideDistance'         => $module_settings['slide_distance'],
            'slideDuration'         => $module_settings['slide_duration'],
            'imageScaleMode'        => $module_settings['slide_image_scale_mode'],
            'thumbnails'            => filter_var( $module_settings['thumbnails'], FILTER_VALIDATE_BOOLEAN ),
            'thumbnailWidth'        => $module_settings['thumbnail_width'],
            'thumbnailHeight'       => $module_settings['thumbnail_height'],
            'rightToLeft'           => is_rtl(),
        );

        $settings = json_encode( $settings );

        return sprintf( 'data-settings=\'%1$s\'', $settings );
    }

    /**
     * [__loop_button_item description]
     * @param  array  $keys   [description]
     * @param  string $format [description]
     * @return [type]         [description]
     */
    protected function __loop_button_item( $keys = array(), $format = '%s' ) {
        $item = $this->__processed_item;
        $params = [];

        foreach ( $keys as $key => $value ) {

            if ( ! array_key_exists( $value, $item ) ) {
                return false;
            }

            if ( empty( $item[$value] ) ) {
                return false;
            }

            $params[] = $item[ $value ];
        }

        return vsprintf( $format, $params );
    }

    /**
     * [__loop_item_image_tag description]
     * @return [type] [description]
     */
    protected function __loop_item_image_tag( ) {
        $item = $this->__processed_item;
        $image = $item['item_image'];

        if ( empty( $image['id'] ) ) {
            return sprintf( '<img class="sp-image" src="%s" alt="">', Utils::get_placeholder_image_src() );
        }

        $slider_image_size = $this->get_settings_for_display( 'slider_image_size' );

        $slider_image_size = ! empty( $slider_image_size ) ? $slider_image_size : 'full';

        $image_attr = array(
            'class' => 'sp-image',
        );

        return wp_get_attachment_image( $image['id'], $slider_image_size, false, $image_attr );
    }

    /**
     * [render description]
     * @return [type] [description]
     */
    protected function render() {

        $this->__context = 'render';

        $this->__open_wrap();
        include $this->__get_global_template( 'index' );
        $this->__close_wrap();
    }

    protected function _content_template() {}

}