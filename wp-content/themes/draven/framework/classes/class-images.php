<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Draven_Images {

    public static $image_sizes = array();

    public function __construct(){

    }

    public function jpeg_quality( $quality ){
        return 100;
    }

    public function get_image_sizes(){
        global $_wp_additional_image_sizes;
        self::$image_sizes = !empty($_wp_additional_image_sizes) ? $_wp_additional_image_sizes : array();
        $image_sizes = array('thumbnail', 'medium', 'large'); // Standard sizes
        foreach($image_sizes as $size){
            if(!isset(self::$image_sizes[$size])){
                self::$image_sizes[$size] = array(
                    'width'     => get_option( "{$size}_size_w" ),
                    'height'    => get_option( "{$size}_size_h" ),
                    'crop'      =>  (bool) get_option( "{$size}_crop" )
                );
            }
        }
        return self::$image_sizes;
    }

    public function get_sizes_by_name( $size ){
        $return = false;
        if(empty($size)){
            return false;
        }
    }

    public function before_resize(){
//        if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) { // check that we are, in fact, using Photon in the first place
//            draven_deactive_filter( 'image_downsize', array( Jetpack_Photon::instance(), 'filter_image_downsize' ) );
//        }
//        add_filter('image_downsize', array( &$this, 'image_downsize' ), 10, 3 );
    }

    public function after_resize(){
//
//        //draven_deactive_filter('image_downsize', array( &$this, 'image_downsize' ), 10 );
//        if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
//            add_filter( 'image_downsize', array( Jetpack_Photon::instance(), 'filter_image_downsize' ), 10, 3 );
//        }
    }

    public function image_downsize( $downsize = false , $id, $size ) {
        // do not resize image if is internal media
        if(in_array($size, get_intermediate_image_sizes()) || is_customize_preview()){
            return $downsize;
        }
        $crop = true;
        // use specific w/h dimensions requested
        if ( is_array( $size ) ) {
            $width = $size[0];
            $height = isset($size[1]) ? $size[1] : 0;
            // make a size name from requested dimensions as a fallback for saving to meta
            $size = $width.'x'.$height;
            // if dimensions match a named size, use that instead
            foreach ( $this->get_image_sizes() as $size_name => $size_atts ) {
                if ( $width == $size_atts['width'] && $height == $size_atts['height'] )
                    $size = $size_name;
            }
        }
        elseif( 'integer' === gettype($size) ) {
            $width = $size;
            $height = 0;
            // make a size name from requested dimensions as a fallback for saving to meta
            $size = $width . 'x' . $height;
            // if dimensions match a named size, use that instead
            foreach ( $this->get_image_sizes() as $size_name => $size_atts) {
                if ($width == $size_atts['width'] && $height == $size_atts['height'])
                    $size = $size_name;
            }
            // or get key/values (width, height, crop) from named size
        }
        elseif ( array_key_exists( $size, $this->get_image_sizes() ) ) {
            $tmp = $this->get_image_sizes();
            if(isset($tmp[$size])){
                extract( $tmp[$size] );
            }
        }
        else {
            // unrecognized size, exit to handle as normal
            return $downsize;
        }

        $meta = wp_get_attachment_metadata( $id );


        // exit if there's already a generated image with the right dimensions
        // the default downsize function would use it anyway (even if it had a different name)

        if(isset($meta['sizes']) &&  array_key_exists( $size, $meta['sizes'] )){
            if( $width && $height && $width == $meta['sizes'][$size]['width'] && $height == $meta['sizes'][$size]['height']){
                return $downsize;
            }
            if($width == 0 && $height == $meta['sizes'][$size]['height']){
                return $downsize;
            }
            if($height == 0 && $width == $meta['sizes'][$size]['width']){
                return $downsize;
            }
        }

        if(!empty($meta['width']) && !empty($meta['height'])){
            if(($width == $meta['width'] && $height == $meta['height']) || ($width == $meta['width'] && $height > $meta['height']) || ($height == $meta['height'] && $width > $meta['width']) ){
                return $downsize;
            }
        }
        else{
            if( (!empty($meta['width']) && $width >= $meta['width']) || (!empty($meta['height']) && $height >= $meta['height']) ){
                return $downsize;
            }
        }

        $crop = apply_filters('draven/filter/image_helper/crop', $crop, $id, $size);

        // nothing matching size exists, generate and save new image from original
        $intermediate = image_make_intermediate_size( get_attached_file( $id ), $width, $height, $crop );

        // exit if failed creating image
        if ( !is_array( $intermediate ) )
            return $downsize;

        // save the new size parameters in meta (to find it next time)
        $meta['sizes'][$size] = $intermediate;
        wp_update_attachment_metadata( $id, $meta );

        // this step is from the default image_downsize function in media.php
        // "might need to further constrain it if content_width is narrower"
        list( $width, $height ) = image_constrain_size_for_editor( $intermediate['width'], $intermediate['height'], $size );

        // use path of original file with new filename
        $original_url = wp_get_attachment_url( $id );
        $original_basename = wp_basename( $original_url );
        $img_url = str_replace($original_basename, $intermediate['file'], $original_url);

        // 'Tis done, and here's the image
        return array( $img_url, $width, $height, true );
    }

    public function get_attachment_image( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '' ){
        $this->before_resize();
        $html = wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
        $this->after_resize();
        return $html;
    }

    public function get_attachment_image_url( $attachment_id, $size = 'thumbnail', $icon = false  ){
        $this->before_resize();
        $html = wp_get_attachment_image_url( $attachment_id, $size, $icon );
        $this->after_resize();
        return $html;
    }

    public function get_attachment_image_src( $attachment_id, $size = 'thumbnail', $icon = false  ){
        $this->before_resize();
        $html = wp_get_attachment_image_src( $attachment_id, $size, $icon );
        $this->after_resize();
        return $html;
    }

    public function get_post_thumbnail($post = null, $size = 'thumbnail', $attr = ''){
        $this->before_resize();
        $html = get_the_post_thumbnail( $post, $size, $attr );
        $this->after_resize();
        return $html;
    }


    public function the_post_thumbnail($post = null, $size = 'thumbnail', $attr = '' ){
        echo draven_render_variable($this->get_post_thumbnail($post, $size, $attr));
    }

    public function get_post_thumbnail_url($post = null, $size = 'thumbnail'){
        return $this->get_attachment_image_url( get_post_thumbnail_id($post), $size );
    }

    public function render_image( $url = '', $attr = array()){
        if(empty($url)){
            return '';
        }
        else{
            $tmp = array();
            $attr = shortcode_atts(array(
                'width' => '',
                'height' => '',
                'alt'   => '',
                'class' => 'wp-post-image'
            ), $attr);

            if(Draven_Helper::is_enable_image_lazy()){
                $attr['srcset'] = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
                $attr['class'] .= ' la-lazyload-image';
                $attr['data-src'] = $url;
            }

            foreach($attr as $k => $v){
                $tmp[] = esc_attr($k) . '="'. esc_attr($v) .'"';
            }
            return sprintf(
                '<img src="%1$s" %2$s/>',
                esc_url($url),
                implode(' ', $tmp)
            );
        }
    }
}