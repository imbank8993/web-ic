<?php
/**
 * Plugin Name: ICGowa — Tata Letak Laman Sejarah Interaktif & Profil Kepala Madrasah (Nuansa Biru)
 * Description: Menjadikan laman Sejarah (/sejarah/) interaktif, elegan, dan bernuansa biru khas madrasah dengan linimasa bersejarah, statistik kunci, kutipan filosofis, serta etalase kartu profil 3 Kepala Madrasah dari masa ke masa disertai foto.
 * Author: ICGowa
 * Version: 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Deteksi apakah request saat ini berada di laman /sejarah/
 */
function icgowa_is_sejarah_request() {
	if ( is_admin() ) {
		return false;
	}
	if ( is_page( 'sejarah' ) || is_page( 189 ) ) {
		return true;
	}
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$path = trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
		if ( $path === 'sejarah' ) {
			return true;
		}
	}
	return false;
}

/**
 * 2. Enqueue CSS Khusus Laman Sejarah (Nuansa Biru Elegan)
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! icgowa_is_sejarah_request() ) {
		return;
	}

	$handle = 'icgowa-sejarah-modern';
	wp_register_style( $handle, false, array(), '1.1.0' );
	wp_enqueue_style( $handle );

	$css = <<<'CSS'
/* ============================================================
   TATA LETAK MODERN LAMAN SEJARAH MAN INSAN CENDEKIA GOWA
   (Nuansa Biru / Royal Navy Theme)
   ============================================================ */

.icgowa-sejarah-wrapper {
	max-width: 1140px;
	margin: 0 auto;
	padding: 1.5rem 1rem 4rem;
	color: #334155;
	font-family: "Poppins", "Helvetica Neue", sans-serif;
}

/* 1. Header Banner & Intro */
.icgowa-sejarah-header {
	text-align: center;
	margin-bottom: 3.5rem;
	position: relative;
}

.icgowa-sejarah-title {
	font-size: 2.25rem;
	font-weight: 700;
	color: #0f172a;
	line-height: 1.35;
	margin: 0 auto 1.25rem;
	max-width: 840px;
}

.icgowa-sejarah-subtitle {
	font-size: 1.05rem;
	color: #475569;
	line-height: 1.8;
	max-width: 780px;
	margin: 0 auto;
}

/* 2. Key Facts / Stats Counter Bar */
.icgowa-sejarah-stats {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 1.25rem;
	margin-bottom: 3.5rem;
}

.icgowa-stat-card {
	background: #ffffff;
	border: 1px solid #e2e8f0;
	border-radius: 16px;
	padding: 1.5rem 1.25rem;
	text-align: center;
	box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
	transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
	position: relative;
	overflow: hidden;
}

.icgowa-stat-card::before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: 4px;
	background: linear-gradient(90deg, #1d4ed8, #0ea5e9);
	opacity: 0;
	transition: opacity 0.3s ease;
}

.icgowa-stat-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.1);
	border-color: #93c5fd;
}

.icgowa-stat-card:hover::before {
	opacity: 1;
}

.icgowa-stat-number {
	font-size: 2rem;
	font-weight: 800;
	color: #1d4ed8;
	line-height: 1.1;
	margin-bottom: 0.35rem;
	display: block;
}

.icgowa-stat-label {
	font-size: 0.85rem;
	color: #64748b;
	font-weight: 500;
	line-height: 1.4;
}

