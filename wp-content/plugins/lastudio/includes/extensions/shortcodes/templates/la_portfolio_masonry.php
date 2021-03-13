<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$mb_column = '';
$category__in = $category__not_in = $post__in = $post__not_in = $orderby = $order = $per_page = $paged = $enable_skill_filter = $enable_loadmore = $load_more_text = $filters = $filter_style = $masonry_style = $column_type = $column = $base_item_h = $base_item_w = $custom_item_size = $item_sizes = $title_tag = $img_size = $el_class = '';
$item_space = '';
$height_mode = $height = '';
$cache = $base_container_w = '';
$shortcode_id = '';
$atts = shortcode_atts( array(
    'category__in' => '',
    'category__not_in' => '',
    'post__in' => '',
    'post__not_in' => '',
    'orderby' => 'date',
    'order' => 'desc',
    'per_page' => 10,
    'paged' => '1',
    'enable_skill_filter' => '',
    'enable_loadmore' => '',
    'load_more_text' => 'Load more',
    'filters' => '',
    'filter_style' => '1',
    'masonry_style' => '1',
    'title_tag' => 'h3',
    'height_mode' => 'original',
    'height' => '',
    'cache' => 'yes',
    'img_size' => 'full',
    'item_space' => '30',
    'column_type' => 'default',
    'column' => '',
    'base_container_w'  => 1170,
    'base_item_w' => 400,
    'base_item_h' => 400,
    'mb_column' => '',
    'custom_item_size' => '',
    'item_sizes' => '',
    'el_class' => '',
    'shortcode_id' => ''

), $atts );

$excerpt_length = 15;

if( 0 === $per_page ) $per_page = 1;
if(empty($paged)){
    $paged = 1;
}

extract( $atts );

$_tmp_class = 'la-portfolio-masonry';
$el_class = $_tmp_class . LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$unique_id = !empty($shortcode_id) ? $shortcode_id : uniqid('la_pf_');

$query_args = array(
    'post_type'             => 'la_portfolio',
    'post_status'		    => 'publish',
    'orderby'               => $orderby,
    'order'                 => $order,
    'ignore_sticky_posts'   => 1,
    'paged'                 => $paged,
    'posts_per_page'        => $per_page
);

if ( $category__in ) {
    $category__in = explode( ',', $category__in );
    $category__in = array_map( 'trim', $category__in );
}
if ( $category__not_in ) {
    $category__not_in = explode( ',', $category__not_in );
    $category__not_in = array_map( 'trim', $category__not_in );
}
if ( $post__in ) {
    $post__in = explode( ',', $post__in );
    $post__in = array_map( 'trim', $post__in );
}
if ( $post__not_in ) {
    $post__not_in = explode( ',', $post__not_in );
    $post__not_in = array_map( 'trim', $post__not_in );
}
$tax_query = array();
if ( !empty( $category__in ) && !empty( $category__not_in ) ){
    $tax_query['relation'] = 'AND';
}
if ( !empty ( $category__in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'la_portfolio_category',
        'field'    => 'term_id',
        'terms'    => $category__in
    );
}
if ( !empty ( $category__not_in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'la_portfolio_category',
        'field'    => 'term_id',
        'terms'    => $category__not_in,
        'operator' => 'NOT IN'
    );
}
if ( !empty($tax_query) ) {
    $query_args['tax_query'] = $tax_query;
}
if ( !empty ( $post__in ) ) {
    $query_args['post__in'] = $post__in;
}
if ( !empty ( $post__not_in ) ) {
    $query_args['post__not_in'] = $post__not_in;
}

$globalVar = apply_filters('LaStudio/global_loop_variable', 'lastudio_loop');
$globalVarTmp = (isset($GLOBALS[$globalVar]) ? $GLOBALS[$globalVar] : '');
$globalParams = array();

$layout = 'masonry';

$globalParams['loop_id']            = $unique_id;
$globalParams['item_space']         = $item_space;
$globalParams['loop_layout']        = $layout;
$globalParams['loop_style']         = $masonry_style;
$globalParams['responsive_column']  = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($column);
$globalParams['image_size']         = LaStudio_Shortcodes_Helper::getImageSizeFormString($img_size);
$globalParams['title_tag']          = $title_tag;
$globalParams['excerpt_length']     = $excerpt_length;
$globalParams['column_type']        = $column_type;
$globalParams['mb_column']          = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($mb_column);
$globalParams['base_container_w']   = $base_container_w;
$globalParams['base_item_w']        = $base_item_w;
$globalParams['base_item_h']        = $base_item_h;
$globalParams['height_mode'] = $height_mode;
$globalParams['height'] = esc_attr($height);

