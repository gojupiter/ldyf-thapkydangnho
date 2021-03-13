<?php
/**
 * LaStudio Common Widget.
 *
 * @package Lastudio Elements
 */

namespace Lastudio_Elements\Base;

use Elementor\Scheme_Color;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Lastudio_Elements\Controls\Group_Control_Box_Style;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Widget
 *
 * @since 0.0.1
 */
abstract class Lastudio_Widget extends Widget_Base {

    public $__context         = 'render';
    public $__processed_item  = false;
    public $__processed_index = 0;


    protected function get_widget_title(){
        return '';
    }

    public function get_title(){
        return 'LaStudio ' . $this->get_widget_title();
    }

    /**
     * Get categories
     *
     * @since 0.0.1
     */
    public function get_categories() {
        return [ 'lastudio' ];
    }

    /**
     * Get globaly affected template
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function __get_global_template( $name = null ) {

        $template = call_user_func( array( $this, sprintf( '__get_%s_template', $this->__context ) ) );

        if ( ! $template ) {
            $template = lastudio_elements_get_template( $this->get_name() . '/global/' . $name . '.php' );
        }

        return $template;
    }

    /**
     * Get front-end template
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function __get_render_template( $name = null ) {
        return lastudio_elements_get_template( $this->get_name() . '/render/' . $name . '.php' );
    }

    /**
     * Get editor template
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function __get_edit_template( $name = null ) {
        return lastudio_elements_get_template( $this->get_name() . '/edit/' . $name . '.php' );
    }

    /**
     * Get global looped template for settings
     * Required only to process repeater settings.
     *
     * @param  string $name    Base template name.
     * @param  string $setting Repeater setting that provide data for template.
     * @return void
     */
    public function __get_global_looped_template( $name = null, $setting = null ) {

        $templates = array(
            'start' => $this->__get_global_template( $name . '-loop-start' ),
            'loop'  => $this->__get_global_template( $name . '-loop-item' ),
            'end'   => $this->__get_global_template( $name . '-loop-end' ),
        );

        call_user_func(
            array( $this, sprintf( '__get_%s_looped_template', $this->__context ) ), $templates, $setting
        );

    }

    /**
     * Get render mode looped template
     *
     * @param  array  $templates [description]
     * @param  [type] $setting   [description]
     * @return [type]            [description]
     */
    public function __get_render_looped_template( $templates = array(), $setting = null ) {

        $loop = $this->get_settings_for_display( $setting );
        $loop = apply_filters( 'lastudio-elements/widget/loop-items', $loop, $setting, $this );

        if ( empty( $loop ) ) {
            return;
        }

        if ( ! empty( $templates['start'] ) ) {
            include $templates['start'];
        }

        foreach ( $loop as $item ) {

            $this->__processed_item = $item;
            if ( ! empty( $templates['start'] ) ) {
                include $templates['loop'];
            }
            $this->__processed_index++;
        }

        $this->__processed_item = false;
        $this->__processed_index = 0;

        if ( ! empty( $templates['end'] ) ) {
            include $templates['end'];
        }

    }

    /**
     * Get edit mode looped template
     *
     * @param  array  $templates [description]
     * @param  [type] $setting   [description]
     * @return [type]            [description]
     */
    public function __get_edit_looped_template( $templates = array(), $setting = null ) {
        ?>
        <# if ( settings.<?php echo $setting; ?> ) { #>
        <?php
        if ( ! empty( $templates['start'] ) ) {
            include $templates['start'];
        }
        ?>
        <# _.each( settings.<?php echo $setting; ?>, function( item ) { #>
        <?php
        if ( ! empty( $templates['loop'] ) ) {
            include $templates['loop'];
        }
        ?>
        <# } ); #>
        <?php
        if ( ! empty( $templates['end'] ) ) {
            include $templates['end'];
        }
        ?>
        <# } #>
        <?php
    }

    /**
     * Get current looped item dependends from context.
     *
     * @param  string $key Key to get from processed item
     * @return mixed
     */
    public function __loop_item( $keys = array(), $format = '%s' ) {

        return call_user_func( array( $this, sprintf( '__%s_loop_item', $this->__context ) ), $keys, $format );

    }

