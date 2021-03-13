<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


class LaStudio_Widget_Twitter_Feed extends WP_Widget {

    public function __construct(){
        parent::__construct(
            'twitter_feed',
            esc_html__('[LaStudio] - Twitter Feed', 'lastudio'),
            array(
                'description' => esc_html__('Display Twitter Feed', 'lastudio')
            )
        );
    }

    public function widget($args, $instance){
        $screen_name = ! empty( $instance['screen_name'] ) ? sanitize_title($instance['screen_name']) : '';
        $number = ! empty( $instance['number'] ) ? absint($instance['number']) : 1;
        $enable_slider = ! empty( $instance['enable_slider'] ) ? $instance['enable_slider'] : '';

        echo ($args['before_widget']);
        if ( ! empty( $instance['title'] ) ) {
            echo ($args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']);
        }
        printf(
            '<div class="twitter-feed %s"><div class="la-tweets-feed js-el" data-profile="%s" data-amount="%s" data-la_component="tweetsFeed"></div></div>',
            (!empty($enable_slider) ? 'tweets-slider' : ''),
            esc_attr($screen_name),
            esc_attr($number)
        );
        echo ($args['after_widget']);
    }
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $screen_name = ! empty( $instance['screen_name'] ) ? sanitize_title($instance['screen_name']) : '';
        $number = ! empty( $instance['number'] ) ? absint($instance['number']) : 1;
        $enable_slider = ! empty( $instance['enable_slider'] ) ? $instance['enable_slider'] : '';

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'lastudio' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'screen_name' ) ); ?>"><?php esc_attr_e( 'Screen Name:', 'lastudio' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'screen_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'screen_name' ) ); ?>" type="text" value="<?php echo esc_attr( $screen_name ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number Feed:', 'lastudio' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" value="<?php echo esc_attr( $number ); ?>">
        </p>
        <p>
            <input value="1" <?php checked($enable_slider, 1); ?> type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'enable_slider' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'enable_slider' ) ); ?>"/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'enable_slider' ) ); ?>"><?php esc_attr_e( 'Enable Slider:', 'lastudio' ); ?></label>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['screen_name'] = ( ! empty( $new_instance['screen_name'] ) ) ? sanitize_title($new_instance['screen_name']) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? $new_instance['number'] : 1;
        $instance['enable_slider'] = ( ! empty( $new_instance['enable_slider'] ) ) ? $new_instance['enable_slider'] : '';
        return $instance;
    }
}