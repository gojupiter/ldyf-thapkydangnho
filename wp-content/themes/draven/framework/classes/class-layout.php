<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Draven_Layout {

    public function __construct() {

        add_filter( 'body_class', array( $this, 'body_classes' ) );
        add_action( 'draven/action/before_render_body', array( $this, 'render_pageloader_icon' ), 1);
        add_action( 'draven/action/before_render_main', array( $this, 'render_additional_block_content_top' ) );
        add_action( 'draven/action/before_render_main_inner', array( $this, 'render_additional_block_content_inner_top' ) );
        add_action( 'draven/action/after_render_main_inner', array( $this, 'render_additional_block_content_inner_bottom' ) );
        add_action( 'draven/action/after_render_main', array( $this, 'render_additional_block_content_bottom' ) );

        add_filter('draven/filter/sidebar_primary_name', array( $this, 'set_sidebar_name'), 10 );

        add_action('wp_head', array( $this, 'render_favicon') );
        add_action('admin_head', array( $this, 'render_favicon') );

        add_filter('draven/get_site_layout', array( $this, 'get_404_layout') );

        add_action('draven/action/after_render_body', array( $this, 'render_footer_bottom' ) );
    }

    public function body_classes( $classes ) {

        $classes[] = is_rtl() ? 'rtl' : 'ltr';
        $classes[] = 'draven-body';
        $classes[] = 'lastudio-draven';

        $context = (array) Draven()->get_current_context();

        $site_layout                = $this->get_site_layout();
        $header_layout              = Draven()->layout()->get_header_layout();
        $page_title_bar_layout      = $this->get_page_title_bar_layout();

        $main_fullwidth             = Draven()->settings()->get_setting_by_context('main_full_width','no');
        $header_full_width          = Draven()->settings()->get_setting_by_context('header_full_width', 'no');
        $header_sticky              = Draven()->settings()->get_setting_by_context('header_sticky', 'no');
        $header_transparency        = Draven()->settings()->get_setting_by_context('header_transparency', 'no');

        $footer_full_width          = Draven()->settings()->get_setting_by_context('footer_full_width','no');

        $body_boxed                 = Draven()->settings()->get('body_boxed', 'no');

        $mobile_footer_bar          = (Draven()->settings()->get('enable_header_mb_footer_bar','no') == 'yes') ? true : false;
        $mobile_footer_bar_items    =  Draven()->settings()->get('header_mb_footer_bar_component', array());

        $custom_body_class          = Draven()->settings()->get_setting_by_context('body_class','');

        if(!empty($custom_body_class)){
            $classes[] = esc_attr($custom_body_class);
        }

        if($body_boxed == 'yes'){
            $classes[] = 'body-boxed';
        }

        if(in_array('is_404', $context)){
            $classes[] = 'body-col-1c';
            $classes['page_title_bar'] = 'page-title-vhide';
        }
        else{
            $classes[] = esc_attr( 'body-' . $site_layout);
            $classes['page_title_bar'] = esc_attr( 'page-title-v' . $page_title_bar_layout);
        }

        $classes[] = 'header-v-' . esc_attr($header_layout);

        if($header_transparency == 'yes'){
            $classes[] = 'enable-header-transparency';
        }
        if($header_sticky != 'no'){
            $classes[] = 'enable-header-sticky';
            if($header_sticky == 'auto'){
                $classes[] = 'header-sticky-type-auto';
            }
        }

        if($header_full_width == 'yes'){
            $classes[] = 'enable-header-fullwidth';
        }
        if($main_fullwidth == 'yes'){
            $classes[] = 'enable-main-fullwidth';
        }
        if($footer_full_width == 'yes'){
            $classes[] = 'enable-footer-fullwidth';
        }
        if(Draven()->settings()->get('page_loading_animation', 'off') == 'on'){
            $classes[] = 'site-loading';
        }

        if($mobile_footer_bar && !empty($mobile_footer_bar_items)){
            $classes[] = 'enable-footer-bars';
        }

        if($site_layout == 'col-1c'){
            $blog_small_layout = Draven()->settings()->get('blog_small_layout', 'off');
            if(is_singular('post')){
                $single_small_layout_global = Draven()->settings()->get('single_small_layout', 'off');
                $single_small_layout = Draven()->settings()->get_post_meta( get_queried_object_id() , 'small_layout' );

                if($single_small_layout == 'on'){
                    $classes[] = 'enable-small-layout';
                }else{
                    if($single_small_layout_global == 'on' && $single_small_layout != 'off'){
                        $classes[] = 'enable-small-layout';
                    }else{
                        if($blog_small_layout == 'on'){
                            $classes[] = 'enable-small-layout';
                        }
                    }
                }
            }
            if(in_array('is_category', $context) || in_array('is_tag', $context)){
                $blog_archive_small_layout = Draven()->settings()->get_term_meta( get_queried_object_id() , 'small_layout' );
                if($blog_archive_small_layout == 'on'){
                    $classes[] = 'enable-small-layout';
                }else{
                    if($blog_small_layout == 'on' && $blog_archive_small_layout != 'off'){
                        $classes[] = 'enable-small-layout';
                    }
                }
            }
        }

        if(function_exists('dokan_get_option')){
            $page_id = dokan_get_option( 'dashboard', 'dokan_pages' );
            if($page_id){
                if ( dokan_is_store_page() || is_page( $page_id ) || ( get_query_var( 'edit' ) && is_singular( 'product' ) ) ) {
                    $classes[] = 'woocommerce-page';
                }
            }
        }

        if(is_404()){
            $content_404 = Draven()->settings()->get('404_page_content');
            if(!empty($content_404)){
                $classes[] = 'has-customized-404';
            }
        }

        if ( class_exists( 'LAHFB', false ) ) {
            $data = false;
            if(!empty($header_layout) && $header_layout != 'inherit'){
                if(!is_admin() && !isset($_GET['lastudio_header_builder'])) {
                    $data = LAHFB_Helper::get_data_frontend_component_with_preset($header_layout, $data);
                }
            }
            $data = $data ? $data : LAHFB_Helper::get_data_frontend_components();
            if(isset($data['desktop-view']['row1']['settings']['header_type'])){
                $header_type = $data['desktop-view']['row1']['settings']['header_type'];
                if($header_type == 'vertical'){
                    $vertical_component_id = $data['desktop-view']['row1']['settings']['uniqueId'];
                    $vertical_toggle = false;
                    if(!empty($data['components'][$vertical_component_id]['vertical_toggle']) && $data['components'][$vertical_component_id]['vertical_toggle'] == 'true'){
                        $vertical_toggle = true;
                    }
                    $classes[] = 'header-type-vertical';
                    if($vertical_toggle){
                        $classes[] = 'header-type-vertical--toggle';
                    }
                    else{
                        $classes[] = 'header-type-vertical--default';
                    }
                }
            }
        }


        return $classes;
    }

    public function get_site_layout(){
        $layout = Draven()->settings()->get_setting_by_context('layout', 'col-1c');
        return apply_filters('draven/get_site_layout', $layout);
    }

    public function get_404_layout( $layout ){
        if(is_404()){
            return 'col-1c';
        }
        return $layout;
    }

    public function get_content_width(){
        return 1170;
    }

    public function get_main_content_css_class( $el_class =  '' ){

        $site_layout = $this->get_site_layout();

        switch($this->get_site_layout()){

            case 'col-2cl':
                $_class = 'col-md-9';
                break;
            case 'col-2cr':
                $_class = 'col-md-9';
                break;
            case 'col-2cl-l':
                $_class = 'col-md-8';
                break;
            case 'col-2cr-l':
                $_class = 'col-md-8';
                break;
            case 'col-3cl':
                $_class = 'col-md-6';
                break;
            case 'col-3cm':
                $_class = 'col-md-6';
                break;
            case 'col-3cr':
                $_class = 'col-md-6';
                break;
            default:
                $_class = 'col-md-12';
        }

        if($site_layout == 'col-1c'){
            $blog_small_layout = Draven()->settings()->get('blog_small_layout', 'off');

            if(is_singular('post')){
                $single_small_layout_global = Draven()->settings()->get('single_small_layout', 'off');
                $single_small_layout = Draven()->settings()->get_post_meta( get_queried_object_id() , 'small_layout' );
                if($single_small_layout == 'on'){
                    $_class = 'col-md-8 col-md-offset-2';
                }
                else{
                    if($single_small_layout_global == 'on' && $single_small_layout != 'off'){
                        $_class = 'col-md-8 col-md-offset-2';
                    }
                    else{
                        if($blog_small_layout == 'on'){
                            $_class = 'col-md-8 col-md-offset-2';
                        }
                    }
                }
            }
            if(is_tag() || is_category()){
                $blog_archive_small_layout = Draven()->settings()->get_post_meta( get_queried_object_id() , 'small_layout' );
                if($blog_archive_small_layout == 'on'){
                    $_class = 'col-md-10 col-md-offset-1';
                }
                else{
                    if($blog_small_layout == 'on' && $blog_archive_small_layout != 'off'){
                        $_class = 'col-md-10 col-md-offset-1';
                    }
                }
            }
            if ( !is_front_page() && is_home() ) {
                if($blog_small_layout == 'on'){
                    $_class = 'col-md-10 col-md-offset-1';
                }
            }
        }

        if(!empty($el_class)){
            $_class .= ' ';
            $_class .= $el_class;
        }
        return $_class;
    }

    public function get_main_sidebar_css_class( $el_class = '' ) {
        switch($this->get_site_layout()){
            case 'col-2cl':
                $_class = 'col-md-3';
                break;
            case 'col-2cr':
                $_class = 'col-md-3';
                break;
            case 'col-2cl-l':
                $_class = 'col-md-4';
                break;
            case 'col-2cr-l':
                $_class = 'col-md-4';
                break;
            case 'col-3cl':
                $_class = 'col-md-3';
                break;
            case 'col-3cm':
                $_class = 'col-md-3';
                break;
            case 'col-3cr':
                $_class = 'col-md-3';
                break;
            default:
                $_class = 'hidden';
        }
        if(!empty($el_class)){
            $_class .= ' ';
            $_class .= $el_class;
        }
        return $_class;
    }

    public function get_header_layout(){

        if( 'default' == get_option('lastudio_header_layout', 'default') ) {
            return 'default';
        }

        return Draven()->settings()->get_setting_by_context('header_layout', 1);
    }

    public function get_page_title_bar_layout(){
        return Draven()->settings()->get_setting_by_context('page_title_bar_layout', 'hide');
    }

    public function get_footer_layout(){
        return Draven()->settings()->get_setting_by_context('footer_layout', '');
    }

    public function render_logo( $default_logo = null ){
        $logo = Draven()->settings()->get('logo', false);
        $logo2x = Draven()->settings()->get('logo_2x', false);
        if(empty($default_logo)){
            $default_logo = apply_filters('draven/setting/logo/default', Draven::$template_dir_url . '/assets/images/logo.png');
        }
        $logo_src = $default_logo;
        $logo_2x_src = false;
        if($logo){
            $logo_src = wp_get_attachment_image_url( $logo, 'full' );
        }
        if(!$logo_src){
            $logo_src = $default_logo;
        }
        if($logo2x){
            $logo_2x_src = wp_get_attachment_image_url( $logo2x, 'full' );
        }
        printf(
            '<img class="lahfb-logo" src="%1$s" alt="%2$s"%3$s/>',
            esc_url($logo_src),
            esc_attr(get_bloginfo('name')),
            (false !== $logo_2x_src ? ' srcset="'.esc_url( $logo_2x_src ).' 2x"' : '')
        );
    }

    public function render_header_tpl(){

        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {

            if(Draven()->settings()->get_setting_by_context('hide_header') == 'yes' && !is_singular('elementor_library')){
                return;
            }

            $value = $this->get_header_layout();
            if ( class_exists( 'LAHFB' ) ) {
                $data = false;
                if(!empty($value) && $value != 'inherit'){
                    if(!is_admin() && !isset($_GET['lastudio_header_builder'])) {
                        $data = LAHFB_Helper::get_data_frontend_component_with_preset($value, $data);
                    }
                }

                echo LAHFB_Output::output(false, $data, $value);
            }
            else{
                get_template_part('templates/headers/header',$value);
            }
        }
    }

    public function render_page_title_bar_layout_tpl(){
        $value = $this->get_page_title_bar_layout();
        if(!empty($value) && $value != 'hide' && !is_singular('elementor_library')){
            get_template_part('templates/page-title-bars/layout',$value);
        }
    }

    public function render_footer_tpl(){

        if( Draven()->settings()->get_setting_by_context('hide_footer') == 'yes'){
            return;
        }

		$value = $this->get_footer_layout();

        if(!empty($value) && $value != 'inherit'){
            $value = absint($value);
            if(!empty($value) && get_post_type($value) == 'elementor_library'){
                echo do_shortcode('[elementor-template id="'. $value .'"]');
            }
        }
        else{
            get_template_part('templates/footers/footer');
        }
    }

    public function set_sidebar_name( $sidebar ){
        $context = Draven()->get_current_context();

        if(in_array( 'is_search', $context)){
            if( ($sidebar_search = Draven()->settings()->get('search_sidebar', $sidebar)) && !empty( $sidebar_search) ) {
                return $sidebar_search;
            }
        }

        if(in_array('is_category', $context) || in_array( 'is_tag', $context )){

            $sidebar = Draven()->settings()->get('blog_archive_sidebar', $sidebar);

            if( Draven()->settings()->get('blog_archive_global_sidebar', false) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }

            $_sidebar = Draven()->settings()->get_term_meta( get_queried_object_id(), 'sidebar');

            if(!empty($_sidebar)){
                return $_sidebar;
            }

        }

        if(is_singular('post')){
            $sidebar = Draven()->settings()->get('posts_sidebar', $sidebar);
            if( Draven()->settings()->get('posts_global_sidebar', false) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }

            $_sidebar = Draven()->settings()->get_post_meta( get_queried_object_id(), 'sidebar');
            if(!empty($_sidebar)){
                return $_sidebar;
            }
        }

        if(in_array('is_tax', $context) && is_tax(get_object_taxonomies( 'la_portfolio' ))){
            $sidebar = Draven()->settings()->get('portfolio_archive_sidebar', $sidebar);
            if( Draven()->settings()->get('portfolio_archive_global_sidebar', false) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }
            $_sidebar = Draven()->settings()->get_post_meta( get_queried_object_id(), 'sidebar');
            if(!empty($_sidebar)){
                return $_sidebar;
            }
        }

        if(is_singular('la_portfolio')){
            $sidebar = Draven()->settings()->get('portfolio_sidebar', $sidebar);
            if( Draven()->settings()->get('portfolio_global_sidebar', false) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }
            $_sidebar = Draven()->settings()->get_post_meta( get_queried_object_id(), 'sidebar');
            if(!empty($_sidebar)){
                return $_sidebar;
            }
        }
        if(is_page()){
            $sidebar = Draven()->settings()->get('pages_sidebar', $sidebar);
            if( Draven()->settings()->get('pages_global_sidebar', false) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }
            $_sidebar = Draven()->settings()->get_post_meta( get_queried_object_id(), 'sidebar');

            if(!empty($_sidebar)){
                return $_sidebar;
            }

        }


        return $sidebar;
    }

    public function render_additional_block_content_top(){
        if( $block_id = (int) Draven()->settings()->get_setting_by_context('block_content_top') ){
            printf( '<div class="la-block-content-top container">%s</div>',
                do_shortcode('[elementor-template id="'. $block_id .'"]')
            );
        }
        if(is_active_sidebar('la-custom-block-top')){
            echo '<div class="la-block-content-top container">';
                dynamic_sidebar('la-custom-block-top');
            echo '</div>';
        }
    }

    public function render_additional_block_content_inner_top(){
        if( $block_id = (int) Draven()->settings()->get_setting_by_context('block_content_inner_top') ){
            printf( '<div class="la-block-content-inner-top">%s</div>',
                do_shortcode('[elementor-template id="'. $block_id .'"]')
            );
        }
        if(is_active_sidebar('la-custom-block-inner-top')){
            echo '<div class="la-block-content-top">';
            dynamic_sidebar('la-custom-block-inner-top');
            echo '</div>';
        }
    }

    public function render_additional_block_content_bottom(){
        if( $block_id = (int) Draven()->settings()->get_setting_by_context('block_content_bottom') ){
            printf( '<div class="la-block-content-bottom container">%s</div>',
                do_shortcode('[elementor-template id="'. $block_id .'"]')
            );
        }
        if(is_active_sidebar('la-custom-block-bottom')){
            echo '<div class="la-block-content-bottom container">';
            dynamic_sidebar('la-custom-block-bottom');
            echo '</div>';
        }
    }

    public function render_additional_block_content_inner_bottom(){
        if( $block_id = (int) Draven()->settings()->get_setting_by_context('block_content_inner_bottom') ){
            printf( '<div class="la-block-content-inner-bottom container">%s</div>',
                do_shortcode('[elementor-template id="'. $block_id .'"]')
            );
        }
        if(is_active_sidebar('la-custom-block-inner-bottom')){
            echo '<div class="la-block-content-bottom">';
            dynamic_sidebar('la-custom-block-inner-bottom');
            echo '</div>';
        }
    }

    public function render_pageloader_icon(){
        if(Draven()->settings()->get('page_loading_animation', 'off') == 'on'){
            $loading_style = Draven()->settings()->get('page_loading_style', '1');
            if($loading_style == 'custom'){
                if($img = Draven()->settings()->get('page_loading_custom')){
                    echo '<div class="la-image-loading spinner-custom"><div class="content"><div class="la-loader">'. wp_get_attachment_image($img, 'full') .'</div></div></div>';
                }else{
                    echo '<div class="la-image-loading"><div class="content"><div class="la-loader spinner1"></div></div></div>';
                }
            }else{
                echo '<div class="la-image-loading"><div class="content"><div class="la-loader spinner'.esc_attr($loading_style).'"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div>';
            }
        }
    }

    public function render_favicon(){
        if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
            if( $favicon = wp_get_attachment_image_url(Draven()->settings()->get('favicon'), 'full') ){
                printf('<link rel="apple-touch-icon" sizes="16x16" href="%s"/>', esc_url($favicon));
            }
            if( $favicon = wp_get_attachment_image_url(Draven()->settings()->get('favicon_iphone'), 'full') ){
                printf('<link rel="apple-touch-icon" sizes="57x57" href="%s"/>', esc_url($favicon));
            }
            if( $favicon = wp_get_attachment_image_url(Draven()->settings()->get('favicon_ipad'), 'full') ){
                printf('<link rel="apple-touch-icon" sizes="72x72" href="%s"/>', esc_url($favicon));
            }
            if( $favicon = wp_get_attachment_image_url(Draven()->settings()->get('favicon'), 'full') ){
                printf('<link  rel="shortcut icon" type="image/png" sizes="72x72" href="%s"/>', esc_url($favicon));
            }
            if( $favicon = wp_get_attachment_image_url(Draven()->settings()->get('favicon_iphone'), 'full') ){
                printf('<link  rel="shortcut icon" type="image/png" sizes="57x57" href="%s"/>', esc_url($favicon));
            }
            if( $favicon = wp_get_attachment_image_url(Draven()->settings()->get('favicon_ipad'), 'full') ){
                printf('<link  rel="shortcut icon" type="image/png" sizes="16x16" href="%s"/>', esc_url($favicon));
            }
        }
    }

    public function render_member_social_tpl( $post_id ) {
        $output = '<div class="item--social member-social">';
        if(($facebook = Draven()->settings()->get_post_meta($post_id, 'facebook')) && !empty($facebook)){
            $output .= sprintf('<a class="social-facebook facebook" href="%s"><i class="fa fa-facebook"></i></a>', esc_url($facebook));
        }
        if(($twitter = Draven()->settings()->get_post_meta($post_id, 'twitter')) && !empty($twitter)){
            $output .= sprintf('<a class="social-twitter twitter" href="%s"><i class="fa fa-twitter"></i></a>', esc_url($twitter));
        }
        if(($pinterest = Draven()->settings()->get_post_meta($post_id, 'pinterest')) && !empty($pinterest)){
            $output .= sprintf('<a class="social-pinterest pinterest" href="%s"><i class="fa fa-pinterest"></i></a>', esc_url($pinterest));
        }
        if(($linkedin = Draven()->settings()->get_post_meta($post_id, 'linkedin')) && !empty($linkedin)){
            $output .= sprintf('<a class="social-linkedin linkedin" href="%s"><i class="fa fa-linkedin"></i></a>', esc_url($linkedin));
        }
        if(($dribbble = Draven()->settings()->get_post_meta($post_id, 'dribbble')) && !empty($dribbble)){
            $output .= sprintf('<a class="social-dribbble dribbble" href="%s"><i class="fa fa-dribbble"></i></a>', esc_url($dribbble));
        }
        if(($youtube = Draven()->settings()->get_post_meta($post_id, 'youtube')) && !empty($youtube)){
            $output .= sprintf('<a class="social-youtube youtube" href="%s"><i class="fa fa-youtube-play"></i></a>', esc_url($youtube));
        }
        if(($gplus = Draven()->settings()->get_post_meta($post_id, 'google_plus')) && !empty($gplus)){
            $output .= sprintf('<a class="social-google-plus google-plus" href="%s"><i class="fa fa-google-plus-official"></i></a>', esc_url($gplus));
        }
        if(($email = Draven()->settings()->get_post_meta($post_id, 'email')) && !empty($email)){
            $output .= sprintf('<a class="social-email email" href="%s"><i class="fa fa-envelope-o"></i></a>', esc_url('mailto:'.$email));
        }
        $output .= '</div>';
        echo draven_render_variable( $output );
    }

    public function render_footer_bottom(){
        get_template_part('templates/footers/footer', 'bottom');
    }
}