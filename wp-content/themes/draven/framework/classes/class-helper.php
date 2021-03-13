<?php if ( ! defined( 'ABSPATH' ) ) { die; }

if(!class_exists('Draven_Helper')){

    class Draven_Helper{

        /**
         * A reference to an instance of this class.
         *
         * @since 1.0.0
         * @var   object
         */
        private static $instance = null;

        /**
         * @var array
         */
        public $args = array();

        /**
         * Returns the instance.
         *
         * @since  1.0.0
         * @return object
         */
        public static function get_instance( ) {

            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }

        public static function is_active_woocommerce(){
            return function_exists('WC');
        }

        public static function compress_text($content, $css = false){
            $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
            $content = str_replace(array("\r\n", "\r", "\n", "\t",'  ','	', '	', '	', '                ', '    '), '', $content);
            if($css){
                $content = str_replace(array(';}'), array('}'), $content);
            }
            return $content;
        }

        public static function get_base_shop_url( $with_post_type_archive = true ){
            if(function_exists('la_get_base_shop_url')){
                return la_get_base_shop_url( $with_post_type_archive );
            }
            else{
                if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
                    $link = home_url();
                }
                elseif ( is_shop() ) {
                    $link = get_permalink( wc_get_page_id( 'shop' ) );
                }
                elseif ( is_product_category() ) {
                    $link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
                }
                elseif ( is_product_tag() ) {
                    $link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
                }
                else {
                    $queried_object = get_queried_object();
                    $link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
                }
                return $link;
            }
        }

        public static function get_hooks( $hook = '' ) {
            global $wp_filter;

            $hooks = isset( $wp_filter[$hook] ) ? $wp_filter[$hook] : array();

            if (class_exists('WP_Hook') && $hooks instanceof WP_Hook) {
                $hooks = $hooks->callbacks;
            }

            if(empty($hooks)){
                return;
            }

            foreach( $hooks as $key => &$items ) {
                foreach ( $items as &$item ){
                    $item['priority'] = $key;
                }
            }

            $hooks = call_user_func_array( 'array_merge', $hooks );

            foreach( $hooks as $key => &$item ) {
                // function name as string or static class method eg. 'Foo::Bar'
                if ( is_string( $item['function'] ) ) {
                    $ref = strpos( $item['function'], '::' ) ? new ReflectionClass( strstr( $item['function'], '::', true ) ) : new ReflectionFunction( $item['function'] );
                    $item['file'] = $ref->getFileName();
                    $item['line'] = get_class( $ref ) == 'ReflectionFunction'
                        ? $ref->getStartLine()
                        : $ref->getMethod( substr( $item['function'], strpos( $item['function'], '::' ) + 2 ) )->getStartLine();

                    // array( object, method ), array( string object, method ), array( string object, string 'parent::method' )
                } elseif ( is_array( $item['function'] ) ) {

                    $ref = new ReflectionClass( $item['function'][0] );

                    // $item['function'][0] is a reference to existing object
                    $item['function'] = array(
                        is_object( $item['function'][0] ) ? get_class( $item['function'][0] ) : $item['function'][0],
                        $item['function'][1]
                    );
                    $item['file'] = $ref->getFileName();
                    $item['line'] = strpos( $item['function'][1], '::' )
                        ? $ref->getParentClass()->getMethod( substr( $item['function'][1], strpos( $item['function'][1], '::' ) + 2 ) )->getStartLine()
                        : $ref->getMethod( $item['function'][1] )->getStartLine();

                    // closures
                } elseif ( is_callable( $item['function'] ) ) {
                    $ref = new ReflectionFunction( $item['function'] );
                    $item['function'] = get_class( $item['function'] );
                    $item['file'] = $ref->getFileName();
                    $item['line'] = $ref->getStartLine();
                }
            }
            echo '<pre>';
            echo "HOOK NAME : <b>$hook</b><br/>";
            print_r($hooks);
            echo '</pre>';
        }

        public static function remove_js_autop($content, $autop = false){
            if ( $autop ) {
                $content = preg_replace( '/<\/?p\>/', "\n", $content );
                $content = preg_replace( '/<p[^>]*><\\/p[^>]*>/', "", $content );
                $content = wpautop( $content . "\n" );
            }
            return do_shortcode( shortcode_unautop( $content ) );
        }

        public static function hex2rgba( $color, $opacity = false ) {
            $default = 'rgb(0,0,0)';
            if(empty($color)){
                return $default;
            }
            if ($color[0] == '#' ) {
                $color = substr( $color, 1 );
            }
            if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
            } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
            } else {
                return $default;
            }
            $rgb =  array_map('hexdec', $hex);
            if($opacity){
                if(abs($opacity) > 1)
                    $opacity = 1.0;
                $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
            } else {
                $output = 'rgb('.implode(",",$rgb).')';
            }
            return $output;
        }

        public static function get_color_codes( $color ) {
            $ret = array('hex' => '', 'rgba' => '');
            // Trim input string
            $color = trim($color);
            // Return default if no color provided
            if(empty($color)){
                return $ret;
            }
            // Sanitize $color if "#" is provided
            if ($color[0] == '#') {
                // Remove first char
                $color = substr($color, 1);
                // Check if color has 6 or 3 characters and get values
                if (strlen($color) == 6) {
                    $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
                } elseif (strlen( $color ) == 3) {
                    $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
                } else {
                    return $ret;
                }
                // Convert hexadec to rgb
                $ret['hex'] = '#'.$color;
                $ret['rgba'] = implode(",", array_map('hexdec', $hex));
            } else if (substr($color, 0, 4) == 'rgba') {
                preg_match("/^rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d*(?:\.\d+)?)\)$/i", $color, $rgba);
                // Count should be 5 if successfull
                if (count($rgba) == 5) {
                    $hex = "#";
                    $hex .= str_pad(dechex($rgba[1]), 2, "0", STR_PAD_LEFT);
                    $hex .= str_pad(dechex($rgba[2]), 2, "0", STR_PAD_LEFT);
                    $hex .= str_pad(dechex($rgba[3]), 2, "0", STR_PAD_LEFT);
                    $ret['hex'] = $hex;
                    $ret['rgba'] = $rgba[1].','.$rgba[2].','.$rgba[3];
                }
            } else if (substr($color, 0, 3) == 'rgb') {
                preg_match("/^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/i", $color, $rgba);
                // Count should be 5 if successfull
                if (count($rgba) == 4) {
                    $hex = "#";
                    $hex .= str_pad(dechex($rgba[1]), 2, "0", STR_PAD_LEFT);
                    $hex .= str_pad(dechex($rgba[2]), 2, "0", STR_PAD_LEFT);
                    $hex .= str_pad(dechex($rgba[3]), 2, "0", STR_PAD_LEFT);

                    $ret['hex'] = $hex;
                    $ret['rgba'] = $rgba[1].','.$rgba[2].','.$rgba[3];
                }
            }
            // Return calculated values
            return $ret;
        }

        public static function get_image_size_from_string( $size, $default = 'thumbnail' ){
            if(empty($size)){
                return $default;
            }
            $ignore = array(
                'thumbnail',
                'thumb',
                'medium',
                'large',
                'full'
            );
            if(false !== strpos($size, 'la_')){
                return $size;
            }
            $_wp_additional_image_sizes = wp_get_additional_image_sizes();
            if(is_string($size) && (in_array($size, $ignore) || (!empty($_wp_additional_image_sizes[$size]) && is_array($_wp_additional_image_sizes[$size]) ))){
                return $size;
            }
            else{
                preg_match_all( '/\d+/', $size, $thumb_matches );
                if ( isset( $thumb_matches[0] ) ) {
                    $thumb_size = array();
                    if ( count( $thumb_matches[0] ) > 1 ) {
                        $thumb_size[] = $thumb_matches[0][0]; // width
                        $thumb_size[] = $thumb_matches[0][1]; // height
                    }
                    elseif ( count( $thumb_matches[0] ) > 0 && count( $thumb_matches[0] ) < 2 ) {
                        $thumb_size[] = $thumb_matches[0][0]; // width
                        $thumb_size[] = 0; //$thumb_matches[0][0]; // height
                    }
                    else {
                        $thumb_size = $default;
                    }
                }
                else{
                    $thumb_size = $default;
                }
                return $thumb_size;
            }
        }

        public static function get_slick_slider_config($default = array()){
            $configs = array_merge($configs = array(
                'infinite' => false,
                'xlg' => 1,
                'lg' => 1,
                'md' => 1,
                'sm' => 1,
                'xs' => 1,
                'mb' => 1,
                'dots' => false,
                'autoplay' => false,
                'arrows' => false,
                'speed' => 300,
                'autoplaySpeed' => 3000,
                'custom_nav' => ''
            ), $default);
            $slider_config = array(
                'infinite' => $configs['infinite'],
                'dots' => $configs['dots'],
                'slidesToShow' => absint($configs['xlg']),
                'slidesToScroll' => absint($configs['xlg']),
                'autoplay' => $configs['autoplay'],
                'arrows' => $configs['arrows'],
                'speed' => $configs['speed'],
                'autoplaySpeed' => $configs['autoplaySpeed'],
                'responsive' => array(
                    array(
                        'breakpoint' => 1824,
                        'settings' => array(
                            'slidesToShow' => absint($configs['lg']),
                            'slidesToScroll' => absint($configs['lg'])
                        )
                    ),
                    array(
                        'breakpoint' => 1200,
                        'settings' => array(
                            'slidesToShow' => absint($configs['md']),
                            'slidesToScroll' => absint($configs['md'])
                        )
                    ),
                    array(
                        'breakpoint' => 992,
                        'settings' => array(
                            'slidesToShow' => absint($configs['sm']),
                            'slidesToScroll' => absint($configs['sm'])
                        )
                    ),
                    array(
                        'breakpoint' => 768,
                        'settings' => array(
                            'slidesToShow' => absint($configs['xs']),
                            'slidesToScroll' => absint($configs['xs'])
                        )
                    ),
                    array(
                        'breakpoint' => 480,
                        'settings' => array(
                            'slidesToShow' => absint($configs['mb']),
                            'slidesToScroll' => absint($configs['mb'])
                        )
                    )
                )
            );
            if(isset($configs['custom_nav']) && !empty($configs['custom_nav'])){
                $slider_config['appendArrows'] = 'jQuery("'.esc_attr($configs['custom_nav']).'")';
            }
            return json_encode($slider_config);
        }

        public static function render_canvas_space( $options, $echo = true ){
            $css = array();
            if(!empty($options) && is_array($options)){
                $tmp = array();
                foreach($options as $k => $v){
                    if(strpos($k, 'border_') === false){
                        if($v !== ''){
                            $css[] = str_replace('_', '-', $k) . ':' . esc_attr($v);
                        }
                    }
                    else{
                        $_k_tmp = str_replace('border_', '', $k);
                        if( $_k_tmp != 'style' && $_k_tmp != 'color' && $v !== ''){
                            $tmp[$_k_tmp] = $v;
                        }
                    }
                }
                if(!empty($options['border_color']) && !empty($options['border_style']) && !empty($tmp)){
                    foreach($tmp as $_k => $_v){
                        $css[] = 'border-' . $_k . '-width:' . $_v ;
                        $css[] = 'border-' . $_k . '-style:' . $options['border_style'] ;
                        $css[] = 'border-' . $_k . '-color:' . $options['border_color'] ;
                    }
                }

            }
            if($echo){
                echo join(';', $css);
            }
            else{
                return join(';', $css);
            }
        }

        public static function render_background_atts($options, $echo = true){
            $return = '';
            if(!empty($options) && is_array($options)){
                foreach ($options as $k => $val){
                    if(!empty($val)){
                        if($echo){
                            $return .= sprintf('background-%s: %s;'
                                , esc_attr($k)
                                , ($k == 'image' ? 'url('.esc_url( str_replace(array('https://', 'http://'), '//', $val) ).')' : esc_attr($val))
                            );
                        }
                        else{
                            $return .= sprintf('background-%s: %s;'
                                , esc_attr($k)
                                , ($k == 'image' ? 'url('.esc_url( str_replace(array('https://', 'http://'), '//', $val) ).')' : esc_attr($val))
                            );
                        }
                    }
                }
            }
            if($echo){
                echo draven_render_variable($return);
            }
            else{
                return $return;
            }
        }

        public static function getRemainingTime( $end_date ){
            $days    = floor( ( $end_date - time() ) / 60 / 60 / 24 );
            $hours   = floor( ( $end_date - time() ) / 60 / 60 ) - ( $days * 24 );
            $minutes = floor( ( $end_date - time() ) / 60 ) - ( $hours * 60 );
            $seconds = ( $end_date - time() ) - ( $minutes * 60 );
            return array(
                'gmt' => get_option( 'gmt_offset' ),
                'to'  => $end_date,
                'dd'  => ( $days > 10 ) ? $days : '0' . $days,
                'hh'  => ( $hours > 10 ) ? $hours : '0' . $hours,
                'mm'  => ( $minutes > 10 ) ? $minutes : '0' . $minutes,
                'ss'  => ( $seconds > 10 ) ? $seconds : '0' . $seconds,
            );
        }

        public static function get_google_font_ref_array( $arr_font = array() ){

            $return = array();

            if(!function_exists('la_get_google_fonts')){
                return $return;
            }

            if(!empty($arr_font)){

                $gfs_data = la_get_google_fonts();

                foreach ( $arr_font as $p => $f_name ){
                    $_tmp = array(
                        'family' => $f_name,
                        'category' => '',
                        'variants' => '',
                        'subsets' => ''
                    );
                    foreach( $gfs_data->items as $k => $font ){
                        if(strtolower($f_name) == strtolower($font->family)){
                            $_tmp = array(
                                'family' => $font->family,
                                'category' => $font->category,
                                'variants' => $font->variants,
                                'subsets' => $font->subsets
                            );
                            break;
                        }
                    }
                    $return[$p] = $_tmp;
                }
            }

            return $return;

        }

        public static function is_not_empty_array_ref($array){
            $flag = array_filter( $array, function( $value ) { return $value !== ''; } );
            if(!empty($flag)){
                return true;
            }else{
                return false;
            }
        }

        public static function render_access_component( $type, $component = array(), $parent_name = '', $css_class = ''){

            $exist_flag     = false;

            $el_class       = !empty($component['el_class']) ? ' ' . $component['el_class'] : '';
            $icon_html      = '<i class="'.(!empty($component['icon']) ? $component['icon'] : 'fa fa-cog').'"></i>';
            $child_html     = '';
            $target_html    = '';
            $component_css_class    = '';
            $tpl        = '<div class="%1$s%2$s">%3$s%4$s</div>';
            if(!empty($component['text'])){
                $current_user_name = __('Guest', 'draven');
                if(is_user_logged_in()){
                    $current_user = wp_get_current_user();
                    $current_user_name = $current_user->display_name;
                }
                $component['text'] = str_replace('{{user_name}}', $current_user_name, $component['text']);
            }

            if(!empty($component['link'])){
                $logout_link = wp_logout_url();
                $login_link = wp_login_url();
                if(function_exists('WC')){
                    $logout_link = wc_get_account_endpoint_url('customer-logout');
                    $login_link = wc_get_account_endpoint_url('dashboard');
                }
                $component['link'] = str_replace(
                    array(
                        '{{logout_url}}',
                        '{{login_url}}'
                    ),
                    array(
                        $logout_link,
                        $login_link,
                    ),
                    $component['link']
                );
            }

            switch($type){
                case 'dropdown_menu':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--dropdown-menu la_compt_iem la_com_action--dropdownmenu ' . $css_class;

                    if(empty($component['icon']) && !empty($component['text'])){
                        $icon_html = '';
                    }
                    if(!empty($component['text'])){
                        $component_css_class .= ' la_com_action--dropdownmenu-text';
                        $icon_html .= '<span class="component-target-text">';
                        $icon_html .= $component['text'];
                        $icon_html .= '</span>';
                    }

                    $target_html = '<a rel="nofollow" class="component-target" href="javascript:;">'.$icon_html.'</a>';
                    if(isset($component['menu_id']) && ($menu_id = $component['menu_id']) && is_nav_menu($menu_id)){
                        $child_html = wp_nav_menu(array(
                            'container' => false,
                            'depth' => 1,
                            'echo' => false,
                            'menu' => $menu_id,
                            'fallback_cb' => false
                        ));
                    }

                    break;

                case 'primary_menu':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--primary-menu la_compt_iem la_com_action--primary-menu ' . $css_class;
                    $icon_html = '<i class="dlicon ui-1_zoom"></i>';
                    $target_html = '<a rel="nofollow" class="component-target" href="javascript:;">'.$icon_html.'</a>';
                    break;

                case 'text':
                    $component_css_class = $parent_name . ' ' . $parent_name . '--text la_compt_iem la_com_action--text ' . $css_class;

                    if(!empty($component['text'])){
                        $exist_flag = true;
                        $target_html .= '<span class="component-target">';
                        if(!empty($component['icon'])){
                            $target_html .= $icon_html;
                        }
                        $target_html .= '<span class="component-target-text">';
                        $target_html .= apply_filters('draven/filter/component/text', $component['text']);
                        $target_html .= '</span>';
                        $target_html .= '</span>';
                    }
                    else{
                        if(!empty($component['icon'])){
                            $exist_flag = true;
                            $target_html .= '<span class="component-target">'. $icon_html .'</span>';
                        }
                    }
                    break;

                case 'link_icon':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--link la_compt_iem la_com_action--link ' . $css_class;
                    $target_url = isset($component['link']) ? $component['link'] : '#';
                    $target_html = '<a rel="nofollow" class="component-target" href="'.$target_url.'">'.$icon_html.'</a>';
                    break;

                case 'link_text':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--linktext la_compt_iem la_com_action--linktext ' . $css_class;
                    $target_url = isset($component['link']) ? $component['link'] : '#';
                    if(empty($component['icon'])){
                       $icon_html = '';
                    }
                    if(!empty($component['text'])){
                        $icon_html .= '<span class="component-target-text">'. apply_filters('draven/filter/component/text', $component['text']) .'</span>';
                    }
                    $target_html = '<a rel="nofollow" class="component-target" href="'.$target_url.'">'.$icon_html.'</a>';
                    break;

                case 'search_1':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--searchbox la_compt_iem la_com_action--searchbox searchbox__01 ' . $css_class;
                    $icon_html = '<i class="dlicon ui-1_zoom"></i>';
                    $target_html = '<a class="component-target" href="javascript:;">'.$icon_html.'</a>';
                    break;

                case 'search_2':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--searchbox la_compt_iem la_com_action--searchbox searchbox__02 ' . $css_class;
                    $icon_html = '<i class="dlicon ui-1_zoom"></i>';
                    $target_html = '<a rel="nofollow" class="component-target" href="javascript:;">'.$icon_html.'</a>';
                    break;

                case 'cart':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--cart la_compt_iem la_com_action--cart ' . $css_class;
                    $target_url = isset($component['link']) ? $component['link'] : '#';
                    if(function_exists('wc_get_cart_url') && ($target_url == '#' || $target_url == '')){
                        $target_url = wc_get_cart_url();
                    }
                    $cart_count = '0';
                    $cart_total_price = '';
                    if(function_exists('WC')){
                        $cart_count = WC()->cart->get_cart_contents_count();
                        $cart_total_price = WC()->cart->get_cart_total();
                    }

                    $icon_html = '<i class="'.(!empty($component['icon']) ? $component['icon'] : 'dlicon shopping_cart-modern').'"></i>';

                    if(!empty($component['text'])){
                        $icon_html .= '<span class="component-target-text">'. apply_filters('draven/filter/component/text', $component['text']) .'</span>';
                        $component_css_class .= ' has-compt-text';
                    }
                    $icon_html .= '<span class="component-target-badget la-cart-count">'.$cart_count.'</span><span class="la-cart-total-price">'.$cart_total_price.'</span>';
                    $target_html = '<a rel="nofollow" class="component-target" href="'.$target_url.'">'.$icon_html.'</a>';
                    break;

                case 'wishlist':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--wishlist la_compt_iem la_com_action--wishlist ' . $css_class;
                    $target_url = isset($component['link']) ? $component['link'] : '#';
                    if(function_exists('yith_wcwl_object_id') && ($target_url == '#' || empty($target_url))){
                        $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
                        $target_url = get_the_permalink($wishlist_page_id);
                    }
                    $icon_html = '<i class="'.(!empty($component['icon']) ? $component['icon'] : 'dlicon ui-3_heart').'"></i><span class="component-target-badget la-wishlist-count">0</span>';
                    $target_html = '<a rel="nofollow" class="component-target" href="'.$target_url.'">'.$icon_html.'</a>';
                    break;

                case 'compare':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--compare la_compt_iem la_com_action--compare ' . $css_class;
                    $target_url = isset($component['link']) ? $component['link'] : '#';
                    $icon_html = '<i class="'.(!empty($component['icon']) ? $component['icon'] : 'dlicon arrows-1_loop-83').'"></i><span class="component-target-badget la-wishlist-count">0</span>';
                    $target_html = '<a rel="nofollow" class="component-target" href="'.$target_url.'">'.$icon_html.'</a>';
                    break;

                case 'aside_header':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--link la_compt_iem la_com_action--aside_header ' . $css_class;
                    $icon_html      = '<i class="'.(!empty($component['icon']) ? $component['icon'] : 'dlicon ui-2_menu-34').'"></i>';
                    $target_html = '<a rel="nofollow" class="component-target" href="javascript:;">'.$icon_html.'</a>';
                    break;

                case 'burger_menu':
                    $exist_flag = true;
                    $component_css_class = $parent_name . ' ' . $parent_name . '--link la_compt_iem la_com_action--burger_menu ' . $css_class;
                    $icon_html      = '<i class="'.(!empty($component['icon']) ? $component['icon'] : 'dlicon ui-2_menu-34').'"></i>';
                    $target_html = '<a rel="nofollow" class="component-target" href="javascript:;">'.$icon_html.'</a>';
                    break;
            }

            if($exist_flag){
                return sprintf( $tpl
                    , esc_attr( $component_css_class )
                    , esc_attr( $el_class )
                    , $target_html
                    , $child_html
                );
            }
            else{
                return '';
            }
        }

        public static function is_enable_image_lazy(){
            return apply_filters('draven/image/use_lazy_load', true);
        }

    }

}