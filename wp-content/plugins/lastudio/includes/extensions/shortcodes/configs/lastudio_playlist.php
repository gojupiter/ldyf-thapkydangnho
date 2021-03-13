<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

// Playlist Shortcode

$choices = array();

$playlists = get_posts( array( 'post_type' => 'lpm_playlist', 'posts_per_page' => -1, ) ); // get all playlist

foreach ( $playlists as $playlist ) {
    $choices[ $playlist->ID ] = $playlist->post_title;
}

// if no result display "no playlist"
if ( array() == $choices ) {
    $choices[0] = esc_html__( 'No playlist created yet', 'lastudio' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Playlist', 'lastudio' ),
        'param_name' => 'id',
        'value' => array_flip( $choices ),
        'admin_label' => true,
    ),

    array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Tracklist Visibility', 'lastudio' ),
        'param_name' => 'show_tracklist',
        'value' => array(
            esc_html__( 'Show', 'lastudio' ) => 'true',
            esc_html__( 'Hide', 'lastudio' ) => 'false'
        )
    ),
    
    array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Is Sticky Player?', 'lastudio' ),
        'param_name' => 'is_sticky_player',
        'value' => array(
            esc_html__( 'No', 'lastudio' ) => 'false',
            esc_html__( 'Yes', 'lastudio' ) => 'true'
        )
    ),

    array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Skin', 'lastudio' ),
        'param_name' => 'theme',
        'value' => array(
            esc_html__( 'Dark', 'lastudio' ) => 'dark',
            esc_html__( 'Light', 'lastudio' ) => 'light'
        )
    ),

    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Extra Class name', 'lastudio'),
        'param_name' 	=> 'el_class',
        'description' 	=> __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'lastudio')
    ),
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Playlist', 'lastudio'),
        'base'			=> 'lastudio_playlist',
        'icon'          => 'dashicons-before dashicons-playlist-audio',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display one of your playlist','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'lastudio_playlist'
);