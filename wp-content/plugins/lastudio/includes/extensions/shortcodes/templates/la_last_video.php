<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$layout = $grid_style = $category__in = $category__not_in = $tag__in = $tag__not_in = $video__in = $video__not_in = $orderby = $order = $title_tag = $height_mode = $height = $img_size = $column = $item_space = $enable_carousel = $style = $items_per_page = $per_page = $paged = $load_more_text = $el_class = $slider_type = $slide_to_scroll = $infinite_loop = $speed = $autoplay = $autoplay_speed = $arrows = $arrow_style = $arrow_bg_color = $arrow_border_color = $border_size = $arrow_color = $arrow_size = $next_icon = $prev_icon = $custom_nav = $dots = $dots_color = $dots_icon = $draggable = $touch_move = $rtl = $adaptive_height = $pauseohover = $centermode = $autowidth = '';

$item_animation = $cache = $shortcode_id = '';

$atts = shortcode_atts( array(
    'layout' => 'grid',
    'grid_style' => '1',
    'category__in' => '',
    'category__not_in' => '',
    'tag__in' => '',
    'tag__not_in' => '',
    'video__in' => '',
    'video__not_in' => '',
    'orderby' => '',
    'order' => '',
    'title_tag' => 'h3',
    'height_mode' => 'original',
    'height' => '',
    'img_size' => 'thumbnail',
    'column' => '',
    'item_space' => 'default',
    'enable_carousel' => '',
    'style' => 'all',
    'items_per_page' => 4,
    'per_page' => 4,
    'paged' => '1',
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
    'item_animation' => ''
), $atts );


$excerpt_length = 15;

if( 0 === $per_page ) $per_page = 1;
if(empty($paged)){
    $paged = 1;
}

extract( $atts );

$_tmp_class = 'la-show-videos';
$el_class = $_tmp_class . LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$unique_id = !empty($shortcode_id) ? $shortcode_id : uniqid('la_videos_');

$query_args = array(
    'post_type'             => 'la_video',
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
if ( $tag__in ) {
    $tag__in = explode( ',', $tag__in );
    $tag__in = array_map( 'trim', $tag__in );
}
if ( $tag__not_in ) {
    $tag__not_in = explode( ',', $tag__not_in );
    $tag__not_in = array_map( 'trim', $tag__not_in );
}

if ( $video__in ) {
    $video__in = explode( ',', $video__in );
    $video__in = array_map( 'trim', $video__in );
}
if ( $tag__not_in ) {
    $tag__not_in = explode( ',', $tag__not_in );
    $tag__not_in = array_map( 'trim', $tag__not_in );
}
$tax_query = array();

if ( !empty( $category__in ) && !empty( $category__not_in ) ){
    $tax_query['relation'] = 'AND';
}
if ( !empty ( $category__in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'video_type',
        'field'    => 'term_id',
        'terms'    => $category__in
    );
}
if ( !empty ( $category__not_in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'video_type',
        'field'    => 'term_id',
        'terms'    => $category__not_in,
        'operator' => 'NOT IN'
    );
}
if ( !empty ( $tag__in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'video_tag',
        'field'    => 'term_id',
        'terms'    => $tag__in
    );
}
if ( !empty ( $tag__not_in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'video_tag',
        'field'    => 'term_id',
        'terms'    => $tag__not_in,
        'operator' => 'NOT IN'
    );
}
if ( !empty($tax_query) ) {
    $query_args['tax_query'] = $tax_query;
}
if ( !empty ( $video__in ) ) {
    $query_args['post__in'] = $video__in;
}
if ( !empty ( $video__not_in ) ) {
    $query_args['post__not_in'] = $video__not_in;
}

$globalVar = apply_filters('LaStudio/global_loop_variable', 'lastudio_loop');
$globalVarTmp = (isset($GLOBALS[$globalVar]) ? $GLOBALS[$globalVar] : '');
$globalParams = array();

$layout_style = ${$layout . '_style'};

$globalParams['loop_id'] = $unique_id;
$globalParams['item_space']  = $item_space;
$globalParams['loop_layout'] = $layout;
$globalParams['loop_style'] = $layout_style;
$globalParams['responsive_column'] = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($column);
$globalParams['image_size'] = LaStudio_Shortcodes_Helper::getImageSizeFormString($img_size);
$globalParams['title_tag'] = $title_tag;
$globalParams['excerpt_length'] = $excerpt_length;
$globalParams['height_mode'] = $height_mode;
$globalParams['height'] = esc_attr($height);
if($enable_carousel) {
    $globalParams['slider_configs'] = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts, 'column');
    if(!empty($item_animation) && $item_animation != 'none' && $centermode != 'yes' && $autowidth != 'yes' && $slider_type == 'horizontal'){
        $globalParams['slider_css_class'] = ' laslick_has_animation laslick_'. $item_animation;
    }
}
$cache_query = la_get_shortcode_loop_query_results('show_la_video', $atts, $query_args, 'la_last_video');

$start_tpl = $end_tpl = $loop_tpl = array();
$start_tpl[] = "templates/videos/loop/{$layout}/start-{$layout_style}.php";
$start_tpl[] = "templates/videos/loop/{$layout}/start.php";
$start_tpl[] = "templates/videos/loop/start-{$layout}.php";
$start_tpl[] = "templates/videos/loop/start.php";
$loop_tpl[] = "templates/videos/loop/{$layout}/loop-{$layout_style}.php";
$loop_tpl[] = "templates/videos/loop/{$layout}/loop.php";
$loop_tpl[] = "templates/videos/loop/loop-{$layout}.php";
$loop_tpl[] = "templates/videos/loop/loop.php";
$end_tpl[] = "templates/videos/loop/{$layout}/end-{$layout_style}.php";
$end_tpl[] = "templates/videos/loop/{$layout}/end.php";
$end_tpl[] = "templates/videos/loop/end-{$layout}.php";
$end_tpl[] = "templates/videos/loop/end.php";

ob_start();

if ( $cache_query && $cache_query->ids ) {

    update_meta_cache( 'post', $cache_query->ids );
    update_object_term_cache( $cache_query->ids, 'la_video' );


    $max_paged = $cache_query->total_pages;
    $max_posts = $cache_query->total;

    $GLOBALS[$globalVar] = $globalParams;

    $original_post = !empty($GLOBALS['post']) ? $GLOBALS['post'] : '';

    add_filter('excerpt_length', function() use ( $excerpt_length ) {
        return absint($excerpt_length);
    }, 1010);

    do_action('LaStudio/shortcodes/before_loop', 'shortcode', 'la_last_video', $atts);

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
            '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s .videos-loop" data-item-class=".video__item">%s<a href="#">%s</a></div>',
            esc_attr( wp_json_encode( array(
                'tag' => 'la_last_video',
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
            data-paged="%s" data-parent-container="#%s" data-container="#%s .videos-loop" data-item-class=".video__item"><div class="la-loading-icon">%s</div><div class="la-pagination">%s</div></div>',
            esc_attr( wp_json_encode( array(
                'tag' => 'la_last_video',
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
    do_action('LaStudio/shortcodes/after_loop','shortcode', 'la_last_video', $atts);

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