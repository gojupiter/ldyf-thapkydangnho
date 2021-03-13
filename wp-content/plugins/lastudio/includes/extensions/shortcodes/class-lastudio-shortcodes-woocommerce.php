<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Products shortcode
 *
 * @author   La-Studio
 * @category Shortcodes
 * @package  LaStudio-Core/Shortcodes
 * @version  3.3.0
 */

class LaStudio_Shortcodes_WooCommerce{

    /**
     * Shortcode type.
     *
     * @since 3.0.0
     * @var   string
     */
    protected $type = 'products';

    /**
     * Attributes.
     *
     * @since 3.0.0
     * @var   array
     */
    protected $attributes = array();

    protected $origin_attributes = array();

    /**
     * Query args.
     *
     * @since 3.0.0
     * @var   array
     */
    protected $query_args = array();

    /**
     * Set custom visibility.
     *
     * @since 3.0.0
     * @var   bool
     */
    protected $custom_visibility = false;

    /**
     * Initialize shortcode.
     *
     * @since 3.0.0
     * @param array  $attributes Shortcode attributes.
     * @param string $type       Shortcode type.
     */
    public function __construct( $attributes = array(), $type = 'products' ) {

        $this->type       = $type;
        $this->attributes = $this->parse_attributes( $attributes );
        $this->query_args = $this->parse_query_args();
        $this->origin_attributes = $attributes;

    }

    public static function render_default_atts($atts){
        return shortcode_atts(array(
            'scenario' => 'recent_products',
            'layout' => 'grid',
            'list_style' => 'default',
            'grid_style' => '1',
            'masonry_style' => '1',
            'category' => '',
            'operator' => 'IN',
            'attribute' => '',
            'filter' => '',
            'orderby' => '',
            'order' => '',
            'ids' => '',
            'per_page' => 12,
            'item_space' => 'default',
            'enable_custom_image_size' => '',
            'img_size' => 'shop_catalog',
            'disable_alt_image' => '',
            'column_type' => 'default',
            'base_container_w' => 1170,
            'base_item_w' => 300,
            'base_item_h' => 300,
            'mb_columns' => '',
            'custom_item_size' => '',
            'item_sizes' => '%5B%7B%22w%22%3A%221%22%2C%22h%22%3A%221%22%2C%22s%22%3A%22%22%7D%5D',
            'columns' => '',
            'display_style' => 'all',
            'load_more_text' => '',
            'enable_carousel' => '',
            'enable_ajax_loader' => '',
            'shortcode_id' => '',
            'el_class' => '',
            'skus' => '',
            'paged' => '1',
            'slider_type' => 'horizontal',
            'slide_to_scroll' => 'all',
            'infinite_loop' => '',
            'speed' => '300',
            'autoplay' => '',
            'autoplay_speed' => '5000',
            'arrows' => '',
            'arrow_style' => 'default',
            'arrow_bg_color' => '',
            'arrow_border_color' => '',
            'border_size' => '2',
            'arrow_color' => '#333333',
            'arrow_size' => '24',
            'next_icon' => 'dl-icon-right',
            'prev_icon' => 'dl-icon-left',
            'custom_nav' => '',
            'dots' => '',
            'dots_color' => '#333333',
            'dots_icon' => 'fa fa-circle',
            'draggable' => 'yes',
            'touch_move' => 'yes',
            'rtl' => '',
            'adaptive_height' => '',
            'pauseohover' => '',
            'centermode' => '',
            'autowidth' => '',
            'tag'   => ''
        ), $atts );
    }

    /**
     * Get shortcode attributes.
     *
     * @since  3.0.0
     * @return array
     */
    public function get_attributes() {
        return $this->attributes;
    }

    /**
     * Get query args.
     *
     * @since  3.0.0
     * @return array
     */
    public function get_query_args() {
        return $this->query_args;
    }

    /**
     * Get shortcode type.
     *
     * @since  3.0.0
     * @return array
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Get shortcode content.
     *
     * @since  3.0.0
     * @return string
     */
    public function get_content() {

        return $this->product_loop();
    }

