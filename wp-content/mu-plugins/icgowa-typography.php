<?php
/**
 * Plugin Name: ICGowa — Tipografi Modern & Warna Ramah Baca (Poppins)
 * Description: Mengubah seluruh font halaman website menjadi lebih hangat & ramah dibaca (Poppins), memperbaiki warna teks global yang sebelumnya biru muda (#9ED6F0) menjadi gelap tegas (#334155), serta memperjelas tampilan Header biru pada seluruh halaman.
 * Author: ICGowa
 * Version: 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', function () {
	// Muat Google Font Poppins (300, 400, 500, 600, 700)
	wp_enqueue_style(
		'icgowa-google-font-poppins',
		'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap',
		array(),
		'1.2.0'
	);

	$handle = 'icgowa-warm-typography';
	wp_register_style( $handle, false, array( 'icgowa-google-font-poppins' ), '1.2.0' );
	wp_enqueue_style( $handle );

	$css = <<<'CSS'
/* ============================================================
   1. Variabel Global: Font Poppins & Warna Teks Jelas (#334155)
   ============================================================ */

:root,
body,
.elementor-kit-6 {
	--e-global-typography-primary-font-family: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	--e-global-typography-secondary-font-family: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	--e-global-typography-text-font-family: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	--e-global-typography-accent-font-family: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	--e-global-color-text: #334155 !important;
	--font-family-base: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;
}

/* ============================================================
   2. Terapkan Font Poppins di Semua Elemen Teks (Aman untuk Ikon)
   ============================================================ */

