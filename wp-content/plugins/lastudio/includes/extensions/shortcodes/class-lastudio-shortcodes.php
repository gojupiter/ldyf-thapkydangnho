<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Shortcodes{

    public static $shortcode_path;

    public static $instance = null;

    private $_shortcodes = array(
        'la_animation_block',
        'la_btn',
        'la_icon_boxes',
        'la_heading',
        'la_team_member',
        'la_stats_counter',
        'la_testimonial',
        'la_show_posts',
        'la_show_portfolios',
        'la_portfolio_masonry',
        'la_portfolio_info',
        'la_maps',
        'la_social_link',
        'la_banner',
        //'la_spa_service',
        'la_block',
        'la_countdown',
        'la_breadcrumb',
        //        'la_timeline',
        //        'la_timeline_item',
        'la_pricing_table',
        'la_divider',
        'la_icon_list',
        'la_icon_list_item',
        'la_instagram_feed',
        'la_carousel',
        'la_hotspot',
        'la_image_with_hotspots'
    );

    private $_woo_shortcodes = array(
        'product',
        'products',
        'recent_products',
        'featured_products',
        'product_category',
        'product_categories',
        'sale_products',
        'best_selling_products',
        'top_rated_products',
        'product_attribute'
    );

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        self::$shortcode_path = plugin_dir_path(__FILE__);
    }

    public function load_dependencies(){
        require_once self::$shortcode_path . 'class-lastudio-shortcodes-helper.php';
        require_once self::$shortcode_path . 'class-lastudio-shortcodes-row.php';
        require_once self::$shortcode_path . 'class-lastudio-shortcodes-parallax-row.php';
        require_once self::$shortcode_path . 'class-lastudio-shortcodes-autocomplete-filters.php';
        require_once self::$shortcode_path . 'class-lastudio-shortcodes-param.php';
        require_once self::$shortcode_path . 'class-lastudio-shortcodes-woocommerce.php';

        LaStudio_Shortcodes_Row::get_instance();
        LaStudio_Shortcodes_Parallax_Row::get_instance();
        LaStudio_Shortcodes_Autocomplete_Filters::get_instance();
    }

    public function create_shortcode(){

        add_shortcode('la_dropcap',     array( $this, 'add_dropcap') );
        add_shortcode('la_quote',       array( $this, 'add_quote_shortcode') );
        add_shortcode('la_text',        array( $this, 'add_text_shortcode') );
        add_shortcode('wp_nav_menu',    array( $this, 'add_navmenu') );
        add_shortcode('la_login_link',    array( $this, 'add_login_link') );
        add_shortcode('la_logout_link',    array( $this, 'add_logout_link') );

        foreach ($this->_shortcodes as $shortcode) {
            add_shortcode( $shortcode, array( $this, 'auto_detect_shortcode_callback' ) );
        }

        if(class_exists('LaStudio_Discography')){
            add_shortcode( 'lastudio_last_releases', array( $this, 'auto_detect_shortcode_callback' ) );
        }

        if(class_exists('LaStudio_Videos')){
            add_shortcode( 'la_last_video', array( $this, 'auto_detect_shortcode_callback' ) );
        }

        if(class_exists('WooCommerce')){
            add_shortcode('la_wishlist',    array( $this, 'add_wishlist') );
            add_shortcode('la_compare',     array( $this, 'add_compare') );
            foreach ( $this->_woo_shortcodes as $shortcode ) {
                add_filter( "{$shortcode}_shortcode_tag", array( $this, 'modify_woocommerce_shortcodes' ) );
                add_shortcode( $shortcode, array( $this, 'auto_detect_shortcode_callback' ) );
            }
        }
    }

    public function vc_after_init(){

        foreach ($this->_shortcodes as $shortcode) {
            $config_file = self::locate_config_shortcode_template($shortcode);
            vc_lean_map( $shortcode, null, $config_file );
        }
        if(class_exists('WooCommerce')){
            foreach ($this->_woo_shortcodes as $woo_shortcode) {
                $woo_config_file = self::locate_config_shortcode_template($woo_shortcode);
                vc_lean_map( $woo_shortcode, null, $woo_config_file );
            }
        }
        if(class_exists('LaStudio_Playlist_Manager')){
            $config_file = self::locate_config_shortcode_template('lastudio_playlist');
            vc_lean_map( 'lastudio_playlist', null, $config_file );
        }

        if(class_exists('LaStudio_Discography')){
            $config_file = self::locate_config_shortcode_template('lastudio_last_releases');
            vc_lean_map( 'lastudio_last_releases', null, $config_file );
        }

        if(class_exists('LaStudio_Videos')){
            $config_file = self::locate_config_shortcode_template('la_last_video');
            vc_lean_map( 'la_last_video', null, $config_file );
        }

        if(class_exists('LaStudio_Events')){
            $config_file = self::locate_config_shortcode_template('lastudio_event_list');
            vc_lean_map( 'lastudio_event_list', null, $config_file );
        }

        add_filter('vc_edit_form_fields_after_render', array( $this, 'add_js_to_edit_vc_form') );
        LaStudio_Shortcodes_Param::get_instance();
    }

    public function formatting($content) {
        $shortcodes = array_merge($this->_shortcodes, $this->_woo_shortcodes, array('la_dropcap', 'la_quote', 'la_text', 'lastudio_playlist', 'lastudio_last_releases', 'la_last_video', 'lastudio_event_list', 'la_wishlist', 'la_compare'));
        $block = join("|", $shortcodes);
        $content = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]", $content);
        $content = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)/","[/$2]", $content);
        return $content;
    }

    public function ajax_render_shortcode() {
        $tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';
        $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
        if( !empty($tag) && !empty($data) ) {
            $atts = isset($data['atts']) ? $data['atts'] : array();
            $content = isset($data['content']) ? $data['content'] : null;
            echo self::auto_detect_shortcode_callback($atts, $content, $tag);
        }
        die();
    }

    public function add_login_link( $atts, $content = null ){
        echo $this->auto_detect_shortcode_callback( $atts, $content, 'la_login_link');
    }

    public function add_logout_link( $atts, $content = null ){
        echo $this->auto_detect_shortcode_callback( $atts, $content, 'la_logout_link');
    }

    public function add_wishlist( $atts, $content = null){
        echo $this->auto_detect_shortcode_callback( $atts, $content, 'la_wishlist');
    }

    public function add_compare( $atts, $content = null){
        echo $this->auto_detect_shortcode_callback( $atts, $content, 'la_compare');
    }

    public function add_dropcap( $atts, $content = null){
        $style = $color = '';
        extract(shortcode_atts(array(
            'style' => 1,
            'color' => '',
        ), $atts));

        ob_start();

        ?><span class="la-dropcap style-<?php echo esc_attr($style);?>" style="color:<?php echo esc_attr($color); ?>"><?php echo wp_strip_all_tags($content, true); ?></span><?php

        return ob_get_clean();
    }

    public function add_quote_shortcode( $atts, $content = null ){
        $output = $style = $author = $link = $role = $el_class = '';
        extract(shortcode_atts(array(
            'style' => 1,
            'author' => '',
            'role' => '',
            'link'  => '',
            'el_class'  => ''
        ), $atts));

        if(empty($content)){
            return '';
        }
        $output .= '<blockquote class="la-blockquote style-'.esc_attr($style) . LaStudio_Shortcodes_Helper::getExtraClass($el_class).'"';
        if(!empty($link)){
            $output .= ' cite="'.esc_url($link).'"';
        }
        $output .= '>';

        $output .= LaStudio_Shortcodes_Helper::remove_js_autop($content, true);

        if(!empty($author)){
            $output .= '<footer>';
            if(!empty($link)){
                $output .= '<cite><a href="'.esc_url($link).'">';
            }
            $output .= esc_html($author);
            if(!empty($link)){
                $output .= '</a></cite>';
            }
            if(!empty($role)){
                $output .= sprintf('<span>%s</span>', esc_html($role));
            }
            $output .= '</footer>';
        }
        $output .= '</blockquote>';
        return $output;
    }

    public function add_text_shortcode( $atts, $content = null){
        $output = $color = $font_size = $line_height = $el_class = '';

        extract(shortcode_atts(array(
            'color' => '',
            'font_size' => '',
            'line_height' => '',
            'el_class'  => ''
        ), $atts));

        $adv_atts = '';

        if(empty($content)){
            return $output;
        }
        $unique_id = uniqid('la_text_');
        if(!empty($color)){
            $adv_atts = 'style="color:';
            $adv_atts .= esc_attr($color);
            $adv_atts .= '"';
        }
        if(!empty($font_size) || !empty($line_height)){
            $adv_atts .= LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
                'target' => '#'. $unique_id ,
                'media_sizes' => array(
                    'font-size' => $font_size,
                    'line-height' => $line_height
                )
            ));
        }
        $output = '<div id="'.$unique_id.'" class="js-el la-text '. LaStudio_Shortcodes_Helper::getExtraClass($el_class) .'"'. $adv_atts .'>';
        $output .= $content;
        $output .= '</div>';
        return $output;
    }

    public function add_js_to_edit_vc_form(){
        echo '<script type="text/javascript">';
        if(!empty($_POST['tag']) && $_POST['tag'] == 'vc_section'){
            echo 'LaVCAdminEditForm("vc_section");';
        }
        if(!empty($_POST['tag']) && $_POST['tag'] == 'vc_row' && !empty($_POST['parent_tag']) && $_POST['parent_tag'] == 'vc_section'){
            echo 'LaVCAdminEditForm("vc_row");';
        }
        if(!empty($_POST['tag']) && $_POST['tag'] == 'la_image_with_hotspots'){
            echo 'LaVCAdminEditForm("la_image_with_hotspots");';
        }
        echo '</script>';
    }

    public function add_navmenu( $atts, $content = null){
        $menu_id = $container_class = '';
        extract(shortcode_atts(array(
            'menu_id' => '',
            'container_class' => '',
        ), $atts));
        if(!is_nav_menu( $menu_id)){
            return '';
        }

        $args = array(
            'menu' => $menu_id
        );
        if(!empty($container_class)){
            $args['container_class'] = $container_class;
        }else{
            $args['container'] = false;
        }
        ob_start();
        wp_nav_menu( $args );
        return ob_get_clean();
    }

    public function auto_detect_shortcode_callback( $atts, $content = null, $shortcode_tag ) {

        if(!empty($atts['enable_ajax_loader'])){
            unset($atts['enable_ajax_loader']);
            return self::get_template(
                'ajax_wrapper',
                array(
                    'shortcode_tag' => $shortcode_tag,
                    'atts' => $atts,
                    'content' => $content
                ),
                true
            );
        }

        return self::get_template(
            $shortcode_tag,
            array(
                'atts' => $atts,
                'content' => $content
            ),
            true
        );
    }

    public static function locate_config_shortcode_template( $path, $load = false, $require_once = false){
        $config_templates = 'la_configs/';
        $theme_template = $config_templates . $path . '.php';
        $plugin_template = self::$shortcode_path . 'configs/' . $path . '.php';
        $located = la_locate_template(array(
            $theme_template
        ), $load, $require_once);

        if( ! $located && file_exists( $plugin_template ) ){
            return apply_filters( 'LaStudio/shortcode/locate_shortcode_config_template', $plugin_template, $path );
        }
        return apply_filters( 'LaStudio/shortcode/locate_shortcode_config_template', $located, $path );
    }

    public static function locate_template( $path, $var = null, $load = false, $require_once = true ) {

        $vc_templates = 'vc_templates/';

        $theme_template = $vc_templates . $path . '.php';
        $plugin_template = self::$shortcode_path . 'templates/' . $path . '.php';

        $located = la_locate_template(array(
            $theme_template
        ), $load, $require_once);

        if( ! $located && file_exists( $plugin_template ) ){
            return apply_filters( 'LaStudio/shortcode/locate_template', $plugin_template, $path );
        }

        return apply_filters( 'LaStudio/shortcode/locate_template', $located, $path );

    }

    public static function get_template( $path, $var = null, $return = false ) {

        $located = self::locate_template( $path, $var );

        if( $var && is_array( $var ) ) {
            extract( $var, EXTR_SKIP );
        }

        if( $return ) {
            ob_start();
        }

        include ( $located );

        if( $return ) {
            return ob_get_clean();
        }
    }

    public function remove_admin_on_vc_inline_mode(){
        if(function_exists('vc_is_inline') && vc_is_inline()){
            show_admin_bar(false);
        }
    }

    /*
    * For WooCommerce
    */

    public function modify_woocommerce_shortcodes( $shortcode ){
        return "{$shortcode}_deprecated";
    }

    public function remove_old_woocommerce_shortcode(){
        foreach ($this->_woo_shortcodes as $shortcode) {
            remove_shortcode( "{$shortcode}_deprecated" );
        }
    }

    public function vc_param_animation_style_list( $style ){
        if(!is_array( $style ) ){
            $style = array();
        }
        $style[] = array(
            'label' => __( 'Infinite Animations', 'lastudio' ),
            'values' => array(
                __( 'InfiniteRotate', 'lastudio' ) => array(
                    'value' => 'InfiniteRotate',
                    'type' => 'infinite'
                ),
                __( 'InfiniteRotateCounter', 'lastudio' ) => array(
                    'value' => 'InfiniteRotateCounter',
                    'type' => 'infinite'
                ),
                __( 'InfiniteDangle', 'lastudio' ) => array(
                    'value' => 'InfiniteDangle',
                    'type' => 'infinite'
                ),
                __( 'InfiniteSwing', 'lastudio' ) => array(
                    'value' => 'InfiniteSwing',
                    'type' => 'infinite'
                ),
                __( 'InfinitePulse', 'lastudio' ) => array(
                    'value' => 'InfinitePulse',
                    'type' => 'infinite'
                ),
                __( 'InfiniteHorizontalShake', 'lastudio' ) => array(
                    'value' => 'InfiniteHorizontalShake',
                    'type' => 'infinite'
                ),
                __( 'InfiniteVericalShake', 'lastudio' ) => array(
                    'value' => 'InfiniteVericalShake',
                    'type' => 'infinite'
                ),
                __( 'InfiniteBounce', 'lastudio' ) => array(
                    'value' => 'InfiniteBounce',
                    'type' => 'infinite'
                ),
                __( 'InfiniteFlash', 'lastudio' ) => array(
                    'value' => 'InfiniteFlash',
                    'type' => 'infinite'
                ),
                __( 'InfiniteTADA', 'lastudio' ) => array(
                    'value' => 'InfiniteTADA',
                    'type' => 'infinite'
                ),
                __( 'InfiniteRubberBand', 'lastudio' ) => array(
                    'value' => 'InfiniteRubberBand',
                    'type' => 'infinite'
                ),
                __( 'InfiniteHorizontalFlip', 'lastudio' ) => array(
                    'value' => 'InfiniteHorizontalFlip',
                    'type' => 'infinite'
                ),
                __( 'InfiniteVericalFlip', 'lastudio' ) => array(
                    'value' => 'InfiniteVericalFlip',
                    'type' => 'infinite'
                ),
                __( 'InfiniteHorizontalScaleFlip', 'lastudio' ) => array(
                    'value' => 'InfiniteHorizontalScaleFlip',
                    'type' => 'infinite'
                ),
                __( 'InfiniteVerticalScaleFlip', 'lastudio' ) => array(
                    'value' => 'InfiniteVerticalScaleFlip',
                    'type' => 'infinite'
                )
            )
        );

        $style[] = array(
            'label' => __( 'Carousel Animations', 'lastudio' ),
            'values' => array(
                __( 'fadeIn', 'lastudio' ) => array(
                    'value' => 'fadeIn',
                    'type' => 'carousel'
                ),
                __( 'fadeInDown', 'lastudio' ) => array(
                    'value' => 'fadeInDown',
                    'type' => 'carousel'
                ),
                __( 'fadeInUp', 'lastudio' ) => array(
                    'value' => 'fadeInUp',
                    'type' => 'carousel'
                ),
                __( 'zoomIn', 'lastudio' ) => array(
                    'value' => 'zoomIn',
                    'type' => 'carousel'
                ),
                __( 'slickZoomIn', 'lastudio' ) => array(
                    'value' => 'slickZoomIn',
                    'type' => 'carousel'
                ),
                __( 'zoomInDown', 'lastudio' ) => array(
                    'value' => 'zoomInDown',
                    'type' => 'carousel'
                ),
                __( 'zoomInUp', 'lastudio' ) => array(
                    'value' => 'zoomInUp',
                    'type' => 'carousel'
                ),
                __( 'slideInDown', 'lastudio' ) => array(
                    'value' => 'slideInDown',
                    'type' => 'carousel'
                ),
                __( 'slideInUp', 'lastudio' ) => array(
                    'value' => 'slideInUp',
                    'type' => 'carousel'
                )
            )
        );

        return $style;
    }

}
