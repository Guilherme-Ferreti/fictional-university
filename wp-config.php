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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fictional-university' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'Fe5J<~rwa^pL3!=5' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'LY*F6)8nsSqvOa+Ab @$nZX<M k]rHL9ND$+3ZS#CnU+TA4jFHE=3.QqK _C8XU]' );
define( 'SECURE_AUTH_KEY',  '{tkF!.jgihSe^;=~.?SbQc-]XmF*8VaYW0%5s@/,77hi|zD?8AQv(KUU|4{8wpgE' );
define( 'LOGGED_IN_KEY',    'l7yhMSVFDsI^fvzxVBlf!rEZa~gEWPEq5&Li:|BC.mw#+HI/UNJ.<^Z17TdT2ldz' );
define( 'NONCE_KEY',        '&*`2X~|F~%RPu.$p(~vG[H`JI^DcbWI6:=JvtXf==tm77nux-dW?LWkP8nIz,BW&' );
define( 'AUTH_SALT',        '>t #HTq=@1RzWh~h;ov](Nw&UAILRF@3@B1HFJC+s06:bo<AKBoZzh:pp`4eMgf!' );
define( 'SECURE_AUTH_SALT', '&6*n0 .+h$w}ieW;?LN8FjFp0*J^mE%xZyU{.,<fj^%#Q3Ec)G>pj(C}|SamIoi/' );
define( 'LOGGED_IN_SALT',   '#Ms:&^Eq,2LT~C/ZKjh_)bAC`R*-]e}hlO)8@Hdo7|:`#GMtm?Ab$O%+5nH:Tq#]' );
define( 'NONCE_SALT',       'kiB~_6c/4,RF:~$ZF>x!F~IS/C0a%f|CgNiSWOxXiD[~1j~VwY.5iNlD+`Ny=MzN' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
