<?php
/**
 * Plugin Name: ICGowa — Tata Letak Laman Visi & Misi Minimalis & Elegan
 * Description: Mendesain ulang laman Visi Misi (/visi-misi/) MAN Insan Cendekia Gowa dengan gaya tipografi minimalis, bersih, dan elegan tanpa kotak/card yang berlebihan.
 * Author: ICGowa
 * Version: 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Deteksi apakah request saat ini berada di laman /visi-misi/
 */
function icgowa_is_visi_misi_request() {
	if ( is_admin() ) {
		return false;
	}
	if ( is_page( 'visi-misi' ) || is_page( 209 ) ) {
		return true;
	}
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$path = trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
		if ( $path === 'visi-misi' ) {
			return true;
		}
	}
	return false;
}

/**
 * 2. Enqueue CSS Khusus Laman Visi Misi Minimalis
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! icgowa_is_visi_misi_request() ) {
		return;
	}

	$handle = 'icgowa-visi-misi-minimal';
	wp_register_style( $handle, false, array(), '2.0.0' );
	wp_enqueue_style( $handle );

	$css = <<<'CSS'
/* ============================================================
   TATA LETAK MINIMALIS, BERSIH & ELEGAN LAMAN VISI MISI
   MAN INSAN CENDEKIA GOWA
   ============================================================ */

/* Penyesuaian container Elementor pada halaman 209 */
.elementor-209 .elementor-element.elementor-element-0031dc1 {
	max-width: 1080px !important;
	margin-left: auto !important;
	margin-right: auto !important;
	padding-left: 1.25rem !important;
	padding-right: 1.25rem !important;
	padding-top: 3.75rem !important; /* Jarak lega dan proporsional dari header banner */
	padding-bottom: 5rem !important;
}

.elementor-209 .elementor-element.elementor-element-0031dc1 > .e-con-inner {
	max-width: 100% !important;
	width: 100% !important;
}

.icgowa-vm-minimal {
	max-width: 1040px;
	margin: 0 auto;
	font-family: "Poppins", "Helvetica Neue", sans-serif;
	color: #334155;
	line-height: 1.8;
}

/* Reset margin default secara aman tanpa !important agar properti margin spesifik bekerja */
.icgowa-vm-minimal h1,
.icgowa-vm-minimal h2,
.icgowa-vm-minimal h3,
.icgowa-vm-minimal h4,
.icgowa-vm-minimal h5,
.icgowa-vm-minimal h6,
.icgowa-vm-minimal p,
.icgowa-vm-minimal blockquote {
	margin-top: 0;
	margin-bottom: 0;
}

/* Section Wrapper Minimalis (Jarak antar bagian lebih luwes dan bernapas) */
.icgowa-vm-section {
	margin-bottom: 4.5rem;
}

/* Label Judul Bagian */
.icgowa-vm-label {
	font-size: 1.5rem !important;
	font-weight: 700 !important;
	color: #0f172a !important;
	letter-spacing: -0.01em;
	display: flex;
	align-items: center;
	gap: 16px;
	margin-top: 0 !important;
	margin-bottom: 1.5rem !important;
}

/* Jika judul diikuti sublabel, kurangi margin agar sublabel menyatu rapi */
.icgowa-vm-label:has(+ .icgowa-vm-sublabel) {
	margin-bottom: 0.65rem !important;
}

.icgowa-vm-label::after {
	content: "";
	flex: 1;
	height: 1px;
	background: #e2e8f0;
}

.icgowa-vm-sublabel {
	font-size: 0.98rem !important;
	color: #64748b !important;
	line-height: 1.65 !important;
	margin-top: 0 !important;
	margin-bottom: 2.25rem !important;
}

/* 1. VISI UTAMA (Blok Kutipan Elegan Minimalis - Rapi, Bernapas & Rata Kiri-Kanan) */
.icgowa-vm-statement-wrap {
	border-left: 3px solid #1d4ed8;
	background: #f8fafc;
	padding: 1.35rem 1.75rem;
	border-radius: 0 10px 10px 0;
	margin-top: 0 !important;
	margin-bottom: 2.5rem !important;
}

