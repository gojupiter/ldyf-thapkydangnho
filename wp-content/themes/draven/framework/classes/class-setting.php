<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Draven_Setting{

    protected $all_options = null;

    public $args = array();

    public function __construct( $args = array() ) {
        $this->args = array_merge(array(
            'option_name'       => Draven::get_option_name(),
            'post_meta_name'    => Draven::get_original_option_name(),
            'term_meta_name'    => Draven::get_original_option_name()
        ), $args);
    }

    public function get_all() {
        if( is_null($this->all_options) || is_customize_preview() ) {
            $this->all_options = get_option( $this->args['option_name'], array() );
        }
        return $this->all_options;
    }

    public function get( $key = '', $default = '' ){

        $option_value = $this->get_all();
        if(!empty($option_value[$key])){
            $value = $option_value[$key];
        }
        else{
            $value = $default;
        }

        return apply_filters("draven/setting/option/get_single", $value, $key );
    }

    public function get_post_meta( $object_id, $sub_key = '', $meta_key = '', $single = true ) {

        if (!is_numeric($object_id)) {
            return false;
        }
        if (empty($meta_key)) {
            $meta_key = $this->args['post_meta_name'];
        }

        $object_value = get_post_meta($object_id, $meta_key, $single);

        if(!empty($sub_key)){
            if( $single ) {
                if(isset($object_value[$sub_key])){
                    return $object_value[$sub_key];
                }
                else{
                    return false;
                }
            }
            else{
                $tmp = array();
                if(!empty($object_value)){
                    foreach( $object_value as $k => $v ){
                        $tmp[] = (isset($v[$sub_key])) ? $v[$sub_key] : '';
                    }
                }
                return $tmp;
            }
        }
        else{
            return $object_value;
        }
    }

    public function get_term_meta( $object_id, $sub_key = '', $meta_key = '', $single = true ) {

        if (!is_numeric($object_id)) {
            return false;
        }
        if (empty($meta_key)) {
            $meta_key = $this->args['term_meta_name'];
        }

        $object_value = get_term_meta($object_id, $meta_key, $single);

        if(!empty($sub_key)){
            if( $single ) {
                if(isset($object_value[$sub_key])){
                    return $object_value[$sub_key];
                }
                else{
                    return false;
                }
            }
            else{
                $tmp = array();
                if(!empty($object_value)){
                    foreach( $object_value as $k => $v ){
                        $tmp[] = (isset($v[$sub_key])) ? $v[$sub_key] : '';
                    }
                }
                return $tmp;
            }
        }
        else{
            return $object_value;
        }
    }

    public function get_setting_by_context( $key, $default = '', $context = array()){

        if(empty($key)){
            return $default;
        }

        if(empty($context)){
            $context = Draven()->get_current_context();
        }
        if(!is_array($context)){
            $context = (array) $context;
        }

        $value = $value_default = $this->get( $key, $default );

        if(in_array('is_home', $context)){
            $_value = $this->get("{$key}_blog");

            if(!empty($_value)){
                if(is_array($_value)){
                    if(Draven_Helper::is_not_empty_array_ref($_value)){
                        $value = $_value;
                    }
                }else{
                    if($_value !== 'inherit'){
                        $value = $_value;
                    }
                }
            }

        }

        if(in_array('is_home', $context) || in_array('is_front_page', $context)){
            $c_page_id = Draven()->get_page_id();
            if($c_page_id){
                $_value = $this->get_post_meta( $c_page_id, $key );
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
            }

        }

        elseif(in_array('is_singular', $context)){

            $post_type = get_query_var('post_type') ? get_query_var('post_type') : ( is_singular('post') ? 'post' : 'page' );

            if(is_array($post_type)){
                $post_type = $post_type[0];
            }

            $post_type = str_replace('la_', '', $post_type);

            /*
             * get {$key} is layout from blog
             */

            if(is_singular('post') && $key == 'layout'){
                $_value = $this->get('layout_blog');
                if(!empty($_value) && $_value !== 'inherit'){
                    $value = $_value;
                }
            }

            $_value = $this->get("{$key}_single_{$post_type}", $value_default );
            if(!empty($_value)){
                if(is_array($_value)){
                    if(Draven_Helper::is_not_empty_array_ref($_value)){
                        $value = $_value;
                    }
                }else{
                    if($_value !== 'inherit'){
                        $value = $_value;
                    }
                }
            }
            $_value = $this->get_post_meta( get_queried_object_id(), $key );

            if(!empty($_value)){
                if(is_array($_value)){
                    if(Draven_Helper::is_not_empty_array_ref($_value)){
                        $value = $_value;
                    }
                }else{
                    if($_value !== 'inherit'){
                        $value = $_value;
                    }
                }
            }

            if(is_singular('elementor_library')){
                if( $key == 'layout' ) {
                    $value = 'col-1c';
                }
                if( $key == 'page_title_bar_layout'){
                    $value = 'hide';
                }
                if( $key == 'hide_header'){
                    $value = 'yes';
                }
                if( $key == 'hide_footer'){
                    $value = 'yes';
                }
            }

        }

        elseif(in_array('is_archive', $context)){

            if(in_array('is_shop', $context)){
                $_value = $this->get("{$key}_archive_product", $value_default);
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
                if(Draven()->get_page_id()){
                    $_value = $this->get_post_meta( Draven()->get_page_id(), $key);
                    if(!empty($_value)){
                        if(is_array($_value)){
                            if(Draven_Helper::is_not_empty_array_ref($_value)){
                                $value = $_value;
                            }
                        }else{
                            if($_value !== 'inherit'){
                                $value = $_value;
                            }
                        }
                    }
                }
            }
            elseif(in_array('is_product_taxonomy', $context)){
                $_value = $this->get("{$key}_archive_product", $value_default);
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
                $_value = $this->get_term_meta( get_queried_object_id(), $key);
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
            }
            elseif(in_array('is_post_type_archive', $context) && is_post_type_archive('la_portfolio')){
                $_value = $this->get("{$key}_archive_portfolio", $value_default);
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
            }
            elseif(in_array('is_tax', $context) && is_tax(get_object_taxonomies( 'la_portfolio' ))){
                $_value = $this->get("{$key}_archive_portfolio", $value_default);
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
                $_value = $this->get_term_meta( get_queried_object_id(), $key);
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
            }
            else{
                if($key == 'layout'){
                    if( is_tag() || is_category() ){
                        $_value = $this->get("layout_blog");
                        if(!empty($_value) && $_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
                else{
                    $_value = $this->get("{$key}_archive_post", $value_default);
                    if(!empty($_value)){
                        if(is_array($_value)){
                            if(Draven_Helper::is_not_empty_array_ref($_value)){
                                $value = $_value;
                            }
                        }else{
                            if($_value !== 'inherit'){
                                $value = $_value;
                            }
                        }
                    }
                }

                $_value = $this->get_term_meta( get_queried_object_id(), $key);
                if(!empty($_value)){
                    if(is_array($_value)){
                        if(Draven_Helper::is_not_empty_array_ref($_value)){
                            $value = $_value;
                        }
                    }else{
                        if($_value !== 'inherit'){
                            $value = $_value;
                        }
                    }
                }
            }
        }

        else{
            /*
             * For search & 404 page
             */
            $value = $value_default;
        }

        if($value === 'inherit'){
            $value = $default;
        }

        return apply_filters('draven/setting/get_setting_by_context', $value, $key, $context);
    }

}