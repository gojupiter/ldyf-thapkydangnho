<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$mobile_footer_bar       = (Draven()->settings()->get('enable_header_mb_footer_bar','no') == 'yes') ? true : false;
$mobile_footer_bar_items =  Draven()->settings()->get('header_mb_footer_bar_component', array());

$hide_footer = Draven()->settings()->get_setting_by_context('hide_footer');


if( 'yes' == Draven()->settings()->get('backtotop_btn', 'no') ){
    echo '<div class="clearfix"><div class="backtotop-container"><a id="btn-backtotop" href="#page" class="btn-backtotop button btn-secondary"><span class="fa fa-angle-up"></span></a></div></div>';
}


if($hide_footer != 'yes' || is_singular('elementor_library')){
    echo '<footer id="colophon" class="site-footer la-footer-builder"><div class="container">';
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
    Draven()->layout()->render_footer_tpl();
}

if($hide_footer != 'yes' || is_singular('elementor_library')){
    echo '</div></footer>';
}

    echo '</div><!-- .site-inner -->';
echo '</div><!-- #page-->';

?>
<?php  if($mobile_footer_bar && !empty($mobile_footer_bar_items)): ?>
    <div class="footer-handheld-footer-bar">
        <div class="footer-handheld__inner">
            <?php
            foreach($mobile_footer_bar_items as $component){
                if(isset($component['type'])){
                    echo Draven_Helper::render_access_component($component['type'], $component, 'handheld_component');
                }
            }
            ?>
        </div>
    </div>
<?php endif; ?>

<?php
do_action('draven/action/after_render_body');
do_action('draven/action/footer');
wp_footer();

?>
</body>
</html>
