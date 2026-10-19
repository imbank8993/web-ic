<?php
/**
 * Plugin Name: ICGowa — Menu & Halaman Survey Layanan Minimalis (Triwulan I - IV)
 * Description: Menambahkan submenu "Survey" pada menu Publikasi serta menampilkan halaman Survey Layanan (/survey/) dengan desain minimalis, rapi, serta memiliki batas dan pembeda antarbagian yang jelas.
 * Author: ICGowa
 * Version: 1.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Tambahkan item "Survey" ke dalam dropdown menu "Publikasi" (Parent ID: 276)
 */
add_filter( 'wp_nav_menu_objects', function ( $sorted_menu_items, $args ) {
	if ( empty( $sorted_menu_items ) || ! is_array( $sorted_menu_items ) ) {
		return $sorted_menu_items;
	}

	$publikasi_parent_id = 0;
	$template_item       = null;
	$already_exists      = false;

	foreach ( $sorted_menu_items as $item ) {
		$title_clean = trim( strip_tags( $item->title ) );
		if ( strcasecmp( $title_clean, 'Publikasi' ) === 0 && (int) $item->menu_item_parent === 0 ) {
			$publikasi_parent_id = (int) $item->ID;
		}
		if ( strcasecmp( $title_clean, 'Survey' ) === 0 ) {
			$already_exists = true;
		}
	}

	if ( $already_exists || ! $publikasi_parent_id ) {
		return $sorted_menu_items;
	}

	foreach ( $sorted_menu_items as $item ) {
		if ( (int) $item->menu_item_parent === $publikasi_parent_id ) {
			$template_item = $item;
		}
	}

	$req_path  = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) : '';
	$is_survey = ( $req_path === 'survey' );

	if ( $template_item ) {
		$survey_item                   = clone $template_item;
		$survey_item->ID               = 999280;
		$survey_item->db_id            = 999280;
		$survey_item->title            = 'Survey';
		$survey_item->post_title       = 'Survey';
		$survey_item->url              = home_url( '/survey/' );
		$survey_item->menu_order       = 999;
		$survey_item->object_id        = 999280;
		$survey_item->type             = 'custom';
		$survey_item->object           = 'custom';
		$survey_item->current          = $is_survey;
		$survey_item->classes          = array(
			'menu-item',
			'menu-item-type-custom',
			'menu-item-object-custom',
			'nav-item',
		);
		if ( $is_survey ) {
			$survey_item->classes[] = 'current-menu-item';
		}
		$sorted_menu_items[] = $survey_item;
	}

	return $sorted_menu_items;
}, 20, 2 );

/**
 * 2. Deteksi apakah URL saat ini adalah /survey/
 */
function icgowa_is_survey_request() {
	if ( is_admin() || ! isset( $_SERVER['REQUEST_URI'] ) ) {
		return false;
	}
	$path = trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	return ( $path === 'survey' );
}

/**
 * 3. Filter Judul Halaman & Heading Banner Elementor #192 saat berada di /survey/
 */
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( icgowa_is_survey_request() ) {
		return 'Survey Layanan — MAN Insan Cendekia Gowa';
	}
	return $title;
}, 99 );

add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
	if ( ! icgowa_is_survey_request() ) {
		return $content;
	}
	if ( method_exists( $widget, 'get_id' ) && $widget->get_id() === 'd4fa7be' ) {
		return '<h2 class="elementor-heading-title elementor-size-default">SURVEY LAYANAN</h2>';
	}
	return $content;
}, 99, 2 );

/**
 * 4. Render Halaman /survey/ Minimalis dengan Batas & Pembeda Antarbagian yang Jelas
 */
