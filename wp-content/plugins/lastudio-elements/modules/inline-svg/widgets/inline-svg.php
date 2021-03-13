<?php
namespace Lastudio_Elements\Modules\InlineSvg\Widgets;

use Lastudio_Elements\Base\Lastudio_Widget;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Inline_Svg Widget
 */
class Inline_Svg extends Lastudio_Widget {

    public function get_name() {
        return 'lastudio-inline-svg';
    }

    protected function get_widget_title() {
        return esc_html__( 'Inline SVG', 'lastudio-elements' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-44';
    }

    protected function _register_controls() {
        $css_scheme = apply_filters(
            'lastudio-elements/lastudio-inline-svg/css-scheme',
            array(
                'svg-wrapper' => '.lastudio-inline-svg__wrapper',
                'svg-link'    => '.lastudio-inline-svg',
                'svg'         => '.lastudio-inline-svg svg',
            )
        );

        $this->start_controls_section(
            'section_svg_content',
            array(
                'label'      => esc_html__( 'SVG', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_CONTENT,
                'show_label' => false,
            )
        );

        $this->add_control(
            'svg_url',
            array(
                'label'   => esc_html__( 'SVG', 'lastudio-elements' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => '',
                ),
            )
        );

        $this->add_control(
            'svg_link',
            array(
                'label'   => esc_html__( 'URL', 'lastudio-elements' ),
                'type'    => Controls_Manager::URL,
                'default' => array(
                    'url' => '',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_svg_style',
            array(
                'label'      => esc_html__( 'SVG', 'lastudio-elements' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'svg_custom_width',
            array(
                'label'        => esc_html__( 'Use Custom Width', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Makes SVG responsive and allows to change its width.', 'lastudio-elements' )
            )
        );

        $this->add_control(
            'svg_aspect_ratio',
            array(
                'label'        => esc_html__( 'Use Aspect Ratio', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'This option allows your SVG item to be scaled up exactly as your bitmap image, at the same time saving its width compared to the height. ', 'lastudio-elements' ),
                'condition'    => array(
                    'svg_custom_width' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'svg_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                ),
                'default'    => array(
                    'size' => 150,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['svg-link'] => 'max-width: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'svg_custom_width' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'svg_height',
            array(
                'label'      => esc_html__( 'Height', 'lastudio-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                ),
                'default'    => array(
                    'size' => 150,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['svg'] => 'height: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'svg_aspect_ratio!' => 'yes',
                    'svg_custom_width'  => 'yes'

                )
            )
        );

        $this->add_control(
            'svg_custom_color',
            array(
                'label'        => esc_html__( 'Use Custom Color', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Specifies color of all SVG elements that have a fill or stroke color set.', 'lastudio-elements' )
            )
        );

        $this->add_control(
            'svg_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['svg-link'] => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'svg_custom_color' => 'yes'
                )
            )
        );

        $this->add_control(
            'svg_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'lastudio-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['svg-link'] . ':hover' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'svg_custom_color' => 'yes'
                )
            )
        );

        $this->add_control(
            'svg_remove_inline_css',
            array(
                'label'        => esc_html__( 'Remove Inline CSS', 'lastudio-elements' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-elements' ),
                'label_off'    => esc_html__( 'No', 'lastudio-elements' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Use this option to delete the inline styles in the loaded SVG.', 'lastudio-elements' )
            )
        );

        $this->add_responsive_control(
            'svg_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'lastudio-elements' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-elements' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['svg-wrapper'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    public function prepare_svg( $svg, $settings ) {
        if ( 'yes' !== $settings['svg_aspect_ratio'] ) {
            $svg = preg_replace( '[preserveAspectRatio\s*?=\s*?"\s*?.*?\s*?"]', '', $svg );
            $svg = preg_replace( '[<svg]', '<svg preserveAspectRatio="none"', $svg );
        }

        if ( 'yes' === $settings['svg_remove_inline_css'] ) {
            $svg = preg_replace( '[style\s*?=\s*?"\s*?.*?\s*?"]', '', $svg );
        }

        if ( 'yes' === $settings['svg_custom_color'] ) {
            $svg = preg_replace( '[fill\s*?=\s*?("(?!(?:\s*?none\s*?)")[^"]*")]', 'fill="currentColor"', $svg );
            $svg = preg_replace( '[stroke\s*?=\s*?("(?!(?:\s*?none\s*?)")[^"]*")]', 'stroke="currentColor"', $svg );
        }

        return $svg;
    }

    protected function render() {
        $this->__context = 'render';
        $settings        = $this->get_settings_for_display();
        $tag             = 'div';
        $svg             = lastudio_elements_tools()->get_image_by_url( $settings['svg_url']['url'], array( 'class' => 'lastudio-inline-svg__inner' ) );

        $url = esc_url( $settings['svg_url']['url'] );

        if ( empty( $url ) ) {
            return;
        }

        $ext  = pathinfo( $url, PATHINFO_EXTENSION );

        if ( 'svg' !== $ext ) {
            return printf( '<h5 class="lastudio-inline-svg__error">%s</h5>', esc_html__( 'Please choose a SVG file format.', 'lastudio-elements' ) );
        }

        $svg = $this->prepare_svg( $svg, $settings );

        $this->add_render_attribute( 'svg_wrap', 'class', 'lastudio-inline-svg' );

        if ( ! empty( $settings['svg_link']['url'] ) ) {
            $tag = 'a';
            $this->add_render_attribute( 'svg_wrap', 'href', $settings['svg_link']['url'] );

            if ( 'on' === $settings['svg_link']['is_external'] ) {
                $this->add_render_attribute( 'svg_wrap', 'target', '_blank' );
            }

            if ( 'on' === $settings['svg_link']['nofollow'] ) {
                $this->add_render_attribute( 'svg_wrap', 'rel', 'nofollow' );
            }
        }

        if ( 'yes' === $settings['svg_custom_width'] ) {
            $this->add_render_attribute( 'svg_wrap', 'class', 'lastudio-inline-svg--custom-width' );
        }

        if ( 'yes' === $settings['svg_custom_color'] ) {
            $this->add_render_attribute( 'svg_wrap', 'class', 'lastudio-inline-svg--custom-color' );
        }

        $this->__open_wrap();
        echo '<div class="lastudio-inline-svg__wrapper"><' . $tag . ' ' . $this->get_render_attribute_string( 'svg_wrap' ) . '>';
        echo $svg;
        echo '</' . $tag . '></div>';
        $this->__close_wrap();
    }

}