    /**
     * Parse attributes.
     *
     * @since  3.0.0
     * @param  array $attributes Shortcode attributes.
     * @return array
     */
    protected function parse_attributes( $attributes ) {
        $attributes = $this->parse_legacy_attributes( $attributes );

        return shortcode_atts( array(
            'limit'          => '-1',      // Results limit.
            'columns'        => '4',       // Number of columns.
            'rows'           => '',        // Number of rows. If defined, limit will be ignored.
            'orderby'        => 'title',   // menu_order, title, date, rand, price, popularity, rating, or id.
            'order'          => 'ASC',     // ASC or DESC.
            'ids'            => '',        // Comma separated IDs.
            'skus'           => '',        // Comma separated SKUs.
            'category'       => '',        // Comma separated category slugs.
            'cat_operator'   => 'IN',      // Operator to compare categories. Possible values are 'IN', 'NOT IN', 'AND'.
            'attribute'      => '',        // Single attribute slug.
            'terms'          => '',        // Comma separated term slugs.
            'terms_operator' => 'IN',      // Operator to compare terms. Possible values are 'IN', 'NOT IN', 'AND'.
            'visibility'     => 'visible', // Possible values are 'visible', 'catalog', 'search', 'hidden', 'featured'.
            'class'          => '',        // HTML class.

            'page'           => 1,         // Page for pagination.
            'cache'          => true,      // Should shortcode output be cached.

            'shortcode_id'   => '',
            'layout'         => 'grid',    // Possible values are 'grid', 'list', 'masonry',
            'grid_style'     => '1',
            'list_style'     => '1',
            'masonry_style'  => '1',
            'item_space'                => 'default',
            'enable_custom_image_size'  => 'no',
            'disable_alt_image'         => 'no',
            'enable_carousel'           => 'no',
            'img_size'                  => 'shop_catalog',
            'display_style'             => 'all', // Possible values are 'all', 'load-more', 'pagination',
            'load_more_text'            => '',
            'items_per_page'            => '',

            'column_type' => 'default',
            'base_container_w' => '',
            'base_item_w' => '',
            'base_item_h' => '',
            'mb_columns' => '',
            'custom_item_size' => '',
            'item_sizes' => '',
            'tag'        => '' //Comma separated product tags slugs.

        ), $attributes, $this->type );
    }

    /**
     * Parse legacy attributes.
     *
     * @since  3.0.0
     * @param  array $attributes Attributes.
     * @return array
     */
    protected function parse_legacy_attributes( $attributes ) {

        $mapping = array(
            'per_page' => 'limit',
            'operator' => 'cat_operator',
            'filter'   => 'terms',
            'el_class' => 'class',
            'paged'    => 'page'
        );

        foreach ( $mapping as $old => $new ) {
            if ( isset( $attributes[ $old ] ) ) {
                $attributes[ $new ] = $attributes[ $old ];
                unset( $attributes[ $old ] );
            }
        }


        if($this->type == 'featured' || $this->type == 'featured_products') {
            $attributes['visibility'] = 'featured';
        }

        return $attributes;
    }

    /**
     * Parse query args.
     *
     * @since  3.2.0
     * @return array
     */
    protected function parse_query_args() {

        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'no_found_rows'       => ( $this->attributes['display_style'] != 'load-more' && $this->attributes['display_style'] != 'pagination' ),
            'orderby'             => $this->attributes['orderby'],
            'order'               => strtoupper( $this->attributes['order'] ),
        );

        if ( ! empty( $this->attributes['rows'] ) ) {
            $this->attributes['limit'] = $this->attributes['columns'] * $this->attributes['rows'];
        }
        // @codingStandardsIgnoreStart
        $ordering_args                = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
        $query_args['orderby']        = $ordering_args['orderby'];
        $query_args['order']          = $ordering_args['order'];
        if ( $ordering_args['meta_key'] ) {
            $query_args['meta_key']       = $ordering_args['meta_key'];
        }
        $query_args['posts_per_page'] = intval( $this->attributes['limit'] );
        if ( 1 < $this->attributes['page'] ) {
            $query_args['paged']          = absint( $this->attributes['page'] );
        }
        $query_args['meta_query']     = WC()->query->get_meta_query();
        $query_args['tax_query']      = array();


