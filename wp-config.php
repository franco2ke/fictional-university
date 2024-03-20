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
define( 'AUTH_KEY',          ':R&u/JiI*V/jn^r4/.QtUed_?!u&GGQ%m$CT^4]H{u>@~{hE*)jt,M%%&aDqG Da' );
define( 'SECURE_AUTH_KEY',   'j7,)~f{j-?V~NoXT<~<H*b`ts8_hL}%6vNeJQ9+!!WuH~Vl%)*t4,u!=7^Nz>vH4' );
define( 'LOGGED_IN_KEY',     '7WgGy]nWz*vR]z9dcN^dzy23|_ByI*M /uf FWG?>I_W[4_7HbB^qL6889a5:U`O' );
define( 'NONCE_KEY',         'Pgr(W>X^`!#S6I(P%MU{8gz~6:lUQs11O Esf`T&<|e_2NBZvJS0e*|27cQ7rY+@' );
define( 'AUTH_SALT',         'w=5$-54,krFpfcU*TP=T,^lUVF=+#&I6L9,S+$]^_.T 9.b433(=AvyD~,77qN?-' );
define( 'SECURE_AUTH_SALT',  'ga8BMGRemox-KDIHw|TBAd}gl*, 2?ZqjMuA YNqW|bRleZZ(VE$N?M%r/viCnd0' );
define( 'LOGGED_IN_SALT',    '-P]bt]50cQ 0bQhA-<AO7iu:z&^zo83:Z{LsMC RkIi)EdaZ4ef*^YDSU_yOqH]P' );
define( 'NONCE_SALT',        '-:%1J7 cc)g$sy%I-C<Ycl(y1;UZBB;i^6wKtFH+uYIYy1yJIt@lpM;8=A9bn[D+' );
define( 'WP_CACHE_KEY_SALT', 'GiOF4t<jdS|uWv~PDcreo8Ptidv ^N^|7 -F>#7 +(;9#)lK`JQTN=a<F<;q9.aO' );


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
