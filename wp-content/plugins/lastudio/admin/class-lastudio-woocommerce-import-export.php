<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LaStudio
 * @subpackage LaStudio/admin
 * @author     Duy Pham <dpv.0990@gmail.com>
 */
class LaStudio_WooCommerce_Import_Export {

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( ) {

    }

    /**
     * This function will be override the hook `woocommerce_product_export_skip_meta_keys`
     *
     * @param $meta_keys
     * @return array
     */
    public function export_skip_meta_keys( $meta_keys ) {
        $meta_keys[] = '_wxr_import_user_slug';
        $meta_keys[] = 'slide_template';
        $meta_keys[] = '_la_swatch_data';
        $meta_keys[] = '_swatch_type';
        $meta_keys[] = '_la_360_enable';
        return $meta_keys;
    }

    /**
     * This function will be override the hook `woocommerce_product_export_product_default_columns`
     *
     * @param $columns
     * @return mixed
     */
    public function export_product_default_columns( $columns ) {
        $columns['lastudio_enable_360']  = __( 'LaStudio: Enable 360 Viewer', 'lastudio' );
        $columns['lastudio_swatch_type'] = __( 'LaStudio: Swatch Type', 'lastudio' );
        $columns['lastudio_swatch_data'] = __( 'LaStudio: Swatch Data', 'lastudio' );
        return $columns;
    }

    /**
     * This function will be override the hook `woocommerce_product_export_product_column_lastudio_enable_360`
     *
     * @param $meta_value
     * @param $product
     * @param $column_id
     * @return mixed
     */
    public function export_product_column_threesixty( $meta_value, $product, $column_id ) {
        $meta_value = $product->get_meta('_la_360_enable', true);
        return $meta_value;
    }

    /**
     * This function will be override the hook `woocommerce_product_export_product_column_lastudio_swatch_type`
     *
     * @param $meta_value
     * @param $product
     * @param $column_id
     * @return mixed
     */
    public function export_product_column_swatch_type( $meta_value, $product, $column_id ) {
        if( $product->is_type( 'variable' ) ) {
            $meta_value = $product->get_meta('_swatch_type', true);
        }
        return $meta_value;
    }

    /**
     * This function will be override the hook `woocommerce_product_export_product_column_lastudio_swatch_data`
     *
     * @param $meta_value
     * @param $product
     * @param $column_id
     * @return false|string
     */
    public function export_product_column_swatch_data( $meta_value, $product, $column_id ) {
        if( $product->is_type( 'variable' ) ) {
            $meta_value = $product->get_meta('_la_swatch_data', true);
            if(!empty($meta_value)){
                $all_images = array();
                $new_meta_value = array();
                foreach($meta_value as $key => $value){
                    if(!empty($value['attributes'])){
                        $attributes = $value['attributes'];
                        $new_attributes = array();
                        foreach($attributes as $k => $v){
                            if(!empty($v['type']) && $v['type'] == 'photo' && !empty($v['photo'])){
                                if( !isset( $all_images[ $v['photo'] ] ) ){
                                    $photo_url = wp_get_attachment_image_url($v['photo'], 'full');
                                    if($photo_url){
                                        $all_images[$v['photo']] = $photo_url;
                                    }
                                    else{
                                        $v['photo'] = 0;
                                    }
                                }
                            }
                            $new_attributes[$k] = $v;
                        }
                        $value['attributes'] = $new_attributes;
                    }
                    if(!empty($all_images)){
                        $new_meta_value['fetch_image'] = $all_images;
                    }
                    $new_meta_value[$key] = $value;
                }
                $meta_value = wp_json_encode($new_meta_value);
            }
        }
        return $meta_value;
    }

    /**
     * This function will be override the hook `woocommerce_csv_product_import_mapping_default_columns`
     *
     * @param $columns
     * @return array
     */
    public function import_mapping_default_columns( $columns ) {
        $columns = wp_parse_args(array(
            __( 'LaStudio: Enable 360 Viewer', 'lastudio' ) => 'lastudio_enable_360',
            __( 'LaStudio: Swatch Type', 'lastudio' ) => 'lastudio_swatch_type',
            __( 'LaStudio: Swatch Data', 'lastudio' ) => 'lastudio_swatch_data',
        ), $columns);
        return $columns;
    }

    /**
     * This function will be override the hook `woocommerce_csv_product_import_mapping_options`
     *
     * @param $options
     * @return mixed
     */
    public function import_mapping_options( $options) {
        $options['lastudio_enable_360']  = __( 'LaStudio: Enable 360 Viewer', 'lastudio' );
        $options['lastudio_swatch_type'] = __( 'LaStudio: Swatch Type', 'lastudio' );
        $options['lastudio_swatch_data'] = __( 'LaStudio: Swatch Data', 'lastudio' );
        return $options;
    }

