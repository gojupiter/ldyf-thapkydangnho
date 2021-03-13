<?php
namespace Lastudio_Elements\Classes;

class Lastudio_Elements_WPML {
    public function __construct()
    {
		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'translate_fields' ] );
	}
	
	public function translate_fields ( $nodes_to_translate ) {


        $nodes_to_translate[ 'lastudio-animated-box' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-animated-box' ),
            'fields'     => array(
                array(
                    'field'       => 'front_side_title',
                    'type'        => esc_html__( 'LaStudio Animated Box: Front Title', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'front_side_subtitle',
                    'type'        => esc_html__( 'LaStudio Animated Box: Front SubTitle', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'front_side_description',
                    'type'        => esc_html__( 'LaStudio Animated Box: Front Description', 'lastudio-elements' ),
                    'editor_type' => 'VISUAL',
                ),
                array(
                    'field'       => 'back_side_title',
                    'type'        => esc_html__( 'LaStudio Animated Box: Back Title', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'back_side_subtitle',
                    'type'        => esc_html__( 'LaStudio Animated Box: Back SubTitle', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'back_side_description',
                    'type'        => esc_html__( 'LaStudio Animated Box: Back Description', 'lastudio-elements' ),
                    'editor_type' => 'VISUAL',
                ),
                array(
                    'field'       => 'back_side_button_text',
                    'type'        => esc_html__( 'LaStudio Animated Box: Button Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-banner' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-banner' ),
            'fields'     => array(
                array(
                    'field'       => 'banner_title',
                    'type'        => esc_html__( 'LaStudio Banner: Title', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'banner_text',
                    'type'        => esc_html__( 'LaStudio Banner: Description', 'lastudio-elements' ),
                    'editor_type' => 'VISUAL',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-countdown-timer' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-countdown-timer' ),
            'fields'     => array(
                array(
                    'field'       => 'label_days',
                    'type'        => esc_html__( 'LaStudio Countdown Timer: Label Days', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'label_hours',
                    'type'        => esc_html__( 'LaStudio Countdown Timer: Label Hours', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'label_min',
                    'type'        => esc_html__( 'LaStudio Countdown Timer: Label Min', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'label_sec',
                    'type'        => esc_html__( 'LaStudio Countdown Timer: Label Sec', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),

            ),
        );

        $nodes_to_translate[ 'lastudio-download-button' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-download-button' ),
            'fields'     => array(
                array(
                    'field'       => 'download_label',
                    'type'        => esc_html__( 'LaStudio Download Button: Label', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),

            ),
        );

        $nodes_to_translate[ 'lastudio-circle-progress' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-circle-progress' ),
            'fields'     => array(
                array(
                    'field'       => 'title',
                    'type'        => esc_html__( 'LaStudio Circle Progress: Title', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'subtitle',
                    'type'        => esc_html__( 'LaStudio Circle Progress: Subtitle', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-posts' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-posts' ),
            'fields'     => array(
                array(
                    'field'       => 'more_text',
                    'type'        => esc_html__( 'LaStudio Posts: Read More Button Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-animated-text' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-animated-text' ),
            'fields'     => array(
                array(
                    'field'       => 'before_text_content',
                    'type'        => esc_html__( 'LaStudio Animated Text: Before Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'after_text_content',
                    'type'        => esc_html__( 'LaStudio Animated Text: After Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
            'integration-class' => 'WPML_LaStudio_Elements_Animated_Text',
        );

        $nodes_to_translate[ 'lastudio-carousel' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-carousel' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Advanced_Carousel',
        );

        $nodes_to_translate[ 'lastudio-advanced-map' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-advanced-map' ),
            'fields'     => array(
                array(
                    'field'       => 'map_center',
                    'type'        => esc_html__( 'LaStudio Map: Map Center', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
            'integration-class' => 'WPML_LaStudio_Elements_Advanced_Map',
        );

        $nodes_to_translate[ 'lastudio-brands' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-brands' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Brands',
        );

        $nodes_to_translate[ 'lastudio-images-layout' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-images-layout' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Images_Layout',
        );

        $nodes_to_translate[ 'lastudio-pricing-table' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-pricing-table' ),
            'fields'     => array(
                array(
                    'field'       => 'title',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Title', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'subtitle',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Subtitle', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'price_prefix',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Price Prefix', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'price',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Price', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'price_suffix',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Price Suffix', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'price_desc',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Price Description', 'lastudio-elements' ),
                    'editor_type' => 'VISUAL',
                ),
                array(
                    'field'       => 'button_before',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Button Before', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'button_text',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Button Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'button_after',
                    'type'        => esc_html__( 'LaStudio Pricing Table: Button After', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
            'integration-class' => 'WPML_LaStudio_Elements_Pricing_Table',
        );

        $nodes_to_translate[ 'lastudio-slider' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-slider' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Slider',
        );

        $nodes_to_translate[ 'lastudio-services' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-services' ),
            'fields'     => array(
                array(
                    'field'       => 'services_title',
                    'type'        => esc_html__( 'LaStudio Services: Title', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'services_description',
                    'type'        => esc_html__( 'LaStudio Services: Description', 'lastudio-elements' ),
                    'editor_type' => 'VISUAL',
                ),
                array(
                    'field'       => 'button_text',
                    'type'        => esc_html__( 'LaStudio Services: Button Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-team-member' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-team-member' ),
            'fields'     => array(
                array(
                    'field'       => 'member_first_name',
                    'type'        => esc_html__( 'LaStudio Team Member: First Name', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'member_last_name',
                    'type'        => esc_html__( 'LaStudio Team Member: Last Name', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'member_position',
                    'type'        => esc_html__( 'LaStudio Team Member: Position', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'member_description',
                    'type'        => esc_html__( 'LaStudio Team Member: Description', 'lastudio-elements' ),
                    'editor_type' => 'VISUAL',
                ),
            ),
            'integration-class' => 'WPML_LaStudio_Elements_Team_Member',
        );

        $nodes_to_translate[ 'lastudio-testimonials' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-testimonials' ),
            'fields'     => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Testimonials',
        );

        $nodes_to_translate[ 'lastudio-button' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-button' ),
            'fields'     => array(
                array(
                    'field'       => 'button_label_normal',
                    'type'        => esc_html__( 'LaStudio Button: Normal Label', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'button_label_hover',
                    'type'        => esc_html__( 'LaStudio Button: Hover Label', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-image-comparison' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-image-comparison' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Image_Comparison',
        );

        $nodes_to_translate[ 'lastudio-headline' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-headline' ),
            'fields'     => array(
                array(
                    'field'       => 'first_part',
                    'type'        => esc_html__( 'LaStudio Headline: First Part', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'second_part',
                    'type'        => esc_html__( 'LaStudio Headline: Second Part', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-scroll-navigation' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-scroll-navigation' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Scroll_Navigation',
        );

        $nodes_to_translate[ 'lastudio-subscribe-form' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-subscribe-form' ),
            'fields'     => array(
                array(
                    'field'       => 'submit_button_text',
                    'type'        => esc_html__( 'LaStudio Subscribe Form: Submit Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'submit_placeholder',
                    'type'        => esc_html__( 'LaStudio Subscribe Form: Input Placeholder', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
            'integration-class' => 'WPML_LaStudio_Elements_Subscribe_Form',
        );

        $nodes_to_translate[ 'lastudio-dropbar' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-dropbar' ),
            'fields'     => array(
                array(
                    'field'       => 'button_text',
                    'type'        => esc_html__( 'LaStudio Dropbar: Button Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'simple_content',
                    'type'        => esc_html__( 'LaStudio Dropbar: Simple Text', 'lastudio-elements' ),
                    'editor_type' => 'VISUAL',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-portfolio' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-portfolio' ),
            'fields'     => array(
                array(
                    'field'       => 'all_filter_label',
                    'type'        => esc_html__( 'LaStudio Portfolio: `All` Filter Label', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
                array(
                    'field'       => 'view_more_button_text',
                    'type'        => esc_html__( 'LaStudio Portfolio: View More Button Text', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
            'integration-class' => 'WPML_LaStudio_Elements_Portfolio',
        );

        $nodes_to_translate[ 'lastudio-price-list' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-price-list' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Price_List',
        );

        $nodes_to_translate[ 'lastudio-progress-bar' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-progress-bar' ),
            'fields'     => array(
                array(
                    'field'       => 'title',
                    'type'        => esc_html__( 'LaStudio Progress Bar: Title', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-timeline' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-timeline' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Timeline',
        );

        $nodes_to_translate[ 'lastudio-weather' ] = array(
            'conditions' => array( 'widgetType' => 'lastudio-weather' ),
            'fields'     => array(
                array(
                    'field'       => 'location',
                    'type'        => esc_html__( 'LaStudio Weather: Location', 'lastudio-elements' ),
                    'editor_type' => 'LINE',
                ),
            ),
        );

        $nodes_to_translate[ 'lastudio-table' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-table' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Table',
        );

        $nodes_to_translate[ 'lastudio-horizontal-timeline' ] = array(
            'conditions'        => array( 'widgetType' => 'lastudio-horizontal-timeline' ),
            'fields'            => array(),
            'integration-class' => 'WPML_LaStudio_Elements_Horizontal_Timeline',
        );

		$this->init_classes();
		
		return $nodes_to_translate;
	}
	
	private function init_classes() {
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-advanced-carousel.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-map.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-animated-text.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-brands.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-images-layout.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-pricing-table.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-slider.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-team-member.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-testimonials.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-image-comparison.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-scroll-navigation.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-portfolio.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-price-list.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-subscribe-form.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-timeline.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-table.php';
        require_once LASTUDIO_ELEMENTS_PATH . 'classes/wpml/class-wpml-lastudio-elements-horizontal-timeline.php';
	}
}

$lastudio_elements_wpml = new Lastudio_Elements_WPML();
