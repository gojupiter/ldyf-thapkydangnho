<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Swatch_Product_Term extends LaStudio_Swatch_Term {

    protected $attribute_options;

    public function __construct($config, $option, $taxonomy, $selected = false) {

        $this->layout_style = $config->get_swatch_display_style();
        $this->label_type = $config->get_swatch_label_display_style();
        $this->attribute_options = $config->get_swatch_configs();

        $this->taxonomy_slug = $taxonomy;
        if (taxonomy_exists($taxonomy)) {
            $this->term = get_term($option, $taxonomy);
            $this->term_label = $this->term->name;
            $this->term_slug = $this->term->slug;
            $this->term_name = $this->term->name;
        } else {
            $this->term = false;
            $this->term_label = $option;
            $this->term_slug = $option;
        }

        $this->selected = $selected;

        $this->size = $this->attribute_options['size'];

        $this->init_swatch_sizes();
        $this->init_term_data();

    }

    private function init_term_data(){

        $attribute_options = $this->attribute_options;

        $key = md5( sanitize_title($this->term_slug) );
        $old_key = sanitize_title($this->term_slug);
        $lookup_key = '';
        if (isset($attribute_options['attributes'][$key])) {
            $lookup_key = $key;
        } elseif (isset($attribute_options['attributes'][$old_key])) {
            $lookup_key = $old_key;
        }

        $this->type = isset($attribute_options['attributes'][$lookup_key]['type']) ? $attribute_options['attributes'][$lookup_key]['type'] : 'color';
        $this->color = isset($attribute_options['attributes'][$lookup_key]['color']) ? $attribute_options['attributes'][$lookup_key]['color'] : '#fff;';

        if (isset($attribute_options['attributes'][$lookup_key]['photo']) && $attribute_options['attributes'][$lookup_key]['photo']) {
            $this->thumbnail_id = $attribute_options['attributes'][$lookup_key]['photo'];

            $current_img = apply_filters('LaStudio/swatches/get_attribute_thumbnail_src', wp_get_attachment_image_url($this->thumbnail_id, $this->size), $this->thumbnail_id, $this->size, $this);

            if($current_img){
                $this->thumbnail_src = $current_img;
            }
            else{
                $this->thumbnail_src = WC()->plugin_url() . '/assets/images/placeholder.png';
            }
        } else {
            $this->thumbnail_src = WC()->plugin_url() . '/assets/images/placeholder.png';
        }
    }
}