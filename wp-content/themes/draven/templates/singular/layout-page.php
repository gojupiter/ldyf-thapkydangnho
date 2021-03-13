<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>
<?php

the_content();

wp_link_pages(
    array(
        'before' => '<div class="clearfix"></div><div class="page-links"><span class="page-links-title">' . esc_html_x( 'Pages:','front-view', 'draven' ) . '</span>',
        'after' => '</div>',
        'link_before' => '<span>',
        'link_after' => '</span>'
    )
);