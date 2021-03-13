<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $wp_query;

$blog_item_space = Draven()->settings()->get('blog_item_space', 'default');

if($blog_item_space == 'zero'){
    $blog_item_space = 0;
}

$blog_design = Draven()->settings()->get('blog_design', 'grid_4');
$blog_columns = wp_parse_args( (array) Draven()->settings()->get('blog_post_column'), array('lg'=> 1,'md'=> 1,'sm'=> 1,'xs'=> 1, 'mb' => 1) );
$blog_masonry = ( Draven()->settings()->get('blog_masonry') == 'on' ) ? true : false;
$blog_pagination_type = Draven()->settings()->get('blog_pagination_type', 'pagination');
$css_classes = array( 'lastudio-posts', 'blog-main-loop' );
$css_classes[] = 'blog-pagination-type-' . $blog_pagination_type;
$css_classes[] = 'blog-' . $blog_design;

$layout = $blog_design;
$style  = str_replace(array('grid_', 'list_'), '', $layout);
$layout = str_replace($style, '', $layout);
$layout = str_replace('_', '', $layout);

$css_classes[] = "$layout-$style";
$css_classes[] = 'showposts-' . $layout;
if($layout == 'grid'){
    $css_classes[] = 'preset-type-' . $style;
}
else{
    $css_classes[] = 'preset-type-list-' . $style;
}


if($layout == 'grid'){
    $css_classes[] = 'grid-items';
    $css_classes[] = 'grid-space-' . $blog_item_space;
    $css_classes[] = draven_render_grid_css_class_from_columns($blog_columns);
}

$data_js_component = array();

if($blog_masonry && $layout == 'grid'){
    $css_classes[] = 'js-el la-isotope-container';
    $data_js_component[] = 'DefaultMasonry';
}

if($blog_pagination_type == 'infinite_scroll'){
    $css_classes[] = 'js-el la-infinite-container';
    $data_js_component[] = 'InfiniteScroll';
}
if($blog_pagination_type == 'load_more'){
    $css_classes[] = 'js-el la-infinite-container infinite-show-loadmore';
    $data_js_component[] = 'InfiniteScroll';
}

$thumbnail_size     = Draven_Helper::get_image_size_from_string(Draven()->settings()->get('blog_thumbnail_size', 'full'), 'full');
$excerpt_length     = Draven()->settings()->get('blog_excerpt_length');
$content_type       = (Draven()->settings()->get('blog_content_display', 'excerpt') == 'excerpt') ? 'excerpt' : 'full';
$show_thumbnail     = (Draven()->settings()->get('featured_images_blog') == 'on') ? true : false;
$height_mode        = 'original';
$thumb_custom_height= '';

draven_set_theme_loop_prop('is_main_loop', true, true);
draven_set_theme_loop_prop('loop_layout', $layout);
draven_set_theme_loop_prop('loop_style', $style);
draven_set_theme_loop_prop('title_tag', 'h2');
draven_set_theme_loop_prop('image_size', $thumbnail_size);
draven_set_theme_loop_prop('excerpt_length', $excerpt_length);
draven_set_theme_loop_prop('content_type', $content_type);
draven_set_theme_loop_prop('show_thumbnail', $show_thumbnail);
draven_set_theme_loop_prop('height_mode', $height_mode);
draven_set_theme_loop_prop('height', $thumb_custom_height);
draven_set_theme_loop_prop('responsive_column', $blog_columns);

?>
<div
    class="<?php echo esc_attr(implode(' ', $css_classes)); ?>"
    <?php if(!empty($data_js_component)) echo 'data-la_component="'. esc_attr(json_encode($data_js_component)) .'"'; ?>
    data-item_selector=".loop__item"
    data-page_num="<?php echo esc_attr( get_query_var('paged') ? get_query_var('paged') : 1 ) ?>"
    data-page_num_max="<?php echo esc_attr( $wp_query->max_num_pages ? $wp_query->max_num_pages : 1 ) ?>"
>