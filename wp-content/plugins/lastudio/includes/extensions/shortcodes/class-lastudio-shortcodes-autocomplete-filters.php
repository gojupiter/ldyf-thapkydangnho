<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Shortcodes_Autocomplete_Filters{

    public $post_types = array();
    public $taxonomies = array();
    public static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $this->post_types = array(
            'post',
            'product',
            'la_portfolio',
            'la_testimonial',
            'la_team_member',
            'la_block',
            'la_event',
            'la_course',
            'ld_release'
        );
        $this->taxonomies = array(
            'tag',
            'category',
            'product_cat',
            'product_tag',
            'la_portfolio_category',
            'la_portfolio_skill',
            'la_event_category',
            'la_course_category',
            'ld_band',
            'ld_label',
        );
        $this->loadFilter();
    }

    private function loadFilter(){
        $filters = array(
            'la_testimonial' => array(
                'ids'	=> array(
                    'callback' 	=> 'la_testimonialContentTypeCallback',
                    'render' 	=> 'contentTypeRender'
                )
            ),
            'la_block' 		 => array(
                'id'	=> array(
                    'callback' 	=> 'la_blockContentTypeCallback',
                    'render' 	=> 'contentTypeRender'
                )
            ),
            'la_team_member' => array(
                'ids'	=> array(
                    'callback' 	=> 'la_team_memberContentTypeCallback',
                    'render' 	=> 'contentTypeRender'
                )
            ),
            'la_show_posts' => array(
                'category__in' 	=> array(
                    'callback' 	=> 'categoryTaxCallback',
                    'render' 	=> 'categoryTaxRender',
                ),
                'category__not_in' 	=> array(
                    'callback' 	=> 'categoryTaxCallback',
                    'render' 	=> 'categoryTaxRender',
                ),
                'post__in' => array(
                    'callback' 	=> 'postContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                ),
                'post__not_in' => array(
                    'callback' 	=> 'postContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                )
            ),
            'la_show_portfolios' => array(
                'filters' 	=> array(
                    'callback' 	=> 'la_portfolio_skillTaxCallback',
                    'render' 	=> 'la_portfolio_skillTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'la_portfolio_categoryTaxCallback',
                    'render' 	=> 'la_portfolio_categoryTaxRender',
                ),
                'category__not_in' 	=> array(
                    'callback' 	=> 'la_portfolio_categoryTaxCallback',
                    'render' 	=> 'la_portfolio_categoryTaxRender',
                ),
                'post__in' => array(
                    'callback' 	=> 'la_portfolioContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                ),
                'post__not_in' => array(
                    'callback' 	=> 'la_portfolioContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                )
            ),
            'la_portfolio_masonry' => array(
                'filters' 	=> array(
                    'callback' 	=> 'la_portfolio_skillTaxCallback',
                    'render' 	=> 'la_portfolio_skillTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'la_portfolio_categoryTaxCallback',
                    'render' 	=> 'la_portfolio_categoryTaxRender',
                ),
                'category__not_in' 	=> array(
                    'callback' 	=> 'la_portfolio_categoryTaxCallback',
                    'render' 	=> 'la_portfolio_categoryTaxRender',
                ),
                'post__in' => array(
                    'callback' 	=> 'la_portfolioContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                ),
                'post__not_in' => array(
                    'callback' 	=> 'la_portfolioContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                )
            ),
            'sale_products' => array(
                'category' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                )
            ),
            'best_selling_products' => array(
                'category' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                )
            ),
            'top_rated_products' => array(
                'category' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                )
            ),
            'featured_products' => array(
                'category' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                )
            ),
            'recent_products' => array(
                'category' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                )
            ),
            'product_attribute' => array(
                'category' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                )
            ),
            'products' => array(
                'category' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'category__in' 	=> array(
                    'callback' 	=> 'product_catTaxCallback',
                    'render' 	=> 'product_catTaxRender',
                ),
                'tag' 	=> array(
                    'callback' 	=> 'product_tagTaxCallback',
                    'render' 	=> 'product_tagTaxRender',
                )
            ),
            'lastudio_last_releases' => array(
                'artist__in' 	=> array(
                    'callback' 	=> 'ld_bandTaxCallback',
                    'render' 	=> 'ld_bandTaxRender',
                ),
                'artist__not_in' 	=> array(
                    'callback' 	=> 'ld_bandTaxCallback',
                    'render' 	=> 'ld_bandTaxRender',
                ),
                'label__in' 	=> array(
                    'callback' 	=> 'ld_labelTaxCallback',
                    'render' 	=> 'ld_bandTaxRender',
                ),
                'label__not_in' 	=> array(
                    'callback' 	=> 'ld_labelTaxCallback',
                    'render' 	=> 'ld_bandTaxRender',
                ),
                'release__in' => array(
                    'callback' 	=> 'ld_releaseContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                ),
                'release__not_in' => array(
                    'callback' 	=> 'ld_releaseContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                )
            ),
            'la_last_video' => array(
                'category__in' 	=> array(
                    'callback' 	=> 'video_typeTaxCallback',
                    'render' 	=> 'video_typeTaxRender',
                ),
                'category__not_in' 	=> array(
                    'callback' 	=> 'video_typeTaxCallback',
                    'render' 	=> 'video_typeTaxRender',
                ),
                'tag__in' 	=> array(
                    'callback' 	=> 'video_tagTaxCallback',
                    'render' 	=> 'video_tagTaxRender',
                ),
                'tag__not_in' 	=> array(
                    'callback' 	=> 'video_tagTaxCallback',
                    'render' 	=> 'video_tagTaxRender',
                ),
                'video__in' => array(
                    'callback' 	=> 'ld_videoContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                ),
                'video__not_in' => array(
                    'callback' 	=> 'ld_videoContentTypeCallback',
                    'render' 	=> 'contentTypeRender',
                )
            )
        );

        foreach ($filters as $shortcode_name => $fields){
            foreach ( $fields as $field_name => $field){
                foreach( $field as $type => $method ){
                    add_filter( "vc_autocomplete_{$shortcode_name}_{$field_name}_{$type}", array( $this, $method) );
                }
            }
        }
    }

    public function __call($name, $arguments){
        if(strpos($name, 'TaxCallback') !== FALSE){
            $taxonomy = str_replace('TaxCallback', '', $name);
            if(in_array($taxonomy, $this->taxonomies)){
                $query = isset($arguments[0]) ? $arguments[0] : array();
                $slug = isset($arguments[1]) ? $arguments[1] : false;
                if( $taxonomy == 'product_cat' || $taxonomy == 'product_tag' ) {
                    $slug = true;
                }
                return $this->getTaxCallback($query, $taxonomy, $slug);
            }
        }
        elseif(strpos($name, 'TaxRender') !== FALSE){
            $taxonomy = str_replace('TaxRender', '', $name);
            if(in_array($taxonomy, $this->taxonomies)){
                $query = isset($arguments[0]) ? $arguments[0] : array();
                $slug = isset($arguments[1]) ? $arguments[1] : false;
                if( $taxonomy == 'product_cat' || $taxonomy == 'product_tag' ) {
                    $slug = true;
                }
                return $this->getTaxRender($query, $taxonomy, $slug);
            }
        }
        elseif(strpos($name, 'ContentTypeCallback') !== FALSE){
            $post_type = str_replace('ContentTypeCallback', '', $name);
            if(in_array($post_type, $this->post_types)){
                $query = isset($arguments[0]) ? $arguments[0] : array();
                return $this->getPostTypeCallback($query, $post_type);
            }
        }
    }

    private function getTaxCallback( $query, $taxonomy, $slug = false){
        global $wpdb;
        $cat_id = (int) $query;
        $query = trim( $query );
        $array_category = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT a.term_id AS id, b.name as name, b.slug AS slug
								FROM {$wpdb->term_taxonomy} AS a
								INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
								WHERE a.taxonomy = '%s' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )",
                $taxonomy,
                $cat_id > 0 ? $cat_id : -1,
                stripslashes( $query ),
                stripslashes( $query )
            ), ARRAY_A );

        $result = array();
        if ( is_array( $array_category ) && !empty( $array_category ) ) {
            foreach ( $array_category as $value ) {
                $data = array();
                $data[ 'value' ] = $slug ? $value[ 'slug' ] : $value[ 'id' ];
                $data[ 'label' ] = __( 'Id', 'lastudio' ) . ': ' .
                    $value[ 'id' ] .
                    ( ( strlen( $value[ 'name' ] ) > 0 ) ? ' - ' . __( 'Name', 'lastudio' ) . ': ' .
                        $value[ 'name' ] : '' ) .
                    ( ( strlen( $value[ 'slug' ] ) > 0 ) ? ' - ' . __( 'Slug', 'lastudio' ) . ': ' .
                        $value[ 'slug' ] : '' );
                $result[ ] = $data;
            }
        }

        return $result;
    }

    private function getTaxRender( $query, $taxonomy, $slug = false){
        $query = $query[ 'value' ];

        if($slug){
            $cat_id = sanitize_title($query);
            $term = get_term_by('slug', $cat_id, $taxonomy);
        }
        else{
            $cat_id = (int) $query;
            $term = get_term( $cat_id, $taxonomy );
        }

        if(is_wp_error($term)){
            return false;
        }

        $term_slug = $term->slug;
        $term_title = $term->name;
        $term_id = $term->term_id;

        $term_slug_display = '';
        if ( !empty( $term_slug ) ) {
            $term_slug_display = ' - ' . __( 'Slug', 'lastudio' ) . ': ' . $term_slug;
        }

        $term_title_display = '';
        if ( !empty( $term_title ) ) {
            $term_title_display = ' - ' . __( 'Title', 'lastudio' ) . ': ' . $term_title;
        }

        $term_id_display = __( 'Id', 'lastudio' ) . ': ' . $term_id;

        $data = array();
        $data[ 'value' ] = $slug ? $term_slug : $term_id;
        $data[ 'label' ] = $term_id_display . $term_title_display . $term_slug_display;

        return !empty( $data ) ? $data : false;
    }

    private function getPostTypeCallback($query,$post_type){
        global $wpdb;
        $post_id = (int) $query;
        $array_posts = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT a.ID AS id, a.post_title AS title
						FROM {$wpdb->posts} AS a
						WHERE a.post_type = '%s'
						AND a.post_status LIKE 'publish'
						AND ( a.ID = '%d' OR a.post_title LIKE '%%%s%%' )",
                $post_type,
                $post_id > 0 ? $post_id : -1,
                stripslashes( $query )
            ), ARRAY_A );

        $results = array();
        if ( is_array( $array_posts ) && !empty( $array_posts ) ) {
            foreach ( $array_posts as $value ) {
                $data = array();
                $data[ 'value' ] = $value[ 'id' ];
                $data[ 'label' ] = __( 'Id', 'lastudio' ) . ': ' .
                    $value[ 'id' ] .
                    ( ( strlen( $value[ 'title' ] ) > 0 ) ? ' - ' . __( 'Title', 'lastudio' ) . ': ' .
                        $value[ 'title' ] : '' );
                $results[ ] = $data;
            }
        }
        return $results;
    }

    public function contentTypeRender($query){
        $query = trim( $query[ 'value' ] );
        if ( !empty( $query ) ) {
            $post_object = get_post( (int) $query );
            if ( is_object( $post_object ) ) {
                $slug = $post_object->post_name;
                $title = $post_object->post_title;
                $post_id = $post_object->ID;
                $post_slug_display = '';
                if ( !empty( $slug ) ) {
                    $post_slug_display = ' - ' . __( 'Slug', 'lastudio' ) . ': ' . $slug;
                }
                $post_title_display = '';
                if ( !empty( $title ) ) {
                    $post_title_display = ' - ' . __( 'Title', 'lastudio' ) . ': ' . $title;
                }
                $post_id_display = __( 'Id', 'lastudio' ) . ': ' . $post_id;
                $data = array();
                $data[ 'value' ] = $post_id;
                $data[ 'label' ] = $post_id_display . $post_title_display . $post_slug_display;
                return !empty( $data ) ? $data : false;
            }
            return false;
        }
        return false;
    }
}