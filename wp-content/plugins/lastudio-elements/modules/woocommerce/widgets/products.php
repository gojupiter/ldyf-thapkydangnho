<?php
namespace Lastudio_Elements\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Lastudio_Elements\Modules\QueryControl\Controls\Group_Control_Posts;
use Lastudio_Elements\Modules\QueryControl\Module;
use Lastudio_Elements\Modules\Woocommerce\Classes\Products_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Products extends Products_Base {

    public function get_ajax_object_type(){
        return 'product';
    }

	public function get_name() {
		return 'lastudio-products';
	}

	public function get_title() {
		return __( 'LaStudio Products', 'lastudio-elements' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'archive' ];
	}

    public function get_script_depends() {
        return [
            'lastudio-elements'
        ];
    }

	public function on_export( $element ) {
		$element = Group_Control_Posts::on_export_remove_setting_from_element( $element, 'lastudio_query' );

		return $element;
	}

	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'lastudio-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Posts::get_type(),
			[
				'name' => 'query',
				'post_type' => 'product',
				'fields_options' => [
					'post_type' => [
						'default' => 'product',
						'options' => [
							'current_query' => __( 'Current Query', 'lastudio-elements' ),
							'product' => __( 'Latest Products', 'lastudio-elements' ),
							'sale' => __( 'Sale', 'lastudio-elements' ),
							'featured' => __( 'Featured', 'lastudio-elements' ),
							'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio-elements' ),
						],
					],
					'product_cat_ids' => [
						'condition' => [
							'post_type!' => [
								'current_query',
								'by_id',
							],
						],
					],
					'product_tag_ids' => [
						'condition' => [
							'post_type!' => [
								'current_query',
								'by_id',
							],
						],
					],
				],
				'exclude' => [
					'authors',
				],
			]
		);

		$this->add_control(
			'advanced',
			[
				'label' => __( 'Advanced', 'lastudio-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'lastudio-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'lastudio-elements' ),
					'title' => __( 'Title', 'lastudio-elements' ),
					'price' => __( 'Price', 'lastudio-elements' ),
					'popularity' => __( 'Popularity', 'lastudio-elements' ),
					'rating' => __( 'Rating', 'lastudio-elements' ),
					'rand' => __( 'Random', 'lastudio-elements' ),
					'menu_order' => __( 'Menu Order', 'lastudio-elements' ),
				],
				'condition' => [
					'query_post_type!' => 'current_query',
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'lastudio-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'lastudio-elements' ),
					'desc' => __( 'DESC', 'lastudio-elements' ),
				],
				'condition' => [
					'query_post_type!' => 'current_query',
				],
			]
		);

		Module::add_exclude_controls( $this );

		$this->end_controls_section();
	}

	protected function _register_controls() {

        $grid_style = apply_filters(
            'lastudio-elements/products/control/grid_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio-elements' ),
                '2' => esc_html__( 'Type-2', 'lastudio-elements' )
            )
        );
        $masonry_style = apply_filters(
            'lastudio-elements/products/control/masonry_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio-elements' ),
                '2' => esc_html__( 'Type-2', 'lastudio-elements' )
            )
        );
        $list_style = apply_filters(
            'lastudio-elements/products/control/list_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio-elements' ),
                '2' => esc_html__( 'Type-2', 'lastudio-elements' )
            )
        );

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'lastudio-elements' ),
			]
		);

        $this->add_control(
            'layout',
            array(
                'label'     => esc_html__( 'Layout', 'lastudio-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'grid',
                'render_type' => 'template',
                'options'   => [
                    'grid'      => __( 'Grid', 'plugin-domain' ),
                    'masonry'   => __( 'Masonry', 'plugin-domain' ),
                    'list'      => __( 'List', 'plugin-domain' ),
                ]
            )
        );

        $this->add_control(
            'grid_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $grid_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'grid'
                ]
            )
        );

        $this->add_control(
            'masonry_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $masonry_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'masonry'
                ]
            )
        );

        $this->add_control(
            'list_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $list_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'list'
                ]
            )
        );

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'lastudio-elements' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'default' => 4,
                'render_type' => 'template',
                'condition' => [
                    'layout!' => 'list'
                ]
			]
		);

		$this->add_control(
			'limit',
			[
                'label' => __( 'Limit', 'lastudio-elements' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 100,
                'default' => -1,
                'render_type' => 'template'
			]
		);

        $this->add_control(
            'enable_custom_image_size',
            [
                'label' => __( 'Enable Custom Image Size', 'lastudio-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'lastudio-elements' ),
                'default'    => 'shop_catalog',
                'options'    => lastudio_elements_tools()->get_image_sizes(),
                'condition' => [
                    'enable_custom_image_size' => 'yes'
                ]
            )
        );

        $this->add_control(
            'disable_alt_image',
            [
                'label' => __( 'Disable Crossfade Image Effect', 'lastudio-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => ''
            ]
        );

		$this->add_control(
			'paginate',
			[
				'label' => __( 'Pagination', 'lastudio-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->add_control(
			'paginate_as_loadmore',
			[
				'label' => __( 'Use Load More', 'lastudio-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->add_control(
			'loadmore_text',
			[
				'label' => __( 'Load More Text', 'lastudio-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Load More',
				'condition' => [
					'paginate' => 'yes',
					'paginate_as_loadmore' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->register_query_controls();

		$this->_register_carousel_controls(
		    [
		        'layout' => 'grid'
            ]
        );

		parent::_register_controls();

		$this->_register_carousel_arrows_dots_style();

		$this->_register_masonry_custom_layout();
	}

	protected function _register_masonry_custom_layout(){

        $repeater = new Repeater();

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
                'dynamic' => array( 'active' => true )
            )
        );

        $this->start_controls_section(
            'section_masonry',
            array(
                'label' => esc_html__( 'Masonry Setting', 'lastudio-elements' ),
                'condition' => [
                    'layout' => 'masonry'
                ]
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
                    'layout' => 'masonry'
                )
            )
        );

        $this->add_control(
            'masonry_container_width',
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
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_base_width',
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
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_base_height',
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
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_sizes',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_title'  => esc_html__( 'Size 1x1', 'lastudio-elements' ),
                        'item_width'  => 1,
                        'item_height'  => 1
                    ),
                    array(
                        'item_title'  => esc_html__( 'Size 2x2', 'lastudio-elements' ),
                        'item_width'  => 2,
                        'item_height'  => 2
                    ),
                    array(
                        'item_title'  => esc_html__( 'Size 1x1', 'lastudio-elements' ),
                        'item_width'  => 1,
                        'item_height'  => 1
                    ),
                    array(
                        'item_title'  => esc_html__( 'Size 1x1', 'lastudio-elements' ),
                        'item_width'  => 1,
                        'item_height'  => 1
                    )
                ),
                'title_field' => '{{{ item_title }}}',
                'condition' => array(
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->end_controls_section();

    }

	protected function render() {

		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // WPCS: override ok.
		}

		$settings = $this->get_settings();

        $settings['carousel_setting'] = $this->_generate_carousel_setting_json();
        $settings['unique_id'] = $this->get_id();

		$shortcode = new Products_Renderer( $settings, 'products' );

		$content = $shortcode->get_content();

		if ( $content ) {
			echo $content;
		}
		elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
		}
	}

	public function render_plain_content() {}
}
