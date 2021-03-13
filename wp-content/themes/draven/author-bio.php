<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?><div class="author-info">
    <div class="author-info--inner">
        <div class="author-info__avatar author-avatar">
            <div class="author-info__avatar-inner">
                <?php
                printf( '<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
                    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'nicename' ) ) ),
                    get_the_author(),
                    get_avatar( get_the_author_meta( 'user_email' ), 50 )
                );
                ?>
            </div>
        </div>
        <div class="author-info__description author-description">
            <div class="author-info__title">
                <?php
                printf( '<div class="author-info__name"><a href="%1$s" title="%2$s" rel="author">%2$s</a></div>',
                    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'nicename' ) ) ),
                    get_the_author()
                );
                ?>
            </div>
            <?php if(get_the_author_meta('description')) : ?>
            <div class="author-info__bio author-bio"><?php the_author_meta( 'description' ); ?></div>
            <?php endif; ?>
            <div class="author-info__link">
                <?php
                echo sprintf(
                        '<a href="%1$s">%2$s%3$s</a>',
                        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'nicename' ) ) ),
                        esc_html_x('View all posts', 'front-end', 'draven'),
                        ' <i class="fa fa-angle-right"></i>'
                    );
                ?>
            </div>
        </div>
    </div>
</div>