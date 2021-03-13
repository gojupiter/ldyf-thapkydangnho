<?php

class LaStudio_Theme_Options {

    /**
     *
     * option database/data name
     * @access public
     * @var string
     *
     */
    public $unique = 'la_options';

    /**
     *
     * settings
     * @access public
     * @var array
     *
     */
    public $settings = array();

    /**
     *
     * options tab
     * @access public
     * @var array
     *
     */
    public $options = array();

    /**
     *
     * options section
     * @access public
     * @var array
     *
     */
    public $sections = array();

    /**
     *
     * options store
     * @access public
     * @var array
     *
     */
    public $get_option = array();

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    // run framework construct
    public function __construct( $settings, $options, $unique ) {
        $this->unique   = $unique;
        $this->settings = $settings;
        $this->options  = apply_filters('LaStudio/framework_option/sections', $options, $settings, $unique);

        if( ! empty( $this->options ) ) {
            $this->sections   = $this->get_sections();
            $this->get_option = get_option( $this->unique );
            add_action('admin_init', array( $this, 'settings_api'), 20 );
            add_action('admin_menu', array( $this, 'admin_menu'), 20 );

        }

    }

    // instance
    public static function instance( $settings = array(), $options = array(), $unique = 'la_options' ) {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $settings, $options, $unique );
        }
        return self::$instance;
    }

    // get sections
    public function get_sections() {

        $sections = array();

        foreach ( $this->options as $key => $value ) {

            if( isset( $value['sections'] ) ) {

                foreach ( $value['sections'] as $section ) {
                    if( isset( $section['fields'] ) ) {
                        $sections[] = $section;
                    }
                }

            } else {

                if( isset( $value['fields'] ) ) {
                    $sections[] = $value;
                }

            }

        }

        return $sections;

    }

    // wp settings api
    public function settings_api() {

        $defaults = array();

        register_setting( $this->unique .'_group', $this->unique, array( $this,'validate_save' ) );

        foreach( $this->sections as $section ) {

            if( isset( $section['fields'] ) ) {

                add_settings_section( $section['name'] .'_section', $section['title'], '', $section['name'] .'_section_group' );

                foreach( $section['fields'] as $field_key => $field ) {

                    add_settings_field( $field_key .'_field', '', array( $this, 'field_callback' ), $section['name'] .'_section_group', $section['name'] .'_section', $field );

                    // set default option if isset
                    if( isset( $field['default'] ) ) {
                        $defaults[$field['id']] = $field['default'];
                        if( ! empty( $this->get_option ) && ! isset( $this->get_option[$field['id']] ) ) {
                            $this->get_option[$field['id']] = $field['default'];
                        }
                    }

                }
            }

        }

        // set default variable if empty options and not empty defaults
        if( empty( $this->get_option ) && ! empty( $defaults ) ) {
            $this->get_option = $defaults;
            update_option( $this->unique, $defaults );
        }

    }

    // section fields validate in save
    public function validate_save( $request ) {

        $add_errors = array();
        $section_id = la_get_var( 'la_section_id' );
        $transient_time = 10;

        // ignore nonce requests
        if( isset( $request['_nonce'] ) ) { unset( $request['_nonce'] ); }

        // import
        if ( isset( $request['import'] ) && ! empty( $request['import'] ) ) {
            $decode_string = json_decode( $request['import'], true );
            if( is_array( $decode_string ) ) {
                $add_errors[] = $this->add_settings_error( __( 'Success. Imported backup options.', 'lastudio' ), 'updated' );
                set_transient( 'la-framework-transient', array( 'errors' => $add_errors, 'section_id' => $section_id ), $transient_time );
                return $decode_string;
            }
        }

        // reset all options
        if ( isset( $request['resetall'] ) ) {
            $add_errors[] = $this->add_settings_error( __( 'Default options restored.', 'lastudio' ), 'updated' );
            set_transient( 'la-framework-transient', array( 'errors' => $add_errors, 'section_id' => $section_id ), $transient_time );
            return;
        }

        // reset only section
        if ( isset( $request['reset'] ) && ! empty( $section_id ) ) {
            foreach ( $this->sections as $value ) {
                if( $value['name'] == $section_id ) {
                    foreach ( $value['fields'] as $field ) {
                        if( isset( $field['id'] ) ) {
                            if( isset( $field['default'] ) ) {
                                $request[$field['id']] = $field['default'];
                            } else {
                                unset( $request[$field['id']] );
                            }
                        }
                    }
                }
            }
            $add_errors[] = $this->add_settings_error( __( 'Default options restored for only this section.', 'lastudio' ), 'updated' );
        }

        // option sanitize and validate
        foreach( $this->sections as $section ) {
            if( isset( $section['fields'] ) ) {
                foreach( $section['fields'] as $field ) {

                    if ( isset( $field['type'] ) && isset( $field['id'] ) ) {

                        // sanitize options
                        $request_value = isset( $request[$field['id']] ) ? $request[$field['id']] : '';
                        $sanitize_type = $field['type'];

                        if( isset( $field['sanitize'] ) ) {
                            $sanitize_type = ( $field['sanitize'] !== false ) ? $field['sanitize'] : false;
                        }

                        if( $sanitize_type !== false && has_filter( 'la_sanitize_'. $sanitize_type ) ) {
                            $request[$field['id']] = apply_filters( 'la_sanitize_' . $sanitize_type, $request_value, $field, $section['fields'] );
                        }

                        // validate options
                        if ( isset( $field['validate'] ) && has_filter( 'la_validate_'. $field['validate'] ) ) {

                            $validate = apply_filters( 'la_validate_' . $field['validate'], $request_value, $field, $section['fields'] );

                            if( ! empty( $validate ) ) {
                                $add_errors[] = $this->add_settings_error( $validate, 'error', $field['id'] );
                                $request[$field['id']] = ( isset( $this->get_option[$field['id']] ) ) ? $this->get_option[$field['id']] : '';
                            }

                        }

                    }

                    if( ! isset( $field['id'] ) || empty( $request[$field['id']] ) ) {
                        continue;
                    }

                }
            }
        }

        $request = apply_filters( 'la_validate_save', $request );

        do_action( 'la_validate_save', $request );

        // set transient
        set_transient( 'la-framework-transient', array( 'errors' => $add_errors, 'section_id' => $section_id ), $transient_time );

        return $request;
    }

    // field callback classes
    public function field_callback( $field ) {
        $value = ( isset( $field['id'] ) && isset( $this->get_option[$field['id']] ) ) ? $this->get_option[$field['id']] : '';
        echo la_fw_add_element( $field, $value, $this->unique );
    }

    // settings sections
    public function do_settings_sections( $page ) {

        global $wp_settings_sections, $wp_settings_fields;

        if ( ! isset( $wp_settings_sections[$page] ) ){
            return;
        }

        foreach ( $wp_settings_sections[$page] as $section ) {

            if ( $section['callback'] ){
                call_user_func( $section['callback'], $section );
            }

            if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) ){
                continue;
            }

            $this->do_settings_fields( $page, $section['id'] );

        }

    }

    // settings fields
    public function do_settings_fields( $page, $section ) {

        global $wp_settings_fields;

        if ( ! isset( $wp_settings_fields[$page][$section] ) ) {
            return;
        }

        foreach ( $wp_settings_fields[$page][$section] as $field ) {
            call_user_func($field['callback'], $field['args']);
        }

    }

    public function add_settings_error( $message, $type = 'error', $id = 'global' ) {
        return array( 'setting' => 'la-errors', 'code' => $id, 'message' => $message, 'type' => $type );
    }

    // adding option page
    public function admin_menu() {

        $defaults_menu_args = array(
            'menu_parent'     => '',
            'menu_title'      => '',
            'menu_type'       => '',
            'menu_slug'       => '',
            'menu_icon'       => '',
            'menu_capability' => 'manage_options',
            'menu_position'   => null,
        );

        $args = wp_parse_args( $this->settings, $defaults_menu_args );

        if( $args['menu_type'] == 'submenu' ) {
            call_user_func( 'add_'. $args['menu_type'] .'_page', $args['menu_parent'], $args['menu_title'], $args['menu_title'], $args['menu_capability'], $args['menu_slug'], array( $this, 'admin_page' ) );
        } else {
            call_user_func( 'add_'. $args['menu_type'] .'_page', $args['menu_title'], $args['menu_title'], $args['menu_capability'], $args['menu_slug'], array( $this, 'admin_page' ), $args['menu_position'] );
        }

    }

    // option page html output
    public function admin_page() {

        $transient  = get_transient( 'la-framework-transient' );
        $has_nav    = ( count( $this->options ) <= 1 ) ? ' la-show-all' : '';
        $section_id = ( ! empty( $transient['section_id'] ) ) ? $transient['section_id'] : $this->sections[0]['name'];
        $section_id = la_get_var( 'la_section', $section_id );

        echo '<div class="la-framework la-option-framework">';

        echo '<form method="post" action="options.php" enctype="multipart/form-data" id="laframework_form">';
        echo '<input type="hidden" class="la-reset" name="la_section_id" value="'. $section_id .'" />';

        if( ! empty( $transient['errors'] ) ) {

            global $la_errors;

            $la_errors = $transient['errors'];

            if ( ! empty( $la_errors ) ) {
                foreach ( $la_errors as $error ) {
                    if( in_array( $error['setting'], array( 'general', 'la-errors' ) ) ) {
                        echo '<div class="la-settings-error '. $error['type'] .'">';
                        echo '<p><strong>'. $error['message'] .'</strong></p>';
                        echo '</div>';
                    }
                }
            }

        }

        settings_fields( $this->unique. '_group' );

        if( !$this->settings['disable_header'] ) {
            echo '<header class="la-header">';
            echo '<h1>' . $this->settings['framework_title'] . '</h1>';
            echo '<fieldset>';

            echo ($this->settings['ajax_save']) ? '<span id="la-save-ajax">' . __('Settings saved.', 'lastudio') . '</span>' : '';

            submit_button(__('Save', 'lastudio'), 'primary la-save', 'la-fw-save', false, array('data-save' => __('Saving...', 'lastudio')));

            echo '</fieldset>';
            echo (empty($has_nav)) ? '<a href="#" class="la-expand-all"><i class="fa fa-eye-slash"></i> ' . __('show all options', 'lastudio') . '</a>' : '';
            echo '<div class="clear"></div>';
            echo '</header>'; // end .la-header
        }
        echo '<div class="la-body'. $has_nav .'">';

        echo '<div class="la-nav">';

        echo '<ul>';
        foreach ( $this->options as $key => $tab ) {

            if( ( isset( $tab['sections'] ) ) ) {

                $tab_active   = la_array_search( $tab['sections'], 'name', $section_id );
                $active_style = ( ! empty( $tab_active ) ) ? ' style="display: block;"' : '';
                $active_list  = ( ! empty( $tab_active ) ) ? ' la-tab-active' : '';
                $tab_icon     = ( ! empty( $tab['icon'] ) ) ? '<i class="la-tab-icon '. $tab['icon'] .'"></i>' : '';

                echo '<li class="la-sub'. $active_list .'">';

                echo '<a href="#" class="la-arrow">'. $tab_icon . $tab['title'] .'</a>';

                echo '<ul class="la-nav-sub-ul"'. $active_style .'>';
                foreach ( $tab['sections'] as $tab_section ) {

                    $active_tab = ( $section_id == $tab_section['name'] ) ? ' class="la-section-active"' : '';
                    $icon = ( ! empty( $tab_section['icon'] ) ) ? '<i class="la-tab-icon '. $tab_section['icon'] .'"></i>' : '';

                    echo '<li><a href="#"'. $active_tab .' data-section="'. $tab_section['name'] .'">'. $icon . $tab_section['title'] .'</a></li>';

                }
                echo '</ul>';

                echo '</li>';

            } else {

                $icon = ( ! empty( $tab['icon'] ) ) ? '<i class="la-tab-icon '. $tab['icon'] .'"></i>' : '';

                if( isset( $tab['fields'] ) ) {

                    $active_list = ( $section_id == $tab['name'] ) ? ' class="la-section-active"' : '';
                    echo '<li><a href="#"'. $active_list .' data-section="'. $tab['name'] .'">'. $icon . $tab['title'] .'</a></li>';

                } else {

                    echo '<li><div class="la-seperator">'. $icon . $tab['title'] .'</div></li>';

                }

            }

        }
        echo '</ul>';

        echo '</div>'; // end .la-nav

        echo '<div class="la-content">';

        echo '<div class="la-sections">';

        foreach( $this->sections as $section ) {

            if( isset( $section['fields'] ) ) {

                $active_content = ( $section_id == $section['name'] ) ? ' style="display: block;"' : '';
                echo '<div id="la-tab-'. $section['name'] .'" class="la-section"'. $active_content .'>';
                echo ( isset( $section['title'] ) && empty( $has_nav ) ) ? '<div class="la-section-title"><h3>'. $section['title'] .'</h3></div>' : '';
                $this->do_settings_sections( $section['name'] . '_section_group' );
                echo '</div>';

            }

        }

        echo '</div>'; // end .la-sections

        echo '<div class="clear"></div>';

        echo '</div>'; // end .la-content

        echo '<div class="la-nav-background"></div>';

        echo '</div>'; // end .la-body
        echo '<footer class="la-footer">';
        echo '<div class="la-block-left">'. sprintf('%s <a href="%s">LA-Studio</a>', __('Powered by', 'lastudio' ), 'https://themeforest.net/user/la-studio/portfolio?ref=LA-Studio' ) .'</div>';
        echo '<div class="la-block-right">';

        submit_button(__('Restore', 'lastudio'), 'secondary la-restore la-reset-confirm', $this->unique . '[reset]', false);

        if ($this->settings['show_reset_all']) {
            submit_button(__('Reset All Options', 'lastudio'), 'secondary la-restore la-warning-primary la-reset-confirm', $this->unique . '[resetall]', false);
        }

        echo '</div>';

        echo '<div class="clear"></div>';
        echo '</footer>'; // end .la-footer
        echo '</form>'; // end form

        echo '<div class="clear"></div>';

        echo '</div>'; // end .la-framework

    }

}