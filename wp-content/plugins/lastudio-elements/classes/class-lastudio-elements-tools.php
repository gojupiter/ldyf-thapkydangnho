<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Lastudio_Elements_Tools' ) ) {

	/**
	 * Define Lastudio_Elements_Tools class
	 */
	class Lastudio_Elements_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Returns columns classes string
		 * @param  [type] $columns [description]
		 * @return [type]          [description]
		 */
		public function col_classes( $columns = array() ) {

			$columns = wp_parse_args( $columns, array(
				'desk'  => '',
				'lap'   => '',
				'tab'   => '',
				'tabp'  => '',
				'mob'   => '',
				'mobp'  => ''
			) );

			$classes = array();

			if(empty($columns['lap']) && !empty($columns['desk'])){
                $columns['lap'] = $columns['desk'];
                unset($columns['desk']);
            }

            if(empty($columns['tabp']) && !empty($columns['tab'])){
                $columns['tabp'] = $columns['tab'];
                unset($columns['tab']);
            }

            if(empty($columns['mobp']) && !empty($columns['mob'])){
                $columns['mobp'] = $columns['mob'];
                unset($columns['mob']);
            }

			foreach ( $columns as $device => $cols ) {
				if ( ! empty( $cols ) ) {
					$classes[] = sprintf( 'col-%1$s-%2$s', $device, $cols );
				}
			}

			return implode( ' ' , $classes );
		}

        public function block_grid_classes( $columns = array() ) {

            $columns = wp_parse_args( $columns, array(
                'desk'  => '',
                'lap'   => '',
                'tab'   => '',
                'tabp'  => '',
                'mob'   => '',
                'mobp'  => ''
            ) );

            $classes = array();

            if(empty($columns['lap']) && !empty($columns['desk'])){
                $columns['lap'] = $columns['desk'];
                unset($columns['desk']);
            }

            if(empty($columns['tabp']) && !empty($columns['tab'])){
                $columns['tabp'] = $columns['tab'];
                unset($columns['tab']);
            }

            if(empty($columns['mobp']) && !empty($columns['mob'])){
                $columns['mobp'] = $columns['mob'];
                unset($columns['mob']);
            }

            foreach ( $columns as $device => $cols ) {
                if ( ! empty( $cols ) ) {
                    $classes[] = sprintf( 'block-%1$s-%2$s', $device, $cols );
                }
            }

            return implode( ' ' , $classes );
        }

        public function block_grid_legacy_classes( $columns = array() ) {

		    $classes = $this->block_grid_classes( $columns );

            $classes = str_replace(
                array(
                    'block-desk',
                    'block-lap',
                    'block-tabp',
                    'block-tab',
                    'block-mobp',
                    'block-mob',
                ),
                array(
                    'xxl-block-grid',
                    'xl-block-grid',
                    'md-block-grid',
                    'lg-block-grid',
                    'block-grid',
                    'sm-block-grid',
                ),
                $classes
            );

            return $classes;
        }

		/**
		 * Returns disable columns gap nad rows gap classes string
		 *
		 * @param  string $use_cols_gap [description]
		 * @param  string $use_rows_gap [description]
		 * @return [type]               [description]
		 */
		public function gap_classes( $use_cols_gap = 'yes', $use_rows_gap = 'yes' ) {

			$result = array();

			foreach ( array( 'cols' => $use_cols_gap, 'rows' => $use_rows_gap ) as $element => $value ) {
				if ( 'yes' !== $value ) {
					$result[] = sprintf( 'disable-%s-gap', $element );
				}
			}

			return implode( ' ', $result );

		}

		/**
		 * Returns image size array in slug => name format
		 *
		 * @return  array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			return array_merge( array( 'full' => esc_html__( 'Full', 'lastudio-elements' ), ), $result );
		}

		/**
		 * Get categories list.
		 *
		 * @return array
		 */
		public function get_categories() {

			$categories = get_categories();

			if ( empty( $categories ) || ! is_array( $categories ) ) {
				return array();
			}

			return wp_list_pluck( $categories, 'name', 'term_id' );

		}

		/**
		 * Returns icons data list.
		 *
		 * @return array
		 */
		public function get_theme_icons_data() {

			$default = array(
				'icons'  => false,
				'format' => 'dlicon %s',
				'file'   => LASTUDIO_ELEMENTS_PATH . 'assets/css/lib/dlicon/dlicon.css'
			);

			/**
			 * Filter default icon data before useing
			 *
			 * @var array
			 */
			$icon_data = apply_filters( 'lastudio-elements/controls/icon/data', $default );
			$icon_data = array_merge( $default, $icon_data );

			return $icon_data;
		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function orderby_arr() {
			return array(
				'none'          => esc_html__( 'None', 'lastudio-elements' ),
				'ID'            => esc_html__( 'ID', 'lastudio-elements' ),
				'author'        => esc_html__( 'Author', 'lastudio-elements' ),
				'title'         => esc_html__( 'Title', 'lastudio-elements' ),
				'name'          => esc_html__( 'Name (slug)', 'lastudio-elements' ),
				'date'          => esc_html__( 'Date', 'lastudio-elements' ),
				'modified'      => esc_html__( 'Modified', 'lastudio-elements' ),
				'rand'          => esc_html__( 'Rand', 'lastudio-elements' ),
				'comment_count' => esc_html__( 'Comment Count', 'lastudio-elements' ),
				'menu_order'    => esc_html__( 'Menu Order', 'lastudio-elements' ),
			);
		}

		/**
		 * Returns allowed order fields for options
		 *
		 * @return array
		 */
		public function order_arr() {

			return array(
				'desc' => esc_html__( 'Descending', 'lastudio-elements' ),
				'asc'  => esc_html__( 'Ascending', 'lastudio-elements' ),
			);

		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function verrtical_align_attr() {
			return array(
				'baseline'    => esc_html__( 'Baseline', 'lastudio-elements' ),
				'top'         => esc_html__( 'Top', 'lastudio-elements' ),
				'middle'      => esc_html__( 'Middle', 'lastudio-elements' ),
				'bottom'      => esc_html__( 'Bottom', 'lastudio-elements' ),
				'sub'         => esc_html__( 'Sub', 'lastudio-elements' ),
				'super'       => esc_html__( 'Super', 'lastudio-elements' ),
				'text-top'    => esc_html__( 'Text Top', 'lastudio-elements' ),
				'text-bottom' => esc_html__( 'Text Bottom', 'lastudio-elements' ),
			);
		}

		/**
		 * Returns array with numbers in $index => $name format for numeric selects
		 *
		 * @param  integer $to Max numbers
		 * @return array
		 */
		public function get_select_range( $to = 10 ) {
			$range = range( 1, $to );
			return array_combine( $range, $range );
		}

		/**
		 * Returns badge placeholder URL
		 *
		 * @return void
		 */
		public function get_badge_placeholder() {
			return LASTUDIO_ELEMENTS_URL . 'assets/images/placeholder-badge.svg' ;
		}

		/**
		 * Rturns image tag or raw SVG
		 *
		 * @param  string $url  image URL.
		 * @param  array  $attr [description]
		 * @return string
		 */
		public function get_image_by_url( $url = null, $attr = array() ) {

			$url = esc_url( $url );

			if ( empty( $url ) ) {
				return;
			}

			$ext  = pathinfo( $url, PATHINFO_EXTENSION );
			$attr = array_merge( array( 'alt' => '' ), $attr );

			if ( 'svg' !== $ext ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			$base_url = site_url( '/' );
			$svg_path = str_replace( $base_url, ABSPATH, $url );
			$key      = md5( $svg_path );
			$svg      = get_transient( $key );

			if ( ! $svg ) {
				$svg = file_get_contents( $svg_path );
			}

			if ( ! $svg ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			set_transient( $key, $svg, DAY_IN_SECONDS );

			unset( $attr['alt'] );

			return sprintf( '<div%2$s>%1$s</div>', $svg, $this->get_attr_string( $attr ) );
		}

		/**
		 * Return attributes string from attributes array.
		 *
		 * @param  array  $attr Attributes string.
		 * @return string
		 */
		public function get_attr_string( $attr = array() ) {

			if ( empty( $attr ) || ! is_array( $attr ) ) {
				return;
			}

			$result = '';

			foreach ( $attr as $key => $value ) {
				$result .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}

			return $result;
		}

		/**
		 * Returns carousel arrow
		 *
		 * @param  array $classes Arrow additional classes list.
		 * @return string
		 */
		public function get_carousel_arrow( $classes ) {

			$format = apply_filters( 'lastudio-elements/carousel/arrows_format', '<i class="%s lastudio-arrow"></i>', $classes );

			return sprintf( $format, implode( ' ', $classes ) );
		}

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public function get_post_types() {

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$deprecated = apply_filters(
				'lastudio-elements/post-types-list/deprecated',
				array( 'attachment', 'elementor_library' )
			);

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $post_type->label;

			}

			return $result;

		}

		/**
		 * Return availbale arrows list
		 * @return [type] [description]
		 */
		public function get_available_prev_arrows_list() {

			return apply_filters(
				'lastudio_elements/carousel/available_arrows/prev',
				array(
					'fa fa-angle-left'          => __( 'Angle', 'lastudio-elements' ),
					'fa fa-chevron-left'        => __( 'Chevron', 'lastudio-elements' ),
					'fa fa-angle-double-left'   => __( 'Angle Double', 'lastudio-elements' ),
					'fa fa-arrow-left'          => __( 'Arrow', 'lastudio-elements' ),
					'fa fa-caret-left'          => __( 'Caret', 'lastudio-elements' ),
					'fa fa-long-arrow-left'     => __( 'Long Arrow', 'lastudio-elements' ),
					'fa fa-arrow-circle-left'   => __( 'Arrow Circle', 'lastudio-elements' ),
					'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'lastudio-elements' ),
					'fa fa-caret-square-o-left' => __( 'Caret Square', 'lastudio-elements' ),
					'dlicon arrows-1_tail-left' => __( 'Tail Left', 'lastudio-elements' ),
                    'dlicon arrows-1_tail-triangle-left' => __( 'Tail Triangle Left', 'lastudio-elements' ),
				)
			);

		}

		/**
		 * Return availbale arrows list
		 * @return [type] [description]
		 */
		public function get_available_next_arrows_list() {

			return apply_filters(
				'lastudio_elements/carousel/available_arrows/next',
				array(
					'fa fa-angle-right'          => __( 'Angle', 'lastudio-elements' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'lastudio-elements' ),
					'fa fa-angle-double-right'   => __( 'Angle Double', 'lastudio-elements' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'lastudio-elements' ),
					'fa fa-caret-right'          => __( 'Caret', 'lastudio-elements' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'lastudio-elements' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'lastudio-elements' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'lastudio-elements' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'lastudio-elements' ),
                    'dlicon arrows-1_tail-right' => __( 'Tail Right', 'lastudio-elements' ),
                    'dlicon arrows-1_tail-triangle-right' => __( 'Tail Triangle Right', 'lastudio-elements' ),
				)
			);

		}

		/**
		 * Get post taxonomies for options.
		 *
		 * @return array
		 */
		public function get_taxonomies_for_options() {

			$args = array(
				'public'   => true,
			);

			$taxonomies = get_taxonomies( $args, 'objects', 'and' );

			return wp_list_pluck( $taxonomies, 'label', 'name' );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Lastudio_Elements_Tools
 *
 * @return object
 */
function lastudio_elements_tools() {
	return Lastudio_Elements_Tools::get_instance();
}
