<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$show_page_title = apply_filters('draven/filter/show_page_title', true);
$show_breadcrumbs = apply_filters('draven/filter/show_breadcrumbs', true);

$layout = Draven()->layout()->get_page_title_bar_layout();

$context = Draven()->get_current_context();
if( in_array('is_singular', $context) ){
    $_hide_breadcrumb = Draven()->settings()->get_post_meta(get_queried_object_id(), 'hide_breadcrumb');
    $_hide_page_title= Draven()->settings()->get_post_meta(get_queried_object_id(), 'hide_page_title');
    if($_hide_breadcrumb == 'yes'){
        $show_breadcrumbs = false;
    }
    if($_hide_page_title == 'yes'){
        $show_page_title = false;
    }
}

if( in_array('is_tax', $context) || in_array('is_category', $context) || in_array('is_tag', $context) ){
    $_hide_breadcrumb = Draven()->settings()->get_term_meta(get_queried_object_id(), 'hide_breadcrumb');
    $_hide_page_title= Draven()->settings()->get_term_meta(get_queried_object_id(), 'hide_page_title');
    if($_hide_breadcrumb == 'on'){
        $show_breadcrumbs = false;
    }
    if($_hide_page_title == 'on'){
        $show_page_title = false;
    }
}

$enable_custom_text = Draven()->settings()->get_setting_by_context('enable_page_title_subtext', 'no');
$custom_text = Draven()->settings()->get_setting_by_context('page_title_custom_subtext', '');


if($show_breadcrumbs || $show_page_title) :
?>
<section id="section_page_header" class="wpb_row section-page-header<?php if($enable_custom_text == 'yes' && !empty($custom_text)) { echo ' use-custom-text'; } ?>">
    <div class="container">
        <div class="page-header-inner">
            <div class="row">
                <div class="col-xs-12">
                    <?php
                    if($layout == 5 && $show_breadcrumbs){
                        if($enable_custom_text == 'yes' && !empty($custom_text)){
                            printf('<div class="la-breadcrumbs use-custom-text">%s</div>', esc_html($custom_text));
                        }
                        else{
                            do_action('draven/action/breadcrumbs/render_html');
                        }
                    }
                    if($show_page_title){
                        echo Draven()->breadcrumbs()->get_title();
                    }
                    ?>
                    <?php
                    if($layout != 5 && $show_breadcrumbs){
                        if($enable_custom_text == 'yes' && !empty($custom_text)){
                            printf('<div class="la-breadcrumbs use-custom-text">%s</div>', esc_html($custom_text));
                        }
                        else{
                            do_action('draven/action/breadcrumbs/render_html');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- #page_header -->
<?php endif; ?>