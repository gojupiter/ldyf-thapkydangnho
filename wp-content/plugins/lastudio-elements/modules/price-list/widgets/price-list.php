<?php
namespace Lastudio_Elements\Modules\PriceList\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Price_List Widget
 */
class Price_List extends Lastudio_Widget {

    public $__processed_item_index = 0;

    public function get_name() {
        return 'lastudio-price-list';
    }

    protected function get_widget_title() {
        return esc_html__( 'Price List', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-39';
    }

    protected function _register_controls() {
        $css_scheme = apply_filters(
            'lastudio-elements/price-list/css-scheme',
            array(
                'price_list'       => '.lastudio-price-list',
                'item'             => '.lastudio-price-list .price-list__item',
                'item_inner'       => '.lastudio-price-list .price-list__item-inner',
                'item_title'       => '.lastudio-price-list .price-list__item-title',
                'item_price'       => '.lastudio-price-list .price-list__item-price',
                'item_description' => '.lastudio-price-list .price-list__item-desc',
                'item_separator'   => '.lastudio-price-list .price-list__item-separator',
                'item_image_wrap'  => '.lastudio-price-list .price-list__item-img-wrap',
            )
        );

        $this->start_controls_section(
            'section_general',
            array(
                'label'      => esc_html__( 'General', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_CONTENT,
                'show_label' => false,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_price',
            array(
                'label'   => esc_html__( 'Price', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_text',
            array(
                'label'   => esc_html__( 'Description', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio-elements' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => '',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_url',
            array(
                'label'   => esc_html__( 'URL', 'lastudio-elements' ),
                'type'    => Controls_Manager::URL,
                'default' => array(
                    'url' => '',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'price_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => array_values( $repeater->get_controls() ),
                'default'     => array(
                    array(
                        'item_title' => esc_html__( 'Item #1', 'lastudio-elements' ),
                        'item_price' => esc_html__( '$12', 'lastudio-elements' ),
                        'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-elements' ),
                    ),
                    array(
                        'item_title' => esc_html__( 'Item #1', 'lastudio-elements' ),
                        'item_price' => esc_html__( '$12', 'lastudio-elements' ),
                        'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-elements' ),
                    ),
                    array(
                        'item_title' => esc_html__( 'Item #1', 'lastudio-elements' ),
                        'item_price' => esc_html__( '$12', 'lastudio-elements' ),
                        'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-elements' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            array(
                'label'      => esc_html__( 'Item', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'item_space_between',
            array(
                'label'      => esc_html__( 'Space Between Items (px)', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 150,
                    ),
                ),
                'default'    => array(
                    'size' => 15,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '+ .price-list__item' => 'margin-top: {{SIZE}}{{UNIT}};',
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
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
            )
        );

        $this->add_control(
            'item_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_content_alignment',
            array(
                'label'   => esc_html__( 'Content Vertical Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_inner'] => 'align-items: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'first_item_heading',
            array(
                'label'     => esc_html__( 'First Item', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'first_item_border_width',
            array(
                'label'      => esc_html__( 'Border Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'first_item_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':first-child' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'last_item_heading',
            array(
                'label'     => esc_html__( 'Last Item', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'last_item_border_width',
            array(
                'label'      => esc_html__( 'Border Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'last_item_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':last-child' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_title'],
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'title_vertical_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_title'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_price_style',
            array(
                'label'      => esc_html__( 'Price', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'price_min_width',
            array(
                'label'      => esc_html__( 'Price Minimal Width (px)', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 400,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'min-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'price_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_price'],
            )
        );

        $this->add_control(
            'price_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'price_background',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'price_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_price'],
            )
        );

        $this->add_control(
            'price_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'price_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_price'],
            )
        );

        $this->add_responsive_control(
            'price_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'price_vertical_alignment',
            array(
                'label'     => esc_html__( 'Vertical Alignment', 'lastudio-elements' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'price_alignment',
            array(
                'label'     => esc_html__( 'Text Alignment', 'lastudio-elements' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_description_style',
            array(
                'label'      => esc_html__( 'Description', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'description_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_description'],
            )
        );

        $this->add_control(
            'description_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_description'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'description_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_description'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'description_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
                'options'   => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center'  => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right'   => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_description'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_separator_style',
            array(
                'label'      => esc_html__( 'Separator', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'separator_border_type',
            array(
                'label'     => esc_html__( 'Separator Type', 'lastudio-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dotted',
                'options'   => array(
                    'none'   => esc_html__( 'None', 'lastudio-elements' ),
                    'solid'  => esc_html__( 'Solid', 'lastudio-elements' ),
                    'double' => esc_html__( 'Double', 'lastudio-elements' ),
                    'dotted' => esc_html__( 'Dotted', 'lastudio-elements' ),
                    'dashed' => esc_html__( 'Dashed', 'lastudio-elements' ),
                    'groove' => esc_html__( 'Groove', 'lastudio-elements' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'border-style: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'separator_border_width',
            array(
                'label'      => esc_html__( 'Separator Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 15,
                    ),
                ),
                'default'    => array(
                    'size' => 1,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'border-bottom-width: {{SIZE}}{{UNIT}}; border-top-width:0; border-right-width:0; border-left-width:0;',
                ),
            )
        );

        $this->add_control(
            'separator_border_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'separator_vertical_alignment',
            array(
                'label'     => esc_html__( 'Vertical Alignment', 'lastudio-elements' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'separator_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_style',
            array(
                'label'      => esc_html__( 'Image', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'image_offset',
            array(
                'label'      => esc_html__( 'Image Offset (px)', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_image_wrap'] => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_width',
            array(
                'label'      => esc_html__( 'Image Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                    '%'  => array(
                        'min' => 0,
                        'max' => 80,
                    ),
                ),
                'default'    => array(
                    'size' => 150,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_image_wrap'] => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_image_wrap'],
            )
        );

        $this->add_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_image_wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_image_wrap'],
            )
        );

        $this->end_controls_section();

    }

    public function __open_price_item_link( $url_key ) {

        call_user_func( array( $this, sprintf( '__open_price_item_link_%s', $this->__context ) ), $url_key );

    }

    public function __open_price_item_link_format() {
        return '<a href="%1$s" class="price-list__item-link"%2$s%3$s>';
    }

    public function __open_price_item_link_render( $url_key ) {

        $item = $this->__processed_item;

        if ( empty( $item[ $url_key ]['url'] ) ) {
            return;
        }

        printf(
            $this->__open_price_item_link_format(),
            $item[ $url_key ]['url'],
            ( ! empty( $item[ $url_key ]['is_external'] ) ? ' target="_blank"' : '' ),
            ( ! empty( $item[ $url_key ]['nofollow'] ) ? ' rel="nofollow"' : '' )
        );

    }

    public function __open_price_item_link_edit( $url_key ) {

        echo '<# if ( item.' . $url_key . '.url ) { #>';
        printf(
            $this->__open_price_item_link_format(),
            '{{{ item.' . $url_key . '.url }}}',
            '<# if ( item.' . $url_key . '.is_external ) { #> target="_blank"<# } #>',
            '<# if ( item.' . $url_key . '.nofollow ) { #> rel="nofollow"<# } #>'
        );
        echo '<# } #>';

    }

    public function __close_price_item_link( $url_key ) {

        call_user_func( array( $this, sprintf( '__close_price_item_link_%s', $this->__context ) ), $url_key );

    }

    public function __close_price_item_link_render( $url_key ) {

        $item = $this->__processed_item;

        if ( empty( $item[ $url_key ]['url'] ) ) {
            return;
        }

        echo '</a>';

    }

    public function __close_price_item_link_edit( $url_key ) {

        echo '<# if ( item.' . $url_key . '.url ) { #>';
        echo '</a>';
        echo '<# } #>';

    }

    public function get_item_inline_editing_attributes( $settings_item_key, $repeater_item_key, $index, $classes ) {
        $item_key = $this->get_repeater_setting_key( $settings_item_key, $repeater_item_key, $index );
        $this->add_render_attribute( $item_key, [ 'class' => $classes ] );
        $this->add_inline_editing_attributes( $item_key, 'basic' );

        return $this->get_render_attribute_string( $item_key );
    }

    protected function render() {
        $this->__context = 'render';

        $this->__open_wrap();
        include $this->__get_global_template( 'index' );
        $this->__close_wrap();

        $this->__processed_item_index = 0;
    }

}