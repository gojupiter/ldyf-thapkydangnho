<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_WooCommerce_Threesixty{

    public function __construct(){

    }

    /** 360 viewer */
  	public function threesixty_product_write_panel_tabs() {
  		printf(
  			'<li class="tab_la_threesixty"><a href="#tab_la_threesixty"><span>%s</span></a></li>',
  			esc_html__('360 Image Settings', 'lastudio')
  		);
  	}

  	public function threesixty_product_data_panel_wrap(){
  		global $post;
  		$enable = get_post_meta( $post->ID, '_la_360_enable', true );
  		printf(
  			'<div id="tab_la_threesixty" class="panel tab_la_threesixty woocommerce_options_panel wc-metaboxes-wrapper" style="display: none"><p class="form-field"><label style="%3$s"><input type="checkbox" class="checkbox" name="_la_360_enable" id="_la_360_enable" value="yes" %1$s/><span class="description">%2$s</span></label></p></div>',
  			checked($enable, 'yes', false),
  			esc_html__('Replace Image with 360 Image', 'lastudio'),
  			'width: 100%;float: none;margin: 0;display: inline-block;'
  		);
  	}

  	public function threesixty_save_product_meta( $post_id, $post ){
  		$data = isset( $_POST['_la_360_enable'] ) ? $_POST['_la_360_enable'] : '';
  		update_post_meta( $post_id, '_la_360_enable', $data );
  	}

  	public function threesixty_replace_product_image(){
  		if(function_exists('is_product') && is_product()) {
  			global $post;
  			$enable = get_post_meta( $post->ID, '_la_360_enable', true );
  			if($enable == 'yes') {
  				remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
  				add_action('woocommerce_before_single_product_summary', array($this, 'threesixty_render_output'));
  				add_filter('post_class', array($this, 'threesixty_add_post_class'));
  			}
  		}
  	}

  	public function threesixty_render_output(){
  		do_action( 'la_threesixty_before_image' );
  		wc_get_template( 'single-product/product-360.php', array(
  			'la_threesixty_vars' => $this->threesixty_js_data_all()
  		));
  		do_action( 'la_threesixty_after_image' );
  	}

  	public function threesixty_add_post_class( $classes ){
  		$classes[] = 'la-360-product';
  		return $classes;
  	}

  	private function threesixty_js_data_all(){

  		$images_array = wp_json_encode( $this->threesixty_js_data_images() );

  		$navigation = true;
  		// Responsiveness
  		$responsive = apply_filters( 'la_threesixty_js_responsive', true );
  		// Drag / Touch
  		$drag = apply_filters( 'la_threesixty_js_drag', true );
  		// Spin
  		$spin = apply_filters( 'la_threesixty_js_spin', false );
  		$speed     = apply_filters( 'la_threesixty_js_speed', 60 );
  		$framerate = apply_filters( 'la_threesixty_js_framerate', $speed );
  		$playspeed = apply_filters( 'la_threesixty_js_playspeed', 100 );

  		// Image Sizes array
  		$wp_get_additional_image_sizes = wp_get_additional_image_sizes();
  		$image_size = isset($wp_get_additional_image_sizes['shop_single']) ? $wp_get_additional_image_sizes['shop_single'] : array( 'width' => 600, 'height' => 600);

  		$width = $image_size['width'];
  		$height = $image_size['height'];

  		return array(
  			'images'     => $images_array,
  			'navigation' => $navigation,
  			'responsive' => $responsive,
  			'drag'       => $drag,
  			'spin'       => $spin,
  			'width'      => $width,
  			'height'     => $height,
  			'framerate'  => $framerate,
  			'playspeed'  => $playspeed,
  		);
  	}

  	private function threesixty_js_data_images( $post_id = '' ) {

  		$image_js_array = array();
  		if ( $post_id ) {
  			$id = $post_id;
  		} else {
  			global $post;
  			$id = $post->ID;
  		}

  		$image_size = 'shop_single';

  		$product = wc_get_product($id);
  		$attachment_ids = $product->get_gallery_image_ids();
  		if ( $attachment_ids ) {
  			do_action('la_threesixty_before_get_image_array');
  			foreach ( $attachment_ids as $attachment_id ) {
  				$image_src = wp_get_attachment_image_src( $attachment_id, $image_size );
  				$image_link = $image_src[0];
  				$image_js_array[] = $image_link;
  			}
  			do_action('la_threesixty_after_get_image_array');
  		}

  		return $image_js_array;

  	}

  	public function threesixty_save_embed_video( $attachment_id ) {
  		if ( isset( $_REQUEST['attachments'][$attachment_id]['videolink'] ) ) {
  			$videolink = $_REQUEST['attachments'][$attachment_id]['videolink'];
  			update_post_meta( $attachment_id, 'videolink', $videolink );
  		}
  	}

  	public function threesixty_add_embed_video( $form_fields, $attachment ) {

  		$field_value = get_post_meta( $attachment->ID, 'videolink', true );

  		$form_fields['videolink'] = array(
  			'value' => $field_value ? $field_value : '',
  			'input' => 'text',
  			'label' => __( 'Video Link', 'lastudio' )
  		);
  		return $form_fields;
  	}

}
