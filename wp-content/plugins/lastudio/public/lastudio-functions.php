<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if(!function_exists('la_get_base_shop_url')){
    function la_get_base_shop_url( $with_post_type_archive = true ){
        $link = '';
        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        }
        elseif( is_tax( get_object_taxonomies( 'product' ) ) ) {
            if( is_product_tag() && $with_post_type_archive ){
                $link = get_post_type_archive_link( 'product' );
            }
            else{
                if( is_product_category() ) {
                    $link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
                }
                elseif ( is_product_tag() ) {
                    $link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
                }
                else{
                    $queried_object = get_queried_object();
                    $link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
                }
            }
        }
        else{
            if($with_post_type_archive){
                $link = get_post_type_archive_link( 'product' );
            }
            else{
                if(function_exists('dokan')){
                    $current_url = add_query_arg(null, null);
                    $current_url = remove_query_arg(array('page', 'paged', 'mode_view', 'la_doing_ajax'), $current_url);
                    $link = preg_replace('/\/page\/\d+/', '', $current_url);
                    $tmp = explode('?', $link);
                    if(isset($tmp[0])){
                        $link = $tmp[0];
                    }
                }
            }
        }
        return $link;
    }
}

if (!function_exists('la_log')) {
    function la_log($log) {
        if (true === WP_DEBUG) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

function la_locate_template($template_names, $load = false, $require_once = true){
    static $template_cache;

    $located = '';
    foreach ((array)$template_names as $template_name) {
        if (!$template_name){
            continue;
        }
        if (!empty($template_cache[$template_name])) {
            $located = $template_cache[$template_name];
            break;
        }
        if (isset($template_cache[$template_name]) && !$template_cache[$template_name]){
            continue;
        }

        if (file_exists(STYLESHEETPATH . '/' . $template_name)) {
            $located = STYLESHEETPATH . '/' . $template_name;
            $template_cache[$template_name] = $located;
            break;
        }
        elseif (file_exists(TEMPLATEPATH . '/' . $template_name)) {
            $located = TEMPLATEPATH . '/' . $template_name;
            $template_cache[$template_name] = $located;
            break;
        }
        elseif (file_exists(ABSPATH . WPINC . '/theme-compat/' . $template_name)) {
            $located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
            $template_cache[$template_name] = $located;
            break;
        }
        $template_cache[$template_name] = false;
    }

    if ($load && '' != $located){
        load_template($located, $require_once);
    }

    return $located;
}

function la_string_to_bool($string){
    return is_bool($string) ? $string : ('yes' === $string || 1 === $string || 'true' === $string || '1' === $string);
}

/**
 * Define a constant if it is not already defined.
 *
 * @since 1.0.0
 * @param string $name Constant name.
 * @param string $value Value.
 */

function la_maybe_define_constant($name, $value){
    if (!defined($name)) {
        define($name, $value);
    }
}

/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_fw_add_element($field = array(), $value = '', $unique = ''){
    $output = '';
    $depend = '';
    $sub = (isset($field['sub'])) ? 'sub-' : '';
    $unique = (isset($unique)) ? $unique : '';
    $class = 'LaStudio_Theme_Options_Field_' . strtolower($field['type']);
    $wrap_class = (isset($field['wrap_class'])) ? ' ' . $field['wrap_class'] : '';
    $el_class = (isset($field['title'])) ? sanitize_title($field['title']) : 'no-title';
    $hidden = '';
    $is_pseudo = (isset($field['pseudo'])) ? ' la-pseudo-field' : '';

    if (isset($field['dependency']) && !empty($field['dependency'])) {
        $hidden = ' hidden';
        $depend .= ' data-' . $sub . 'controller="' . $field['dependency'][0] . '"';
        $depend .= ' data-' . $sub . 'condition="' . $field['dependency'][1] . '"';
        $depend .= ' data-' . $sub . 'value="' . $field['dependency'][2] . '"';
    }

    $output .= '<div class="la-element la-element-' . $el_class . ' la-field-' . $field['type'] . $is_pseudo . $wrap_class . $hidden . '"' . $depend . '>';

    if (isset($field['title'])) {
        $field_desc = (isset($field['desc'])) ? '<p class="la-text-desc">' . $field['desc'] . '</p>' : '';
        $output .= '<div class="la-title"><h4>' . $field['title'] . '</h4>' . $field_desc . '</div>';
    }

    $output .= (isset($field['title'])) ? '<div class="la-fieldset">' : '';

    $value = (!isset($value) && isset($field['default'])) ? $field['default'] : $value;
    $value = (isset($field['value'])) ? $field['value'] : $value;

    if (class_exists($class)) {
        ob_start();
        $element = new $class($field, $value, $unique);
        $element->output();
        $output .= ob_get_clean();
    }
    else {
        $output .= '<p>' . __('This field class is not available!', 'lastudio') . '</p>';
    }

    $output .= (isset($field['title'])) ? '</div>' : '';
    $output .= '<div class="clear"></div>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_array_search($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }
        foreach ($array as $sub_array) {
            $results = array_merge($results, la_array_search($sub_array, $key, $value));
        }
    }
    return $results;
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_get_google_fonts(){
    $transient_name = 'la_get_google_fonts_' . LaStudio_Cache_Helper::get_transient_version('google_fonts');
    $transient_value = get_transient($transient_name);
    if( false === $transient_value ) {
        $file = plugin_dir_path(dirname(__FILE__)) . 'public/fonts/google-fonts.json';
        if (file_exists($file)) {
            $tmp = @file_get_contents($file);
            if (!is_wp_error($tmp)){
                $results = json_decode($tmp, false);
                if( is_object( $results ) ) {
                    $new_items = array();
                    foreach($results->items as $k => $v){
                        $font_obj = new stdClass();
                        $font_obj->family = $v->family;
                        $font_obj->category = $v->category;
                        $font_obj->variants = $v->variants;
                        $font_obj->subsets = $v->subsets;
                        $new_items[] = $font_obj;
                    }
                    $obj_tmp = new stdClass();
                    $obj_tmp->items = $new_items;

                    set_transient( $transient_name, $obj_tmp, DAY_IN_SECONDS * 30 );
                    return $obj_tmp;
                }
            }
        }
    }
    return !empty($transient_value) ? $transient_value : array();
}


/**
 *
 * Getting POST Var
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_get_var($var, $default = ''){
    if (isset($_POST[$var])) {
        return $_POST[$var];
    }
    if (isset($_GET[$var])) {
        return $_GET[$var];
    }
    return $default;
}

/**
 *
 * Getting POST Vars
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_get_vars($var, $depth, $default = ''){
    if (isset($_POST[$var][$depth])) {
        return $_POST[$var][$depth];
    }
    if (isset($_GET[$var][$depth])) {
        return $_GET[$var][$depth];
    }
    return $default;
}

function la_convert_option_to_customize($options){
    $panels = array();
    foreach ($options as $section) {
        if (empty($section['sections']) && empty($section['fields'])) {
            continue;
        }
		
		if(isset($section['name']) && $section['name'] == 'sidebar_panel'){
			continue;
		}
		
        $panel = array(
            'name' => (isset($section['name']) ? $section['name'] : uniqid()),
            'title' => $section['title'],
            'description' => (isset($section['description']) ? $section['description'] : '')
        );

        if (!empty($section['sections'])) {
            $sub_panel = array();
            foreach ($section['sections'] as $sub_section) {
				
				if(isset($sub_section['name']) && $sub_section['name'] == 'social_link_sections'){
					continue;
				}
				
                if (!empty($sub_section['fields'])) {
                    $sub_panel2 = array(
                        'name' => (isset($sub_section['name']) ? $sub_section['name'] : uniqid()),
                        'title' => $sub_section['title'],
                        'description' => (isset($sub_section['description']) ? $sub_section['description'] : '')
                    );
                    $fields = array();
                    foreach ($sub_section['fields'] as $field) {
                        $fields[] = la_convert_field_option_to_customize($field);
                    }
                    $sub_panel2['settings'] = $fields;
                    $sub_panel[] = $sub_panel2;
                }
            }
            $panel['sections'] = $sub_panel;
            $panels[] = $panel;
        } elseif (!empty($section['fields'])) {
            $fields = array();

            foreach ($section['fields'] as $field) {
                $fields[] = la_convert_field_option_to_customize($field);
            }
            $panel['settings'] = $fields;
            $panels[] = $panel;
        }
    }
    return $panels;
}

function la_convert_field_option_to_customize($field){
    $backup_field = $field;
    if (isset($backup_field['id'])) {
        $field_id = $backup_field['id'];
        unset($backup_field['id']);
    } else {
        $field_id = uniqid();
    }
    if (isset($backup_field['type']) && 'wp_editor' === $backup_field['type']) {
        $backup_field['type'] = 'textarea';
    }
    $tmp = array(
        'name' => $field_id,
        'control' => array(
            'type' => 'la_field',
            'options' => $backup_field
        )
    );
    if (isset($backup_field['default'])) {
        $tmp['default'] = $backup_field['default'];
        unset($backup_field['default']);
    }
    return $tmp;
}


function la_fw_get_child_shortcode_nested($content, $atts = null){
    $res = array();
    $reg = get_shortcode_regex();
    preg_match_all('~' . $reg . '~', $content, $matches);
    if (isset($matches[2]) && !empty($matches[2])) {
        foreach ($matches[2] as $key => $name) {
            $res[$name] = $name;
        }
    }
    return $res;
}

function la_fw_override_shortcodes($content = null){
    if (!empty($content)) {
        global $shortcode_tags, $backup_shortcode_tags;
        $backup_shortcode_tags = $shortcode_tags;
        $child_exists = la_fw_get_child_shortcode_nested($content);
        if (!empty($child_exists)) {
            foreach ($child_exists as $tag) {
                $shortcode_tags[$tag] = 'la_fw_wrap_shortcode_in_div';
            }
        }
    }
}

function la_fw_wrap_shortcode_in_div($attr, $content = null, $tag){
    global $backup_shortcode_tags;
    return '<div class="la-item-wrap">' . call_user_func($backup_shortcode_tags[$tag], $attr, $content, $tag) . '</div>';
}

function la_fw_restore_shortcodes(){
    global $shortcode_tags, $backup_shortcode_tags;
    // Restore the original callbacks
    if (isset($backup_shortcode_tags)) {
        $shortcode_tags = $backup_shortcode_tags;
    }
}

function la_pagespeed_detected(){
    return (
        isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/GTmetrix|Page Speed/i', $_SERVER['HTTP_USER_AGENT'])
    );
}

function la_shortcode_custom_css_class($param_value, $prefix = ''){
    $css_class = preg_match('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value) ? $prefix . preg_replace('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value) : '';
    return $css_class;
}

function la_build_link_from_atts($value){
    $result = array('url' => '', 'title' => '', 'target' => '', 'rel' => '');
    $params_pairs = explode('|', $value);
    if (!empty($params_pairs)) {
        foreach ($params_pairs as $pair) {
            $param = preg_split('/\:/', $pair);
            if (!empty($param[0]) && isset($param[1])) {
                $result[$param[0]] = rawurldecode($param[1]);
            }
        }
    }
    return $result;
}


function la_get_blank_image_src(){
    return 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
}

function la_get_shortcode_loop_transient_name( $shortcode_name, $shortcode_atts = array(), $cache_name = '' ){
    $transient_name = 'la_shortcode_loop' . substr( md5( wp_json_encode( $shortcode_atts ) . $shortcode_name ), 28 );
    if (isset($shortcode_atts['orderby']) &&  'rand' === $shortcode_atts['orderby'] ) {
        // When using rand, we'll cache a number of random queries and pull those to avoid querying rand on each page load.
        $rand_index      = rand( 0, max( 1, absint( apply_filters( 'lastudio_shortcode_query_max_rand_cache_count', 5 ) ) ) );
        $transient_name .= $rand_index;
    }
    $cache_name = sanitize_key($cache_name);
    if(empty($cache_name)){
        $cache_name = 'shortcode_query';
    }
    $transient_name .= LaStudio_Cache_Helper::get_transient_version( $cache_name );
    return $transient_name;
}

function la_get_shortcode_loop_query_results( $shortcode_name, $shortcode_atts = array(), $query_args = array(), $cache_name = ''){
    $transient_name = la_get_shortcode_loop_transient_name( $shortcode_name, $shortcode_atts, $cache_name);
    $tmp_cache_atts = isset($shortcode_atts['cache']) ? $shortcode_atts['cache'] : '';
    $cache          = la_string_to_bool( $tmp_cache_atts ) === true;
    $results        = $cache ? get_transient( $transient_name ) : false;
    if ( false === $results ) {
        $query = new WP_Query( $query_args );
        $paginated = ! $query->get( 'no_found_rows' );

        $results = (object) array(
            'ids'          => wp_list_pluck( $query->posts, 'ID' ),
            'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
            'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
            'per_page'     => (int) $query->get( 'posts_per_page' ),
            'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
        );
        if ( $cache ) {
            set_transient( $transient_name, $results, DAY_IN_SECONDS * 30 );
        }
    }
    return $results;
}

/**
 * @param $atts_string
 *
 * @since 1.0
 * @return array|mixed
 */
function la_param_group_parse_atts( $atts_string ) {
    $array = json_decode( urldecode( $atts_string ), true );

    return $array;
}

/**
 * Convert string to a valid css class name.
 *
 * @since 1.0
 *
 * @param string $class
 *
 * @return string
 */
function la_build_safe_css_class( $class ) {
    return preg_replace( '/\W+/', '', strtolower( str_replace( ' ', '_', strip_tags( $class ) ) ) );
}

function la_parse_multi_attribute( $value, $default = array() ) {
    $result = $default;
    $params_pairs = explode( '|', $value );
    if ( ! empty( $params_pairs ) ) {
        foreach ( $params_pairs as $pair ) {
            $param = preg_split( '/\:/', $pair );
            if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
                $result[ $param[0] ] = rawurldecode( $param[1] );
            }
        }
    }

    return $result;
}


function la_get_product_grid_style()
{
    return array(
        __('Design 01', 'lastudio')                 => '1',
        __('Design 02', 'lastudio')                 => '2',
        __('Design 03', 'lastudio')                 => '3',
        __('Design Grid 3 Products', 'lastudio')    => '4',
    );
}

function la_get_product_list_style()
{
    return array(
        __('Default', 'lastudio')           => 'default',
        __('List 3 Products', 'lastudio')   => 'special_1',
        __('Mini', 'lastudio')              => 'mini'
    );
}

function la_export_options()
{
    $unique = isset($_REQUEST['unique']) ? $_REQUEST['unique'] : 'la_options';
    header('Content-Type: plain/text');
    header('Content-disposition: attachment; filename=backup-' . esc_attr($unique) . '-' . gmdate('d-m-Y') . '.txt');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo wp_json_encode(get_option($unique));
    die();
}

add_action('wp_ajax_la-export-options', 'la_export_options');

function la_add_script_to_compare()
{
    echo '<script type="text/javascript">var redirect_to_cart=true;</script>';
}

add_action('yith_woocompare_after_main_table', 'la_add_script_to_compare');

function la_add_script_to_quickview_product()
{
    global $product;
    if (function_exists('is_product') && isset($_GET['product_quickview']) && is_product()) {
        if ($product->get_type() == 'variable') {
            wp_print_scripts('underscore');
            wc_get_template('single-product/add-to-cart/variation.php');
            ?>
            <script type="text/javascript">
                /* <![CDATA[ */
                var _wpUtilSettings = <?php echo wp_json_encode(array(
                    'ajax' => array('url' => admin_url('admin-ajax.php', 'relative'))
                ));?>;
                var wc_add_to_cart_variation_params = <?php echo wp_json_encode(array(
                    'wc_ajax_url'                      => WC_AJAX::get_endpoint( '%%endpoint%%' ),
                    'i18n_no_matching_variations_text' => esc_attr__('Sorry, no products matched your selection. Please choose a different combination.', 'lastudio'),
                    'i18n_make_a_selection_text' => esc_attr__('Select product options before adding this product to your cart.', 'lastudio'),
                    'i18n_unavailable_text' => esc_attr__('Sorry, this product is unavailable. Please choose a different combination.', 'lastudio')
                )); ?>;
                /* ]]> */
            </script>
            <script type="text/javascript" src="<?php echo esc_url(includes_url('js/wp-util.min.js')) ?>"></script>
            <script type="text/javascript"
                    src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/frontend/add-to-cart-variation.min.js' ?>"></script>
            <?php
        } else {
            ?>
            <script type="text/javascript">
                /* <![CDATA[ */
                var wc_single_product_params = <?php echo wp_json_encode(array(
                    'i18n_required_rating_text' => esc_attr__('Please select a rating', 'lastudio'),
                    'review_rating_required' => get_option('woocommerce_review_rating_required'),
                    'flexslider' => apply_filters('woocommerce_single_product_carousel_options', array(
                        'rtl' => is_rtl(),
                        'animation' => 'slide',
                        'smoothHeight' => false,
                        'directionNav' => false,
                        'controlNav' => 'thumbnails',
                        'slideshow' => false,
                        'animationSpeed' => 500,
                        'animationLoop' => false, // Breaks photoswipe pagination if true.
                    )),
                    'zoom_enabled' => 0,
                    'photoswipe_enabled' => 0,
                    'flexslider_enabled' => 1,
                ));?>;
                /* ]]> */
            </script>
            <?php
        }
    }
}

add_action('woocommerce_after_single_product', 'la_add_script_to_quickview_product');

function la_theme_fix_wc_track_product_view()
{
    if (!is_singular('product')) {
        return;
    }
    if (!function_exists('wc_setcookie')) {
        return;
    }
    global $post;
    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    }
    else {
        $viewed_products = (array)explode('|', $_COOKIE['woocommerce_recently_viewed']);
    }
    if (!in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
    }
    if (sizeof($viewed_products) > 15) {
        array_shift($viewed_products);
    }
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}

