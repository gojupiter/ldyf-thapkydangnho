<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * Blog settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function draven_options_section_blog( $sections )
{
    $sections['blog'] = array(
        'name'          => 'blog_panel',
        'title'         => esc_html_x('Blog', 'admin-view', 'draven'),
        'icon'          => 'fa fa-newspaper-o',
        'sections' => array(
            array(
                'name'      => 'blog_general_section',
                'title'     => esc_html_x('General Blog', 'admin-view', 'draven'),
                'icon'      => 'fa fa-check',
                'fields'    => array(
                    array(
                        'id'        => 'layout_blog',
                        'type'      => 'image_select',
                        'title'     => esc_html_x('Blog Page Layout', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'admin-view', 'draven'),
                        'default'   => 'col-1c',
                        'radio'     => true,
                        'options'   => Draven_Options::get_config_main_layout_opts(true, true)
                    ),
                    array(
                        'id'        => 'blog_small_layout',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'off',
                        'title'     => esc_html_x('Enable Small Layout', 'admin-view', 'draven'),
                        'dependency' => array('layout_blog_col-1c', '==', 'true'),
                        'options'   => array(
                            'on'        => esc_html_x('On', 'admin-view', 'draven'),
                            'off'       => esc_html_x('Off', 'admin-view', 'draven')
                        )
                    ),
                    array(
                        'id'        => 'page_title_bar_layout_blog_global',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'off',
                        'title'     => esc_html_x('Page Title Bar', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to show the page title bar for the assigned blog page in "settings > reading" or blog archive pages', 'admin-view', 'draven'),
                        'options'   => array(
                            'on'        => esc_html_x('On', 'admin-view', 'draven'),
                            'off'       => esc_html_x('Off', 'admin-view', 'draven')
                        )
                    ),
                    array(
                        'id'        => 'blog_design',
                        'default'   => 'grid_1',
                        'title'     => esc_html_x('Blog Design', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Controls the layout for the assigned blog page in "settings > reading" or blog archive pages', 'admin-view', 'draven'),
                        'type'      => 'select',
                        'options'   => array(
                            'list_1'        => esc_html_x('List Style 01', 'admin-view', 'draven'),
                            'grid_1'        => esc_html_x('Grid Style 01', 'admin-view', 'draven'),
                            'grid_2'        => esc_html_x('Grid Style 02', 'admin-view', 'draven'),
                            'grid_3'        => esc_html_x('Grid Style 03', 'admin-view', 'draven'),
                            'grid_4'        => esc_html_x('Grid Style 04', 'admin-view', 'draven'),
                            'grid_5'        => esc_html_x('Grid Style 05', 'admin-view', 'draven'),
                            'grid_6'        => esc_html_x('Grid Style 05', 'admin-view', 'draven'),
                        )
                    ),
                    array(
                        'id'      => 'blog_post_column',
                        'default'   => array(
                            'xlg' => 1,
                            'lg' => 1,
                            'md' => 1,
                            'sm' => 1,
                            'xs' => 1,
                            'mb' => 1
                        ),
                        'title'     => esc_html_x('Blog Post Columns', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Controls the amount of columns for the grid layout when using it for the assigned blog page in "settings > reading" or blog archive pages or search results page.', 'admin-view', 'draven'),
                        'type'      => 'column_responsive',
                        'dependency' => array('blog_design', 'any', 'grid_1,grid_2,grid_3,grid_4,grid_5,grid_6'),
                    ),
                    array(
                        'id'        => 'blog_item_space',
                        'default'   => 'default',
                        'title'     => esc_html_x('Blog Item Space', 'admin-view', 'draven'),
                        'type'      => 'select',
                        'options'   => array(
                            'default'    => esc_html_x('Default', 'admin-view', 'draven'),
                            'zero'       => esc_html_x('0px', 'admin-view', 'draven'),
                            '5'          => esc_html_x('5px', 'admin-view', 'draven'),
                            '10'         => esc_html_x('10px', 'admin-view', 'draven'),
                            '15'         => esc_html_x('15px', 'admin-view', 'draven'),
                            '20'         => esc_html_x('20px', 'admin-view', 'draven'),
                            '25'         => esc_html_x('25px', 'admin-view', 'draven'),
                            '30'         => esc_html_x('30px', 'admin-view', 'draven'),
                            '35'         => esc_html_x('35px', 'admin-view', 'draven'),
                            '40'         => esc_html_x('40px', 'admin-view', 'draven'),
                            '45'         => esc_html_x('45px', 'admin-view', 'draven'),
                            '50'         => esc_html_x('50px', 'admin-view', 'draven'),
                            '55'         => esc_html_x('55px', 'admin-view', 'draven'),
                            '60'         => esc_html_x('60px', 'admin-view', 'draven'),
                            '65'         => esc_html_x('65px', 'admin-view', 'draven'),
                            '70'         => esc_html_x('70px', 'admin-view', 'draven'),
                            '75'         => esc_html_x('75px', 'admin-view', 'draven'),
                            '80'         => esc_html_x('80px', 'admin-view', 'draven'),
                        ),
                        'dependency' => array('blog_design', 'any', 'grid_1,grid_2,grid_3,grid_4,grid_5,grid_6'),
                    ),
                    array(
                        'id'        => 'featured_images_blog',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'on',
                        'title'     => esc_html_x('Featured Image on Blog Archive Page', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to display featured images on the blog or archive pages.', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false)
                    ),

                    array(
                        'id'        => 'blog_thumbnail_height_mode',
                        'default'   => 'original',
                        'title'     => esc_html_x('Blog Image Height Mode', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Sizing proportions for height and width. Select "Original" to scale image without cropping.', 'admin-view', 'draven'),
                        'type'      => 'select',
                        'options'   => array(
                            '1-1'       => esc_html_x('1-1', 'admin-view', 'draven'),
                            'original'  => esc_html_x('Original', 'admin-view', 'draven'),
                            '4-3'       => esc_html_x('4:3', 'admin-view', 'draven'),
                            '3-4'       => esc_html_x('3:4', 'admin-view', 'draven'),
                            '16-9'      => esc_html_x('16:9', 'admin-view', 'draven'),
                            '9-16'      => esc_html_x('9:16', 'admin-view', 'draven'),
                            'custom'    => esc_html_x('Custom', 'admin-view', 'draven')
                        )
                    ),

                    array(
                        'id'        => 'blog_thumbnail_height_custom',
                        'type'      => 'text',
                        'default'   => '50%',
                        'title'     => esc_html_x('Blog Image Height Custom', 'admin-view', 'draven'),
                        'dependency'=> array('blog_thumbnail_height_mode', '==', 'custom'),
                        'desc'      => esc_html_x('Enter custom height.', 'admin-view', 'draven')
                    ),

                    array(
                        'id'        => 'blog_thumbnail_size',
                        'default'   => 'full',
                        'title'     => esc_html_x('Blog Image Size', 'admin-view', 'draven'),
                        'dependency' => array('featured_images_blog_on', '==', 'true'),
                        'type'      => 'select',
                        'options'   => draven_get_list_image_sizes()
                    ),

                    array(
                        'id'        => 'blog_content_display',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'excerpt',
                        'title'     => esc_html_x('Blog Content Display', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Controls if the blog content displays an excerpt or full content for the assigned blog page in "settings > reading" or blog archive pages.', 'admin-view', 'draven'),
                        'options'   => array(
                            'excerpt'   => esc_html_x('Excerpt', 'admin-view', 'draven')
                        )
                    ),
                    array(
                        'id'        => 'blog_excerpt_length',
                        'type'      => 'slider',
                        'default'   => 20,
                        'title'     => esc_html_x( 'Excerpt Length', 'admin-view', 'draven' ),
                        'desc'      => esc_html_x('Controls the number of words in the post excerpts for the assigned blog page in "settings > reading" or blog archive pages.', 'admin-view', 'draven'),
                        'options'   => array(
                            'step'    => 1,
                            'min'     => 0,
                            'max'     => 500,
                            'unit'    => ''
                        ),
                        'dependency' => array('blog_content_display_excerpt', '==', 'true')
                    ),
                    array(
                        'id'        => 'blog_masonry',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'off',
                        'title'     => esc_html_x('Enable Blog Masonry', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false),
                        'dependency' => array('blog_design', 'any', 'grid_1,grid_2,grid_3,grid_4,grid_5,grid_6'),
                    ),
                    array(
                        'id'        => 'blog_pagination_type',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'pagination',
                        'title'     => esc_html_x('Pagination Type', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Controls the pagination type for the assigned blog page in "settings > reading" or blog pages.', 'admin-view', 'draven'),
                        'options'   => array(
                            'pagination' => esc_html_x('Pagination', 'admin-view', 'draven'),
                            'infinite_scroll' => esc_html_x('Infinite Scroll', 'admin-view', 'draven'),
                            'load_more' => esc_html_x('Load More Button', 'admin-view', 'draven')
                        )
                    )
                )
            ),
            array(
                'name'      => 'blog_single_section',
                'title'     => esc_html_x('Blog Single Post', 'admin-view', 'draven'),
                'icon'      => 'fa fa-check',
                'fields'    => array(
                    array(
                        'id'        => 'layout_single_post',
                        'type'      => 'image_select',
                        'title'     => esc_html_x('Single Page Layout', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'admin-view', 'draven'),
                        'default'   => 'inherit',
                        'radio'     => true,
                        'options'   => Draven_Options::get_config_main_layout_opts(true, true)
                    ),
                    array(
                        'id'        => 'single_small_layout',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'off',
                        'title'     => esc_html_x('Enable Small Layout', 'admin-view', 'draven'),
                        'dependency' => array('layout_single_post_col-1c', '==', 'true'),
                        'options'   => array(
                            'on'        => esc_html_x('On', 'admin-view', 'draven'),
                            'off'       => esc_html_x('Off', 'admin-view', 'draven')
                        )
                    ),
                    array(
                        'id'        => 'featured_images_single',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'off',
                        'title'     => esc_html_x('Featured Image / Video on Single Blog Post', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to display featured images and videos on single blog posts.', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false)
                    ),

                    array(
                        'id'        => 'single_post_thumbnail_size',
                        'default'   => 'full',
                        'title'     => esc_html_x('Blog Image Size', 'admin-view', 'draven'),
                        'dependency' => array('featured_images_blog_on', '==', 'true'),
                        'type'      => 'select',
                        'options'   => draven_get_list_image_sizes()
                    ),

                    array(
                        'id'        => 'blog_pn_nav',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'on',
                        'title'     => esc_html_x('Previous/Next Pagination', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to display the previous/next post pagination for single blog posts.', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false)
                    ),
                    array(
                        'id'        => 'blog_post_title',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'below',
                        'title'     => esc_html_x('Post Title', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Controls if the post title displays above or below the featured post image or is disabled.', 'admin-view', 'draven'),
                        'options'   => array(
                            'below'        => esc_html_x('Below', 'admin-view', 'draven'),
                            'above'        => esc_html_x('Above', 'admin-view', 'draven'),
                            'off'          => esc_html_x('Disabled', 'admin-view', 'draven')
                        )
                    ),
                    array(
                        'id'        => 'blog_author_info',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'wrap_class'=> 'hidden',
                        'default'   => 'on',
                        'title'     => esc_html_x('Author Info Box', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to display the author info box below posts.', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false)
                    ),
                    array(
                        'id'        => 'blog_social_sharing_box',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'on',
                        'title'     => esc_html_x('Social Sharing Box', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to display the social sharing box.', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false)
                    ),
                    array(
                        'id'        => 'blog_related_posts',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'on',
                        'title'     => esc_html_x('Related Posts', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to display related posts.', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false)
                    ),
                    array(
                        'id'        => 'blog_related_design',
                        'default'   => '1',
                        'title'     => esc_html_x('Related Design', 'admin-view', 'draven'),
                        'type'      => 'select',
                        'options'   => array(
                            '1'        => esc_html_x('Style 1', 'admin-view', 'draven'),
                            '2'        => esc_html_x('Style 2', 'admin-view', 'draven'),
                            '3'        => esc_html_x('Style 3', 'admin-view', 'draven'),
                            '4'        => esc_html_x('Style 4', 'admin-view', 'draven'),
                            '5'        => esc_html_x('Style 5', 'admin-view', 'draven')
                        ),
                        'dependency' => array('blog_related_posts_on', '==', 'true'),
                        /*'wrap_class' => 'force-hidden'*/
                    ),
                    array(
                        'id'        => 'blog_related_by',
                        'default'   => 'random',
                        'title'     => esc_html_x('Related Posts By', 'admin-view', 'draven'),
                        'type'      => 'select',
                        'options'   => array(
                            'category'      => esc_html_x('Category', 'admin-view', 'draven'),
                            'tag'           => esc_html_x('Tag', 'admin-view', 'draven'),
                            'both'          => esc_html_x('Category & Tag', 'admin-view', 'draven'),
                            'random'        => esc_html_x('Random', 'admin-view', 'draven')

                        ),
                        'dependency' => array('blog_related_posts_on', '==', 'true'),
                    ),
                    array(
                        'id'        => 'blog_related_max_post',
                        'type'      => 'slider',
                        'default'   => 1,
                        'title'     => esc_html_x( 'Maximum Related Posts', 'admin-view', 'draven' ),
                        'options'   => array(
                            'step'    => 1,
                            'min'     => 1,
                            'max'     => 10,
                            'unit'    => ''
                        ),
                        'dependency' => array('blog_related_posts_on', '==', 'true')
                    ),
                    array(
                        'id'        => 'blog_comments',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'on',
                        'title'     => esc_html_x('Comments', 'admin-view', 'draven'),
                        'desc'      => esc_html_x('Turn on to display comments.', 'admin-view', 'draven'),
                        'options'   => Draven_Options::get_config_radio_onoff(false)
                    )
                )
            )
        )
    );
    return $sections;
}