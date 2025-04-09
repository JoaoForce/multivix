<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '_V=0LOp#)&w=Fn>Bor2XCagG=pD^$uSP!TJ;n+4G4vL.u[DNKcy1=d-k}J-D2lq9' );
define( 'SECURE_AUTH_KEY',   'LomDI(:z[gl[^&e:.K?,J<YSijRs_7tw&3.?<Z`a<<aHlt![DT]ykffXqM%[)j H' );
define( 'LOGGED_IN_KEY',     'y49Xbb4yNR*vx!444X%wH!w(6x<{d)NiGgfd3`stzbo[fw47r%K&fuk7^/7^WFW`' );
define( 'NONCE_KEY',         ':IBYeTr< a#SV~.j[(~tr kRT(5fW.)nH+:Uie,p85Bn7w#J%u7xQZIW:llzyzqF' );
define( 'AUTH_SALT',         '[Kw])!F8HS6[RopldU0&ZAR)D/bTzC=^&fiw%uy=F;+4Njyt!px/>f5ghb|6|ZWZ' );
define( 'SECURE_AUTH_SALT',  ';Xt4i)FTP56,4{[ETtoNDyQ/*9x[3a1u<*;40-W!Xt)5c:yTizC3kj5LcHrL9pd9' );
define( 'LOGGED_IN_SALT',    '@Evo^}y|z@0o7g}dDf&7f*wq]bNw:VoQ.Y@Si2!31mXQpVkXP(Z[um@s7b^j+mY ' );
define( 'NONCE_SALT',        's}gh>!gyxUi2@;{-xP.1@]md_RR9fH ! Xe9qt>KOMdL(JP{Lr1i*<g}qwKO1uSI' );
define( 'WP_CACHE_KEY_SALT', 'P7C5OaP=r^nBMUppa:bK@;Y&P%,c3?C9kSx#TW<rxrQRoWBNt=Wl[UM(a25Oc5.`' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