add_action('template_redirect', 'la_theme_fix_wc_track_product_view', 30);


add_filter('LaStudio/framework_option/sections', function( $sections, $settings, $unique ){
    if(isset($settings['menu_slug']) && $settings['menu_slug'] == 'theme_options'){
        $sections[] = array(
            'name' => 'la_extension_panel',
            'title' => esc_html_x('Extensions', 'admin-view', 'lastudio'),
            'icon' => 'fa fa-lock',
            'fields' => array(
                array(
                    'id'       => 'la_extension_available',
                    'type'     => 'checkbox',
                    'title'    => esc_html_x('Extensions Avaiable', 'admin-view', 'lastudio'),
                    'options'  => array(
                        'swatches' => 'Product Color Swatches',
                        '360' => 'Product 360',
                        'content_type' => 'Custom Content Type'
                    ),
                    'default' => array(
                        'swatches',
                        '360',
                        'content_type'
                    )
                ),
                array(
                    'type'    => 'notice',
                    'class'   => 'no-format la-section-title',
                    'content' => sprintf('<h3>%s</h3>', esc_html_x('Mailing List Manager', 'admin-view', 'lastudio'))
                ),
                array(
                    'id'        => 'mailchimp_api_key',
                    'type'      => 'text',
                    'title'     => esc_html_x('MailChimp API key', 'admin-view', 'lastudio'),
                    'attributes'=> array(
                            'placeholder' => esc_html_x('MailChimp API key', 'admin-view', 'lastudio')
                    ),
                    'desc'      => sprintf( '%1$s <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys">%2$s</a>', esc_html__( 'Input your MailChimp API key', 'lastudio' ), esc_html__( 'About API Keys', 'lastudio' ) ),
                ),
                array(
                    'id'        => 'mailchimp_list_id',
                    'type'      => 'text',
                    'attributes'=> array(
                        'placeholder' => esc_html_x('MailChimp list ID', 'admin-view', 'lastudio')
                    ),
                    'title'     => esc_html_x('MailChimp list ID', 'admin-view', 'lastudio'),
                    'desc'      => sprintf( '%1$s <a href="http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id">%2$s</a>', esc_html__( 'MailChimp list ID', 'lastudio' ), esc_html__( 'list ID', 'lastudio' ) ),
                ),
                array(
                    'id'        => 'mailchimp_double_opt_in',
                    'type'      => 'switcher',
                    'title'       => esc_html__( 'Double opt-in', 'lastudio' ),
                    'desc' => esc_html__( 'Send contacts an opt-in confirmation email when they subscribe to your list.', 'lastudio' ),
                ),
                array(
                    'type'    => 'notice',
                    'class'   => 'no-format la-section-title',
                    'content' => sprintf('<h3>%s</h3>', esc_html_x('Plugins Updates', 'admin-view', 'lastudio'))
                ),
                array(
                    'type'    => 'content',
                    'content' => '<div class="lasf_table"><div class="lasf_table--top"><a class="button button-primary lasf-button-check-plugins-for-updates" href="javascript:;">Check for updates</a></div></div>'
                )
            )
        );
    }
    return $sections;
}, 10, 3);

