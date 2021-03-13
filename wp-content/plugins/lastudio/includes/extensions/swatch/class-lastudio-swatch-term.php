<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Swatch_Term {

    protected $attribute_meta_key;
    protected $term_id;
    protected $term;
    protected $term_label;
    protected $term_slug;
    protected $taxonomy_slug;
    protected $selected;
    protected $type;
    protected $color;
    protected $thumbnail_src;
    protected $thumbnail_id;
    protected $size = 'la_swatches_image_size';
    protected $width = 32;
    protected $height = 32;
    protected $label_type = 'default';
    protected $layout_style = 'default';

    public function __construct( $config, $term_id, $taxonomy, $selected = false, $size = 'la_swatches_image_size' ) {

        if(NULL != $config){
            $this->label_type = $config->get_swatch_label_display_style();
            $this->layout_style = $config->get_swatch_display_style();
        }

        $this->attribute_meta_key = 'swatches_id';

        $term_object = get_term( $term_id, $taxonomy );
        if(!is_wp_error($term_object)){
            $this->term_id = $term_object->term_id;
            $this->term_label = $term_object->name;
            $this->term_slug = $term_object->slug;
        }
        else{
            $this->term_id = 0;
            $this->term_label = 'none';
            $this->term_slug = 'none';
        }

        $this->taxonomy_slug = $taxonomy;
        $this->selected = $selected;

        $this->size = $size;

        $this->init_swatch_sizes();
        $this->on_init();

    }

    protected function init_swatch_sizes() {
        $size = $this->get_size_name();

        if( $size == 'la_swatches_image_size' ) {
            $la_swatches_configs = get_option('la_swatches_configs', array());
            if(isset($la_swatches_configs['image_size']['width'])) {
                $this->width = $la_swatches_configs['image_size']['width'];
            }else{
                $this->width = 40;
            }
            if(isset($la_swatches_configs['image_size']['height'])) {
                $this->height = $la_swatches_configs['image_size']['height'];
            }
            else{
                $this->height = 40;
            }
        }
        else{
            $_wp_additional_image_sizes = wp_get_additional_image_sizes();

            $the_size = isset( $_wp_additional_image_sizes[$size] ) ? $_wp_additional_image_sizes[$size] : $_wp_additional_image_sizes['shop_thumbnail'];
            if ( isset( $the_size['width'] ) && isset( $the_size['height'] ) ) {
                $this->width = $the_size['width'];
                $this->height = $the_size['height'];
            } else {
                $this->width = 40;
                $this->height = 40;
            }
        }
    }

    protected function on_init() {

        $term_data = get_term_meta( $this->term_id, 'la_swatches', true );

        $type = (!empty($term_data['type']) ? $term_data['type'] : 'none');
        $color = (!empty($term_data['color']) ? $term_data['color'] : '#fff');
        $this->thumbnail_id = (!empty($term_data['photo']) ? $term_data['photo'] : 0);

        $this->type = $type;
        $this->thumbnail_src = WC()->instance()->plugin_url() . '/assets/images/placeholder.png';
        $this->color = '#FFFFFF';

        if ( $type == 'photo' ) {
            if ( $this->thumbnail_id ) {
                $current_img = apply_filters('LaStudio/swatches/get_attribute_thumbnail_src', wp_get_attachment_image_url($this->thumbnail_id, $this->size), $this->thumbnail_id, $this->size, $this);
                if ( $current_img ) {
                    $this->thumbnail_src = $current_img;
                } else {
                    $this->thumbnail_src = WC()->instance()->plugin_url() . '/assets/images/placeholder.png';
                }
            } else {
                $this->thumbnail_src = WC()->instance()->plugin_url() . '/assets/images/placeholder.png';
            }
        } elseif ( $type == 'color' ) {
            $this->color = $color;
        }
    }

    public function get_output( $product_url = '#', $data_thumb = '', $placeholder = true, $placeholder_src = 'default' ) {

        $picker = '';

        $href = $product_url;
        $anchor_class = 'swatch-anchor';

        $term_label = $this->get_term_label();

        if($this->get_label_type() == 'only_label'){
            $inline_style = 'min-width:' . $this->get_width() . 'px;';
            $inline_style .= 'min-height:' . $this->get_height() . 'px;';
            $inline_style .= 'line-height:' . $this->get_height() . 'px;';
            $picker = sprintf(
                '<a href="%s" style="%s" title="%s" class="%s"><span>%s</span></a>',
                esc_url( $href ),
                esc_attr( $inline_style ),
                esc_attr( $term_label ),
                esc_attr( $anchor_class ),
                esc_html( $term_label )
            );
        }
        elseif ( $this->get_type() == 'photo' || $this->get_type() == 'image' ) {

            $inline_style = 'width:' . $this->get_width() . 'px;';
            $inline_style .= 'height:' . $this->get_height() . 'px;';

            $picker = sprintf(
                '<a href="%s" style="%s" title="%s" class="%s"><img src="%s" alt="%s" class="%s" width="%s" height="%s"/></a>',
                esc_url( $href ),
                esc_attr( $inline_style ),
                esc_attr( $term_label ),
                esc_attr( $anchor_class ),
                esc_url( $this->get_image_src() ),
                esc_attr( $term_label ),
                esc_attr( 'swatch-img wp-post-image swatch-photo'. $this->meta_key() ),
                $this->get_width(),
                $this->get_height()
            );
        }
        elseif ( $this->get_type() == 'color' ) {

            $inline_style = 'text-indent:-9999px;';
            $inline_style .= 'width:' . $this->get_width() . 'px;';
            $inline_style .= 'height:' . $this->get_height() . 'px;';
            $inline_style .= 'background-color:' . $this->get_color();
            $picker = sprintf(
                '<a href="%s" style="%s" title="%s" class="%s">%s</a>',
                esc_url( $href ),
                esc_attr( $inline_style ),
                esc_attr( $term_label ),
                esc_attr( $anchor_class ),
                esc_html( $term_label )
            );
        }
        elseif ( $placeholder ) {
            if ( $placeholder_src == 'default' ) {
                $src = WC()->instance()->plugin_url() . '/assets/images/placeholder.png';
            } else {
                $src = $placeholder_src;
            }
            $inline_style = 'width:' . $this->get_width() . 'px;';
            $inline_style .= 'height:' . $this->get_height() . 'px;';
            $picker = sprintf(
                '<a href="%s" style="%s" title="%s" class="%s"><img src="%s" alt="%s" class="%s" width="%s" height="%s"/></a>',
                esc_url( $href ),
                esc_attr( $inline_style ),
                esc_attr( $term_label ),
                esc_attr( $anchor_class ),
                esc_url( $src ),
                esc_attr( $term_label ),
                esc_attr( 'swatch-img wp-post-image swatch-photo'. $this->meta_key() ),
                $this->get_width(),
                $this->get_height()
            );
        }
        else {
            return '';
        }

        $picker .= sprintf(
            '<span class="swatch-anchor-label">%s</span>',
            esc_attr( $term_label )
        );

        $html_class_wrap = array('select-option', 'swatch-wrapper');
        $html_class_wrap[] = 'la-swatch-item-style-' . $this->get_layout_style();

        if( $this->get_label_type() == 'only_label' ) {
            $html_class_wrap[] = 'swatch-only-label';
        }
        if( $this->selected ) {
            $html_class_wrap[] = 'selected';
        }

        $html_output = '<div data-thumb="'.esc_attr($data_thumb).'" class="' . esc_attr(implode(' ', $html_class_wrap )) . '" data-attribute="' . esc_attr($this->taxonomy_slug) . '" data-value="' . esc_attr( $this->term_slug ) . '">';
        $html_output .= apply_filters( 'LaStudio/swatches/picker_output', $picker, $this );
        $html_output .= '</div>';

        return $html_output;
    }

    public function get_term_label(){
        return $this->term_label;
    }

    public function get_type() {
        return $this->type;
    }

    public function get_label_type(){
        return $this->label_type;
    }

    public function get_layout_style(){
        return $this->layout_style;
    }

    public function get_color() {
        return $this->color;
    }

    public function get_image_src() {
        return $this->thumbnail_src;
    }

    public function get_image_id() {
        return $this->thumbnail_id;
    }

    public function get_size_name(){
        return $this->size;
    }

    public function get_width() {
        return $this->width;
    }

    public function get_height() {
        return $this->height;
    }

    public function meta_key() {
        return $this->taxonomy_slug . '_' . $this->attribute_meta_key;
    }

}