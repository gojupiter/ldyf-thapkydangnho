<?php

class LaStudio_Content_Type {

    private $post_types = array();
    private $taxonomies = array();

    public function __construct( $post_types = array(), $taxonomies = array() )
    {
        $this->post_types = $post_types;
        $this->taxonomies = $taxonomies;
    }

    public function setup_filters() {
        $this->post_types = apply_filters('LaStudio/core/post_type_allow', $this->post_types);
        $this->taxonomies = apply_filters('LaStudio/core/taxonomy_allow', $this->taxonomies);
    }

    public function register_content_type(){

        if( !empty( $this->post_types ) ) {
            foreach ( $this->post_types as $post_type => $args ) {
                if( !empty($args ) ) {
                    register_post_type( $post_type, $args );
                }
            }
        }
        if( !empty( $this->taxonomies ) ) {
            foreach ( $this->taxonomies as $taxonomy => $args ) {
                if( !empty($args) && !empty($args['post_type']) && !empty( $args['args'] ) ) {
                    register_taxonomy( $taxonomy, $args['post_type'], $args['args'] );
                }
            }
        }
    }

    public function single_template( $template ){
        $object = get_queried_object();
        if( !empty( $this->post_types ) && array_key_exists( $object->post_type, $this->post_types) ) {
            $new_type_name = str_replace('_', '-', $object->post_type);
            $array_tpl_allow = array(
                "single-{$object->post_type}-{$object->post_name}.php",
                "single-{$new_type_name}-{$object->post_name}.php",
                "single-{$object->post_type}.php",
                "single-{$new_type_name}.php"
            );
            $new_tpl = locate_template( $array_tpl_allow );
            if(file_exists($new_tpl)){
                return $new_tpl;
            }
        }
        return $template;
    }

    public function archive_template( $template ){
        $post_type = get_query_var( 'post_type' );
        if(!empty( $this->post_types ) && array_key_exists( $post_type, $this->post_types )){
            $new_type_name = str_replace('_', '-', $post_type);
            $array_tpl_allow = array(
                "archive-{$post_type}.php",
                "archive-{$new_type_name}.php"
            );
            $new_tpl = locate_template( $array_tpl_allow );
            if(file_exists($new_tpl)){
                return $new_tpl;
            }
        }

        return $template;
    }

    public function taxonomy_template( $template ){

        $term = get_queried_object();

        if(! empty( $term->slug ) && !empty( $this->taxonomies ) && array_key_exists( $term->taxonomy, $this->taxonomies )){
            $array_tpl_allow = array();
            $new_type_name = str_replace('_', '-', $term->taxonomy);
            $slug_decoded = urldecode( $term->slug );
            if ( $slug_decoded !== $term->slug ) {
                $array_tpl_allow[] = "taxonomy-{$term->taxonomy}-{$slug_decoded}.php";
                $array_tpl_allow[] = "taxonomy-{$new_type_name}-{$slug_decoded}.php";
            }
            $array_tpl_allow[] = "taxonomy-{$term->taxonomy}-{$term->slug}.php";
            $array_tpl_allow[] = "taxonomy-{$new_type_name}-{$term->slug}.php";
            $array_tpl_allow[] = "taxonomy-{$term->taxonomy}.php";
            $array_tpl_allow[] = "taxonomy-{$new_type_name}.php";

            if(in_array($term->taxonomy, array('la_portfolio_category', 'la_portfolio_skill'))){
                $array_tpl_allow[] = "archive-la_portfolio.php";
                $array_tpl_allow[] = "archive-la-portfolio.php";
            }

            $new_tpl = locate_template( $array_tpl_allow );
            if(file_exists($new_tpl)){
                return $new_tpl;
            }

        }
        return $template;
    }

}
