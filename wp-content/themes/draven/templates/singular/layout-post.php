<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$single_post_thumbnail_size = Draven_Helper::get_image_size_from_string(Draven()->settings()->get('single_post_thumbnail_size', 'full'), 'full');
$post_class = 'single-post-content';

if(!get_post_meta( get_the_ID(), '_elementor_edit_mode', true )){
    $post_class .= ' article-post-no-builder';
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>

    <?php
    if('above' == Draven()->settings()->get('blog_post_title')){
        the_title( '<header class="entry-header entry-header-above single_post_item--title"><h1 class="entry-title">', '</h1></header>' );
        echo '<div class="post-meta post-meta-top">';
        draven_entry_meta_item_postdate();
        draven_entry_meta_item_author();
        draven_entry_meta_item_category_list('<div class="post-terms post-meta__item">', '</div>', '');
        echo '</div>';
    }
    ?>

    <?php
    if(Draven()->settings()->get('featured_images_single') == 'on'){
        draven_single_post_thumbnail($single_post_thumbnail_size);
    }
    ?>

    <?php
    if('below' == Draven()->settings()->get('blog_post_title') ){
        echo '<header class="entry-header entry-header-below entry-header single_post_item--title">';
        the_title( '<h1 class="entry-title">', '</h1>' );
        echo '</header>';
        echo '<div class="post-meta post-meta-top">';
        draven_entry_meta_item_postdate();
        draven_entry_meta_item_author();
        draven_entry_meta_item_category_list('<div class="post-terms post-meta__item">', '</div>', '');
        echo '</div>';
    }
    ?>

    <div class="entry-content<?php if(!get_post_meta( get_the_ID(), '_elementor_edit_mode', true )){ echo ' entry-content-no-builder'; } ?>">
        <?php

        the_content();

        wp_link_pages( array(
            'before'      => '<div class="clearfix"></div><div class="page-links"><span class="page-links-title">' . esc_html_x( 'Pages:', 'front-view', 'draven' ) . '</span>',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
            'pagelink'    => '<span class="screen-reader-text">' . esc_html_x( 'Page', 'front-view', 'draven' ) . ' </span>%',
            'separator'   => '<span class="screen-reader-text">, </span>',
        ) );

        edit_post_link( $text = null, '<p class="hidden">', '</p>');

        ?>
    </div><!-- .entry-content -->

    <div class="clearfix"></div>
    <footer class="entry-footer"><?php

        echo '<span class="tags-list">';
        the_tags();
        echo '</span>';

        if( Draven()->settings()->get('blog_post_title') == 'off' ) {
            draven_entry_meta_item_category_list('<span class="tags-list">', '</span>', ', ');
        }

        if(Draven()->settings()->get('blog_social_sharing_box') == 'on'){
            echo '<span class="la-sharing-single-posts">';
            echo sprintf('<span>%s </span>', esc_html_x('Share on', 'front-view', 'draven') );
            draven_social_sharing(get_the_permalink(), get_the_title(), (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : ''));
            echo '</span>';
        }

        ?></footer><!-- .entry-footer -->

</article><!-- #post-## -->

<div class="clearfix"></div>

<?php

if(Draven()->settings()->get('blog_pn_nav') == 'on'){
    the_post_navigation( array(
        'next_text' => '<span class="blog_pn_nav-title">%title</span><span class="blog_pn_nav-text">'. esc_html__('Newer Post', 'draven') .'</span>',
        'prev_text' => '<span class="blog_pn_nav-title">%title</span><span class="blog_pn_nav-text">'. esc_html__('Older Post', 'draven') .'</span>'
    ) );
    echo '<div class="clearfix"></div>';
}


if(Draven()->settings()->get('blog_author_info') == 'on'){
    get_template_part( 'author-bio' );
    echo '<div class="clearfix"></div>';
}

if(Draven()->settings()->get('blog_comments') == 'on' && ( comments_open() || get_comments_number() ) ){
    comments_template();
    echo '<div class="clearfix"></div>';
}