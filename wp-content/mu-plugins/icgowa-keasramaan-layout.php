<?php
/**
 * Plugin Name: ICGowa — Tata Letak Keasramaan & Sarana Prasarana (Slider Interaktif)
 * Description: Merapikan teks halaman Bidang (rata kiri-kanan / justify), menampilkan slider Program Kerja khusus pada laman Keasramaan, serta menampilkan slider fasilitas bergambar berukuran besar pada laman Sarana Prasarana.
 * Author: ICGowa
 * Version: 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', function () {
	$handle = 'icgowa-keasramaan-slider';
	wp_register_style( $handle, false, array(), '1.2.0' );
	wp_enqueue_style( $handle );

	$css = <<<'CSS'
/* ============================================================
   1. Rata Kiri-Kanan (Justify) pada Halaman Keasramaan & Bidang
   ============================================================ */
.single-bidang- .elementor-widget-text-editor p,
.single-bidang- .elementor-widget-text-editor li,
.elementor-439 .elementor-widget-text-editor p,
.elementor-439 .elementor-widget-text-editor li {
	text-align: justify !important;
	text-justify: inter-word;
}

/* Rapikan jarak paragraf dan list di halaman Bidang */
.single-bidang- .elementor-widget-text-editor p:empty,
.elementor-439 .elementor-widget-text-editor p:empty {
	display: none;
}

.single-bidang- .elementor-widget-text-editor ol,
.single-bidang- .elementor-widget-text-editor ul,
.elementor-439 .elementor-widget-text-editor ol,
.elementor-439 .elementor-widget-text-editor ul {
	padding-left: 1.4rem;
	margin-bottom: 1.25rem;
}

.single-bidang- .elementor-widget-text-editor li,
.elementor-439 .elementor-widget-text-editor li {
	margin-bottom: 0.5rem;
}

/* ============================================================
   2. Slider Horizontal "Program Kerja" Khusus Keasramaan
   ============================================================ */
.icgowa-proker-section {
	margin-top: 2.5rem;
	margin-bottom: 1.5rem;
	position: relative;
}

.icgowa-proker-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	margin-bottom: 1.25rem;
	flex-wrap: wrap;
}

.icgowa-proker-header h3 {
	margin: 0 !important;
	font-size: 1.5rem !important;
	font-weight: 700 !important;
	color: #0F296B !important;
	position: relative;
	padding-bottom: 8px;
}

.icgowa-proker-header h3::after {
	content: "";
	position: absolute;
	left: 0;
	bottom: 0;
	width: 56px;
	height: 4px;
	border-radius: 4px;
	background: linear-gradient(90deg, #1536A2, #FFAE00);
}

.icgowa-proker-nav {
	display: flex;
	align-items: center;
	gap: 10px;
}

.icgowa-proker-nav-btn {
	width: 42px;
	height: 42px;
	border-radius: 50%;
	border: 1px solid rgba(21, 54, 162, 0.18);
	background: #FFFFFF;
	color: #0F296B;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	box-shadow: 0 4px 12px rgba(15, 41, 107, 0.08);
	transition: all 0.25s ease;
	padding: 0;
}

.icgowa-proker-nav-btn:hover:not(:disabled) {
	background: #1536A2;
	color: #FFFFFF;
	border-color: #1536A2;
	transform: translateY(-2px);
	box-shadow: 0 8px 18px rgba(21, 54, 162, 0.25);
}

.icgowa-proker-nav-btn:disabled {
	opacity: 0.4;
	cursor: not-allowed;
}

.icgowa-proker-nav-btn svg {
	width: 20px;
	height: 20px;
	stroke: currentColor;
	stroke-width: 2.2;
	fill: none;
}

/* Track Slider */
.icgowa-proker-track-wrapper {
	position: relative;
	overflow: hidden;
	margin: 0 -8px;
	padding: 8px 8px 20px;
}

.icgowa-proker-track {
	display: flex;
	gap: 20px;
	overflow-x: auto;
	scroll-snap-type: x mandatory;
	scroll-behavior: smooth;
	scrollbar-width: none;
	-ms-overflow-style: none;
	padding-bottom: 6px;
}

.icgowa-proker-track::-webkit-scrollbar {
	display: none;
}

/* Kartu Program Kerja (Keasramaan) */
.icgowa-proker-card {
	flex: 0 0 calc(33.333% - 14px);
	min-width: 270px;
	scroll-snap-align: start;
	background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFF 100%);
	border: 1px solid rgba(21, 54, 162, 0.12);
	border-radius: 16px;
	padding: 24px 22px 20px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	box-shadow: 0 8px 24px -6px rgba(15, 41, 107, 0.08);
	transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
	            box-shadow 0.3s cubic-bezier(0.22, 1, 0.36, 1),
	            border-color 0.3s ease;
	position: relative;
	overflow: hidden;
}

.icgowa-proker-card::before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: 4px;
	background: linear-gradient(90deg, #1536A2 0%, #FFAE00 100%);
	opacity: 0.85;
}

.icgowa-proker-card:hover {
	transform: translateY(-6px);
	border-color: rgba(21, 54, 162, 0.3);
	box-shadow: 0 18px 36px -8px rgba(15, 41, 107, 0.16);
}

.icgowa-proker-badge {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	border-radius: 10px;
	background: rgba(21, 54, 162, 0.09);
	color: #1536A2 !important;
	font-weight: 700;
	font-size: 0.9rem;
	margin-bottom: 14px;
}

.icgowa-proker-card-title {
	font-size: 1.08rem !important;
	font-weight: 600 !important;
	color: #0F296B !important;
	line-height: 1.4 !important;
	margin: 0 0 10px 0 !important;
	text-align: left !important;
}

.icgowa-proker-card-excerpt {
	font-size: 0.92rem !important;
	color: #475569 !important;
	line-height: 1.65 !important;
	margin: 0 0 18px 0 !important;
	text-align: justify !important;
	display: -webkit-box;
	-webkit-line-clamp: 4;
	-webkit-box-orient: vertical;
	overflow: hidden;
}

.icgowa-proker-card-footer {
	margin-top: auto;
	padding-top: 12px;
	border-top: 1px dashed rgba(15, 41, 107, 0.1);
	display: flex;
	align-items: center;
	justify-content: flex-end;
}

.icgowa-proker-more-btn,
.elementor-widget-text-editor .icgowa-proker-more-btn,
.elementor-widget-text-editor .icgowa-proker-more-btn span {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	background: #1536A2;
	color: #FFFFFF !important;
	border: none;
	border-radius: 999px;
	padding: 8px 16px;
	font-size: 0.85rem;
	font-weight: 500;
	cursor: pointer;
	transition: all 0.25s ease;
	box-shadow: 0 4px 12px rgba(21, 54, 162, 0.2);
}

.elementor-widget-text-editor .icgowa-proker-more-btn span {
	padding: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
}

