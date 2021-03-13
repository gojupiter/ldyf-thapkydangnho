<?php
namespace Lastudio_Elements\Modules\SubscribeForm;

use Lastudio_Elements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

    /**
     * System message.
     *
     * @var array
     */
    public $sys_messages = array();

    /**
     * MailChimp API server
     *
     * @var string
     */
    private $api_server = 'https://%s.api.mailchimp.com/2.0/';

    public function __construct(){
        parent::__construct();
        $this->init_ajax_handler();
    }

	public function get_name() {
		return 'lastudio-subscribe-form';
	}

	public function get_widgets() {
		return [
			'Subscribe_Form'
		];
	}

	public function init_ajax_handler(){

        $this->sys_messages = apply_filters( 'lastudio_elements/popups_sys_messages', array(
            'invalid_mail'                => esc_html__( 'Please, provide valid mail', 'lastudio-elements' ),
            'mailchimp'                   => esc_html__( 'Please, set up MailChimp API key and List ID', 'lastudio-elements' ),
            'internal'                    => esc_html__( 'Internal error. Please, try again later', 'lastudio-elements' ),
            'server_error'                => esc_html__( 'Server error. Please, try again later', 'lastudio-elements' ),
            'subscribe_success'           => esc_html__( 'Success', 'lastudio-elements' ),
        ) );

        add_action( 'wp_ajax_lastudio_subscribe_form_ajax', [ $this, 'subscribe_form_ajax' ] );
        add_action( 'wp_ajax_nopriv_lastudio_subscribe_form_ajax', [ $this, 'subscribe_form_ajax' ] );

        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_localize_script' ] );

    }

    public function enqueue_frontend_localize_script(){
        wp_localize_script(
            'lastudio-elements',
            'lastudio_subscribe_form_ajax',
            array(
                'action' => 'lastudio_subscribe_form_ajax',
                'nonce' => wp_create_nonce('lastudio_subscribe_form_ajax'),
                'type' => 'POST',
                'data_type' => 'json',
                'is_public' => 'true',
                'sys_messages' => $this->sys_messages
            )
        );
    }

    public function subscribe_form_ajax() {

        if ( ! wp_verify_nonce( isset($_POST['nonce']) ? $_POST['nonce'] : false, 'lastudio_subscribe_form_ajax' ) ) {
            $response = array(
                'message' => $this->sys_messages['invalid_nonce'],
                'type'    => 'error-notice',
            ) ;

            wp_send_json( $response );
        }

        $data = ( ! empty( $_POST['data'] ) ) ? $_POST['data'] : false;

        if ( ! $data ) {
            wp_send_json_error( array( 'type' => 'error', 'message' => $this->sys_messages['server_error'] ) );
        }

        $api_key = get_option('lastudio_elements_mailchimp_api_key', '');
        $list_id = get_option('lastudio_elements_mailchimp_list_id', '');
        $double_opt_in = filter_var( get_option('lastudio_elements_mailchimp_double_opt_in', ''), FILTER_VALIDATE_BOOLEAN );

        if ( ! $api_key ) {
            wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
        }

        if ( isset( $data['use_target_list_id'] ) &&
            filter_var( $data['use_target_list_id'], FILTER_VALIDATE_BOOLEAN ) &&
            ! empty( $data['target_list_id'] )
        ) {
            $list_id = $data['target_list_id'];
        }

        if ( ! $list_id ) {
            wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
        }

        $mail = $data['email'];

        if ( empty( $mail ) || ! is_email( $mail ) ) {
            wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['invalid_mail'] ) );
        }

        $args = [
            'email_address' => $mail,
            'status'        => $double_opt_in ? 'pending' : 'subscribed',
        ];

        if ( ! empty( $data['additional'] ) ) {

            $additional = $data['additional'];

            foreach ( $additional as $key => $value ) {
                $merge_fields[ strtoupper( $key ) ] = $value;
            }

            $args['merge_fields'] = $merge_fields;

        }

        $response = $this->api_call( $api_key, $list_id, $args );

        if ( false === $response ) {
            wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
        }

        $response = json_decode( $response, true );

        if ( empty( $response ) ) {
            wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['internal'] ) );
        }

        if ( isset( $response['status'] ) && 'error' == $response['status'] ) {
            wp_send_json( array( 'type' => 'error', 'message' => esc_html( $response['error'] ) ) );
        }

        wp_send_json( array( 'type' => 'success', 'message' => $this->sys_messages['subscribe_success'] ) );
    }

    /**
     * Make remote request to mailchimp API
     *
     * @param  string $method API method to call.
     * @param  array  $args   API call arguments.
     * @return array|bool
     */
    public function api_call( $api_key, $list_id, $args = [] ) {

        $key_data = explode( '-', $api_key );

        if ( empty( $key_data ) || ! isset( $key_data[1] ) ) {
            return false;
        }

        $this->api_server = sprintf( 'https://%s.api.mailchimp.com/3.0/', $key_data[1] );

        $url = esc_url( trailingslashit( $this->api_server . 'lists/' . $list_id . '/members/' ) );

        $data = json_encode( $args );

        $request_args = [
            'method'      => 'POST',
            'timeout'     => 20,
            'headers'     => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'apikey ' . $api_key
            ],
            'body'        => $data,
        ];

        $request = wp_remote_post( $url, $request_args );

        /*$url = esc_url( trailingslashit( $this->api_server . 'lists/' . $list_id . '/merge-fields' ) );
        $request = wp_remote_post( $url, [
            'method'      => 'GET',
            'timeout'     => 20,
            'headers'     => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'apikey ' . $api_key
            ],
            //'body'        => $data,
        ] );*/

        //var_dump( json_decode(wp_remote_retrieve_body( $request ), true ) );
        //exit();

        return wp_remote_retrieve_body( $request );
    }

}