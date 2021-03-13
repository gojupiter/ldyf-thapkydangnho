<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


class Draven_Widget_CssClass {

    public function __construct() {
        add_action( 'wp_loaded', array( $this, 'load' ) );
        add_action( 'init', array( $this, 'init' ) );
    }

    public function load(){
        if(!is_admin()){
            add_filter( 'dynamic_sidebar_params', array( $this, 'addWidgetClasses' ) );
        }
    }

    public function init(){
        if(is_admin()){
            add_action( 'in_widget_form', array( $this, 'extendWidgetForm' ), 10, 3 );
            add_filter( 'widget_update_callback', array( $this, 'updateWidget' ), 10, 2 );
        }
    }

    public function extendWidgetForm($widget, $return, $instance){
        if ( !isset( $instance['classes'] ) ) $instance['classes'] = null;

        $widget_id      = 'widget-'.$widget->id_base.'-'.$widget->number.'-classes';
        $widget_name    = 'widget-'.$widget->id_base.'['.$widget->number.'][classes]';

        $fields = sprintf(
            '<p><label for="%s">%s</label><input type="text" name="%s" id="%s" value="%s" class="widefat"/></p>',
            esc_attr($widget_id),
            esc_html__('CSS Classes', 'draven'),
            esc_attr($widget_name),
            esc_attr($widget_id),
            esc_attr($instance['classes'])
        );

        do_action( 'widget_css_classes_form', $fields, $instance );

        echo draven_render_variable($fields);
        return $instance;
    }

    public function updateWidget($instance, $new_instance){
        if(isset($new_instance['classes'])){
            $instance['classes'] = $new_instance['classes'];
        }
        do_action( 'widget_css_classes_update', $instance, $new_instance );
        return $instance;
    }

    public function addWidgetClasses($params){
        global $wp_registered_widgets, $widget_number;

        $widget_id              = $params[0]['widget_id'];
        $widget_obj             = $wp_registered_widgets[$widget_id];
        $widget_num             = $widget_obj['params'][0]['number'];
        $widget_opt             = null;

        if ( isset( $widget_obj['callback'][0]->option_name ) ) {
            $widget_opt = get_option( $widget_obj['callback'][0]->option_name );
        }
        // Add classes
        if ( isset( $widget_opt[$widget_num]['classes'] ) && !empty( $widget_opt[$widget_num]['classes'] ) ) {
            $params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1 );
        }
        do_action( 'widget_css_classes_add_classes', $params, $widget_id, $widget_number, $widget_opt, $widget_obj );
        return $params;
    }

}