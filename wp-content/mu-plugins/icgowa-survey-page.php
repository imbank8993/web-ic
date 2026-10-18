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

		/* BAGIAN 3: Kontainer Khusus Rincian Bidang Layanan (Batas & Pembeda Jelas) */
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

		.icgowa-bidang-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 16px;
		}
		.icgowa-bidang-card {
			background: #F8FAFC;
			border-radius: 12px;
			padding: 16px;
			border: 1px solid #E2E8F0;
			border-left: 4px solid #1536A2;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			transition: all 0.2s ease;
		}
		.icgowa-bidang-card.card-sangat-puas {
			border-left-color: #16A34A;
		}
		.icgowa-bidang-card:hover {
			background: #FFFFFF;
			border-color: #93C5FD;
			border-left-width: 4px;
			box-shadow: 0 8px 18px rgba(15, 41, 107, 0.07);
		}
		.icgowa-bidang-card h5 {
			margin: 0 0 8px 0 !important;
			font-size: 0.9rem !important;
			font-weight: 600 !important;
			color: #334155 !important;
		}
		.icgowa-bidang-row {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 8px;
			margin-bottom: 12px;
		}
		.icgowa-bidang-score {
			font-size: 1.18rem;
			font-weight: 700;
			color: #0F296B;
			line-height: 1;
			white-space: nowrap;
		}
		.icgowa-detail-btn {
			width: 100%;
			background: #FFFFFF;
			color: #1536A2;
			border: 1px solid #CBD5E1;
			border-radius: 8px;
			padding: 6px 10px;
			font-size: 0.78rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		.icgowa-detail-btn:hover {
			background: #1536A2;
			color: #FFFFFF;
			border-color: #1536A2;
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
			max-width: 720px;
			width: 100%;
			max-height: 82vh;
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
			padding: 18px 22px;
			overflow-y: auto;
		}
		.icgowa-survey-table {
			width: 100%;
			border-collapse: collapse;
			font-size: 0.88rem;
		}
		.icgowa-survey-table th {
			background: #F8FAFC;
			color: #475569;
			font-weight: 600;
			text-align: left;
			padding: 10px 12px;
			border-bottom: 2px solid #E2E8F0;
		}
		.icgowa-survey-table td {
			padding: 10px 12px;
			border-bottom: 1px solid #F1F5F9;
			color: #334155;
		}

		@media (max-width: 860px) {
			.icgowa-survey-bar {
				flex-direction: column;
				align-items: flex-start;
			}
			.icgowa-tw-grid,
			.icgowa-bidang-grid {
				grid-template-columns: repeat(2, 1fr);
			}
		}
		@media (max-width: 520px) {
			.icgowa-tw-grid,
			.icgowa-bidang-grid {
				grid-template-columns: 1fr;
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

			<!-- 3. PANEL RINCIAN BIDANG LAYANAN (DENGAN BATAS KONTAINER TEGAS) -->
			<div class="icgowa-bidang-panel">
				<div class="icgowa-bidang-panel-header">
					<h4 id="icgowa-panel-title">Rincian Bidang Layanan — Triwulan III</h4>
				</div>
				<div id="icgowa-bidang-cards-container" class="icgowa-bidang-grid"></div>
			</div>

		</div>
	</div>

	<!-- MODAL POPUP INDIKATOR -->
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

		function getPredClass(pred) {
			var p = (pred || '').toLowerCase();
			if (p.indexOf('sangat') !== -1) return 'pred-sangat-puas';
			if (p.indexOf('cukup') !== -1) return 'pred-cukup-puas';
			if (p.indexOf('tidak') !== -1) return 'pred-tidak-puas';
			return 'pred-puas';
		}

		function formatNum(n) {
			return Number(n || 0).toFixed(2).replace('.', ',');
		}

		function renderActiveQuarter(tw) {
			if (!surveyData[tw]) return;
			currentTw = tw;
			var list = surveyData[tw] || [];
			var cardsContainer = document.getElementById('icgowa-bidang-cards-container');
			var panelTitle = document.getElementById('icgowa-panel-title');

			if (panelTitle && twLabels[tw]) {
				panelTitle.textContent = 'Rincian Bidang Layanan — ' + twLabels[tw];
			}

			document.querySelectorAll('.icgowa-tw-card[data-select-tw]').forEach(function(c) {
				c.classList.toggle('active-card', c.getAttribute('data-select-tw') === tw);
			});

			cardsContainer.innerHTML = list.map(function(sec, idx) {
				var pClass = getPredClass(sec.predicate);
				var cardBorderClass = (pClass === 'pred-sangat-puas') ? ' card-sangat-puas' : '';
				return '<div class="icgowa-bidang-card' + cardBorderClass + '">' +
					'<div>' +
						'<h5>' + sec.name + '</h5>' +
						'<div class="icgowa-bidang-row">' +
							'<span class="icgowa-bidang-score">' + formatNum(sec.avg_pct) + '%</span>' +
							'<span class="icgowa-pred-badge ' + pClass + '">' + sec.predicate + '</span>' +
						'</div>' +
					'</div>' +
					'<button type="button" class="icgowa-detail-btn" data-sec-idx="' + idx + '">Detail</button>' +
				'</div>';
			}).join('');
		}

		document.querySelectorAll('.icgowa-tw-card[data-select-tw]').forEach(function(card) {
			card.addEventListener('click', function() {
				renderActiveQuarter(card.getAttribute('data-select-tw'));
			});
		});

		var modal = document.getElementById('icgowa-indicator-modal');
		document.getElementById('icgowa-bidang-cards-container').addEventListener('click', function(e) {
			var btn = e.target.closest('.icgowa-detail-btn');
			if (!btn) return;
			var idx = parseInt(btn.getAttribute('data-sec-idx'), 10);
			var sec = (surveyData[currentTw] || [])[idx];
			if (!sec) return;

			document.getElementById('icgowa-indicator-modal-title').textContent = sec.name + ' — ' + twLabels[currentTw];
			var rowsHtml = (sec.indicators || []).map(function(ind) {
				return '<tr>' +
					'<td>' + ind.no + '</td>' +
					'<td>' + ind.label + '</td>' +
					'<td><strong>' + formatNum(ind.pct) + '%</strong></td>' +
					'<td><span class="icgowa-pred-badge ' + getPredClass(ind.pred) + '">' + ind.pred + '</span></td>' +
				'</tr>';
			}).join('');

			document.getElementById('icgowa-indicator-modal-body').innerHTML =
				'<table class="icgowa-survey-table">' +
					'<thead><tr><th>No.</th><th>Indikator</th><th>Nilai</th><th>Predikat</th></tr></thead>' +
					'<tbody>' + rowsHtml + '</tbody>' +
				'</table>';
			modal.classList.add('open');
		});

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
