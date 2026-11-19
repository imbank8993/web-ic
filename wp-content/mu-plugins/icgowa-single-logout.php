<?php
/**
 * Plugin Name: ICGowa — ACCA Single Logout
 * Description: Menutup sesi WordPress pada browser saat pengguna logout dari portal ACCA.
 * Author: ICGowa & ACCA Developer
 * Version: 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'icgowa_single_logout_origin_of' ) ) {
	function icgowa_single_logout_origin_of( $url ) {
		$parts = wp_parse_url( $url );
		if ( ! is_array( $parts ) || empty( $parts['scheme'] ) || empty( $parts['host'] ) ) {
			return '';
		}
		$origin = strtolower( $parts['scheme'] . '://' . $parts['host'] );
		if ( isset( $parts['port'] ) ) {
			$origin .= ':' . $parts['port'];
		}
		return $origin;
	}
}

if ( ! function_exists( 'icgowa_single_logout_get_allowed_origins' ) ) {
	function icgowa_single_logout_get_allowed_origins() {
		$allowed = array(
			'https://acca.icgowa.sch.id',
			'http://localhost:3000',
			'http://localhost:3001',
		);

		$home = icgowa_single_logout_origin_of( home_url( '/' ) );
		if ( $home ) {
			$allowed[] = $home;
		}

		$site = icgowa_single_logout_origin_of( site_url( '/' ) );
		if ( $site ) {
			$allowed[] = $site;
		}

		if ( defined( 'ACCA_SINGLE_LOGOUT_ORIGIN' ) && ACCA_SINGLE_LOGOUT_ORIGIN ) {
			$custom = icgowa_single_logout_origin_of( ACCA_SINGLE_LOGOUT_ORIGIN );
			if ( $custom ) {
				$allowed[] = $custom;
			}
		}

		return array_unique( array_filter( $allowed ) );
	}
}

if ( ! function_exists( 'icgowa_handle_single_logout_request' ) ) {
	function icgowa_handle_single_logout_request() {
		$is_logout_request = false;

		if ( isset( $_GET['acca_single_logout'] ) && '1' === (string) $_GET['acca_single_logout'] ) {
			$is_logout_request = true;
		} elseif ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'acca_single_logout=1' ) !== false ) {
			$is_logout_request = true;
		}

		if ( ! $is_logout_request ) {
			return;
		}

		// Nonaktifkan segala bentuk caching
		nocache_headers();
		header( 'Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'Vary: Origin', false );

		$origin = isset( $_SERVER['HTTP_ORIGIN'] ) ? trim( wp_unslash( $_SERVER['HTTP_ORIGIN'] ) ) : '';
		$allowed_origins = icgowa_single_logout_get_allowed_origins();

		if ( ! empty( $origin ) ) {
			$origin_lower = strtolower( $origin );
			if ( ! in_array( $origin_lower, $allowed_origins, true ) ) {
				wp_send_json( array(
					'ok'    => false,
					'error' => 'Origin tidak diizinkan: ' . esc_html( $origin ),
				), 403 );
			}
			header( 'Access-Control-Allow-Origin: ' . $origin );
			header( 'Access-Control-Allow-Credentials: true' );
		} else {
			header( 'Access-Control-Allow-Origin: https://acca.icgowa.sch.id' );
			header( 'Access-Control-Allow-Credentials: true' );
		}

		header( 'Access-Control-Allow-Methods: POST, OPTIONS' );
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, X-ACCA-Secret' );

		// Handle Preflight OPTIONS
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
			status_header( 204 );
			exit;
		}

		// Validasi HTTP method
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			header( 'Allow: POST, OPTIONS' );
			wp_send_json( array(
				'ok'    => false,
				'error' => 'Metode HTTP tidak valid. Gunakan POST.',
			), 405 );
		}

		// Hapus sesi dan cookie otentikasi WordPress
		wp_logout();

		wp_send_json( array(
			'ok'      => true,
			'message' => 'Sesi WordPress berhasil ditutup.',
		) );
	}
}
add_action( 'init', 'icgowa_handle_single_logout_request', 1 );

if ( ! function_exists( 'icgowa_single_logout_render_listener' ) ) {
	function icgowa_single_logout_render_listener() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$endpoint = add_query_arg( 'acca_single_logout', '1', home_url( '/' ) );
		$login_url = 'https://acca.icgowa.sch.id/login?logged_out=1';

		?>
		<script>
		(function () {
			if (window.__accaLogoutListener) return;
			window.__accaLogoutListener = true;
			var read = function () {
				return document.cookie.split('; ').find(function (part) {
					return part.indexOf('acca_logout_at=') === 0;
				}) || '';
			};
			var previous = read(), closing = false;
			function check() {
				var current = read();
				if (!current || current === previous || closing) return;
				closing = true;
				var controller = new AbortController();
				var timer = setTimeout(function () { controller.abort(); }, 10000);
				fetch(<?php echo wp_json_encode( $endpoint, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>, {
					method: 'POST',
					credentials: 'include',
					cache: 'no-store',
					signal: controller.signal
				})
				.then(function (res) {
					if (!res.ok) throw new Error('Logout failed');
					window.location.replace(<?php echo wp_json_encode( $login_url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>);
				})
				.catch(function () {
					closing = false;
				})
				.finally(function () {
					clearTimeout(timer);
				});
			}
			setInterval(check, 2500);
			window.addEventListener('focus', check);
			document.addEventListener('visibilitychange', check);
		})();
		</script>
		<?php
	}
}
add_action( 'wp_head', 'icgowa_single_logout_render_listener' );
add_action( 'admin_head', 'icgowa_single_logout_render_listener' );
