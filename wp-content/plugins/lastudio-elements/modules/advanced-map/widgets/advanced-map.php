<?php
namespace Lastudio_Elements\Modules\AdvancedMap\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Advanced_Map Widget
 */
class Advanced_Map extends Lastudio_Widget {
    public $geo_api_url = 'https://maps.googleapis.com/maps/api/geocode/json';

    public function get_name() {
        return 'lastudio-advanced-map';
    }

    protected function get_widget_title() {
        return esc_html__( 'Advanced Map', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-8';
    }

    public function get_script_depends() {
        return [
            'google-maps-api',
            'lastudio-elements'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_map_settings',
            array(
                'label' => esc_html__( 'Map Settings', 'lastudio-elements' ),
            )
        );

        $key = apply_filters('lastudio-elements/advanced-map/api', '');

        if ( ! $key ) {

            $this->add_control(
                'set_key',
                array(
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => sprintf(
                        esc_html__( 'Please set Google maps API key before using this widget. You can create own API key  %1$s. Paste created key on %2$s', 'lastudio-elements' ),
                        '<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">' . esc_html__( 'here', 'lastudio-elements' ) . '</a>',
                        '<a target="_blank" href="'. admin_url('themes.php?page=theme_options&la_section=additional_code_panel') .'">' . esc_html__( 'settings page', 'lastudio-elements' ) . '</a>'
                    )
                )
            );
        }

        $default_address = esc_html__( 'PO Box 16122 Collins Street West Victoria 8007 Australia', 'lastudio-elements' );

        $this->add_control(
            'map_center',
            array(
                'label'       => esc_html__( 'Map Center', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => $default_address,
                'default'     => $default_address,
                'label_block' => true,
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'zoom',
            array(
                'label'      => esc_html__( 'Initial Zoom', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => 'zoom',
                    'size' => 11,
                ),
                'range'      => array(
                    'zoom' => array(
                        'min' => 1,
                        'max' => 18,
                    ),
                ),
            )
        );

        $this->add_control(
            'scrollwheel',
            array(
                'label'   => esc_html__( 'Scrollwheel Zoom', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'false',
                'options' => array(
                    'true'  => esc_html__( 'Enabled', 'lastudio-elements' ),
                    'false' => esc_html__( 'Disabled', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'zoom_controls',
            array(
                'label'   => esc_html__( 'Zoom Controls', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'true',
                'options' => array(
                    'true'  => esc_html__( 'Show', 'lastudio-elements' ),
                    'false' => esc_html__( 'Hide', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'fullscreen_control',
            array(
                'label'   => esc_html__( 'Fullscreen Control', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'true',
                'options' => array(
                    'true'  => esc_html__( 'Show', 'lastudio-elements' ),
                    'false' => esc_html__( 'Hide', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'street_view',
            array(
                'label'   => esc_html__( 'Street View Controls', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'true',
                'options' => array(
                    'true'  => esc_html__( 'Show', 'lastudio-elements' ),
                    'false' => esc_html__( 'Hide', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'map_type',
            array(
                'label'   => esc_html__( 'Map Type Controls (Map/Satellite)', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'true',
                'options' => array(
                    'true'  => esc_html__( 'Show', 'lastudio-elements' ),
                    'false' => esc_html__( 'Hide', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'drggable',
            array(
                'label'   => esc_html__( 'Is Map Draggable?', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'true',
                'options' => array(
                    'true'  => esc_html__( 'Yes', 'lastudio-elements' ),
                    'false' => esc_html__( 'No', 'lastudio-elements' ),
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_map_style',
            array(
                'label' => esc_html__( 'Map Style', 'lastudio-elements' ),
            )
        );

        $this->add_responsive_control(
            'map_height',
            array(
                'label'       => esc_html__( 'Map Height', 'lastudio-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 50,
                'default'     => 300,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-map' => 'height: {{VALUE}}px',
                ),
            )
        );

        $this->add_control(
            'map_style',
            array(
                'label'       => esc_html__( 'Map Style', 'lastudio-elements' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => $this->_get_available_map_styles(),
                'label_block' => true,
                'description' => esc_html__( 'You can add own map styles within your theme. Add file with styles array in .json format into lastudio-elements/google-map-styles/ folder in your theme. File must be minified', 'lastudio-elements' )
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_map_pins',
            array(
                'label' => esc_html__( 'Pins', 'lastudio-elements' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'pin_address',
            array(
                'label'       => esc_html__( 'Pin Address', 'lastudio-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => $default_address,
                'label_block' => true,
                'dynamic'     => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'pin_desc',
            array(
                'label'   => esc_html__( 'Pin Description', 'lastudio-elements' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => $default_address,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'pin_image',
            array(
                'label'   => esc_html__( 'Pin Icon', 'lastudio-elements' ),
                'type'    => Controls_Manager::MEDIA,
            )
        );

        $repeater->add_control(
            'pin_state',
            array(
                'label'   => esc_html__( 'Initial State', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'visible',
                'options' => array(
                    'visible' => esc_html__( 'Visible', 'lastudio-elements' ),
                    'hidden'  => esc_html__( 'Hidden', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_control(
            'pins',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => array_values( $repeater->get_controls() ),
                'default'     => array(
                    array(
                        'pin_address' => $default_address,
                        'pin_desc'    => $default_address,
                        'pin_state'   => 'visible',
                    ),
                ),
                'title_field' => '{{{ pin_address }}}',
            )
        );

        $this->end_controls_section();

    }

    /**
     * Get available map styles list.
     *
     * @return array
     */
    public function _get_available_map_styles() {

        $key           = md5( 'lastudio-elements-' . LASTUDIO_ELEMENTS_VER );
        $plugin_styles = get_transient( $key );

        if ( ! $plugin_styles ) {

            $plugin_styles = $this->_get_map_styles_from_path(
                LASTUDIO_ELEMENTS_PATH . 'assets/js/lib/google-maps/'
            );

            set_transient( $key, $plugin_styles, WEEK_IN_SECONDS );
        }

        $parent_styles = $this->_get_map_styles_from_path(
            get_template_directory() . '/' . lastudio_elements_template_path() . 'google-map-styles/'
        );

        if ( get_stylesheet_directory() !== get_template_directory() ) {
            $child_styles = $this->_get_map_styles_from_path(
                get_stylesheet_directory() . '/' . lastudio_elements_template_path() . 'google-map-styles/'
            );
        } else {
            $child_styles = array();
        }

        return array_merge(
            array( 'default' => esc_html__( 'Default', 'lastudio-elements' ) ),
            $plugin_styles,
            $parent_styles,
            $child_styles
        );
    }

    /**
     * Get map styles array rom path
     *
     * @param  string $path [description]
     * @return array
     */
    public function _get_map_styles_from_path( $path = null ) {

        if ( ! file_exists( $path ) ) {
            return array();
        }

        $result = array();
        $absp   = untrailingslashit( ABSPATH );

        foreach ( glob( $path . '*.json' ) as $file ) {
            $data = get_file_data( $file, array( 'name'=>'Name' ) );
            $result[ str_replace( $absp, '', $file ) ] = ! empty( $data['name'] ) ? $data['name'] : basename( $file );
        }

        return $result;
    }

    /**
     * Get map style JSON by file name
     *
     * @param  string $style Style file
     * @return string
     */
    public function _get_map_style( $style ) {

        $full_path    = untrailingslashit( ABSPATH ) . $style;
        $include_path = null;

        ob_start();

        if ( file_exists( $full_path ) ) {
            $include_path = $full_path;
        } elseif ( file_exists( $style ) ) {
            $include_path = $style;
        } elseif ( file_exists( str_replace( '\\', '/', $full_path ) ) ) {
            $include_path = str_replace( '\\', '/', $full_path );
        }

        ob_get_clean();

        if ( ! $include_path ) {
            return '';
        }

        ob_start();
        include $include_path;
        return preg_replace( '/\/\/?\s*\*[\s\S]*?\*\s*\/\/?/m', '', ob_get_clean() );
    }

    /**
     * Get lcation coordinates by entered address and store into metadata.
     *
     * @return void
     */
    public function get_location_coord( $location ) {

        $api_key = apply_filters('lastudio-elements/advanced-map/api', '');

        // Do nothing if api key not provided
        if ( ! $api_key ) {
            $message = esc_html__( 'Please set Google maps API key before using this widget.', 'lastudio-elements' );

            echo $this->get_map_message( $message );

            return;
        }

        $key = md5( $location );

        $coord = get_transient( $key );

        if ( ! empty( $coord ) ) {
            return $coord;
        }

        // Prepare request data
        $location = esc_attr( $location );
        $api_key  = esc_attr( $api_key );

        $reques_url = esc_url( add_query_arg(
            array(
                'address' => urlencode( $location ),
                'key'     => urlencode( $api_key )
            ),
            $this->geo_api_url
        ) );

        // Fixed '&' encoding bug
        $reques_url = str_replace( '&#038;', '&', $reques_url );

        $response = wp_remote_get( $reques_url );
        $json     = wp_remote_retrieve_body( $response );
        $data     = json_decode( $json, true );

        $coord = isset( $data['results'][0]['geometry']['location'] )
            ? $data['results'][0]['geometry']['location']
            : false;

        if ( ! $coord ) {

            $message = esc_html__( 'Coordinates of this location not found', 'lastudio-elements' );

            if(!empty($data['error_message'])){
                $message = $data['error_message'];
            }

            echo $this->get_map_message( $message );

            return;
        }

        set_transient( $key, $coord, WEEK_IN_SECONDS );

        return $coord;
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        if ( empty( $settings['map_center'] ) ) {
            return;
        }

        $coordinates = $this->get_location_coord( $settings['map_center'] );

        if ( ! $coordinates ) {
            return;
        }

        $scroll_ctrl     = isset( $settings['scrollwheel'] ) ? $settings['scrollwheel'] : '';
        $zoom_ctrl       = isset( $settings['zoom_controls'] ) ? $settings['zoom_controls'] : '';
        $fullscreen_ctrl = isset( $settings['fullscreen_control'] ) ? $settings['fullscreen_control'] : '';
        $streetview_ctrl = isset( $settings['street_view'] ) ? $settings['street_view'] : '';

        $init = apply_filters( 'lastudio-elements/addons/advanced-map/data-args', array(
            'center'            => $coordinates,
            'zoom'              => isset( $settings['zoom']['size'] ) ? intval( $settings['zoom']['size'] ) : 11,
            'scrollwheel'       => filter_var( $scroll_ctrl, FILTER_VALIDATE_BOOLEAN ),
            'zoomControl'       => filter_var( $zoom_ctrl, FILTER_VALIDATE_BOOLEAN ),
            'fullscreenControl' => filter_var( $fullscreen_ctrl, FILTER_VALIDATE_BOOLEAN ),
            'streetViewControl' => filter_var( $streetview_ctrl, FILTER_VALIDATE_BOOLEAN ),
            'mapTypeControl'    => filter_var( $settings['map_type'], FILTER_VALIDATE_BOOLEAN ),
        ) );

        if ( 'false' === $settings['drggable'] ) {
            $init['gestureHandling'] = 'none';
        }

        if ( 'default' !== $settings['map_style'] ) {
            $init['styles'] = json_decode( $this->_get_map_style( $settings['map_style'] ) );
        }

        $this->add_render_attribute( 'map-data', 'data-init', json_encode( $init ) );

        $pins = array();

        if ( ! empty( $settings['pins'] ) ) {

            foreach ( $settings['pins'] as $pin ) {

                if ( empty( $pin['pin_address'] ) ) {
                    continue;
                }

                $current = array(
                    'position' => $this->get_location_coord( $pin['pin_address'] ),
                    'desc'     => $pin['pin_desc'],
                    'state'    => $pin['pin_state'],
                );

                if ( ! empty( $pin['pin_image']['url'] ) ) {
                    $current['image'] = esc_url( $pin['pin_image']['url'] );
                }

                $pins[] = $current;
            }

        }

        $this->add_render_attribute( 'map-pins', 'data-pins', json_encode( $pins ) );

        printf(
            '<div class="lastudio-map" %1$s %2$s></div>',
            $this->get_render_attribute_string( 'map-data' ),
            $this->get_render_attribute_string( 'map-pins' )
        );
    }

    /**
     * [map_message description]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public function get_map_message( $message ) {
        return sprintf( '<div class="lastudio-map-message"><div class="lastudio-map-message__dammy-map"></div><span class="lastudio-map-message__text">%s</span></div>', $message );
    }
}