.icgowa-proker-more-btn:hover,
.elementor-widget-text-editor .icgowa-proker-more-btn:hover,
.elementor-widget-text-editor .icgowa-proker-more-btn:hover span {
	background: #FFAE00;
	color: #0F296B !important;
	transform: translateX(2px);
	box-shadow: 0 6px 16px rgba(255, 174, 0, 0.35);
}

.elementor-widget-text-editor .icgowa-proker-more-btn:hover span {
	background: transparent !important;
	box-shadow: none !important;
	transform: none !important;
}

.icgowa-proker-more-btn svg {
	width: 15px;
	height: 15px;
	stroke: currentColor;
	stroke-width: 2.2;
	fill: none;
}

/* ============================================================
   3. Slider Besar Bergambar Khusus Laman SARPRAS
   ============================================================ */
.icgowa-sarpras-tabs {
	display: flex;
	flex-wrap: wrap;
	gap: 8px;
	margin-bottom: 1.35rem;
}

.icgowa-sarpras-tab,
.elementor-widget-text-editor .icgowa-sarpras-tab {
	background: #F1F5F9;
	color: #334155 !important;
	border: 1px solid #E2E8F0;
	border-radius: 999px;
	padding: 8px 16px;
	font-size: 0.85rem;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.22s ease;
}

.icgowa-sarpras-tab:hover,
.elementor-widget-text-editor .icgowa-sarpras-tab:hover {
	background: #E2E8F0;
	color: #0F296B !important;
}

.icgowa-sarpras-tab.active,
.elementor-widget-text-editor .icgowa-sarpras-tab.active {
	background: #0F296B;
	color: #FFFFFF !important;
	border-color: #0F296B;
	box-shadow: 0 4px 12px rgba(15, 41, 107, 0.2);
}

/* Kartu Slider Sarpras — Ukuran Lebih Besar dengan Foto */
.icgowa-sarpras-card {
	flex: 0 0 calc(50% - 12px);
	min-width: 340px;
	scroll-snap-align: start;
	background: #FFFFFF;
	border: 1px solid rgba(21, 54, 162, 0.14);
	border-radius: 18px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	box-shadow: 0 10px 28px -8px rgba(15, 41, 107, 0.10);
	transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
	            box-shadow 0.3s cubic-bezier(0.22, 1, 0.36, 1),
	            border-color 0.3s ease;
	position: relative;
	overflow: hidden;
}

.icgowa-sarpras-card:hover {
	transform: translateY(-6px);
	border-color: rgba(21, 54, 162, 0.32);
	box-shadow: 0 20px 40px -10px rgba(15, 41, 107, 0.18);
}

.icgowa-sarpras-card-media {
	position: relative;
	width: 100%;
	height: 220px;
	overflow: hidden;
	background: #0F296B;
}

.icgowa-sarpras-card-media img {
	width: 100% !important;
	height: 100% !important;
	object-fit: cover !important;
	display: block !important;
	transition: transform 0.45s ease;
}

.icgowa-sarpras-card:hover .icgowa-sarpras-card-media img {
	transform: scale(1.05);
}

.icgowa-sarpras-card-media::after {
	content: "";
	position: absolute;
	inset: 0;
	background: linear-gradient(180deg, rgba(15, 41, 107, 0.05) 0%, rgba(15, 41, 107, 0.55) 100%);
	pointer-events: none;
}

.icgowa-sarpras-cat-badge,
.elementor-widget-text-editor span.icgowa-sarpras-cat-badge {
	position: absolute;
	top: 14px;
	left: 14px;
	z-index: 2;
	background: rgba(15, 41, 107, 0.88);
	backdrop-filter: blur(4px);
	color: #FFAE00 !important;
	font-size: 0.75rem;
	font-weight: 600;
	padding: 5px 12px;
	border-radius: 999px;
	letter-spacing: 0.02em;
}

.icgowa-sarpras-num-badge,
.elementor-widget-text-editor span.icgowa-sarpras-num-badge {
	position: absolute;
	top: 14px;
	right: 14px;
	z-index: 2;
	width: 34px;
	height: 34px;
	border-radius: 10px;
	background: rgba(255, 255, 255, 0.92);
	color: #0F296B !important;
	font-weight: 700;
	font-size: 0.85rem;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.icgowa-sarpras-card-body {
	padding: 22px 24px 18px;
	display: flex;
	flex-direction: column;
	flex-grow: 1;
	justify-content: space-between;
}

.icgowa-sarpras-card-title {
	font-size: 1.18rem !important;
	font-weight: 700 !important;
	color: #0F296B !important;
	line-height: 1.38 !important;
	margin: 0 0 10px 0 !important;
	text-align: left !important;
}

.icgowa-sarpras-card-excerpt {
	font-size: 0.93rem !important;
	color: #475569 !important;
	line-height: 1.65 !important;
	margin: 0 0 16px 0 !important;
	text-align: justify !important;
	display: -webkit-box;
	-webkit-line-clamp: 3;
	-webkit-box-orient: vertical;
	overflow: hidden;
}

/* Tabel Program Kerja di Laman Sarpras */
.icgowa-sarpras-table-wrap {
	margin-top: 1rem;
	overflow-x: auto;
	border-radius: 14px;
	border: 1px solid #E2E8F0;
	box-shadow: 0 4px 16px rgba(15, 41, 107, 0.05);
}

.icgowa-sarpras-table-wrap table {
	width: 100%;
	border-collapse: collapse;
	margin: 0 !important;
	font-size: 0.92rem;
}

.icgowa-sarpras-table-wrap tr:first-child td,
.icgowa-sarpras-table-wrap thead th {
	background: #0F296B !important;
	color: #FFFFFF !important;
	font-weight: 600 !important;
	padding: 13px 16px !important;
	border: none !important;
	text-align: left !important;
}

.icgowa-sarpras-table-wrap tr:first-child td strong {
	color: #FFFFFF !important;
}

.icgowa-sarpras-table-wrap tr:not(:first-child) td {
	padding: 12px 16px !important;
	border-bottom: 1px solid #E2E8F0 !important;
	color: #334155 !important;
	vertical-align: top;
}

.icgowa-sarpras-table-wrap tr:nth-child(even) td {
	background: #F8FAFC;
}

@media (max-width: 991px) {
	.icgowa-proker-card {
		flex: 0 0 calc(50% - 10px);
	}
	.icgowa-sarpras-card {
		flex: 0 0 calc(85% - 10px);
		min-width: 300px;
	}
}

@media (max-width: 640px) {
	.icgowa-proker-card,
	.icgowa-sarpras-card {
		flex: 0 0 90%;
		min-width: 260px;
	}
	.icgowa-sarpras-card-media {
		height: 185px;
	}
}

/* ============================================================
   4. Modal Detail "More Info"
   ============================================================ */
.icgowa-proker-modal-backdrop {
	position: fixed;
	inset: 0;
	background: rgba(10, 25, 68, 0.65);
	backdrop-filter: blur(4px);
	z-index: 99999;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 20px;
	opacity: 0;
	visibility: hidden;
	transition: opacity 0.25s ease, visibility 0.25s ease;
}

.icgowa-proker-modal-backdrop.open {
	opacity: 1;
	visibility: visible;
}

.icgowa-proker-modal {
	background: #FFFFFF;
	border-radius: 20px;
	max-width: 680px;
	width: 100%;
	max-height: 86vh;
	display: flex;
	flex-direction: column;
	box-shadow: 0 25px 60px -15px rgba(10, 25, 68, 0.45);
	transform: translateY(16px) scale(0.98);
	transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1);
	overflow: hidden;
}

