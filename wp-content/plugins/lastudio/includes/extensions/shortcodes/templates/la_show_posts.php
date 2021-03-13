<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$layout = $list_style = $grid_style = $masonry_style = $category__in = $category__not_in = $post__in = $post__not_in = $per_page = $title_tag = $excerpt_length = $img_size = $column = $enable_carousel = $el_class = '';
$orderby = $order = $img_size2 = '';
$paged = $load_more_text = $items_per_page = $style = '';
$item_space = '';
$height_mode = $height = '';
$item_animation = $slider_type = $cache = '';
$atts = shortcode_atts( array(
    'layout' => 'grid',
    'blog_style' => '1',
    'list_style' => '1',
    'grid_style' => '1',
    'masonry_style' => '1',
    'special_style' => '1',
    'category__in' => '',
    'category__not_in' => '',
    'post__in' => '',
    'post__not_in' => '',
    'orderby' => '',
    'order' => '',
    'title_tag' => 'h3',
    'excerpt_length' => 20,
    'height_mode' => 'original',
    'height' => '',
    'img_size' => 'thumbnail',
    'img_size2' => 'thumbnail',
    'column' => '',
    'item_space' => 'default',
    'enable_carousel' => '',
    'style' => 'all',
    'per_page' => 4,
    'paged' => '1',
    'items_per_page' => 4,
    'load_more_text' => 'Read more',
    'el_class' => '',
    'slider_type' => 'horizontal',
    'slide_to_scroll' => 'all',
    'infinite_loop' => '',
    'speed' => '300',
    'autoplay' => '',
    'autoplay_speed' => '5000',
    'arrows' => '',
    'arrow_style' => 'default',
    'arrow_bg_color' => '',
    'arrow_border_color' => '',
    'border_size' => '2',
    'arrow_color' => '#333333',
    'arrow_size' => '24',
    'next_icon' => 'dl-icon-right',
    'prev_icon' => 'dl-icon-left',
    'custom_nav' => '',
    'dots' => '',
    'dots_color' => '#333333',
    'dots_icon' => 'fa fa-circle',
    'draggable' => 'yes',
    'touch_move' => 'yes',
    'rtl' => '',
    'adaptive_height' => '',
    'pauseohover' => '',
    'centermode' => '',
    'autowidth' => '',
    'cache' => 'yes',
    'item_animation' => ''
), $atts );
extract( $atts );

$_tmp_class = 'la-showposts';
$el_class = $_tmp_class . LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$unique_id = uniqid('la_showposts_');

$query_args = array(
    'post_type'             => 'post',
    'post_status'		    => 'publish',
    'orderby'               => $orderby,
    'order'                 => $order,
    'ignore_sticky_posts'   => 1,
    'paged'                 => $paged,
    'posts_per_page'        => $per_page
);

if($style != 'all'){
    if($per_page != '-1' && $items_per_page > $per_page){
        $items_per_page = $per_page;
    }
    $query_args['posts_per_page'] = $items_per_page;
}

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
if ( !empty ( $category__in ) ) {
    $query_args['category__in'] = $category__in;
}
if ( !empty ( $category__not_in ) ) {
    $query_args['category__not_in'] = $category__not_in;
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

$globalParams['loop_id'] = $unique_id;
$globalParams['loop_layout'] = $layout;
$globalParams['loop_style'] = ${$layout . '_style'};
$globalParams['responsive_column'] = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($column);
$globalParams['image_size'] = LaStudio_Shortcodes_Helper::getImageSizeFormString($img_size);
$globalParams['height_mode'] = $height_mode;
$globalParams['height'] = esc_attr($height);
$globalParams['title_tag'] = $title_tag;
$globalParams['item_space'] = $item_space;
$globalParams['excerpt_length'] = $excerpt_length;
if($enable_carousel){
    $globalParams['slider_configs'] = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts, 'column');
    if(!empty($item_animation) && $item_animation != 'none' && $centermode != 'yes' && $autowidth != 'yes' && $slider_type == 'horizontal'){
        $globalParams['slider_css_class'] = ' laslick_has_animation laslick_'. $item_animation;
    }
}

