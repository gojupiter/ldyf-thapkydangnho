<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

class LaStudio_Widget_Price_Filter_List extends WP_Widget {

	/**
	 * Sets up a new Text widget instance.
	 *
	 * @since  2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'woocommerce la-price-filter-list',
			'description' => esc_html__( 'Shows a price filter list in a widget which lets you narrow down the list of shown products when viewing product categories.', 'lastudio' ),
		);
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct( 'la-price-filter-list', esc_html__( '[LaStudio] - Price Filter List', 'lastudio' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the content for the current Text widget instance.
	 *
	 * @since  2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $args, $instance ) {

		if(!function_exists('WC')) return;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$widget_text = ! empty( $instance['text'] ) ? $instance['text'] : '';

		/**
		 * Filters the content of the Text widget.
		 *
		 * @since 2.3.0
		 * @since 4.4.0 Added the `$this` parameter.
		 *
		 * @param string         $widget_text The widget content.
		 * @param array          $instance    Array of settings for the current widget.
		 * @param WP_Widget_Text $this        Current Text widget instance.
		 */
		$text = apply_filters( 'widget_price_filter_list', $widget_text, $instance, $this );

		$min_price_actived = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';

		$prices = $this->get_filtered_price();

		$min = floor( $prices->min_price );
		$max = ceil( $prices->max_price );

		$base_shop_url = la_get_base_shop_url(false);


		echo ($args['before_widget']);
		if ( ! empty( $title ) ) {
			echo ($args['before_title'] . $title . $args['after_title']);
		} ?>
		<div class="textwidget">
			<?php
			$output     = array();
			$price_list = explode( "\n", $text );
			if ( $price_list ) {

				$params = array();
				foreach ( $_GET as $key => $val ) {
					if ( 'min_price' === $key || 'max_price' === $key || 'la_doing_ajax' === $key ) {
						continue;
					}
					$params[$key] = $val;
				}


				foreach ( $price_list as $price ) {
					$price_attr = explode( "|", $price );

					if ( $price_attr ) {
						$min_price  = isset( $price_attr[0] ) ? $price_attr[0] : '';
						$max_price  = isset( $price_attr[1] ) ? $price_attr[1] : '';
						$text_price = '';

						$skip_max = false;

						if ( function_exists( 'wc_price' ) ) {
							$text_price = $min_price ? wc_price( $min_price ) : wc_price( 0 );
							if ( $max_price ) {
								$text_price .= '<span> - </span>' . wc_price( $max_price );
							}
							else {
								$text_price .= '<span> + </span>';
                                $skip_max = true;
							}
						}

						if ( $min_price === '' ) {
							continue;
						}

						$max_price = $max_price ? $max_price : $max;

						if ( $min != $max ) {
							if ( $min_price > $max ) {
								continue;
							}

						} else {
							if ( $min_price > $max ) {
								continue;
							}
						}

						if ( $max_price < $min ) {
							continue;
						}

						$css_class = '';
						if ( $min_price == $min_price_actived ) {
							$css_class = 'active';
						}

						$link = $base_shop_url;

						if ( $min_price == $min_price_actived ) {
							if ( !empty($params) ) {
								$link = add_query_arg($params, $link);
							}

						}
						else {
							$link = add_query_arg(array(
								'min_price' => $min_price
							), $link);

							if(!$skip_max){
                                $link = add_query_arg(array(
                                    'max_price' => $max_price
                                ), $link);
                            }

							if ( !empty($params) ) {
								$link = add_query_arg($params, $link);
							}
						}

						$output[] = sprintf( '<li class="%s"><a href="%s">%s</a></li>', esc_attr( $css_class ), esc_url( $link ), $text_price );
					}
				}
			}


			if ( $output ) {
				printf( '<ul>%s</ul>', implode( ' ', $output ) );
			}

			?>
		</div>
		<?php
		echo ($args['after_widget']);
	}

	/**
	 * Get filtered min price for current products.
	 * @return int
	 */
	protected function get_filtered_price() {
		global $wpdb, $wp_the_query;

		$args       = $wp_the_query->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[$key] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		return $wpdb->get_row( $sql );
	}

	/**
	 * Handles updating settings for the current Text widget instance.
	 *
	 * @since  2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		return $instance;
	}

	/**
	 * Outputs the Text widget settings form.
	 *
	 * @since  2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title    = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'lastudio' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php esc_html_e( 'Content:', 'lastudio' ); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'text' )); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
		</p>

		<p><?php esc_html_e( 'Enter the price by format: min_price|max_price. Divide price list with linebreaks (Enter). Example:', 'lastudio' ); ?></p>
		<p><?php esc_html_e( '100|200', 'lastudio' ); ?><br><?php esc_html_e( '200|300', 'lastudio' ); ?>
			<br><?php esc_html_e( '300|400', 'lastudio' ); ?></p>
		<?php
	}
}