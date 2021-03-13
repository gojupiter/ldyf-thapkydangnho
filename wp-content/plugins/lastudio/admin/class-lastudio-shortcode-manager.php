<?php

class LaStudio_Shortcode_Manager {

    public $options = array();

    public $shortcodes = array();

    public $exclude_post_types = array();

    private static $instance = null;

    public function __construct( $options ) {

        $this->options = $options;
        $this->exclude_post_types = array();

        if( ! empty( $this->options ) ) {

            $this->shortcodes = $this->get_shortcodes();

            add_action( 'media_buttons', array( $this, 'media_shortcode_button' ), 99 );
            add_action( 'admin_footer', array( $this, 'shortcode_dialog' ), 99 );
            add_action( 'customize_controls_print_footer_scripts', array( $this, 'shortcode_dialog' ), 99 );
            add_action( 'wp_ajax_la-get-shortcode', array( $this, 'shortcode_generator' ), 99 );

        }

    }

    public static function instance( $options = array() ){
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $options );
        }
        return self::$instance;
    }

    public function media_shortcode_button( $editor_id ) {

        global $post;

        $post_type = ( isset( $post->post_type ) ) ? $post->post_type : '';

        if( ! in_array( $post_type, $this->exclude_post_types ) ) {
            echo '<a href="#" class="button button-primary la-shortcode" data-editor-id="'. $editor_id .'">'. __( 'Add Shortcode', 'lastudio' ) .'</a>';
        }

    }

    public function shortcode_dialog() {
        ?>
        <div id="la-shortcode-dialog" class="la-dialog" title="<?php _e( 'Add Shortcode', 'lastudio' ); ?>">
            <div class="la-dialog-header">
                <select class="<?php echo ( is_rtl() ) ? 'chosen-rtl ' : ''; ?>la-dialog-select" data-placeholder="<?php _e( 'Select a shortcode', 'lastudio' ); ?>">
                    <option value=""></option>
                    <?php
                    foreach ( $this->options as $group ) {
                        echo '<optgroup label="'. $group['title'] .'">';
                        foreach ( $group['shortcodes'] as $shortcode ) {
                            $view = ( isset( $shortcode['view'] ) ) ? $shortcode['view'] : 'normal';
                            echo '<option value="'. $shortcode['name'] .'" data-view="'. $view .'">'. $shortcode['title'] .'</option>';
                        }
                        echo '</optgroup>';
                    }
                    ?>
                </select>
            </div>
            <div class="la-dialog-load"></div>
            <div class="la-insert-button hidden">
                <a href="#" class="button button-primary la-dialog-insert"><?php _e( 'Insert Shortcode', 'lastudio' ); ?></a>
            </div>
        </div>
        <?php
    }

    public function shortcode_generator() {

        $request = la_get_var( 'shortcode' );

        if( empty( $request ) ) { die(); }

        $shortcode = $this->shortcodes[$request];

        if( isset( $shortcode['fields'] ) ) {

            foreach ( $shortcode['fields'] as $key => $field ) {

                if( isset( $field['id'] ) ) {
                    $field['attributes'] = ( isset( $field['attributes'] ) ) ? wp_parse_args( array( 'data-atts' => $field['id'] ), $field['attributes'] ) : array( 'data-atts' => $field['id'] );
                }

                $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

                if( in_array( $field['type'], array('image_select', 'checkbox') ) && isset( $field['options'] ) ) {
                    $field['attributes']['data-check'] = true;
                }

                if( in_array( $field['type'], array('responsive', 'column_responsive') ) ) {
                    $field['attributes']['data-parent-field'] = true;
                }

                echo la_fw_add_element( $field, $field_default, 'shortcode' );

            }

        }

        if( isset( $shortcode['clone_fields'] ) ) {

            $clone_id = isset( $shortcode['clone_id'] ) ? $shortcode['clone_id'] : $shortcode['name'];

            echo '<div class="la-shortcode-clone" data-clone-id="'. $clone_id .'">';
            echo '<a href="#" class="la-remove-clone"><i class="fa fa-trash"></i></a>';

            foreach ( $shortcode['clone_fields'] as $key => $field ) {

                $field['sub']        = true;
                $field['attributes'] = ( isset( $field['attributes'] ) ) ? wp_parse_args( array( 'data-clone-atts' => $field['id'] ), $field['attributes'] ) : array( 'data-clone-atts' => $field['id'] );
                $field_default       = ( isset( $field['default'] ) ) ? $field['default'] : '';

                if( in_array( $field['type'], array('image_select', 'checkbox') ) && isset( $field['options'] ) ) {
                    $field['attributes']['data-check'] = true;
                }

                echo la_fw_add_element( $field, $field_default, 'shortcode' );

            }

            echo '</div>';

            echo '<div class="la-clone-button"><a id="shortcode-clone-button" class="button" href="#"><i class="fa fa-plus-circle"></i> '. $shortcode['clone_title'] .'</a></div>';

        }

        die();
    }

    // getting shortcodes from config array
    public function get_shortcodes() {

        $shortcodes = array();

        foreach ( $this->options as $group_value ) {
            foreach ( $group_value['shortcodes'] as $shortcode ) {
                $shortcodes[$shortcode['name']] = $shortcode;
            }
        }

        return $shortcodes;
    }

}