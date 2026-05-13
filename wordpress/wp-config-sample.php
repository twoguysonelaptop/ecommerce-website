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
define( 'AUTH_KEY',          '^A>(1}Mtk~$!5bL*1l@|>oH52^9UsG0u<|d4]?@kk+=CO0qrVOl2F>kSuI~b<ZR?' );
define( 'SECURE_AUTH_KEY',   '0TE2T1sQ70+;T3LpLgsAz#!q`*hEdX}dE_j*Vd* ~UoWK-]oyTmvCfyl10%z?WmW' );
define( 'LOGGED_IN_KEY',     '6No=y4k{#wu*P~$-kgiU5}2MZZ#o[jM4^yIo=mO2 gct&-zxg(xx#6Px#zU[8>X|' );
define( 'NONCE_KEY',         '(Swe.m?a!Gn~.LK[wc_{ZxWAd5y02&G2geOdOW19^0U<T>bOBebL7^tG(UJ>^Ud.' );
define( 'AUTH_SALT',         'S],o%I{oD)ytFZ7+>{_=*O#|% Q=5DSF5*xJDYR8.mRxDEGgKkdg(,7davR.ueQI' );
define( 'SECURE_AUTH_SALT',  'K/] 2_`Sv%VZ[wHT-V[wYrMi)?Ygl>&L<vS.8j,]V1x}7 ~|w_NfS&eKQRRH4?^@' );
define( 'LOGGED_IN_SALT',    'C-o[pA 9q,fhVr!!A}n@R9.`503&H{<8r;aSMyjL]hT(=;IaC,(HjI!FY:<FN;Ht' );
define( 'NONCE_SALT',        '!+^+Ps?Kn5M)^%Ie [YU }>(<Gcs2a#U[^dl~>6ZV7DwuBhdvJNL>(j:X9EFKLvQ' );
define( 'WP_CACHE_KEY_SALT', 'mAsDe*)1D&RL k]EPqEx1TlpCo5l-@vt%&1}1]0^eYDSg:Z-JCGUR-M5t:VC_Az9' );


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
