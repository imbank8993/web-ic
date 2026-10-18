<?php
/**
 * Plugin Name: ICGowa — HTTPS & Mixed Content Fixer
 * Description: Memastikan seluruh aset CSS, JS, gambar, dan cache Elementor pada domain icgowa.sch.id disajikan melalui HTTPS tanpa error Mixed Content di balik Reverse Proxy.
 * Author: ICGowa
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cek apakah request saat ini berjalan untuk domain produksi icgowa.sch.id
 */
function icgowa_is_production_https() {
	if ( isset( $_SERVER['HTTP_HOST'] ) && strpos( $_SERVER['HTTP_HOST'], 'icgowa.test' ) !== false ) {
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
 * Ubah URL http://icgowa.sch.id menjadi https://icgowa.sch.id pada string/konten
 */
function icgowa_force_https_urls( $content ) {
	if ( ! is_string( $content ) || empty( $content ) || ! icgowa_is_production_https() ) {
		return $content;
	}
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
	return $content;
}

// Filter aset & media WordPress
add_filter( 'script_loader_src', 'icgowa_force_https_urls', 999 );
add_filter( 'style_loader_src', 'icgowa_force_https_urls', 999 );
add_filter( 'wp_get_attachment_url', 'icgowa_force_https_urls', 999 );
add_filter( 'the_content', 'icgowa_force_https_urls', 999 );
add_filter( 'elementor/frontend/the_content', 'icgowa_force_https_urls', 999 );
add_filter( 'widget_text', 'icgowa_force_https_urls', 999 );

add_filter( 'wp_calculate_image_srcset', function ( $sources ) {
	if ( ! is_array( $sources ) || ! icgowa_is_production_https() ) {
		return $sources;
	}
	foreach ( $sources as &$source ) {
		if ( isset( $source['url'] ) ) {
			$source['url'] = icgowa_force_https_urls( $source['url'] );
		}
	}
	return $sources;
}, 999 );

/**
 * Sinkronisasi opsi siteurl & home di database serta regenerasi CSS Elementor
 */
add_action( 'init', function () {
	if ( ! icgowa_is_production_https() ) {
		return;
	}

	global $wpdb;
	$migrated = get_option( 'icgowa_https_migrated_v2' );
	if ( ! $migrated ) {
		// Pastikan nilai siteurl dan home di tabel options menggunakan https://icgowa.sch.id
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->options} SET option_value = %s WHERE option_name IN ('siteurl', 'home') AND option_value LIKE %s",
				'https://icgowa.sch.id',
				'http://icgowa.sch.id%'
			)
		);
		wp_cache_delete( 'alloptions', 'options' );

		// Bersihkan cache CSS Elementor agar di-generate ulang dengan HTTPS
		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		update_option( 'icgowa_https_migrated_v2', time(), true );
	}
}, 20 );

/**
 * Output buffer untuk memastikan tidak ada Mixed Content di seluruh output HTML
 */
add_action( 'template_redirect', function () {
	if ( ! icgowa_is_production_https() || is_admin() ) {
		return;
	}
	ob_start( 'icgowa_force_https_urls' );
}, 1 );