/* 3. Quote Box Filosofis B.J. Habibie */
.icgowa-habibie-quote {
	background: linear-gradient(135deg, #0a2540 0%, #0f3963 100%);
	color: #ffffff;
	border-radius: 20px;
	padding: 2.25rem 2.5rem;
	margin-bottom: 4rem;
	position: relative;
	box-shadow: 0 12px 30px rgba(10, 37, 64, 0.25);
	display: flex;
	align-items: center;
	gap: 2rem;
	overflow: hidden;
	border: 1px solid rgba(255, 255, 255, 0.1);
}

.icgowa-habibie-quote::after {
	content: "“";
	position: absolute;
	right: 1.5rem;
	bottom: -2.5rem;
	font-size: 11rem;
	font-family: Georgia, serif;
	color: rgba(255, 255, 255, 0.05);
	line-height: 1;
	pointer-events: none;
}

.icgowa-quote-icon {
	flex-shrink: 0;
	width: 64px;
	height: 64px;
	border-radius: 50%;
	background: rgba(56, 189, 248, 0.15);
	border: 2px solid #38bdf8;
	display: flex;
	align-items: center;
	justify-content: center;
	color: #38bdf8;
}

.icgowa-quote-icon svg {
	width: 32px;
	height: 32px;
	fill: currentColor;
}

.icgowa-quote-text h4 {
	font-size: 1.15rem;
	font-weight: 600;
	color: #38bdf8;
	margin: 0 0 0.5rem;
}

.icgowa-quote-text p {
	font-size: 0.98rem;
	line-height: 1.7;
	color: #e2e8f0;
	margin: 0;
}

/* 4. Timeline Perjalanan Sejarah (Milestones) */
.icgowa-section-header {
	text-align: center;
	margin-bottom: 2.5rem;
}

.icgowa-section-tag {
	font-size: 0.82rem;
	font-weight: 700;
	color: #1d4ed8;
	text-transform: uppercase;
	letter-spacing: 0.1em;
	display: block;
	margin-bottom: 0.5rem;
}

.icgowa-section-heading {
	font-size: 1.85rem;
	font-weight: 700;
	color: #0f172a;
	margin: 0 0 0.75rem;
}

.icgowa-section-desc {
	font-size: 0.98rem;
	color: #64748b;
	max-width: 650px;
	margin: 0 auto;
}

.icgowa-timeline {
	position: relative;
	margin: 3rem 0 4.5rem;
	padding: 1rem 0;
}

.icgowa-timeline::before {
	content: "";
	position: absolute;
	top: 0;
	bottom: 0;
	left: 50%;
	width: 3px;
	background: linear-gradient(180deg, #1d4ed8 0%, #cbd5e1 50%, #0284c7 100%);
	transform: translateX(-50%);
	border-radius: 9999px;
}

.icgowa-timeline-item {
	display: flex;
	align-items: flex-start;
	margin-bottom: 2.5rem;
	position: relative;
}

.icgowa-timeline-item:nth-child(odd) {
	flex-direction: row-reverse;
}

.icgowa-timeline-content {
	width: 45%;
	background: #ffffff;
	border: 1px solid #e2e8f0;
	border-radius: 16px;
	padding: 1.75rem;
	box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
	position: relative;
	transition: all 0.3s ease;
}

.icgowa-timeline-content:hover {
	transform: translateY(-3px);
	box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.08);
	border-color: #93c5fd;
}

.icgowa-timeline-badge {
	position: absolute;
	left: 50%;
	transform: translateX(-50%);
	width: 44px;
	height: 44px;
	background: #ffffff;
	border: 3px solid #1d4ed8;
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	font-weight: 700;
	font-size: 0.85rem;
	color: #1d4ed8;
	box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.15);
	z-index: 2;
}

.icgowa-timeline-year {
	display: inline-block;
	background: #eff6ff;
	color: #1d4ed8;
	padding: 0.25rem 0.75rem;
	border-radius: 6px;
	font-size: 0.8rem;
	font-weight: 700;
	margin-bottom: 0.65rem;
}

.icgowa-timeline-title {
	font-size: 1.15rem;
	font-weight: 700;
	color: #0f172a;
	margin: 0 0 0.65rem;
	line-height: 1.4;
}

.icgowa-timeline-body {
	font-size: 0.92rem;
	line-height: 1.75;
	color: #475569;
	margin: 0;
	text-align: justify;
	text-justify: inter-word;
}

/* 5. Bagian Kepala Madrasah dari Masa ke Masa */
.icgowa-leaders-section {
	margin-top: 5rem;
	padding-top: 3.5rem;
	border-top: 1px dashed #cbd5e1;
}

.icgowa-leaders-grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 2rem;
	margin-top: 2.5rem;
}

.icgowa-leader-card {
	background: #ffffff;
	border-radius: 20px;
	border: 1px solid #e2e8f0;
	overflow: hidden;
	box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06);
	transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
	display: flex;
	flex-direction: column;
	position: relative;
}

.icgowa-leader-card:hover {
	transform: translateY(-8px);
	box-shadow: 0 20px 35px -8px rgba(15, 23, 42, 0.12);
	border-color: #2563eb;
}

