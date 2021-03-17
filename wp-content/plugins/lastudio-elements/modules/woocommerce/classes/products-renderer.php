<?php

namespace Lastudio_Elements\Modules\Woocommerce\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Products_Renderer extends \WC_Shortcode_Products {

	private $settings = [];
	private $is_added_product_filter = false;

	public function __construct( $settings = [], $type = 'products' ) {

		$this->settings = $settings;
		$this->type = $type;
		$this->attributes = $this->parse_attributes( [
			'columns' => $settings['columns'],
			'limit' => $settings['limit'],
			'paginate' => $settings['paginate'],
			'cache' => false,
		] );
		$this->query_args = $this->parse_query_args();
	}

	/**
	 * Override the original `get_query_results`
	 * with modifications that:
	 * 1. Remove `pre_get_posts` action if `is_added_product_filter`.
	 *
	 * @return bool|mixed|object
	 */

	protected function get_query_results() {
		$results = parent::get_query_results();
		// Start edit.
		if ( $this->is_added_product_filter ) {
			remove_action( 'pre_get_posts', [ wc()->query, 'product_query' ] );
		}
		// End edit.

		return $results;
	}

	protected function parse_query_args() {
		$settings = &$this->settings;
		$query_args = [
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows' => false === wc_string_to_bool( $this->attributes['paginate'] ),
		];

		if ( 'current_query' === $this->settings['query_post_type'] ) {
			if ( ! is_page( wc_get_page_id( 'shop' ) ) ) {
				$query_args = $GLOBALS['wp_query']->query_vars;
			}

			// Fix for parent::get_transient_name.
			if ( ! isset( $query_args['orderby'] ) ) {
				$query_args['orderby'] = '';
				$query_args['order'] = '';
			}

			add_action( 'pre_get_posts', [ wc()->query, 'product_query' ] );
			$this->is_added_product_filter = true;

		}
		else {
			$query_args = [
				'post_type' => 'product',
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
				'no_found_rows' => false === wc_string_to_bool( $this->attributes['paginate'] ),
				'orderby' => $settings['orderby'],
				'order' => strtoupper( $settings['order'] ),
			];

			$query_args['meta_query'] = WC()->query->get_meta_query();
			$query_args['tax_query'] = [];
			// @codingStandardsIgnoreEnd

			// Visibility.
			$this->set_visibility_query_args( $query_args );

			// SKUs.
			$this->set_featured_query_args( $query_args );

			// IDs.
			$this->set_ids_query_args( $query_args );

			// Set specific types query args.
			if ( method_exists( $this, "set_{$this->type}_query_args" ) ) {
				$this->{"set_{$this->type}_query_args"}( $query_args );
			}

			// Categories.
			$this->set_categories_query_args( $query_args );

			// Tags.
			$this->set_tags_query_args( $query_args );

			$query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );

			if ( 'yes' === $settings['paginate'] && 'yes' === $settings['allow_order'] ) {
				$ordering_args = WC()->query->get_catalog_ordering_args();
			} else {
				$ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
			}

			$query_args['orderby'] = $ordering_args['orderby'];
			$query_args['order'] = $ordering_args['order'];
			if ( $ordering_args['meta_key'] ) {
				$query_args['meta_key'] = $ordering_args['meta_key'];
			}

			$query_args['posts_per_page'] = $settings['limit'];
		} // End if().

		if ( 'yes' === $settings['paginate'] ) {
			$page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

//			if ( 'yes' !== $settings['allow_order'] ) {
//				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
//			}
//
//			if ( 'yes' !== $settings['show_result_count'] ) {
//				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
//			}
		}
		$query_args['posts_per_page'] = $settings['limit'];

		// Always query only IDs.
		$query_args['fields'] = 'ids';

