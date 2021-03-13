<?php
namespace Lastudio_Elements\Modules\InstagramGallery\Widgets;

if (!defined('WPINC')) {
    die;
}

use Lastudio_Elements\Base\Lastudio_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;


/**
 * Instagram_Gallery Widget
 */
class Instagram_Gallery extends Lastudio_Widget {

    /**
     * Instagram API-server URL.
     *
     * @since 1.0.0
     * @var string
     */
    private $api_url = 'https://www.instagram.com/';

    /**
     * Request config
     *
     * @var array
     */
    public $config = array();

    public function get_name() {
        return 'lastudio-instagram-gallery';
    }

    protected function get_widget_title() {
        return esc_html__( 'Instagram', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-30';
    }

    public function get_script_depends() {
        return [
            'lastudio-salvattore',
            'lastudio-elements'
        ];
    }

    protected function _register_controls() {

        $css_scheme = apply_filters(
            'lastudio-elements/instagram-gallery/css-scheme',
            array(
                'instance'       => '.lastudio-instagram-gallery__instance',
                'image_instance' => '.lastudio-instagram-gallery__image',
                'item'           => '.lastudio-instagram-gallery__item',
                'inner'          => '.lastudio-instagram-gallery__inner',
                'content'        => '.lastudio-instagram-gallery__content',
                'caption'        => '.lastudio-instagram-gallery__caption',
                'meta'           => '.lastudio-instagram-gallery__meta',
                'meta_item'      => '.lastudio-instagram-gallery__meta-item',
                'meta_icon'      => '.lastudio-instagram-gallery__meta-icon',
                'meta_label'     => '.lastudio-instagram-gallery__meta-label',
                'slick_list'       => '.lastudio-carousel .slick-list',
            )
        );

        $this->start_controls_section(
            'section_instagram_settings',
            array(
                'label' => esc_html__( 'Instagram Settings', 'lastudio-elements'  ),
            )
        );

        $this->add_control(
            'endpoint',
            array(
                'label'   => esc_html__( 'What to display', 'lastudio-elements'  ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hashtag',
                'options' => array(
                    'hashtag'  => esc_html__( 'Tagged Photos', 'lastudio-elements'  ),
                    'self'     => esc_html__( 'My Photos', 'lastudio-elements'  ),
                ),
            )
        );

        if(!shortcode_exists('instagram-feed')){
            $this->add_control(
                'set_key',
                array(
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => sprintf(
                        esc_html__( 'Please install `%1$s` to use this widget', 'lastudio-elements'  ),
                        '<a target="_blank" href="https://wordpress.org/plugins/instagram-feed/">' . esc_html__( 'Smash Balloon Social Photo Feed', 'lastudio-elements'  ) . '</a>'
                    ),
                    'condition' => array(
                        'endpoint' => 'self',
                    )
                )
            );
        }

        $this->add_control(
            'hashtag',
            array(
                'label' => esc_html__( 'Hashtag (enter without `#` symbol)', 'lastudio-elements'  ),
                'label_block' => true,
                'type'  => Controls_Manager::TEXT,
                'condition' => array(
                    'endpoint' => 'hashtag',
                )
            )
        );

        $this->add_control(
            'self',
            array(
                'label' => esc_html__( 'Username', 'lastudio-elements'  ),
                'type'  => Controls_Manager::TEXT,
                'condition' => array(
                    'endpoint' => '_self',
                ),
            )
        );

        $this->add_control(
            'cache_timeout',
            array(
                'label'   => esc_html__( 'Cache Timeout', 'lastudio-elements'  ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hour',
                'options' => array(
                    'none'   => esc_html__( 'None', 'lastudio-elements'  ),
                    'minute' => esc_html__( 'Minute', 'lastudio-elements'  ),
                    'hour'   => esc_html__( 'Hour', 'lastudio-elements'  ),
                    'day'    => esc_html__( 'Day', 'lastudio-elements'  ),
                    'week'   => esc_html__( 'Week', 'lastudio-elements'  ),
                ),
                'condition' => array(
                    'endpoint' => 'hashtag',
                )
            )
        );

        $this->add_control(
            'photo_size',
            array(
                'label'   => esc_html__( 'Photo Size', 'lastudio-elements'  ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'high',
                'options' => array(
                    'thumbnail' => esc_html__( 'Thumbnail (150x150)', 'lastudio-elements'  ),
                    'low'       => esc_html__( 'Low (320x320)', 'lastudio-elements'  ),
                    'standard'  => esc_html__( 'Standard (640x640)', 'lastudio-elements'  ),
                    'high'      => esc_html__( 'High (original)', 'lastudio-elements'  ),
                ),
            )
        );

        $this->add_control(
            'posts_counter',
            array(
                'label'   => esc_html__( 'Limit (Maximum display of 12 posts)', 'lastudio-elements'  ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'max'     => 12,
                'step'    => 1,
            )
        );

        $this->add_control(
            'post_link',
            array(
                'label'        => esc_html__( 'Enable linking photos', 'lastudio-elements'  ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements'  ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements'  ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'post_caption',
            array(
                'label'        => esc_html__( 'Enable caption', 'lastudio-elements'  ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements'  ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements'  ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'post_caption_length',
            array(
                'label'   => esc_html__( 'Caption length', 'lastudio-elements'  ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 50,
                'min'     => 1,
                'max'     => 300,
                'step'    => 1,
                'condition' => array(
                    'post_caption' => 'yes',
                    'endpoint' => 'hashtag',
                ),
            )
        );

        $this->add_control(
            'post_comments_count',
            array(
                'label'        => esc_html__( 'Enable Comments Count', 'lastudio-elements'  ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements'  ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements'  ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition' => array(
                    'endpoint' => 'hashtag',
                ),
            )
        );

        $this->add_control(
            'post_likes_count',
            array(
                'label'        => esc_html__( 'Enable Likes Count', 'lastudio-elements'  ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements'  ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements'  ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition' => array(
                    'endpoint' => 'hashtag',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Layout Settings', 'lastudio-elements'  ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'lastudio-elements'  ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => array(
                    'masonry' => esc_html__( 'Masonry', 'lastudio-elements'  ),
                    'grid'    => esc_html__( 'Grid', 'lastudio-elements'  )
                ),
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio-elements'  ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6
                ),
                'condition' => array(
                    'layout_type' => array( 'masonry', 'grid' ),
                ),
            )
        );

        $this->add_responsive_control(
            'image_height',
            array(
                'label' => esc_html__( 'Image Height', 'lastudio-elements'  ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'default' => [
                    'size'  => 100,
                    'unit' => '%'
                ],
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-instagram-gallery__media' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio-elements'  ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right: -{{RIGHT}}{{UNIT}};',
                ),
                'default'    => array(
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                    'isLinked' => true
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements'  ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
            )
        );

        $this->add_responsive_control(
            'item_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
         * Caption Style Section
         */
        $this->start_controls_section(
            'section_caption_style',
            array(
                'label'      => esc_html__( 'Caption', 'lastudio-elements'  ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'caption_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-elements'  ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['caption'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'caption_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['caption'],
            )
        );

        $this->add_responsive_control(
            'caption_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['caption'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'caption_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['caption'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'caption_width',
            array(
                'label' => esc_html__( 'Caption Width', 'lastudio-elements'  ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => [
                    'size'  => 100,
                    'unit' => '%'
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['caption'] => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'caption_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements'  ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements'  ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements'  ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements'  ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['caption'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'caption_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-elements'  ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements'  ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements'  ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements'  ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['caption'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Meta Style Section
         */
        $this->start_controls_section(
            'section_meta_style',
            array(
                'label'      => esc_html__( 'Meta', 'lastudio-elements'  ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'meta_icon_color',
            array(
                'label'  => esc_html__( 'Icon Color', 'lastudio-elements'  ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta_icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'meta_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-elements'  ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em' ,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta_icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'meta_label_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-elements'  ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta_label'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'meta_label_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['meta_label'],
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'meta_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['meta'],
            )
        );

        $this->add_responsive_control(
            'meta_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta_item_margin',
            array(
                'label'      => __( 'Item Margin', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'meta_border',
                'label'       => esc_html__( 'Border', 'lastudio-elements'  ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['meta'],
            )
        );

        $this->add_responsive_control(
            'meta_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['meta'],
            )
        );

        $this->add_responsive_control(
            'meta_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements'  ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements'  ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements'  ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements'  ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Overlay Style Section
         */
        $this->start_controls_section(
            'section_overlay_style',
            array(
                'label'      => esc_html__( 'Overlay', 'lastudio-elements'  ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'show_on_hover',
            array(
                'label'        => esc_html__( 'Show on hover', 'lastudio-elements'  ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements'  ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements'  ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'] . ':before',
            )
        );

        $this->add_responsive_control(
            'overlay_paddings',
            array(
                'label'      => __( 'Padding', 'lastudio-elements'  ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Order Style Section
         */
        $this->start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Content Order and Alignment', 'lastudio-elements'  ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'caption_order',
            array(
                'label'   => esc_html__( 'Caption Order', 'lastudio-elements'  ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
                'max'     => 4,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['caption'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_order',
            array(
                'label'   => esc_html__( 'Meta Order', 'lastudio-elements'  ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 4,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['meta'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'cover_alignment',
            array(
                'label'   => esc_html__( 'Cover Content Vertical Alignment', 'lastudio-elements'  ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'lastudio-elements'  ),
                    'center'        => esc_html__( 'Center', 'lastudio-elements'  ),
                    'flex-end'      => esc_html__( 'Bottom', 'lastudio-elements'  ),
                    'space-between' => esc_html__( 'Space between', 'lastudio-elements'  ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['content'] => 'justify-content: {{VALUE}};',
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

    /**
     * Render gallery html.
     *
     * @return string
     */
    public function render_gallery() {
        $settings = $this->get_settings_for_display();

        if ( 'hashtag' === $settings['endpoint'] && empty( $settings['hashtag'] ) ) {
            return print esc_html__( 'Please, enter #hashtag.', 'lastudio-elements'  );
        }

        //        if ( 'self' === $settings['endpoint'] && empty($settings['self']) ) {
        //            return print esc_html__( 'Please, enter User Name.', 'lastudio-elements'  );
        //        }

        $html = '';
        $col_class = '';

        // Endpoint.
        $endpoint = $this->sanitize_endpoint();

        switch ( $settings['cache_timeout'] ) {
            case 'none':
                $cache_timeout = 1;
                break;

            case 'minute':
                $cache_timeout = MINUTE_IN_SECONDS;
                break;

            case 'hour':
                $cache_timeout = HOUR_IN_SECONDS;
                break;

            case 'day':
                $cache_timeout = DAY_IN_SECONDS;
                break;

            case 'week':
                $cache_timeout = WEEK_IN_SECONDS;
                break;

            default:
                $cache_timeout = HOUR_IN_SECONDS;
                break;
        }

        $this->config = array(
            'endpoint'            => $endpoint,
            'target'              => ( 'hashtag' === $endpoint ) ? sanitize_text_field( $settings[ $endpoint ] ) : sanitize_text_field( $settings[ $endpoint ] ),
            'posts_counter'       => $settings['posts_counter'],
            'post_link'           => filter_var( $settings['post_link'], FILTER_VALIDATE_BOOLEAN ),
            'photo_size'          => $settings['photo_size'],
            'post_caption'        => filter_var( $settings['post_caption'], FILTER_VALIDATE_BOOLEAN ),
            'post_caption_length' => ! empty( $settings['post_caption_length'] ) ? $settings['post_caption_length'] : 50,
            'post_comments_count' => filter_var( $settings['post_comments_count'], FILTER_VALIDATE_BOOLEAN ),
            'post_likes_count'    => filter_var( $settings['post_likes_count'], FILTER_VALIDATE_BOOLEAN ),
            'cache_timeout'       => $cache_timeout,
        );

        if($endpoint == 'hashtag'){
            $posts = $this->get_posts( $this->config );

            if ( ! empty( $posts ) ) {

                foreach ( $posts as $post_data ) {
                    $item_html   = '';
                    $link        = sprintf( $this->get_post_url(), $post_data['link'] );
                    $the_image   = $this->the_image_src( $post_data );
                    $the_caption = $this->the_caption( $post_data );
                    $the_meta    = $this->the_meta( $post_data );

                    $item_html = sprintf(
                        '<div class="lastudio-instagram-gallery__media">%1$s</div><div class="lastudio-instagram-gallery__content">%2$s%3$s</div>',
                        $the_image,
                        $the_caption,
                        $the_meta
                    );

                    if ( $this->config['post_link'] ) {
                        $link_format = '<a class="lastudio-instagram-gallery__link" href="%s" target="_blank" rel="nofollow">%s</a>';
                        $link_format = apply_filters( 'LaStudioElement/instagram-gallery/link-format', $link_format );

                        $item_html = sprintf( $link_format, esc_url( $link ), $item_html );
                    }

                    $html .= sprintf( '<div class="loop__item grid-item lastudio-instagram-gallery__item %s"><div class="lastudio-instagram-gallery__inner">%s</div></div>', $col_class, $item_html );
                }

            } else {
                $html .= sprintf(
                    '<div class="loop__item grid-item lastudio-instagram-gallery__item">%s</div>',
                    esc_html__( 'Posts not found', 'lastudio-elements'  )
                );
            }
        }
        elseif(shortcode_exists('instagram-feed')){
            $sc = '[instagram-feed showfollow=false showbutton=false showheader=false num="'.$settings['posts_counter'].'"]';
            $html = do_shortcode($sc);
            try{
                $doc = new \DOMDocument();
                $doc->loadHTML($html);
                $tmp_html = '';
                $aElements = $doc->getElementsByTagName('a');
                foreach ($aElements as $aElement) {
                    $item_html = sprintf(
                        '<div class="lastudio-instagram-gallery__media"><span class="lastudio-instagram-gallery__image la-lazyload-image" data-background-image="%1$s"></span></div><div class="lastudio-instagram-gallery__content">%2$s%3$s</div>',
                        $aElement->getAttribute('data-full-res'),
                        $this->config['post_caption'] ? '<div class="lastudio-instagram-gallery__caption">'.$aElement->textContent.'</div>' : '',
                        ''
                    );
                    if ( $this->config['post_link'] ) {
                        $link_format = '<a class="lastudio-instagram-gallery__link" href="%s" target="_blank" rel="nofollow">%s</a>';
                        $link_format = apply_filters( 'LaStudioElement/instagram-gallery/link-format', $link_format );

                        $item_html = sprintf( $link_format, esc_url( $aElement->getAttribute('href') ), $item_html );
                    }

                    $tmp_html .= sprintf( '<div class="loop__item grid-item lastudio-instagram-gallery__item %s"><div class="lastudio-instagram-gallery__inner">%s</div></div>', $col_class, $item_html );

                    $html = $tmp_html;
                }
                //$html = $tmp_html;
            }catch ( \Exception $e ){
                $html = sprintf(
                    '<div class="loop__item grid-item lastudio-instagram-gallery__item">%s</div>',
                    esc_html__( 'Posts not found', 'lastudio-elements'  )
                );
            }
        }
        echo $html;
    }

    /**
     * Display a HTML link with image.
     *
     * @since  1.0.0
     * @param  array $item Item photo data.
     * @return string
     */
    public function the_image( $item ) {

        $size = $this->get_settings_for_display( 'photo_size' );

        $thumbnail_resources = $item['thumbnail_resources'];

        if ( array_key_exists( $size, $thumbnail_resources ) ) {
            $width = $thumbnail_resources[ $size ]['config_width'];
            $height = $thumbnail_resources[ $size ]['config_height'];
            $post_photo_url = $thumbnail_resources[ $size ]['src'];
        } else {
            $width = isset( $item['dimensions']['width'] ) ? $item['dimensions']['width'] : '';
            $height = isset( $item['dimensions']['height'] ) ? $item['dimensions']['height'] : '';
            $post_photo_url = isset( $item['image'] ) ? $item['image'] : '';
        }

        if ( empty( $post_photo_url ) ) {
            return '';
        }

        $giflazy = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

        $photo_format = apply_filters( 'LaStudioElement/instagram-gallery/photo-format', '<img class="lastudio-instagram-gallery__image la-lazyload-image" src="%4$s" data-src="%1$s" width="%2$s" height="%3$s" srcset="%4$s" alt="">');

        $image = sprintf( $photo_format, $post_photo_url,  esc_attr($width), esc_attr($height), $giflazy);

        return $image;
    }

    /**
     * Display a HTML link with image.
     *
     * @since  1.0.0
     * @param  array $item Item photo data.
     * @return string
     */
    public function the_image_src( $item ) {

        $size = $this->get_settings_for_display( 'photo_size' );

        $thumbnail_resources = $item['thumbnail_resources'];

        if ( array_key_exists( $size, $thumbnail_resources ) ) {
            $width = $thumbnail_resources[ $size ]['config_width'];
            $height = $thumbnail_resources[ $size ]['config_height'];
            $post_photo_url = $thumbnail_resources[ $size ]['src'];
        } else {
            $width = isset( $item['dimensions']['width'] ) ? $item['dimensions']['width'] : '';
            $height = isset( $item['dimensions']['height'] ) ? $item['dimensions']['height'] : '';
            $post_photo_url = isset( $item['image'] ) ? $item['image'] : '';
        }

        if ( empty( $post_photo_url ) ) {
            return '';
        }


        $photo_format = apply_filters( 'LaStudioElement/instagram-gallery/photo-format-src', '<span class="lastudio-instagram-gallery__image la-lazyload-image" data-background-image="%1$s"></span>');

        $image = sprintf( $photo_format, $post_photo_url );

        return $image;
    }

    /**
     * Display a caption.
     *
     * @since  1.0.0
     * @param  array $item Item photo data.
     * @return string
     */
    public function the_caption( $item ) {

        if ( ! $this->config['post_caption'] || empty( $item['caption'] ) ) {
            return;
        }

        $format = apply_filters(
            'LaStudioElement/instagram-gallery/the-caption-format', '<div class="lastudio-instagram-gallery__caption">%s</div>'
        );

        return sprintf( $format, $item['caption'] );
    }

    /**
     * Display a meta.
     *
     * @since  1.0.0
     * @param  array $item Item photo data.
     * @return string
     */
    public function the_meta( $item ) {

        if ( ! $this->config['post_comments_count'] && ! $this->config['post_likes_count'] ) {
            return;
        }

        $meta_html = '';

        if ( $this->config['post_comments_count'] ) {
            $comments_icon = '<i class="dlicon ui-3_chat-46"></i>';
            $meta_html .= sprintf(
                '<div class="lastudio-instagram-gallery__meta-item lastudio-instagram-gallery__comments-count"><span class="lastudio-instagram-gallery__comments-icon lastudio-instagram-gallery__meta-icon">%s</span><span class="lastudio-instagram-gallery__comments-label lastudio-instagram-gallery__meta-label">%s</span></div>',
                $comments_icon,
                $item['comments']
            );
        }

        if ( $this->config['post_likes_count'] ) {
            $likes_icon = '<i class="dlicon ui-3_heart"></i>';
            $meta_html .= sprintf(
                '<div class="lastudio-instagram-gallery__meta-item lastudio-instagram-gallery__likes-count"><span class="lastudio-instagram-gallery__likes-icon lastudio-instagram-gallery__meta-icon">%s</span><span class="lastudio-instagram-gallery__likes-label lastudio-instagram-gallery__meta-label">%s</span></div>',
                $likes_icon,
                $item['likes']
            );
        }

        $format = apply_filters( 'LaStudioElement/instagram-gallery/the-meta-format', '<div class="lastudio-instagram-gallery__meta">%s</div>' );

        return sprintf( $format, $meta_html );
    }

    /**
     * Retrieve a photos.
     *
     * @since  1.0.0
     * @param  array $config Set of configuration.
     * @return array
     */
    public function get_posts( $config ) {

        $transient_key = md5( $this->get_transient_key() );

        $data = get_transient( $transient_key );

        if ( ! empty( $data ) && 1 !== $config['cache_timeout'] && array_key_exists( 'thumbnail_resources', $data[0] ) ) {
            return $data;
        }

        $response = $this->remote_get( $config );

        if ( is_wp_error( $response ) ) {
            return array();
        }

        $data = $this->get_response_data( $response );

        if ( empty( $data ) ) {
            return array();
        }

        set_transient( $transient_key, $data, $config['cache_timeout'] );

        return $data;
    }

    /**
     * Retrieve the raw response from the HTTP request using the GET method.
     *
     * @since  1.0.0
     * @return array|WP_Error
     */
    public function remote_get( $config ) {

        $url = $this->get_grab_url( $config );

        $server_api = 'https://la-studioweb.com/tools/ig.php';
        $server_api = add_query_arg('endpoint', $url, $server_api);

        $server_api = apply_filters('LaStudioElement/instagram-gallery/remote_get', $url, $server_api, $config, $this);

        $response = wp_remote_get( $server_api, array(
            'timeout'   => 60,
            'sslverify' => false
        ) );

        $response_code = wp_remote_retrieve_response_code( $response );

        if ( '' === $response_code ) {
            return new \WP_Error;
        }

        $result = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! is_array( $result ) ) {
            return new \WP_Error;
        }

        return $result;
    }

    /**
     * Get prepared response data.
     *
     * @param $response
     *
     * @return array
     */
    public function get_response_data( $response ) {

        $key = 'hashtag' == $this->config['endpoint'] ? 'hashtag' : 'user';

        if ( 'hashtag' === $key ) {
            $response = isset( $response['graphql'] ) ? $response['graphql'] : $response;
        }

        if(empty($response)){
            return array();
        }

        $response_items = ( 'hashtag' === $key ) ? $response[ $key ]['edge_hashtag_to_media']['edges'] : $response['graphql'][ $key ]['edge_owner_to_timeline_media']['edges'];

        if ( empty( $response_items ) ) {
            return array();
        }

        $data  = array();
        $nodes = array_slice(
            $response_items,
            0,
            $this->config['posts_counter'],
            true
        );

        foreach ( $nodes as $post ) {

            $_post               = array();
            $_post['link']       = $post['node']['shortcode'];
            $_post['image']      = $post['node']['thumbnail_src'];
            $_post['caption']    = isset( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ? wp_html_excerpt( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'], $this->config['post_caption_length'], '&hellip;' ) : '';
            $_post['comments']   = $post['node']['edge_media_to_comment']['count'];
            $_post['likes']      = $post['node']['edge_liked_by']['count'];
            $_post['dimensions'] = $post['node']['dimensions'];
            $_post['thumbnail_resources'] = $this->_generate_thumbnail_resources( $post );

            array_push( $data, $_post );
        }

        return $data;
    }

    /**
     * Generate thumbnail resources.
     *
     * @param $post_data
     *
     * @return array
     */
    public function _generate_thumbnail_resources( $post_data ) {
        $post_data = $post_data['node'];

        $thumbnail_resources = array(
            'thumbnail' => false,
            'low'       => false,
            'standard'  => false,
            'high'      => false,
        );

        if ( is_array( $post_data['thumbnail_resources'] ) && ! empty( $post_data['thumbnail_resources'] ) ) {
            foreach ( $post_data['thumbnail_resources'] as $key => $resources_data ) {

                if ( 150 === $resources_data['config_width'] ) {
                    $thumbnail_resources['thumbnail'] = $resources_data;

                    continue;
                }

                if ( 320 === $resources_data['config_width'] ) {
                    $thumbnail_resources['low'] = $resources_data;

                    continue;
                }

                if ( 640 === $resources_data['config_width'] ) {
                    $thumbnail_resources['standard'] = $resources_data;

                    continue;
                }
            }
        }

        if ( ! empty( $post_data['display_url'] ) ) {
            $thumbnail_resources['high'] = array(
                'src'           => $post_data['display_url'],
                'config_width'  => $post_data['dimensions']['width'],
                'config_height' => $post_data['dimensions']['height'],
            ) ;
        }

        return $thumbnail_resources;
    }

    /**
     * Retrieve a grab URL.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_grab_url( $config ) {

        if ( 'hashtag' == $config['endpoint'] ) {
            $url = sprintf( $this->get_tags_url(), $config['target'] );
            $url = add_query_arg( array( '__a' => 1 ), $url );

        } else {
            $url = sprintf( $this->get_self_url(), $config['target'] );
            $url = add_query_arg( array( '__a' => 1 ), $url );
        }

        return $url;
    }

    /**
     * Retrieve a URL for photos by hashtag.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_tags_url() {
        return apply_filters( 'LaStudioElement/instagram-gallery/get-tags-url', $this->api_url . 'explore/tags/%s/' );
    }

    /**
     * Retrieve a URL for self photos.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_self_url() {
        return apply_filters( 'LaStudioElement/instagram-gallery/get-self-url', $this->api_url . '%s/' );
    }

    /**
     * Retrieve a URL for post.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_post_url() {
        return apply_filters( 'LaStudioElement/instagram-gallery/get-post-url', $this->api_url . 'p/%s/' );
    }

    /**
     * sanitize endpoint.
     *
     * @since  1.0.0
     * @return string
     */
    public function sanitize_endpoint() {
        return in_array( $this->get_settings( 'endpoint' ) , array( 'hashtag', 'self' ) ) ? $this->get_settings( 'endpoint' ) : 'hashtag';
    }

    /**
     * Get transient key.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_transient_key() {
        return sprintf( 'lastudio_elements_instagram_%s_%s_posts_count_%s_caption_%s',
            $this->config['endpoint'],
            $this->config['target'],
            $this->config['posts_counter'],
            $this->config['post_caption_length']
        );
    }
    /**
     * Generate setting json
     *
     * @return string
     */
    public function generate_setting_json() {
        $module_settings = $this->get_settings();

        $settings = array(
            'layoutType'    => $module_settings['layout_type'],
            'columns'       => $module_settings['columns'],
            'columnsTablet' => $module_settings['columns_tablet'],
            'columnsMobile' => $module_settings['columns_mobile'],
        );

        $settings = json_encode( $settings );

        return sprintf( 'data-settings=\'%1$s\'', $settings );
    }
}