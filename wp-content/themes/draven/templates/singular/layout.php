<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>
<div <?php post_class('single-post-content'); ?>>
    <div class="entry-content">
        <?php
        the_content();
        ?>
    </div><!-- .entry-content -->
</div><!-- #post-## -->