		return $query_args;
	}

	protected function set_ids_query_args( &$query_args ) {
		switch ( $this->settings['query_post_type'] ) {
			case 'by_id':
				$post__in = $this->settings['query_posts_ids'];
				break;
			case 'sale':
				$post__in = wc_get_product_ids_on_sale();
				break;
		}

		if ( ! empty( $post__in ) ) {
			$query_args['post__in'] = $post__in;
			remove_action( 'pre_get_posts', [ wc()->query, 'product_query' ] );
		}
	}

	protected function set_categories_query_args( &$query_args ) {
		$query_type = $this->settings['query_post_type'];

		if ( 'by_id' === $query_type || 'current_query' === $query_type ) {
			return;
		}

		if ( ! empty( $this->settings['query_product_cat_ids'] ) ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'product_cat',
				'terms' => $this->settings['query_product_cat_ids'],
				'field' => 'term_id',
			];
		}
	}

	protected function set_tags_query_args( &$query_args ) {
		$query_type = $this->settings['query_post_type'];

		if ( 'by_id' === $query_type || 'current_query' === $query_type ) {
			return;
		}

		if ( ! empty( $this->settings['query_product_tag_ids'] ) ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'product_tag',
				'terms' => $this->settings['query_product_tag_ids'],
				'field' => 'term_id',
				'operator' => 'IN',
			];
		}
	}

	protected function set_featured_query_args( &$query_args ) {
		if ( 'featured' === $this->settings['query_post_type'] ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$query_args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => [ $product_visibility_term_ids['featured'] ],
			];
		}
	}


    protected function product_loop() {

        $globalWcLoopTmp            = array();
        $columns                    = absint( $this->attributes['columns'] ) ;
        $wrapper_classes            = $this->get_wrapper_classes( $columns );
        $layout                     = !empty($this->settings['layout']) ? $this->settings['layout'] : 'grid';
        $style                      = $this->settings[$layout . '_style'];

        $loopCssClass 	= array();
        $container_attr = $disable_alt_image = $image_size = false;
        if( 'yes' == $this->settings['enable_custom_image_size'] ) {
            $image_size = true;
        }
        if( 'yes' == $this->settings['disable_alt_image'] ) {
            $disable_alt_image = true;
        }
        if( $layout == 'grid' ){
            if( 'yes' == $this->settings['carousel_enabled'] ) {
                $container_attr = ' data-la_component="AutoCarousel" ';
                $container_attr .= ' data-slider_config="'. esc_attr($this->settings['carousel_setting']) .'"';
                $loopCssClass[] = 'lastudio-carousel js-el la-slick-slider';
            }
        }

        $globalWcLoopTmp['loop_layout']    = $layout;
        $globalWcLoopTmp['loop_style']     = $style;

        if($image_size){
            $globalWcLoopTmp['image_size'] = $this->settings['image_size'];
        }
        if($disable_alt_image){
            $globalWcLoopTmp['disable_alt_image'] = true;
        }

        $loopCssClass[] = 'products ul_products';

        if($layout != 'list'){
            if( $layout != 'masonry' || ($layout == 'masonry' && 'true' != $this->settings['enable_custom_masonry_layout']) ){
                $loopCssClass[] = 'grid-items';
                $loopCssClass[] = lastudio_elements_tools()->block_grid_legacy_classes(array(
                    'desk'  => $this->settings['columns'],
                    'lap'   => $this->settings['columns_laptop'],
                    'tab'   => $this->settings['columns_tablet'],
                    'tabp'  => $this->settings['columns_width800'],
                    'mob'   => $this->settings['columns_mobile'],
                    'mobp'   => $this->settings['columns_mobile'],
                ));
            }
            $loopCssClass[] = 'products-grid';
            $loopCssClass[] = 'products-grid-' . $style;

            if($layout == 'masonry'){

                $loopCssClass[] = 'js-el la-isotope-container';
                $loopCssClass[] = 'prods_masonry';

                if( 'true' == $this->settings['enable_custom_masonry_layout'] ) {
                    $loopCssClass[] = 'masonry__column-type-custom';
                    $container_attr  = ' data-la_component="AdvancedMasonry"';
                }
                else{
                    $container_attr  = ' data-la_component="DefaultMasonry"';
                }

                $container_attr .= ' data-item-width="' . ( !empty($this->settings['masonry_item_base_width']['size']) ? intval($this->settings['masonry_item_base_width']['size']) : 300 ) . '"';
                $container_attr .= ' data-item-height="' . ( !empty($this->settings['masonry_item_base_height']['size']) ? intval($this->settings['masonry_item_base_height']['size']) : 300 ) . '"';
                $container_attr .= ' data-container-width="' . ( !empty($this->settings['masonry_container_width']['size']) ? intval($this->settings['masonry_container_width']['size']) : 1170 ) . '"';
                $container_attr .= ' data-md-col="' . ($this->settings['columns_tablet'] ? $this->settings['columns_tablet'] : 1) . '"';
                $container_attr .= ' data-sm-col="' . ($this->settings['columns_width800'] ? $this->settings['columns_width800'] : 1) . '"';
                $container_attr .= ' data-xs-col="' . ($this->settings['columns_mobile'] ? $this->settings['columns_mobile'] : 1) . '"';
                $container_attr .= ' data-mb-col="' . ($this->settings['columns_mobile'] ? $this->settings['columns_mobile'] : 1) . '"';

                if( 'true' == $this->settings['enable_custom_masonry_layout'] ) {
                    $_item_sizes = $this->settings['masonry_item_sizes'];
                    $__new_item_sizes = array();
                    if(!empty($_item_sizes)){
                        foreach($_item_sizes as $k => $size){
                            $__new_item_sizes[$k] = [
                                'w' => $size['item_width'],
                                'h' => $size['item_height']
                            ];
                        }
                    }
                    $globalWcLoopTmp['item_sizes'] = $__new_item_sizes;
                }

                $globalWcLoopTmp['prods_masonry'] = true;

                $container_attr .= ' data-item_selector=".product_item"';
                $container_attr .= ' data-la-effect="sequencefade"';
            }
        }
        else{
            $loopCssClass[] = 'products-' . $layout;
            $loopCssClass[] = 'products-' . $layout . '-' . $style;
        }

        if(isset($this->settings['paginate_as_loadmore']) && $this->settings['paginate_as_loadmore'] == 'yes'){
            $globalWcLoopTmp['products__loadmore_ajax_text'] = $this->settings['loadmore_text'];
        }

        $products = $this->get_query_results();

        $loop_tpl               = array();
        $loop_tpl[]             = "woocommerce/content-product-{$layout}-{$style}.php";
        $loop_tpl[]             = "woocommerce/content-product-{$layout}.php";
        $loop_tpl[]             = "woocommerce/content-product.php";

        ob_start();

        if ( $products && $products->ids ) {

            // Prime meta cache to reduce future queries.
            update_meta_cache( 'post', $products->ids );
            update_object_term_cache( $products->ids, 'product' );

            // Setup the loop.

            wc_setup_loop(
                wp_parse_args($globalWcLoopTmp, array(
                    'columns'      => $columns,
                    'name'         => $this->type,
                    'is_shortcode' => true,
                    'is_search'    => false,
                    'is_paginated' => wc_string_to_bool( $this->attributes['paginate'] ),
                    'total'        => $products->total,
                    'total_pages'  => $products->total_pages,
                    'per_page'     => $products->per_page,
                    'current_page' => $products->current_page
                ))
            );

            $original_post = isset($GLOBALS['post']) ? $GLOBALS['post'] : false;

            do_action('woocommerce_shortcode_before_la_products_loop');

            // Fire standard shop loop hooks when paginating results so we can show result counts and so on.
            if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
                //do_action( 'woocommerce_before_shop_loop' );
            }

            if ( wc_get_loop_prop( 'total' ) ) {

                echo sprintf(
                    '<div class="row"><div class="col-xs-12"><ul class="%s"%s>',
                    esc_attr(implode(' ', $loopCssClass)),
                    $container_attr ? $container_attr : ''
                );

                foreach ( $products->ids as $product_id ) {
                    $GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
                    setup_postdata( $GLOBALS['post'] );
                    // Set custom product visibility when quering hidden products.
                    add_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );

                    locate_template($loop_tpl, true, false);

                    remove_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );
                }

                echo '</ul></div></div>';
            }

            if($original_post){
                $GLOBALS['post'] = $original_post; // WPCS: override ok.
            }

            // Fire standard shop loop hooks when paginating results so we can show result counts and so on.
            if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
                do_action( 'woocommerce_after_shop_loop' );
            }

            do_action( "woocommerce_shortcode_after_{$this->type}_loop", $this->attributes );

            wp_reset_postdata();
            wc_reset_loop();

        }
        else{
            do_action( "woocommerce_shortcode_{$this->type}_loop_no_results", $this->attributes );
        }

        return '<div id="wc_widget_'.esc_attr($this->settings['unique_id']).'" class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">' . ob_get_clean() . '</div>';
    }
}