    /**
     * Loop edit item
     *
     * @param  [type]  $keys       [description]
     * @param  string  $format     [description]
     * @param  boolean $nested_key [description]
     * @return [type]              [description]
     */
    public function __edit_loop_item( $keys = array(), $format = '%s' ) {

        $settings = $keys[0];

        if ( isset( $keys[1] ) ) {
            $settings .= '.' . $keys[1];
        }

        ob_start();

        echo '<# if ( item.' . $settings . ' ) { #>';
        printf( $format, '{{{ item.' . $settings . ' }}}' );
        echo '<# } #>';

        return ob_get_clean();
    }

    /**
     * Loop render item
     *
     * @param  string  $format     [description]
     * @param  [type]  $key        [description]
     * @param  boolean $nested_key [description]
     * @return [type]              [description]
     */
    public function __render_loop_item( $keys = array(), $format = '%s' ) {

        $item = $this->__processed_item;

        $key        = $keys[0];
        $nested_key = isset( $keys[1] ) ? $keys[1] : false;

        if ( empty( $item ) || ! isset( $item[ $key ] ) ) {
            return false;
        }

        if ( false === $nested_key || ! is_array( $item[ $key ] ) ) {
            $value = $item[ $key ];
        } else {
            $value = isset( $item[ $key ][ $nested_key ] ) ? $item[ $key ][ $nested_key ] : false;
        }

        if ( ! empty( $value ) ) {
            return sprintf( $format, $value );
        }

    }

    /**
     * Include global template if any of passed settings is defined
     *
     * @param  [type] $name     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function __glob_inc_if( $name = null, $settings = array() ) {

        $template = $this->__get_global_template( $name );

        call_user_func( array( $this, sprintf( '__%s_inc_if', $this->__context ) ), $template, $settings );

    }

    /**
     * Include render template if any of passed setting is not empty
     *
     * @param  [type] $file     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function __render_inc_if( $file = null, $settings = array() ) {

        foreach ( $settings as $setting ) {
            $val = $this->get_settings_for_display( $setting );

            if ( ! empty( $val ) ) {
                include $file;
                return;
            }

        }

    }

    /**
     * Include render template if any of passed setting is not empty
     *
     * @param  [type] $file     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function __edit_inc_if( $file = null, $settings = array() ) {

        $condition = null;
        $sep       = null;

        foreach ( $settings as $setting ) {
            $condition .= $sep . 'settings.' . $setting;
            $sep = ' || ';
        }

        ?>

        <# if ( <?php echo $condition; ?> ) { #>

        <?php include $file; ?>

        <# } #>

        <?php
    }

    /**
     * Open standard wrapper
     *
     * @return void
     */
    public function __open_wrap() {
        printf( '<div class="elementor-%s lastudio-elements">', $this->get_name() );
    }

    /**
     * Close standard wrapper
     *
     * @return void
     */
    public function __close_wrap() {
        echo '</div>';
    }

    /**
     * Print HTML markup if passed setting not empty.
     *
     * @param  string $setting Passed setting.
     * @param  string $format  Required markup.
     * @param  array  $args    Additional variables to pass into format string.
     * @param  bool   $echo    Echo or return.
     * @return string|void
     */
    public function __html( $setting = null, $format = '%s' ) {

        call_user_func( array( $this, sprintf( '__%s_html', $this->__context ) ), $setting, $format );

    }

    /**
     * Returns HTML markup if passed setting not empty.
     *
     * @param  string $setting Passed setting.
     * @param  string $format  Required markup.
     * @param  array  $args    Additional variables to pass into format string.
     * @param  bool   $echo    Echo or return.
     * @return string|void
     */
    public function __get_html( $setting = null, $format = '%s' ) {

        ob_start();
        $this->__html( $setting, $format );
        return ob_get_clean();

    }

    /**
     * Print HTML template
     *
     * @param  [type] $setting [description]
     * @param  [type] $format  [description]
     * @return [type]          [description]
     */
    public function __render_html( $setting = null, $format = '%s' ) {

        if ( is_array( $setting ) ) {
            $key     = $setting[1];
            $setting = $setting[0];
        }

        $val = $this->get_settings_for_display( $setting );

        if ( ! is_array( $val ) && '0' === $val ) {
            printf( $format, $val );
        }

        if ( is_array( $val ) && empty( $val[ $key ] ) ) {
            return '';
        }

        if ( ! is_array( $val ) && empty( $val ) ) {
            return '';
        }

        if ( is_array( $val ) ) {
            printf( $format, $val[ $key ] );
        } else {
            printf( $format, $val );
        }

    }

