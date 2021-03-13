<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
//    array(
//        'type' => 'hidden',
//        'heading' => __('Layout', 'lastudio'),
//        'param_name' => 'layout',
//        'value' => 'blog'
//    ),
//    array(
//        'type' => 'dropdown',
//        'heading' => __('Layout','lastudio'),
//        'param_name' => 'layout',
//        'value' => array(
//            __('List','lastudio') => 'list',
//            __('Grid','lastudio') => 'grid',
//            __('Masonry', 'lastudio') => 'masonry',
//            __('Special', 'lastudio') => 'special'
//        ),
//        'default' => 'grid'
//    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Layout','lastudio'),
        'param_name' => 'layout',
        'value' => array(
            __('Grid','lastudio') => 'grid',
            __('List','lastudio') => 'list',
            __('Masonry', 'lastudio') => 'masonry'
        ),
        'std' => 'grid'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'blog_style',
        'value' => array(
            __('Style 01','lastudio') => '1',
            __('Style 02','lastudio') => '2',
            __('Style 03','lastudio') => '3',
            __('Style 04','lastudio') => '4',
            __('Style 05','lastudio') => '5'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'blog'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'list_style',
        'value' => array(
            __('Style 01','lastudio')   => '1',
            __('Style 02','lastudio')   => '2',
            __('Style 03','lastudio')   => '3',
            __('Mini Style','lastudio')   => 'mini',
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'list'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'grid_style',
        'value' => array(
            __('Style 01','lastudio') => '1',
            __('Style 02','lastudio') => '2',
            __('Style 03','lastudio') => '3',
            __('Style 04','lastudio') => '4',
            __('Style 04 has description','lastudio') => '6',
            __('Style 05','lastudio') => '5',
            __('Style Slider','lastudio') => 'slider'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'masonry_style',
        'value' => array(
            __('Style 01','lastudio') => '1',
            __('Style 02','lastudio') => '2',
            __('Style 03','lastudio') => '3',
            __('Style 04','lastudio') => '4',
            __('Style 05','lastudio') => '5'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'masonry'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'special_style',
        'value' => array(
            __('Default', 'lastudio') => '1'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'special'
        ),
        'std' => '1'
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Category In:', 'lastudio' ),
        'param_name' => 'category__in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Category Not In:', 'lastudio' ),
        'param_name' => 'category__not_in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Post In:', 'lastudio' ),
        'param_name' => 'post__in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Post Not In:', 'lastudio' ),
        'param_name' => 'post__not_in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Order by', 'lastudio' ),
        'param_name' => 'orderby',
        'value' => array(
            '',
            __( 'Date', 'lastudio' ) => 'date',
            __( 'ID', 'lastudio' ) => 'ID',
            __( 'Author', 'lastudio' ) => 'author',
            __( 'Title', 'lastudio' ) => 'title',
            __( 'Modified', 'lastudio' ) => 'modified',
            __( 'Random', 'lastudio' ) => 'rand',
            __( 'Comment count', 'lastudio' ) => 'comment_count',
            __( 'Menu order', 'lastudio' ) => 'menu_order',
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Sort order', 'lastudio' ),
        'param_name' => 'order',
        'value' => array(
            '',
            __( 'Descending', 'lastudio' ) => 'DESC',
            __( 'Ascending', 'lastudio' ) => 'ASC',
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Item title tag','lastudio'),
        'param_name' => 'title_tag',
        'value' => array(
            __('Default','lastudio') => 'h3',
            __('H1','lastudio') => 'h1',
            __('H2','lastudio') => 'h2',
            __('H4','lastudio') => 'h4',
            __('H5','lastudio') => 'h5',
            __('H6','lastudio') => 'h6',
            __('DIV','lastudio') => 'div',
        ),
        'std' => 'h3',
        'description' => __('Default is H3', 'lastudio'),
        'group' => __('Item Settings', 'lastudio')
    ),
    array(
        'type' => 'la_number',
        'heading' => __('Excerpt Length', 'lastudio'),
        'param_name' => 'excerpt_length',
        'value' => 20,
        'min' => 1,
        'max' => 100,
        'suffix' => '',
        'group' => __('Item Settings', 'lastudio')
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Height Mode','lastudio'),
        'param_name' => 'height_mode',
        'value' => array(
            __('1:1','lastudio') => '1-1',
            __('Original','lastudio') => 'original',
            __('4:3','lastudio') => '4-3',
            __('3:4','lastudio') => '3-4',
            __('16:9','lastudio') => '16-9',
            __('9:16','lastudio') => '9-16',
            __('Custom','lastudio') => 'custom',
        ),
        'std' => 'original',
        'description' => __('Sizing proportions for height and width. Select "Original" to scale image without cropping.', 'lastudio'),
        'group' => __('Item Settings', 'lastudio')
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Height', 'lastudio'),
        'param_name' 	=> 'height',
        'value'			=> '',
        'description' 	=> __('Enter custom height.', 'lastudio'),
        'group'         => __('Item Settings', 'lastudio'),
        'dependency' => array(
            'element'   => 'height_mode',
            'value'     => array('custom')
        )
    ),
    LaStudio_Shortcodes_Helper::fieldImageSize(array(
        'group' => __('Item Settings', 'lastudio')
    )),

    LaStudio_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'lastudio'),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid','masonry','blog')
        ),
    )),
    LaStudio_Shortcodes_Helper::getParamItemSpace(array(
        'std'        => 'default',
        'dependency' => array(
            'element'=> 'layout',
            'value'  => array('blog', 'grid', 'masonry')
        )
    )),
    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'lastudio' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid','blog')
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Display Style','lastudio'),
        'description' => __('Select display style for grid.', 'lastudio'),
        'param_name' => 'style',
        'value' => array(
            __('Show all','lastudio') => 'all',
            __('Load more button','lastudio') => 'load-more',
            __('Pagination','lastudio') => 'pagination',
        ),
        'std' => 'all',
        'dependency' => array(
            'element'=> 'layout',
            'value'  => array('blog', 'grid', 'masonry', 'list')
        )
    ),

    array(
        'type' => 'la_number',
        'heading' => __('Number items showing', 'lastudio'),
        'param_name' => 'items_per_page',
        'description' => __('Number of items to show per page.', 'lastudio'),
        'value' => 4,
        'min' => 1,
        'max' => 1000,
        'dependency' => array(
            'element'   => 'style',
            'value'     => array('load-more','pagination')
        )
    ),

    array(
        'type' => 'la_number',
        'heading' => __('Total items', 'lastudio'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'lastudio'),
        'param_name' => 'per_page',
        'value' => 4,
        'min' => -1,
        'max' => 1000
    ),
    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'lastudio'),
        'param_name' => 'paged',
        'value' => '1'
    ),

    array(
        'type' => 'textfield',
        'heading' => __('Load more text', 'lastudio'),
        'param_name' => 'load_more_text',
        'value' => 'Read more',
        'dependency' => array(
            'element'   => 'style',
            'value'     => 'load-more'
        )
    ),

    LaStudio_Shortcodes_Helper::fieldExtraClass()
);

$carousel = LaStudio_Shortcodes_Helper::paramCarouselShortCode(false);
$slides_column_idx = LaStudio_Shortcodes_Helper::getParamIndex( $carousel, 'slides_column');
if($slides_column_idx){
    unset($carousel[$slides_column_idx]);
}

$shortcode_params = array_merge( $shortcode_params, $carousel);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Blog Posts', 'lastudio'),
        'base'			=> 'la_show_posts',
        'icon'          => 'icon-wpb-application-icon-large',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display posts with la-studio themes style.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_show_posts'
);