.icgowa-proker-modal-backdrop.open .icgowa-proker-modal {
	transform: translateY(0) scale(1);
}

.icgowa-proker-modal-header {
	padding: 20px 26px;
	background: linear-gradient(135deg, #0F296B 0%, #1536A2 100%);
	color: #FFFFFF;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
}

.icgowa-proker-modal-header-title-wrap {
	display: flex;
	align-items: center;
	gap: 12px;
}

.icgowa-proker-modal-badge {
	background: #FFAE00;
	color: #0F296B !important;
	font-weight: 700;
	font-size: 0.82rem;
	padding: 4px 10px;
	border-radius: 8px;
	flex-shrink: 0;
}

.icgowa-proker-modal-title {
	margin: 0 !important;
	font-size: 1.18rem !important;
	font-weight: 600 !important;
	color: #FFFFFF !important;
	line-height: 1.35 !important;
}

.icgowa-proker-modal-close {
	background: rgba(255, 255, 255, 0.14);
	color: #FFFFFF;
	border: none;
	width: 36px;
	height: 36px;
	border-radius: 50%;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: 1.2rem;
	cursor: pointer;
	transition: background 0.2s ease;
	flex-shrink: 0;
}

.icgowa-proker-modal-close:hover {
	background: rgba(255, 255, 255, 0.28);
}

.icgowa-proker-modal-body {
	padding: 24px 28px;
	overflow-y: auto;
	color: #334155;
	font-size: 0.98rem;
	line-height: 1.78;
}

.icgowa-proker-modal-photo {
	width: 100%;
	height: 250px;
	object-fit: cover;
	border-radius: 12px;
	margin-bottom: 18px;
	display: block;
}

.icgowa-proker-modal-body p,
.icgowa-proker-modal-body li {
	text-align: justify !important;
	color: #334155 !important;
}

.icgowa-proker-modal-body h4 {
	color: #0F296B !important;
	font-size: 1.02rem !important;
	font-weight: 600 !important;
	margin: 1rem 0 0.4rem !important;
}

.icgowa-proker-modal-body ul,
.icgowa-proker-modal-body ol {
	padding-left: 1.3rem;
	margin: 0.5rem 0 1rem;
}

.icgowa-proker-modal-body li {
	margin-bottom: 0.4rem;
}

.icgowa-proker-modal-footer {
	padding: 14px 26px;
	background: #F8FAFC;
	border-top: 1px solid #E2E8F0;
	display: flex;
	justify-content: flex-end;
}

.icgowa-proker-modal-close-btn {
	background: #0F296B;
	color: #FFFFFF;
	border: none;
	border-radius: 999px;
	padding: 8px 22px;
	font-size: 0.9rem;
	font-weight: 500;
	cursor: pointer;
	transition: background 0.2s ease;
}

.icgowa-proker-modal-close-btn:hover {
	background: #1536A2;
}
CSS;

	wp_add_inline_style( $handle, $css );
}, 1000 );

/**
 * Injeksi script di footer:
 * 1) Hanya pada laman Keasramaan (/bidang-/keasramaan/): ubah PROGRAM KERJA menjadi slider.
 * 2) Hanya pada laman Sarpras (/bidang-/sarana-prasarana/): ubah bagian-bagian Sarana menjadi slider besar bergambar.
 */
