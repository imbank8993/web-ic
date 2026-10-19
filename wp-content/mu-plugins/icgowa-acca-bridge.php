<?php
/**
 * Plugin Name: ICGowa — ACCA Single Sign-On (SSO) Bridge
 * Description: Jembatan Single Sign-On (SSO) resmi untuk menghubungkan portal ACCA (acca.icgowa.sch.id) ke WordPress MAN Insan Cendekia Gowa. Mengizinkan guru dan staf masuk ke dashboard WordPress secara otomatis tanpa mengetik password manual.
 * Author: ICGowa & ACCA Developer
 * Version: 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Secret key default untuk autentikasi antara backend NestJS dan WordPress.
if ( ! defined( 'ACCA_BRIDGE_SECRET' ) ) {
	$env_secret = getenv( 'ACCA_BRIDGE_SECRET' );
	define( 'ACCA_BRIDGE_SECRET', $env_secret ? $env_secret : 'acca_sec_d83e20f49a15bc72901e4a68bf23dc58' );
}

class ICGowa_ACCA_SSO {

	const NAMESPACE = 'acca/v1';

	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
		add_action( 'init', array( __CLASS__, 'handle_sso_auto_login' ) );

		// Header CORS khusus domain resmi ACCA
		add_action( 'rest_api_init', function () {
			$origin  = isset( $_SERVER['HTTP_ORIGIN'] ) ? $_SERVER['HTTP_ORIGIN'] : '';
			$allowed = array( 'https://acca.icgowa.sch.id', 'http://localhost:3000', 'http://localhost:3001' );
			if ( in_array( $origin, $allowed, true ) ) {
				header( 'Access-Control-Allow-Origin: ' . $origin );
				header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
				header( 'Access-Control-Allow-Headers: Authorization, Content-Type, X-ACCA-Secret' );
			}
		} );
	}

	/**
	 * Daftarkan route REST API untuk SSO
	 */
	public static function register_routes() {
		// 1. Health Check
		register_rest_route( self::NAMESPACE, '/health', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'handle_health_check' ),
			'permission_callback' => array( __CLASS__, 'check_permission' ),
		) );

		// 2. Generate One-Time SSO Magic Link
		register_rest_route( self::NAMESPACE, '/sso/generate-link', array(
			'methods'             => 'POST',
			'callback'            => array( __CLASS__, 'handle_generate_sso_link' ),
			'permission_callback' => array( __CLASS__, 'check_permission' ),
		) );
	}

	/**
	 * Validasi Secret Key dari Header request
	 */
	public static function check_permission( WP_REST_Request $request ) {
		$secret = $request->get_header( 'X-ACCA-Secret' );

		if ( empty( $secret ) ) {
			$auth = $request->get_header( 'Authorization' );
			if ( $auth && preg_match( '/Bearer\s+(.*)$/i', $auth, $matches ) ) {
				$secret = trim( $matches[1] );
			}
		}

		if ( empty( $secret ) ) {
			return new WP_Error(
				'acca_unauthorized',
				'Header X-ACCA-Secret atau Authorization Bearer wajib disertakan.',
				array( 'status' => 401 )
			);
		}

		if ( ! hash_equals( ACCA_BRIDGE_SECRET, $secret ) ) {
			return new WP_Error(
				'acca_forbidden',
				'Secret Key ACCA tidak valid.',
				array( 'status' => 403 )
			);
		}

		return true;
	}

	/**
	 * GET /wp-json/acca/v1/health
	 */
	public static function handle_health_check() {
		return rest_ensure_response( array(
			'status'    => 'ok',
			'service'   => 'ACCA Single Sign-On (SSO)',
			'version'   => '2.0.0',
			'timestamp' => current_time( 'mysql' ),
			'wp_url'    => home_url(),
		) );
	}

	/**
	 * Daftar role ACCA yang diizinkan masuk ke WordPress dan pemetaannya.
	 * Role yang TIDAK ada di sini (misal: 'siswa', 'alumni', 'orang_tua')
	 * AKAN DITOLAK otomatis demi keamanan.
	 */
	public static function get_allowed_role_mapping() {
		$default_mapping = array(
			// Role di ACCA   => Role di WordPress
			'superadmin' => 'administrator',
			'admin'      => 'administrator',
			'humas'      => 'editor',
			'editor'     => 'editor',
			'guru'       => 'author',       // Dapat menulis, mengedit, mempublikasikan artikel sendiri
			'pembina'    => 'author',
			'staf'       => 'author',
		);

		return apply_filters( 'acca_sso_role_mapping', $default_mapping );
	}

	/**
	 * POST /wp-json/acca/v1/sso/generate-link
	 * Menerima permintaan dari backend NestJS ACCA untuk membuat One-Time Magic Login Link.
	 */
	public static function handle_generate_sso_link( WP_REST_Request $request ) {
		$email       = sanitize_email( $request->get_param( 'email' ) );
		$name        = sanitize_text_field( $request->get_param( 'name' ) ?: '' );
		$role_input  = strtolower( trim( sanitize_text_field( $request->get_param( 'role' ) ?: '' ) ) );
		$redirect_to = esc_url_raw( $request->get_param( 'redirect_to' ) ?: admin_url() );

		if ( empty( $email ) || ! is_email( $email ) ) {
			return new WP_Error( 'acca_invalid_email', 'Parameter "email" wajib berupa alamat email yang valid.', array( 'status' => 400 ) );
		}

		// 1. Validasi Role yang Diizinkan
		$role_mapping = self::get_allowed_role_mapping();

		if ( ! isset( $role_mapping[ $role_input ] ) ) {
			$allowed_list = implode( ', ', array_keys( $role_mapping ) );
			return new WP_Error(
				'acca_role_not_allowed',
				"Akses Ditolak: Role '{$role_input}' tidak diizinkan masuk ke WordPress. Hanya role berikut yang diperbolehkan: {$allowed_list}.",
				array( 'status' => 403 )
			);
		}

		$wp_role = $role_mapping[ $role_input ];

		// 2. Cek apakah user sudah ada di WordPress, jika belum -> Otomatis Buat Akun Baru (Auto-Provisioning)
		$user = get_user_by( 'email', $email );

		if ( ! $user ) {
			$username_base = sanitize_user( current( explode( '@', $email ) ), true );
			$username      = ! empty( $username_base ) ? $username_base : 'acca_user_' . wp_rand( 1000, 9999 );

			if ( username_exists( $username ) ) {
				$username .= '_' . wp_rand( 100, 999 );
			}

			$user_id = wp_insert_user( array(
				'user_login'   => $username,
				'user_email'   => $email,
				'display_name' => ! empty( $name ) ? $name : $username,
				'first_name'   => $name,
				'user_pass'    => wp_generate_password( 24, true, true ),
				'role'         => $wp_role,
			) );

			if ( is_wp_error( $user_id ) ) {
				return new WP_Error( 'acca_user_creation_failed', 'Gagal membuat user WordPress: ' . $user_id->get_error_message(), array( 'status' => 500 ) );
			}
			$user = get_user_by( 'id', $user_id );
		} else {
			$user_id     = $user->ID;
			$update_data = array( 'ID' => $user_id );

			if ( ! empty( $name ) && $user->display_name !== $name ) {
				$update_data['display_name'] = $name;
			}
			if ( ! in_array( $wp_role, (array) $user->roles, true ) ) {
				$update_data['role'] = $wp_role;
			}
			if ( count( $update_data ) > 1 ) {
				wp_update_user( $update_data );
			}
		}

		// 3. Buat One-Time Token yang Aman (Berlaku 60 Detik)
		$token         = bin2hex( random_bytes( 32 ) );
		$transient_key = 'acca_sso_' . $token;

		set_transient( $transient_key, array(
			'user_id'     => $user_id,
			'redirect_to' => $redirect_to,
			'created_at'  => time(),
		), 60 );

		$sso_login_url = add_query_arg( 'acca_sso', $token, home_url( '/' ) );

		return rest_ensure_response( array(
			'success'     => true,
			'message'     => 'Token SSO berhasil dibuat.',
			'sso_url'     => $sso_login_url,
			'expires_in'  => 60,
			'wp_user_id'  => $user_id,
			'wp_username' => $user->user_login,
			'wp_role'     => $wp_role,
		) );
	}

	/**
	 * Handler Auto-Login saat browser mengakses link SSO: https://icgowa.sch.id/?acca_sso=<TOKEN>
	 */
	public static function handle_sso_auto_login() {
		if ( ! isset( $_GET['acca_sso'] ) || empty( $_GET['acca_sso'] ) ) {
			return;
		}

		$token         = sanitize_text_field( $_GET['acca_sso'] );
		$transient_key = 'acca_sso_' . $token;
		$data          = get_transient( $transient_key );

		// Hapus token seketika (Single use token)
		delete_transient( $transient_key );

		if ( ! $data || empty( $data['user_id'] ) ) {
			wp_die(
				'<div style="max-width:480px;margin:60px auto;padding:32px 28px;background:#ffffff;border:1px solid #E2E8F0;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.06);font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif;text-align:center;">
					<div style="width:54px;height:54px;line-height:54px;background:#FEE2E2;color:#DC2626;border-radius:50%;font-size:26px;margin:0 auto 16px;">&times;</div>
					<h2 style="color:#0F172A;font-size:1.25rem;margin:0 0 8px;font-weight:700;">Tautan SSO Tidak Valid</h2>
					<p style="color:#64748B;font-size:0.9375rem;line-height:1.6;margin:0 0 24px;">Tautan login sekali pakai ini sudah pernah digunakan atau telah kadaluarsa (melebihi batas waktu 60 detik).</p>
					<a href="https://acca.icgowa.sch.id" style="display:inline-block;padding:12px 24px;background:#047857;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;font-size:0.9375rem;">Kembali ke Portal ACCA</a>
				</div>',
				'SSO Login Kadaluarsa',
				array( 'response' => 403 )
			);
		}

		$user = get_user_by( 'id', (int) $data['user_id'] );
		if ( ! $user ) {
			wp_die( 'User tidak ditemukan di sistem.', 'Error', array( 'response' => 404 ) );
		}

		// Otentikasi dan set Cookie Login WordPress
		wp_clear_auth_cookie();
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID, true, is_ssl() );
		do_action( 'wp_login', $user->user_login, $user );

		// Redirect ke Dashboard WP-Admin
		$redirect_url = ! empty( $data['redirect_to'] ) ? esc_url_raw( $data['redirect_to'] ) : admin_url();
		wp_safe_redirect( $redirect_url );
		exit;
	}
}

ICGowa_ACCA_SSO::init();
