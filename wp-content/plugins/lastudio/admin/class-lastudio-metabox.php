<?php

class LaStudio_Metabox {

    /**
     *
     * metabox options
     * @access public
     * @var array
     *
     */
    public $options = array();

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    // run metabox construct
    public function __construct( $options ) {

        $this->options = $options;

        if( ! empty( $this->options ) ) {
            add_action( 'add_meta_boxes', array( $this, 'add_meta_box') );
            add_action( 'save_post', array( $this, 'save_post'), 10, 2 );
        }

    }

    // instance
    public static function instance( $options = array() ){
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $options );
        }
        return self::$instance;
    }

    // add metabox
    public function add_meta_box( $post_type ) {

        foreach ( $this->options as $value ) {
            add_meta_box( $value['id'], $value['title'], array( $this, 'render_meta_box_content' ), $value['post_type'], $value['context'], $value['priority'], $value );
        }

    }

    // metabox render content
    public function render_meta_box_content( $post, $callback ) {

        global $post, $typenow;

        wp_nonce_field( 'lastudio', 'la-framework-metabox-nonce' );

        $unique     = $callback['args']['id'];
        $sections   = $callback['args']['sections'];
        $meta_value = get_post_meta( $post->ID, $unique, true );
        $transient  = get_transient( 'la-metabox-transient' );
        $la_errors  = isset($transient['errors']) ? $transient['errors'] : array();
        $has_nav    = ( count( $sections ) >= 2 && $callback['args']['context'] != 'side' ) ? true : false;
        $show_all   = ( ! $has_nav ) ? ' la-show-all' : '';
        $section_id = ( ! empty( $transient['ids'][$unique] ) ) ? $transient['ids'][$unique] : '';
        $section_id = la_get_var( 'la-section', $section_id );

        echo '<div class="la-framework la-metabox-framework">';

        echo '<input type="hidden" name="la_section_id['. $unique .']" class="la-reset" value="'. $section_id .'">';

        if(!empty($la_errors)) {
            foreach ( $la_errors as $error ) {
                echo '<div class="la-settings-error '. $error['type'] .'">';
                echo '<p><strong><span>'. $error['code'] .'</span> '. $error['message'] .'</strong></p>';
                echo '</div>';
            }
        }

        echo '<div class="la-body'. $show_all .'">';

        if( $has_nav ) {

            echo '<div class="la-nav">';

            echo '<ul>';
            $num = 0;
            foreach( $sections as $value ) {

                if( ! empty( $value['typenow'] ) && $value['typenow'] !== $typenow ) { continue; }

                $tab_icon = ( ! empty( $value['icon'] ) ) ? '<i class="la-tab-icon '. $value['icon'] .'"></i>' : '';

                if( isset( $value['fields'] ) ) {
                    $active_section = ( ( empty( $section_id ) && $num === 0 ) || $section_id == $value['name'] ) ? ' class="la-section-active"' : '';
                    echo '<li><a href="#"'. $active_section .' data-section="'. $value['name'] .'">'. $tab_icon . $value['title'] .'</a></li>';
                } else {
                    echo '<li><div class="la-seperator">'. $tab_icon . $value['title'] .'</div></li>';
                }

                $num++;
            }
            echo '</ul>';

            echo '</div>';

        }

        echo '<div class="la-content">';

        echo '<div class="la-sections">';
        $num = 0;
        foreach( $sections as $v ) {

            if( ! empty( $v['typenow'] ) && $v['typenow'] !== $typenow ) { continue; }

            if( isset( $v['fields'] ) ) {

                $active_content = ( ( empty( $section_id ) && $num === 0 ) || $section_id == $v['name'] ) ? ' style="display: block;"' : '';

                echo '<div id="la-tab-'. $v['name'] .'" class="la-section"'. $active_content .'>';
                echo ( isset( $v['title'] ) ) ? '<div class="la-section-title"><h3>'. $v['title'] .'</h3></div>' : '';

                foreach ( $v['fields'] as $field_key => $field ) {

                    $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                    $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
                    $elem_value = ( is_array( $meta_value ) && isset( $meta_value[$elem_id] ) ) ? $meta_value[$elem_id] : $default;

                    if( 'fieldset' == $field['type'] && !empty($field['fields']) && !empty($field['un_array'])){
                        $fieldset_value = array();
                        foreach($field['fields'] as $child_field ){
                            if(!empty($child_field['id'])){
                                $child_value_default = ( isset( $child_field['default'] ) ) ? $child_field['default'] : '';
                                $fieldset_value[$child_field['id']] = ( is_array( $meta_value ) && isset( $meta_value[$child_field['id']] ) ) ? $meta_value[$child_field['id']] : $child_value_default;
                            }
                        }
                        $field['child_value'] = $fieldset_value;
                    }

                    echo la_fw_add_element( $field, $elem_value, $unique );

                }
                echo '</div>';

            }

            $num++;
        }
        echo '</div>';

        echo '<div class="clear"></div>';

        echo '</div>';

        echo ( $has_nav ) ? '<div class="la-nav-background"></div>' : '';

        echo '<div class="clear"></div>';

        echo '</div>';

        echo '</div>';

    }

    // save metabox options
    public function save_post( $post_id, $post ) {

        if ( wp_verify_nonce( la_get_var( 'la-framework-metabox-nonce' ), 'lastudio' ) && !wp_is_post_revision($post_id)) {

            $transient = array();
            $errors = array();
            $post_type = la_get_var( 'post_type' );

            foreach ( $this->options as $request_value ) {

                if( in_array( $post_type, (array) $request_value['post_type'] ) ) {

                    $request_key = $request_value['id'];
                    $request = la_get_var( $request_key, array() );

                    // ignore _nonce
                    if( isset( $request['_nonce'] ) ) {
                        unset( $request['_nonce'] );
                    }

                    foreach( $request_value['sections'] as $key => $section ) {

                        if( isset( $section['fields'] ) ) {

                            foreach( $section['fields'] as $field ) {

                                if( isset( $field['type'] ) && isset( $field['id'] ) ) {

                                    $field_value = la_get_vars( $request_key, $field['id'] );

                                    // sanitize options
                                    $sanitize_type = $field['type'];
                                    if( isset( $field['sanitize'] ) ) {
                                        $sanitize_type = ( $field['sanitize'] !== false ) ? $field['sanitize'] : false;
                                    }

                                    if( $sanitize_type !== false && has_filter( 'la_sanitize_'. $sanitize_type ) ) {
                                        $request[$field['id']] = apply_filters( 'la_sanitize_' . $sanitize_type, $field_value, $field, $section['fields'] );
                                    }

                                    // validate options
                                    if ( isset( $field['validate'] ) && has_filter( 'la_validate_'. $field['validate'] ) ) {

                                        $validate = apply_filters( 'la_validate_' . $field['validate'], $field_value, $field, $section['fields'] );

                                        if( ! empty( $validate ) ) {

                                            $meta_value = get_post_meta( $post_id, $request_key, true );

                                            $errors[$field['id']] = array(
                                                'code' => ($field['title'] ? $field['title'] : $field['id']),
                                                'message' => $validate,
                                                'type' => 'error'
                                            );
                                            $default_value = isset( $field['default'] ) ? $field['default'] : '';
                                            $request[$field['id']] = ( isset( $meta_value[$field['id']] ) ) ? $meta_value[$field['id']] : $default_value;

                                        }

                                    }

                                }

                            }

                        }

                    }

                    $request = apply_filters( 'la_save_post', $request, $request_key, $post_id );

                    if( empty( $request ) ) {

                        delete_post_meta( $post_id, $request_key );

                    }
                    else {

                        update_post_meta( $post_id, $request_key, $request );

                    }

                    $transient['ids'][$request_key] = la_get_vars( 'la_section_id', $request_key );
                    $transient['errors'] = $errors;

                }

            }

            set_transient( 'la-metabox-transient', $transient, 10 );

        }

    }
}