<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ldyf' );

/** MySQL database username */
define( 'DB_USER', 'ldyf' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'OUEWE0}cOaV;^-E~9h5Ss?wXgNMuBW8X.9]Rex<ykxOPb;;]*zx4~LBQoRsUhf`h' );
define( 'SECURE_AUTH_KEY',  ';+gxS-fbxH~ @00u.35^(1`#dbC(a#<9x&{hx7L$Y!D~qa8l1l!+0ulqELMkN`02' );
define( 'LOGGED_IN_KEY',    '1FK.UMK$|[?] {/oRju{edxS~IS<5caT&Eqz6d)EHrq=(7k%fRz^$@ @ }a&a$Q}' );
define( 'NONCE_KEY',        'Sb*ZcD^)R=z1TLmy9??l{LW=gA6/J7D/I6W1;QjB@Z8[xO5=R.xE4l]<lUV+S$a%' );
define( 'AUTH_SALT',        'I|6Wc%bGl}lb}GfZ_%r8smq<RF.rDHe2o8tumg/!vq,Jw!(M;o!.yl+eBp+W6qAJ' );
define( 'SECURE_AUTH_SALT', '%[SZj[2V8aj1 Hc=aZuoGa!2)i%vZu_7!98~M@Zo$K/9DapB#OZ4^FoyaK+YE%X)' );
define( 'LOGGED_IN_SALT',   '&49jjPxENNDJ2WMQ#4Ef. #:EkBJM P,]]jv=&MyIh}9+8O+vA{e7w,2>.@)^Y|~' );
define( 'NONCE_SALT',       'n1bMnhE0h;(J`){uT7; .eu$r|2cO^psINT@;>5KVB$](g>D$0P&f_3^S#ei #>L' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