body,
button,
input,
select,
textarea,
p,
span:not([class*="eicon"]):not([class*="fa-"]):not([class*="icon"]):not(.dashicons),
a,
li,
td,
th,
blockquote,
label,
h1, h2, h3, h4, h5, h6,
.elementor-heading-title,
.elementor-widget-heading .elementor-heading-title,
.elementor-widget-text-editor,
.elementor-widget-text-editor p,
.elementor-button,
.elementor-button-text,
.elementor-icon-box-title,
.elementor-icon-box-description,
.elementor-image-box-title,
.elementor-image-box-description,
.elementor-post__title,
.elementor-post__title a,
.elementor-post__excerpt,
.elementor-post__excerpt p,
.elementor-post__meta-data,
.elementor-post__read-more,
.elementor-tab-title,
.elementor-tab-content,
.e-n-tab-title-text,
.elementskit-navbar-nav > li > a,
.elementskit-submenu-panel > li > a,
.ekit-wid-con .elementskit-infobox .elementskit-info-box-title,
.ekit-wid-con .elementskit-infobox p,
.ekit-wid-con .elementskit-section-title-wraper .elementskit-section-title,
.jet-listing-dynamic-field__content,
.jet-listing-dynamic-link__label {
	font-family: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

/* ============================================================
   3. Perbaikan Warna Teks Konten Halaman (Tidak Lagi Biru Pucat)
   ============================================================ */

body {
	color: #334155;
	line-height: 1.75;
	letter-spacing: 0.01em;
}

/* Pastikan teks artikel/halaman (Kesiswaan, Keasramaan, Humas, Sarpras, Tata Usaha, Profil, PPID, dll.)
   berwarna gelap nyaman dibaca (#334155), kecuali elemen yang memang di-set putih di beranda (#aa18bb6) */
.elementor-widget-text-editor:not(.elementor-element-aa18bb6),
.elementor-widget-text-editor:not(.elementor-element-aa18bb6) p,
.elementor-widget-text-editor:not(.elementor-element-aa18bb6) li,
.elementor-widget-text-editor:not(.elementor-element-aa18bb6) span,
.elementor-widget-theme-post-content,
.elementor-widget-theme-post-content p,
.elementor-widget-theme-post-content li {
	color: #334155 !important;
	font-size: 16px;
	line-height: 1.8;
}

/* Judul di dalam isi konten halaman (seperti H3 KESISWAAN, KEASRAMAAN, HUMAS, TATA USAHA)
   diberi warna Biru Tua Navy (#0F296B) dengan aksen garis bawah Emas (#FFAE00) yang senada */
.elementor-widget-text-editor h1,
.elementor-widget-text-editor h2,
.elementor-widget-text-editor h3,
.elementor-widget-text-editor h4,
.elementor-widget-theme-post-content h1,
.elementor-widget-theme-post-content h2,
.elementor-widget-theme-post-content h3,
.elementor-widget-theme-post-content h4 {
	color: #0F296B !important;
	font-weight: 600 !important;
	line-height: 1.35 !important;
	margin-top: 1.2em;
	margin-bottom: 0.6em;
}

/* Rapikan kontainer halaman Bidang / halaman teks agar tidak terlalu mepet ke tepi layar */
.elementor-439 .elementor-element.elementor-element-78a164c,
.elementor-1316 .elementor-element.elementor-element-77531e5b,
.elementor-16 .elementor-element.elementor-element-54f2ee23 {
	max-width: 960px;
	margin-left: auto !important;
	margin-right: auto !important;
	padding: 36px 40px !important;
	background-color: #FFFFFF;
	border-radius: 16px;
	box-shadow: 0 10px 30px rgba(15, 41, 107, 0.06);
	border: 1px solid rgba(21, 54, 162, 0.08);
}

@media (max-width: 767px) {
	.elementor-439 .elementor-element.elementor-element-78a164c,
	.elementor-1316 .elementor-element.elementor-element-77531e5b,
	.elementor-16 .elementor-element.elementor-element-54f2ee23 {
		padding: 24px 20px !important;
		margin-left: 15px !important;
		margin-right: 15px !important;
	}
}

/* ============================================================
   4. Perbaikan Header Banner Biru & Navigasi (Header #192 & #40)
   ============================================================ */

/* Banner Header pada halaman dalam (Header #192):
   Overlay gradasi biru tua yang elegan & kontras tinggi */
.elementor-192 .elementor-element.elementor-element-434c88f::before,
.elementor-192 .elementor-element.elementor-element-434c88f > .elementor-motion-effects-container > .elementor-motion-effects-layer::before {
	background: linear-gradient(135deg, rgba(10, 25, 68, 0.90) 0%, rgba(21, 54, 162, 0.82) 100%) !important;
	opacity: 1 !important;
}

/* Judul Halaman di dalam Banner Header Biru (sebelumnya biru di atas biru sehingga gelap/tidak terbaca)
   diubah menjadi Putih Bersih (#FFFFFF) dengan aksen garis emas di bawahnya */
.elementor-192 .elementor-element.elementor-element-d4fa7be .elementor-heading-title,
.elementor-192 .elementor-element.elementor-element-434c88f .elementor-heading-title {
	color: #FFFFFF !important;
	font-size: clamp(26px, 3.5vw, 38px) !important;
	font-weight: 700 !important;
	letter-spacing: 0.03em !important;
	text-transform: uppercase;
	text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35);
	position: relative;
	display: inline-block;
	padding-bottom: 14px;
}

.elementor-192 .elementor-element.elementor-element-d4fa7be .elementor-heading-title::after {
	content: "";
	position: absolute;
	left: 50%;
	bottom: 0;
	transform: translateX(-50%);
	width: 64px;
	height: 4px;
	border-radius: 4px;
	background-color: #FFAE00;
}

/* Navbar Utama (Header #40 & Header #192) — warna teks gelap tegas (#1E293B) & hover biru/emas */
.elementor-40 .elementskit-navbar-nav > li > a,
.elementor-192 .elementskit-navbar-nav > li > a {
	color: #1E293B !important;
	font-weight: 500 !important;
	font-size: 14px !important;
	letter-spacing: 0.02em !important;
	transition: color 0.25s ease !important;
}