.icgowa-leader-media {
	position: relative;
	width: 100%;
	padding-top: 110%; /* Rasio potret ideal */
	overflow: hidden;
	background: linear-gradient(135deg, #0a2540 0%, #1e3a8a 100%);
}

.icgowa-leader-img {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	object-fit: cover;
	object-position: top center;
	transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.icgowa-leader-card:hover .icgowa-leader-img {
	transform: scale(1.05);
}

.icgowa-period-pill {
	position: absolute;
	bottom: 12px;
	left: 14px;
	right: 14px;
	background: rgba(15, 23, 42, 0.88);
	backdrop-filter: blur(8px);
	-webkit-backdrop-filter: blur(8px);
	color: #ffffff;
	padding: 0.45rem 0.85rem;
	border-radius: 10px;
	font-size: 0.78rem;
	font-weight: 600;
	display: flex;
	align-items: center;
	justify-content: space-between;
	border: 1px solid rgba(255, 255, 255, 0.15);
	z-index: 2;
}

.icgowa-period-pill.is-active {
	background: rgba(15, 35, 75, 0.92);
	border-color: rgba(56, 189, 248, 0.5);
}

.icgowa-period-tag {
	display: inline-block;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: #38bdf8;
	box-shadow: 0 0 8px #38bdf8;
}

.icgowa-leader-info {
	padding: 1.5rem 1.4rem;
	display: flex;
	flex-direction: column;
	flex-grow: 1;
}

.icgowa-leader-role {
	font-size: 0.78rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.08em;
	color: #1d4ed8;
	margin-bottom: 0.4rem;
}

.icgowa-leader-name {
	font-size: 1.15rem;
	font-weight: 700;
	color: #0f172a;
	margin: 0 0 0.85rem;
	line-height: 1.4;
}

.icgowa-leader-desc {
	font-size: 0.88rem;
	color: #64748b;
	line-height: 1.65;
	margin: 0;
	text-align: justify;
	text-justify: inter-word;
	flex-grow: 1;
}

/* 6. Responsiveness */
@media (max-width: 991px) {
	.icgowa-sejarah-stats {
		grid-template-columns: repeat(2, 1fr);
	}
	.icgowa-leaders-grid {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media (max-width: 768px) {
	.icgowa-sejarah-title {
		font-size: 1.75rem;
	}
	.icgowa-sejarah-stats {
		grid-template-columns: repeat(2, 1fr);
		gap: 0.75rem;
	}
	.icgowa-habibie-quote {
		flex-direction: column;
		text-align: center;
		padding: 1.75rem 1.25rem;
		gap: 1.25rem;
	}
	.icgowa-quote-icon {
		margin: 0 auto;
	}
	.icgowa-timeline::before {
		left: 20px;
	}
	.icgowa-timeline-item {
		flex-direction: row !important;
		padding-left: 50px;
	}
	.icgowa-timeline-badge {
		left: 20px;
		width: 36px;
		height: 36px;
		font-size: 0.75rem;
	}
	.icgowa-timeline-content {
		width: 100%;
		padding: 1.25rem;
	}
	.icgowa-leaders-grid {
		grid-template-columns: 1fr;
		max-width: 380px;
		margin-left: auto;
		margin-right: auto;
	}
}
CSS;

	wp_add_inline_style( $handle, $css );
} );

/**
 * 3. Filter Konten Widget Editor Elementor pada Laman Sejarah
 */
add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
	if ( ! icgowa_is_sejarah_request() ) {
		return $content;
	}

	// Tangkap HANYA widget text-editor utama pada halaman Sejarah (id: 4dc33dd)
	if ( method_exists( $widget, 'get_id' ) && $widget->get_id() === '4dc33dd' ) {
		return icgowa_get_sejarah_page_html();
	}

	return $content;
}, 99, 2 );

/**
 * 4. Fallback filter the_content jika Elementor tidak aktif
 */
add_filter( 'the_content', function ( $content ) {
	if ( ! icgowa_is_sejarah_request() ) {
		return $content;
	}
	if ( in_the_loop() && is_main_query() ) {
		return icgowa_get_sejarah_page_html();
	}
	return $content;
}, 99 );

/**
 * 5. Fungsi Penghasil Template HTML Laman Sejarah Modern & Profil 3 Kepala Madrasah
 */