if($custom_item_size == 'yes'){
    $_item_sizes = (array) la_param_group_parse_atts( $item_sizes );
    $__new_item_sizes = array();
    if(!empty($_item_sizes)){
        foreach($_item_sizes as $k => $size){
            $__new_item_sizes[$k] = $size;
            if(!empty($size['s'])){
                $__new_item_sizes[$k]['s'] = LaStudio_Shortcodes_Helper::getImageSizeFormString($size['s']);
            }
        }
    }
    $globalParams['item_sizes'] = $__new_item_sizes;
}

if($enable_skill_filter == 'yes'){
    $globalParams['enable_skill_filter'] = true;
    $globalParams['filters'] = $filters;
    $globalParams['filter_style'] = $filter_style;
}


$cache_query = la_get_shortcode_loop_query_results('show_portfolios', $atts, $query_args, 'la_portfolio');

$start_tpl = $end_tpl = $loop_tpl = array();
$start_tpl[] = "templates/portfolios/{$layout}/start-{$masonry_style}.php";
$start_tpl[] = "templates/portfolios/{$layout}/start.php";
$start_tpl[] = "templates/portfolios/start-{$layout}.php";
$start_tpl[] = "templates/portfolios/start.php";
$loop_tpl[] = "templates/portfolios/{$layout}/loop-{$masonry_style}.php";
$loop_tpl[] = "templates/portfolios/{$layout}/loop.php";
$loop_tpl[] = "templates/portfolios/loop-{$layout}.php";
$loop_tpl[] = "templates/portfolios/loop.php";
$end_tpl[] = "templates/portfolios/{$layout}/end-{$masonry_style}.php";
$end_tpl[] = "templates/portfolios/{$layout}/end.php";
$end_tpl[] = "templates/portfolios/end-{$layout}.php";
$end_tpl[] = "templates/portfolios/end.php";

ob_start();

if ( $cache_query && $cache_query->ids ) {

    update_meta_cache( 'post', $cache_query->ids );
    update_object_term_cache( $cache_query->ids, 'portfolio' );


    $max_paged = $cache_query->total_pages;
    $max_posts = $cache_query->total;

    $GLOBALS[$globalVar] = $globalParams;

    $original_post = !empty($GLOBALS['post']) ? $GLOBALS['post'] : '';

    add_filter('excerpt_length', function() use ( $excerpt_length ) {
        return absint($excerpt_length);
    }, 1010);

    do_action('LaStudio/shortcodes/before_loop', 'shortcode', 'la_portfolio_masonry', $atts);

    if($cache_query->total){
        la_locate_template($start_tpl, true, false);
        $i_counter = 1 * ($paged == 1 ? $paged : ($paged + 1));
        foreach ( $cache_query->ids as $post_id ) {
            if($i_counter > $max_posts){
                break;
            }
            $GLOBALS['post'] = get_post( $post_id ); // WPCS: override ok.
            setup_postdata( $GLOBALS['post'] );
            la_locate_template($loop_tpl, true, false);
            $i_counter++;
        }
        la_locate_template($end_tpl, true, false);
    }

    if($enable_loadmore && $max_paged > $paged){
        echo sprintf(
            '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s .portfolios-loop" data-item-class=".portfolio__item">%s<a href="#" class="btn">%s</a></div>',
            esc_attr( wp_json_encode( array(
                'tag' => 'la_portfolio_masonry',
                'atts' => $atts
            ) ) ),
            esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
            esc_attr($paged),
            esc_attr($max_paged),
            esc_attr($unique_id),
            LaStudio_Shortcodes_Helper::getLoadingIcon(),
            esc_html($load_more_text)
        );
    }

    remove_all_filters('excerpt_length', 1010);
    do_action('LaStudio/shortcodes/after_loop','shortcode', 'la_portfolio_masonry', $atts);

    wp_reset_postdata();
    if(!empty($original_post)){
        $GLOBALS['post'] = $original_post; // WPCS: override ok.
    }
    $GLOBALS[$globalVar] = $globalVarTmp; // WPCS: override ok.
}

echo sprintf(
    '<div id="%1$s" class="%2$s">%3$s</div>',
    esc_attr($unique_id),
    esc_attr($el_class),
    ob_get_clean()
);