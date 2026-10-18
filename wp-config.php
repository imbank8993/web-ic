<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') ?: 'sql_icgowa' );

/** Database username */
define( 'DB_USER', getenv('WORDPRESS_DB_USER') ?: 'sql_icgowa' );

/** Database password */
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') !== false ? getenv('WORDPRESS_DB_PASSWORD') : '73HEMczxpLFrR4AY' );

/** Database hostname */
define( 'DB_HOST', getenv('WORDPRESS_DB_HOST') ?: '194.233.81.192:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/** Support for Reverse Proxy SSL (aaPanel / Nginx / Cloudflare) */
if (
	( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && strpos( $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https' ) !== false ) ||
	( isset( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on' ) ||
	( isset( $_SERVER['HTTP_CF_VISITOR'] ) && strpos( $_SERVER['HTTP_CF_VISITOR'], 'https' ) !== false ) ||
	( isset( $_SERVER['HTTP_HOST'] ) && strpos( $_SERVER['HTTP_HOST'], 'icgowa.sch.id' ) !== false )
) {
	$_SERVER['HTTPS'] = 'on';
	$_SERVER['SERVER_PORT'] = 443;
}

/** Konfigurasi URL Lokal / Server */
if ( isset( $_SERVER['HTTP_HOST'] ) && strpos( $_SERVER['HTTP_HOST'], 'icgowa.test' ) !== false ) {
	define( 'WP_HOME', 'http://icgowa.test' );
	define( 'WP_SITEURL', 'http://icgowa.test' );
} elseif ( isset( $_SERVER['HTTP_HOST'] ) && strpos( $_SERVER['HTTP_HOST'], 'icgowa.sch.id' ) !== false ) {
	define( 'WP_HOME', 'https://icgowa.sch.id' );
	define( 'WP_SITEURL', 'https://icgowa.sch.id' );
} elseif ( getenv('WP_HOME') ) {
	define( 'WP_HOME', getenv('WP_HOME') );
	define( 'WP_SITEURL', getenv('WP_SITEURL') ?: getenv('WP_HOME') );
	if ( strpos( getenv('WP_HOME'), 'https://' ) === 0 ) {
		$_SERVER['HTTPS'] = 'on';
		$_SERVER['SERVER_PORT'] = 443;
	}
} elseif ( defined('WP_CLI') ) {
	define( 'WP_HOME', 'https://icgowa.sch.id' );
	define( 'WP_SITEURL', 'https://icgowa.sch.id' );
}

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
define( 'AUTH_KEY',         'lbbsshda3sdthouegqmawyhpgntxhs3uauc9yimadkezh4hfwrwc0hau0e6pymcf' );
define( 'SECURE_AUTH_KEY',  '4a4y2fq6sm8674jw9lpuqquiyu43ihvaznmpauummrs9hys16c3cymjmwsitlgac' );
define( 'LOGGED_IN_KEY',    'lmfydvfu3hanjkq76jym0jetafdyvrs17so7tftdadigdxyzqmypuy9vfb24ijag' );
define( 'NONCE_KEY',        'jvirj8ltumldt6ly0hnwodooyh57cni70ucjkncjq4m0rvjy5fspwexnr9uot8ux' );
define( 'AUTH_SALT',        '2vpbi5i2t18b3w6ks6zbhasydrq0mpzkvwt5l4idrnwrcj6iwxkyqocgzu49x9en' );
define( 'SECURE_AUTH_SALT', '1id6vsjm06tug9lc4ufhcmsrzyksl0iuprqkstgu8ytvd6cai7n114exgvgcfe1f' );
define( 'LOGGED_IN_SALT',   'ghgvbau8bssaaqirx7vgztfcnz8kkozlbgrjgloanrtxq38ifhpn2xuitp9rdxix' );
define( 'NONCE_SALT',       'h4auqfu41wck22vmr8briuc25imvdwjiaxlpp9xcimtijqokwj4alloqlso3yfk0' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wphv_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
