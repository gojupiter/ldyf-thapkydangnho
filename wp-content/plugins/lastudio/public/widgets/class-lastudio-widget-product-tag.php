<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WC_Widget' ) ) {
	return;
}

/**
 * Tag Cloud Widget.
 *
 */
class LaStudio_Widget_Product_Tag extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {


		$this->widget_cssclass    = 'woocommerce widget_product_tag_cloud la_product_tag_cloud';
		$this->widget_description = esc_html__( 'Your most used product tags in cloud format.', 'lastudio' );
		$this->widget_id          = 'la_product_tag_cloud';
		$this->widget_name        = esc_html__( '[LaStudio] - Product Tags', 'lastudio' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Product Tags', 'lastudio' ),
				'label' => esc_html__( 'Title', 'lastudio' )
			)
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$current_taxonomy = 'product_tag';
		$term_id          = 0;
		$queried_object   = get_queried_object();
		if ( $queried_object && isset ( $queried_object->term_id ) ) {
			$term_id = $queried_object->term_id;
		}

		if ( empty( $instance['title'] ) ) {
			$taxonomy          = get_taxonomy( $current_taxonomy );
			$instance['title'] = $taxonomy->labels->name;
		}

		$this->widget_start( $args, $instance );

		$terms  = get_terms( $current_taxonomy );
		$found  = false;
		$output = array();
		if ( $terms ) {

			foreach ( $terms as $term ) {

				$css_class = '';
				if ( $term_id == $term->term_id ) {
					$css_class = 'active';
					$found     = true;
				}

				$output[] = sprintf( '<li class="%s"><a href="%s">%s</a></li>', esc_attr( $css_class ), esc_url( get_term_link( $term ) ), $term->name );
			}

		}
		$css_class = $found ? '' : 'active';

		printf(
			'<ul class="tagcloud"><li class="%s"><a href="%s">%s</a></li>%s</ul>',
			esc_attr( $css_class ),
			esc_url( esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ) ),
			esc_html__( 'All', 'lastudio' ),
			implode( ' ', $output )
		);

		$this->widget_end( $args );
	}

}