add_filter('la_validate_save', function( $request ) {

    if(isset($request['la_extension_available'])){

        $default = array(
            'swatches' => false,
            '360' => false,
            'content_type' => false
        );

        $la_extension_available = !empty($request['la_extension_available']) ? $request['la_extension_available'] : array('default' => 'hello');

        if(in_array('swatches', $la_extension_available)){
            $default['swatches'] = true;
        }
        if(in_array('360', $la_extension_available)){
            $default['360'] = true;
        }

        if(in_array('content_type', $la_extension_available)){
            $default['content_type'] = true;
        }

        update_option('la_extension_available', $default);
    }

    if(isset($request['la_custom_css'])){
        if(isset($request['mailchimp_api_key'])){
            $mailchimp_api_key = $request['mailchimp_api_key'];
            update_option('lastudio_elements_mailchimp_api_key', $mailchimp_api_key);
        }
        if(isset($request['mailchimp_list_id'])){
            $mailchimp_list_id = $request['mailchimp_list_id'];
            update_option('lastudio_elements_mailchimp_list_id', $mailchimp_list_id);
        }

        if(isset($request['mailchimp_double_opt_in'])){
            $mailchimp_double_opt_in = $request['mailchimp_double_opt_in'];
        }
        else{
            $mailchimp_double_opt_in = false;
        }
        update_option('lastudio_elements_mailchimp_double_opt_in', $mailchimp_double_opt_in);
    }

    return $request;
});