add_action( 'template_redirect', function () {
	if ( ! icgowa_is_survey_request() ) {
		return;
	}

	@ini_set( 'display_errors', '0' );

	global $wp_query, $post;
	$dummy_post = new stdClass();
	$dummy_post->ID                    = 999280;
	$dummy_post->post_author           = 1;
	$dummy_post->post_date             = current_time( 'mysql' );
	$dummy_post->post_date_gmt         = current_time( 'mysql', 1 );
	$dummy_post->post_content          = '';
	$dummy_post->post_title            = 'Survey Layanan';
	$dummy_post->post_excerpt          = '';
	$dummy_post->post_status           = 'publish';
	$dummy_post->comment_status        = 'closed';
	$dummy_post->ping_status           = 'closed';
	$dummy_post->post_password         = '';
	$dummy_post->post_name             = 'survey';
	$dummy_post->to_ping               = '';
	$dummy_post->pinged                = '';
	$dummy_post->post_modified         = current_time( 'mysql' );
	$dummy_post->post_modified_gmt     = current_time( 'mysql', 1 );
	$dummy_post->post_content_filtered = '';
	$dummy_post->post_parent           = 0;
	$dummy_post->guid                  = home_url( '/survey/' );
	$dummy_post->menu_order            = 0;
	$dummy_post->post_type             = 'page';
	$dummy_post->post_mime_type        = '';
	$dummy_post->comment_count         = 0;
	$dummy_post->filter                = 'raw';

	$post                        = new WP_Post( $dummy_post );
	$wp_query->post              = $post;
	$wp_query->posts             = array( $post );
	$wp_query->queried_object    = $post;
	$wp_query->queried_object_id = $post->ID;
	$wp_query->post_count        = 1;
	$wp_query->found_posts       = 1;
	$wp_query->is_404            = false;
	$wp_query->is_page           = true;
	$wp_query->is_singular       = true;

	status_header( 200 );

	$json_path   = ABSPATH . 'wp-content/uploads/survey/survey_data.json';
	$survey_data = file_exists( $json_path ) ? json_decode( file_get_contents( $json_path ), true ) : array();

	get_header();
	?>
	<link rel="stylesheet" href="<?php echo esc_url( content_url( '/uploads/elementor/css/post-192.css' ) ); ?>" media="all" />
	<script src="<?php echo esc_url( content_url( '/uploads/survey/chart.umd.min.js' ) ); ?>"></script>
	<script src="<?php echo esc_url( content_url( '/uploads/survey/chartjs-plugin-datalabels.min.js' ) ); ?>"></script>
	<style>
		.icgowa-survey-wrapper {
			background: #F1F5F9;
			padding: 44px 20px 72px;
			min-height: 65vh;
		}
		.icgowa-survey-container {
			max-width: 1020px;
			margin: 0 auto;
		}

		/* Label Seksi dengan Garis Pembatas */
		.icgowa-sec-label {
			display: flex;
			align-items: center;
			gap: 14px;
			margin-bottom: 16px;
		}
		.icgowa-sec-label span {
			font-size: 0.82rem;
			font-weight: 700;
			color: #0F296B;
			text-transform: uppercase;
			letter-spacing: 0.08em;
			white-space: nowrap;
			background: #E2E8F0;
			padding: 5px 12px;
			border-radius: 6px;
		}
		.icgowa-sec-label::after {
			content: "";
			flex: 1;
			height: 1px;
			background: #CBD5E1;
		}

		/* BAGIAN 1: Bar Link Survey */
		.icgowa-survey-bar {
			background: linear-gradient(135deg, #0F296B 0%, #1536A2 100%);
			border-radius: 16px;
			padding: 28px 32px;
			color: #FFFFFF;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 24px;
			margin-bottom: 38px;
			border-left: 6px solid #FFAE00;
			box-shadow: 0 10px 25px rgba(15, 41, 107, 0.15);
		}
		.icgowa-survey-bar h3 {
			color: #FFFFFF !important;
			font-size: 1.3rem !important;
			font-weight: 700 !important;
			margin: 0 0 6px 0 !important;
		}
		.icgowa-survey-bar p {
			color: rgba(255, 255, 255, 0.88) !important;
			font-size: 0.94rem !important;
			line-height: 1.65 !important;
			margin: 0 !important;
			max-width: 660px;
			text-align: justify;
		}
		.icgowa-btn-survey {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			background: #FFAE00;
			color: #0F296B !important;
			font-weight: 700;
			font-size: 0.92rem;
			padding: 12px 26px;
			border-radius: 999px;
			text-decoration: none !important;
			white-space: nowrap;
			flex-shrink: 0;
			transition: all 0.2s ease;
		}
		.icgowa-btn-survey:hover {
			background: #FFC233;
			transform: translateY(-1px);
		}

		/* BAGIAN 2: Kartu Indeks Triwulan (Pembeda Visual: Warna Navy Saat Aktif) */
		.icgowa-tw-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 16px;
			margin-bottom: 38px;
		}
		.icgowa-tw-card {
			background: #FFFFFF;
			border-radius: 14px;
			padding: 20px;
			border: 2px solid #CBD5E1;
			cursor: pointer;
			transition: all 0.22s ease;
			text-align: center;
			position: relative;
		}
		.icgowa-tw-card:hover:not(.coming-soon):not(.active-card) {
			border-color: #1536A2;
			transform: translateY(-2px);
		}
		/* Pembeda Tegas untuk Triwulan yang Sedang Dipilih */
		.icgowa-tw-card.active-card {
			background: #0F296B;
			border-color: #0F296B;
			box-shadow: 0 12px 26px rgba(15, 41, 107, 0.25);
			transform: translateY(-3px);
		}
		.icgowa-tw-card.active-card::after {
			content: "";
			position: absolute;
			bottom: -10px;
			left: 50%;
			transform: translateX(-50%);
			border-width: 10px 10px 0;
			border-style: solid;
			border-color: #0F296B transparent transparent;
		}
		.icgowa-tw-title {
			font-size: 0.85rem;
			font-weight: 700;
			color: #475569;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			margin-bottom: 6px;
		}
		.icgowa-tw-card.active-card .icgowa-tw-title {
			color: #FFAE00;
		}
		.icgowa-tw-pct {
			font-size: 1.75rem;
			font-weight: 700;
			color: #0F296B;
			line-height: 1.2;
			margin-bottom: 8px;
		}
		.icgowa-tw-card.active-card .icgowa-tw-pct {
			color: #FFFFFF;
		}

		/* Triwulan IV (Coming Soon) */
		.icgowa-tw-card.coming-soon {
			background: #E2E8F0;
			border: 2px dashed #94A3B8;
			opacity: 0.65;
			cursor: default;
		}
		.icgowa-tw-card.coming-soon .icgowa-tw-title {
			color: #64748B;
		}
		.icgowa-tw-card.coming-soon .icgowa-tw-pct {
			font-size: 1.05rem;
			color: #64748B;
			margin: 14px 0 4px;
		}

		/* Badge Predikat */
		.icgowa-pred-badge {
			display: inline-block;
			padding: 3px 9px;
			border-radius: 999px;
			font-size: 0.66rem;
			font-weight: 700;
			text-transform: uppercase;
			white-space: nowrap;
			letter-spacing: 0.02em;
		}
		.pred-sangat-puas {
			background: #DCFCE7;
			color: #15803D;
		}
		.pred-puas {
			background: #DBEAFE;
			color: #1D4ED8;
		}
		.pred-cukup-puas {
			background: #FEF3C7;
			color: #B45309;
		}
		.pred-tidak-puas {
			background: #FEE2E2;
			color: #B91C1C;
		}
		.icgowa-tw-card.active-card .pred-puas {
			background: rgba(255, 255, 255, 0.18);
			color: #FFFFFF;
		}

		/* BAGIAN 3: Kontainer Diagram Batang Perbandingan Bidang Layanan */
		.icgowa-bidang-panel {
			background: #FFFFFF;
			border-radius: 18px;
			padding: 28px;
			border: 1px solid #CBD5E1;
			box-shadow: 0 8px 24px rgba(15, 41, 107, 0.05);
		}
		.icgowa-bidang-panel-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 12px;
			padding-bottom: 16px;
			margin-bottom: 22px;
			border-bottom: 2px solid #F1F5F9;
		}
		.icgowa-bidang-panel-header h4 {
			margin: 0 !important;
			font-size: 1.08rem !important;
			font-weight: 700 !important;
			color: #0F296B !important;
			padding-left: 12px;
			border-left: 4px solid #FFAE00;
		}
		.icgowa-panel-subtitle {
			margin: 4px 0 0 12px !important;
			font-size: 0.84rem !important;
			color: #64748B !important;
		}
		.icgowa-panel-legend {
			display: flex;
			align-items: center;
			gap: 16px;
			font-size: 0.78rem;
			color: #64748B;
		}
		.legend-item {
			display: flex;
			align-items: center;
			gap: 6px;
		}
		.legend-dot {
			width: 10px;
			height: 10px;
			border-radius: 50%;
			display: inline-block;
		}
		.dot-sangat-puas {
			background: #16A34A;
		}
		.dot-puas {
			background: #1D4ED8;
		}

		/* Kontrol Mode Tampilan Grafik */
		.icgowa-chart-controls {
			display: flex;
			align-items: center;
			gap: 12px;
			flex-wrap: wrap;
		}
		.icgowa-view-toggle {
			display: inline-flex;
			background: #F1F5F9;
			padding: 4px;
			border-radius: 9px;
			gap: 4px;
			border: 1px solid #E2E8F0;
		}
		.icgowa-toggle-btn {
			background: transparent;
			border: none;
			padding: 6px 14px;
			border-radius: 6px;
			font-size: 0.8rem;
			font-weight: 600;
			color: #64748B;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		.icgowa-toggle-btn:hover:not(.active) {
			color: #0F296B;
			background: rgba(255, 255, 255, 0.6);
		}
		.icgowa-toggle-btn.active {
			background: #0F296B;
			color: #FFFFFF;
			box-shadow: 0 2px 6px rgba(15, 41, 107, 0.2);
		}

		/* Canvas Grafik Chart.js */
		.icgowa-chart-canvas-wrap {
			position: relative;
			width: 100%;
			height: 380px;
			margin: 10px 0 24px;
			background: #FFFFFF;
		}

		/* Header Daftar Bidang Layanan */
		.icgowa-bidang-list-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 8px;
			padding: 18px 0 14px;
			border-top: 1px solid #F1F5F9;
			margin-top: 8px;
		}
		.icgowa-bidang-list-header h5 {
			margin: 0 !important;
			font-size: 0.96rem !important;
			font-weight: 700 !important;
			color: #0F296B !important;
		}
		.icgowa-list-hint {
			font-size: 0.78rem;
			color: #64748B;
		}

		/* Kontainer Diagram Batang */
		.icgowa-barchart-container {
			display: flex;
			flex-direction: column;
			gap: 12px;
		}
		.icgowa-barchart-row {
			display: flex;
			align-items: center;
			gap: 16px;
			padding: 12px 18px;
			background: #F8FAFC;
			border-radius: 12px;
			border: 1px solid #E2E8F0;
			transition: all 0.2s ease;
		}
		.icgowa-barchart-row:hover {
			background: #FFFFFF;
			border-color: #CBD5E1;
			box-shadow: 0 4px 14px rgba(15, 41, 107, 0.06);
			transform: translateY(-1px);
		}
		.icgowa-barchart-label {
			width: 180px;
			flex-shrink: 0;
		}
		.icgowa-barchart-name {
			font-size: 0.92rem;
			font-weight: 600;
			color: #0F296B;
			line-height: 1.35;
			display: block;
		}
		.icgowa-barchart-bar-area {
			flex: 1;
			min-width: 120px;
		}
		.icgowa-barchart-track {
			width: 100%;
			height: 14px;
			background: #E2E8F0;
			border-radius: 999px;
			overflow: hidden;
			position: relative;
		}
		.icgowa-barchart-fill {
			height: 100%;
			border-radius: 999px;
			transition: width 0.7s cubic-bezier(0.4, 0, 0.2, 1);
		}
		.icgowa-barchart-fill.fill-sangat-puas {
			background: linear-gradient(90deg, #16A34A 0%, #22C55E 100%);
		}
		.icgowa-barchart-fill.fill-puas {
			background: linear-gradient(90deg, #1D4ED8 0%, #3B82F6 100%);
		}
		.icgowa-barchart-fill.fill-cukup-puas {
			background: linear-gradient(90deg, #D97706 0%, #F59E0B 100%);
		}
		.icgowa-barchart-fill.fill-tidak-puas {
			background: linear-gradient(90deg, #DC2626 0%, #EF4444 100%);
		}
		.icgowa-barchart-score {
			width: 65px;
			flex-shrink: 0;
			text-align: right;
			font-size: 0.96rem;
			font-weight: 700;
			color: #0F296B;
		}
		.icgowa-barchart-badge {
			width: 105px;
			flex-shrink: 0;
			text-align: center;
		}
		.icgowa-barchart-action {
			flex-shrink: 0;
		}

		/* Tombol Detail Proporsional & Rapi */
		.icgowa-detail-btn {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			background: #FFFFFF;
			color: #0F296B;
			border: 1px solid #CBD5E1;
			border-radius: 8px;
			padding: 7px 14px;
			font-size: 0.8rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s ease;
			white-space: nowrap;
		}
		.icgowa-detail-btn:hover {
			background: #0F296B;
			color: #FFFFFF;
			border-color: #0F296B;
			transform: translateY(-1px);
			box-shadow: 0 4px 10px rgba(15, 41, 107, 0.15);
		}
		.icgowa-btn-arrow {
			width: 13px;
			height: 13px;
			transition: transform 0.2s ease;
		}
		.icgowa-detail-btn:hover .icgowa-btn-arrow {
			transform: translateX(2px);
		}

		/* Modal Popup */
		.icgowa-survey-modal-backdrop {
			position: fixed;
			inset: 0;
			background: rgba(10, 25, 68, 0.55);
			backdrop-filter: blur(3px);
			z-index: 99999;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 20px;
			opacity: 0;
			visibility: hidden;
			transition: all 0.2s ease;
		}
		.icgowa-survey-modal-backdrop.open {
			opacity: 1;
			visibility: visible;
		}
		.icgowa-survey-modal {
			background: #FFFFFF;
			border-radius: 16px;
			max-width: 740px;
			width: 100%;
			max-height: 84vh;
			display: flex;
			flex-direction: column;
			overflow: hidden;
			box-shadow: 0 20px 50px rgba(10, 25, 68, 0.3);
		}
		.icgowa-survey-modal-header {
			padding: 16px 22px;
			background: #0F296B;
			color: #FFFFFF;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}
		.icgowa-survey-modal-header h3 {
			margin: 0 !important;
			color: #FFFFFF !important;
			font-size: 1.05rem !important;
			font-weight: 600 !important;
		}
		.icgowa-survey-modal-close {
			background: rgba(255, 255, 255, 0.15);
			color: #FFFFFF;
			border: none;
			width: 30px;
			height: 30px;
			border-radius: 50%;
			font-size: 1.1rem;
			cursor: pointer;
		}
		.icgowa-survey-modal-body {
			padding: 20px 24px;
			overflow-y: auto;
		}

		/* Model Detail Item Penilaian Tanpa Garis (Clean & Borderless) */
		.icgowa-modal-summary {
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 8px;
			padding: 12px 18px;
			background: #F8FAFC;
			border-radius: 10px;
			margin-bottom: 18px;
			font-size: 0.88rem;
			color: #475569;
		}
		.icgowa-summary-count {
			font-weight: 600;
			color: #0F296B;
		}
		.icgowa-summary-score {
			display: flex;
			align-items: center;
			gap: 10px;
		}
		.icgowa-summary-score strong {
			color: #0F296B;
			font-size: 0.98rem;
		}

		.icgowa-ind-list {
			display: flex;
			flex-direction: column;
			gap: 8px;
			border: none !important;
		}
		.icgowa-ind-item {
			display: flex;
			align-items: center;
			gap: 14px;
			padding: 11px 16px;
			border-radius: 10px;
			background: transparent;
			border: none !important; /* Model tanpa garis sesuai permintaan */
			transition: background 0.15s ease;
		}
		.icgowa-ind-item:nth-child(even) {
			background: #F8FAFC; /* Soft contrast tanpa garis */
		}
		.icgowa-ind-item:hover {
			background: #F1F5F9;
		}
		.icgowa-ind-no {
			font-size: 0.8rem;
			font-weight: 700;
			color: #94A3B8;
			width: 24px;
			flex-shrink: 0;
			text-align: center;
		}
		.icgowa-ind-body {
			flex: 1;
			min-width: 0;
		}
		.icgowa-ind-label {
			font-size: 0.88rem;
			font-weight: 500;
			color: #1E293B;
			line-height: 1.45;
			margin-bottom: 6px;
		}
		.icgowa-ind-progress-wrap {
			width: 100%;
			max-width: 260px;
		}
		.icgowa-ind-track {
			width: 100%;
			height: 5px;
			background: #E2E8F0;
			border-radius: 999px;
			overflow: hidden;
		}
		.icgowa-ind-fill {
			height: 100%;
			border-radius: 999px;
		}
		.icgowa-ind-fill.fill-sangat-puas {
			background: #16A34A;
		}
		.icgowa-ind-fill.fill-puas {
			background: #1D4ED8;
		}
		.icgowa-ind-fill.fill-cukup-puas {
			background: #D97706;
		}
		.icgowa-ind-fill.fill-tidak-puas {
			background: #DC2626;
		}
		.icgowa-ind-score {
			font-size: 0.92rem;
			font-weight: 700;
			color: #0F296B;
			width: 65px;
			text-align: right;
			flex-shrink: 0;
		}
		.icgowa-ind-badge {
			width: 95px;
			text-align: center;
			flex-shrink: 0;
		}

		@media (max-width: 860px) {
			.icgowa-survey-bar {
				flex-direction: column;
				align-items: flex-start;
			}
			.icgowa-tw-grid {
				grid-template-columns: repeat(2, 1fr);
			}
			.icgowa-barchart-row {
				flex-wrap: wrap;
				gap: 10px 14px;
				padding: 14px 16px;
			}
			.icgowa-barchart-label {
				width: auto;
				flex: 1;
				min-width: 140px;
			}
			.icgowa-barchart-bar-area {
				order: 5;
				width: 100%;
				flex: 1 1 100%;
				margin-top: 4px;
			}
			.icgowa-barchart-score {
				width: auto;
			}
			.icgowa-barchart-badge {
				width: auto;
			}
			.icgowa-ind-progress-wrap {
				max-width: 100%;
			}
		}
		@media (max-width: 520px) {
			.icgowa-tw-grid {
				grid-template-columns: 1fr;
			}
			.icgowa-ind-item {
				flex-wrap: wrap;
				gap: 8px;
			}
			.icgowa-ind-body {
				width: 100%;
				flex: 1 1 100%;
			}
			.icgowa-ind-score {
				margin-left: auto;
			}
		}
	</style>

	<div class="icgowa-survey-wrapper">
		<div class="icgowa-survey-container">

			<!-- 1. LINK SURVEY (TANPA JUDUL LINK SURVEY DI ATASNYA, DENGAN DESKRIPSI) -->
			<div class="icgowa-survey-bar">
				<div>
					<h3>Formulir Survey Kepuasan Layanan</h3>
					<p>Silakan berikan penilaian dan masukan Anda terhadap kualitas pelayanan di MAN Insan Cendekia Gowa melalui tautan formulir survey berikut.</p>
				</div>
				<a href="https://docs.google.com/forms/" target="_blank" rel="noopener" class="icgowa-btn-survey">
					<span>Isi Survey</span>
					<span>&rarr;</span>
				</a>
			</div>

			<!-- 2. PILIHAN TRIWULAN (I - IV) -->
			<div class="icgowa-sec-label"><span>Hasil Survey Triwulan</span></div>
			<div class="icgowa-tw-grid">
				<div class="icgowa-tw-card" data-select-tw="tw1">
					<div class="icgowa-tw-title">Triwulan I</div>
					<div class="icgowa-tw-pct">71,29%</div>
					<span class="icgowa-pred-badge pred-puas">Puas</span>
				</div>

				<div class="icgowa-tw-card" data-select-tw="tw2">
					<div class="icgowa-tw-title">Triwulan II</div>
					<div class="icgowa-tw-pct">74,59%</div>
					<span class="icgowa-pred-badge pred-puas">Puas</span>
				</div>

				<div class="icgowa-tw-card active-card" data-select-tw="tw3">
					<div class="icgowa-tw-title">Triwulan III</div>
					<div class="icgowa-tw-pct">76,63%</div>
					<span class="icgowa-pred-badge pred-puas">Puas</span>
				</div>

				<div class="icgowa-tw-card coming-soon">
					<div class="icgowa-tw-title">Triwulan IV</div>
					<div class="icgowa-tw-pct">Coming Soon</div>
				</div>
			</div>

			<!-- 3. PANEL RINCIAN BIDANG LAYANAN (GRAFIK CHART.JS & DAFTAR INDIKATOR) -->
			<div class="icgowa-bidang-panel">
				<div class="icgowa-bidang-panel-header">
					<div>
						<h4 id="icgowa-panel-title">Grafik Persentase Kepuasan Layanan — Triwulan III</h4>
						<p class="icgowa-panel-subtitle">Visualisasi perbandingan persentase kepuasan tiap bidang layanan menggunakan Chart.js</p>
					</div>
					<div class="icgowa-chart-controls">
						<div class="icgowa-view-toggle">
							<button type="button" class="icgowa-toggle-btn active" data-chart-mode="single" id="btn-mode-single">Per Triwulan</button>
							<button type="button" class="icgowa-toggle-btn" data-chart-mode="compare" id="btn-mode-compare">Komparasi Semua Triwulan</button>
						</div>
					</div>
				</div>

				<!-- CANVAS GRAFIK CHART.JS -->
				<div class="icgowa-chart-canvas-wrap">
					<canvas id="icgowa-survey-chart"></canvas>
				</div>

				<!-- HEADER DAFTAR BIDANG DENGAN TOMBOL DETAIL PROPORSIONAL -->
				<div class="icgowa-bidang-list-header">
					<h5>Rincian &amp; Indikator Penilaian Tiap Bidang</h5>
					<span class="icgowa-list-hint">Klik tombol Detail atau klik batang grafik untuk melihat rincian indikator</span>
				</div>
				<div id="icgowa-bidang-cards-container" class="icgowa-barchart-container"></div>
			</div>

		</div>
	</div>

	<!-- MODAL POPUP INDIKATOR (MODEL BERSIH TANPA GARIS TABEL) -->
	<div class="icgowa-survey-modal-backdrop" id="icgowa-indicator-modal">
		<div class="icgowa-survey-modal">
			<div class="icgowa-survey-modal-header">
				<h3 id="icgowa-indicator-modal-title">Rincian Indikator</h3>
				<button type="button" class="icgowa-survey-modal-close" id="icgowa-indicator-modal-close">&times;</button>
			</div>
			<div class="icgowa-survey-modal-body" id="icgowa-indicator-modal-body"></div>
		</div>
	</div>

	<script>
	(function() {
		var surveyData = <?php echo wp_json_encode( $survey_data ); ?>;
		var twLabels = {
			tw1: 'Triwulan I',
			tw2: 'Triwulan II',
			tw3: 'Triwulan III'
		};
		var currentTw = 'tw3';
		var currentChartMode = 'single';
		var chartInstance = null;

		function getPredClass(pred) {
			var p = (pred || '').toLowerCase();
			if (p.indexOf('sangat') !== -1) return 'pred-sangat-puas';
			if (p.indexOf('cukup') !== -1) return 'pred-cukup-puas';
			if (p.indexOf('tidak') !== -1) return 'pred-tidak-puas';
			return 'pred-puas';
		}

		function getFillClass(pred) {
			var p = (pred || '').toLowerCase();
			if (p.indexOf('sangat') !== -1) return 'fill-sangat-puas';
			if (p.indexOf('cukup') !== -1) return 'fill-cukup-puas';
			if (p.indexOf('tidak') !== -1) return 'fill-tidak-puas';
			return 'fill-puas';
		}

		function formatNum(n) {
			return Number(n || 0).toFixed(2).replace('.', ',');
		}

		function updateChart() {
			var canvas = document.getElementById('icgowa-survey-chart');
			if (!canvas || typeof Chart === 'undefined') return;

			if (typeof ChartDataLabels !== 'undefined') {
				Chart.register(ChartDataLabels);
			}

			var fields = surveyData[currentTw] || [];
			var labels = fields.map(function(f) { return f.name; });

			if (currentChartMode === 'single') {
				var dataValues = fields.map(function(f) { return f.avg_pct; });
				var bgColors = fields.map(function(f) {
					var p = (f.predicate || '').toLowerCase();
					if (p.indexOf('sangat') !== -1) return '#16A34A';
					if (p.indexOf('cukup') !== -1) return '#D97706';
					if (p.indexOf('tidak') !== -1) return '#DC2626';
					return '#1D4ED8';
				});

				var config = {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: 'Kepuasan (' + twLabels[currentTw] + ')',
							data: dataValues,
							backgroundColor: bgColors,
							borderRadius: 6,
							borderSkipped: false,
							barThickness: 22
						}]
					},
					options: {
						indexAxis: 'y',
						responsive: true,
						maintainAspectRatio: false,
						animation: { duration: 600, easing: 'easeOutQuart' },
						layout: { padding: { right: 55, top: 10, bottom: 10 } },
						scales: {
							x: {
								min: 0,
								max: 100,
								grid: { color: '#F1F5F9' },
								ticks: {
									callback: function(v) { return v + '%'; },
									font: { family: "'Poppins', sans-serif", size: 11 },
									color: '#64748B'
								}
							},
							y: {
								grid: { display: false },
								ticks: {
									font: { family: "'Poppins', sans-serif", size: 12, weight: '600' },
									color: '#0F296B'
								}
							}
						},
						plugins: {
							legend: { display: false },
							tooltip: {
								backgroundColor: '#0F296B',
								titleFont: { family: "'Poppins', sans-serif", size: 13, weight: 'bold' },
								bodyFont: { family: "'Poppins', sans-serif", size: 12 },
								padding: 12,
								cornerRadius: 8,
								callbacks: {
									label: function(ctx) {
										var sec = fields[ctx.dataIndex] || {};
										return ' Tingkat Kepuasan: ' + formatNum(ctx.raw) + '% (' + (sec.predicate || 'Puas') + ')';
									}
								}
							},
							datalabels: {
								anchor: 'end',
								align: 'end',
								color: '#0F296B',
								font: { family: "'Poppins', sans-serif", weight: 'bold', size: 11 },
								formatter: function(v) { return formatNum(v) + '%'; }
							}
						},
						onClick: function(evt, elements) {
							if (elements && elements.length > 0) {
								openIndicatorModal(elements[0].index);
							}
						}
					}
				};

				if (chartInstance) chartInstance.destroy();
				chartInstance = new Chart(canvas, config);

			} else {
				// KOMPARASI SEMUA TRIWULAN (TW1, TW2, TW3)
				var tw1Vals = (surveyData.tw1 || []).map(function(f) { return f.avg_pct; });
				var tw2Vals = (surveyData.tw2 || []).map(function(f) { return f.avg_pct; });
				var tw3Vals = (surveyData.tw3 || []).map(function(f) { return f.avg_pct; });

				var config = {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [
							{
								label: 'Triwulan I',
								data: tw1Vals,
								backgroundColor: '#0284C7',
								borderRadius: 4,
								barThickness: 12
							},
							{
								label: 'Triwulan II',
								data: tw2Vals,
								backgroundColor: '#1D4ED8',
								borderRadius: 4,
								barThickness: 12
							},
							{
								label: 'Triwulan III',
								data: tw3Vals,
								backgroundColor: '#16A34A',
								borderRadius: 4,
								barThickness: 12
							}
						]
					},
					options: {
						indexAxis: 'x',
						responsive: true,
						maintainAspectRatio: false,
						animation: { duration: 600, easing: 'easeOutQuart' },
						layout: { padding: { top: 20, bottom: 10 } },
						scales: {
							x: {
								grid: { display: false },
								ticks: {
									font: { family: "'Poppins', sans-serif", size: 11, weight: '600' },
									color: '#0F296B'
								}
							},
							y: {
								min: 50,
								max: 100,
								grid: { color: '#F1F5F9' },
								ticks: {
									callback: function(v) { return v + '%'; },
									font: { family: "'Poppins', sans-serif", size: 11 },
									color: '#64748B'
								}
							}
						},
						plugins: {
							legend: {
								display: true,
								position: 'top',
								labels: {
									font: { family: "'Poppins', sans-serif", size: 12, weight: '600' },
									color: '#334155',
									usePointStyle: true,
									pointStyle: 'circle',
									padding: 16
								}
							},
							tooltip: {
								backgroundColor: '#0F296B',
								titleFont: { family: "'Poppins', sans-serif", size: 13, weight: 'bold' },
								bodyFont: { family: "'Poppins', sans-serif", size: 12 },
								padding: 12,
								cornerRadius: 8,
								callbacks: {
									label: function(ctx) {
										return ' ' + ctx.dataset.label + ': ' + formatNum(ctx.raw) + '%';
									}
								}
							},
							datalabels: {
								anchor: 'end',
								align: 'end',
								color: '#475569',
								font: { family: "'Poppins', sans-serif", weight: '600', size: 10 },
								formatter: function(v) { return formatNum(v) + '%'; }
							}
						},
						onClick: function(evt, elements) {
							if (elements && elements.length > 0) {
								openIndicatorModal(elements[0].index);
							}
						}
					}
				};

				if (chartInstance) chartInstance.destroy();
				chartInstance = new Chart(canvas, config);
			}
		}

		function openIndicatorModal(idx) {
			var sec = (surveyData[currentTw] || [])[idx];
			if (!sec) return;

			var modal = document.getElementById('icgowa-indicator-modal');
			document.getElementById('icgowa-indicator-modal-title').textContent = 'Rincian Penilaian: ' + sec.name + ' (' + twLabels[currentTw] + ')';

			var summaryHtml = '<div class="icgowa-modal-summary">' +
				'<div><span class="icgowa-summary-count">' + (sec.indicators || []).length + ' Item Penilaian</span></div>' +
				'<div class="icgowa-summary-score">' +
					'<span>Rata-rata: <strong>' + formatNum(sec.avg_pct) + '%</strong></span>' +
					'<span class="icgowa-pred-badge ' + getPredClass(sec.predicate) + '">' + sec.predicate + '</span>' +
				'</div>' +
			'</div>';

			var rowsHtml = (sec.indicators || []).map(function(ind) {
				var pClass = getPredClass(ind.pred);
				var fillClass = getFillClass(ind.pred);
				var noFormatted = ind.no < 10 ? '0' + ind.no : ind.no;
				return '<div class="icgowa-ind-item">' +
					'<div class="icgowa-ind-no">' + noFormatted + '</div>' +
					'<div class="icgowa-ind-body">' +
						'<div class="icgowa-ind-label">' + ind.label + '</div>' +
						'<div class="icgowa-ind-progress-wrap">' +
							'<div class="icgowa-ind-track">' +
								'<div class="icgowa-ind-fill ' + fillClass + '" style="width: ' + ind.pct + '%;"></div>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div class="icgowa-ind-score">' + formatNum(ind.pct) + '%</div>' +
					'<div class="icgowa-ind-badge">' +
						'<span class="icgowa-pred-badge ' + pClass + '">' + ind.pred + '</span>' +
					'</div>' +
				'</div>';
			}).join('');

			document.getElementById('icgowa-indicator-modal-body').innerHTML =
				summaryHtml + '<div class="icgowa-ind-list">' + rowsHtml + '</div>';
			modal.classList.add('open');
		}

		function renderActiveQuarter(tw) {
			if (!surveyData[tw]) return;
			currentTw = tw;
			var list = surveyData[tw] || [];
			var cardsContainer = document.getElementById('icgowa-bidang-cards-container');
			var panelTitle = document.getElementById('icgowa-panel-title');

			if (panelTitle && twLabels[tw]) {
				if (currentChartMode === 'single') {
					panelTitle.textContent = 'Grafik Persentase Kepuasan Layanan — ' + twLabels[tw];
				} else {
					panelTitle.textContent = 'Komparasi Tingkat Kepuasan Semua Triwulan';
				}
			}

			document.querySelectorAll('.icgowa-tw-card[data-select-tw]').forEach(function(c) {
				c.classList.toggle('active-card', c.getAttribute('data-select-tw') === tw);
			});

			cardsContainer.innerHTML = list.map(function(sec, idx) {
				var pClass = getPredClass(sec.predicate);
				var fillClass = getFillClass(sec.predicate);
				return '<div class="icgowa-barchart-row">' +
					'<div class="icgowa-barchart-label">' +
						'<span class="icgowa-barchart-name">' + sec.name + '</span>' +
					'</div>' +
					'<div class="icgowa-barchart-bar-area">' +
						'<div class="icgowa-barchart-track" title="' + formatNum(sec.avg_pct) + '% (' + sec.predicate + ')">' +
							'<div class="icgowa-barchart-fill ' + fillClass + '" style="width: ' + sec.avg_pct + '%;"></div>' +
						'</div>' +
					'</div>' +
					'<div class="icgowa-barchart-score">' + formatNum(sec.avg_pct) + '%</div>' +
					'<div class="icgowa-barchart-badge">' +
						'<span class="icgowa-pred-badge ' + pClass + '">' + sec.predicate + '</span>' +
					'</div>' +
					'<div class="icgowa-barchart-action">' +
						'<button type="button" class="icgowa-detail-btn" data-sec-idx="' + idx + '">' +
							'<span>Detail</span>' +
							'<svg class="icgowa-btn-arrow" viewBox="0 0 20 20" fill="currentColor">' +
								'<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />' +
							'</svg>' +
						'</button>' +
					'</div>' +
				'</div>';
			}).join('');

			updateChart();
		}

		// Event listener untuk pilihan kartu Triwulan di atas
		document.querySelectorAll('.icgowa-tw-card[data-select-tw]').forEach(function(card) {
			card.addEventListener('click', function() {
				currentChartMode = 'single';
				document.querySelectorAll('.icgowa-toggle-btn').forEach(function(b) {
					b.classList.toggle('active', b.getAttribute('data-chart-mode') === 'single');
				});
				renderActiveQuarter(card.getAttribute('data-select-tw'));
			});
		});

		// Event listener toggle mode grafik (Per Triwulan vs Komparasi)
		document.querySelectorAll('.icgowa-toggle-btn').forEach(function(btn) {
			btn.addEventListener('click', function() {
				var mode = btn.getAttribute('data-chart-mode');
				currentChartMode = mode;
				document.querySelectorAll('.icgowa-toggle-btn').forEach(function(b) {
					b.classList.toggle('active', b === btn);
				});
				var panelTitle = document.getElementById('icgowa-panel-title');
				if (mode === 'compare') {
					panelTitle.textContent = 'Komparasi Tingkat Kepuasan Semua Triwulan';
				} else {
					panelTitle.textContent = 'Grafik Persentase Kepuasan Layanan — ' + twLabels[currentTw];
				}
				updateChart();
			});
		});

		// Modal click delegation
		document.getElementById('icgowa-bidang-cards-container').addEventListener('click', function(e) {
			var btn = e.target.closest('.icgowa-detail-btn');
			if (!btn) return;
			var idx = parseInt(btn.getAttribute('data-sec-idx'), 10);
			openIndicatorModal(idx);
		});

		var modal = document.getElementById('icgowa-indicator-modal');
		document.getElementById('icgowa-indicator-modal-close').addEventListener('click', function() {
			modal.classList.remove('open');
		});
		modal.addEventListener('click', function(e) {
			if (e.target === modal) modal.classList.remove('open');
		});

		renderActiveQuarter('tw3');
	})();
	</script>
	<?php

	get_footer();
	exit;
}, 5 );
