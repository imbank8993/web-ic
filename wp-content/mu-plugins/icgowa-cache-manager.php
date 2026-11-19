<?php
/**
 * Plugin Name: ICGowa — Cache Invalidation & Performance Manager
 * Description: Otomatis membersihkan static page cache saat konten/Elementor diperbarui, serta menstabilkan frekuensi Heartbeat API.
 * Author: ICGowa
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hapus seluruh file static page cache
 */
function icgowa_purge_page_cache() {
	$cache_dir = WP_CONTENT_DIR . '/cache/page';
	if ( ! is_dir( $cache_dir ) ) {
		return;
	}
	$files = glob( $cache_dir . '/*.html' );
	if ( is_array( $files ) ) {
		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				@unlink( $file );
			}
		}
	}
}

// 1. Bersihkan cache otomatis saat postingan/halaman/menu berubah
add_action( 'save_post', 'icgowa_purge_page_cache' );
add_action( 'deleted_post', 'icgowa_purge_page_cache' );
add_action( 'switch_theme', 'icgowa_purge_page_cache' );
add_action( 'wp_update_nav_menu', 'icgowa_purge_page_cache' );

// 2. Bersihkan cache saat Elementor menyimpan perubahan
add_action( 'elementor/core/files/clear_cache', 'icgowa_purge_page_cache' );
add_action( 'elementor/editor/after_save', 'icgowa_purge_page_cache' );

// 3. Throttle WordPress Heartbeat API ke 60 detik (menghemat CPU hingga 75% dari tab admin yang terbuka)
add_filter( 'heartbeat_settings', function ( $settings ) {
	$settings['interval'] = 60;
	return $settings;
} );

// 4. Tombol "Bersihkan Cache Web" di Admin Bar atas
add_action( 'admin_bar_menu', function ( $wp_admin_bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$wp_admin_bar->add_node( array(
		'id'    => 'icgowa_clear_cache',
		'title' => '⚡ Bersihkan Cache Web',
		'href'  => wp_nonce_url( admin_url( 'admin-post.php?action=icgowa_clear_cache' ), 'icgowa_clear_cache_nonce' ),
	) );
}, 100 );

add_action( 'admin_post_icgowa_clear_cache', function () {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'icgowa_clear_cache_nonce' ) ) {
		wp_die( 'Akses tidak diizinkan.' );
	}
	icgowa_purge_page_cache();
	wp_safe_redirect( add_query_arg( 'cache_cleared', '1', wp_get_referer() ?: admin_url() ) );
	exit;
} );

// Notifikasi sukses setelah membersihkan cache
add_action( 'admin_notices', function () {
	if ( isset( $_GET['cache_cleared'] ) && '1' === $_GET['cache_cleared'] ) {
		echo '<div class="notice notice-success is-dismissible"><p><strong>Cache Web Berhasil Dibersihkan!</strong> Seluruh halaman kini dimuat ulang langsung.</p></div>';
	}
} );