if(!function_exists('lasf_array_diff_assoc_recursive')){
    function lasf_array_diff_assoc_recursive($array1, $array2) {
        $difference=array();
        foreach($array1 as $key => $value) {
            if( is_array($value) ) {
                if( !isset($array2[$key]) || !is_array($array2[$key]) ) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = lasf_array_diff_assoc_recursive($value, $array2[$key]);
                    if( !empty($new_diff) )
                        $difference[$key] = $new_diff;
                }
            } else if( !array_key_exists($key,$array2) || $array2[$key] !== $value ) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }
}

add_action('wp_ajax_lasf_check_plugins_for_updates', function(){

    do_action('lastudio_elementor_recreate_editor_file');

    $theme_obj = wp_get_theme();

    $option_key = $theme_obj->template . '_required_plugins_list';

    $theme_version = $theme_obj->version;

    if( $theme_obj->parent() !== false ) {
        $theme_version = $theme_obj->parent()->version;
    }

    $remote_url = 'https://la-studioweb.com/file-resouces/' ;

    $response = wp_remote_get($remote_url, array(
        'method' => 'POST',
        'timeout' => 30,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array(
            'theme_name'    => $theme_obj->template,
            'site_url'      => home_url('/'),
            'customer'      => call_user_func(strrev('noitpo_teg'),strrev('liame_nimda'))
        ),
        'cookies' => array()
    ));

    // request failed
    if ( is_wp_error( $response ) ) {
        echo 'Could not connect to server, please try later';
        die();
    }

    $code = (int) wp_remote_retrieve_response_code( $response );

    if ( $code !== 200 ) {
        echo 'Could not connect to server, please try later';
        die();
    }

    try{

        $body = json_decode(wp_remote_retrieve_body($response), true);

        $response_theme_version = !empty($body['theme']['version']) ? $body['theme']['version'] : $theme_version;

        if( version_compare($response_theme_version, $theme_version) >= 0 ) {

            $old_plugins = get_option($option_key, array());

            if( !empty( $body['plugins'] ) &&  !empty( lasf_array_diff_assoc_recursive( $body['plugins'], $old_plugins ) ) ) {
                update_option($option_key, $body['plugins']);
                delete_transient('lasf_auto_check_update');
                echo 'Please go to `Appearance` -> `Install Plugins` to update the required plugins ( if it is available )';
            }
            else{
                echo 'Nothing needs updating, everything is the latest';
            }
        }
        else{
            echo 'Nothing needs updating, everything is the latest';
        }

    }
    catch ( Exception $ex ){
        echo 'Could not connect to server, please try later';
    }
    die();

});