add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}
	?>
	<script>
	(function() {
		function createSharedModal() {
			var existing = document.getElementById('icgowa-shared-modal-backdrop');
			if (existing) return existing;

			var modalBackdrop = document.createElement('div');
			modalBackdrop.id = 'icgowa-shared-modal-backdrop';
			modalBackdrop.className = 'icgowa-proker-modal-backdrop';
			modalBackdrop.innerHTML =
				'<div class="icgowa-proker-modal" role="dialog" aria-modal="true">' +
					'<div class="icgowa-proker-modal-header">' +
						'<div class="icgowa-proker-modal-header-title-wrap">' +
							'<span class="icgowa-proker-modal-badge" id="icgowa-modal-badge">01</span>' +
							'<h3 class="icgowa-proker-modal-title" id="icgowa-modal-title">Judul</h3>' +
						'</div>' +
						'<button type="button" class="icgowa-proker-modal-close" aria-label="Tutup">&times;</button>' +
					'</div>' +
					'<div class="icgowa-proker-modal-body" id="icgowa-modal-body"></div>' +
					'<div class="icgowa-proker-modal-footer">' +
						'<button type="button" class="icgowa-proker-modal-close-btn">Tutup</button>' +
					'</div>' +
				'</div>';
			document.body.appendChild(modalBackdrop);

			function closeModal() {
				modalBackdrop.classList.remove('open');
				document.body.style.overflow = '';
			}

			modalBackdrop.addEventListener('click', function(e) {
				if (e.target === modalBackdrop || e.target.closest('.icgowa-proker-modal-close') || e.target.closest('.icgowa-proker-modal-close-btn')) {
					closeModal();
				}
			});

			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && modalBackdrop.classList.contains('open')) {
					closeModal();
				}
			});

			return modalBackdrop;
		}

		/* ============================================================
		   A. SLIDER PROGRAM KERJA — KHUSUS LAMAN KEASRAMAAN SAJA
		   ============================================================ */
		function initKeasramaanProkerSlider() {
			var path = (window.location.pathname || '').toLowerCase();
			var isKeasramaan = path.indexOf('/keasramaan') !== -1 || document.body.classList.contains('postid-435');
			if (!isKeasramaan) return;

			var container = document.querySelector('.elementor-439 .elementor-widget-text-editor') ||
			                document.querySelector('.single-bidang- .elementor-widget-text-editor');
			if (!container) return;

			var headings = container.querySelectorAll('h3, h2, h4, p');
			var prokerHeading = null;
			for (var i = 0; i < headings.length; i++) {
				var txt = (headings[i].textContent || '').replace(/\s+/g, ' ').trim().toUpperCase();
				if (txt === 'PROGRAM KERJA') {
					prokerHeading = headings[i];
					break;
				}
			}
			if (!prokerHeading) return;

			var prokerItems = [
				{
					num: '01',
					title: "Ta'lim Al-Qur'an dan Hadits",
					excerpt: "Program pembinaan utama yang meliputi Tahsin Al-Qur'an, Tahfizh Al-Qur'an (minimal 3 juz & ayat tematik), Simaan Al-Qur'an, serta Tahfizhul Hadis Arba'in An-Nawawiyyah.",
					detail: "<p>Program pembinaan Al-Qur'an dan Hadits di asrama mencakup empat kegiatan terpadu:</p>" +
					        "<h4>1. Tahsin Al-Qur'an</h4>" +
					        "<p>Pada saat awal masuk madrasah peserta didik baru mengikuti matrikulasi keagamaan untuk diuji kemampuan bacaan Al-Qur'an. Hal ini ditujukan agar peserta didik mampu membaca Al-Qur'an dengan baik dan benar. Selanjutnya peserta didik akan dikelompokkan sesuai kemampuan bacaannya selama satu semester sebelum mengikuti program Tahfizh Al-Qur'an.</p>" +
					        "<h4>2. Tahfizh Al-Qur'an</h4>" +
					        "<p>Dilaksanakan setelah shalat Maghrib dan shalat Subuh bersama pembina asrama dengan target capaian sesuai jenjang, di mana saat kelulusan peserta didik minimal mencapai 3 juz serta menghafal ayat-ayat tematik sains dan humaniora.</p>" +
					        "<h4>3. Simaan Al-Qur'an</h4>" +
					        "<p>Proses mempelajari dan menguasai cara membaca Al-Qur'an dengan tartil serta sesuai dengan kaidah ilmu tajwid yang baik dan benar.</p>" +
					        "<h4>4. Tahfizhul Hadis</h4>" +
					        "<p>Kegiatan menghafalkan hadits-hadits dalam kitab <em>Al-Arba'in An-Nawawiyyah</em> karya Imam Nawawi selama tiga tahun di MAN Insan Cendekia Gowa, rutin dilaksanakan satu kali sepekan melibatkan seluruh guru agama.</p>"
				},
				{
					num: '02',
					title: "Kajian Kitab (Qira'atul Kutub)",
					excerpt: "Kajian kitab kuning yang dilaksanakan secara massal di Masjid dua kali sepekan mencakup Aqidatul Awam, Ta'lim al-Muta'allim, Safinatun Najah, Barzanji, dan Ushul Fikih.",
					detail: "<p>Kajian kitab kuning dilaksanakan secara massal di Masjid dua kali dalam sepekan, dengan membaca kitab:</p>" +
					        "<ul>" +
					        "<li><strong>Aqidatul Awam</strong> untuk kelas X</li>" +
					        "<li><strong>Ta'lim al-Muta'allim</strong> untuk kelas X, XI, dan XII</li>" +
					        "<li><strong>Safinatun Najah &amp; Ta'lim al-Muta'allim</strong> untuk kelas XI</li>" +
					        "<li><strong>Barzanji</strong> untuk seluruh siswa MAN IC Gowa</li>" +
					        "<li><strong>Ushul Fikih</strong> untuk kelas XII</li>" +
					        "</ul>" +
					        "<p>Bagi peserta didik yang ingin memperdalam lebih lanjut tentang kajian kitab kuning, disediakan waktu pembinaan tambahan tersendiri.</p>"
				},
				{
					num: '03',
					title: "Pembinaan Ibadah Amaliyah",
					excerpt: "Pembiasaan ibadah harian peserta didik meliputi shalat fardhu berjamaah, shalat sunnah rawatib, tahajud, dhuha, serta puasa Ramadhan dan puasa sunnah.",
					detail: "<p>Peserta didik dibimbing untuk membiasakan dan melaksanakan ibadah amaliyah harian yang mencakup:</p>" +
					        "<ol>" +
					        "<li>Shalat lima waktu berjamaah di masjid, shalat sunnah rawatib, shalat tahajud, dan shalat dhuha.</li>" +
					        "<li>Puasa Ramadhan, puasa sunnah Senin-Kamis, puasa Daud, puasa Ayyamul Bidh, serta puasa sunnah lainnya.</li>" +
					        "</ol>"
				},
				{
					num: '04',
					title: "Pembinaan Imam Shalat",
					excerpt: "Pembekalan bagi siswa kelas X dan seleksi imam masjid pada awal kelas XI agar peserta didik memiliki kesiapan memimpin shalat fardhu di madrasah maupun masyarakat.",
					detail: "<p>Program pembinaan ini diberikan bagi peserta didik kelas X, dilanjutkan dengan seleksi imam masjid pada awal kelas XI. Program ini ditujukan agar para siswa mempunyai bekal yang matang untuk menjadi imam shalat di tengah masyarakat.</p>" +
					        "<p>Setelah terpilih sebagai imam masjid, peserta didik diberi kesempatan terjadwal untuk memimpin pelaksanaan shalat fardhu berjamaah.</p>"
				},
				{
					num: '05',
					title: "Pembinaan Khatib Jum'at & Tausiyah",
					excerpt: "Pelatihan dan pembimbingan peserta didik kelas X, XI, dan XII untuk tampil sebagai khatib Jum'at di mimbar masjid sesuai jadwal yang ditentukan.",
					detail: "<p>Untuk memberikan kesiapan dan keberanian tampil di masyarakat, peserta didik kelas X, XI, dan XII dibimbing secara khusus untuk menjadi khatib Jum'at di Masjid pada jadwal yang telah ditentukan.</p>" +
					        "<p>Peserta didik dilatih dan dibimbing terlebih dahulu oleh para guru sebelum tampil di mimbar. Setiap peserta didik mendapatkan kesempatan minimal satu kali untuk tampil berkhutbah di mimbar.</p>"
				},
				{
					num: '06',
					title: "Taushiah",
					excerpt: "Kegiatan ceramah agama oleh peserta didik di masjid yang dilaksanakan secara terjadwal setelah shalat Ashar usai kegiatan pembelajaran reguler.",
					detail: "<p>Taushiah merupakan kegiatan latihan dakwah dan ceramah agama yang dilakukan oleh peserta didik di masjid. Kegiatan ini dilaksanakan secara terjadwal setelah pelaksanaan shalat Ashar selesai kegiatan pembelajaran reguler.</p>"
				},
				{
					num: '07',
					title: "Pendampingan Belajar Mandiri",
					excerpt: "Pendampingan dan pengawasan kegiatan belajar mandiri peserta didik selama 5 hari sepekan secara bergilir oleh guru MAN Insan Cendekia Gowa.",
					detail: "<p>Agar kegiatan pembelajaran mandiri peserta didik di asrama berjalan optimal, dilakukan pendampingan dan pengawasan belajar mandiri oleh guru MAN Insan Cendekia Gowa secara bergilir selama 5 hari dalam sepekan.</p>" +
					        "<p>Tempat kegiatan belajar mandiri meliputi asrama, masjid/serambi masjid, ruang kelas, gazebo, dan area kondusif lainnya.</p>" +
					        "<h4>Tugas Guru Piket Pendamping:</h4>" +
					        "<ol>" +
					        "<li>Memberikan pelayanan konsultasi dan bimbingan kepada peserta didik dalam pembelajaran tambahan.</li>" +
					        "<li>Memastikan serta menertibkan suasana belajar mandiri agar senantiasa tertib dan kondusif.</li>" +
					        "</ol>"
				},
				{
					num: '08',
					title: "Program Guru Asuh",
					excerpt: "Pendampingan kekeluargaan oleh guru sebagai pengganti orang tua di asrama untuk memantau perkembangan akademik, karakter, dan kesejahteraan siswa.",
					detail: "<p>Karena seluruh peserta didik MAN Insan Cendekia Gowa tinggal di asrama, diperlukan peran guru sebagai pengganti orang tua melalui program <strong>Guru Asuh</strong> agar perkembangan jiwa dan karakter peserta didik terpantau dengan baik.</p>" +
					        "<h4>Kegiatan Guru Asuh:</h4>" +
					        "<ol>" +
					        "<li>Pertemuan rutin (makan siang bersama dan kunjungan ke asrama) untuk mengarahkan peserta didik dalam kehidupan berasrama.</li>" +
					        "<li>Sharing permasalahan yang dihadapi peserta didik, baik akademik maupun sosial.</li>" +
					        "<li>Membimbing peserta didik berpikir dewasa dalam penyelesaian masalah pribadi dan sosial.</li>" +
					        "</ol>" +
					        "<h4>Peran Guru Asuh:</h4>" +
					        "<ul>" +
					        "<li>Sebagai pembimbing dan pengawas siswa di asrama.</li>" +
					        "<li>Sebagai <em>role model</em> (teladan) bagi siswa.</li>" +
					        "<li>Sebagai fasilitator dalam proses pembelajaran dan pengembangan potensi siswa secara optimal.</li>" +
					        "</ul>"
				},
				{
					num: '09',
					title: "Apresiasi Keasramaan",
					excerpt: "Pemberian penghargaan (reward) bulanan bagi peserta didik berpredikat baik berupa izin bermalam bersama keluarga selama 3 hari 2 malam.",
					detail: "<p>Bidang Keasramaan memberikan apresiasi kepada peserta didik berupa <em>reward</em> bulanan bagi siswa yang mendapatkan penilaian sangat baik dari guru bina asrama.</p>" +
					        "<p>Bentuk apresiasi tersebut berupa izin bermalam di rumah masing-masing selama 3 hari 2 malam sebagai motivasi positif dalam menjaga kedisiplinan dan akhlak di asrama.</p>"
				},
				{
					num: '10',
					title: "Peringatan Hari Besar Islam",
					excerpt: "Penyelenggaraan syiar hari besar umat Islam seperti Maulid Nabi Muhammad SAW, Hari Santri, Tahun Baru Islam, dan Isra' Mi'raj di lingkungan madrasah.",
					detail: "<p>Peringatan Hari Besar Umat Islam (PHBI) dilaksanakan secara semarak dan khidmat di MAN Insan Cendekia Gowa, meliputi peringatan Maulid Nabi Muhammad SAW, Hari Santri Nasional, Tahun Baru Islam, Isra' Mi'raj, dan kegiatan syiar keislaman lainnya.</p>"
				},
				{
					num: '11',
					title: "Pembinaan Kebahasaan",
					excerpt: "Pengembangan penguasaan Bahasa Arab dan Bahasa Inggris melalui penguasaan mufradat/vocabulary dan pembiasaan percakapan harian di seluruh lingkungan kampus.",
					detail: "<p>Pembinaan kebahasaan di Asrama MAN Insan Cendekia Gowa bertujuan untuk:</p>" +
					        "<ol>" +
					        "<li>Meningkatkan dan mengembangkan kemampuan berbahasa asing peserta didik.</li>" +
					        "<li>Meningkatkan penguasaan Bahasa Arab dan Bahasa Inggris untuk menunjang pembelajaran.</li>" +
					        "<li>Memberi bekal keterampilan komunikasi global bagi peserta didik.</li>" +
					        "</ol>" +
					        "<h4>Target Capaian:</h4>" +
					        "<ol>" +
					        "<li>Siswa menguasai mufradat/kosakata di lingkungan asrama, masjid, kantin, kelas, kantor, dan perpustakaan.</li>" +
					        "<li>Siswa mempraktikkan percakapan Bahasa Arab dan Inggris dalam komunikasi sehari-hari di dalam maupun luar kampus.</li>" +
					        "</ol>"
				},
				{
					num: '12',
					title: "Tadarus Bersama Guru & Tendik",
					excerpt: "Kegiatan membaca dan mengkaji Al-Qur'an bersama antara guru dan tenaga kependidikan untuk memperkuat karakter Islami dan spiritualitas civitas madrasah.",
					detail: "<p>Tadarus Bersama Guru dan Tenaga Kependidikan adalah kegiatan membaca serta mempelajari Al-Qur'an bersama-sama di lingkungan madrasah yang bertujuan untuk:</p>" +
					        "<ol>" +
					        "<li><strong>Meningkatkan Pemahaman Al-Qur'an:</strong> Memperdalam pemahaman isi dan kandungan makna Al-Qur'an.</li>" +
					        "<li><strong>Membentuk Karakter Islami:</strong> Menumbuhkan kesadaran pengamalan Al-Qur'an dalam keteladanan sehari-hari.</li>" +
					        "<li><strong>Meningkatkan Spiritualitas:</strong> Memperkuat ikatan spiritual dan kebersamaan seluruh guru serta staf madrasah.</li>" +
					        "</ol>"
				}
			];

			var nextEl = prokerHeading.nextElementSibling;
			while (nextEl) {
				var toRemove = nextEl;
				nextEl = nextEl.nextElementSibling;
				toRemove.parentNode.removeChild(toRemove);
			}

			var section = document.createElement('div');
			section.className = 'icgowa-proker-section';

			var cardsHtml = prokerItems.map(function(item, idx) {
				return '<div class="icgowa-proker-card">' +
					'<div>' +
						'<span class="icgowa-proker-badge">' + item.num + '</span>' +
						'<h4 class="icgowa-proker-card-title">' + item.title + '</h4>' +
						'<p class="icgowa-proker-card-excerpt">' + item.excerpt + '</p>' +
					'</div>' +
					'<div class="icgowa-proker-card-footer">' +
						'<button type="button" class="icgowa-proker-more-btn" data-proker-idx="' + idx + '">' +
							'<span>More Info</span>' +
							'<svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>' +
						'</button>' +
					'</div>' +
				'</div>';
			}).join('');

			section.innerHTML =
				'<div class="icgowa-proker-header">' +
					'<h3>PROGRAM KERJA</h3>' +
					'<div class="icgowa-proker-nav">' +
						'<button type="button" class="icgowa-proker-nav-btn proker-prev" aria-label="Geser ke kiri">' +
							'<svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>' +
						'</button>' +
						'<button type="button" class="icgowa-proker-nav-btn proker-next" aria-label="Geser ke kanan">' +
							'<svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>' +
						'</button>' +
					'</div>' +
				'</div>' +
				'<div class="icgowa-proker-track-wrapper">' +
					'<div class="icgowa-proker-track">' + cardsHtml + '</div>' +
				'</div>';

			prokerHeading.parentNode.replaceChild(section, prokerHeading);

			var modalBackdrop = createSharedModal();
			var track = section.querySelector('.icgowa-proker-track');
			var prevBtn = section.querySelector('.proker-prev');
			var nextBtn = section.querySelector('.proker-next');

			function getScrollStep() {
				var firstCard = track.querySelector('.icgowa-proker-card');
				return firstCard ? (firstCard.offsetWidth + 20) : 320;
			}

			prevBtn.addEventListener('click', function() {
				track.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
			});

			nextBtn.addEventListener('click', function() {
				track.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
			});

			section.addEventListener('click', function(e) {
				var btn = e.target.closest('.icgowa-proker-more-btn');
				if (btn) {
					var idx = parseInt(btn.getAttribute('data-proker-idx'), 10);
					var item = prokerItems[idx];
					if (!item) return;
					document.getElementById('icgowa-modal-badge').textContent = 'Program ' + item.num;
					document.getElementById('icgowa-modal-title').textContent = item.title;
					document.getElementById('icgowa-modal-body').innerHTML = item.detail;
					modalBackdrop.classList.add('open');
					document.body.style.overflow = 'hidden';
				}
			});
		}

		/* ============================================================
		   B. SLIDER BESAR BERGAMBAR — KHUSUS LAMAN SARPRAS SAJA
		   ============================================================ */
		function initSarprasSlider() {
			var path = (window.location.pathname || '').toLowerCase();
			var isSarpras = path.indexOf('/sarana-prasarana') !== -1 || document.body.classList.contains('postid-433');
			if (!isSarpras) return;

			var container = document.querySelector('.elementor-439 .elementor-widget-text-editor') ||
			                document.querySelector('.single-bidang- .elementor-widget-text-editor');
			if (!container) return;

			// Cari heading Fasilitas pertama ("Fasilitas Pembelajaran:") dan heading "Program Kerja"
			var headings = container.querySelectorAll('h3, h2, h4');
			var firstFacilityHeading = null;
			var prokerHeading = null;

			for (var i = 0; i < headings.length; i++) {
				var txt = (headings[i].textContent || '').replace(/\s+/g, ' ').trim().toUpperCase();
				if (!firstFacilityHeading && txt.indexOf('FASILITAS PEMBELAJARAN') !== -1) {
					firstFacilityHeading = headings[i];
				}
				if (txt === 'PROGRAM KERJA') {
					prokerHeading = headings[i];
				}
			}

			if (!firstFacilityHeading) return;

			var baseUpload = '/wp-content/uploads/2025/11/';

			var sarprasItems = [
				{
					num: '01',
					cat: 'pembelajaran',
					catLabel: 'Fasilitas Pembelajaran',
					title: 'Ruang Kelas Nyaman & Interaktif',
					img: baseUpload + 'Screen-Shot-2025-11-12-at-13.17.11.png',
					excerpt: 'Setiap ruang kelas dilengkapi pendingin udara, proyektor/layar interaktif, papan tulis memadai, serta tata ruang fleksibel untuk berbagai metode pembelajaran abad ke-21.',
					detail: '<p>Setiap ruang kelas di MAN Insan Cendekia Gowa dirancang secara modern dan dilengkapi fasilitas penunjang pembelajaran efektif, meliputi:</p>' +
					        '<ul>' +
					        '<li>Pendingin udara (AC) dan pencahayaan alami yang optimal.</li>' +
					        '<li>Proyektor dan layar interaktif untuk mendukung presentasi multimedia.</li>' +
					        '<li>Tata ruang yang fleksibel untuk diskusi kelompok, debat, dan pembelajaran kolaboratif.</li>' +
					        '</ul>'
				},
				{
					num: '02',
					cat: 'pembelajaran',
					catLabel: 'Fasilitas Pembelajaran',
					title: 'Laboratorium Ilmu Pengetahuan Alam (IPA)',
					img: baseUpload + 'Screen-Shot-2025-11-22-at-12.52.58.png',
					excerpt: 'Laboratorium Fisika, Kimia, dan Biologi dengan peralatan praktikum serta bahan eksperimen lengkap untuk menunjang pemahaman konsep ilmiah secara mendalam.',
					detail: '<p>Fasilitas Laboratorium IPA terdiri atas <strong>Laboratorium Fisika, Kimia, dan Biologi</strong> yang dilengkapi peralatan praktikum berstandar serta bahan eksperimen lengkap.</p>' +
					        '<p>Laboratorium ini digunakan untuk praktikum reguler, riset karya ilmiah remaja (KIR), serta pembinaan olimpiade sains tingkat nasional maupun internasional.</p>'
				},
				{
					num: '03',
					cat: 'pembelajaran',
					catLabel: 'Fasilitas Pembelajaran',
					title: 'Laboratorium Komputer, TIK & Bahasa',
					img: baseUpload + 'Screen-Shot-2025-11-22-at-12.50.03.png',
					excerpt: 'Dilengkapi perangkat komputer terkini, jaringan internet berkecepatan tinggi, serta perangkat audio-visual interaktif untuk penguasaan teknologi digital dan bahasa asing.',
					detail: '<p>Mencakup dua laboratorium modern yang saling menunjang:</p>' +
					        '<h4>1. Laboratorium Komputer dan TIK</h4>' +
					        '<p>Dilengkapi komputer dengan akses internet berkecepatan tinggi dan perangkat lunak terkini untuk pembelajaran informatika, pemrograman, serta asesmen berbasis komputer.</p>' +
					        '<h4>2. Laboratorium Bahasa</h4>' +
					        '<p>Dilengkapi perangkat audio-visual interaktif untuk menunjang kemahiran berbahasa Indonesia, Bahasa Arab, dan Bahasa Inggris secara efektif.</p>'
				},
				{
					num: '04',
					cat: 'pembelajaran',
					catLabel: 'Fasilitas Pembelajaran',
					title: 'Pusat Pembelajaran Terpadu & Perpustakaan',
					img: baseUpload + 'Screen-Shot-2025-11-12-at-12.25.44.png',
					excerpt: 'Menyediakan koleksi buku pelajaran, referensi ilmiah, jurnal, sumber belajar digital, ruang baca nyaman, akses internet, serta ruang teater multimedia.',
					detail: '<p>Gedung Pusat Pembelajaran Terpadu dan Perpustakaan MAN Insan Cendekia Gowa menyediakan:</p>' +
					        '<ul>' +
					        '<li><strong>Perpustakaan Representatif:</strong> Koleksi lengkap buku teks, referensi olimpiade, fiksi, non-fiksi, jurnal, serta katalog dan literatur digital dengan ruang baca ber-AC dan ruang diskusi.</li>' +
					        '<li><strong>Ruang Teater &amp; Multimedia:</strong> Digunakan untuk pemutaran film dokumenter pembelajaran, seminar, presentasi riset siswa, dan pelatihan.</li>' +
					        '</ul>'
				},
				{
					num: '05',
					cat: 'pembelajaran',
					catLabel: 'Fasilitas Pembelajaran',
					title: 'Masjid Kampus yang Representatif',
					img: baseUpload + 'Screen-Shot-2025-11-22-at-12.43.54.png',
					excerpt: 'Pusat kegiatan ibadah dan pembinaan spiritual civitas madrasah untuk shalat berjamaah lima waktu, tahfizh Al-Qur\'an, kajian kitab kuning, dan syiar Islam.',
					detail: '<p>Masjid Kampus MAN Insan Cendekia Gowa berfungsi sebagai jantung pembinaan keagamaan dan karakter peserta didik.</p>' +
					        '<p>Dilengkapi ruang ibadah utama yang luas dan sejuk, sarana wudhu yang memadai, serta sistem tata suara yang mendukung kegiatan shalat fardhu berjamaah, halaqah tahfizh, kajian kitab kuning (<em>Qira\'atul Kutub</em>), dan latihan khutbah/taushiah.</p>'
				},
				{
					num: '06',
					cat: 'pembelajaran',
					catLabel: 'Fasilitas Pembelajaran',
					title: 'Aula Serbaguna & Ruang Seni Budaya',
					img: baseUpload + 'Screen-Shot-2025-11-12-at-16.17.11.png',
					excerpt: 'Fasilitas aula kapasitas besar untuk seminar, pelatihan, dan pertemuan orang tua, serta ruang khusus pengembangan minat bakat seni rupa, musik, dan tari.',
					detail: '<p>Mendukung pengembangan akademik, kepemimpinan, serta apresiasi seni budaya peserta didik melalui:</p>' +
					        '<ul>' +
					        '<li><strong>Aula Serbaguna:</strong> Ruang berkapasitas besar untuk kegiatan resmi madrasah, seminar nasional, workshop, pentas seni, dan silaturahmi orang tua/wali.</li>' +
					        '<li><strong>Ruang Seni dan Budaya:</strong> Ruang latihan dan kreasi yang dilengkapi peralatan seni rupa, musik, dan seni pertunjukan.</li>' +
					        '</ul>'
				},
				{
					num: '07',
					cat: 'asrama',
					catLabel: 'Fasilitas Asrama',
					title: 'Gedung Asrama Putra & Putri',
					img: baseUpload + 'Screen-Shot-2025-11-12-at-12.38.12.png',
					excerpt: 'Kamar asrama yang nyaman, aman, dan terawat lengkap dengan tempat tidur, lemari pakaian, meja belajar tiap siswa, ruang belajar bersama, dan ruang makan sehat.',
					detail: '<p>Sebagai madrasah berasrama (<em>boarding school</em>), MAN Insan Cendekia Gowa menyediakan fasilitas hunian terpadu yang aman dan kondusif:</p>' +
					        '<ul>' +
					        '<li><strong>Kamar Asrama Nyaman &amp; Aman:</strong> Dilengkapi tempat tidur, lemari pakaian, dan meja belajar untuk setiap peserta didik di bawah pendampingan guru bina asrama.</li>' +
					        '<li><strong>Ruang Makan &amp; Dapur Bersama:</strong> Ruang makan bersih dan higienis yang menyajikan menu sehat bergizi setiap hari.</li>' +
					        '<li><strong>Ruang Belajar Bersama:</strong> Area kolaborasi untuk belajar mandiri maupun diskusi kelompok di malam hari.</li>' +
					        '<li><strong>Kamar Mandi &amp; Sanitasi:</strong> Fasilitas sanitasi bersih dan memadai di setiap lantai asrama.</li>' +
					        '</ul>'
				},
				{
					num: '08',
					cat: 'olahraga',
					catLabel: 'Olahraga & Kesehatan',
					title: 'Lapangan Olahraga & GOR Indoor',
					img: baseUpload + 'Screen-Shot-2025-11-12-at-12.55.57.png',
					excerpt: 'Sarana olahraga luar ruangan (sepak bola, bola voli, basket, atletik) serta Gedung Olahraga (GOR) Indoor untuk menjaga kebugaran dan prestasi atletik siswa.',
					detail: '<p>Menunjang kesehatan jasmani serta pembinaan ekstrakurikuler olahraga melalui:</p>' +
					        '<ul>' +
					        '<li><strong>Lapangan Olahraga Outdoor:</strong> Fasilitas lapangan sepak bola/futsal, bola voli, bola basket, panahan, dan atletik.</li>' +
					        '<li><strong>Gedung Olahraga (GOR) Indoor:</strong> Fasilitas olahraga dalam ruangan untuk bulu tangkis, bela diri, tenis meja, dan senam kebugaran.</li>' +
					        '</ul>'
				},
				{
					num: '09',
					cat: 'olahraga',
					catLabel: 'Olahraga & Kesehatan',
					title: 'Ruang UKS & Layanan Kesehatan',
					img: baseUpload + 'Screen-Shot-2025-11-22-at-12.55.30.png',
					excerpt: 'Unit Kesehatan Sekolah (UKS) yang dilengkapi peralatan medis dasar, ruang istirahat pasien, serta tenaga kesehatan untuk pertolongan pertama dan pemantauan kesehatan.',
					detail: '<p>Ruang Usaha Kesehatan Sekolah (UKS) menyediakan layanan pemantauan kesehatan harian peserta didik di asrama maupun jam sekolah, dilengkapi tempat tidur pemeriksaan, peralatan medis dasar, obat-obatan P3K, serta pendampingan tenaga kesehatan dan kader PMR.</p>'
				},
				{
					num: '10',
					cat: 'pendukung',
					catLabel: 'Fasilitas Pendukung',
					title: 'Ruang Pimpinan, Guru, Tata Usaha & Konseling',
					img: baseUpload + 'Screen-Shot-2025-11-12-at-12.35.48.png',
					excerpt: 'Ruang kerja representatif bagi Kepala Madrasah, guru, staf administrasi Tata Usaha/PTSP, serta ruang Bimbingan dan Konseling (BK) yang ramah siswa.',
					detail: '<p>Fasilitas perkantoran dan layanan pendampingan akademik meliputi:</p>' +
					        '<ul>' +
					        '<li><strong>Ruang Kepala Madrasah &amp; Tata Usaha:</strong> Pusat administrasi dan Pelayanan Terpadu Satu Pintu (PTSP).</li>' +
					        '<li><strong>Ruang Guru dan Staf:</strong> Ruang kerja nyaman untuk persiapan pengajaran dan konsultasi akademik.</li>' +
					        '<li><strong>Ruang Konseling:</strong> Ruang khusus layanan bimbingan konseling karir, akademik, dan psikologis siswa.</li>' +
					        '</ul>'
				},
				{
					num: '11',
					cat: 'pendukung',
					catLabel: 'Fasilitas Pendukung',
					title: 'Taman, Ruang Terbuka Hijau & Lingkungan Kampus',
					img: baseUpload + 'Screen-Shot-2025-11-22-at-12.45.08.png',
					excerpt: 'Lingkungan kampus hijau yang asri dilengkapi taman baca, kantin sehat, area parkir luas, jaringan internet Free Wi-Fi seluruh area, serta keamanan CCTV 24 jam.',
					detail: '<p>Fasilitas pendukung kenyamanan dan keamanan seluruh warga madrasah meliputi:</p>' +
					        '<ul>' +
					        '<li><strong>Taman &amp; Ruang Terbuka Hijau:</strong> Lingkungan asri dan sejuk untuk beristirahat serta belajar di ruang terbuka.</li>' +
					        '<li><strong>Kantin Sekolah Sehat:</strong> Menyediakan makanan dan minuman sehat bergizi.</li>' +
					        '<li><strong>Akses Internet Free Wi-Fi:</strong> Jaringan internet di area pembelajaran dan kampus.</li>' +
					        '<li><strong>Area Parkir Luas &amp; Sistem Keamanan 24 Jam:</strong> Dilengkapi pemantauan CCTV dan petugas keamanan di seluruh area kampus.</li>' +
					        '</ul>'
				}
			];

			// Hapus seluruh daftar fasilitas lama di antara firstFacilityHeading dan prokerHeading
			var node = firstFacilityHeading.nextElementSibling;
			while (node && node !== prokerHeading) {
				var next = node.nextElementSibling;
				node.parentNode.removeChild(node);
				node = next;
			}

			var section = document.createElement('div');
			section.className = 'icgowa-proker-section icgowa-sarpras-section';

			function renderCards(filterCat) {
				return sarprasItems
					.map(function(item, idx) {
						return { item: item, idx: idx };
					})
					.filter(function(entry) {
						return filterCat === 'all' || entry.item.cat === filterCat;
					})
					.map(function(entry) {
						var item = entry.item;
						return '<div class="icgowa-sarpras-card">' +
							'<div class="icgowa-sarpras-card-media">' +
								'<span class="icgowa-sarpras-cat-badge">' + item.catLabel + '</span>' +
								'<span class="icgowa-sarpras-num-badge">' + item.num + '</span>' +
								'<img src="' + item.img + '" alt="' + item.title + '" loading="lazy" />' +
							'</div>' +
							'<div class="icgowa-sarpras-card-body">' +
								'<div>' +
									'<h4 class="icgowa-sarpras-card-title">' + item.title + '</h4>' +
									'<p class="icgowa-sarpras-card-excerpt">' + item.excerpt + '</p>' +
								'</div>' +
								'<div class="icgowa-proker-card-footer">' +
									'<button type="button" class="icgowa-proker-more-btn" data-sarpras-idx="' + entry.idx + '">' +
										'<span>More Info</span>' +
										'<svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>' +
									'</button>' +
								'</div>' +
							'</div>' +
						'</div>';
					}).join('');
			}

			section.innerHTML =
				'<div class="icgowa-proker-header">' +
					'<h3>FASILITAS &amp; SARANA MADRASAH</h3>' +
					'<div class="icgowa-proker-nav">' +
						'<button type="button" class="icgowa-proker-nav-btn sarpras-prev" aria-label="Geser ke kiri">' +
							'<svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>' +
						'</button>' +
						'<button type="button" class="icgowa-proker-nav-btn sarpras-next" aria-label="Geser ke kanan">' +
							'<svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>' +
						'</button>' +
					'</div>' +
				'</div>' +
				'<div class="icgowa-proker-track-wrapper">' +
					'<div class="icgowa-proker-track" id="icgowa-sarpras-track">' + renderCards('all') + '</div>' +
				'</div>';

			firstFacilityHeading.parentNode.replaceChild(section, firstFacilityHeading);

			var modalBackdrop = createSharedModal();
			var track = section.querySelector('#icgowa-sarpras-track');
			var prevBtn = section.querySelector('.sarpras-prev');
			var nextBtn = section.querySelector('.sarpras-next');

			function getScrollStep() {
				var firstCard = track.querySelector('.icgowa-sarpras-card');
				return firstCard ? (firstCard.offsetWidth + 20) : 400;
			}

			prevBtn.addEventListener('click', function() {
				track.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
			});

			nextBtn.addEventListener('click', function() {
				track.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
			});

			// Klik More Info pada Kartu Sarpras
			section.addEventListener('click', function(e) {
				var btn = e.target.closest('.icgowa-proker-more-btn');
				if (btn && btn.hasAttribute('data-sarpras-idx')) {
					var idx = parseInt(btn.getAttribute('data-sarpras-idx'), 10);
					var item = sarprasItems[idx];
					if (!item) return;
					document.getElementById('icgowa-modal-badge').textContent = item.catLabel;
					document.getElementById('icgowa-modal-title').textContent = item.title;
					document.getElementById('icgowa-modal-body').innerHTML =
						'<img class="icgowa-proker-modal-photo" src="' + item.img + '" alt="' + item.title + '" />' +
						item.detail;
					modalBackdrop.classList.add('open');
					document.body.style.overflow = 'hidden';
				}
			});

			// Rapikan Tabel Program Kerja Sarpras (perbaiki nested table pada baris ke-5)
			if (prokerHeading) {
				var tableEl = prokerHeading.nextElementSibling;
				while (tableEl && tableEl.tagName !== 'TABLE') {
					tableEl = tableEl.nextElementSibling;
				}
				if (tableEl) {
					var nestedTables = tableEl.querySelectorAll('td table');
					for (var n = 0; n < nestedTables.length; n++) {
						var parentTd = nestedTables[n].closest('td');
						if (parentTd) {
							parentTd.textContent = (parentTd.textContent || '').replace(/\s+/g, ' ').trim();
						}
					}
					var wrapper = document.createElement('div');
					wrapper.className = 'icgowa-sarpras-table-wrap';
					tableEl.parentNode.insertBefore(wrapper, tableEl);
					wrapper.appendChild(tableEl);
				}
			}
		}

		function initAll() {
			initKeasramaanProkerSlider();
			initSarprasSlider();
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initAll);
		} else {
			initAll();
		}
	})();
	</script>
	<?php
}, 99 );