    /**
     * Print underscore template
     *
     * @param  [type] $setting [description]
     * @param  [type] $format  [description]
     * @return [type]          [description]
     */
    public function __edit_html( $setting = null, $format = '%s' ) {

        if ( is_array( $setting ) ) {
            $setting = $setting[0] . '.' . $setting[1];
        }

        echo '<# if ( settings.' . $setting . ' ) { #>';
        printf( $format, '{{{ settings.' . $setting . ' }}}' );
        echo '<# } #>';
    }

    protected function _load_template( $file ){
        include $file;
    }


    protected function _generate_carousel_setting_json(){
        $settings = $this->get_settings();

        $json_data = '';

        if ( 'yes' !== $settings['carousel_enabled'] ) {
            return $json_data;
        }

        $is_rtl = is_rtl();

        $desktop_col = absint( $settings['columns'] );
        $laptop_col = absint( $settings['columns_laptop'] );
        $tablet_col = absint( $settings['columns_tablet'] );
        $tablet_portrait_col = absint( $settings['columns_width800'] );
        $mobile_col = absint( $settings['columns_mobile'] );
        $mobile_portrait_col = $mobile_col;

        if($laptop_col == 0){
            $laptop_col = $desktop_col;
        }
        if($tablet_col == 0){
            $tablet_col = $laptop_col;
        }
        if($tablet_portrait_col == 0){
            $tablet_portrait_col = $tablet_col;
        }
        if($mobile_col == 0){
            $mobile_col = 1;
        }
        if($mobile_portrait_col == 0){
            $mobile_portrait_col = $mobile_col;
        }

        $slidesToShow = array(
            'desktop'           => $desktop_col,
            'laptop'            => $laptop_col,
            'tablet'            => $tablet_col,
            'tablet_portrait'   => $tablet_portrait_col,
            'mobile'            => $mobile_col,
            'mobile_portrait'   => $mobile_portrait_col
        );

        $options = array(
            'slidesToShow'   => $slidesToShow,
            'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
            'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
            'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
            'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
            'speed'          => absint( $settings['speed'] ),
            'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
            'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
            'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
            'prevArrow'      => lastudio_elements_tools()->get_carousel_arrow(
                array( $settings['prev_arrow'], 'prev-arrow' )
            ),
            'nextArrow'      => lastudio_elements_tools()->get_carousel_arrow(
                array( $settings['next_arrow'], 'next-arrow' )
            ),
            'rtl' => $is_rtl,
        );

        if(isset($settings['center_mode']) && $settings['center_mode'] == 'yes'){
            $options['centerMode'] = true;
            $options['centerPadding'] = '0px';
        }

        if ( 1 === absint( $settings['columns'] ) ) {
            $options['fade'] = ( 'fade' === $settings['effect'] );
        }

        $json_data = htmlspecialchars( json_encode( $options ) );

        return $json_data;

    }
    /**
     * Helper Register Carousel Controls
     */
    protected function _register_carousel_controls( $carousel_condition = array() ){
        $this->start_controls_section(
            'section_carousel',
            array(
                'label' => esc_html__( 'Carousel', 'lastudio-elements' ),
                'condition' => $carousel_condition
            )
        );

        $this->add_control(
            'carousel_enabled',
            array(
                'label'        => esc_html__( 'Enable Carousel', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_control(
            'slides_to_scroll',
            array(
                'label'     => esc_html__( 'Slides to Scroll', 'lastudio-elements' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => lastudio_elements_tools()->get_select_range( 6 ),
                'condition' => array(
                    'columns!' => '1',
                ),
            )
        );

        $this->add_control(
            'arrows',
            array(
                'label'        => esc_html__( 'Show Arrows Navigation', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'prev_arrow',
            array(
                'label'   => esc_html__( 'Prev Arrow Icon', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fa fa-angle-left',
                'options' => lastudio_elements_tools()->get_available_prev_arrows_list(),
                'condition' => array(
                    'arrows' => 'true',
                ),
            )
        );

        $this->add_control(
            'next_arrow',
            array(
                'label'   => esc_html__( 'Next Arrow Icon', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fa fa-angle-right',
                'options' => lastudio_elements_tools()->get_available_next_arrows_list(),
                'condition' => array(
                    'arrows' => 'true',
                ),
            )
        );

        $this->add_control(
            'dots',
            array(
                'label'        => esc_html__( 'Show Dots Navigation', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'pause_on_hover',
            array(
                'label'        => esc_html__( 'Pause on Hover', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => esc_html__( 'Autoplay', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'autoplay_speed',
            array(
                'label'     => esc_html__( 'Autoplay Speed', 'lastudio-elements' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5000,
                'condition' => array(
                    'autoplay' => 'true',
                ),
            )
        );

        $this->add_control(
            'infinite',
            array(
                'label'        => esc_html__( 'Infinite Loop', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'effect',
            array(
                'label'   => esc_html__( 'Effect', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => array(
                    'slide' => esc_html__( 'Slide', 'lastudio-elements' ),
                    'fade'  => esc_html__( 'Fade', 'lastudio-elements' ),
                ),
                'condition' => array(
                    'columns' => '1',
                ),
            )
        );

        $this->add_control(
            'speed',
            array(
                'label'   => esc_html__( 'Animation Speed', 'lastudio-elements' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 500,
            )
        );

        $this->add_control(
            'center_mode',
            array(
                'label'        => esc_html__( 'Center Mode', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => ''
            )
        );

        $this->add_responsive_control(
            'center_mode_gap',
            array(
                'label' => esc_html__( 'Center Padding', 'lastudio-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'vw' => array(
                        'min' => 0,
                        'max' => 50,
                    )
                ),
                'size_units' => ['px', '%', 'vw'],
                'default' => [
                    'size' => 30,
                    'unit' => 'px'
                ],
                'selectors' => array(
                    '{{WRAPPER}} .slick-list' => 'padding-left: {{SIZE}}{{UNIT}} !important; padding-right: {{SIZE}}{{UNIT}} !important;',
                ),
                'condition' => [
                    'center_mode' => 'yes'
                ]
            )
        );

        $this->end_controls_section();
    }

    /**
     * Helper Register Carousel Controls Style
     */
    protected function _register_carousel_arrows_dots_style(){
        /**
         * Arrow Sections
         */
        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'      => esc_html__( 'Carousel Arrows', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_prev',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_next_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_hover_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow:hover',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'prev_arrow_position',
            array(
                'label'     => esc_html__( 'Prev Arrow Position', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'prev_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio-elements' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_vert_position' => 'top',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_bottom_position',
            array(
                'label'      => esc_html__( 'Bottom Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_vert_position' => 'bottom',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->add_control(
            'prev_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio-elements' ),
                    'right' => esc_html__( 'Right', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->add_control(
            'next_arrow_position',
            array(
                'label'     => esc_html__( 'Next Arrow Position', 'lastudio-elements' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'next_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio-elements' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'next_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_vert_position' => 'top',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_bottom_position',
            array(
                'label'      => esc_html__( 'Bottom Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_vert_position' => 'bottom',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->add_control(
            'next_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio-elements' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio-elements' ),
                    'right' => esc_html__( 'Right', 'lastudio-elements' ),
                ),
            )
        );

        $this->add_responsive_control(
            'next_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dots_style',
            array(
                'label'      => esc_html__( 'Carousel Dots', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li span',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_3,
                        ),
                    ),
                ),
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_hover',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li span:hover',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ),
                    ),
                ),
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-elements' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_active',
                'label'          => esc_html__( 'Dots Style', 'lastudio-elements' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li.slick-active span',
                'fields_options' => array(
                    'color' => array(
                        'scheme' => array(
                            'type'  => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_4,
                        ),
                    ),
                ),
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'dots_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio-elements' ),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 5,
                    'unit' => 'px',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                ),
                'separator' => 'before',
            )
        );

        $this->add_control(
            'dots_margin',
            array(
                'label'      => esc_html__( 'Dots Box Margin', 'lastudio-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'dots_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

}