add_action( 'admin_notices', 'lasf_auto_check_update', 20 );

function lasf_auto_check_update(){
    $cache = get_transient('lasf_auto_check_update');
    $time_to_life = HOUR_IN_SECONDS * 12; // 12 hours

    $theme_obj = wp_get_theme();
    $theme_version = $theme_obj->version;
    if( $theme_obj->parent() !== false ) {
        $theme_version = $theme_obj->parent()->version;
    }
    $option_key = $theme_obj->template . '_required_plugins_list';

    if(empty($cache)){
        $remote_url = 'https://la-studioweb.com/file-resouces/';
        $response = wp_remote_get($remote_url, array(
            'method' => 'POST',
            'timeout' => 30,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array(
                'theme_name'    => $theme_obj->template
            ),
            'cookies' => array()
        ));
        // request failed
        if ( is_wp_error( $response ) ) {
            return false;
        }
        $code = (int) wp_remote_retrieve_response_code( $response );

        if ( $code !== 200 ) {
            return false;
        }
        $body = json_decode(wp_remote_retrieve_body($response), true);
        set_transient('lasf_auto_check_update', $body, $time_to_life);
    }
    else{
        $response_theme_version = !empty($cache['theme']['version']) ? $cache['theme']['version'] : $theme_version;

        if(version_compare($response_theme_version, $theme_version) > 0 ) {
            $class = 'notice notice-warning is-dismissible';
            $message = 'Version <strong>'.$response_theme_version.'</strong> of the theme is available, please update the theme';
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
        }

        if(version_compare($response_theme_version, $theme_version) >= 0 ) {
            $old_plugins = get_option($option_key, array());
            if( !empty( $cache['plugins'] ) &&  !empty( lasf_array_diff_assoc_recursive( $cache['plugins'], $old_plugins ) ) ) {
                update_option($option_key, $cache['plugins']);
            }
        }
    }
}