        // @codingStandardsIgnoreEnd
        // Visibility.
        $this->set_visibility_query_args( $query_args );
        // SKUs.
        $this->set_skus_query_args( $query_args );
        // IDs.
        $this->set_ids_query_args( $query_args );
        // Set specific types query args.
        if ( method_exists( $this, "set_{$this->type}_query_args" ) ) {
            $this->{"set_{$this->type}_query_args"}( $query_args );
        }
        // Attributes.
        $this->set_attributes_query_args( $query_args );
        // Categories.
        $this->set_categories_query_args( $query_args, $this->attributes);
        // Tags.
        $this->set_tags_query_args( $query_args );
        $query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );
        // Always query only IDs.
        $query_args['fields'] = 'ids';
        return $query_args;
    }

    /**
     * Set skus query args.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_skus_query_args( &$query_args ) {
        if ( ! empty( $this->attributes['skus'] ) ) {
            $skus = array_map( 'trim', explode( ',', $this->attributes['skus'] ) );
            $query_args['meta_query'][] = array(
                'key'     => '_sku',
                'value'   => 1 === count( $skus ) ? $skus[0] : $skus,
                'compare' => 1 === count( $skus ) ? '=' : 'IN',
            );
        }
    }

    /**
     * Set ids query args.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_ids_query_args( &$query_args ) {
        if ( ! empty( $this->attributes['ids'] ) ) {
            $ids = array_map( 'trim', explode( ',', $this->attributes['ids'] ) );

            if ( 1 === count( $ids ) ) {
                $query_args['p'] = $ids[0];
            } else {
                $query_args['post__in'] = $ids;
            }
        }
    }

    /**
     * Set attributes query args.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_attributes_query_args( &$query_args ) {
        if ( ! empty( $this->attributes['attribute'] ) || ! empty( $this->attributes['terms'] ) ) {
            $query_args['tax_query'][] = array(
                'taxonomy' => strstr( $this->attributes['attribute'], 'pa_' ) ? sanitize_title( $this->attributes['attribute'] ) : 'pa_' . sanitize_title( $this->attributes['attribute'] ),
                'terms'    => array_map( 'sanitize_title', explode( ',', $this->attributes['terms'] ) ),
                'field'    => 'slug',
                'operator' => $this->attributes['terms_operator'],
            );
        }
    }

    /**
     * Set categories query args.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_categories_query_args( &$query_args, $old_atts ) {
        if ( ! empty( $this->attributes['category'] ) ) {
            $ordering_args = WC()->query->get_catalog_ordering_args( $old_atts['orderby'], $old_atts['order'] );
            $query_args['orderby']  = $ordering_args['orderby'];
            $query_args['order']    = $ordering_args['order'];
            // @codingStandardsIgnoreStart
            $query_args['meta_key'] = $ordering_args['meta_key'];
            // @codingStandardsIgnoreEnd

            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'terms'    => array_map( 'sanitize_title', explode( ',', $this->attributes['category'] ) ),
                'field'    => 'slug',
                'operator' => $this->attributes['cat_operator'],
            );
        }
    }

    /**
     * Set tags query args.
     *
     * @since 3.3.0
     * @param array $query_args Query args.
     */
    protected function set_tags_query_args( &$query_args ) {
        if ( ! empty( $this->attributes['tag'] ) ) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'terms'    => array_map( 'sanitize_title', explode( ',', $this->attributes['tag'] ) ),
                'field'    => 'slug',
                'operator' => 'IN',
            );
        }
    }


    /**
     * Set sale products query args.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_sale_products_query_args( &$query_args ) {
        $query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
    }

    /**
     * Set best selling products query args.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_best_selling_products_query_args( &$query_args ) {
        // @codingStandardsIgnoreStart
        $query_args['meta_key'] = 'total_sales';
        // @codingStandardsIgnoreEnd
        $query_args['order']    = 'DESC';
        $query_args['orderby']  = 'meta_value_num';
    }

    /**
     * Set visibility as hidden.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_visibility_hidden_query_args( &$query_args ) {
        $this->custom_visibility = true;
        $query_args['tax_query'][] = array(
            'taxonomy'         => 'product_visibility',
            'terms'            => array( 'exclude-from-catalog', 'exclude-from-search' ),
            'field'            => 'name',
            'operator'         => 'AND',
            'include_children' => false,
        );
    }

    /**
     * Set visibility as catalog.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_visibility_catalog_query_args( &$query_args ) {
        $this->custom_visibility = true;
        $query_args['tax_query'][] = array(
            'taxonomy'         => 'product_visibility',
            'terms'            => 'exclude-from-search',
            'field'            => 'name',
            'operator'         => 'IN',
            'include_children' => false,
        );
        $query_args['tax_query'][] = array(
            'taxonomy'         => 'product_visibility',
            'terms'            => 'exclude-from-catalog',
            'field'            => 'name',
            'operator'         => 'NOT IN',
            'include_children' => false,
        );
    }

    /**
     * Set visibility as search.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_visibility_search_query_args( &$query_args ) {
        $this->custom_visibility = true;
        $query_args['tax_query'][] = array(
            'taxonomy'         => 'product_visibility',
            'terms'            => 'exclude-from-catalog',
            'field'            => 'name',
            'operator'         => 'IN',
            'include_children' => false,
        );
        $query_args['tax_query'][] = array(
            'taxonomy'         => 'product_visibility',
            'terms'            => 'exclude-from-search',
            'field'            => 'name',
            'operator'         => 'NOT IN',
            'include_children' => false,
        );
    }

    /**
     * Set visibility as featured.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_visibility_featured_query_args( &$query_args ) {
        // @codingStandardsIgnoreStart
        $query_args['tax_query'] = array_merge( $query_args['tax_query'], WC()->query->get_tax_query() );
        // @codingStandardsIgnoreEnd

        $query_args['tax_query'][] = array(
            'taxonomy'         => 'product_visibility',
            'terms'            => 'featured',
            'field'            => 'name',
            'operator'         => 'IN',
            'include_children' => false,
        );
    }

    /**
     * Set visibility query args.
     *
     * @since 3.0.0
     * @param array $query_args Query args.
     */
    protected function set_visibility_query_args( &$query_args ) {
        if ( method_exists( $this, 'set_visibility_' . $this->attributes['visibility'] . '_query_args' ) ) {
            $this->{'set_visibility_' . $this->attributes['visibility'] . '_query_args'}( $query_args );
        } else {
            // @codingStandardsIgnoreStart
            $query_args['tax_query'] = array_merge( $query_args['tax_query'], WC()->query->get_tax_query() );
            // @codingStandardsIgnoreEnd
        }
    }


    /**
     * Set product as visible when quering for hidden products.
     *
     * @since  3.0.0
     * @param  bool $visibility Product visibility.
     * @return bool
     */
    public function set_product_as_visible( $visibility ) {
        return $this->custom_visibility ? true : $visibility;
    }

    /**
     * Get wrapper classes.
     * @return array
     */
    protected function get_wrapper_classes() {
        $classes = array( 'woocommerce' );
        $classes[] = 'products_scenario_' . $this->get_type();
        if(!empty($this->attributes['class'])){
            $classes[] = $this->attributes['class'];
        }
        return $classes;
    }

    /**
     * Generate and return the transient name for this shortcode based on the query args.
     *
     * @since 3.3.0
     * @return string
     */
    protected function get_transient_name() {

        $transient_name = 'wc_product_loop' . substr( md5( wp_json_encode( $this->query_args ) . $this->type ), 28 );
        if ( 'rand' === $this->query_args['orderby'] ) {
            // When using rand, we'll cache a number of random queries and pull those to avoid querying rand on each page load.
            $rand_index      = rand( 0, max( 1, absint( apply_filters( 'woocommerce_product_query_max_rand_cache_count', 5 ) ) ) );
            $transient_name .= $rand_index;
        }
        $transient_name .= WC_Cache_Helper::get_transient_version( 'product_query' );
        return $transient_name;
    }

    protected function get_wrapper_shortcode_id(){
        if(!empty($this->attributes['shortcode_id'])){
            $shortcode_id = $this->attributes['shortcode_id'];
        }
        else{
            $shortcode_id = uniqid($this->type . '_');
        }
        return $shortcode_id;
    }

    protected function get_query_results() {
        $transient_name = $this->get_transient_name();
        $cache          = wc_string_to_bool( $this->attributes['cache'] ) === true;
        $results        = $cache ? get_transient( $transient_name ) : false;
        if ( false === $results ) {
            if ( 'top_rated_products' === $this->type ) {
                add_filter( 'posts_clauses', array( __CLASS__, 'order_by_rating_post_clauses' ) );
                $query = new WP_Query( $this->query_args );
                remove_filter( 'posts_clauses', array( __CLASS__, 'order_by_rating_post_clauses' ) );
            } else {
                $query = new WP_Query( $this->query_args );
            }

            $paginated = ! $query->get( 'no_found_rows' );
            $results = (object) array(
                'ids'          => wp_parse_id_list( $query->posts ),
                'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
                'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
                'per_page'     => (int) $query->get( 'posts_per_page' ),
                'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
            );
            if ( $cache ) {
                set_transient( $transient_name, $results, DAY_IN_SECONDS * 30 );
            }
        }
        // Remove ordering query arguments.
        if ( ! empty( $this->attributes['category'] ) ) {
            WC()->query->remove_ordering_args();
        }

        return $results;
    }

    /**
     * Loop over found products.
     *
     * @since  3.2.4
     * @return string
     */
    protected function product_loop() {

        $existsGlobalWcLoop         = isset($GLOBALS['woocommerce_loop']) ? true : false;
        $globalWcLoopTmp            = array();

        $unique_id                  = $this->get_wrapper_shortcode_id();
        $wrapper_classes            = $this->get_wrapper_classes();
        $columns                    = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts( $this->attributes['columns'] );
        $layout                     = !empty($this->attributes['layout']) ? $this->attributes['layout'] : 'grid';
        $style                      = $this->attributes[$layout . '_style'];
        $item_space                 = $this->attributes['item_space'];

        $loopCssClass 	= array();
        $container_attr = $disable_alt_image = $image_size = false;
        if( 'yes' == $this->attributes['enable_custom_image_size'] ) {
            $image_size = true;
        }
        if( 'yes' == $this->attributes['disable_alt_image'] ) {
            $disable_alt_image = true;
        }
        if( $layout == 'grid' ){
            if( 'yes' == $this->attributes['enable_carousel'] ) {
                $container_attr = ' data-la_component="AutoCarousel" ';
                $container_attr .= LaStudio_Shortcodes_Helper::getParamCarouselShortCode( $this->origin_attributes );
                $loopCssClass[] = 'js-el la-slick-slider';

                $slider_item_animation  = isset($this->origin_attributes['item_animation']) && !empty($this->origin_attributes['item_animation']) ? $this->origin_attributes['item_animation'] : '';
                $slider_centermode      = isset($this->origin_attributes['centermode']) ? $this->origin_attributes['centermode'] : '';
                $slider_autowidth       = isset($this->origin_attributes['autowidth']) ? $this->origin_attributes['autowidth'] : '';
                $slider_slider_type     = isset($this->origin_attributes['slider_type']) ? $this->origin_attributes['slider_type'] : 'horizontal';

                if( !empty($slider_item_animation) && $slider_item_animation != 'none' && $slider_centermode != 'yes' && $slider_autowidth != 'yes' && $slider_slider_type == 'horizontal'){
                    $loopCssClass[] = 'laslick_has_animation';
                    $loopCssClass[] = 'laslick_' . $slider_item_animation;
                }
            }
        }

        $globalWcLoopTmp['loop_id']        = $unique_id;
        $globalWcLoopTmp['loop_layout']    = $layout;
        $globalWcLoopTmp['loop_style']     = $style;
        $globalWcLoopTmp['item_space']     = $item_space;


        if($image_size){
            $globalWcLoopTmp['image_size'] = LaStudio_Shortcodes_Helper::getImageSizeFormString( $this->attributes['img_size'] );
        }
        if($disable_alt_image){
            $globalWcLoopTmp['disable_alt_image'] = true;
        }

        $loopCssClass[] = 'products';

        if($layout != 'list'){
            $loopCssClass[] = 'grid-space-' . $item_space;
            if( $layout != 'masonry' || ($layout == 'masonry' && 'custom' != $this->attributes['column_type']) ){
                $loopCssClass[] = 'grid-items';
                $loopCssClass[] = LaStudio_Shortcodes_Helper::renderResponsiveCssColumns($columns);
            }
            $loopCssClass[] = 'products-grid';
            $loopCssClass[] = 'products-grid-' . $style;

            if($layout == 'masonry'){

                $loopCssClass[] = 'js-el la-isotope-container';
                $loopCssClass[] = 'prods_masonry';
                $loopCssClass[] = 'masonry__column-type-'. $this->attributes['column_type'];
                if('custom' != $this->attributes['column_type']){
                    $container_attr = ' data-la_component="DefaultMasonry"';
                }
                else{
                    $mb_columns = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts( $this->attributes['mb_columns'] );
                    $container_attr = ' data-la_component="AdvancedMasonry"';
                    $container_attr .= ' data-item-width="' . ( $this->attributes['base_item_w'] ? intval($this->attributes['base_item_w']) : 300 ) . '"';
                    $container_attr .= ' data-item-height="' . ( $this->attributes['base_item_h'] ? intval($this->attributes['base_item_h']) : 300 ) . '"';
                    $container_attr .= ' data-container-width="' . ( $this->attributes['base_container_w'] ? intval($this->attributes['base_container_w']) : 1170 ) . '"';
                    $container_attr .= ' data-md-col="' . $mb_columns['md'] . '"';
                    $container_attr .= ' data-sm-col="' . $mb_columns['sm'] . '"';
                    $container_attr .= ' data-xs-col="' . $mb_columns['xs'] . '"';
                    $container_attr .= ' data-mb-col="' . $mb_columns['mb'] . '"';

                    if( 'yes' == $this->attributes['custom_item_size'] ) {
                        $_item_sizes = (array) json_decode( urldecode( $this->attributes['item_sizes'] ), true );
                        $__new_item_sizes = array();
                        if(!empty($_item_sizes)){
                            foreach($_item_sizes as $k => $size){
                                $__new_item_sizes[$k] = $size;
                                if(!empty($size['s'])){
                                    $__new_item_sizes[$k]['s'] = LaStudio_Shortcodes_Helper::getImageSizeFormString($size['s']);
                                }
                            }
                        }
                        $globalWcLoopTmp['item_sizes'] = $__new_item_sizes;
                    }
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

            if($existsGlobalWcLoop){
                $original_wc_loop = $GLOBALS['woocommerce_loop'];
            }

            $GLOBALS['woocommerce_loop'] = wp_parse_args( $globalWcLoopTmp,  array(
                'columns'      => $columns['lg'],
                'name'         => $this->type,
                'is_shortcode' => true,
                'is_search'    => false,
                'is_paginated' => ( $this->attributes['display_style'] != 'load-more' && $this->attributes['display_style'] != 'pagination' ),
                'total'        => $products->total,
                'total_pages'  => $products->total_pages,
                'per_page'     => $products->per_page,
                'current_page' => $products->current_page
            ));

            $original_post = isset($GLOBALS['post']) ? $GLOBALS['post'] : false;

            do_action('LaStudio/shortcodes/before_loop', 'woo_shortcode', $this->type, $this->origin_attributes);
            do_action( "woocommerce_shortcode_before_{$this->type}_loop", $this->attributes );

            if($products->total){

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
                    // Render product template.
                    locate_template($loop_tpl, true, false);
                    // Restore product visibility.
                    remove_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );
                }

                echo '</ul></div></div>';
            }

            if($original_post){
                $GLOBALS['post'] = $original_post; // WPCS: override ok.
            }

            do_action( "woocommerce_shortcode_after_{$this->type}_loop", $this->attributes );

            do_action('LaStudio/shortcodes/after_loop', 'woo_shortcode', $this->type, $this->origin_attributes);


            if( ( !empty($this->attributes['display_style']) && $this->attributes['display_style'] != 'all' )
                && !empty($products->total_pages) && !empty($products->current_page) && ($products->total_pages >= $products->current_page )
            ) {

                if($this->attributes['display_style'] == 'pagination'){
                    $__url = add_query_arg('la_paged', 999999999, add_query_arg(null,null));
                    $_paginate_links = paginate_links( array(
                        'base'         => esc_url_raw( str_replace( 999999999, '%#%', $__url ) ),
                        'format'       => '?la_paged=%#%',
                        'add_args'     => '',
                        'current'      => max( 1, $products->current_page ),
                        'total'        => $products->total_pages,
                        'prev_text'    => '<i class="fa fa-long-arrow-left"></i>',
                        'next_text'    => '<i class="fa fa-long-arrow-right"></i>',
                        'type'         => 'list'
                    ) );

                    echo sprintf('<div class="elm-pagination-ajax" data-query-settings="%s" data-request="%s" data-append-type="replace" data-paged="%s" data-parent-container="#%s" data-container="#%s ul.products" data-item-class=".product_item"><div class="la-loading-icon">%s</div><div class="la-pagination">%s</div></div>',
                        esc_attr( wp_json_encode( array(
                            'tag' => $this->type,
                            'atts' => $this->origin_attributes
                        ))),
                        esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
                        esc_attr($products->current_page),
                        esc_attr($unique_id),
                        esc_attr($unique_id),
                        LaStudio_Shortcodes_Helper::getLoadingIcon(),
                        $_paginate_links
                    );
                }

                if($this->attributes['display_style'] == 'load-more'){
                    echo sprintf(
                        '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s ul.products" data-item-class=".product_item">%s<a href="#">%s</a></div>',
                        esc_attr( wp_json_encode( array(
                            'tag' => $this->type,
                            'atts' => $this->origin_attributes
                        ) ) ),
                        esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
                        esc_attr($products->current_page),
                        esc_attr($products->total_pages),
                        esc_attr($unique_id),
                        LaStudio_Shortcodes_Helper::getLoadingIcon(),
                        esc_html($this->attributes['load_more_text'])
                    );
                }
            }

            wp_reset_postdata();

            if(function_exists('wc_reset_loop')){
                wc_reset_loop();
            }
            else{
                woocommerce_reset_loop();
            }

            if($existsGlobalWcLoop){
                $GLOBALS['woocommerce_loop'] = $original_wc_loop; // WPCS: override ok.
            }
        }
        else{

            do_action( "woocommerce_shortcode_{$this->type}_loop_no_results", $this->attributes );

        }

        return '<div id="'.esc_attr( $unique_id ).'" class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">' . ob_get_clean() . '</div>';
    }

    /**
     * Order by rating.
     *
     * @since  3.0.0
     * @param  array $args Query args.
     * @return array
     */
    public static function order_by_rating_post_clauses( $args ) {
        global $wpdb;

        $args['where']   .= " AND $wpdb->commentmeta.meta_key = 'rating' ";
        $args['join']    .= "LEFT JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID) LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)";
        $args['orderby'] = "$wpdb->commentmeta.meta_value DESC";
        $args['groupby'] = "$wpdb->posts.ID";

        return $args;
    }

}