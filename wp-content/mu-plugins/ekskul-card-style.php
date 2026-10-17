<?php
/**
 * Plugin Name: ICGowa — Tampilan Modern Kartu Ekstrakurikuler
 * Description: Mempercantik tampilan kartu pada listing "KEGIATAN EKSTRAKULIKULER"
 *              (JetEngine listing post 120) agar lebih profesional & modern.
 *              Hanya menambah CSS front-end, tidak mengubah database.
 * Author: ICGowa
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', function () {
	// Pastikan dimuat SETELAH style frontend Elementor agar override-nya berlaku.
	$handle = 'icgowa-ekskul-card-style';
	wp_register_style( $handle, false );
	wp_enqueue_style( $handle );

	$css = <<<CSS
/* ============================================================
   Kartu Ekstrakurikuler — gaya modern & profesional
   Target: JetEngine listing #120 (elemen di-generate Elementor)
   ============================================================ */

/* Kontainer kartu */
.elementor-element-a4e9710 {
	border-radius: 18px !important;
	overflow: hidden !important;
	background-color: #ffffff !important;
	box-shadow: 0 10px 30px -10px rgba(16, 42, 110, 0.25),
	            0 2px 8px rgba(0, 0, 0, 0.06) !important;
	transition: transform 0.40s cubic-bezier(0.22, 1, 0.36, 1),
	            box-shadow 0.40s cubic-bezier(0.22, 1, 0.36, 1) !important;
	will-change: transform;
}

/* Efek angkat halus saat hover */
.elementor-element-a4e9710:hover {
	transform: translateY(-8px) !important;
	border: none !important;
	box-shadow: 0 22px 48px -12px rgba(16, 42, 110, 0.45),
	            0 6px 16px rgba(0, 0, 0, 0.10) !important;
}

/* Wrapper gambar — kunci rasio agar seragam & rapi */
.elementor-element-f6cffbf {
	margin-bottom: 0 !important;
	overflow: hidden !important;
	border-radius: 0 !important;
	position: relative;
}

.elementor-element-f6cffbf img {
	width: 100% !important;
	height: 240px !important;
	object-fit: cover !important;
	display: block !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1) !important;
}

/* Zoom lembut pada gambar ketika kartu di-hover */
.elementor-element-a4e9710:hover .elementor-element-f6cffbf img {
	transform: scale(1.07) !important;
}

/* Overlay gradasi tipis di bawah gambar — menyatu ke box judul */
.elementor-element-f6cffbf::after {
	content: "";
	position: absolute;
	left: 0;
	right: 0;
	bottom: 0;
	height: 38%;
	background: linear-gradient(to top, rgba(0, 0, 0, 0.18), rgba(0, 0, 0, 0));
	pointer-events: none;
	opacity: 0.6;
	transition: opacity 0.4s ease;
}

.elementor-element-a4e9710:hover .elementor-element-f6cffbf::after {
	opacity: 0;
}

/* Box judul */
.elementor-element-e03c452 {
	padding: 20px 16px 22px !important;
	position: relative;
}

/* Garis aksen kecil di atas judul */
.elementor-element-e03c452::before {
	content: "";
	position: absolute;
	top: 0;
	left: 50%;
	transform: translateX(-50%);
	width: 40px;
	height: 3px;
	border-radius: 3px;
	background: var(--e-global-color-primary, #1b2f8a);
	opacity: 0.85;
}

.elementor-element-e03c452 .elementor-heading-title {
	letter-spacing: 0.2px;
	transition: color 0.3s ease;
}

/* Sedikit jarak antar kartu di carousel agar tidak berdempetan */
.elementor-element-a4e9710 {
	margin: 12px 8px !important;
}
CSS;

	wp_add_inline_style( $handle, $css );
}, 99 );
