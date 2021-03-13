<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$output = $excerpt_length = '';
$per_page = '';
$style = $ids = $enable_carousel = $column = $img_size = $el_class = $title_tag = '';
$enable_loadmore = false;
$paged = 1;
$load_more_text = __('Load More', 'lastudio');
$item_space = $el_id = '';
$slider_type = $cache = $height_mode = $height = '';
$atts = shortcode_atts( array(
    'style' => '1',
    'ids' => '',
    'per_page' => 4,
    'column' => '',
    'item_space' => 'default',
    'enable_carousel' => '',
    'img_size' => 'thumbnail',
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
    'el_id' => '',
    'cache' => 'yes',
    'height_mode' => 'original',
    'height' => '',
    'title_tag' => 'h3',
    'excerpt_length' => 10,
    'item_animation' => ''
), $atts );

extract( $atts );

$_tmp_class = 'la-team-members';
$el_class = $_tmp_class . LaStudio_Shortcodes_Helper::getExtraClass($el_class);

if(!empty($ids)){
    $ids = explode(',', $ids);
    $ids = array_map('trim', $ids);
    $ids = array_map('absint', $ids);
}

$unique_id = !empty($el_id) ? esc_attr($el_id) : uniqid('la_team_');
$query_args = array(
    'post_type' => 'la_team_member',
    'posts_per_page' => $per_page,
    'paged'=> $paged
);
if(!empty($ids)){
    $query_args['post__in'] = $ids;
    $query_args['orderby'] = 'post__in';
}

$globalVar = apply_filters('LaStudio/global_loop_variable', 'lastudio_loop');

$globalVarTmp = (isset($GLOBALS[$globalVar]) ? $GLOBALS[$globalVar] : '');
$globalParams = array();
$globalParams['loop_id'] = $unique_id;
$globalParams['loop_style'] = $style;
$globalParams['responsive_column'] = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($column);
$globalParams['image_size'] = LaStudio_Shortcodes_Helper::getImageSizeFormString($img_size);
$globalParams['title_tag'] = $title_tag;
$globalParams['item_space'] = $item_space;
$globalParams['excerpt_length'] = $excerpt_length;
$globalParams['height_mode'] = $height_mode;
$globalParams['height'] = $height;
if($enable_carousel){
    $globalParams['slider_configs'] = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts, 'column');
    if(!empty($item_animation) && $item_animation != 'none' && $centermode != 'yes' && $autowidth != 'yes' && $slider_type == 'horizontal'){
        $globalParams['slider_css_class'] = ' laslick_has_animation laslick_'. $item_animation;
    }
}


$cache_query = la_get_shortcode_loop_query_results('members', $atts, $query_args, 'la_team_member');

$start_tpl = $end_tpl = $loop_tpl = array();
$start_tpl[] = "templates/members/start-{$style}.php";
$start_tpl[] = "templates/members/start.php";
$loop_tpl[] = "templates/members/loop-{$style}.php";
$loop_tpl[] = "templates/members/loop.php";
$end_tpl[] = "templates/members/end-{$style}.php";
$end_tpl[] = "templates/members/end.php";

ob_start();

if ( $cache_query && $cache_query->ids ) {
    update_meta_cache( 'post', $cache_query->ids );

    $max_paged = $cache_query->total_pages;
    $max_posts = $cache_query->total;

    $GLOBALS[$globalVar] = $globalParams;
    $original_post = !empty($GLOBALS['post']) ? $GLOBALS['post'] : '';

    add_filter('excerpt_length', function() use ( $excerpt_length ) {
        return absint($excerpt_length);
    }, 1010);

    do_action('LaStudio/shortcodes/before_loop', 'shortcode', 'la_team_member', $atts);

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

    if($enable_loadmore){
        echo sprintf(
            '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s .team__members" data-item-class=".team__member">%s<a href="#">%s</a></div>',
            esc_attr( wp_json_encode( array(
                'tag' => 'la_team_member',
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
    do_action('LaStudio/shortcodes/after_loop','shortcode', 'la_team_member', $atts);

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