.icgowa-vm-statement {
	font-size: 1.02rem !important;
	font-weight: 500 !important;
	line-height: 1.85 !important;
	color: #1e293b !important;
	font-style: italic;
	text-align: justify !important;
	text-justify: inter-word;
	border: none !important;
	padding: 0 !important;
	margin: 0 !important;
}

.icgowa-vm-statement strong {
	color: #1d4ed8 !important;
	font-weight: 600 !important;
	font-style: normal;
}

/* 3 Pilar Visi (Minimalist 3 Kolom Teks) */
.icgowa-vm-pillars {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 2.5rem;
	padding-top: 0.5rem;
}

.icgowa-vm-pillar {
	display: flex;
	gap: 16px;
	align-items: flex-start;
}

.icgowa-vm-pillar-num {
	font-size: 1.15rem !important;
	font-weight: 800 !important;
	color: #1d4ed8 !important;
	line-height: 1.2;
	flex-shrink: 0;
	padding-top: 2px;
}

.icgowa-vm-pillar-title {
	font-size: 1.02rem !important;
	font-weight: 700 !important;
	color: #0f172a !important;
	margin-top: 0 !important;
	margin-bottom: 0.45rem !important;
}

.icgowa-vm-pillar-desc {
	font-size: 0.92rem !important;
	color: #64748b !important;
	line-height: 1.7 !important;
	text-align: justify !important;
	text-justify: inter-word;
	margin: 0 !important;
}

/* 2. SEMBOYAN / MOTTO (Minimalist Divider Line dengan Ruang Luwes) */
.icgowa-vm-motto-minimal {
	text-align: center;
	padding: 2.25rem 1rem;
	border-top: 1px solid #e2e8f0;
	border-bottom: 1px solid #e2e8f0;
	margin-top: 4.5rem;
	margin-bottom: 4.5rem;
}

.icgowa-vm-motto-quote {
	font-size: clamp(1.2rem, 2vw, 1.45rem) !important;
	font-weight: 700 !important;
	color: #0f172a !important;
	letter-spacing: -0.01em;
	display: inline-block;
}

/* 3. MISI (Minimalist 2-Column List dengan Hairline Divider) */
.icgowa-vm-misi-list {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 2.25rem 3rem;
}

.icgowa-vm-misi-item {
	display: flex;
	gap: 20px;
	align-items: flex-start;
	padding-bottom: 1.75rem;
	border-bottom: 1px solid #f1f5f9;
}

.icgowa-vm-misi-num {
	font-size: 1.35rem !important;
	font-weight: 800 !important;
	color: #1d4ed8 !important;
	line-height: 1.1;
	flex-shrink: 0;
	min-width: 32px;
	padding-top: 2px;
}

.icgowa-vm-misi-heading {
	font-size: 1.05rem !important;
	font-weight: 700 !important;
	color: #0f172a !important;
	line-height: 1.45 !important;
	margin-top: 0 !important;
	margin-bottom: 0.55rem !important;
}

.icgowa-vm-misi-desc {
	font-size: 0.93rem !important;
	color: #475569 !important;
	line-height: 1.75 !important;
	text-align: justify !important;
	text-justify: inter-word;
	margin: 0 !important;
}

/* 4. PROFIL KOMPETENSI LULUSAN (Minimalist 3 Columns dengan Kolom Luwes) */
.icgowa-vm-comp-grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 2.5rem;
}

.icgowa-vm-comp-col {
	display: flex;
	flex-direction: column;
	padding-top: 0.25rem;
}

.icgowa-vm-comp-col:not(:first-child) {
	border-left: 1px solid #e2e8f0;
	padding-left: 2rem;
}

.icgowa-vm-comp-title {
	font-size: 1.06rem !important;
	font-weight: 700 !important;
	color: #0f172a !important;
	margin-top: 0 !important;
	margin-bottom: 0.65rem !important;
}

