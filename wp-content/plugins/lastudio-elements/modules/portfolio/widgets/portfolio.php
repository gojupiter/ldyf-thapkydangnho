<?php
namespace Lastudio_Elements\Modules\Portfolio\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;
use Lastudio_Elements\Controls\Group_Control_Box_Style;

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
 * Portfolio Widget
 */
class Portfolio extends Lastudio_Widget {

    /**
     * [$item_counter description]
     * @var integer
     */
    public $item_counter = 0;

    public function get_name() {
        return 'lastudio-portfolio';
    }

    protected function get_widget_title() {
        return esc_html__( 'Portfolio', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-36';
    }

    public function get_script_depends() {
        return [
            'imagesloaded',
            'lastudio-anime-js',
            'lastudio-masonry-js',
            'lastudio-elements'
        ];
    }

    protected function _register_controls() {

        $css_scheme = apply_filters(
            'lastudio-elements/portfolio/css-scheme',
            array(
                'instance'         => '.lastudio-portfolio',
                'list_container_wrap'   => '.lastudio-portfolio__list_wrapper',
                'list_container'   => '.lastudio-portfolio__list',
                'item'             => '.lastudio-portfolio__item',
                'inner'            => '.lastudio-portfolio__inner',
                'image_wrap'       => '.lastudio-portfolio__image',
                'image_instance'   => '.lastudio-portfolio__image-instance',
                'content_wrap'     => '.lastudio-portfolio__content',
                'content_inner'    => '.lastudio-portfolio__content-inner',
                'cover'            => '.lastudio-portfolio__cover',
                'title'            => '.lastudio-portfolio__title',
                'desc'             => '.lastudio-portfolio__desc',
                'category'         => '.lastudio-portfolio__category',
                'button'           => '.lastudio-portfolio__button',
                'view_more'        => '.lastudio-portfolio__view-more-button',
                'filters_wrap'     => '.lastudio-portfolio__filter',
                'filters'          => '.lastudio-portfolio__filter-list',
                'filter'           => '.lastudio-portfolio__filter-item',
                'filter_separator' => '.lastudio-portfolio__filter-item-separator',
            )
        );

        $preset_type = apply_filters(
            'lastudio-elements/portfolio/control/preset',
            array(
                'type-1' => esc_html__( 'Type-1', 'lastudio-elements' ),
                'type-2' => esc_html__( 'Type-2', 'lastudio-elements' ),
                'type-3' => esc_html__( 'Type-3', 'lastudio-elements' ),
                'type-4' => esc_html__( 'Type-4', 'lastudio-elements' ),
                'type-5' => esc_html__( 'Type-5', 'lastudio-elements' ),
                'type-6' => esc_html__( 'Type-6', 'lastudio-elements' ),
                'type-7' => esc_html__( 'Type-7', 'lastudio-elements' ),
                'type-8' => esc_html__( 'Type-8', 'lastudio-elements' )
            )
        );

        $preset_list_type = apply_filters(
            'lastudio-elements/portfolio/control/preset_list',
            array(
                'list-type-1' => esc_html__( 'Type-1', 'lastudio-elements' ),
                'list-type-2' => esc_html__( 'Type-2', 'lastudio-elements' ),
                'list-type-3' => esc_html__( 'Type-3', 'lastudio-elements' ),
                'list-type-4' => esc_html__( 'Type-4', 'lastudio-elements' ),
                'list-type-5' => esc_html__( 'Type-5', 'lastudio-elements' ),
            )
        );

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => array(
                    'masonry' => esc_html__( 'Masonry', 'lastudio-elements' ),
                    'grid'    => esc_html__( 'Grid', 'lastudio-elements' ),
                    'justify' => esc_html__( 'Justify', 'lastudio-elements' ),
                    'list'    => esc_html__( 'List', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'preset',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'type-1',
                'options' => $preset_type,
                'condition' => array(
                    'layout_type!' => 'list'
                )
            )
        );

        $this->add_control(
            'preset_list',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'type-list-1',
                'options' => $preset_list_type,
                'condition' => array(
                    'layout_type' => 'list'
                )
            )
        );

        $this->add_control(
            'enable_custom_masonry_layout',
            array(
                'label'        => esc_html__( 'Enable Custom Masonry Layout', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => '',
                'condition' => array(
                    'layout_type' => 'masonry'
                )
            )
        );

        $this->add_control(
            'container_width',
            array(
                'label' => esc_html__( 'Container Width', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 500,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 1170,
                ],
                'condition' => array(
                    'layout_type' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_width',
            array(
                'label' => esc_html__( 'Masonry Item Width', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 300,
                ],
                'condition' => array(
                    'layout_type' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_height',
            array(
                'label' => esc_html__( 'Masonry Item Height', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 300,
                ],
                'condition' => array(
                    'layout_type' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );


        $this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => ''
            )
        );

        $this->add_responsive_control(
            'custom_image_height',
            array(
                'label' => esc_html__( 'Image Height', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'size_units' => ['px', '%', 'vh'],
                'default' => [
                    'size' => 300,
                    'unit' => 'px'
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['image_instance'] => 'display: none;'
                ),
                'render_type' => 'template',
                'condition' => [
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => lastudio_elements_tools()->get_select_range( 6 ),
                'condition' => array(
                    'layout_type' => array( 'masonry', 'grid', 'list' ),
                ),
            )
        );

        $this->add_control(
            'all_filter_label',
            array(
                'label'   => esc_html__( '`All` Filter Label', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'All', 'lastudio-elements' ),
                'condition' => array(
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                ),
            )
        );

        $this->add_control(
            'view_more_button',
            array(
                'label'        => esc_html__( 'View More Button', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => false,
                'condition' => array(
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                ),
            )
        );

        $this->add_control(
            'view_more_button_text',
            array(
                'label'     => esc_html__( 'View More Button Text', 'lastudio-elements' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'View More', 'lastudio-elements' ),
                'condition' => array(
                    'view_more_button' => 'true',
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                ),
            )
        );

        $this->add_control(
            'per_page',
            array(
                'label'   => esc_html__( 'Item Per Page', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'max'     => 18,
                'step'    => 1,
                'condition' => array(
                    'view_more_button' => 'true',
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'lastudio-elements' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_category',
            array(
                'label'   => esc_html__( 'Category', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

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

        /**
         * Use Retina Image
         * @var boolean
         */
        $use_retina = apply_filters( 'lastudio-elements/portfolio/use-retina-image', false );

        if ( $use_retina ) {
            $repeater->add_control(
                'item_image_2x',
                array(
                    'label'   => esc_html__( 'Retina Image', 'lastudio-elements' ),
                    'type'    => Controls_Manager::MEDIA,
                    'default' => array(
                        'url' => Utils::get_placeholder_image_src(),
                    ),
                    'dynamic' => array( 'active' => true ),
                )
            );
        }

        $repeater->add_control(
            'item_width',
            array(
                'label'   => esc_html__( 'Item Width', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => '1 width',
                    '2' => '2 width',
                    '0-5' => '1/2 width'
                ),
                'dynamic' => array( 'active' => true )
            )
        );

        $repeater->add_control(
            'item_height',
            array(
                'label'   => esc_html__( 'Item Height', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => '1 height',
                    '2' => '2 height',
                    '0-5' => '1/2 height',
                    '0-75' => '3/4 height'
                ),
                'dynamic' => array( 'active' => true )
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
            'item_desc',
            array(
                'label'   => esc_html__( 'Description', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_button_text',
            array(
                'label'   => esc_html__( 'Link Text', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'More', 'lastudio-elements' ),
            )
        );

        $repeater->add_control(
            'item_button_url',
            array(
                'label'       => esc_html__( 'Link Url', 'lastudio-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'default' => array(
                    'url' => '',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_el_class',
            array(
                'label'   => esc_html__( 'CSS Class', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true )
            )
        );

        $this->add_control(
            'image_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => array_values( $repeater->get_controls() ),
                'default'     => array(
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #1', 'lastudio-elements' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #2', 'lastudio-elements' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #3', 'lastudio-elements' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #4', 'lastudio-elements' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #5', 'lastudio-elements' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #6', 'lastudio-elements' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-elements' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->add_control(
            'title_html_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'   => esc_html__( 'H1', 'lastudio-elements' ),
                    'h2'   => esc_html__( 'H2', 'lastudio-elements' ),
                    'h3'   => esc_html__( 'H3', 'lastudio-elements' ),
                    'h4'   => esc_html__( 'H4', 'lastudio-elements' ),
                    'h5'   => esc_html__( 'H5', 'lastudio-elements' ),
                    'h6'   => esc_html__( 'H6', 'lastudio-elements' ),
                    'div'  => esc_html__( 'div', 'lastudio-elements' ),
                    'span' => esc_html__( 'span', 'lastudio-elements' ),
                    'p'    => esc_html__( 'p', 'lastudio-elements' ),
                ),
                'default' => 'h4',
                'separator' => 'before',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_carousel',
            array(
                'label' => esc_html__( 'Carousel', 'lastudio-elements' ),
                'condition' => array(
                    'layout_type' => array(
                        'grid',
                        'list'
                    )
                )
            )
        );

        $this->add_control(
            'carousel_enabled',
            array(
                'label'        => esc_html__( 'Enable Carousel', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_control(
            'slides_to_scroll',
            array(
                'label'     => esc_html__( 'Slides to Scroll', 'lastudio-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => lastudio_elements_tools()->get_select_range( 6 ),
                'condition' => array(
                    'columns!' => '1',
                ),
            )
        );

        $this->add_control(
            'arrows',
            array(
                'label'        => esc_html__( 'Show Arrows Navigation', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'prev_arrow',
            array(
                'label'   => esc_html__( 'Prev Arrow Icon', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fa fa-angle-left',
                'options' => lastudio_elements_tools()->get_available_prev_arrows_list(),
                'condition' => array(
                    'arrows' => 'true',
                ),
            )
        );

        $this->add_control(
            'next_arrow',
            array(
                'label'   => esc_html__( 'Next Arrow Icon', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fa fa-angle-right',
                'options' => lastudio_elements_tools()->get_available_next_arrows_list(),
                'condition' => array(
                    'arrows' => 'true',
                ),
            )
        );

        $this->add_control(
            'dots',
            array(
                'label'        => esc_html__( 'Show Dots Navigation', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'pause_on_hover',
            array(
                'label'        => esc_html__( 'Pause on Hover', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => esc_html__( 'Autoplay', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'autoplay_speed',
            array(
                'label'     => esc_html__( 'Autoplay Speed', 'lastudio-elements' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5000,
                'condition' => array(
                    'autoplay' => 'true',
                ),
            )
        );

        $this->add_control(
            'infinite',
            array(
                'label'        => esc_html__( 'Infinite Loop', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'effect',
            array(
                'label'   => esc_html__( 'Effect', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => array(
                    'slide' => esc_html__( 'Slide', 'lastudio-elements' ),
                    'fade'  => esc_html__( 'Fade', 'lastudio-elements' ),
                ),
                'condition' => array(
                    'columns' => '1',
                ),
            )
        );

        $this->add_control(
            'speed',
            array(
                'label'   => esc_html__( 'Animation Speed', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 500,
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_portfolio_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'item_margin',
            array(
                'label' => esc_html__( 'Items Margin', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'default' => [
                    'size' => 10,
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner']          => 'margin: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['list_container_wrap'] => 'margin: -{{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
            )
        );

        $this->add_responsive_control(
            'item_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'item_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
            )
        );

        $this->end_controls_section();

        /**
         * Filter Style Section
         */
        $this->start_controls_section(
            'section_portfolio_overlay_style',
            array(
                'label'      => esc_html__( 'Filters', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'filters_container_styles_heading',
            array(
                'label'     => esc_html__( 'Filters Container Styles', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filters_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_responsive_control(
            'filters_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filters_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filters_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_responsive_control(
            'filters_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filters_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_control(
            'filters_items_styles_heading',
            array(
                'label'     => esc_html__( 'Filters Items Styles', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'filters_items_separator_icon',
            array(
                'label'       => esc_html__( 'Separator Icon', 'lastudio-elements' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'file'        => '',
                'default'     => 'fa fa-circle',
            )
        );

        $this->add_control(
            'filter_items_separator_color',
            array(
                'label' => esc_html__( 'Separator Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_items_separator_size',
            array(
                'label' => esc_html__( 'Separator Size', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filters_items_aligment',
            array(
                'label'       => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'label_block' => false,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-arrow-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters_wrap'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_filter_item' );

        $this->start_controls_tab(
            'tab_filter_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'filter_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'],
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->add_responsive_control(
            'filter_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->add_responsive_control(
            'filter_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_item_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'filter_color_hover',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography_hover',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'filter_padding_hover',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin_hover',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'filter_border_radius_hover',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_item_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'filter_color_active',
            array(
                'label' => esc_html__( 'Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography_active',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_responsive_control(
            'filter_padding_active',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin_active',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border_active',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_responsive_control(
            'filter_border_radius_active',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Content Style Section
         */
        $this->start_controls_section(
            'section_portfolio_content_style',
            array(
                'label'      => esc_html__( 'Content', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'section_portfolio_content_wrapper_heading',
            array(
                'label'     => esc_html__( 'Container', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_container_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->add_responsive_control(
            'content_wrapper_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_wrapper_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'content_wrapper_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->add_responsive_control(
            'content_wrapper_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'content_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->end_controls_section();


        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_responsive_control(
            'title_padding',
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
            'title_margin',
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
            'title_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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

        $this->end_controls_section();

        /**
         * Category Style Section
         */
        $this->start_controls_section(
            'section_category_style',
            array(
                'label'      => esc_html__( 'Category', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'category_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['category'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'category_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} ' . $css_scheme['category'],
            )
        );

        $this->add_responsive_control(
            'category_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['category'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'category_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['category'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'category_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                    '{{WRAPPER}} ' . $css_scheme['category'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Description Style Section
         */
        $this->start_controls_section(
            'section_desc_style',
            array(
                'label'      => esc_html__( 'Description', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-elements' ),
                'type'   => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
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
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->add_responsive_control(
            'desc_padding',
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
            'desc_margin',
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
            'desc_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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

        /**
         * More Button Section
         */

        $this->start_controls_section(
            'section_portfolio_more_button_style',
            array(
                'label'      => esc_html__( 'More Button', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_more_button_style' );

        $this->start_controls_tab(
            'tab_more_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'more_button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'more_button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'more_button_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['view_more'],
            )
        );

        $this->add_responsive_control(
            'more_button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'more_button_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'more_button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'more_button_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['view_more'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'more_button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['view_more'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_more_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_control(
            'more_button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'more_button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'more_button_hover_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['view_more'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'more_button_hover_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'more_button_hover_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'more_button_hover_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'more_button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'more_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Arrow Sections
         */

        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'      => esc_html__( 'Carousel Arrows', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_prev',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow',
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

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_next_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_hover_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow:hover',
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

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'prev_arrow_position',
            array(
                'label'     => esc_html__( 'Prev Arrow Position', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'prev_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio-elements' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_vert_position' => 'top',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_bottom_position',
            array(
                'label'      => esc_html__( 'Bottom Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_vert_position' => 'bottom',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->add_control(
            'prev_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio-elements' ),
                    'right' => esc_html__( 'Right', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->add_control(
            'next_arrow_position',
            array(
                'label'     => esc_html__( 'Next Arrow Position', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'next_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio-elements' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'next_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_vert_position' => 'top',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_bottom_position',
            array(
                'label'      => esc_html__( 'Bottom Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_vert_position' => 'bottom',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->add_control(
            'next_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio-elements' ),
                    'right' => esc_html__( 'Right', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'next_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dots_style',
            array(
                'label'      => esc_html__( 'Carousel Dots', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li span',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_3,
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
            'tab_dots_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_hover',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li span:hover',
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
            'tab_dots_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_active',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li.slick-active span',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_4,
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

        $this->add_control(
            'dots_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 5,
                    'unit' => 'px',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                ),
                'separator' => 'before',
            )
        );

        $this->add_control(
            'dots_margin',
            array(
                'label'      => esc_html__( 'Dots Box Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'dots_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start' => array(
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
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

    }

    public function generate_carousel_setting_json(){
        $settings = $this->get_settings();

        $json_data = '';

        if ( 'yes' !== $settings['carousel_enabled'] ) {
            return $json_data;
        }

        $is_rtl = is_rtl();

        $desktop_col = absint( $settings['columns'] );
        $laptop_col = absint( $settings['columns_laptop'] );
        $tablet_col = absint( $settings['columns_tablet'] );
        $tablet_portrait_col = absint( $settings['columns_width800'] );
        $mobile_col = absint( $settings['columns_mobile'] );
        $mobile_portrait_col = $mobile_col;

        if($laptop_col == 0){
            $laptop_col = $desktop_col;
        }
        if($tablet_col == 0){
            $tablet_col = $laptop_col;
        }
        if($tablet_portrait_col == 0){
            $tablet_portrait_col = $tablet_col;
        }
        if($mobile_col == 0){
            $mobile_col = 1;
        }
        if($mobile_portrait_col == 0){
            $mobile_portrait_col = $mobile_col;
        }

        $slidesToShow = array(
            'desktop'           => $desktop_col,
            'laptop'            => $laptop_col,
            'tablet'            => $tablet_col,
            'tablet_portrait'   => $tablet_portrait_col,
            'mobile'            => $mobile_col,
            'mobile_portrait'   => $mobile_portrait_col
        );

        $options = array(
            'slidesToShow'   => $slidesToShow,
            'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
            'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
            'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
            'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
            'speed'          => absint( $settings['speed'] ),
            'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
            'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
            'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
            'prevArrow'      => lastudio_elements_tools()->get_carousel_arrow(
                array( $settings['prev_arrow'], 'prev-arrow' )
            ),
            'nextArrow'      => lastudio_elements_tools()->get_carousel_arrow(
                array( $settings['next_arrow'], 'next-arrow' )
            ),
            'rtl' => $is_rtl,
        );

        if ( 1 === absint( $settings['columns'] ) ) {
            $options['fade'] = ( 'fade' === $settings['effect'] );
        }

        $json_data = htmlspecialchars( json_encode( $options ) );

        return $json_data;

    }

    /**
     * Generate setting json
     *
     * @return string
     */
    public function generate_setting_json() {
        $module_settings = $this->get_settings();

        $desktop_col = absint( $module_settings['columns'] );
        $laptop_col = absint( $module_settings['columns_laptop'] );
        $tablet_col = absint( $module_settings['columns_tablet'] );
        $tablet_portrait_col = absint( $module_settings['columns_width800'] );
        $mobile_col = absint( $module_settings['columns_mobile'] );
        $mobile_portrait_col = $mobile_col;

        if($laptop_col == 0){
            $laptop_col = $desktop_col;
        }
        if($tablet_col == 0){
            $tablet_col = $laptop_col;
        }
        if($tablet_portrait_col == 0){
            $tablet_portrait_col = $tablet_col;
        }
        if($mobile_col == 0){
            $mobile_col = 1;
        }
        if($mobile_portrait_col == 0){
            $mobile_portrait_col = $mobile_col;
        }



        $settings = array(
            'layoutType'            => $module_settings['layout_type'],
            'columns'               => $desktop_col,
            'columnsLaptop'         => $laptop_col,
            'columnsTablet'         => $tablet_col,
            'columnsTabletPortrait' => $tablet_portrait_col,
            'columnsMobile'         => $mobile_col,
            'columnsMobilePortrait' => $mobile_portrait_col,
            'perPage'               => $module_settings['per_page'],
        );


        $settings = json_encode( $settings );

        return $settings;
    }

    /**
     * Get loop image html
     *
     * @return html
     */
    protected function __loop_image_item() {
        $item = $this->__processed_item;
        $params = [];

        if ( ! array_key_exists( 'item_image', $item ) ) {
            return false;
        }

        $image_item = $item[ 'item_image' ];

        if ( ! empty( $image_item['id'] ) ) {
            $image_data = wp_get_attachment_image_src( $image_item['id'], 'full' );

            $params[] = $image_data[0];
            $params[] = $image_data[1];
            $params[] = $image_data[2];
        }
        else {
            $params[] = $image_item['url'];
            $params[] = 1200;
            $params[] = 800;
        }

        if(empty($params[0])){
            $params[0] = $item[ 'item_image' ]['url'];
        }

        $srcset = '';

        if ( ! empty( $item[ 'item_image_2x' ] ) && ! empty( $item[ 'item_image_2x' ][ 'url' ] ) ) {
            $srcset = 'srcset="' . $item['item_image_2x'][ 'url' ] . ' 2x"';
        }

        $srcset = 'srcset="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="';

        return sprintf( '<img class="lastudio-portfolio__image-instance la-lazyload-image" src="%1$s" data-src="%1$s" width="%2$s" height="%3$s" %4$s alt="">', $params[0], $params[1], $params[2], $srcset );
    }

    /**
     * [get_justify_item_layout description]
     * @return [type] [description]
     */
    protected function get_justify_item_layout() {
        $item = $this->__processed_item;

        if ( ! array_key_exists( 'item_image', $item ) ) {
            return false;
        }

        $image_item = $item[ 'item_image' ];

        if ( ! empty( $item[ 'item_image_2x' ] ) && ! empty( $item[ 'item_image_2x' ][ 'url' ] ) ) {
            $image_item = $item[ 'item_image_2x' ];
        }

        $url = $image_item['url'];
        $width = 1200;
        $height = 800;
        $size = 'justify-size-1-4';

        if ( ! empty( $image_item['id'] ) ) {
            $image_data = wp_get_attachment_image_src( $image_item['id'], 'full' );

            $url = $image_data[0];
            $width = ! empty( $image_data[1] ) ? $image_data[1] : 1200;
            $height = ! empty( $image_data[2] ) ? $image_data[2] : 800;
        }

        $ratio = $width / $height;

        if ( $this->range_check( $ratio, 0, 1 ) ) {
            $size = 'justify-size-1-4';
        }

        if ( $this->range_check( $ratio, 1, 1.5 ) ) {
            $size = 'justify-size-2-4';
        }

        if ( $this->range_check( $ratio, 1.5, 2 ) ) {
            $size = 'justify-size-3-4';
        }

        if ( $this->range_check( $ratio, 2, 5 ) ) {
            $size = 'justify-size-4-4';
        }

        return $size;
    }

    /**
     * [get_justify_item_layout description]
     * @return [type] [description]
     */
    protected function get_masonry_item_width() {
        $item = $this->__processed_item;
        $item_width = isset($item['item_width']) ? $item['item_width'] : '1';
        return str_replace('-', '.', $item_width);
    }

    protected function get_masonry_item_height() {
        $item = $this->__processed_item;
        $item_height = isset($item['item_height']) ? $item['item_height'] : '1';
        return str_replace('-', '.', $item_height);
    }

    /**
     * [range_check description]
     * @param  [type] $val [description]
     * @param  [type] $min [description]
     * @param  [type] $max [description]
     * @return [type]      [description]
     */
    public function range_check( $val, $min, $max ) {
        return ( $val >= $min && $val <= $max );
    }

    /**
     * Get filters html
     *
     * @return html
     */
    public function render_filters() {
        $html = '';

        $separator_html = '';

        $separator_icon = $this->get_settings_for_display( 'filters_items_separator_icon' );

        $category_list = $this->generate_category_data();

        if ( empty( $category_list ) ) {
            return false;
        }

        $all_label = $this->get_settings_for_display( 'all_filter_label' );
        $all_label = ( ! empty( $all_label ) ) ? $all_label : esc_html__( 'All', 'lastudio-elements' );
        $all_label_slug = 'all';

        $html .= sprintf( '<div class="lastudio-portfolio__filter-item active" data-slug="%1$s"><span>%2$s</span></div>', $all_label_slug, $all_label );

        if ( ! empty( $separator_icon ) ) {
            $separator_html = sprintf( '<i class="lastudio-portfolio__filter-item-separator %s"></i>', $separator_icon );
        }

        foreach ( $category_list as $slug => $category_name ) {
            if($slug == $all_label_slug) continue;
            $html .= sprintf( '%3$s<div class="lastudio-portfolio__filter-item" data-slug="%1$s"><span>%2$s</span></div>', $slug, $category_name, $separator_html );
        }

        echo sprintf( '<div class="lastudio-portfolio__filter"><div class="lastudio-portfolio__filter-list">%s</div></div>', $html );
    }

    /**
     * [generate_category_data description]
     * @return [type] [description]
     */
    public function generate_category_data() {
        $category_list = [];

        $image_items = $this->get_settings_for_display( 'image_list' );

        foreach ( $image_items as $key => $item ) {
            if ( ! empty( $item['item_category'] ) ) {
                $categories = explode( ',', $item['item_category'] );

                foreach ( $categories as $key => $category ) {
                    $slug = sanitize_title( $category );

                    if ( ! array_key_exists( $slug, $category_list ) ) {
                        $category_list[ $slug ] = $category;
                    }
                }
            }
        }

        return $category_list;
    }

    /**
     * [get_item_slug description]
     *
     * @param  [type] $item_data [description]
     * @return [type]            [description]
     */
    public function get_item_slug( $item_data ) {
        $slug_list = array( 'all' );

        if ( empty( $item_data ) || empty( $item_data['item_category'] ) ) {
            return $slug_list;
        }

        $categories = explode( ',', $item_data['item_category'] );

        foreach ( $categories as $key => $category ) {
            $slug_list[] = sanitize_title( $category );
        }

        return $slug_list;
    }

    /**
     * Get filters html
     *
     * @return html
     */
    public function render_view_more_button() {
        $module_settings = $this->get_settings_for_display();
        $html = '';

        if ( ! filter_var( $module_settings['view_more_button'], FILTER_VALIDATE_BOOLEAN ) ) {
            return false;
        }

        $button_text = $module_settings['view_more_button_text'];

        $this->add_render_attribute( 'view_more_button', 'class', array(
            'elementor-button',
            'elementor-size-md',
            'lastudio-portfolio__view-more-button',
        ) );

        $format = apply_filters( 'lastudio-elements/portfolio/more-button-format', '<div class="lastudio-portfolio__view-more hidden-status"><div %1$s>%2$s</div></div>' );

        echo sprintf( $format, $this->get_render_attribute_string( 'view_more_button' ), $button_text );
    }

    /**
     * [get_item_category description]
     *
     * @return [type] [description]
     */
    public function get_item_slug_list() {
        $item = $this->__processed_item;

        $slug = $this->get_item_slug( $item );

        return json_encode( $slug, JSON_FORCE_OBJECT );
    }

    /**
     * [__generate_item_button description]
     * @return [type] [description]
     */
    public function __generate_item_button() {

        $settings = $this->__processed_item;

        $button_url  = $settings[ 'item_button_url' ];
        $button_text = $settings[ 'item_button_text' ];

        if ( empty( $button_text ) || empty( $button_url['url'] ) ) {
            return false;
        }

        $button_instance = 'button-instance-' . $this->item_counter;

        $this->add_render_attribute( $button_instance, 'class', array(
            'elementor-button',
            'elementor-size-md',
            'lastudio-portfolio__button',
        ) );

        $this->add_render_attribute( $button_instance, 'href', $button_url['url'] );

        if ( $button_url['is_external'] ) {
            $this->add_render_attribute( $button_instance, 'target', '_blank' );
        }

        if ( ! empty( $button_url['nofollow'] ) ) {
            $this->add_render_attribute( $button_instance, 'rel', 'nofollow' );
        }

        $format = apply_filters( 'lastudio-elements/portfolio/action-button-format', '<a %1$s><span class="lastudio-portfolio__button-text">%2$s</span></a>' );

        return sprintf( $format, $this->get_render_attribute_string( $button_instance ), $button_text );
    }

    /**
     * [__get_item_category description]
     * @return [type] [description]
     */
    public function __get_item_category() {
        $settings = $this->get_settings_for_display();
        $processed_item = $this->__processed_item;
        $category_html = '';

        if(isset($settings['show_category_list']) && filter_var( $settings['show_category_list'], FILTER_VALIDATE_BOOLEAN )){
            $category_html = $processed_item['item_category'];
        }

        $category_html = $processed_item['item_category'];

        if(!empty($category_html)){
            return sprintf( '<p class="lastudio-portfolio__category">%s</p>', $category_html );
        }
        else{
            return $category_html;
        }

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

}