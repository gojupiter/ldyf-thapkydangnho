<?php

class LaStudio_Taxonomy {

    /**
     *
     * taxonomy options
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

    private $current_metabox = array();

    // run taxonomy construct
    public function __construct( $options ) {

        $this->options = $options;

        if( ! empty( $this->options ) ) {
            add_action( 'admin_init', array( $this, 'add_taxonomy_fields') );
        }

    }

    // instance
    public static function instance( $options = array() ) {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $options );
        }
        return self::$instance;
    }

    // add taxonomy add/edit fields
    public function add_taxonomy_fields() {

        foreach ( $this->options as $option ) {

            $opt_taxonomy = $option['taxonomy'];
            $get_taxonomy = la_get_var( 'taxonomy' );

            if( $get_taxonomy == $opt_taxonomy ) {

                $this->current_metabox = $option;

                add_action( $opt_taxonomy .'_add_form_fields', array( $this, 'render_taxonomy_form_fields') );
                add_action( $opt_taxonomy .'_edit_form', array( $this, 'render_taxonomy_form_fields') );

                add_action( 'created_'. $opt_taxonomy, array( $this, 'save_taxonomy') );
                add_action( 'edited_'. $opt_taxonomy, array( $this, 'save_taxonomy') );
                add_action( 'delete_'. $opt_taxonomy, array( $this, 'delete_taxonomy') );

            }

        }

    }

    // render taxonomy add/edit form fields
    public function render_taxonomy_form_fields( $term ){

        if(empty($this->current_metabox)){
            return;
        }

        $current_metabox = $this->current_metabox;

        if(empty($current_metabox['id']) || empty($current_metabox['taxonomy']) || empty($current_metabox['sections'])) {
            return;
        }


        $form_edit = ( is_object( $term ) && isset( $term->taxonomy ) ) ? true : false;

        $classname = ( $form_edit ) ? 'edit' : 'add';
        $transient = get_transient( 'la-taxonomy-transient' );

        wp_nonce_field( 'la-taxonomy', 'la-framework-taxonomy-nonce' );

        $unique     = $current_metabox['id'];
        $sections   = $current_metabox['sections'];


        $meta_value = ( $form_edit && !empty($unique) ) ? get_term_meta( $term->term_id, $unique, true ) : '';


        $la_errors  = isset($transient['errors']) ? $transient['errors'] : array();
        $has_nav    = ( count( $sections ) >= 2 ) ? true : false;
        $show_all   = ( ! $has_nav ) ? ' la-show-all' : '';
        $section_id = ( ! empty( $transient['ids'][$unique] ) ) ? $transient['ids'][$unique] : '';
        $section_id = la_get_var( 'la-section', $section_id );

        echo '<div class="la-framework la-metabox-framework la-taxonomy la-taxonomy-'.$classname.'-fields"><div class="la-content-taxonomy">';

        if(isset($current_metabox['title'])){
            printf('<h2>%s</h2>', $current_metabox['title']);
        }

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

        echo '</div>';
    }


    // save taxonomy form fields
    public function save_taxonomy( $term_id ) {

        if ( wp_verify_nonce( la_get_var( 'la-framework-taxonomy-nonce' ), 'la-taxonomy' ) && !empty($this->current_metabox)) {


            $transient = array();
            $errors = array();
            $taxonomy = la_get_var( 'taxonomy' );
            $current_metabox = $this->current_metabox;

            if(empty($current_metabox['id']) || empty($current_metabox['taxonomy']) || empty($current_metabox['sections'])) {
                return;
            }

            if($taxonomy != $current_metabox['taxonomy']){
                return;
            }

            $request_sections = $current_metabox['sections'];
            $request_key = $current_metabox['id'];
            $request = la_get_var( $request_key, array() );

            // ignore _nonce
            if( isset( $request['_nonce'] ) ) {
                unset( $request['_nonce'] );
            }


            foreach( $request_sections as $key => $section ) {

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

                                    $meta_value = get_term_meta( $term_id, $request_key, true );

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

            $request = apply_filters( 'la_save_taxonomy', $request, $request_key, $term_id );

            if( empty( $request ) ) {

                delete_term_meta( $term_id, $request_key );

            } else {

                update_term_meta( $term_id, $request_key, $request );

            }

            $transient['ids'][$request_key] = la_get_vars( 'la_section_id', $request_key );
            $transient['errors'] = $errors;

            set_transient( 'la-taxonomy-transient', $errors, 10 );

        }

    }

    // delete taxonomy
    public function delete_taxonomy( $term_id ) {

        $taxonomy = la_get_var( 'taxonomy' );

        if( ! empty( $taxonomy ) ) {

            foreach ( $this->options as $request_value ) {

                if( $taxonomy == $request_value['taxonomy'] ) {

                    $request_key = $request_value['id'];

                    delete_term_meta( $term_id, $request_key );

                }

            }

        }

    }

}