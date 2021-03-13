<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


if(!class_exists('LaStudio_Swatch_Attribute_Configuration_Object')){

    class LaStudio_Swatch_Attribute_Configuration_Object {

        private $_attribute_name;
        private $_swatch_options;
        private $_product_id;

        /**
         *
         * @param WC_Product $product
         * @param string $attribute The name of the attribute.
         */

        public function __construct( $product, $attribute ) {
            $swatch_options = maybe_unserialize( get_post_meta( $product->get_id(), '_la_swatch_data', true ) );

            if ( !empty( $swatch_options ) ) {

                $st_name = str_replace('-', '_', sanitize_title( $attribute ));
                $hashed_name = md5( $st_name );
                $lookup_name = '';

                //Normalize the key we use, this is for backwards compatibility.
                if ( isset( $swatch_options[$hashed_name] ) ) {
                    $lookup_name = $hashed_name;
                } elseif ( isset( $swatch_options[$st_name] ) ) {
                    $lookup_name = $st_name;
                }
                if(!isset($swatch_options[$lookup_name]['size'])){
                    $swatch_options[$lookup_name]['size'] = 'la_swatches_image_size';
                }
                $this->_attribute_name = $attribute;
                $this->_product_id = $product->get_id();
                $this->_swatch_options = $swatch_options[$lookup_name];
            }

        }

        /**
         * Returns the type of input to display.
         */
        public function get_swatch_type() {
            return apply_filters('LaStudio/swatches/configuration_object/get_swatch_type', $this->sg('type', 'default' ), $this);
        }

        public function get_swatch_display_size() {
            return apply_filters( 'LaStudio/swatches/configuration_object/get_swatch_display_size', $this->sg( 'size' ), $this );
        }

        public function get_swatch_label_display_style() {
            return apply_filters( 'LaStudio/swatches/configuration_object/get_swatch_label_display_style', $this->sg('layout', 'default'), $this );
        }

        public function get_swatch_display_style() {
            return apply_filters( 'LaStudio/swatches/configuration_object/get_swatch_display_style', $this->sg('style', 'default'), $this );
        }

        public function get_swatch_configs() {
            return apply_filters( 'LaStudio/swatches/configuration_object/get_swatch_options', $this->_swatch_options, $this );
        }

        /**
         * Safely get a configuration value from the swatch options.
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        private function sg( $key, $default = null ) {
            return isset( $this->_swatch_options[$key] ) ? $this->_swatch_options[$key] : $default;
        }

    }
}