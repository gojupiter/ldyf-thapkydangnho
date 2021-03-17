<?php
/**
 * Header Builder - AJAX Class.
 *
 * @author	LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHFB_Ajax' ) ) :

    class LAHFB_Ajax {

		/**
		 * Instance of this class.
         *
		 * @since	1.0.0
		 * @access	private
		 * @var		LAHFB_Ajax
		 */
		private static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since	1.0.0
		 * @return	object
		 */
		public static function get_instance() {

			if ( self::$instance === null ) {
				self::$instance = new self();
            }

			return self::$instance;

		}

		/**
		 * Constructor.
		 *
		 * @since	1.0.0
		 */
		public function __construct() {

		    // Global AJAX action
			add_action( 'wp_ajax_lahfb_ajax_action',			    array( $this, 'detect_ajax_action' ) );

			// Remove item from woocommerce cart
			add_action( 'wp_ajax_nopriv_la_woo_ajax_update_cart',	array( $this, 'remove_item_from_cart' ) );
			add_action( 'wp_ajax_la_woo_ajax_update_cart',			array( $this, 'remove_item_from_cart' ) );

			// Add item to woocommerce cart
			add_action( 'wp_ajax_nopriv_la_woo_ajax_add_to_cart',	array( $this, 'add_item_to_cart' ) );
			add_action( 'wp_ajax_la_woo_ajax_add_to_cart',			array( $this, 'add_item_to_cart' ) );
		}

		public function detect_ajax_action(){

            check_ajax_referer( 'lahfb-nonce', 'nonce' );

		    $router = isset($_REQUEST['router']) ? sanitize_text_field($_REQUEST['router']) : '';
		    if(!empty($router)){
		        switch ($router){

                    case 'save_header_as_template':
                        $this->ajax_save_header_as_template();
                        break;

                    case 'delete_header_preset_template':
                        $this->ajax_delete_header_template();
                        break;

                    case 'publish_header':
                        $this->ajax_publish_header();
                        break;

                    case 'save_header_data':
                        $this->ajax_save_header_data();
                        break;

                    case 'get_component_field':
                        $this->ajax_get_component_field();
                        break;

                    case 'export_header':
                        $this->ajax_export_header();
                        break;

                    case 'live_dynamic_styles':
                        $this->ajax_live_dynamic_styles();
                        break;


                    default:
                        // does not allow to do anything on here !!!!!
                }
            }
            wp_die();
        }

        private function ajax_get_component_field(){
            $el_name = $_POST['el_name'];
            ob_start();
            include_once LAHFB_Helper::get_file( 'includes/elements/components/backend/' . $el_name . '-backend.php' );
            $output = ob_get_contents();
            ob_end_clean();
            echo $output;
        }

        /**
         * Save data for header and render HTML
         */
        private function ajax_save_header_data(){
            $frontendComponents = maybe_unserialize( json_decode( stripslashes( $_POST['frontendComponents'] ), true ) );
            $headerPreset = !empty($_POST['headerPreset']) ? esc_attr($_POST['headerPreset']) : '';
            echo LAHFB_Output::output(true, $frontendComponents, $headerPreset);
        }

        /**
         * Publish this header
         */
        private function ajax_publish_header(){
            $frontendComponents = maybe_unserialize( json_decode( stripslashes( $_POST['frontendComponents'] ), true ) );
            $frontendComponents = LAHFB_Helper::convertOldHeaderData($frontendComponents);
            update_option('lahfb_data_frontend_components', $frontendComponents);
            update_option( 'lastudio_header_layout', 'builder' );
        }

        /**
         * Save preset with new data !
         */
        private function ajax_save_header_as_template(){
            $content = array();
            $data_received = !empty($_REQUEST['frontendComponents']) ? $frontendComponents = maybe_unserialize( json_decode( stripslashes( $_REQUEST['frontendComponents'] ), true ) ) : get_option('lahfb_data_frontend_components', array());
            $frontendComponents = LAHFB_Helper::convertOldHeaderData($data_received);
            $content['lahfb_data_frontend_components'] = $frontendComponents;
            $content = json_encode( $content );

            $new_name = !empty($_REQUEST['header_name']) ? esc_html($_REQUEST['header_name']) :  'Pre-defined Headers ' . date( 'd-m-Y' );
            $new_key = !empty($_REQUEST['header_key']) ? esc_html($_REQUEST['header_key']) : sanitize_title($new_name);
            $image = !empty($_REQUEST['header_image']) ? esc_html($_REQUEST['header_image']) : 0;

            $existing_headers = LAHFB_Helper::get_prebuild_headers();

            if(!empty($existing_headers[$new_key]['image']) && empty($image)){
                $image = $existing_headers[$new_key]['image'];
            }

            $existing_headers[$new_key] = array(
                'name' => $new_name,
                'image' => $image,
                'data' => $content
            );

            update_option( 'lahfb_preheaders', $existing_headers );

            $response = array();
            foreach ($existing_headers as $k => $v){
                $tmp = array(
                    'name' => $v['name']
                );
                if(!empty($v['image']) && wp_attachment_is_image($v['image'])){
                    $tmp['image'] = wp_get_attachment_image_url($v['image'], 'full');
                }
                $response[$k] = $tmp;
            }
            wp_send_json_success($response);
        }


		/**
		 * Live styling tab and other dynamic styles.
		 *
		 * @since	1.0.0
		 */
		public function ajax_live_dynamic_styles() {
            $prebuild_header_key = '';
            if(!empty($_REQUEST['headerPreset']) && LAHFB_Helper::is_prebuild_header_exists(esc_attr($_REQUEST['headerPreset']))){
                $prebuild_header_key = esc_attr($_REQUEST['headerPreset']);
            }
			echo LAHFB_Helper::get_dynamic_styles($prebuild_header_key);
		}

		public function ajax_delete_header_template(){
		    $key = !empty($_REQUEST['header_key']) ? esc_attr($_REQUEST['header_key']) : '';
            $existing_headers = LAHFB_Helper::get_prebuild_headers();
            if(!empty($key) && isset($existing_headers[$key])){
                unset($existing_headers[$key]);
                update_option('lahfb_preheaders', $existing_headers);

                $response = array();
                foreach ($existing_headers as $k => $v){
                    $tmp = array(
                        'name' => $v['name']
                    );
                    if(!empty($v['image']) && wp_attachment_is_image($v['image'])){
                        $tmp['image'] = wp_get_attachment_image_url($v['image'], 'full');
                    }
                    $response[$k] = $tmp;
                }
                wp_send_json_success($response);
            }
        }

		/**
		 * Export header.
		 * 
		 * @since	1.0.0
		 */
		private function ajax_export_header() {

			$content = array();

			$content['lahfb_data_frontend_components'] = get_option('lahfb_data_frontend_components', array());

			if(!empty($_GET['prebuild_header'])){
                $prebuild_header_key = esc_attr($_REQUEST['prebuild_header']);
                $all_existing = LAHFB_Helper::get_prebuild_headers();
                if(!empty($all_existing[$prebuild_header_key]) && !empty($all_existing[$prebuild_header_key]['data'])) {
                    $tmp = json_decode($all_existing[$prebuild_header_key]['data'], true);
                    $content['lahfb_data_frontend_components'] = $tmp['lahfb_data_frontend_components'];
                }
            }

			$content = json_encode( $content );

			header( 'Content-Description: File Transfer' );
			header( 'Content-type: application/txt' );
			header( 'Content-Disposition: attachment; filename="lahfb-header-builder-' . date( 'd-m-Y' ) . '.json"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );

			echo wp_unslash( $content );

			exit;

		}

		/**
		 * Remove item from woocommerce cart.
		 *
		 * @since	1.0.0
		 * @return	cart items
		 */
		public function remove_item_from_cart() {

			// Set the product ID to remove
			$cart_product_id = $_POST['cart_product_id'];
			$prod_to_remove	 = array( $cart_product_id );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
				$cart_item_id = $cart_item['data']->get_id();

				if ( in_array($cart_item_id, $prod_to_remove) ) {
					WC()->cart->remove_cart_item($cart_item_key);
				}
			}

			lahfb_mini_cart();

			wp_die(); // this is required to terminate immediately and return a proper response

		}

		/**
		 * Add item to woocommerce cart.
		 *
		 * @since   1.0.0
		 * @return  cart items
		 */
		public function add_item_to_cart() {

			WC()->cart->add_to_cart( $product_id = $_POST['cart_product_id'], $quantity = 1, $variation_id = 0, $variation = array(), $cart_item_data = array() );

			lahfb_mini_cart();

			wp_die(); // this is required to terminate immediately and return a proper response

		}
    }

endif;
