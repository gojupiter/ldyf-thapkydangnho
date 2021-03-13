<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

if(!class_exists('LaStudio_Swatch')){

    class LaStudio_Swatch{

        public function __construct(){

        }

        public function init_swatches_widget(){
            if ( class_exists( 'WC_Widget_Layered_Nav' ) ) {
                unregister_widget( 'WC_Widget_Layered_Nav' );
                require_once plugin_dir_path( __FILE__ ) . 'class-lastudio-swatch-widget.php';
                register_widget( 'LaStudio_Swatch_Widget' );
            }
        }

        public function admin_menu(){
            add_submenu_page(
                'edit.php?post_type=product',
                esc_html__('Swatches', 'lastudio'),
                esc_html__('Swatches', 'lastudio'),
                'manage_options',
                'la-swatches-page',
                array( $this, 'admin_menu_callback' )
            );
            add_action( 'admin_init', array( $this, 'register_field_settings' ) );
        }

        public function register_field_settings(){
            register_setting( 'la-studio-swatches-settings-group', 'la_swatches_configs' );
        }

        public function admin_menu_callback(){
            $la_swatches_configs = get_option('la_swatches_configs', array());
            $image_size = isset($la_swatches_configs['image_size']) ? $la_swatches_configs['image_size'] : array('width' => 40, 'height' => 40, 'crop' => 1 );
            $image_crop = isset($image_size['crop']) ? $image_size['crop'] : false;
            $attribute_in_list = !empty($la_swatches_configs['attribute_in_list']) ? $la_swatches_configs['attribute_in_list'] : array();
        ?>
        <div class="la-framework la-option-framework">
            <form method="post" action="options.php">
                <?php settings_fields( 'la-studio-swatches-settings-group' ); ?>
                <?php do_settings_sections( 'la-studio-swatches-settings-group' ); ?>
                <header class="la-header">
                    <h1>LA Studio Swatches Settings</h1>
                    <fieldset><?php submit_button(); ?></fieldset>
                    <div class="clear"></div>
                </header>
                <div class="la-body la-show-all">
                    <div class="la-content">
                        <div class="la-sections">
                            <div class="la-section" style="display:block;">
                                <div class="la-element la-field-spacing">
                                    <div class="la-title">
                                        <h4><?php esc_html_e('Swatches and Photos', 'lastudio' )?></h4>
                                    </div>
                                    <div class="la-fieldset">
                                        <div class="la-parent-fields">
                                            <fieldset>
                                                <div class="la-element la-element-no-title la-field-text la-pseudo-field">
                                                    <input type="text" size="3" name="la_swatches_configs[image_size][width]" value="<?php echo esc_attr($image_size['width']) ?>"/>
                                                </div>
                                                <span class="la-pseudo-field"> x </span>
                                                <div class="la-element la-element-no-title la-field-text la-pseudo-field">
                                                    <input type="text" size="3" name="la_swatches_configs[image_size][height]" value="<?php echo esc_attr($image_size['height']) ?>"/>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <p class="la-text-desc"><?php esc_html_e('The default size for color swatches and photos.', 'lastudio') ?></p>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <?php

                                    $attribute_array      = array();
                                    $attribute_taxonomies = wc_get_attribute_taxonomies();

                                    if ( ! empty( $attribute_taxonomies ) ) {
                                        foreach ( $attribute_taxonomies as $tax ) {
                                            if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
                                                $attribute_array[ $tax->attribute_name ] = $tax->attribute_label;
                                            }
                                        }
                                    }

                                    if(! empty($attribute_array) ) {

                                        echo la_fw_add_element(array(
                                            'id'         => 'attribute_in_list',
                                            'type'       => 'checkbox',
                                            'title'      => esc_html__('Swatch Attributes Visibility', 'lastudio'),
                                            'desc'       => esc_html__('Swatch attributes visibility on items from the product listing page', 'lastudio'),
                                            'options'    => $attribute_array,
                                        ), $attribute_in_list, 'la_swatches_configs');
                                    }
                                    else{
                                        echo la_fw_add_element(array(
                                            'type'    => 'notice',
                                            'class'   => 'warning',
                                            'content' => sprintf(__('Please %s add new attribute %s before to do this!','lastudio'), '<a href="'.admin_url('edit.php?post_type=product&page=product_attributes').'">','</a>'),
                                        ));
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <footer class="la-footer"><div class="la-block-left"></div><div class="la-block-right">Powered by <a href="https://themeforest.net/user/la-studio/portfolio?ref=LA-Studio">LA-Studio</a></div><div class="clear"></div></footer>
            </form>
        </div>
        <?php
        }

        public function admin_init(){
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if ( $attribute_taxonomies ) {
                foreach ( $attribute_taxonomies as $tax ) {

                    add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array($this, 'addFormFields') );
                    add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array($this, 'editFormFields'), 10, 2 );

                    add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array($this, 'addThumbToTable') );
                    add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array($this, 'showThumbToTable'), 10, 3 );
                }
            }
        }

        public function addFormFields($taxonomy){
            $unique_id = 'la_swatches';
            echo '<div class="la-framework la-taxonomy la-taxonomy-for-swatch la-taxonomy-add-fields"><div class="la-content-taxonomy"><div class="la-content">';
            echo la_fw_add_element(array(
                'id'             => 'type',
                'type'           => 'select',
                'title'          => esc_html__('Swatch Type', 'lastudio'),
                'default'        => 'none',
                'attributes'     => array(
                    'data-depend-id' => 'la_swatches_type'
                ),
                'options'        => array(
                    'none'          => esc_html__('None', 'lastudio'),
                    'color'         => esc_html__('Color Swatch', 'lastudio'),
                    'photo'         => esc_html__('Photo', 'lastudio'),
                )
            ), '', $unique_id);
            echo la_fw_add_element(array(
                'id'    => 'color',
                'type'  => 'color_picker',
                'title' => esc_html__('Color', 'lastudio'),
                'rgba'  => false,
                'dependency' => array('la_swatches_type', '==' , 'color')
            ), '', $unique_id);
            echo la_fw_add_element(array(
                'id'    => 'photo',
                'type'  => 'image',
                'title' => esc_html__('Photo', 'lastudio'),
                'dependency' => array('la_swatches_type', '==' , 'photo')
            ), '', $unique_id);
            echo '</div></div></div>';
        }

        public function editFormFields($term, $taxonomy){
            $unique_id = 'la_swatches';
            $data = get_term_meta($term->term_id, 'la_swatches', true);
            $type = 'none';
            $color = $photo = '';
            if(isset($data['type'])){
                $type = $data['type'];
            }
            if(isset($data['color'])){
                $color = $data['color'];
            }
            if(isset($data['photo'])){
                $photo = $data['photo'];
            }

            echo '<tr><td colspan="2" style="padding: 0"><div class="la-framework la-taxonomy-for-swatch la-taxonomy la-taxonomy-edit-fields"><div class="la-content-taxonomy"><div class="la-content">';
            echo la_fw_add_element(array(
                'id'             => 'type',
                'type'           => 'select',
                'title'          => esc_html__('Swatch Type', 'lastudio'),
                'default'        => 'none',
                'attributes'     => array(
                    'data-depend-id' => 'la_swatches_type'
                ),
                'options'        => array(
                    'none'          => esc_html__('None', 'lastudio'),
                    'color'         => esc_html__('Color Swatch', 'lastudio'),
                    'photo'         => esc_html__('Photo', 'lastudio'),
                )
            ), $type, $unique_id);
            echo la_fw_add_element(array(
                'id'    => 'color',
                'type'  => 'color_picker',
                'title' => esc_html__('Color', 'lastudio'),
                'rgba'  => false,
                'dependency' => array('la_swatches_type', '==' , 'color')
            ), $color, $unique_id);
            echo la_fw_add_element(array(
                'id'    => 'photo',
                'type'  => 'image',
                'title' => esc_html__('Photo', 'lastudio'),
                'dependency' => array('la_swatches_type', '==' , 'photo')
            ), $photo, $unique_id);
            echo '</div></div></div></td></tr>';
        }

        public function saveTermMeta($term_id, $tt_id, $taxonomy){
            if ( isset( $_POST['la_swatches'] ) ) {
                $data = $_POST['la_swatches'];
                update_term_meta( $term_id, 'la_swatches', $data );
            }
        }

        //Registers a column for this attribute taxonomy for this image
        public function addThumbToTable( $columns ) {
            $new_columns = array();
            if(isset($columns['cb'])){
                $new_columns['cb'] = $columns['cb'];
                $new_columns['la_swatches'] = __( 'Thumbnail', 'lastudio' );
                unset( $columns['cb'] );
                $columns = array_merge( $new_columns, $columns );
            }
            return $columns;
        }

        public function showThumbToTable( $columns, $column, $id ) {
            if ( $column == 'la_swatches' ) :
                $data = get_term_meta($id, 'la_swatches', true);
                $output = '';
                if(isset($data['type'])){
                    $type = $data['type'];

                    if($type == 'color'){
                        if(isset($data['color'])){
                            $output .= '<span class="type-color" style="background-color:'. $data['color'] .'"></span>';
                        }else{
                            $output .= '<span class="type-none">'.esc_html__('Not Set', 'lastudio').'</span>';
                        }
                    }elseif($type == 'photo'){
                        if(isset($data['photo']) && wp_attachment_is_image($data['photo'])){
                            $output .= wp_get_attachment_image( $data['photo'], 'la_swatches_image_size' );
                        }else{
                            $output .= '<span class="type-none">'.__('Not Set', 'lastudio').'</span>';
                        }
                    }else{
                        $output .= '<span class="type-none">'.__('None', 'lastudio').'</span>';
                    }

                }else{
                    $output .= '<span class="type-none">'.__('None', 'lastudio').'</span>';
                }
                $columns .= $output;
            endif;
            return $columns;
        }

        public function product_write_panel_tabs() {
            ?>
            <li class="tab_la_swatches show_if_variable"><a href="#panel_la_swatches"><span><?php esc_html_e('Swatches', 'lastudio') ?></span></a></li>
            <?php
        }

        public function product_data_panel_wrap() {
            ?>
            <div id="panel_la_swatches" class="panel tab_la_swatches woocommerce_options_panel wc-metaboxes-wrapper" style="display: none;">
                <?php $this->render_product_tab_content(); ?>
            </div>
            <?php
        }

        public function render_product_tab_content( ) {
            global $post;
            $post_id = $post->ID;
            $variation_attribute_found = true;

            $unique_id = 'la_swatch_data';

            $default_type = array(
                'none'              => esc_html__('None', 'lastudio'),
                'term_options'      => esc_html__('Taxonomy Colors and Images', 'lastudio'),
                'product_custom'    => esc_html__('Custom Colors and Images', 'lastudio'),
                'radio'             => esc_html__('Radio Button', 'lastudio')
            );
            $default_layout = array(
                'default'       => esc_html__('Default', 'lastudio'),
                'only_label'    => esc_html__('Show Only Label', 'lastudio')
            );

            $default_style = array(
                'default' => esc_html__('Default', 'lastudio'),
                'circle' => esc_html__('Circle', 'lastudio'),
                'square' => esc_html__('Square', 'lastudio'),
                'rounder' => esc_html__('Rounder', 'lastudio')
            );

            $default_sub_type = array(
                'color'     => esc_html__('Color', 'lastudio'),
                'photo'     => esc_html__('Image', 'lastudio')
            );

            $product = wc_get_product($post_id);

            $product_type_array = array('variable', 'variable-subscription');

            if ( !in_array( $product->get_type(), $product_type_array ) ) {
                $variation_attribute_found = false;
            }

            $attributes_name = wp_list_pluck( wc_get_attribute_taxonomies(),'attribute_label' ,'attribute_name' );
            $product_swatches_data = $product->get_meta('_la_swatch_data', true);

            if(!$product_swatches_data){
                $product_swatches_data = array();
            }

            ?>
            <div id="panel_la_swatches_inner" class="options_group la-content">
                <?php if ( ! $variation_attribute_found ) : ?>
                    <div id="message" class="inline notice woocommerce-message">
                        <p><?php _e( 'Before you can add a swatches you need to add some variation attributes on the <strong>Attributes</strong> tab.', 'lastudio' ); ?></p>
                        <p>
                            <a class="button-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_docs_url', 'https://docs.woocommerce.com/document/variable-product/', 'product-variations' ) ); ?>" target="_blank"><?php _e( 'Learn more', 'lastudio' ); ?></a>
                        </p>
                    </div>

                <?php else : ?>
                    <div class="fields_header">
                        <table class="widefat">
                            <thead>
                                <th class="attribute_swatch_label">
                                    <?php esc_html_e( 'Product Attribute Name', 'lastudio' ); ?>
                                </th>
                                <th class="attribute_swatch_type">
                                    <?php esc_html_e( 'Attribute Control Type', 'lastudio' ); ?>
                                </th>
                            </thead>
                        </table>
                    </div>
                    <div class="fields">
                        <?php
                        $attributes = $product->get_variation_attributes();
                        if(!empty($attributes)){
                            foreach ( $attributes as $taxonomy_key => $attribute_list ){
                                if(empty($attribute_list)){
                                    continue;
                                }
                                $attribute_terms = array();
                                $current_is_tax = taxonomy_exists($taxonomy_key);
                                $key_attr = md5( str_replace( '-', '_', sanitize_title( $taxonomy_key ) ) );
                                $tax_title = $taxonomy_key;
                                if($current_is_tax){
                                    $tax_title = $attributes_name[str_replace('pa_', '', $taxonomy_key)];
                                    $terms = get_terms( $taxonomy_key, array('hide_empty' => false) );
                                    foreach( $terms as $term ){
                                        if ( in_array( $term->slug, $attribute_list ) ) {
                                            $attribute_terms[] = array('id' => md5( $term->slug ), 'label' => $term->name, 'old_id' => $term->slug);
                                        }
                                    }
                                }else{
                                    foreach ( $attribute_list as $term ) {
                                        $attribute_terms[] = array('id' => ( md5( sanitize_title( strtolower( $term ) ) ) ), 'label' => esc_html( $term ), 'old_id' => esc_attr( sanitize_title( $term ) ));
                                    }
                                }
                                if(empty($attribute_terms)){
                                    continue;
                                }

                                $parent_type = 'none';
                                $parent_layout = 'default';
                                $parent_size = 'la_swatches_image_size';
                                $parent_style = 'default';
                                if(!empty($product_swatches_data[$key_attr]['size'])){
                                    $parent_size = $product_swatches_data[$key_attr]['size'];
                                }
                                if(!empty($product_swatches_data[$key_attr]['type'])){
                                    $parent_type = $product_swatches_data[$key_attr]['type'];
                                }
                                if(!empty($product_swatches_data[$key_attr]['layout'])){
                                    $parent_layout = $product_swatches_data[$key_attr]['layout'];
                                }
                                if(!empty($product_swatches_data[$key_attr]['style'])){
                                    $parent_style = $product_swatches_data[$key_attr]['style'];
                                }
                                if($current_is_tax){
                                    $default_parent_type = $default_type;
                                }else{
                                    $default_parent_type = $default_type;
                                    unset($default_parent_type['term_options']);
                                }
                                ?>
                                <div class="field">
                                    <div class="la_swatch_field_meta">
                                        <table class="widefat">
                                            <tbody>
                                            <tr>
                                                <td class="attribute_swatch_label">
                                                    <strong><a class="la_swatch_field row-title" href="javascript:;"><?php echo esc_html($tax_title) ?></a></strong>
                                                </td>
                                                <td class="attribute_swatch_type"><?php echo $default_parent_type[$parent_type]; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="la_swatch_field_form_mask">
                                        <div class="field_form">
                                            <?php

                                            echo la_fw_add_element(array(
                                                'id'             => $key_attr . '_type',
                                                'type'           => 'select',
                                                'name'           => $unique_id . '[' . $key_attr . ']' . '[type]',
                                                'title'          => esc_html__('Type', 'lastudio'),
                                                'default'        => 'none',
                                                'class'          => 'la-parent-type-class',
                                                'options'        => $default_parent_type
                                            ), $parent_type);
                                            echo la_fw_add_element(array(
                                                'id'             => $key_attr . '_size',
                                                'type'           => 'select',
                                                'name'           => $unique_id . '[' . $key_attr . ']' . '[size]',
                                                'title'          => esc_html__('Swatches and Photos Size', 'lastudio'),
                                                'default'        => 'la_swatches_image_size',
                                                'options'        => array(
                                                    'thumbnail'                 => esc_html__('Thumbnail', 'lastudio'),
                                                    'medium'                    => esc_html__('Medium', 'lastudio'),
                                                    'shop_thumbnail'            => esc_html__('Product thumbnails', 'lastudio'),
                                                    'shop_catalog'              => esc_html__('Catalog images', 'lastudio'),
                                                    'la_swatches_image_size'    => esc_html__('Swatches Image Size', 'lastudio')
                                                ),
                                                'dependency' => array( $key_attr . '_type', 'any' , 'term_options,product_custom')
                                            ), $parent_size);
                                            echo la_fw_add_element(array(
                                                'id'             => $key_attr . '_layout',
                                                'type'           => 'select',
                                                'name'           => $unique_id . '[' . $key_attr . ']' . '[layout]',
                                                'title'          => esc_html__('Layout', 'lastudio'),
                                                'default'        => 'default',
                                                'options'        => $default_layout,
                                                'dependency' => array( $key_attr . '_type', 'any' , 'term_options,product_custom')
                                            ), $parent_layout);
                                            echo la_fw_add_element(array(
                                                'id'             => $key_attr . '_style',
                                                'type'           => 'select',
                                                'name'           => $unique_id . '[' . $key_attr . ']' . '[style]',
                                                'title'          => esc_html__('Style', 'lastudio'),
                                                'default'        => 'circle',
                                                'options'        => $default_style,
                                                'dependency' => array( $key_attr . '_type', 'any' , 'term_options,product_custom')
                                            ), $parent_style);
                                            ?>
                                            <div class="la-element la-field-fieldset" data-controller="<?php echo esc_attr($key_attr) ?>_type" data-condition="==" data-value="product_custom">
                                                <div class="la-title"><h4><?php esc_html_e('Attribute Configuration', 'lastudio') ?></h4></div>
                                                <div class="la-fieldset">
                                                    <div class="la-inner">
                                                        <div class="product_custom">
                                                            <div class="fields_header">
                                                                <table class="widefat">
                                                                    <thead>
                                                                        <th class="attribute_swatch_preview">
                                                                            <?php esc_html_e( 'Preview', 'lastudio' ); ?>
                                                                        </th>
                                                                        <th class="attribute_swatch_label">
                                                                            <?php esc_html_e( 'Attribute', 'lastudio' ); ?>
                                                                        </th>
                                                                        <th class="attribute_swatch_type">
                                                                            <?php esc_html_e( 'Type', 'lastudio' ); ?>
                                                                        </th>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                            <div class="fields">
                                                                <?php foreach($attribute_terms as $attribute_term): ?>
                                                                    <?php
                                                                    $key_sub_attr = $attribute_term['id'];
                                                                    $current_attribute_type = 'color';
                                                                    $current_attribute_color =  '#fff';
                                                                    $current_attribute_photo =  0;
                                                                    if(!empty($product_swatches_data[$key_attr]['attributes'][$key_sub_attr]['type'])){
                                                                        $current_attribute_type = $product_swatches_data[$key_attr]['attributes'][$key_sub_attr]['type'];
                                                                    }
                                                                    if(!empty($product_swatches_data[$key_attr]['attributes'][$key_sub_attr]['color'])){
                                                                        $current_attribute_color = $product_swatches_data[$key_attr]['attributes'][$key_sub_attr]['color'];
                                                                    }
                                                                    if(!empty($product_swatches_data[$key_attr]['attributes'][$key_sub_attr]['photo'])){
                                                                        $current_attribute_photo = $product_swatches_data[$key_attr]['attributes'][$key_sub_attr]['photo'];
                                                                    }
                                                                    ?>
                                                                    <div class="sub_field field">
                                                                        <div class="la_swatch_field_meta">
                                                                            <table class="widefat">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="attribute_swatch_preview">
                                                                                        <span class="attr-prev-type-color"<?php if($current_attribute_type == 'photo') echo ' style="display:none"' ?> style="background-color:<?php echo $current_attribute_color?>"></span>
                                                                                        <span class="attr-prev-type-image"<?php if($current_attribute_type == 'color') echo ' style="display:none"' ?>>
                                                                                            <?php
                                                                                            if($thumb_url = wp_get_attachment_image_url($current_attribute_photo, $parent_size)){
                                                                                                printf('<img src="%s" alt=""/>', $thumb_url);
                                                                                            }else{
                                                                                                echo '<span></span>';
                                                                                            }
                                                                                            ?>
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="attribute_swatch_label"><?php echo $attribute_term['label']; ?></td>
                                                                                    <td class="attribute_swatch_type">
                                                                                        <?php echo $default_sub_type[$current_attribute_type]; ?>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="la_swatch_field_form_mask">
                                                                            <div class="field_form">
                                                                                <?php
                                                                                echo la_fw_add_element(array(
                                                                                    'id'             => $key_attr . '_attributes_' . $key_sub_attr . '_type',
                                                                                    'type'           => 'select',
                                                                                    'name'           => $unique_id . '[' . $key_attr . ']' . '[attributes]['. $key_sub_attr . '][type]',
                                                                                    'title'          => esc_html__('Attribute Color or Image', 'lastudio'),
                                                                                    'default'        => 'color',
                                                                                    'options'        => $default_sub_type
                                                                                ), $current_attribute_type);
                                                                                echo la_fw_add_element(array(
                                                                                    'id'            => $key_attr . '_attributes_' . $key_sub_attr . '_color',
                                                                                    'type'          => 'color_picker',
                                                                                    'name'          => $unique_id . '[' . $key_attr . ']' . '[attributes]['. $key_sub_attr . '][color]',
                                                                                    'title'         => esc_html__('Color', 'lastudio'),
                                                                                    'rgba'          => false,
                                                                                    'dependency'    => array($key_attr . '_attributes_' . $key_sub_attr . '_type', '==' , 'color')
                                                                                ), $current_attribute_color);
                                                                                echo la_fw_add_element(array(
                                                                                    'id'            => $key_attr . '_attributes_' . $key_sub_attr . '_photo',
                                                                                    'type'          => 'image',
                                                                                    'name'          => $unique_id . '[' . $key_attr . ']' . '[attributes]['. $key_sub_attr . '][photo]',
                                                                                    'title'         => esc_html__('Photo', 'lastudio'),
                                                                                    'dependency'    => array($key_attr . '_attributes_' . $key_sub_attr . '_type', '==' , 'photo')
                                                                                ), $current_attribute_photo);
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }

        public function ajax_render_swatches_panel(){
            die();
        }

        public function process_meta_box( $post_id, $post ) {

            $product = wc_get_product($post_id);

            $swatch_type_options = isset( $_POST['la_swatch_data'] ) ? $_POST['la_swatch_data'] : false;
            $swatch_type = 'default';

            if ( $swatch_type_options && is_array( $swatch_type_options ) ) {
                foreach ( $swatch_type_options as $options ) {
                    if ( isset( $options['type'] ) && $options['type'] != 'default' && $options['type'] != 'radio' ) {
                        $swatch_type = 'pickers';
                        break;
                    }
                }

                $product->update_meta_data('_la_swatch_data', $swatch_type_options );
            }

            $product->update_meta_data('_swatch_type', $swatch_type );
            $product->save_meta_data();

        }

        public function get_product_variations() {
            if ( !isset( $_POST['product_id'] ) || empty( $_POST['product_id'] ) ) {
                wp_send_json_error();
                die();
            }

            $product = wc_get_product( $_POST['product_id'] );
            $variations = $this->get_available_variations( $product );

            wp_send_json_success( $variations );
            die();
        }


        /**
         * Get an array of available variations for the a product.
         * Use this function as it's faster than the core implementation.
         * @return array
         */
        private function get_available_variations( $product ) {

            $transient_name = 'la_swatches_av_' . $product->get_id();
            $transient_data = get_transient($transient_name);
            if (!empty($transient_data)){
                return $transient_data;
            }

            $available_variations = array();

            //Get the children all in one call.
            //This will prime the WP_Post cache so calls to get_child are much faster.

            $args = array(
                'post_parent' => $product->get_id(),
                'post_type' => 'product_variation',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
                'numberposts' => -1,
                'no_found_rows' => true
            );
            $children = get_posts( $args );
            if(empty($children)){
                return $available_variations;
            }

            foreach ( $children as $child ) {
                $variation = wc_get_product( $child );

                $variation_id = $variation->get_id();
                $variation_is_in_stock = $variation->is_in_stock();

                // Hide out of stock variations if 'Hide out of stock items from the catalog' is checked
                if ( empty( $variation_id ) || ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && !$variation_is_in_stock ) ) {
                    continue;
                }

                // Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price)
                if ( apply_filters( 'woocommerce_hide_invisible_variations', false, $product->get_id(), $variation ) && !$variation->variation_is_visible() ) {
                    continue;
                }

                $available_variations[] = array(
                    'variation_id' => $variation_id,
                    'variation_is_active' => $variation->variation_is_active(),
                    'attributes' => $variation->get_variation_attributes(),
                );
            }
            set_transient( $transient_name, $available_variations, MONTH_IN_SECONDS * 1 );
            return $available_variations;
        }

        public function on_deleted_transient( $product_id ) {
            delete_transient( 'la_swatches_av_' . $product_id );
        }


        private function get_product_images_by_variations( $product_variations ){
            $array = array();
            if ( !empty( $product_variations ) ) {
                foreach($product_variations as $product_variation) {
                    if($product_variation['variation_is_active'] && $product_variation['variation_is_visible']){
                        $array[$product_variation['variation_id']] = array(
                            'image_id' => $product_variation['image_id'],
                            'attributes' => $product_variation['attributes']
                        );
                    }
                }
            }
            return $array;
        }

        private function get_product_variation_image_url_by_attribute($product_variations, $attribute_name, $attribute_value ){

            $attribute_name = 'attribute_' . $attribute_name;
            $image_url = '';
            $image_id = false;
            $_tmp = $this->get_product_images_by_variations($product_variations);
            if(!empty($_tmp)){
                foreach($_tmp as $p_id => $val ){
                    if(isset($val['attributes'][$attribute_name]) && $val['attributes'][$attribute_name] == $attribute_value){
                        $image_id = $val['image_id'];
                        break;
                    }
                }
            }
            if($image_id){
                return apply_filters('LaStudio/swatches/get_product_variation_image_url_by_attribute', wp_get_attachment_image_url($image_id, 'shop_catalog'), $image_id);
                //return wp_get_attachment_image_url($image_id, apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' ));
            }
            return $image_url;
        }

        public function _render_option_by_attribute( $product, $attr_selected = array() ){
            $html = '';
            if( $product->get_type() != 'variable' || empty($attr_selected) ) {
                return $html;
            }

            $get_variations = sizeof( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

            $available_variations = $get_variations ? $product->get_available_variations() : false;

            if($available_variations){

                $attributes = $product->get_variation_attributes();

                foreach ( $attributes as $attribute_name => $options ) {
                    $_tmp = str_replace('pa_', '', $attribute_name);
                    if(!in_array( $_tmp, $attr_selected)){
                        continue;
                    }
                    $swatch_config_object = new LaStudio_Swatch_Attribute_Configuration_Object( $product, $attribute_name );
                    if($swatch_config_object->get_swatch_type() != 'none' && $swatch_config_object->get_swatch_type() != 'default'){
                        if ( !empty( $options ) ) {
                            if ( $product && taxonomy_exists( $attribute_name ) ) {
                                // Get terms if this is a taxonomy - ordered. We need the names too.
                                $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array('fields' => 'all') );

                                $html .= '<div class="la-swatch-control">';
                                foreach ( $terms as $term ) {
                                    if ( in_array( $term->slug, $options ) ) {
                                        $attribute_link = add_query_arg(
                                            'attribute_' . sanitize_title($attribute_name),
                                            $term->slug,
                                            $product->get_permalink()
                                        );

                                        $image_url = $this->get_product_variation_image_url_by_attribute($available_variations, $attribute_name, $term->slug);
                                        if ( $swatch_config_object->get_swatch_type() == 'term_options' ) {
                                            $swatch_term = new LaStudio_Swatch_Term( $swatch_config_object, $term->term_id, $attribute_name, false, $swatch_config_object->get_swatch_display_size() );
                                            $html .= $swatch_term->get_output($attribute_link, $image_url);
                                        } elseif ( $swatch_config_object->get_swatch_type() == 'product_custom' ) {
                                            $swatch_term = new LaStudio_Swatch_Product_Term( $swatch_config_object, $term->term_id, $attribute_name, false );
                                            $html .= $swatch_term->get_output($attribute_link, $image_url);
                                        }
                                    }
                                }
                                $html .= '</div>';
                            }
                        }
                    }
                }

            }

            return $html;
        }

        public function render_attribute_in_product_list_loop( $product ){
            $la_swatches_configs = get_option('la_swatches_configs', array());
            $attribute_allow = !empty($la_swatches_configs['attribute_in_list']) ? $la_swatches_configs['attribute_in_list'] : array();
            if(!empty($attribute_allow)){
                add_filter('LaStudio/swatches/configuration_object/get_swatch_display_style', array( $this, 'override_swatch_style_in_list'), 10, 2);
                echo $this->_render_option_by_attribute( $product, $attribute_allow );
                remove_filter('LaStudio/swatches/configuration_object/get_swatch_display_style', array( $this, 'override_swatch_style_in_list'), 10);
            }
        }

        public function override_swatch_style_in_list( $style, $instance ){
            return 'inlist';
        }

        /**
         * Helper: WPML - Get original variation ID
         *
         * If WPML is active and this is a translated variaition, get the original ID.
         *
         * @param int $id
         */
        public function wpml_get_original_variation_id( $id ) {

            $wpml_original_variation_id = get_post_meta( $id, '_wcml_duplicate_of_variation', true );

            if( $wpml_original_variation_id )
                $id = $wpml_original_variation_id;

            return $id;

        }

        /**
         * Helper: Get translated media ID
         *
         * @param int $media_file_id
         * @return bool|int
         */
        public function get_translated_media_id( $media_file_id ) {
            if( !function_exists('icl_object_id') )
                return $media_file_id;

            $media_file_id = icl_object_id( $media_file_id, 'attachment', true );

            return $media_file_id;

        }

        /**
         * Helper: Get all images transient name for specific variation/product
         *
         * @param int $id
         * @param string $type
         * @return string
         */
        public function get_gallery_transient_name( $id, $type ) {
            if( $type === "all-images" ) {
                $id = $this->wpml_get_original_variation_id( $id );
                $transient_name = sprintf("transient_la-swatches_variation_image_ids_%d", $id);
            } elseif( $type === "dvi" ) {
                $transient_name = sprintf('transient_la-swatches_dvi_%d', $id);
            } elseif ( $type === "sizes" ) {
                $transient_name = sprintf("transient_la-swatches_variation_image_sizes_%d", $id);
            } elseif ( $type === "variation" ) {
                $transient_name = sprintf("transient_la-swatches_variation_%d", $id);
            } else {
                $transient_name = false;
            }
            return apply_filters( 'LaStudio/swatches_gallery/get_gallery_transient_name', $transient_name, $type, $id);
        }

        public function flush_variation_gallery_cache( $force = false, $product_id = false ){
            if( isset( $_POST['la-swatches-delete-image-cache'] ) || $force === true ) {
                global $wpdb;
                $transients = $wpdb->get_results(
                    $wpdb->prepare("
                      SELECT * FROM $wpdb->options
                      WHERE `option_name` LIKE '%s'
                    ",
                        '%transient_la-swatches_%'
                    )
                );

                if( $transients ) {
                    foreach( $transients as $transient ) {
                        $transient_name = str_replace(array('_transient_timeout_transient_la-swatches_', '_transient_transient_la-swatches_'), 'transient_la-swatches_', $transient->option_name);
                        delete_transient( $transient_name );
                    }
                }
            }
            if( $product_id ) {
                $transient_keys = array("all-images", "dvi", "sizes", "variation");
                foreach ($transient_keys as $n){
                    $ts_name = $this->get_gallery_transient_name($product_id, $n);
                    delete_transient( $ts_name );
                }
            }
        }

        public function flush_all_gallery_cache(){
            if(isset($_GET['la-clear-swatches-cache'])){
                $this->flush_variation_gallery_cache(true, false);
                $current_url = add_query_arg(null, null);
                $current_url = remove_query_arg('la-clear-swatches-cache', $current_url);
                wp_safe_redirect($current_url);
                exit;
            }
        }

        public function admin_load_variation_gallery(){
            if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'swatches_nonce' ) ) {
                die ( 'Invalid Nonce' );
            }

            $variation_id = isset($_REQUEST['variation_id']) ? $_REQUEST['variation_id'] : 0;

            $attachments = get_post_meta($variation_id, '_product_image_gallery', true);
            $attachmentsExp = array_filter(explode(',', $attachments));
            $image_ids = array();
            ?>
            <ul class="la_variation_thumbs">
                <?php if (!empty($attachmentsExp)) { ?>
                    <?php foreach ($attachmentsExp as $id) { $image_ids[] = $id; ?>
                        <li class="image" data-attachment_id="<?php echo $id; ?>">
                            <a href="#" class="delete" title="Delete image"><span style="background-image: url(<?php echo esc_url(wp_get_attachment_image_url($id, 'shop_thumbnail')) ?>)"></span></a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
            <input type="hidden" class="la_variation_image_gallery" name="la_variation_image_gallery[<?php echo $variation_id; ?>]" value="<?php echo $attachments; ?>">
            <?php
            exit;
        }

        public function save_gallery_for_product_variation( $variation_id, $i ){
            $this->flush_variation_gallery_cache( false, $variation_id );
            if ( isset( $_POST['la_variation_image_gallery'][$variation_id] ) ) {
                update_post_meta($variation_id, '_product_image_gallery', $_POST['la_variation_image_gallery'][$variation_id]);
            }
        }

        /*
        *
        * Helper: Get all image IDs for a specific variation
        *
        * @param int $id
        *
        */
        public function get_all_variation_image_ids( $id ) {

            $transient_name = $this->get_gallery_transient_name( $id, "all-images" );
            $all_images = get_transient($transient_name);

            if ( empty($all_images) ) {
                $all_images = array();
                $show_gallery = false;
                $has_featured_image = has_post_thumbnail( $id );
                $product = wc_get_product($id);
                $post_type = $product->get_type();
                $parent_id = $product->get_parent_id( $product );

                // Main Image
                if ( $has_featured_image ) {
                    $all_images['featured'] = get_post_thumbnail_id( $id );
                }
                else {
                    if( $parent_id > 0 ) {
                        if ( has_post_thumbnail( $parent_id ) ) {
                            $all_images['featured'] = get_post_thumbnail_id( $parent_id );
                        }
                        else {
                            $all_images[] = 'placeholder';
                        }
                        $show_gallery = true;
                    }
                    else {
                        $all_images[] = 'placeholder';
                    }

                }

                // Gallery Attachments

                if ( $post_type == 'variation' ) {

                    $wt_attachments = $product->get_gallery_image_ids();

                    if( !empty( $wt_attachments ) ) {

                        $all_images = array_merge($all_images, $wt_attachments);

                        // if there was no featured image, set the first
                        // woothumbs attachment as the featured image

                        if( !$has_featured_image ) {
                            $all_images['featured'] = $all_images[0];
                            unset( $all_images[0] );
                            $show_gallery = false;
                        }

                    }
                    else{
                        $show_gallery = apply_filters('LaStudio/swatches_gallery/get_variation_gallery_from_parent_if_missing', true);
                    }

                }

                // Gallery Attachments
                if ( $post_type == 'product' || $show_gallery ) {

                    $id = !empty( $parent_id ) ? $parent_id : $id;
                    $gallery_product = wc_get_product( $id );

                    $attach_ids = $gallery_product->get_gallery_image_ids();

                    if ( !empty( $attach_ids ) ) {
                        $all_images = array_merge($all_images, $attach_ids);
                    }

                }

                $all_images = array_map( array($this, 'get_translated_media_id'), $all_images );

                $all_images = apply_filters( 'LaStudio/swatches_gallery/get_variation_image_ids_before_transient', $all_images, $id );

                set_transient( $transient_name, $all_images, 1 * MONTH_IN_SECONDS );

            }

            return apply_filters( 'LaStudio/swatches_gallery/get_variation_image_ids', $all_images, $id );

        }

        /*
         *
         * Helper: Get all image sizes
         *
         * @param int $variation_id
         *
         */
        public function get_all_variation_image_sizes( $variation_id ) {

            $image_ids = $this->get_all_variation_image_ids( absint($variation_id) );
            $images = array();

            if ( !empty($image_ids) ) {
                foreach ($image_ids as $image_id):

                    $transient_name = $this->get_gallery_transient_name( $image_id, "sizes" );
                    $image_sizes = get_transient( $transient_name );
                    if ( empty($image_sizes) ) {
                        $image_sizes = false;
                        if ($image_id == 'placeholder') {
                            $image_sizes = array(
                                'large' => array( wc_placeholder_img_src() ),
                                'single' => array( wc_placeholder_img_src() ),
                                'thumb' => array( wc_placeholder_img_src() ),
                                'alt' => '',
                                'title' => ''
                            );
                        } else {

                            if (!array_key_exists($image_id, $images)) {
                                $large = wp_get_attachment_image_src( $image_id, 'full' );
                                $single = wp_get_attachment_image_src( $image_id, 'woocommerce_single' );
                                $thumb = wp_get_attachment_image_src( $image_id, 'woocommerce_gallery_thumbnail' );

                                $srcset = wp_get_attachment_image_srcset( $image_id, 'woocommerce_single' );
                                $sizes = wp_get_attachment_image_sizes( $image_id, 'woocommerce_single' );
                                $thumb_srcset = wp_get_attachment_image_srcset( $image_id, 'woocommerce_gallery_thumbnail' );
                                $thumb_sizes = wp_get_attachment_image_sizes( $image_id, 'woocommerce_gallery_thumbnail' );

                                $image_sizes = array(
                                    'large' => $large,
                                    'single' => $single,
                                    'thumb' => $thumb,
                                    'alt' => get_post_field( 'post_title', $image_id ),
                                    'title' => get_post_field( 'post_title', $image_id ),
                                    'caption' => get_post_field( 'post_excerpt', $image_id ),
                                    'srcset' => $srcset ? $srcset : "",
                                    'sizes' => $sizes ? $sizes : "",
                                    'thumb_srcset' => $thumb_srcset ? $thumb_srcset : "",
                                    'thumb_sizes' => $thumb_sizes ? $thumb_sizes : "",
                                    'videolink' => esc_attr(get_post_meta( $image_id, 'videolink', true))
                                );
                            }
                        }
                        $image_sizes = apply_filters( 'LaStudio/swatches_gallery/get_variation_image_sizes_before_transient', $image_sizes );
                        set_transient( $transient_name, $image_sizes, 1 * MONTH_IN_SECONDS );
                    }

                    if( $image_sizes )
                        $images[$image_id] = $image_sizes;

                endforeach;
            }

            return apply_filters( 'LaStudio/swatches_gallery/get_variation_image_sizes', $images );

        }

        public function add_additional_into_variation_json( $variation_data, $wc_product_variable, $variation_obj ) {
            $images = $this->get_all_variation_image_sizes( $variation_data['variation_id'] );
            $variation_data['la_additional_images'] = array_values($images);
            return $variation_data;
        }

        public function override_output_dropdown_variation_attribute_options( $html, $args ){

            $new_html = '';

            $args = wp_parse_args( $args, array(
                'options'           => false,
                'attribute'         => false,
                'product'           => false,
                'selected'          => false,
                'name'              => '',
                'id'                => '',
                'class'             => '',
                'show_option_none'  => apply_filters('LaStudio/swatches/args/show_option_none', __( 'Choose an option', 'la-studio' ))
            ) );

            // Get selected value.
            if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
                $selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
                $args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( urldecode( wp_unslash( $_REQUEST[ $selected_key ] ) ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
            }

            $options            = $args['options'];
            $product            = $args['product'];
            $attribute          = $args['attribute'];
            $name               = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
            $id                 = $args['id'] ? $args['id'] : sanitize_title( $attribute );

            $swatch_config_object = new LaStudio_Swatch_Attribute_Configuration_Object( $product, $attribute );

            if ( $swatch_config_object->get_swatch_type() == 'radio' ) {
                do_action('la_swatches_before_picker', $swatch_config_object);
                $new_html .= '<div id="picker_' . esc_attr($id) . '" class="radio-select select swatch-control">';
                $args['hide'] = true;
                $new_html .= $this->render_dropdown_variation($args);
                $new_html .= $this->render_radio_variation($args);
                $new_html .= '</div>';
            }

            elseif ( $swatch_config_object->get_swatch_type() != 'none' && $swatch_config_object->get_swatch_type() != 'default' ) {

                $new_html .= '<div class="attribute_' . $id . '_picker_label swatch-label"></div>';

                if ($swatch_config_object->get_swatch_label_display_style() == 'label_above') {
                    $new_html .= '<div class="attribute_' . $id . '_picker_label swatch-label"></div>';
                }

                do_action('la_swatches_before_picker', $swatch_config_object);

                $new_html .= '<div id="picker_' . esc_attr($id) . '" class="select swatch-control">';
                $args['hide'] = true;
                $new_html .= $this->render_dropdown_variation($args);

                if (!empty($options)) {

                    if ($product && taxonomy_exists($attribute)) {
                        // Get terms if this is a taxonomy - ordered. We need the names too.
                        $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));

                        foreach ($terms as $term) {
                            if (in_array($term->slug, $options)) {
                                switch($swatch_config_object->get_swatch_type()){
                                    case 'term_options':
                                        $swatch_term = new LaStudio_Swatch_Term($swatch_config_object, $term->term_id, $attribute, $args['selected'] == $term->slug, $swatch_config_object->get_swatch_display_size());
                                        break;
                                    case 'product_custom':
                                        $swatch_term = new LaStudio_Swatch_Product_Term($swatch_config_object, $term->term_id, $attribute, $args['selected'] == $term->slug);
                                        break;
                                }
                                if( !empty($swatch_term) && $swatch_term instanceof LaStudio_Swatch_Term ) {
                                    do_action('la_swatches_before_picker_item', $swatch_term);
                                    $new_html .= $swatch_term->get_output();
                                    do_action('la_swatches_after_picker_item', $swatch_term);
                                }
                            }
                        }

                    }
                    else {
                        foreach ($options as $option) {
                            $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'], sanitize_title($option), false) : selected($args['selected'], $option, false);
                            $swatch_term = new LaStudio_Swatch_Product_Term($swatch_config_object, $option, $name, $selected);
                            do_action('la_swatches_before_picker_item', $swatch_term);
                            $new_html .= $swatch_term->get_output();
                            do_action('la_swatches_after_picker_item', $swatch_term);
                        }
                    }
                }
                $new_html .= '</div>';

                if ($swatch_config_object->get_swatch_label_display_style() == 'label_below') {
                    $new_html .= '<div class="attribute_' . $id . '_picker_label swatch-label">&nbsp;</div>';
                }
            }
            else {
                $args['hide'] = false;
                $args['class'] .= (!empty( $args['class'] ) ? ' ' : '') . 'wc-default-select';
                $new_html .= $this->render_dropdown_variation($args);
            }

            return $new_html;
        }

        protected function render_dropdown_variation( $args ) {
            $options                = $args['options'];
            $product                = $args['product'];
            $attribute              = $args['attribute'];
            $name                   = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
            $id                     = $args['id'] ? $args['id'] : sanitize_title( $attribute );
            $class                  = $args['class'];
            $show_option_none       = (bool) $args['show_option_none'];

            if ( empty( $options ) && !empty( $product ) && !empty( $attribute ) ) {
                $attributes = $product->get_variation_attributes();
                $options = $attributes[$attribute];
            }

            $html  = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';

            if ( $show_option_none ) {
                $html .= '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
            }

            if ( ! empty( $options ) ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array(
                        'fields' => 'all',
                    ) );

                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options, true ) ) {
                            $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                        }
                    }
                } else {
                    foreach ( $options as $option ) {
                        // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                        $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                        $html    .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                    }
                }
            }

            $html .= '</select>';

            return $html;
        }

        protected function render_radio_variation( $args ) {
            $options        = $args['options'];
            $product        = $args['product'];
            $attribute      = $args['attribute'];
            $name           = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute ) . '_' . uniqid();
            $id             = $args['id'] ? $args['id'] : sanitize_title( $attribute ) . uniqid();
            $class          = $args['class'];

            if ( empty( $options ) && !empty( $product ) && !empty( $attribute ) ) {
                $attributes = $product->get_variation_attributes();
                $options = $attributes[$attribute];
            }

            $html = '<ul id="radio_select_' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';

            if ( !empty( $options ) ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array('fields' => 'all') );

                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options ) ) {
                            $html .= '<li>';
                            $html .= '<input class="radio-option" name="' . esc_attr( $name ) . '" id="radio_' . esc_attr( $id ) . '_' . esc_attr( $term->slug ) . '" type="radio" data-value="' . esc_attr( $term->slug ) . '" value="' . esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $args['selected'] ), $term->slug, false ) . ' /><label for="radio_' . esc_attr( $id ) . '_' . esc_attr( $term->slug ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</label>';
                            $html .= '</li>';
                        }
                    }
                } else {
                    foreach ( $options as $option ) {
                        // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                        $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? checked( $args['selected'], sanitize_title( $option ), false ) : checked( $args['selected'], $option, false );
                        $html .= '<li>';
                        $html .= '<input class="radio-option" name="' . esc_attr( $name ) . '" id="radio_' . esc_attr( $id ) . '_' . esc_attr( $option ) . '" type="radio" data-value="' . esc_attr( $option ) . '" value="' . esc_attr( $option ) . '" ' . $selected . ' /><label for="radio_' . esc_attr( $id ) . '_' . esc_attr( $option ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</label>';
                        $html .= '</li>';
                    }
                }
            }

            $html .= '</ul>';

            return $html;
        }

    }
}