.icgowa-vm-comp-desc {
	font-size: 0.92rem !important;
	color: #64748b !important;
	line-height: 1.75 !important;
	text-align: justify !important;
	text-justify: inter-word;
	margin: 0 !important;
}

/* 5. KUTIPAN FILOSOFIS B.J. HABIBIE (Minimalist Pull-Quote) */
.icgowa-vm-quote-minimal {
	border-left: 3px solid #0284c7;
	padding: 1.5rem 2rem;
	margin-top: 4.5rem;
	margin-bottom: 1.5rem;
	background: #f8fafc;
	border-radius: 0 12px 12px 0;
}

.icgowa-vm-quote-text {
	font-size: 1.02rem !important;
	color: #334155 !important;
	line-height: 1.8 !important;
	font-style: italic;
	text-align: justify !important;
	text-justify: inter-word;
	margin-top: 0 !important;
	margin-bottom: 0.75rem !important;
	border: none !important;
	padding: 0 !important;
}

.icgowa-vm-quote-text strong {
	color: #0284c7 !important;
	font-style: normal;
}

.icgowa-vm-quote-author {
	display: block;
	font-size: 0.88rem !important;
	color: #64748b !important;
	font-style: normal;
	font-weight: 600 !important;
	margin: 0 !important;
}

/* RESPONSIVE */
@media (max-width: 900px) {
	.icgowa-vm-pillars {
		grid-template-columns: 1fr;
		gap: 1.75rem;
	}
	.icgowa-vm-misi-list {
		grid-template-columns: 1fr;
		gap: 1.75rem;
	}
	.icgowa-vm-comp-grid {
		grid-template-columns: 1fr;
		gap: 2rem;
	}
	.icgowa-vm-comp-col:not(:first-child) {
		border-left: none;
		padding-left: 0;
		border-top: 1px solid #e2e8f0;
		padding-top: 1.5rem;
	}
}

@media (max-width: 600px) {
	.icgowa-vm-section {
		margin-bottom: 3rem;
	}
	.icgowa-vm-statement-wrap {
		padding: 1rem 1.15rem;
	}
	.icgowa-vm-quote-minimal {
		padding: 1.15rem 1.25rem;
	}
}
CSS;

	wp_add_inline_style( $handle, $css );
} );

/**
 * 3. Filter Konten Widget Editor Elementor pada Laman Visi Misi
 */
add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
	if ( ! icgowa_is_visi_misi_request() ) {
		return $content;
	}

	// Tangkap widget text-editor utama pada halaman Visi Misi (id: 13f7d62)
	if ( method_exists( $widget, 'get_id' ) && $widget->get_id() === '13f7d62' ) {
		return icgowa_get_visi_misi_page_html();
	}

	return $content;
}, 99, 2 );

/**
 * 4. Fallback filter the_content jika Elementor tidak aktif
 */
add_filter( 'the_content', function ( $content ) {
	if ( ! icgowa_is_visi_misi_request() ) {
		return $content;
	}
	if ( in_the_loop() && is_main_query() ) {
		return icgowa_get_visi_misi_page_html();
	}
	return $content;
}, 99 );

/**
 * 5. Fungsi Penghasil Template HTML Laman Visi & Misi Minimalis & Elegan
 */