    /**
     * This function will be override the hook `woocommerce_product_import_pre_insert_product_object`
     *
     * @param $product_obj
     * @param $data
     * @return mixed
     */
    public function import_pre_insert_product_object( $product_obj, $data ) {
        if(!empty($data['lastudio_enable_360'])){
            $product_obj->update_meta_data( '_la_360_enable', $data['lastudio_enable_360'] );
        }
        if(!empty($data['lastudio_swatch_type'])){
            $product_obj->update_meta_data( '_swatch_type', $data['lastudio_swatch_type'] );
        }
        if(!empty($data['lastudio_swatch_data'])){
            $new_image_ids = array();
            $lastudio_swatch_data = json_decode($data['lastudio_swatch_data'], true);
            if(!empty($lastudio_swatch_data['fetch_image'])){
                $fetch_image = $lastudio_swatch_data['fetch_image'];
                unset($lastudio_swatch_data['fetch_image']);
                foreach($fetch_image as $old_id => $image_url){
                    $new_image_ids[$old_id] = $this->get_attachment_id_from_url( $image_url, $product_obj->get_id() );
                }
            }

            if(!empty($lastudio_swatch_data)){
                if(!empty($new_image_ids)){
                    $new_meta_value = array();
                    foreach($lastudio_swatch_data as $key => $value){
                        if(!empty($value['attributes'])){
                            $attributes = $value['attributes'];
                            $new_attributes = array();
                            foreach($attributes as $k => $v){
                                if(!empty($v['type']) && $v['type'] == 'photo' && !empty($v['photo'])){
                                    if(isset($new_image_ids[$v['photo']])){
                                        $v['photo'] = $new_image_ids[$v['photo']];
                                    }
                                    else{
                                        $v['photo'] = 0;
                                    }
                                }
                                $new_attributes[$k] = $v;
                            }
                            $value['attributes'] = $new_attributes;
                        }
                        $new_meta_value[$key] = $value;
                    }

                    $product_obj->update_meta_data( '_la_swatch_data', $new_meta_value );
                }
                else{
                    $product_obj->update_meta_data( '_la_swatch_data', $lastudio_swatch_data );
                }
            }

        }
        return $product_obj;
    }

    private function get_attachment_id_from_url( $url, $product_id ){
        if ( empty( $url ) ) {
            return 0;
        }

        $id         = 0;
        $upload_dir = wp_upload_dir( null, false );
        $base_url   = $upload_dir['baseurl'] . '/';

        // Check first if attachment is inside the WordPress uploads directory, or we're given a filename only.
        if ( false !== strpos( $url, $base_url ) || false === strpos( $url, '://' ) ) {
            // Search for yyyy/mm/slug.extension or slug.extension - remove the base URL.
            $file = str_replace( $base_url, '', $url );
            $args = array(
                'post_type'   => 'attachment',
                'post_status' => 'any',
                'fields'      => 'ids',
                'meta_query'  => array( // @codingStandardsIgnoreLine.
                    'relation' => 'OR',
                    array(
                        'key'     => '_wp_attached_file',
                        'value'   => '^' . $file,
                        'compare' => 'REGEXP',
                    ),
                    array(
                        'key'     => '_wp_attached_file',
                        'value'   => '/' . $file,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => '_wc_attachment_source',
                        'value'   => '/' . $file,
                        'compare' => 'LIKE',
                    ),
                ),
            );
        } else {
            // This is an external URL, so compare to source.
            $args = array(
                'post_type'   => 'attachment',
                'post_status' => 'any',
                'fields'      => 'ids',
                'meta_query'  => array( // @codingStandardsIgnoreLine.
                    array(
                        'value' => $url,
                        'key'   => '_wc_attachment_source',
                    ),
                ),
            );
        }

        $ids = get_posts( $args ); // @codingStandardsIgnoreLine.

        if ( $ids ) {
            $id = current( $ids );
        }

        // Upload if attachment does not exists.
        if ( ! $id && stristr( $url, '://' ) ) {
            $upload = wc_rest_upload_image_from_url( $url );

            if ( is_wp_error( $upload ) ) {
                throw new Exception( $upload->get_error_message(), 400 );
            }

            $id = wc_rest_set_uploaded_image_as_attachment( $upload, $product_id );

            if ( ! wp_attachment_is_image( $id ) ) {
                /* translators: %s: image URL */
                throw new Exception( sprintf( __( 'Not able to attach "%s".', 'lastudio' ), $url ), 400 );
            }

            // Save attachment source for future reference.
            update_post_meta( $id, '_wc_attachment_source', $url );
        }

        if ( ! $id ) {
            /* translators: %s: image URL */
            throw new Exception( sprintf( __( 'Unable to use image "%s".', 'lastudio' ), $url ), 400 );
        }

        return $id;
    }
}