.elementor-40 .elementskit-navbar-nav > li:hover > a,
.elementor-40 .elementskit-navbar-nav > li.current-menu-item > a,
.elementor-40 .elementskit-navbar-nav > li.current-menu-ancestor > a,
.elementor-192 .elementskit-navbar-nav > li:hover > a,
.elementor-192 .elementskit-navbar-nav > li.current-menu-item > a,
.elementor-192 .elementskit-navbar-nav > li.current-menu-ancestor > a {
	color: #1536A2 !important;
	font-weight: 600 !important;
}

/* Pastikan baris Navbar berada di lapisan paling atas (tidak tertutup Banner/Hero di bawahnya) */
.elementor-location-header,
.elementor-40 .elementor-element.elementor-element-575a1ac5,
.elementor-40 .elementor-element.elementor-element-66281196,
.elementor-40 .elementor-element.elementor-element-609b3a9,
.elementor-40 .elementor-element.elementor-element-6ab1e816,
.elementor-192 .elementor-element.elementor-element-4605cf3c,
.elementor-192 .elementor-element.elementor-element-402f9068,
.elementor-192 .elementor-element.elementor-element-7da94485,
.elementor-192 .elementor-element.elementor-element-2a260d5f {
	position: relative;
	z-index: 9999 !important;
	overflow: visible !important;
}

.elementor-192 .elementor-element.elementor-element-434c88f {
	position: relative;
	z-index: 1 !important;
}

/* Dropdown Submenu (Kesiswaan, Keasramaan, Humas, Sarpras, Tata Usaha, dll.) */
.elementskit-navbar-nav > li.menu-item-has-children,
.elementskit-navbar-nav > li.elementskit-dropdown-has {
	position: relative !important;
}

.elementskit-navbar-nav .elementskit-submenu-panel {
	border-radius: 12px !important;
	padding: 10px 8px !important;
	border: 1px solid rgba(15, 41, 107, 0.08) !important;
	box-shadow: 0 12px 32px rgba(15, 41, 107, 0.16) !important;
	background-color: #FFFFFF !important;
	z-index: 99999 !important;
	pointer-events: auto !important;
}

/* Jembatan hover transparan agar submenu tidak menutup sendiri saat kursor turun ke bawah */
.elementskit-navbar-nav .elementskit-submenu-panel::before {
	content: "";
	position: absolute;
	top: -16px;
	left: 0;
	right: 0;
	height: 16px;
	background: transparent;
}

.elementskit-navbar-nav .elementskit-submenu-panel > li > a {
	color: #1E293B !important;
	font-size: 14.5px !important;
	font-weight: 500 !important;
	padding: 9px 16px !important;
	border-radius: 8px !important;
	transition: all 0.2s ease !important;
	display: block !important;
	position: relative !important;
	z-index: 2 !important;
}

.elementskit-navbar-nav .elementskit-submenu-panel > li:hover > a,
.elementskit-navbar-nav .elementskit-submenu-panel > li > a:hover,
.elementskit-navbar-nav .elementskit-submenu-panel > li.current-menu-item > a {
	color: #1536A2 !important;
	background-color: #F0F4FF !important;
	padding-left: 20px !important;
}

/* ============================================================
   5. Laman PTSP (#26) — Foto Maklumat Pelayanan Sejajar Teks
   ============================================================ */
.elementor-26 .elementor-element.elementor-element-3900a25 {
	align-items: center !important;
}

.elementor-26 .elementor-element.elementor-element-2795770 {
	justify-content: center !important;
	align-items: center !important;
}

.elementor-26 .elementor-element.elementor-element-879f114 {
	margin: 0 !important;
	text-align: center !important;
}

.elementor-26 .elementor-element.elementor-element-879f114 img,
.elementor-26 .elementor-element.elementor-element-a962650 img {
	border: none !important;
	border-width: 0 !important;
	width: auto !important;
	max-width: 100% !important;
	max-height: 440px !important;
	height: auto !important;
	object-fit: contain !important;
	border-radius: 10px !important;
	box-shadow: 0 8px 24px rgba(15, 41, 107, 0.10) !important;
}
CSS;

	wp_add_inline_style( $handle, $css );
}, 999 );
