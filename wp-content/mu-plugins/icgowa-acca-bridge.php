<?php
/**
 * Plugin Name: ICGowa — ACCA Bridge REST API
 * Description: Jembatan REST API aman untuk integrasi sistem ACCA (NestJS & Next.js) dengan WordPress MAN Insan Cendekia Gowa. Mengizinkan publikasi dan pengelolaan konten tanpa memerlukan akun WordPress per guru.
 * Author: ICGowa & ACCA Developer
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Secret key default untuk autentikasi antara NestJS dan WordPress.
// Dapat di-override melalui wp-config.php define('ACCA_BRIDGE_SECRET', '...') atau env WP.
if ( ! defined( 'ACCA_BRIDGE_SECRET' ) ) {
	$env_secret = getenv( 'ACCA_BRIDGE_SECRET' );
	define( 'ACCA_BRIDGE_SECRET', $env_secret ? $env_secret : 'acca_sec_d83e20f49a15bc72901e4a68bf23dc58' );
}

class ICGowa_ACCA_Bridge {

	const NAMESPACE = 'acca/v1';

	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
		add_filter( 'the_author', array( __CLASS__, 'filter_the_author' ), 20, 1 );
		add_filter( 'get_the_author_display_name', array( __CLASS__, 'filter_the_author' ), 20, 2 );
		add_filter( 'the_content', array( __CLASS__, 'append_acca_author_badge' ), 20 );
	}

	/**
	 * Daftarkan semua route REST API untuk ACCA
	 */
	public static function register_routes() {
		// 1. Health Check
		register_rest_route( self::NAMESPACE, '/health', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'handle_health_check' ),
			'permission_callback' => array( __CLASS__, 'check_permission' ),
		) );

		// 2. Daftar Kategori
		register_rest_route( self::NAMESPACE, '/categories', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'handle_get_categories' ),
			'permission_callback' => array( __CLASS__, 'check_permission' ),
		) );

		// 3. Kelola Postingan (List & Create)
		register_rest_route( self::NAMESPACE, '/posts', array(
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'handle_get_posts' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'handle_create_post' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
		) );

		// 4. Update & Delete Postingan
		register_rest_route( self::NAMESPACE, '/posts/(?P<id>\d+)', array(
			array(
				'methods'             => array( 'POST', 'PUT', 'PATCH' ),
				'callback'            => array( __CLASS__, 'handle_update_post' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
			array(
				'methods'             => 'DELETE',
				'callback'            => array( __CLASS__, 'handle_delete_post' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
		) );

		// 5. Kelola Ekstrakurikuler (Ekskul)
		register_rest_route( self::NAMESPACE, '/ekskul', array(
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'handle_get_ekskul' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'handle_create_ekskul' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
		) );

		register_rest_route( self::NAMESPACE, '/ekskul/(?P<id>\d+)', array(
			array(
				'methods'             => array( 'POST', 'PUT', 'PATCH' ),
				'callback'            => array( __CLASS__, 'handle_update_ekskul' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
			array(
				'methods'             => 'DELETE',
				'callback'            => array( __CLASS__, 'handle_delete_ekskul' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
		) );

		// 6. Kelola Data Survey Layanan (Nilai Triwulan & Grafik)
		register_rest_route( self::NAMESPACE, '/survey', array(
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'handle_get_survey' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'handle_update_survey' ),
				'permission_callback' => array( __CLASS__, 'check_permission' ),
			),
		) );
	}


	/**
	 * Validasi Secret Key dari Header request
	 */
	public static function check_permission( WP_REST_Request $request ) {
		$secret = $request->get_header( 'X-ACCA-Secret' );

		// Dukung juga format Bearer Token jika disediakan
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
			'app'       => 'ACCA-ICGowa Bridge',
			'version'   => '1.0.0',
			'timestamp' => current_time( 'mysql' ),
			'wp_name'   => get_bloginfo( 'name' ),
			'wp_url'    => home_url(),
		) );
	}

	/**
	 * GET /wp-json/acca/v1/categories
	 */
	public static function handle_get_categories() {
		$categories = get_categories( array(
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		) );

		$data = array();
		foreach ( $categories as $cat ) {
			$data[] = array(
				'id'    => $cat->term_id,
				'name'  => $cat->name,
				'slug'  => $cat->slug,
				'count' => $cat->count,
			);
		}

		return rest_ensure_response( array(
			'success'    => true,
			'categories' => $data,
		) );
	}

	/**
	 * GET /wp-json/acca/v1/posts
	 */
	public static function handle_get_posts( WP_REST_Request $request ) {
		$page     = max( 1, (int) $request->get_param( 'page' ) );
		$per_page = min( 50, max( 1, (int) $request->get_param( 'per_page' ) ?: 10 ) );
		$status   = $request->get_param( 'status' ) ?: 'any';
		$search   = sanitize_text_field( $request->get_param( 'search' ) ?: '' );

		$args = array(
			'post_type'      => 'post',
			'post_status'    => $status,
			'posts_per_page' => $per_page,
			'paged'          => $page,
			's'              => $search,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$query = new WP_Query( $args );
		$posts = array();

		foreach ( $query->posts as $p ) {
			$thumb_id  = get_post_thumbnail_id( $p->ID );
			$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'full' ) : null;
			$cats      = wp_get_post_categories( $p->ID, array( 'fields' => 'names' ) );

			$posts[] = array(
				'id'                 => $p->ID,
				'title'              => html_entity_decode( $p->post_title, ENT_QUOTES, 'UTF-8' ),
				'slug'               => $p->post_name,
				'status'             => $p->post_status,
				'date'               => $p->post_date,
				'excerpt'            => get_the_excerpt( $p ),
				'categories'         => $cats,
				'featured_image_url' => $thumb_url,
				'permalink'          => get_permalink( $p->ID ),
				'author_name'        => get_post_meta( $p->ID, '_acca_author_name', true ) ?: get_the_author_meta( 'display_name', $p->post_author ),
				'author_role'        => get_post_meta( $p->ID, '_acca_author_role', true ) ?: '',
			);
		}

		return rest_ensure_response( array(
			'success'     => true,
			'total'       => (int) $query->found_posts,
			'total_pages' => (int) $query->max_num_pages,
			'page'        => $page,
			'per_page'    => $per_page,
			'posts'       => $posts,
		) );
	}

	/**
	 * POST /wp-json/acca/v1/posts
	 */
	public static function handle_create_post( WP_REST_Request $request ) {
		$title       = sanitize_text_field( $request->get_param( 'title' ) );
		$content     = $request->get_param( 'content' ); // HTML diperbolehkan
		$excerpt     = sanitize_textarea_field( $request->get_param( 'excerpt' ) ?: '' );
		$status      = in_array( $request->get_param( 'status' ), array( 'publish', 'draft', 'pending' ), true ) ? $request->get_param( 'status' ) : 'publish';
		$author_name = sanitize_text_field( $request->get_param( 'author_name' ) ?: 'Guru / Staf ACCA' );
		$author_role = sanitize_text_field( $request->get_param( 'author_role' ) ?: '' );
		$category    = $request->get_param( 'category' );
		$tags        = $request->get_param( 'tags' );
		$image_url   = esc_url_raw( $request->get_param( 'featured_image_url' ) ?: '' );

		if ( empty( $title ) ) {
			return new WP_Error( 'acca_missing_title', 'Parameter "title" wajib diisi.', array( 'status' => 400 ) );
		}

		// Cari user ID untuk post_author (prioritas admin_icgowa, ID: 2, atau fallback user ID pertama)
		$default_author_id = 2;
		if ( ! get_user_by( 'id', $default_author_id ) ) {
			$first_admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
			$default_author_id = ! empty( $first_admin ) ? $first_admin[0]->ID : 1;
		}

		$post_arr = array(
			'post_title'   => $title,
			'post_content' => wp_kses_post( $content ),
			'post_excerpt' => $excerpt,
			'post_status'  => $status,
			'post_author'  => $default_author_id,
			'post_type'    => 'post',
		);

		$post_id = wp_insert_post( $post_arr, true );

		if ( is_wp_error( $post_id ) ) {
			return new WP_Error( 'acca_post_error', $post_id->get_error_message(), array( 'status' => 500 ) );
		}

		// Simpan metadata penulis asli dari ACCA
		update_post_meta( $post_id, '_acca_created', 1 );
		update_post_meta( $post_id, '_acca_author_name', $author_name );
		if ( ! empty( $author_role ) ) {
			update_post_meta( $post_id, '_acca_author_role', $author_role );
		}

		// Assign Kategori
		if ( ! empty( $category ) ) {
			self::assign_categories( $post_id, $category );
		}

		// Assign Tags
		if ( ! empty( $tags ) ) {
			wp_set_post_tags( $post_id, $tags, false );
		}

		// Download & set Featured Image jika ada URL gambar
		if ( ! empty( $image_url ) ) {
			self::set_featured_image_from_url( $post_id, $image_url, $title );
		}

		return rest_ensure_response( array(
			'success'     => true,
			'message'     => 'Artikel berhasil disimpan ke WordPress.',
			'post_id'     => $post_id,
			'permalink'   => get_permalink( $post_id ),
			'status'      => $status,
			'author_name' => $author_name,
			'author_role' => $author_role,
		) );
	}

	/**
	 * PUT/PATCH /wp-json/acca/v1/posts/{id}
	 */
	public static function handle_update_post( WP_REST_Request $request ) {
		$post_id = (int) $request->get_param( 'id' );
		$post    = get_post( $post_id );

		if ( ! $post || $post->post_type !== 'post' ) {
			return new WP_Error( 'acca_not_found', 'Postingan tidak ditemukan.', array( 'status' => 404 ) );
		}

		$post_arr = array( 'ID' => $post_id );

		if ( $request->has_param( 'title' ) ) {
			$post_arr['post_title'] = sanitize_text_field( $request->get_param( 'title' ) );
		}
		if ( $request->has_param( 'content' ) ) {
			$post_arr['post_content'] = wp_kses_post( $request->get_param( 'content' ) );
		}
		if ( $request->has_param( 'excerpt' ) ) {
			$post_arr['post_excerpt'] = sanitize_textarea_field( $request->get_param( 'excerpt' ) );
		}
		if ( $request->has_param( 'status' ) ) {
			$status = $request->get_param( 'status' );
			if ( in_array( $status, array( 'publish', 'draft', 'pending' ), true ) ) {
				$post_arr['post_status'] = $status;
			}
		}

		$updated = wp_update_post( $post_arr, true );
		if ( is_wp_error( $updated ) ) {
			return new WP_Error( 'acca_update_error', $updated->get_error_message(), array( 'status' => 500 ) );
		}

		// Update author meta jika diberikan
		if ( $request->has_param( 'author_name' ) ) {
			update_post_meta( $post_id, '_acca_author_name', sanitize_text_field( $request->get_param( 'author_name' ) ) );
		}
		if ( $request->has_param( 'author_role' ) ) {
			update_post_meta( $post_id, '_acca_author_role', sanitize_text_field( $request->get_param( 'author_role' ) ) );
		}

		if ( $request->has_param( 'category' ) ) {
			self::assign_categories( $post_id, $request->get_param( 'category' ) );
		}

		if ( $request->has_param( 'tags' ) ) {
			wp_set_post_tags( $post_id, $request->get_param( 'tags' ), false );
		}

		if ( $request->has_param( 'featured_image_url' ) ) {
			$image_url = esc_url_raw( $request->get_param( 'featured_image_url' ) );
			if ( ! empty( $image_url ) ) {
				self::set_featured_image_from_url( $post_id, $image_url, get_the_title( $post_id ) );
			}
		}

		return rest_ensure_response( array(
			'success'   => true,
			'message'   => 'Artikel berhasil diperbarui.',
			'post_id'   => $post_id,
			'permalink' => get_permalink( $post_id ),
		) );
	}

	/**
	 * DELETE /wp-json/acca/v1/posts/{id}
	 */
	public static function handle_delete_post( WP_REST_Request $request ) {
		$post_id = (int) $request->get_param( 'id' );
		$force   = (bool) $request->get_param( 'force' );

		$post = get_post( $post_id );
		if ( ! $post ) {
			return new WP_Error( 'acca_not_found', 'Postingan tidak ditemukan.', array( 'status' => 404 ) );
		}

		$deleted = wp_delete_post( $post_id, $force );
		if ( ! $deleted ) {
			return new WP_Error( 'acca_delete_error', 'Gagal menghapus postingan.', array( 'status' => 500 ) );
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => $force ? 'Postingan berhasil dihapus permanen.' : 'Postingan berhasil dipindahkan ke tempat sampah (trash).',
			'post_id' => $post_id,
		) );
	}

	/**
	 * Helper untuk memasang kategori berdasarkan Nama atau ID
	 */
	private static function assign_categories( $post_id, $category_input ) {
		$cat_ids = array();
		$cats = is_array( $category_input ) ? $category_input : explode( ',', $category_input );

		foreach ( $cats as $cat ) {
			$cat = trim( $cat );
			if ( is_numeric( $cat ) ) {
				$cat_ids[] = (int) $cat;
			} else {
				$term = term_exists( $cat, 'category' );
				if ( ! $term ) {
					$term = wp_insert_term( $cat, 'category' );
				}
				if ( ! is_wp_error( $term ) && isset( $term['term_id'] ) ) {
					$cat_ids[] = (int) $term['term_id'];
				}
			}
		}

		if ( ! empty( $cat_ids ) ) {
			wp_set_post_categories( $post_id, $cat_ids, false );
		}
	}

	/**
	 * Helper untuk mengunduh gambar dari URL dan memasangnya sebagai Featured Image
	 */
	private static function set_featured_image_from_url( $post_id, $url, $title ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Download temporary file
		$tmp = download_url( $url );
		if ( is_wp_error( $tmp ) ) {
			return false;
		}

		$file_array = array(
			'name'     => sanitize_file_name( basename( parse_url( $url, PHP_URL_PATH ) ) ?: 'acca-image-' . time() . '.jpg' ),
			'tmp_name' => $tmp,
		);

		// Masukkan ke Media Library WordPress
		$attach_id = media_handle_sideload( $file_array, $post_id, $title );
		if ( is_wp_error( $attach_id ) ) {
			@unlink( $tmp );
			return false;
		}

		// Pasang sebagai thumbnail (featured image)
		return set_post_thumbnail( $post_id, $attach_id );
	}

	/**
	 * GET /wp-json/acca/v1/ekskul
	 */
	public static function handle_get_ekskul( WP_REST_Request $request ) {
		$args = array(
			'post_type'      => 'ekstrakulikuler',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		$query = new WP_Query( $args );
		$list  = array();

		foreach ( $query->posts as $p ) {
			$thumb_id  = get_post_thumbnail_id( $p->ID );
			$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'full' ) : null;
			$desc      = get_post_meta( $p->ID, 'deskripsi', true ) ?: $p->post_content;
			$galeri    = get_post_meta( $p->ID, 'galeri_foto', true ) ?: '';

			$list[] = array(
				'id'          => $p->ID,
				'name'        => html_entity_decode( $p->post_title, ENT_QUOTES, 'UTF-8' ),
				'slug'        => $p->post_name,
				'deskripsi'   => $desc,
				'image_url'   => $thumb_url,
				'galeri_foto' => ! empty( $galeri ) ? explode( ',', $galeri ) : array(),
				'permalink'   => get_permalink( $p->ID ),
			);
		}

		return rest_ensure_response( array(
			'success' => true,
			'total'   => count( $list ),
			'ekskul'  => $list,
		) );
	}

	/**
	 * POST /wp-json/acca/v1/ekskul
	 */
	public static function handle_create_ekskul( WP_REST_Request $request ) {
		$name      = sanitize_text_field( $request->get_param( 'name' ) ?: $request->get_param( 'title' ) );
		$deskripsi = $request->get_param( 'deskripsi' ) ?: $request->get_param( 'content' );
		$image_url = esc_url_raw( $request->get_param( 'image_url' ) ?: $request->get_param( 'featured_image_url' ) ?: '' );

		if ( empty( $name ) ) {
			return new WP_Error( 'acca_missing_name', 'Nama ekstrakurikuler ("name" atau "title") wajib diisi.', array( 'status' => 400 ) );
		}

		$post_id = wp_insert_post( array(
			'post_title'   => strtoupper( $name ),
			'post_content' => wp_kses_post( $deskripsi ),
			'post_status'  => 'publish',
			'post_type'    => 'ekstrakulikuler',
			'post_author'  => 1,
		), true );

		if ( is_wp_error( $post_id ) ) {
			return new WP_Error( 'acca_ekskul_error', $post_id->get_error_message(), array( 'status' => 500 ) );
		}

		// Simpan meta 'deskripsi' yang dibaca oleh JetEngine listing kartu
		update_post_meta( $post_id, 'deskripsi', wp_kses_post( $deskripsi ) );
		update_post_meta( $post_id, '_acca_created', 1 );

		if ( ! empty( $image_url ) ) {
			self::set_featured_image_from_url( $post_id, $image_url, $name );
		}

		return rest_ensure_response( array(
			'success'   => true,
			'message'   => 'Ekstrakurikuler berhasil ditambahkan.',
			'id'        => $post_id,
			'name'      => strtoupper( $name ),
			'permalink' => get_permalink( $post_id ),
		) );
	}

	/**
	 * PUT/PATCH /wp-json/acca/v1/ekskul/{id}
	 */
	public static function handle_update_ekskul( WP_REST_Request $request ) {
		$post_id = (int) $request->get_param( 'id' );
		$post    = get_post( $post_id );

		if ( ! $post || $post->post_type !== 'ekstrakulikuler' ) {
			return new WP_Error( 'acca_not_found', 'Data ekstrakurikuler tidak ditemukan.', array( 'status' => 404 ) );
		}

		$post_arr = array( 'ID' => $post_id );
		if ( $request->has_param( 'name' ) || $request->has_param( 'title' ) ) {
			$name = sanitize_text_field( $request->get_param( 'name' ) ?: $request->get_param( 'title' ) );
			$post_arr['post_title'] = strtoupper( $name );
		}
		if ( $request->has_param( 'deskripsi' ) || $request->has_param( 'content' ) ) {
			$desc = wp_kses_post( $request->get_param( 'deskripsi' ) ?: $request->get_param( 'content' ) );
			$post_arr['post_content'] = $desc;
			update_post_meta( $post_id, 'deskripsi', $desc );
		}

		wp_update_post( $post_arr );

		if ( $request->has_param( 'image_url' ) || $request->has_param( 'featured_image_url' ) ) {
			$image_url = esc_url_raw( $request->get_param( 'image_url' ) ?: $request->get_param( 'featured_image_url' ) );
			if ( ! empty( $image_url ) ) {
				self::set_featured_image_from_url( $post_id, $image_url, get_the_title( $post_id ) );
			}
		}

		return rest_ensure_response( array(
			'success'   => true,
			'message'   => 'Data ekstrakurikuler berhasil diperbarui.',
			'id'        => $post_id,
			'name'      => get_the_title( $post_id ),
			'permalink' => get_permalink( $post_id ),
		) );
	}

	/**
	 * DELETE /wp-json/acca/v1/ekskul/{id}
	 */
	public static function handle_delete_ekskul( WP_REST_Request $request ) {
		$post_id = (int) $request->get_param( 'id' );
		$force   = (bool) $request->get_param( 'force' );

		$post = get_post( $post_id );
		if ( ! $post || $post->post_type !== 'ekstrakulikuler' ) {
			return new WP_Error( 'acca_not_found', 'Data ekstrakurikuler tidak ditemukan.', array( 'status' => 404 ) );
		}

		wp_delete_post( $post_id, $force );

		return rest_ensure_response( array(
			'success' => true,
			'message' => 'Ekstrakurikuler berhasil dihapus.',
			'id'      => $post_id,
		) );
	}

	/**
	 * GET /wp-json/acca/v1/survey
	 */
	public static function handle_get_survey() {
		$json_path = ABSPATH . 'wp-content/uploads/survey/survey_data.json';
		if ( ! file_exists( $json_path ) ) {
			return new WP_Error( 'acca_no_survey_file', 'File survey_data.json belum tersedia.', array( 'status' => 404 ) );
		}

		$data = json_decode( file_get_contents( $json_path ), true );
		return rest_ensure_response( array(
			'success' => true,
			'data'    => $data,
		) );
	}

	/**
	 * POST /wp-json/acca/v1/survey
	 */
	public static function handle_update_survey( WP_REST_Request $request ) {
		$new_data = $request->get_json_params();

		if ( empty( $new_data ) || ! is_array( $new_data ) ) {
			return new WP_Error( 'acca_invalid_json', 'Payload JSON survey tidak valid atau kosong.', array( 'status' => 400 ) );
		}

		$dir = ABSPATH . 'wp-content/uploads/survey';
		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		$json_path = $dir . '/survey_data.json';
		$saved = file_put_contents( $json_path, json_encode( $new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );

		if ( $saved === false ) {
			return new WP_Error( 'acca_save_failed', 'Gagal menulis ke file survey_data.json.', array( 'status' => 500 ) );
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => 'Data nilai survey berhasil diperbarui dan grafik langsung terupdate di website.',
			'bytes'   => $saved,
		) );
	}

	/**
	 * Filter nama author di WordPress agar menampilkan nama guru dari ACCA
	 */
	public static function filter_the_author( $author_name ) {
		if ( is_admin() ) {
			return $author_name;
		}
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return $author_name;
		}

		$acca_author = get_post_meta( $post_id, '_acca_author_name', true );
		if ( ! empty( $acca_author ) ) {
			return esc_html( $acca_author );
		}

		return $author_name;
	}

	/**
	 * Sematkan lencana kecil profil penulis ACCA di bagian bawah artikel single post
	 */
	public static function append_acca_author_badge( $content ) {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post_id = get_the_ID();
		$author_name = get_post_meta( $post_id, '_acca_author_name', true );
		if ( empty( $author_name ) ) {
			return $content;
		}

		$author_role = get_post_meta( $post_id, '_acca_author_role', true );
		$role_html   = ! empty( $author_role ) ? ' &bull; <span style="color:#64748B;">' . esc_html( $author_role ) . '</span>' : '';

		$badge = '
		<div class="icgowa-acca-author-card" style="margin-top: 2.5rem; padding: 1.25rem 1.5rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 4px solid #047857; border-radius: 8px; font-family: inherit;">
			<div style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em; color: #047857; font-weight: 700; margin-bottom: 0.35rem;">
				Kontributor Konten (ACCA)
			</div>
			<div style="font-size: 1.0625rem; font-weight: 600; color: #1E293B;">
				' . esc_html( $author_name ) . $role_html . '
			</div>
		</div>';

		return $content . $badge;
	}
}

ICGowa_ACCA_Bridge::init();

