<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header(); ?>
<?php do_action( 'draven/action/before_render_main' ); ?>
    <div id="main" class="site-main">
        <div class="container">
            <div class="row">
                <main id="site-content" class="<?php echo esc_attr(Draven()->layout()->get_main_content_css_class('col-xs-12 site-content'))?>">
                    <div class="site-content-inner">

                        <?php do_action( 'draven/action/before_render_main_inner' );?>

                        <div id="portfolio_content_container" class="main--loop-container">

                            <?php
                            do_action( 'draven/action/before_render_main_content' );

                            if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
                                get_template_part('templates/portfolios/archive');
                            }

                            do_action( 'draven/action/after_render_main_content' );
                            ?>

                        </div>

                        <?php do_action( 'draven/action/after_render_main_inner' );?>
                    </div>
                </main>
                <!-- #site-content -->
                <?php get_sidebar();?>
            </div>
        </div>
    </div>
    <!-- .site-main -->
<?php do_action( 'draven/action/after_render_main' ); ?>
<?php get_footer();?>