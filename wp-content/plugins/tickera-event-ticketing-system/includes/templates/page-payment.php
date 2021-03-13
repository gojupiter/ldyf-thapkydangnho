<?php
if ( is_admin() || isset( $_POST['action'] ) && 'heartbeat' == $_POST['action'] ) {
    return;
}

global $tc;

$tc->remove_order_session_data_only();
$cart_contents = $tc->get_cart_cookie();
$tc_general_settings = get_option('tc_general_setting', false);

if ( isset( $tc_general_settings['force_login'] ) && 'yes' == $tc_general_settings['force_login'] && !is_user_logged_in() ) : ?>
    <div class="force_login_message"><?php printf(__('Please %s to see this page', 'tc'), '<a href="' . apply_filters('tc_force_login_url', wp_login_url($tc->get_payment_slug(true)), $tc->get_payment_slug(true)) . '">' . __('Log In', 'tc') . '</a>'); ?></div>
<?php else :
    if ( empty( $cart_contents ) ) {
        @wp_redirect($tc->get_cart_slug(true));
        tc_js_redirect($tc->get_cart_slug(true));
        exit;
    }
    if ( false == apply_filters( 'tc_has_cart_or_payment_errors', false, $cart_contents ) ) {
        $tc->cart_payment(true);
    } else {
        do_action( 'tc_has_cart_or_payment_errors_action', $cart_contents );
    }
endif;
$_SESSION['tc_gateway_error'] = ''; ?>
