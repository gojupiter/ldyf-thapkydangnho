<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header(); ?>
<?php do_action( 'draven/action/before_render_main' ); ?>
<?php $content_404 = Draven()->settings()->get('404_page_content'); ?>
    <div id="main" class="site-main<?php if($content_404) { echo ' has-custom-404-content';  } ?>">
        <div class="container">
            <div class="row">
                <main id="site-content" class="<?php echo esc_attr(Draven()->layout()->get_main_content_css_class('col-xs-12 site-content'))?>">
                    <div class="site-content-inner">

                        <?php do_action( 'draven/action/before_render_main_inner' );?>

                        <div class="page-content">
                            <?php
                            if(!empty($content_404)) : ?>
                                <div class="customerdefine-404-content">
                                    <?php echo Draven_Helper::remove_js_autop($content_404,true); ?>
                                </div>
                            <?php else : ?>
                                <div class="default-404-content">
                                    <div class="col-xs-12">
                                        <h1><?php echo esc_html_x('404', 'front-end', 'draven') ?></h1>
                                        <h5><?php echo esc_html_x('Page cannot be found !', 'front-end', 'draven') ?></h5>
                                        <p><?php echo esc_html_x('It looks like nothing was found at this locations.', 'front-view', 'draven');?></p>
                                        <p class="btn-wrapper"><a class="button" href="<?php echo esc_url(home_url('/')) ?>"><?php echo esc_html_x('Back to home', 'front-view','draven')?></a></p>
                                    </div>
                                </div>
                                <?php
                            endif;
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