function icgowa_get_visi_misi_page_html() {
	ob_start();
	?>
	<div class="icgowa-vm-minimal">

		<!-- 1. VISI UTAMA -->
		<section class="icgowa-vm-section">
			<h2 class="icgowa-vm-label">Visi</h2>
			<div class="icgowa-vm-statement-wrap">
				<blockquote class="icgowa-vm-statement">
					“Terwujudnya sumber daya manusia yang berkualitas tinggi dalam <strong>keimanan dan ketakwaan</strong>, menguasai <strong>ilmu pengetahuan dan teknologi</strong>, serta mampu <strong>mengaktualisasikannya dalam masyarakat</strong>.”
				</blockquote>
			</div>

			<!-- 3 Pilar Penopang Visi -->
			<div class="icgowa-vm-pillars">
				<div class="icgowa-vm-pillar">
					<span class="icgowa-vm-pillar-num">01</span>
					<div>
						<h4 class="icgowa-vm-pillar-title">Keimanan &amp; Ketakwaan</h4>
						<p class="icgowa-vm-pillar-desc">Fondasi akidah kokoh, berakhlak mulia, berjiwa toleran, dan mengamalkan nilai-nilai Islam <em>rahmatan lil ’alamin</em>.</p>
					</div>
				</div>
				<div class="icgowa-vm-pillar">
					<span class="icgowa-vm-pillar-num">02</span>
					<div>
						<h4 class="icgowa-vm-pillar-title">Penguasaan IPTEK</h4>
						<p class="icgowa-vm-pillar-desc">Keunggulan sains dan riset ilmiah, nalar kritis analitis, dan adaptif terhadap transformasi digital global.</p>
					</div>
				</div>
				<div class="icgowa-vm-pillar">
					<span class="icgowa-vm-pillar-num">03</span>
					<div>
						<h4 class="icgowa-vm-pillar-title">Aktualisasi Masyarakat</h4>
						<p class="icgowa-vm-pillar-desc">Daya kemanfaatan nyata, kepemimpinan solutif, serta pengabdian aktif untuk kemaslahatan umat dan bangsa.</p>
					</div>
				</div>
			</div>
		</section>

		<!-- 2. SEMBOYAN / MOTTO MADRASAH -->
		<div class="icgowa-vm-motto-minimal">
			<span class="icgowa-vm-motto-quote">“Kampus Prestasi, Mandiri, dan Islami”</span>
		</div>

		<!-- 3. MISI STRATEGIS -->
		<section class="icgowa-vm-section">
			<h2 class="icgowa-vm-label">Misi</h2>
			<p class="icgowa-vm-sublabel">Enam langkah strategis untuk mewujudkan keunggulan dan mutu berkelanjutan:</p>

			<div class="icgowa-vm-misi-list">
				<!-- Misi 01 -->
				<div class="icgowa-vm-misi-item">
					<div class="icgowa-vm-misi-num">01</div>
					<div class="icgowa-vm-misi-content">
						<h3 class="icgowa-vm-misi-heading">Calon Pemimpin Masa Depan Berkarakter Tangguh</h3>
						<p class="icgowa-vm-misi-desc">Menyiapkan calon pemimpin masa depan yang menguasai ilmu pengetahuan dan teknologi, mempunyai daya juang tinggi, kreatif, inovatif, proaktif, dan mempunyai landasan iman dan takwa yang kuat.</p>
					</div>
				</div>

				<!-- Misi 02 -->
				<div class="icgowa-vm-misi-item">
					<div class="icgowa-vm-misi-num">02</div>
					<div class="icgowa-vm-misi-content">
						<h3 class="icgowa-vm-misi-heading">Pengembangan Minat, Bakat &amp; Prestasi Global</h3>
						<p class="icgowa-vm-misi-desc">Menumbuhkembangkan minat, bakat, dan potensi peserta didik untuk meraih prestasi pada tingkat nasional sampai internasional.</p>
					</div>
				</div>

				<!-- Misi 03 -->
				<div class="icgowa-vm-misi-item">
					<div class="icgowa-vm-misi-num">03</div>
					<div class="icgowa-vm-misi-content">
						<h3 class="icgowa-vm-misi-heading">Komunikasi Aktif Bahasa Internasional</h3>
						<p class="icgowa-vm-misi-desc">Meningkatkan kemampuan peserta didik dalam berkomunikasi dengan bahasa internasional secara aktif, percaya diri, dan berdaya saing antarbangsa.</p>
					</div>
				</div>

				<!-- Misi 04 -->
				<div class="icgowa-vm-misi-item">
					<div class="icgowa-vm-misi-num">04</div>
					<div class="icgowa-vm-misi-content">
						<h3 class="icgowa-vm-misi-heading">Peningkatan Profesionalisme Pendidik &amp; Tenaga Kependidikan</h3>
						<p class="icgowa-vm-misi-desc">Meningkatkan pengetahuan dan kemampuan profesional guru dan tenaga kependidikan sesuai dengan perkembangan dunia pendidikan mutakhir.</p>
					</div>
				</div>

				<!-- Misi 05 -->
				<div class="icgowa-vm-misi-item">
					<div class="icgowa-vm-misi-num">05</div>
					<div class="icgowa-vm-misi-content">
						<h3 class="icgowa-vm-misi-heading">Tata Kelola Baik, Mandiri &amp; Berwawasan Lingkungan</h3>
						<p class="icgowa-vm-misi-desc">Menjadikan MAN Insan Cendekia Gowa sebagai lembaga pendidikan yang bertatakelola baik, mandiri, transparan, dan berwawasan lingkungan hijau (Eco-School).</p>
					</div>
				</div>

				<!-- Misi 06 -->
				<div class="icgowa-vm-misi-item">
					<div class="icgowa-vm-misi-num">06</div>
					<div class="icgowa-vm-misi-content">
						<h3 class="icgowa-vm-misi-heading">Model Rujukan Pembelajaran IPTEK &amp; IMTAK</h3>
						<p class="icgowa-vm-misi-desc">Menjadikan MAN Insan Cendekia Gowa sebagai model dalam pengembangan pembelajaran IPTEK dan IMTAK bagi lembaga pendidikan lainnya di Indonesia.</p>
					</div>
				</div>
			</div>
		</section>

		<!-- 4. PROFIL KOMPETENSI LULUSAN -->
		<section class="icgowa-vm-section">
			<h2 class="icgowa-vm-label">Profil Kompetensi Lulusan</h2>
			<p class="icgowa-vm-sublabel">Tiga domain capaian holistik peserta didik MAN Insan Cendekia Gowa:</p>

			<div class="icgowa-vm-comp-grid">
				<div class="icgowa-vm-comp-col">
					<h4 class="icgowa-vm-comp-title">Aspek Afektif</h4>
					<p class="icgowa-vm-comp-desc">Keimanan dan ketakwaan kokoh kepada Allah SWT, menjunjung tinggi nilai-nilai etika, estetika islami, demokrasi yang santun, toleransi antarsesama, serta kepekaan humaniora.</p>
				</div>
				<div class="icgowa-vm-comp-col">
					<h4 class="icgowa-vm-comp-title">Aspek Kognitif</h4>
					<p class="icgowa-vm-comp-desc">Penguasaan mendalam atas dasar-dasar sains, teknologi, matematika, ilmu sosial, dan riset ilmiah mutakhir sebagai bekal menembus perguruan tinggi terkemuka dunia.</p>
				</div>
				<div class="icgowa-vm-comp-col">
					<h4 class="icgowa-vm-comp-title">Aspek Praktik / Psikomotorik</h4>
					<p class="icgowa-vm-comp-desc">Kecakapan hidup (<em>life skills</em>), daya cipta inovatif, kelincahan komunikasi trilingual, kepemimpinan adaptif dalam asrama, kebugaran jasmani, dan jiwa kewirausahaan.</p>
				</div>
			</div>
		</section>

		<!-- 5. KUTIPAN FILOSOFIS B.J. HABIBIE -->
		<div class="icgowa-vm-quote-minimal">
			<blockquote class="icgowa-vm-quote-text">
				“Untuk memenuhi kebutuhan sumber daya manusia yang berkualitas tinggi dalam penguasaan IPTEK yang didasari nilai keimanan dan ketakwaan, maka dihadirkan madrasah akademik unggulan yang kelak melahirkan calon pemimpin bangsa <strong>berotak Jerman dan berhati Mekkah</strong>.”
			</blockquote>
			<cite class="icgowa-vm-quote-author">— Prof. Dr.-Ing. Bacharuddin Jusuf Habibie (Presiden ke-3 Republik Indonesia)</cite>
		</div>

	</div>
	<?php
	return ob_get_clean();
}
