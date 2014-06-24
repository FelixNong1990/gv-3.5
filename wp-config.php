<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gv-3.5' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '6%A]0{f#u}|rk8 4a0U>U?.%-|m;n%s:`Vt<0w#-)`CB<)wy^wphFE.(g7@+/~a0');
define('SECURE_AUTH_KEY',  '|vH;/pHK$GS (R*euj);~-BNIW/?py5Xf}#xN64E0SF(WN*%&M5a82&f8?EX:V.L');
define('LOGGED_IN_KEY',    'SSDXLmrdT9Jv+WU-h&lr51(-KF- WRzrv-NRc:#g7/_R O![IQ n{lEET>fs4_qU');
define('NONCE_KEY',        'V`K=O?Da[urLv9{{pq]0?RHO+`Wj|k f;w|VM43_~f#|kz`~TtuI[]-.i|YJ]qkU');
define('AUTH_SALT',        'yQW12E*=s49?7!4}xX>5{_X$U(TNPU3BsXn!P&Ubz^G.arIvh^+lz?5~-]lnd%cG');
define('SECURE_AUTH_SALT', 'vYP<?]*#%%n<9}w)M:%4w/nNcP&PyQL|^,v/bIYBp!K4#mH+Jp}6-.o+W{{g8oZv');
define('LOGGED_IN_SALT',   'LJtaD;-6d)gTS]-qOF6(s94CLA/L7bOQU08<o;*B62 @C~h9<i#z8/9*<Gq86S|h');
define('NONCE_SALT',       '|7,5s#~tk]K5s{b{J02|_:)ep5K.wy`:DgB`7c4*M<%O&$kv&~=W+t#0lSm)xP!*');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'gv_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
