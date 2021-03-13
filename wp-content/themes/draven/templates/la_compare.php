<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div id="la_compare_table_wrapper">
    <div id="la_compare_table_wrapper2">
    <?php

    if(function_exists('wc_print_notices')){
        wc_print_notices();
    }

    $lists = Draven_WooCommerce_Compare::get_data();

    $default_attr = draven_get_wc_attribute_for_compare();
    $tax_attr = draven_get_wc_attribute_taxonomies();
    $all_attr = array_merge($default_attr, $tax_attr);

    $attribute_allow = Draven()->settings()->get('compare_attribute', $default_attr);
    if(empty($attribute_allow)){
        $attribute_allow = $default_attr;
    }

    global $product;

    $terms = array();

    if (!empty($lists)) {
        foreach ($lists as $product_id) {
            $product_id = draven_wpml_object_id($product_id, 'product', true);
            $term = array();
            $_product = wc_get_product($product_id);
            $term['id'] = $product_id;
            $term['sku'] = ($_product->get_sku() ? $_product->get_sku() : __('N/A', 'draven'));
            $term['title'] = $_product->get_title();
            $term['image'] = $_product->get_image();
            $term['rating'] = sprintf('<div class="star-rating">%s</div>', wc_get_star_rating_html( $_product->get_average_rating() ));
            $term['price'] = $_product->get_price_html();
            $term['link'] = $_product->get_permalink();
            $term['description'] = wp_trim_words(wp_strip_all_tags($_product->get_short_description()), 10);
            $term['availability'] = $_product->get_availability();
            $term['dimensions'] = wc_format_dimensions($_product->get_dimensions(false));
            $term['weight'] = wc_format_weight($_product->get_weight());
            $term['stock'] = $_product->is_in_stock();

            if($attributes = $_product->get_attributes()){
                foreach ($attributes as $key => $attribute) {
                    if(!in_array($key,$attribute_allow)){
                        continue;
                    }
                    $values = array();
                    if($attribute->is_taxonomy()){
                        $attribute_taxonomy = $attribute->get_taxonomy_object();
                        $attribute_values = wc_get_product_terms( $product_id, $attribute->get_name(), array( 'fields' => 'all' ) );
                        foreach ( $attribute_values as $attribute_value ) {
                            $values[] = esc_html( $attribute_value->name );
                        }

                    }
                    $term[$key] = !empty($values) ? join(', ', $values) : '';
                }
            }

            $terms[] = $term;
        }
        ?>
        <table class="la-compare-table">
            <tbody>
            <?php

            $total_product = count($terms);

            $label_list = array();

            foreach( $attribute_allow as $attr_alow ){
                if(in_array($attr_alow, array('image','title','add-to-cart'))){
                    $label_list['info'] = esc_html_x('Product Info', 'front-view', 'draven');
                }
                else{
                    if(isset($all_attr[$attr_alow])){
                        $label_list[$attr_alow] = $all_attr[$attr_alow];
                    }
                }
            }

            foreach ($label_list as $k => $v) {
                echo '<tr class="compare-tr compare-tr-'. esc_attr($k) .'">';
                echo sprintf('<th>%s</th>', $v);
                foreach ($terms as $item) {
                    if ($k == 'info') {
                        echo '<td>';
                            echo sprintf(
                                '<div class="remove"><a href="%1$s" class="la_remove_from_compare" aria-label="%2$s" data-product_id="%3$s"><i class="fa fa-times"></i>%2$s</a></div>',
                                esc_url(add_query_arg(array(
                                    'la_helpers_compare_remove' => $item['id']
                                ))),
                                __('Remove', 'draven'),
                                esc_attr($item['id'])
                            );
                            echo sprintf(
                                '<a href="%1$s" title="%3$s"><div class="image-wrap">%2$s</div><h4>%3$s</h4></a>',
                                esc_url($item['link']),
                                $item['image'],
                                $item['title']
                            );
                            echo '<div class="add_to_cart_wrap">';
                            $product = wc_get_product($item['id']);
                            woocommerce_template_loop_add_to_cart();
                            echo '</div>';
                        echo '</td>';
                    }
                    elseif( in_array($k, array('rating', 'price', 'description', 'dimensions', 'weight', 'sku')) ) {
                        echo sprintf( '<td>%s</td>', $item[$k] );
                    }
                    elseif ($k == 'stock') {
                        $class_stock = '';
                        $text_stock = '';
                        if ($item['availability'] && !empty($item['availability']['availability'])) {
                            $product2 = wc_get_product($item['id']);
                            $availability_html = empty($item['availability']['availability']) ? '' : '<p class="stock ' . esc_attr($item['availability']['class']) . '">' . esc_html($item['availability']['availability']) . '</p>';
                            $text_stock = apply_filters('woocommerce_stock_html', $availability_html, $item['availability']['availability'], $product2);
                            $class_stock = esc_attr($item['availability']['class']);
                        }
                        else {
                            if ($item['stock']) {
                                $text_stock = __('In stock', 'draven');
                                $class_stock = 'in-stock';
                            } else {
                                $text_stock = __('Out of stock', 'draven');
                                $class_stock = 'out-of-stock';
                            }
                            $text_stock = '<p class="stock ' . $class_stock . '">' . $text_stock . '</p>';
                        }
                        echo sprintf( '<td>%s</td>', $text_stock );
                    }
                    else{
                        echo sprintf( '<td>%s</td>', (isset($item[$k]) ? $item[$k] : '') );
                    }
                }
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
        <?php
    }

	else{
        ?>
        <p class="text-center"><?php esc_html_e('No products were added to the list', 'draven') ?></p>
        <?php
    }
    ?>
</div>
</div>