function icgowa_get_sejarah_page_html() {
	$base_upload = content_url( '/uploads/' );
	$kamad_1_img = $base_upload . 'kamad/kamad-1-supiana.svg';
	$kamad_2_img = $base_upload . 'kamad/kamad-2-andri.svg';
	// Gunakan foto asli pak-kamad.png untuk Burhanuddin, S.Ag., M.Fil
	$kamad_3_img = $base_upload . 'kamad/kamad-3-burhanuddin.png';

	ob_start();
	?>
	<div class="icgowa-sejarah-wrapper">

		<!-- 1. HEADER SECTION (Badge hijau dihilangkan sesuai permintaan, nuansa biru modern) -->
		<div class="icgowa-sejarah-header">
			<h1 class="icgowa-sejarah-title">Sejarah &amp; Perjalanan Berdirinya MAN Insan Cendekia Gowa</h1>
			<p class="icgowa-sejarah-subtitle">
				Kilas balik sejarah dari gagasan besar Presiden ke-3 RI Prof. Dr.-Ing. B.J. Habibie, masa perintisan di Balai Diklat Somba Opu, hingga berdirinya kampus mandiri di Parangloe yang melahirkan generasi berdaya saing global berlandaskan IMTAK &amp; IPTEK.
			</p>
		</div>

		<!-- 2. STATISTIK KUNCI (KEY FACTS) -->
		<div class="icgowa-sejarah-stats">
			<div class="icgowa-stat-card">
				<span class="icgowa-stat-number">1996</span>
				<span class="icgowa-stat-label">Gagasan Magnet School oleh B.J. Habibie</span>
			</div>
			<div class="icgowa-stat-card">
				<span class="icgowa-stat-number">2018</span>
				<span class="icgowa-stat-label">Resmi Berdiri di Sulawesi Selatan (KMA 744/2017)</span>
			</div>
			<div class="icgowa-stat-card">
				<span class="icgowa-stat-number">48 Siswa</span>
				<span class="icgowa-stat-label">Kuota Angkatan Perintis Perdana (2018/2019)</span>
			</div>
			<div class="icgowa-stat-card">
				<span class="icgowa-stat-number">9.839 m²</span>
				<span class="icgowa-stat-label">Luas Tanah Kampus Hibah Pemkab Gowa</span>
			</div>
		</div>

		<!-- 3. QUOTE FILOSOFIS B.J. HABIBIE -->
		<div class="icgowa-habibie-quote">
			<div class="icgowa-quote-icon">
				<svg viewBox="0 0 24 24"><path d="M13 14.725c0-5.141 3.86-6.141 5.86-6.725l.84 2c-1.5.385-2.7 1.285-2.7 2.725 0 .275.2.275.5.275 1.4 0 2.5 1.1 2.5 2.5s-1.1 2.5-2.5 2.5c-2.485 0-4.5-1.785-4.5-3.275zm-9 0c0-5.141 3.86-6.141 5.86-6.725l.84 2c-1.5.385-2.7 1.285-2.7 2.725 0 .275.2.275.5.275 1.4 0 2.5 1.1 2.5 2.5s-1.1 2.5-2.5 2.5c-2.485 0-4.5-1.785-4.5-3.275z"/></svg>
			</div>
			<div class="icgowa-quote-text">
				<h4>Visi Pendirian Magnet School — Prof. Dr.-Ing. B.J. Habibie</h4>
				<p>
					"Untuk memenuhi kebutuhan sumber daya manusia yang berkualitas tinggi dalam penguasaan IPTEK yang didasari nilai keimanan dan ketakwaan, maka dihadirkan madrasah akademik unggulan yang kelak melahirkan calon pemimpin bangsa berotak Jerman dan berhati Mekkah."
				</p>
			</div>
		</div>

		<!-- 4. LINIMASA PERJALANAN SEJARAH (TIMELINE) -->
		<div class="icgowa-section-header">
			<span class="icgowa-section-tag">Kronologi Bersejarah</span>
			<h2 class="icgowa-section-heading">Tonggak Penting Perjalanan Madrasah</h2>
			<p class="icgowa-section-desc">
				Rangkaian peristiwa penting sejak inisiasi awal nasional hingga bertransformasi menjadi salah satu madrasah unggulan terbaik di kawasan Indonesia Timur.
			</p>
		</div>

		<div class="icgowa-timeline">

			<!-- Milestone 1 -->
			<div class="icgowa-timeline-item">
				<div class="icgowa-timeline-badge">01</div>
				<div class="icgowa-timeline-content">
					<span class="icgowa-timeline-year">Tahun 1996 – 1997</span>
					<h3 class="icgowa-timeline-title">Gagasan Magnet School &amp; STEP BPPT</h3>
					<p class="icgowa-timeline-body">
						Atas inisiatif Prof. Dr.-Ing. Bacharuddin Jusuf Habibie (saat menjabat Kepala BPPT sekaligus Menristek), Badan Pengkajian dan Penerapan Teknologi (BPPT) mendirikan <em>Magnet School</em> yang kemudian bertransformasi menjadi SMU Insan Cendekia di Serpong (1996) dan Gorontalo (1997) melalui program <strong>STEP</strong> (<em>Science and Technology Equity Program</em>) untuk memperkuat penguasaan sains dan teknologi di kalangan santri dan madrasah.
					</p>
				</div>
			</div>

			<!-- Milestone 2 -->
			<div class="icgowa-timeline-item">
				<div class="icgowa-timeline-badge">02</div>
				<div class="icgowa-timeline-content">
					<span class="icgowa-timeline-year">Tahun 2000 – 2001</span>
					<h3 class="icgowa-timeline-title">Alih Kelola ke Departemen Agama RI</h3>
					<p class="icgowa-timeline-body">
						Pengelolaan SMU Insan Cendekia secara resmi dilimpahkan oleh BPPT kepada Departemen Agama (sekarang Kementerian Agama RI) guna mempertahankan sinergi harmonis antara sains-teknologi (IPTEK) dan iman-takwa (IMTAK). Melalui <strong>SK Menteri Agama RI Nomor 490 Tahun 2001</strong>, statusnya resmi bertransformasi menjadi <strong>Madrasah Aliyah Negeri (MAN) Insan Cendekia</strong>.
					</p>
				</div>
			</div>

			<!-- Milestone 3 -->
			<div class="icgowa-timeline-item">
				<div class="icgowa-timeline-badge">03</div>
				<div class="icgowa-timeline-content">
					<span class="icgowa-timeline-year">Tahun 2017 – 2018</span>
					<h3 class="icgowa-timeline-title">Penetapan Pendirian MAN IC Gowa</h3>
					<p class="icgowa-timeline-body">
						Berdasarkan <strong>Keputusan Menteri Agama (KMA) Nomor 744 Tahun 2017</strong>, ditetapkan pendirian 13 MAN IC baru di Indonesia, salah satunya <strong>MAN Insan Cendekia Gowa</strong> di Provinsi Sulawesi Selatan. Pemilihan Kabupaten Gowa didukung penuh oleh Pemerintah Kabupaten Gowa melalui hibah tanah seluas <strong>9.839 m²</strong> di Parangloe. Pada tahun 2018 dibuka Seleksi Nasional Pendidik, Tenaga Kependidikan, serta seleksi siswa baru perdana dengan kuota 48 siswa.
					</p>
				</div>
			</div>

			<!-- Milestone 4 -->
			<div class="icgowa-timeline-item">
				<div class="icgowa-timeline-badge">04</div>
				<div class="icgowa-timeline-content">
					<span class="icgowa-timeline-year">13 Juli – Desember 2018</span>
					<h3 class="icgowa-timeline-title">Masa Perintisan di Balai Diklat Somba Opu</h3>
					<p class="icgowa-timeline-body">
						Pembelajaran perdana sistem <em>boarding school</em> dimulai pada 13 Juli 2018 berlokasi di Balai Diklat Kabupaten Gowa (Jl. Tombolo, Somba Opu) sembari menunggu rampungnya gedung permanen. Dua bangunan pinjaman disulap menjadi ruang kelas, asrama, dan kantor. Di gedung sederhana berlantai dua inilah, dedikasi awal dan tradisi prestasi gemilang peserta didik angkatan pertama mulai diukir dengan ketangguhan luar biasa.
					</p>
				</div>
			</div>

			<!-- Milestone 5 -->
			<div class="icgowa-timeline-item">
				<div class="icgowa-timeline-badge">05</div>
				<div class="icgowa-timeline-content">
					<span class="icgowa-timeline-year">05 Januari 2019 – Sekarang</span>
					<h3 class="icgowa-timeline-title">Hijrah Akbar ke Kampus Belapunranga Parangloe</h3>
					<p class="icgowa-timeline-body">
						Tepat tanggal 05 Januari 2019, bentangan langit biru Desa Belapunranga, Kecamatan Parangloe menyambut kepindahan seluruh siswa, guru, dan pengasuh dari Somba Opu. Gedung kelas, laboratorium, asrama, masjid, kantin, dan perumahan guru yang telah rampung resmi beroperasi penuh, menandai dimulainya era keemasan MAN Insan Cendekia Gowa sebagai pusat keunggulan akademik dan riset di Sulawesi Selatan.
					</p>
				</div>
			</div>

		</div>

		<!-- 5. BAGIAN KEPALA MADRASAH DARI MASA KE MASA -->
		<div class="icgowa-leaders-section" id="kepala-madrasah">
			<div class="icgowa-section-header">
				<span class="icgowa-section-tag">Kepemimpinan Madrasah</span>
				<h2 class="icgowa-section-heading">Kepala Madrasah dari Masa ke Masa</h2>
				<p class="icgowa-section-desc">
					Figur pemimpin berdedikasi yang menakhodai MAN Insan Cendekia Gowa sejak masa perintisan, era transisi, hingga bertransformasi menjadi madrasah unggul dan berprestasi di tingkat nasional.
				</p>
			</div>

			<div class="icgowa-leaders-grid">

				<!-- 1. Hj. Supiana -->
				<div class="icgowa-leader-card">
					<div class="icgowa-leader-media">
						<img src="<?php echo esc_url( $kamad_1_img ); ?>" alt="Hj. Supiana., M.Pd.Si., Ph.D" class="icgowa-leader-img" loading="lazy" />
						<div class="icgowa-period-pill">
							<span>Periode Perintis</span>
							<span style="color: #60a5fa; font-weight: 700;">2018 – 2022</span>
						</div>
					</div>
					<div class="icgowa-leader-info">
						<span class="icgowa-leader-role">Kepala Madrasah Pertama</span>
						<h3 class="icgowa-leader-name">Hj. Supiana., M.Pd.Si., Ph.D</h3>
						<p class="icgowa-leader-desc">
							Sosok peletak fondasi awal akademik dan kultur keasramaan MAN Insan Cendekia Gowa. Beliau memimpin perjuangan pada masa perintisan di Balai Diklat Somba Opu hingga keberhasilan hijrah ke kampus permanen di Parangloe.
						</p>
					</div>
				</div>

				<!-- 2. Andri Adi Mustika -->
				<div class="icgowa-leader-card">
					<div class="icgowa-leader-media">
						<img src="<?php echo esc_url( $kamad_2_img ); ?>" alt="Andri Adi Mustika, S.Pd., M.Pd" class="icgowa-leader-img" loading="lazy" />
						<div class="icgowa-period-pill">
							<span>Plt. Kepala Madrasah</span>
							<span style="color: #38bdf8; font-weight: 700;">2022 – 2023</span>
						</div>
					</div>
					<div class="icgowa-leader-info">
						<span class="icgowa-leader-role">Plt. Kepala Madrasah</span>
						<h3 class="icgowa-leader-name">Andri Adi Mustika, S.Pd., M.Pd</h3>
						<p class="icgowa-leader-desc">
							Mengawal masa transisi kepemimpinan madrasah dengan penuh tanggung jawab, menjaga stabilitas iklim akademik, serta memastikan kesinambungan berbagai program mutu dan pembinaan prestasi peserta didik.
						</p>
					</div>
				</div>

				<!-- 3. Burhanuddin -->
				<div class="icgowa-leader-card">
					<div class="icgowa-leader-media">
						<img src="<?php echo esc_url( $kamad_3_img ); ?>" alt="Burhanuddin, S.Ag., M.Fil" class="icgowa-leader-img" loading="lazy" />
						<div class="icgowa-period-pill is-active">
							<span style="display: flex; align-items: center; gap: 6px;">
								<span class="icgowa-period-tag"></span>
								Kepala Madrasah Aktif
							</span>
							<span style="color: #38bdf8; font-weight: 700;">2023 – Sekarang</span>
						</div>
					</div>
					<div class="icgowa-leader-info">
						<span class="icgowa-leader-role">Kepala Madrasah</span>
						<h3 class="icgowa-leader-name">Burhanuddin, S.Ag., M.Fil</h3>
						<p class="icgowa-leader-desc">
							Membawa MAN Insan Cendekia Gowa memasuki babak akselerasi baru melalui transformasi digital, penguatan budaya riset sains, pengembangan sarana prasarana modern, serta capaian prestasi nasional &amp; internasional.
						</p>
					</div>
				</div>

			</div>
		</div>

	</div>
	<?php
	return ob_get_clean();
}
