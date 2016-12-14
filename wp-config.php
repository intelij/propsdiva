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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'aptos_wppropsdiva');

/** MySQL database username */
define('DB_USER', 'aptos_caesar');

/** MySQL database password */
define('DB_PASSWORD', 'caesar123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'ofUx!#5>ab{@<i:`mYWDvpj6.=H`yDanRYd:s/Q|4[-dG~jvX,0oP1E&WqM7*P[&');
define('SECURE_AUTH_KEY',  'E4Z5C#|^E9d2],6.J!0VJk~.:5cN{S&nm(zx:zeyE4W:k:sm]^?IFN8nYe@g*9M&');
define('LOGGED_IN_KEY',    '%I3d~?80*+>lA,d.t)B?>=S*l9-.2M:TwzUn;DGibL@a:iT|F=@=lZuC:PZy?5bV');
define('NONCE_KEY',        '!=;?R94.hD1~0!,`Tm<| 0?C4g{$iJwO-6z;!BM>q#?cx)DwPSb+_-y1OXTOYiH6');
define('AUTH_SALT',        'X<h6lK[c*M{q)}wLb(`qyi3+H({+*9vw75scU[_M*Y*%Q^>e^NUH2FU*Z0Ja ef#');
define('SECURE_AUTH_SALT', 'h9gGk`1nazyRp2P!<R6wL,gQa3bcQZ&;(CVIWj+vb?J_*C>8l{7Il2&}[fR2^;Sm');
define('LOGGED_IN_SALT',   '!YE[J7W{Ll/(BP,J3(+cKz-wNQ 3/[&sTx48L<1CPcj*Ige~o=y}(?|`6;n2!x5,');
define('NONCE_SALT',       'y:3u}*/o]k(.fQy.i*|jw?8kS(w|O#Z0zWRztHY:2XW?(sAS.^>$$Z!Th;@nolji');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
