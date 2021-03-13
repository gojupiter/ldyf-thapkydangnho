<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$title_tag = 'h2';

$post_class = array('blog__item', 'lastudio-posts__item', 'show-excerpt');
?>
<div <?php post_class($post_class); ?>>
    <div class="loop__item__inner lastudio-posts__inner-box">
        <div class="loop__item__inner2">
            <div class="lastudio-posts__inner-content">
                <?php
                echo sprintf(
                    '<%1$s class="entry-title"><a href="%2$s">%3$s</a></%1$s>',
                    esc_attr($title_tag),
                    esc_url(get_the_permalink()),
                    esc_html(get_the_title())
                );
                ?>
                <div class="post-meta post-meta-bottom"><?php draven_entry_meta_item_postdate(); ?></div>
                <div class="entry-excerpt"><?php the_excerpt(); ?></div>
                <div class="lastudio-more-wrap"><a href="<?php the_permalink(); ?>" class="btn elementor-button lastudio-more"><span class="btn__text"><?php echo esc_html_x('Read more', 'front-view', 'draven'); ?></span></a></div>
            </div>
        </div>
    </div>
</div>