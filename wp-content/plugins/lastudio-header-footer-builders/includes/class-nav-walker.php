<?php
/**
 * Header Builder - Field Class.
 *
 * @author  LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHFB_Nav_Walker' ) ) :

    class LAHFB_Nav_Walker extends Walker_Nav_Menu{

        public $custom_block = null;
        public $current_popup_column = 4;
        public $is_megamenu = false;

        // add popup class to ul sub-menus
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            $submenu_custom_style = '';
            $out_div = '';
            if($depth == 0){
                $submenu_custom_style = isset( $args->popup_custom_style ) ? ' style="' . esc_attr( $args->popup_custom_style ) . '"' : '';
                $args->popup_custom_style = '';
            }
            $output .= "{$out_div}<ul class=\"sub-menu\">";
            if( $depth == 0 && $this->is_megamenu ) {
                $output .= '<li class="mm-mega-li"'.$submenu_custom_style.'><ul class="mm-mega-ul">';
            }
        }

        public function end_lvl( &$output, $depth = 0, $args = array() ) {
            $out_div = '';
            if($depth == 0){
                $this->current_popup_column = 4;
                if($this->is_megamenu){
                    $out_div .= '</li></ul>';
                }
                $this->is_megamenu = false;
            }
            if( $depth == 1 && !empty( $this->custom_block )){
                $out_div .= '<div class="mm-menu-block menu-block-after">'.do_shortcode('[elementor-template id="'.esc_attr($this->custom_block).'"]').'</div>';
                $this->custom_block = null;
            }
            $output .= "</ul>{$out_div}";
        }

        public function mega_start_el( &$output, $item, $depth=0, $args=array(),$current_object_id=0 ){
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            $class_names = $value = $mega_class = '';


            $is_mega = false;

            if($depth == 0 && isset($item->menu_type) && $item->menu_type == "wide"){
                $is_mega = true;
            }

            if( $is_mega ) {
                $mega_class = ' mega';
            }

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'mm-lv-' . $depth;

            if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ){
                $classes[] = 'active';
            }

            if ($depth == 0) {
                $popup_custom_styles = '';
                if ($item->menu_type == "wide") {

                    $this->is_megamenu = true;

                    if ($item->popup_column == ""){
                        $item->popup_column = 4;
                    }

                    $this->current_popup_column = $item->popup_column;

                    $classes[] = "mm-popup-wide";
                    $classes[] = "mm-popup-column-{$item->popup_column}";

                    if(!empty($item->popup_background)){
                        $popup_background = shortcode_atts(array(
                            'image' => '',
                            'repeat' => 'repeat',
                            'position' => 'left top',
                            'attachment' => 'scroll',
                            'size' => '',
                            'color' => ''
                        ),$item->popup_background);
                        $popup_custom_styles .= LAHFB_Helper::render_background_atts($popup_background, false);
                    }
                    if(isset($item->popup_max_width) && !empty($item->popup_max_width)){
                        $popup_custom_styles .= 'max-width:' . absint($item->popup_max_width) . 'px;';
                        $classes[] = "mm-popup-max-width";
                    }
                    if( $item->force_full_width ){
                        $classes[] = "mm-popup-force-fullwidth";
                    }
                }
                else {
                    $classes[] = "mm-popup-narrow";
                }
                if(!empty($item->custom_style)){
                    $popup_custom_styles .= $item->custom_style;
                }
                $popup_custom_styles = str_replace('"', '\'', $popup_custom_styles);

                $args->popup_custom_style = $popup_custom_styles;
            }

            if ($depth == 1) {
                $popup_custom_styles = '';
                if ( !empty( $item->popup_background ) ) {
                    $popup_background = shortcode_atts(array(
                        'image' => '',
                        'repeat' => 'repeat',
                        'position' => 'left top',
                        'attachment' => 'scroll',
                        'size' => '',
                        'color' => ''
                    ), $item->popup_background);
                    $popup_custom_styles .= LAHFB_Helper::render_background_atts($popup_background, false);
                }
                if ( !empty( $item->custom_style ) ) {
                    $popup_custom_styles .= $item->custom_style;
                }
                $popup_custom_styles = str_replace( '"', '\'', $popup_custom_styles );

                /** waiting for options behind */
                $args->popup_custom_style = $popup_custom_styles;

                if ( $item->block || $item->block2) {
                    $classes[] = 'mm-menu-custom-block';
                }
                if($item->block){
                    $classes[] = 'mm-menu-custom-block-before';
                }
                if ( $item->block2 ) {
                    $classes[] = 'mm-menu-custom-block-after';
                    $this->custom_block = $item->block2;
                }
            }

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . $mega_class . '"' : '';


            $output .= $indent . '<li'  . $value . $class_names;

            if( $depth == 1 && $this->is_megamenu ) {
                $item_column = $item->item_column ? number_format(floatval($item->item_column), 2) : 1;
                if($item_column <= 0){
                    $item_column = 1;
                }
                if($this->current_popup_column <= 0 ) {
                    $this->current_popup_column = 1;
                }
                $item_width = round( $item_column / $this->current_popup_column , 4) * 100;
                $output .= ' data-column="'.$item_column.'" style="width: '.$item_width.'%"';
            }

            $output .= '>';

            $atts = array();
            $atts['title']      = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target']     = ! empty( $item->target )     ? $item->target     : '';
            $atts['rel']        = ! empty( $item->xfn )        ? $item->xfn        : '';
            $atts['href']       = ! empty( $item->url )        ? $item->url        : '';
            $atts['data-description']       = ! empty( $item->description )        ? $item->description        : '';

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
            $attributes = '';
            $item_output = '';

            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr && ! empty( $item->url ) ) ? esc_url( $value ) : '#';
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $badge_text = isset($item->tip_label) && !empty($item->tip_label) ? $item->tip_label : false;
            $badge_text_css1 = $badge_text_css2 = '';
            if($badge_text){
                if(isset($item->tip_color) && !empty($item->tip_color)){
                    $badge_text_css1 .= 'color:' . $item->tip_color . ';';
                    $badge_text_css2 = 'border-top-color:' . $item->tip_color;
                }
                if(isset($item->tip_background_color) && !empty($item->tip_background_color)){
                    $badge_text_css1 .= 'background-color:' . $item->tip_background_color;
                    $badge_text_css2 = 'border-top-color:' . $item->tip_background_color;
                }
            }

            if(isset($args->before)){
                $item_output .= $args->before;
            }

            $item_output .= '<a'. $attributes. '>';
            if(!empty($item->icon)){
                $item_output .= '<i class="mm-icon '.$item->icon.'"></i>';
            }

            if(isset($args->link_before)){
                $item_output .= $args->link_before;
            }

            $item_output .= apply_filters( 'the_title', $item->title, $item->ID );

            if(isset($args->link_after)){
                $item_output .= $args->link_after;
            }

            if(!empty($badge_text)){
                $item_output .= '<span class="menu-item-badge"><span class="menu-item-badge-text" style="'.$badge_text_css1.'">'.$badge_text.'</span><span class="menu-item-badge-border" style="'.$badge_text_css2.'"></span></span>';
            }

            if ( ! empty( $item->description ) ) {
                $item_output .= '<div class="la-menu-desc">' . $item->description . '</div>';
            }
            $item_output .= '</a>';

            if(isset($args->after)){
                $item_output .= $args->after;
            }

            if ( $depth == 1 && !empty($item->block)){
                $item_output .= '<div class="mm-menu-block menu-block-before">'.do_shortcode('[elementor-template id="'.esc_attr($item->block).'"]').'</div>';
            }

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }

        public function default_start_el( &$output, $item, $depth=0, $args=array(),$current_object_id=0 ){

            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            $class_names = $value = $mega_class = '';


            $is_mega = false;

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'mm-lv-' . $depth;

            if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ){
                $classes[] = 'active';
            }


            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . $mega_class . '"' : '';


            $output .= $indent . '<li'  . $value . $class_names;

            $output .= '>';

            $atts = array();
            $atts['title']      = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target']     = ! empty( $item->target )     ? $item->target     : '';
            $atts['rel']        = ! empty( $item->xfn )        ? $item->xfn        : '';
            $atts['href']       = ! empty( $item->url )        ? $item->url        : '';
            $atts['data-description']       = ! empty( $item->description )        ? $item->description        : '';

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
            $attributes = '';
            $item_output = '';

            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr && ! empty( $item->url ) ) ? esc_url( $value ) : '#';
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $badge_text = isset($item->tip_label) && !empty($item->tip_label) ? $item->tip_label : false;
            $badge_text_css1 = $badge_text_css2 = '';
            if($badge_text){
                if(isset($item->tip_color) && !empty($item->tip_color)){
                    $badge_text_css1 .= 'color:' . $item->tip_color . ';';
                    $badge_text_css2 = 'border-top-color:' . $item->tip_color;
                }
                if(isset($item->tip_background_color) && !empty($item->tip_background_color)){
                    $badge_text_css1 .= 'background-color:' . $item->tip_background_color;
                    $badge_text_css2 = 'border-top-color:' . $item->tip_background_color;
                }
            }

            if(isset($args->before)){
                $item_output .= $args->before;
            }

            $item_output .= '<a'. $attributes. '>';
            if(!empty($item->icon)){
                $item_output .= '<i class="mm-icon '.$item->icon.'"></i>';
            }

            if(isset($args->link_before)){
                $item_output .= $args->link_before;
            }

            $item_output .= apply_filters( 'the_title', $item->title, $item->ID ) ;

            if(isset($args->link_after)){
                $item_output .= $args->link_after;
            }

            if(!empty($badge_text)){
                $item_output .= '<span class="menu-item-badge"><span class="menu-item-badge-text" style="'.$badge_text_css1.'">'.$badge_text.'</span><span class="menu-item-badge-border" style="'.$badge_text_css2.'"></span></span>';
            }

            if ( ! empty( $item->description ) ) {
                $item_output .= '<div class="la-menu-desc">' . $item->description . '</div>';
            }
            $item_output .= '</a>';

            if(isset($args->after)){
                $item_output .= $args->after;
            }

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args);

        }

        public function start_el(&$output, $item, $depth=0, $args=array(),$current_object_id=0){

            if(isset($args->show_megamenu) && $args->show_megamenu == 'true' ) {
                $this->mega_start_el( $output, $item, $depth, $args, $current_object_id);
            }
            else{
                $this->default_start_el( $output, $item, $depth, $args, $current_object_id);
            }

        }

        public static function fallback( $args ){
            $menu_id = uniqid('fallback-menu-');
            $output = str_replace( array("page_item", "menu-item_has_children","<ul class='children'>"), array("page_item menu-item", "menu-item-has-children","<ul class='sub-menu'>"), wp_list_pages('echo=0&title_li=') );
            return sprintf( $args['items_wrap'], $menu_id, $args['menu_class'], $output );
        }

    }

endif;