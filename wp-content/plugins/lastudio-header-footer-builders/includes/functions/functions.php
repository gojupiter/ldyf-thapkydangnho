<?php

if (!function_exists('la_log')) {
    function la_log($log)
    {
        if (true === WP_DEBUG) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

if(!function_exists('lahfb_pretty_property_name')){
    function lahfb_pretty_property_name( $property, $screen, $id ){
        $prefix = '_sae_';
        switch ( $screen ) {
            case 'tablets':
                $prefix = '_ste_';
                break;
            case 'mobiles':
                $prefix = '_sme_';
                break;
            default:
                $prefix = '_sae_';
        }
        $name = $property . $prefix . $id;
        return $name;
    }
}

if (!function_exists('lahfb_login_form')) {
    function lahfb_login_form() {
        $user_ID = get_current_user_id();
        if ($user_ID) : ?>
        <?php
            $user_identity = wp_get_current_user()->display_name;
        ?>
            <div class="login-dropdown-arrow-wrap"></div>
            <div id="user-logged" class="colorskin-custom">
                <span class="author-avatar"><?php echo get_avatar( $user_ID, $size = '100'); ?></span>
                <div class="user-welcome"><?php esc_html_e('Hello','lastudio-header-footer-builder'); ?> <strong><?php echo esc_html($user_identity) ?></strong></div>
                <ul class="logged-links colorb">
                    <?php if ( class_exists( 'BuddyPress' ) ) { ?>
                        <li><a href="<?php echo bp_loggedin_user_domain(); ?>"><?php esc_html_e('Profile','lastudio-header-footer-builder'); ?> </a></li>
                        <li><a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>"><?php esc_html_e('Logout','lastudio-header-footer-builder'); ?> </a></li>
                    <?php } elseif(function_exists('wc_get_account_menu_items')) { ?>
                        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                            </li>
                        <?php endforeach; ?>
                    <?php } else { ?>
                        <li><a href="<?php echo esc_url(home_url('/wp-admin/')); ?>"><?php esc_html_e('Dashboard','lastudio-header-footer-builder'); ?> </a></li>
                        <li><a href="<?php echo esc_url(home_url('/wp-admin/profile.php')); ?>"><?php esc_html_e('My Profile','lastudio-header-footer-builder'); ?> </a></li>
                        <li><a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>"><?php esc_html_e('Logout','lastudio-header-footer-builder'); ?> </a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        <?php else: ?>
            <div class="login-dropdown-arrow-wrap"></div>
            <?php if ( is_plugin_active('super-socializer/super_socializer.php') ) { ?>
                <!-- social login -->
                <div class="wn-social-login">
                    <?php echo do_shortcode('[TheChamp-Login]'); ?>
                    <div class="wn-or-divider">
                        <span><?php _e( 'or', 'lastudio-header-footer-builder' ); ?></span>
                    </div>
                </div>
            <?php } ?>
            <h3 id="user-login-title" class="user-login-title"><?php esc_html_e( 'Login', 'lastudio-header-footer-builder' ); ?></h3>
            <div id="user-login">
                <?php
                $form = wp_login_form(array(
                    'echo'          => false,
                    'form_id'       => 'lahfb_loginform',
                    'id_username'   => 'lahfb_user_login',
                    'id_password'   => 'lahfb_user_pass',
                    'id_remember'   => 'lahfb_rememberme',
                    'id_submit'     => 'lahfb_wp_submit',
                ));

                echo str_replace(
                    array(
                        '<form ',
                        '<p class="login-username">',
                        '<p class="login-password">',
                        '<p class="login-remember">',
                        'class="input"'
                    ),
                    array(
                        '<form class="login" ',
                        '<p class="login-username form-row">',
                        '<p class="login-password form-row">',
                        '<p class="login-remember form-row">',
                        'class="input input-text"'
                    ),
                    $form
                );

                ?>
                <ul class="login-links">
                    <?php if ( get_option('users_can_register') ) : ?><?php echo wp_register() ?><?php endif; ?>
                    <li><a href="<?php echo esc_url(wp_lostpassword_url(get_permalink()))?>"><?php esc_html_e('Lost your password?','lastudio-header-footer-builder'); ?></a></li>
                </ul>
            </div>
        <?php endif;
    }
}


/**
 * Mini cart.
 *
 * @since   1.0.0
 */
if (!function_exists('lahfb_mini_cart')) {
    function lahfb_mini_cart()
    {

        echo '<div class="widget_shopping_cart_content">';
        if (function_exists('woocommerce_mini_cart')) {
            woocommerce_mini_cart();
        }
        echo '</div>';

    }
}

if (!function_exists('lahfb_gradient')) {

    function lahfb_gradient($color_1 = '', $color_2 = '', $color_3 = '', $color_4 = '', $deg = '90deg')
    {

        // variables
        $grad_colors = array();
        $color_1 = $color_1 ? $grad_colors[] = $color_1 : '';
        $color_2 = $color_2 ? $grad_colors[] = $color_2 : '';
        $color_3 = $color_3 ? $grad_colors[] = $color_3 : '';
        $color_4 = $color_4 ? $grad_colors[] = $color_4 : '';
        $deg = ($deg == '' || $deg == '0' || $deg == '0deg') ? '-360deg' : $deg;
        $grad_bg = '';
        $grad_count = sizeof($grad_colors);
        $grad_plus = 0;
        $grad = '';
        $precent = $grad_count - 1;

        // Diagnosis direction
        if ($deg) {

            $deg = explode('deg', $deg);
            $deg = $deg[0];

        }

        // default color
        if (!empty($color_1)) {

            $grad_bg = $color_1;

        } elseif (!empty($color_2)) {

            $grad_bg = $color_2;

        } elseif (!empty($color_3)) {

            $grad_bg = $color_3;

        } elseif (!empty($color_4)) {

            $grad_bg = $color_4;

        }

        // generate gradient
        for ($i = 0; $i < $grad_count; $i++) {

            // comma
            $comma = $i < $grad_count - 1 ? ',' : '';

            // Percent
            $grad .= $grad_colors[$i] . ' ' . floor($grad_plus) . '%' . $comma . ' ';

            // if precent value isn't ziro
            if ($precent != 0) {
                $grad_plus = $grad_plus + (100 / $precent);
            }

        }

        // output
        $out = '';
        if ($grad_count <= 1) {

            $out .= !empty($grad_bg) ? ' background: ' . $grad_bg . ';' : '';

        } else {

            if (!empty($grad_bg) && !empty($grad) && !empty($deg)) {

                $out .= '
						background: ' . $grad_bg . ';
						background: linear-gradient( ' . $deg . 'deg, ' . $grad . ');
						background: -moz-linear-gradient( ' . $deg . 'deg, ' . $grad . ');
						background: -webkit-linear-gradient( ' . $deg . 'deg, ' . $grad . ');';

            }

        }

        return $out;
    }

}
if (!function_exists('lahfb_rename_icon')) {
    function lahfb_rename_icon($icon = '')
    {
        $icon = str_replace('fa__fa-', 'fa fa-', $icon);
        $icon = str_replace('dl__icon-', 'dl-icon-', $icon);
        return $icon;
    }
}

if( !function_exists('lahfb_wp_get_attachment_url')){
    function lahfb_wp_get_attachment_url($attachment_id = 0){
        return wp_get_attachment_url($attachment_id);
    }
}
