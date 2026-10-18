<?php
/**
 * Plugin Name: ICGowa — HTTPS & Local Development URL Fixer
 * Description: Memastikan seluruh aset CSS, JS, gambar, dan cache Elementor pada domain icgowa.sch.id disajikan melalui HTTPS, serta mendukung preview lokal (localhost / icgowa.test) tanpa merusak URL produksi di database.
 * Author: ICGowa
 * Version: 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cek apakah request saat ini berjalan di environment lokal (localhost / icgowa.test)
 */
function icgowa_is_local_dev() {
	if ( ! isset( $_SERVER['HTTP_HOST'] ) ) {
		return false;
	}
	$host = $_SERVER['HTTP_HOST'];
	return (
		strpos( $host, 'icgowa.test' ) !== false ||
		strpos( $host, 'localhost' ) !== false ||
		strpos( $host, '127.0.0.1' ) !== false
	);
}

/**
 * Cek apakah request saat ini berjalan untuk domain produksi icgowa.sch.id
 */
function icgowa_is_production_https() {
	if ( icgowa_is_local_dev() ) {
		return false;
	}
	if ( isset( $_SERVER['HTTP_HOST'] ) && strpos( $_SERVER['HTTP_HOST'], 'icgowa.sch.id' ) !== false ) {
		return true;
	}
	if ( defined( 'WP_HOME' ) && strpos( WP_HOME, 'https://icgowa.sch.id' ) === 0 ) {
		return true;
	}
	return is_ssl();
}

/**
 * Sesuaikan URL aset dan konten sesuai environment (Lokal vs Produksi HTTPS)
 */
function icgowa_rewrite_environment_urls( $content ) {
	if ( ! is_string( $content ) || empty( $content ) ) {
		return $content;
	}

	if ( icgowa_is_local_dev() ) {
		$scheme     = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ) ? 'https://' : 'http://';
		$local_base = $scheme . $_SERVER['HTTP_HOST'];
		$local_json = str_replace( '/', '\/', $local_base );

		$content = str_replace(
			array(
				'https://icgowa.sch.id',
				'http://icgowa.sch.id',
				'https:\/\/icgowa.sch.id',
				'http:\/\/icgowa.sch.id',
			),
			array(
				$local_base,
				$local_base,
				$local_json,
				$local_json,
			),
			$content
		);
		return $content;
	}

	if ( icgowa_is_production_https() ) {
		$content = str_replace(
			array(
				'http://icgowa.sch.id',
				'http:\/\/icgowa.sch.id',
				'http%3A%2F%2Ficgowa.sch.id',
			),
			array(
				'https://icgowa.sch.id',
				'https:\/\/icgowa.sch.id',
				'https%3A%2F%2Ficgowa.sch.id',
			),
			$content
		);
	}

	return $content;
}

// Filter aset & media WordPress
add_filter( 'script_loader_src', 'icgowa_rewrite_environment_urls', 999 );
add_filter( 'style_loader_src', 'icgowa_rewrite_environment_urls', 999 );
add_filter( 'wp_get_attachment_url', 'icgowa_rewrite_environment_urls', 999 );
add_filter( 'the_content', 'icgowa_rewrite_environment_urls', 999 );
add_filter( 'elementor/frontend/the_content', 'icgowa_rewrite_environment_urls', 999 );
add_filter( 'widget_text', 'icgowa_rewrite_environment_urls', 999 );

add_filter( 'wp_calculate_image_srcset', function ( $sources ) {
	if ( ! is_array( $sources ) ) {
		return $sources;
	}
	foreach ( $sources as &$source ) {
		if ( isset( $source['url'] ) ) {
			$source['url'] = icgowa_rewrite_environment_urls( $source['url'] );
		}
	}
	return $sources;
}, 999 );

/**
 * Sinkronisasi opsi siteurl & home di database serta regenerasi CSS Elementor (hanya di produksi)
 */
add_action( 'init', function () {
	if ( ! icgowa_is_production_https() ) {
		return;
	}

	global $wpdb;
	$migrated = get_option( 'icgowa_https_migrated_v2' );
	if ( ! $migrated ) {
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->options} SET option_value = %s WHERE option_name IN ('siteurl', 'home') AND option_value LIKE %s",
				'https://icgowa.sch.id',
				'http://icgowa.sch.id%'
			)
		);
		wp_cache_delete( 'alloptions', 'options' );

		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		update_option( 'icgowa_https_migrated_v2', time(), true );
	}
}, 20 );

/**
 * Output buffer untuk memastikan URL di HTML sesuai environment (Lokal atau Produksi HTTPS)
 */
add_action( 'template_redirect', function () {
	if ( is_admin() ) {
		return;
	}
	if ( icgowa_is_local_dev() || icgowa_is_production_https() ) {
		ob_start( 'icgowa_rewrite_environment_urls' );
	}
}, 1 );
