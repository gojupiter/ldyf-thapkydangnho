<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<header id="lastudio-header-builder" class="lahfb-wrap default-header">
    <div class="lahfbhouter">
        <div class="lahfbhinner">
            <div class="main-slide-toggle"></div>
            <div class="lahfb-screen-view lahfb-desktop-view">
                <div class="lahfb-area lahfb-row1-area lahfb-content-middle header-area-padding lahfb-area__auto">
                    <div class="container">
                        <div class="lahfb-content-wrap lahfb-area__auto">
                            <div class="lahfb-col lahfb-col__left">
                                <a href="<?php echo home_url('/')?>" class="lahfb-element lahfb-logo">
                                    <?php Draven()->layout()->render_logo();?>
                                </a>
                            </div>
                            <div class="lahfb-col lahfb-col__center"></div>
                            <div class="lahfb-col lahfb-col__right">
                                <div class="lahfb-responsive-menu-wrap lahfb-responsive-menu-1546041916358" data-uniqid="1546041916358">
                                    <div class="close-responsive-nav">
                                        <div class="lahfb-menu-cross-icon"></div>
                                    </div>
                                    <ul class="responav menu">
                                        <?php
                                        $menu_output = wp_nav_menu( array(
                                            'theme_location' => 'main-nav',
                                            'container'     => false,
                                            'link_before'   => '',
                                            'link_after'    => '',
                                            'items_wrap'    => '%3$s',
                                            'echo'          => false,
                                            'fallback_cb'   => array( 'Draven_MegaMenu_Walker', 'fallback' ),
                                            'walker'        => new Draven_MegaMenu_Walker
                                        ));
                                        echo draven_render_variable($menu_output);
                                        ?>
                                    </ul>
                                </div>
                                <nav class="lahfb-element lahfb-nav-wrap nav__wrap_1546041916358" data-uniqid="1546041916358"><ul class="menu"><?php echo draven_render_variable($menu_output); ?></ul></nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lahfb-screen-view lahfb-tablets-view">
                <div class="lahfb-area lahfb-row1-area lahfb-content-middle header-area-padding lahfb-area__auto">
                    <div class="container">
                        <div class="lahfb-content-wrap lahfb-area__auto">
                            <div class="lahfb-col lahfb-col__left">
                                <a href="<?php echo home_url('/')?>" class="lahfb-element lahfb-logo">
                                    <?php Draven()->layout()->render_logo();?>
                                </a>
                            </div>
                            <div class="lahfb-col lahfb-col__center"></div>
                            <div class="lahfb-col lahfb-col__right">
                                <div class="lahfb-element lahfb-responsive-menu-icon-wrap nav__res_hm_icon_1546041916358" data-uniqid="1546041916358"><a href="#"><i class="fa fa-bars"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lahfb-screen-view lahfb-mobiles-view">
                <div class="lahfb-area lahfb-row1-area lahfb-content-middle header-area-padding lahfb-area__auto">
                    <div class="container">
                        <div class="lahfb-content-wrap lahfb-area__auto">
                            <div class="lahfb-col lahfb-col__left">
                                <a href="<?php echo home_url('/')?>" class="lahfb-element lahfb-logo">
                                    <?php Draven()->layout()->render_logo();?>
                                </a>
                            </div>
                            <div class="lahfb-col lahfb-col__center"></div>
                            <div class="lahfb-col lahfb-col__right">
                                <div class="lahfb-element lahfb-responsive-menu-icon-wrap nav__res_hm_icon_1546041916358" data-uniqid="1546041916358"><a href="#"><i class="fa fa-bars"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lahfb-wrap-sticky-height"></div>
    </div>
</header>