$cache_query = la_get_shortcode_loop_query_results('show_posts', $atts, $query_args, 'post');

$start_tpl = $end_tpl = $loop_tpl = array();
$start_tpl[] = "templates/posts/{$layout}/start-" . ${$layout . '_style'} . ".php";
$start_tpl[] = "templates/posts/{$layout}/start.php";
$start_tpl[] = "templates/posts/start-{$layout}.php";
$start_tpl[] = "templates/posts/start.php";
$loop_tpl[] = "templates/posts/{$layout}/loop-" . ${$layout . '_style'} . ".php";
$loop_tpl[] = "templates/posts/{$layout}/loop.php";
$loop_tpl[] = "templates/posts/loop-{$layout}.php";
$loop_tpl[] = "templates/posts/loop.php";
$end_tpl[] = "templates/posts/{$layout}/end-" . ${$layout . '_style'} . ".php";
$end_tpl[] = "templates/posts/{$layout}/end.php";
$end_tpl[] = "templates/posts/end-{$layout}.php";
$end_tpl[] = "templates/posts/end.php";

ob_start();

if ( $cache_query && $cache_query->ids ) {

    update_meta_cache( 'post', $cache_query->ids );
    update_object_term_cache( $cache_query->ids, 'post' );


    $max_paged = $cache_query->total_pages;
    $max_posts = $cache_query->total;

    if($style != 'all'){
        if($per_page > 0){
            $__max_paged = ceil($per_page / $items_per_page);
            if($max_paged > $__max_paged){
                $max_paged = $__max_paged;
            }
            if($max_posts > $per_page){
                $max_posts = $per_page;
            }
        }
    }

    $GLOBALS[$globalVar] = $globalParams;

    $original_post = !empty($GLOBALS['post']) ? $GLOBALS['post'] : '';

    add_filter('excerpt_length', function() use ( $excerpt_length ) {
        return absint($excerpt_length);
    }, 1010);

    do_action('LaStudio/shortcodes/before_loop', 'shortcode', 'la_show_posts', $atts);

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

    if($style == 'load-more'){
        echo sprintf(
            '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s .showposts-loop" data-item-class=".loop__item">%s<a href="#">%s</a></div>',
            esc_attr( wp_json_encode( array(
                'tag' => 'la_show_posts',
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
    if($enable_carousel != 'yes' && $style == 'pagination'){
        $__url = add_query_arg('la_paged', 999999999, add_query_arg(null,null));
        $_paginate_links = paginate_links( array(
            'base'         => esc_url_raw( str_replace( 999999999, '%#%', $__url ) ),
            'format'       => '?la_paged=%#%',
            'add_args'     => '',
            'current'      => max( 1, $paged ),
            'total'        => $max_paged,
            'prev_text'    => '<i class="fa fa-long-arrow-left"></i>',
            'next_text'    => '<i class="fa fa-long-arrow-right"></i>',
            'type'         => 'list'
        ) );

        echo sprintf('<div class="elm-pagination-ajax" data-query-settings="%s" data-request="%s" data-append-type="replace"
            data-paged="%s" data-parent-container="#%s" data-container="#%s .showposts-loop" data-item-class=".loop__item"><div class="la-loading-icon">%s</div><div class="la-pagination">%s</div></div>',
            esc_attr( wp_json_encode( array(
                'tag' => 'la_show_posts',
                'atts' => $atts
            ))),
            esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
            esc_attr($paged),
            esc_attr($unique_id),
            esc_attr($unique_id),
            LaStudio_Shortcodes_Helper::getLoadingIcon(),
            $_paginate_links
        );
    }

    remove_all_filters('excerpt_length', 1010);
    do_action('LaStudio/shortcodes/after_loop','shortcode', 'la_show_posts', $atts);

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