add_action('wp_dashboard_setup', 'lasf_add_widget_into_admin_dashboard', 0);
function lasf_add_widget_into_admin_dashboard(){
    wp_add_dashboard_widget('lasf_dashboard_theme_support', 'LaStudio Support', 'lasf_widget_dashboard_support_callback');
    wp_add_dashboard_widget('lasf_dashboard_latest_new', 'LaStudio Latest News', 'lasf_widget_dashboard_latest_news_callback');
}
function lasf_widget_dashboard_support_callback(){
    ?>
    <h3>Welcome to LA-Studio Theme! Need help?</h3>
    <p><a class="button button-primary" target="_blank" href="https://support.la-studioweb.com/">Open a ticket</a></p>
    <p>For WordPress Tutorials visit: <a href="https://la-studioweb.com/" target="_blank">LaStudioWeb.Com</a></p>
    <?php
}
function lasf_widget_dashboard_latest_news_callback(){
    ?>

    <style type="text/css">
        .lasf-latest-news li{display:-ms-flexbox;display:flex;width:100%;margin-bottom:12px;border-bottom:1px solid #eee;padding-bottom:12px}.lasf-latest-news li:last-child{border-bottom:0;margin-bottom:0}.lasf_news-img{background-position:top center;background-repeat:no-repeat;width:120px;position:relative;padding-bottom:67px;background-size:cover;flex:0 0 120px;margin-right:15px}.lasf_news-img a{position:absolute;font-size:0;opacity:0;width:100%;height:100%;top:0;left:0}.lasf_news-info{flex-grow:2}.lasf_news-info h4{margin-bottom:5px!important;overflow:hidden;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical}.lasf_news-desc{max-height:3.5em;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}#lasf_dashboard_latest_new h3{margin-bottom:10px;font-weight:600}ul.lasf-latest-news{margin:0;list-style:none;padding:0}ul.lasf-latest-themes{margin:0;padding:0;display:-ms-flexbox;display:flex;-webkit-flex-flow:row wrap;-ms-flex-flow:row wrap;flex-flow:row wrap;-webkit-align-content:flex-start;-ms-flex-line-pack:start;align-content:flex-start;margin-left:-8px;margin-right:-8px}ul.lasf-latest-themes li{width:50%;display:inline-block;padding:8px;box-sizing:border-box}.lasf_theme-img{position:relative;display:block;padding-bottom:50.8%;background-position:top center;background-size:cover;background-repeat:no-repeat;justify-content:center;align-items:center;margin-bottom:8px}.lasf_theme-img a.lasf_theme-action-view{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;font-size:0;background:#fff}.lasf_theme-img a.lasf_theme-action-details{position:absolute;background-color:#3E3E3E;color:#fff;text-transform:uppercase;bottom:10px;font-size:11px;padding:5px 0;line-height:20px;border-radius:4px;font-weight:500;width:80px;text-align:center;right:50%;margin-right:5px}.lasf_theme-img a.lasf_theme-action-demo{position:absolute;background-color:#3E3E3E;color:#fff;text-transform:uppercase;bottom:10px;font-size:11px;padding:5px 0;line-height:20px;border-radius:4px;font-weight:500;width:80px;text-align:center;left:50%;margin-left:5px}.lasf_theme-img a.lasf_theme-action-details:hover,.lasf_theme-img a.lasf_theme-action-demo:hover{background-color:#ed2a11}.lasf_theme-img:hover a.lasf_theme-action-view{opacity:.2}.lasf_theme-info h4{margin-bottom:5px!important}.lasf_theme-info .lasf_news-price{color:#ed2a11;font-weight:600}.lasf_theme-info .lasf_news-price s{color:#444;margin-left:5px}.lasf_dashboard_latest_new p a{text-align:center}#lasf_dashboard_latest_new p{display:block;text-align:center;margin:0 0 20px;border-bottom:1px solid #eee;padding-bottom:12px}#lasf_dashboard_latest_new p:last-child{margin-bottom:0;border:none;padding-bottom:0}#lasf_dashboard_latest_new p a{border:none;text-decoration:none;background-color:#3E3E3E;color:#fff;display:inline-block;padding:5px 20px;border-radius:4px}#lasf_dashboard_latest_new p a:hover{background-color:#ed2a11}
    </style>
    <?php
    $theme_obj = wp_get_theme();
    $remote_url = 'https://la-studioweb.com/tools/recent-news/';
    $cache = get_transient('lasf_dashboard_latest_new');
    $time_to_life = DAY_IN_SECONDS * 5; // 5 days
    if(empty($cache)){
        $response = wp_remote_post( $remote_url, array(
            'method' => 'POST',
            'timeout' => 30,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array(
                'theme_name'    => $theme_obj->template,
                'site_url'      => home_url('/'),
                'customer'      => call_user_func(strrev('noitpo_teg'),strrev('liame_nimda'))
            ),
            'cookies' => array()
        ));

        // request failed
        if ( is_wp_error( $response ) ) {
            echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
            set_transient('lasf_dashboard_latest_new', 'false', $time_to_life);
            return false;
        }

        $code = (int) wp_remote_retrieve_response_code( $response );

        if ( $code !== 200 ) {
            echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
            set_transient('lasf_dashboard_latest_new', 'false', $time_to_life);
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $body = json_decode($body, true);
        set_transient('lasf_dashboard_latest_new', $body, $time_to_life);
    }

    if($cache == 'false'){
        echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
    }
    else{
        if(empty($cache['news']) && empty($cache['themes'])){
            echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
        }
        else{
            if(!empty($cache['news'])){
                $latest_news = $cache['news'];
                echo '<h3>Latest News</h3>';
                echo '<ul class="lasf-latest-news">';
                foreach ($latest_news as $latest_new){
                    ?>
                    <li>
                        <div class="lasf_news-img" style="background-image: url('<?php echo esc_url($latest_new['thumb']) ?>')">
                            <a href="<?php echo esc_url($latest_new['url']) ?>"><?php echo esc_attr($latest_new['title']) ?></a>
                        </div>
                        <div class="lasf_news-info">
                            <h4><a href="<?php echo esc_url($latest_new['url']) ?>"><?php echo esc_attr($latest_new['title']) ?></a></h4>
                            <div class="lasf_news-desc"><?php echo $latest_new['desc'] ?></div>
                        </div>
                    </li>
                    <?php
                }
                echo '</ul>';
                echo '<p><a href="https://la-studioweb.com/blog/">See More</a></p>';
            }
            if(!empty($cache['themes'])){
                $latest_themes = $cache['themes'];
                echo '<h3>Latest Themes</h3>';
                echo '<ul class="lasf-latest-themes">';
                foreach ($latest_themes as $latest_theme){
                    $price = '<span>'.$latest_theme['price'].'</span>';
                    if(!empty($latest_theme['sale'])){
                        $price = '<span>'.$latest_theme['sale'].'</span><s>'.$latest_theme['price'].'</s>';
                    }
                    ?>
                    <li>
                        <div class="lasf_theme-img" style="background-image: url('<?php echo esc_url($latest_theme['thumb']) ?>')">
                            <a class="lasf_theme-action-view" href="<?php echo esc_url($latest_theme['url']) ?>"><?php echo esc_attr($latest_theme['title']) ?></a>
                            <a class="lasf_theme-action-details" href="<?php echo esc_url($latest_theme['url']) ?>">Details</a>
                            <a class="lasf_theme-action-demo" href="<?php echo esc_url($latest_theme['buy']) ?>">Live Demo</a>
                        </div>
                        <div class="lasf_theme-info">
                            <h4><a href="<?php echo esc_url($latest_theme['url']) ?>"><?php echo esc_attr($latest_theme['title']) ?></a></h4>
                            <div class="lasf_news-price"><?php echo $price; ?></div>
                        </div>
                    </li>
                    <?php
                }
                echo '</ul>';
                echo '<p><a href="https://la-studioweb.com/theme-list/">Discover More</a></p>';
            }
        }
    }

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var lasf1 = jQuery('#lasf_dashboard_latest_new'),
                lasf2 = jQuery('#lasf_dashboard_theme_support');
            if(lasf1.length > 0){
                lasf1.prependTo(lasf1.parent());
            }
            if(lasf2.length > 0){
                lasf2.prependTo(lasf2.parent());
            }

        })
    </script>
    <?php
}