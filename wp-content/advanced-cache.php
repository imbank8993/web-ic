<?php
/**
 * ICGowa High-Performance Static Page Cache Drop-In
 * Serves cached HTML instantly before WordPress database/plugins load.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Only cache GET requests
if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
	return;
}

// 2. Do not cache admin, login, cron, API, SSO or queries with nocache
$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
if (
	strpos( $req_uri, '/wp-admin' ) !== false ||
	strpos( $req_uri, 'wp-login.php' ) !== false ||
	strpos( $req_uri, 'wp-cron.php' ) !== false ||
	strpos( $req_uri, 'wp-json' ) !== false ||
	strpos( $req_uri, 'admin-ajax.php' ) !== false ||
	strpos( $req_uri, 'preview=true' ) !== false ||
	strpos( $req_uri, 'acca_sso=' ) !== false ||
	strpos( $req_uri, 'acca_single_logout' ) !== false ||
	isset( $_GET['nocache'] )
) {
	return;
}

// 3. Do not cache for logged-in WordPress users or comment authors
if ( ! empty( $_COOKIE ) ) {
	foreach ( $_COOKIE as $key => $val ) {
		if (
			strpos( $key, 'wordpress_logged_in_' ) === 0 ||
			strpos( $key, 'comment_author_' ) === 0 ||
			strpos( $key, 'elementor_safe_mode' ) === 0
		) {
			return;
		}
	}
}

// 4. Resolve Cache File Path
$cache_dir = WP_CONTENT_DIR . '/cache/page';
$host      = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( $_SERVER['HTTP_HOST'] ) : 'icgowa.sch.id';
$hash      = md5( $host . '|' . $req_uri );
$cache_file = $cache_dir . '/page_' . $hash . '.html';

// 5. Check if cached file exists and is younger than 2 hours (7200 seconds)
if ( file_exists( $cache_file ) ) {
	$age = time() - @filemtime( $cache_file );
	if ( $age < 7200 && filesize( $cache_file ) > 1000 ) {
		header( 'Content-Type: text/html; charset=UTF-8' );
		header( 'X-ICGowa-Cache: HIT (Age: ' . $age . 's)' );
		header( 'Cache-Control: public, max-age=600' );
		readfile( $cache_file );
		exit;
	}
}

// 6. Cache MISS -> Setup Output Buffer to store rendered page
ob_start( function ( $buffer ) use ( $cache_dir, $cache_file ) {
	// Only cache successful HTML responses
	if (
		http_response_code() === 200 &&
		! is_404() &&
		strlen( $buffer ) > 1500 &&
		stripos( $buffer, '<html' ) !== false &&
		stripos( $buffer, '</html>' ) !== false
	) {
		if ( ! is_dir( $cache_dir ) ) {
			@mkdir( $cache_dir, 0775, true );
		}
		$tagged_buffer = $buffer . "\n<!-- ICGowa Page Cache generated at " . gmdate( 'Y-m-d H:i:s' ) . " GMT -->";
		@file_put_contents( $cache_file, $tagged_buffer );
		header( 'X-ICGowa-Cache: MISS (Cached for subsequent requests)' );
		return $tagged_buffer;
	}
	return $buffer;
} );
