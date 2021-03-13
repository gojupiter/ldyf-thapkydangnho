<?php
/**
 * Child Theme Function
 *
 */

add_action( 'after_setup_theme', 'draven_child_theme_setup' );
add_action( 'wp_enqueue_scripts', 'draven_child_enqueue_styles', 20);

if( !function_exists('draven_child_enqueue_styles') ) {
    function draven_child_enqueue_styles() {
        wp_enqueue_style( 'draven-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( 'draven-theme' ),
            wp_get_theme()->get('Version')
        );

    }
}

if( !function_exists('draven_child_theme_setup') ) {
    function draven_child_theme_setup() {
        load_child_theme_textdomain( 'draven-child', get_stylesheet_directory() . '/languages' );
    }
}