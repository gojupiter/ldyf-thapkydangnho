<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Draven_FavoritePosts {

    protected $setting = null;

    public function __construct( $setting = array() ){
        $this->setting = array_merge(array(
            'cookie_name'       => 'draven_favorites',
            'user_meta_key'     => 'la_favorites',
            'meta_key'          => 'la_total_favorites',
            'cookie_life'       => MONTH_IN_SECONDS
        ), $setting);
        $this->init();
    }

    private function init(){
        add_action( 'wp_ajax_la_helpers_favorite', array( $this, 'ajax_process' ) );
        add_action( 'wp_ajax_nopriv_la_helpers_favorite', array( $this, 'ajax_process' ) );
    }

    public function ajax_process(){

        $check_ajax_referer = true;

        if(apply_filters('draven/check_ajax_referer', false, 'favorite_posts', 'security')){
            $check_ajax_referer = check_ajax_referer( 'favorite_posts', 'security', false );
        }

        if( $check_ajax_referer ){
            if(!isset($_REQUEST['post_id'])){
                wp_send_json_error(array(
                    'message' => __('Invalid Post ID', 'draven')
                ));
            }
            $post_id = absint($_REQUEST['post_id']);
            if(!$post_id){
                wp_send_json_error(array(
                    'message' => __('Invalid Post ID', 'draven')
                ));
            }

            $method = ( !empty( $_REQUEST['type'] ) && $_REQUEST['type'] == 'remove' ? 'remove' : 'add' );

            $status = $this->$method($post_id);
            if($status !== false){
                wp_send_json_success(array(
                    'count' => $status
                ));
            }
        }
        else{
            wp_send_json_error(array(
                'message' => __('Invalid security key', 'draven')
            ));
        }
    }

    public function get_site_id(){
        global $blog_id;
        return is_multisite() ? absint($blog_id) : 1;
    }

    public function load_lists_from_cookie($site_id = null){
        $lists = array();

        if (empty($_COOKIE[ $this->setting['cookie_name'] ])) return $lists;

        if(empty($site_id)){
            $site_id = $this->get_site_id();
        }

        $values = json_decode(stripslashes($_COOKIE[$this->setting['cookie_name']]), true);

        if(empty($values)) return $lists;

        foreach( $values as $value ){
            if( isset($value['site_id']) && $value['site_id'] == $site_id ){
                $lists = $value['posts'];
                break;
            }
        }

        return $lists;
    }

    public function load_lists_from_usermeta($user_id = 0, $site_id = null){

        $lists = array();
        if (!empty($user_id)){
            $user = get_user_by( 'id', $user_id );
            if(!$user) return $lists;
        }
        else{
            $user = wp_get_current_user();
            if(!$user->ID) return $lists;
        }

        $values = get_user_meta( $user->ID , $this->setting['user_meta_key'], true);

        if(empty($values)){
            return $lists;
        }
        else{
            if(empty($site_id)){
                $site_id = $this->get_site_id();
            }
            foreach( $values as $value ){
                if( isset($value['site_id']) && $value['site_id'] == $site_id ){
                    $lists = $value['posts'];
                    break;
                }
            }
        }
        return $lists;
    }

    public function get_total_favorites_for_post( $post_id = 0 ) {
        $total = 0;
        if(!empty($post_id)){
            return (int) get_post_meta($post_id, $this->setting['meta_key'], true);
        }
        return $total;
    }

    public function load_favorite_lists(){
        $lists = array_merge(
            $this->load_lists_from_cookie(),
            $this->load_lists_from_usermeta()
        );
        return array_values( array_unique( $lists ) );
    }

    public function add( $post_id = 0 ) {
        $post_type = get_post_type($post_id);
        if($post_type){
            $total = $this->get_total_favorites_for_post( $post_id );
            $exist_lists = $this->load_favorite_lists();
            if( !in_array( $post_id, $exist_lists ) ) {
                $total = $total + 1;
                array_push( $exist_lists, $post_id );
                update_post_meta($post_id, $this->setting['meta_key'], $total);
                $this->set_lists_for_user($exist_lists);
                $this->set_lists_for_cookie($exist_lists);
            }
            return $total;
        }
        return false;
    }

    public function remove( $post_id = 0 ){
        $post_type = get_post_type($post_id);
        if($post_type){
            $total = $this->get_total_favorites_for_post( $post_id );
            $exist_lists = $this->load_favorite_lists();
            if(($key = array_search($post_id, $exist_lists)) !== false) {
                $total = max(0, $total - 1);
                unset($exist_lists[$key]);
                update_post_meta($post_id, $this->setting['meta_key'], $total);
                $this->set_lists_for_user($exist_lists);
                $this->set_lists_for_cookie($exist_lists);
            }
            return $total;
        }
        return false;
    }

    public function set_lists_for_user( $lists = array(), $user_id = 0 ){

        if (!empty($user_id)){
            $user = get_user_by( 'id', $user_id );
            if(!$user) return;
        }else{
            $user = wp_get_current_user();
            if(!$user->ID) return;
        }

        update_user_meta( $user->ID, $this->setting['user_meta_key'], $lists);
    }

    public function set_lists_for_cookie( $lists = array(), $site_id = null ) {

        if(empty($site_id)){
            $site_id = $this->get_site_id();
        }

        $key = false;

        $values = array();

        if(!empty($_COOKIE[ $this->setting['cookie_name'] ])){
            $values = json_decode(stripslashes($_COOKIE[$this->setting['cookie_name']]), true);
            if(!empty($values)){
                foreach($values as $k => $value){
                    if( isset($value['site_id']) && $value['site_id'] == $site_id ){
                        $key = $k;
                        break;
                    }
                }
                if($key !== false){
                    $values[$key] = array(
                        'site_id'   => $site_id,
                        'posts'     => $lists
                    );
                }else{
                    $values[] = array(
                        'site_id'   => $site_id,
                        'posts'     => $lists
                    );
                }
            }else{
                $values[] = array(
                    'site_id'   => $site_id,
                    'posts'     => $lists
                );
            }
        }

        else {
            $values[] = array(
                'site_id'   => $site_id,
                'posts'     => $lists
            );
        }

        @setcookie( $this->setting['cookie_name'], json_encode($values), time() + $this->setting['cookie_life'], '/' );
    }
}