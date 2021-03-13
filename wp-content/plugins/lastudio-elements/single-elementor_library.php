<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

if(!has_term('footer', 'elementor_library_type', get_the_ID())){
    ?>
    <div id="main" class="site-main">
        <div class="container">
            <div class="row">
                <main id="site-content" class="col-md-12 col-xs-12 site-content">
                    <div class="site-content-inner">
                        <div class="page-content">
                            <?php
                            the_content();
                            ?>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <?php
}

else{

    ?>
    <div id="main" class="site-main">
        <div class="container">
            <div class="row">
                <main id="site-content" class="col-md-12 col-xs-12 site-content">
                    <div class="site-content-inner">
                        <div class="page-content">
                            <div class="elementor-theme-builder-content-area"><?php esc_html_e('Content Area', 'lastudio-elements'); ?></div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <footer id="colophon" class="site-footer la-footer-builder">
        <div class="container">
        <?php the_content(); ?>
        </div>
    </footer>
    <?php
}
get_footer();