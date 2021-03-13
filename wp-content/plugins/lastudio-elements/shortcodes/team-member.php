<?php

namespace Lastudio_Elements\Shortcodes;

use Lastudio_Elements\Base\Shortcode_Base;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Member shortcode class
 */
class Team_Member extends Shortcode_Base {

	/**
	 * Shortocde tag
	 *
	 * @return string
	 */
	public function get_tag() {
		return 'lastudio-team-member';
	}

	/**
	 * Shortocde attributes
	 *
	 * @return array
	 */
	public function get_atts() {

		$columns           = lastudio_elements_tools()->get_select_range( 6 );
		$custom_query_link = sprintf(
			'<a href="https://la-studioweb.com/tool/wp-query-generator/" target="_blank">%s</a>',
			__( 'Generate custom query', 'lastudio-elements' )
		);

        $preset_type = apply_filters(
            'lastudio-elements/shortcodes/lastudio-team-member/preset',
            array(
                'type-1' => esc_html__( 'Type-1', 'lastudio-elements' ),
                'type-2' => esc_html__( 'Type-2', 'lastudio-elements' ),
                'type-3' => esc_html__( 'Type-3', 'lastudio-elements' )
            )
        );

		return apply_filters( 'lastudio-elements/shortcodes/lastudio-team-member/atts', array(
			'number' => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Posts Number', 'lastudio-elements' ),
				'default'   => 3,
				'min'       => -1,
				'max'       => 30,
				'step'      => 1,
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'true',
				),
			),
            'preset' => array(
                'type'       => 'select',
                'label'      => esc_html__( 'Preset', 'lastudio-elements' ),
                'default'    => 'type-1',
                'options'    => $preset_type,
            ),
			'columns' => array(
				'type'       => 'select',
				'responsive' => true,
				'label'      => esc_html__( 'Columns', 'lastudio-elements' ),
				'default'    => 3,
				'options'    => $columns,
			),
            'columns_laptop' => array(
                'default' => 3,
            ),
            'columns_tablet' => array(
                'default' => 2,
            ),
            'columns_width800' => array(
                'default' => 2,
            ),
            'columns_mobile' => array(
                'default' => 1,
            ),
			'equal_height_cols' => array(
				'label'        => esc_html__( 'Equal Columns Height', 'lastudio-elements' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
				'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
				'return_value' => 'true',
				'default'      => '',
			),
			'is_archive_template' => array(
				'label'        => esc_html__( 'Is archive template', 'lastudio-elements' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
				'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
				'return_value' => 'true',
				'default'      => '',
			),

			'posts_query' => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Query posts by', 'lastudio-elements' ),
				'default'    => 'latest',
				'options'    => array(
					'latest'   => esc_html__( 'Latest Posts', 'lastudio-elements' ),
					'ids'      => esc_html__( 'By Specific IDs', 'lastudio-elements' )
				),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'true',
				),
			),

			'post_ids' => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Set comma separated IDs list (10, 22, 19 etc.)', 'lastudio-elements' ),
				'default'   => '',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'posts_query'          => array( 'ids' ),
					'is_archive_template!' => 'true',
				),
			),

			'post_offset' => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Post offset', 'lastudio-elements' ),
				'default'   => 0,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'separator' => 'before',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'true',
				),
			),
			'use_custom_query_heading' => array(
				'label'     => esc_html__( 'Custom Query', 'lastudio-elements' ),
				'type'      => 'heading',
				'separator' => 'before',
				'condition' => array(
					'is_archive_template!' => 'true',
				),

			),
			'use_custom_query' => array(
				'label'        => esc_html__( 'Use Custom Query', 'lastudio-elements' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
				'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
				'return_value' => 'true',
				'default'      => '',
				'condition' => array(
					'is_archive_template!' => 'true',
				),
			),
			'custom_query' => array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Set custom query', 'lastudio-elements' ),
				'default'     => '',
				'description' => $custom_query_link,
				'condition'   => array(
					'use_custom_query' => 'true',
					'is_archive_template!' => 'true',
				),
			),

			'thumb_size' => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Featured Image Size', 'lastudio-elements' ),
				'default'    => 'full',
				'options'    => lastudio_elements_tools()->get_image_sizes()
			),
			'columns_gap' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between columns', 'lastudio-elements' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
				'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'rows_gap' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between rows', 'lastudio-elements' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
				'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		) );

	}

	/**
	 * Get default query args
	 *
	 * @return array
	 */
	public function get_default_query_args() {

		$query_args = array(
			'post_type'           => 'la_team_member',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => intval( $this->get_attr( 'number' ) ),
		);

		$offset = $this->get_attr( 'post_offset' );
		$offset = ! empty( $offset ) ? absint( $offset ) : 0;

		if ( $offset ) {
			$query_args['offset'] = $offset;
		}

		switch ( $this->get_attr( 'posts_query' ) ) {

			case 'ids':

				if ( '' !== $this->get_attr( 'post_ids' ) ) {
					$query_args['post__in'] = explode(
						',',
						str_replace( ' ', '', $this->get_attr( 'post_ids' ) )
					);
				}
				break;
		}

		return $query_args;

	}

	/**
	 * Get custom query args
	 *
	 * @return array
	 */
	public function get_custom_query_args() {

		$query_args = $this->get_attr( 'custom_query' );
		$query_args = json_decode( $query_args, true );

		if ( ! $query_args ) {
			$query_args = array();
		}

		return $query_args;
	}

	/**
	 * Query posts by attributes
	 *
	 * @return object
	 */
	public function query() {

		if ( 'true' === $this->get_attr( 'is_archive_template' ) ) {
			global $wp_query;
			$query = $wp_query;
			return $query;
		}

		if ( 'true' === $this->get_attr( 'use_custom_query' ) ) {
			$query_args = $this->get_custom_query_args();
		} else {
			$query_args = $this->get_default_query_args();
		}

		$query = new \WP_Query( $query_args );

		return $query;
	}

	/**
	 * Posts shortocde function
	 *
	 * @param  array  $atts Attributes array.
	 * @return string
	 */
	public function _shortcode( $content = null ) {

		$query = $this->query();

		if ( ! $query->have_posts() ) {
			$not_found = $this->get_template( 'not-found' );
		}

		$loop_start = $this->get_template( 'loop-start' );
		$loop_item  = $this->get_template( 'loop-item' );
		$loop_end   = $this->get_template( 'loop-end' );

		global $post;

		ob_start();

		/**
		 * Hook before loop start template included
		 */
		do_action( 'lastudio-elements/shortcodes/lastudio-team-member/loop-start' );

		include $loop_start;

		while ( $query->have_posts() ) {

			$query->the_post();
			$post = $query->post;

			setup_postdata( $post );

			/**
			 * Hook before loop item template included
			 */
			do_action( 'lastudio-elements/shortcodes/lastudio-team-member/loop-item-start' );

			include $loop_item;

			/**
			 * Hook after loop item template included
			 */
			do_action( 'lastudio-elements/shortcodes/lastudio-team-member/loop-item-end' );

		}

		include $loop_end;

		/**
		 * Hook after loop end template included
		 */
		do_action( 'lastudio-elements/shortcodes/lastudio-team-member/loop-end' );

		wp_reset_postdata();

		return ob_get_clean();

	}


}
