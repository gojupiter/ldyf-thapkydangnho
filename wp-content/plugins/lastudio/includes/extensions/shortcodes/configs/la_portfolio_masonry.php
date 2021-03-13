<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(

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
            __( 'Date', 'lastudio' ) => 'date',
            __( 'ID', 'lastudio' ) => 'ID',
            __( 'Author', 'lastudio' ) => 'author',
            __( 'Title', 'lastudio' ) => 'title',
            __( 'Modified', 'lastudio' ) => 'modified',
            __( 'Random', 'lastudio' ) => 'rand',
            __( 'Comment count', 'lastudio' ) => 'comment_count',
            __( 'Menu order', 'lastudio' ) => 'menu_order',
        ),
        'default' => 'date',
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Sort order', 'lastudio' ),
        'param_name' => 'order',
        'default' => 'desc',
        'value' => array(
            __( 'Descending', 'lastudio' ) => 'desc',
            __( 'Ascending', 'lastudio' ) => 'asc',
        ),
        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type' => 'la_number',
        'heading' => __('Items per page', 'lastudio'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'lastudio'),
        'param_name' => 'per_page',
        'value' => 10,
        'min' => -1,
        'max' => 1000,
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'lastudio'),
        'param_name' => 'paged',
        'value' => '1',
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Skill Filter', 'lastudio' ),
        'param_name' => 'enable_skill_filter',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Load More', 'lastudio' ),
        'param_name' => 'enable_loadmore',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'group' => __('Query Settings', 'lastudio')
    ),
    array(
        'type' => 'textfield',
        'heading' => __('Load More Text', 'lastudio'),
        'param_name' => 'load_more_text',
        'value' => __('Load more', 'lastudio'),
        'dependency' => array( 'element' => 'enable_loadmore', 'value' => 'yes' ),
        'group' => __('Query Settings', 'lastudio')
    ),

    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Skill Filter', 'lastudio' ),
        'param_name' => 'filters',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'dependency' => array(
            'element'   => 'enable_skill_filter',
            'value'     => 'yes'
        ),
        'group' => __('Skill Filters', 'lastudio'),
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Filter style','lastudio'),
        'param_name' => 'filter_style',
        'value' => array(
            __('Style 01','lastudio') => '1',
            __('Style 02','lastudio') => '2',
            __('Style 03','lastudio') => '3'
        ),
        'std' => '1',
        'dependency' => array(
            'element'   => 'enable_skill_filter',
            'value'     => 'yes'
        ),
        'group' => __('Skill Filters', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Design','lastudio'),
        'param_name' => 'masonry_style',
        'value' => array(
            __('Design 01','lastudio') => '1',
            __('Design 02','lastudio') => '2',
            __('Design 03','lastudio') => '3',
            __('Design 04','lastudio') => '4',
            __('Design 05','lastudio') => '5',
            __('Design 06','lastudio') => '6',
            __('Design 07','lastudio') => '7',
            __('Design 08','lastudio') => '8'
        ),
        'std' => '1',
        'group' => __('Item Settings', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Item Title HTML Tag','lastudio'),
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
        'default' => 'h3',
        'description' => __('Default is H3', 'lastudio'),
        'group' => __('Item Settings', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Image Height Mode','lastudio'),
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
        'description' => __('Sizing proportions for height and width. Select "Original" to scale image without cropping.( This option may not work if value of "Column Type" is "Custom" )', 'lastudio'),
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

    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Image Size', 'lastudio'),
        'param_name' 	=> 'img_size',
        'value'			=> 'full',
        'description' 	=> __('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'lastudio'),
        'group' => __('Item Settings', 'lastudio')
    ),

    LaStudio_Shortcodes_Helper::getParamItemSpace(array(
        'group' => __('Item Settings', 'lastudio')
    )),

    array(
        'type' => 'dropdown',
        'heading' => __('Column Type','lastudio'),
        'param_name' => 'column_type',
        'value' => array(
            __('Default','lastudio') => 'default',
            __('Custom','lastudio') => 'custom',
        ),
        'default' => 'default',
        'group' => __('Item Settings', 'lastudio')
    ),
    array(
        'type' 			=> 'la_column',
        'heading' 		=> __('Responsive Column', 'lastudio'),
        'param_name' 	=> 'column',
        'unit'			=> '',
        'media'			=> array(
            'xlg'	=> 1,
            'lg'	=> 1,
            'md'	=> 1,
            'sm'	=> 1,
            'xs'	=> 1,
            'mb'	=> 1
        ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'default'
        ),
        'group' => __('Item Settings', 'lastudio')
    ),

    array(
        'type' => 'la_number',
        'heading' => __('Container Width', 'lastudio'),
        'param_name' => 'base_container_w',
        'description' => __('This value will determine the number of items per row', 'lastudio'),
        'value' => 1170,
        'min' => 800,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'    => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'lastudio')
    ),

    array(
        'type' => 'la_number',
        'heading' => __('Portfolio Item Width', 'lastudio'),
        'param_name' => 'base_item_w',
        'description' => __('Set your portfolio item default width', 'lastudio'),
        'value' => 400,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'lastudio')
    ),

    array(
        'type' => 'la_number',
        'heading' => __('Portfolio Item Height', 'lastudio'),
        'description' => __('Set your portfolio item default height', 'lastudio'),
        'param_name' => 'base_item_h',
        'value' => 400,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'lastudio')
    ),

    array(
        'type' 			=> 'la_column',
        'heading' 		=> __('Mobile Column', 'lastudio'),
        'param_name' 	=> 'mb_column',
        'unit'			=> '',
        'media'			=> array(
            'md'	=> 1,
            'sm'	=> 1,
            'xs'	=> 1,
            'mb'	=> 1
        ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'lastudio')
    ),

    array(
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Custom Item Setting', 'lastudio' ),
        'param_name' => 'custom_item_size',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'lastudio')
    ),
    array(
        'type' => 'param_group',
        'param_name' => 'item_sizes',
        'heading' => __( 'Item Sizes', 'lastudio' ),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => __('Width','lastudio'),
                'description' 	=> __('it will occupy x width of base item width ( example: this item will be occupy 2x width of base width you need entered "2")', 'lastudio'),
                'param_name' => 'w',
                'admin_label' => true,
                'value' => array(
                    __('1/2x width','lastudio')    => '0.5',
                    __('1x width','lastudio')      => '1',
                    __('1.5x width','lastudio')    => '1.5',
                    __('2x width','lastudio')      => '2',
                    __('2.5x width','lastudio')    => '2.5',
                    __('3x width','lastudio')      => '3',
                    __('3.5x width','lastudio')    => '3.5',
                    __('4x width','lastudio')      => '4',
                ),
                'default' => '1'
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Height','lastudio'),
                'description' 	=> __('it will occupy x height of base item height ( example: this item will be occupy 2x height of base height you need entered "2")', 'lastudio'),
                'param_name' => 'h',
                'admin_label' => true,
                'value' => array(
                    __('1/2x height','lastudio')    => '0.5',
                    __('1x height','lastudio')      => '1',
                    __('1.5x height','lastudio')    => '1.5',
                    __('2x height','lastudio')      => '2',
                    __('2.5x height','lastudio')    => '2.5',
                    __('3x height','lastudio')      => '3',
                    __('3.5x height','lastudio')    => '3.5',
                    __('4x height','lastudio')      => '4',
                ),
                'default' => '1'
            )
        ),
        'dependency' => array(
            'element'   => 'custom_item_size',
            'value'     => 'yes'
        ),
        'group' => __('Item Settings', 'lastudio')
    ),
    LaStudio_Shortcodes_Helper::fieldElementID(array(
        'group'         => __('Query Settings', 'lastudio')
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'group'         => __('Query Settings', 'lastudio')
    ))
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Portfolio Masonry', 'lastudio'),
        'base'			=> 'la_portfolio_masonry',
        'icon'          => 'la-wpb-icon la_portfolio_masonry',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display portfolio with la-studio themes style.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_portfolio_masonry'
);