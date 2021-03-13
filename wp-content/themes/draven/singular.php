<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

do_action( 'draven/action/before_render_main' );
?>

<div id="main" class="site-main">
<?php do_action( 'draven/action/before_render_main_container' ); ?>
    <div class="container">
        <div class="row">
            <main id="site-content" class="<?php echo esc_attr(Draven()->layout()->get_main_content_css_class('col-xs-12 site-content'))?>">
                <div class="site-content-inner">

                    <?php do_action( 'draven/action/before_render_main_inner' );?>

                    <div class="page-content">

                        <div class="single-post-detail clearfix">
                            <?php

                            do_action( 'draven/action/before_render_main_content' );

                            // Elementor `single` location
                            if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

                                // Loop through posts
                                while ( have_posts() ) : the_post();
                                    // Library post types
                                    if ( is_singular( 'elementor_library' ) ) {
                                        get_template_part( 'templates/elementor/library/layout' );

                                    }
                                    // All other post types.
                                    else {
                                        get_template_part( 'templates/singular/layout', get_post_type() );
                                    }

                                endwhile;

                            }

                            do_action( 'draven/action/after_render_main_content' );

                            ?>
                        </div>

                    </div>

                    <?php do_action( 'draven/action/after_render_main_inner' );?>
                </div>
            </main>
            <!-- #site-content -->
            <?php get_sidebar();?>
        </div>
    </div>
    <?php do_action( 'draven/action/after_render_main_container' ); ?>
</div>

<!-- .site-main -->
<?php do_action( 'draven/action/after_render_main' ); ?>
<?php get_footer();?>
