<?php


class LaStudio_Theme_Customize  {

    /**
     *
     * sections
     * @access public
     * @var array
     *
     */
    public $options = array();

    public $unique = 'la_options';

    /**
     *
     * panel priority
     * @access public
     * @var bool
     *
     */
    public $priority = 1;

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    // run customize construct
    public function __construct( $options, $unique ) {

        $this->options = $options;
        $this->unique = $unique;

        if( ! empty( $this->options ) ) {
            add_action( 'customize_register', array( $this, 'customize_register' ), 20 );
        }

    }

    // instance
    public static function instance( $options = array(), $unique = 'la_options' ){
        if ( is_null( self::$instance )  ) {
            self::$instance = new self( $options, $unique );
        }
        return self::$instance;
    }

    private function load_dependencies(){
        if( !class_exists('LaStudio_Theme_Customize_Control', false)){
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lastudio-theme-customize-control.php';
        }
    }

    // customize register
    public function customize_register( $wp_customize ) {

        $panel_priority = 1;

        $this->load_dependencies();

        foreach ( $this->options as $value ) {

            $this->priority = $panel_priority;

            if( isset( $value['sections'] ) ) {

                $wp_customize->add_panel( $value['name'], array(
                    'title'       => $value['title'],
                    'priority'    => ( isset( $value['priority'] ) ) ? $value['priority'] : $panel_priority,
                    'description' => ( isset( $value['description'] ) ) ? $value['description'] : '',
                ));

                $this->add_section( $wp_customize, $value, $value['name'] );

            } else {

                $this->add_section( $wp_customize, $value );

            }

            $panel_priority++;

        }

    }

    // add customize section
    public function add_section( $wp_customize, $value, $panel = false ) {

        $section_priority = ( $panel ) ? 1 : $this->priority;
        $sections         = ( $panel ) ? $value['sections'] : array( 'sections' => $value );


        foreach ( $sections as $section ) {

            if(empty($section) || empty($section['settings'])){
                continue;
            }

            // add_section
            $wp_customize->add_section( $section['name'], array(
                'title'       => $section['title'],
                'priority'    => ( isset( $section['priority'] ) ) ? $section['priority'] : $section_priority,
                'description' => ( isset( $section['description'] ) ) ? $section['description'] : '',
                'panel'       => ( $panel ) ? $panel : '',
            ) );

            $setting_priority = 1;

            foreach ( $section['settings'] as $setting ) {

                $setting_name = $this->unique . '[' . $setting['name'] .']';

                // add_setting
                $wp_customize->add_setting( $setting_name,
                    wp_parse_args( $setting, array(
                            'type'              => 'option',
                            'capability'        => 'edit_theme_options',
                            'sanitize_callback' => array('LaStudio_Admin', 'sanitize_clean'),
                        )
                    )
                );

                // add_control
                $control_args = wp_parse_args( $setting['control'], array(
                    'unique'    => $this->unique,
                    'section'   => $section['name'],
                    'settings'  => $setting_name,
                    'priority'  => $setting_priority,
                ));

                if( $control_args['type'] == 'la_field' ) {

                    $call_class = 'LaStudio_Theme_Customize_Control';
                    $wp_customize->add_control( new $call_class( $wp_customize, $setting['name'], $control_args ) );

                }
                else {

                    $wp_controls = array( 'color', 'upload', 'image', 'media' );
                    $call_class  = 'WP_Customize_'. ucfirst( $control_args['type'] ) .'_Control';

                    if( in_array( $control_args['type'], $wp_controls ) && class_exists( $call_class ) ) {
                        $wp_customize->add_control( new $call_class( $wp_customize, $setting['name'], $control_args ) );
                    }
                    else {
                        $wp_customize->add_control( $setting['name'], $control_args );
                    }

                }

                $setting_priority++;
            }

            $section_priority++;

        }

    }

}