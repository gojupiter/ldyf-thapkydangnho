<?php
if ( is_admin() && isset($_POST['action']) && 'heartbeat' == $_POST['action'] ) {
    return;
}

global $tc, $tc_gateway_plugins, $wp;

if ( !session_id() && !isset( $_SESSION ) ) {
    @session_start();
}

$cart_contents = $tc->get_cart_cookie();
$cart_total = isset( $_SESSION['tc_cart_total'] ) ? $_SESSION['tc_cart_total'] : null;

if ( is_null( $cart_total ) ) {
    $tc->checkout_error = true;
    $_SESSION['tc_gateway_error'] = sprintf(__('Sorry, something went wrong. %sPlease try again%s.', 'tc'), '<a href="' . $tc->get_cart_slug(true) . '">', '</a>');
    wp_redirect($tc->get_payment_slug(true));
    tc_js_redirect($tc->get_payment_slug(true));
    exit;
}


if ( !isset( $_REQUEST['tc_choose_gateway'] ) ) {

    /* Set free orders as gateway since none is selected */

    if ( $cart_total > 0 ) {
        $tc->checkout_error = true;
        $_SESSION['tc_gateway_error'] = sprintf(__('Sorry, something went wrong. %sPlease try again%s.', 'tc'), '<a href="' . $tc->get_cart_slug(true) . '">', '</a>');
        tc_js_redirect($tc->get_payment_slug(true));
        exit;

    } else {

        /* Set free orders since total is exactly zero */

        if ( isset( $_SESSION['tc_cart_total'] ) ) {
            $_SESSION['tc_gateway_error'] = '';
            $tc->checkout_error = false;
            $payment_class_name = $tc_gateway_plugins[apply_filters('tc_not_selected_default_gateway', 'free_orders')][0];

        } else {
            $tc->checkout_error = true;
            $_SESSION['tc_gateway_error'] = sprintf(__('Sorry, something went wrong. %sPlease try again%s.', 'tc'), '<a href="' . $tc->get_cart_slug(true) . '">', '</a>');
            wp_redirect($tc->get_payment_slug(true));
            tc_js_redirect($tc->get_payment_slug(true));
            exit;
        }
    }

} else {

    if ( ( $cart_total > 0 && $_REQUEST['tc_choose_gateway'] !== 'free_orders' ) || ($cart_total == 0 && $_REQUEST['tc_choose_gateway'] == 'free_orders' ) ) {
        $_SESSION['tc_gateway_error'] = '';
        $tc->checkout_error = false;
        $payment_class_name = $tc_gateway_plugins[$_REQUEST['tc_choose_gateway']][0];

    } else {
        $tc->checkout_error = true;
        $_SESSION['tc_gateway_error'] = sprintf(__('Sorry, something went wrong. %sPlease try again%s.', 'tc'), '<a href="' . $tc->get_cart_slug(true) . '">', '</a>');
        wp_redirect($tc->get_payment_slug(true));
        tc_js_redirect($tc->get_payment_slug(true));
        exit;
    }
}

$payment_gateway = new $payment_class_name;

if ( !empty( $cart_contents ) && count( $cart_contents ) > 0 ) {

    if ( false == $tc->checkout_error ) {
        $payment_gateway->process_payment($cart_contents);
        exit;

    } else {
        wp_redirect($this->get_payment_slug(true));
        tc_js_redirect($this->get_payment_slug(true));
        exit;
    }

} else {

    /* The cart is empty and this page shouldn't be reached */

    wp_redirect($this->get_payment_slug(true));
    tc_js_redirect($this->get_payment_slug(true));
    exit;
}
?>