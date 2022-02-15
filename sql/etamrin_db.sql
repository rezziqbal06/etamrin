-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2022 at 11:09 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `etamrin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `a_kelas`
--

CREATE TABLE `a_kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(56) NOT NULL,
  `deskripsi` text NOT NULL,
  `wali_kelas` varchar(128) NOT NULL DEFAULT '',
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `a_kelas`
--

INSERT INTO `a_kelas` (`id`, `nama`, `deskripsi`, `wali_kelas`, `is_active`) VALUES
(2, 'X IPA 1', '', 'Pa Guru', 1),
(3, 'X IPA 2', '', 'Pa Guru', 1),
(4, 'X IPA 3', '', 'Pa Guru', 1),
(5, 'X IPS 1', '', 'Pa Guru', 1),
(6, 'XI IPS 2', '', 'Pa Guru', 1);

-- --------------------------------------------------------

--
-- Table structure for table `a_modules`
--

CREATE TABLE `a_modules` (
  `identifier` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT '',
  `level` int(1) NOT NULL DEFAULT 0 COMMENT 'depth level of menu, 0 mean outer 3 deeper submenu',
  `has_submenu` int(1) NOT NULL DEFAULT 0 COMMENT '1 mempunyai submenu, 2 tidak mempunyai submenu',
  `children_identifier` varchar(255) DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `is_default` enum('allowed','denied') NOT NULL DEFAULT 'denied',
  `is_visible` int(1) NOT NULL DEFAULT 1,
  `priority` int(3) NOT NULL DEFAULT 0 COMMENT '0 mean higher 999 lower',
  `fa_icon` varchar(255) NOT NULL DEFAULT 'fa fa-home' COMMENT 'font-awesome icon on menu',
  `utype` varchar(48) NOT NULL DEFAULT 'internal' COMMENT 'type module : internal, external'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='list modul yang ada dimenu atau tidak ada dimenu';

--
-- Dumping data for table `a_modules`
--

INSERT INTO `a_modules` (`identifier`, `name`, `path`, `level`, `has_submenu`, `children_identifier`, `is_active`, `is_default`, `is_visible`, `priority`, `fa_icon`, `utype`) VALUES
('akun', 'Akun', 'akun', 0, 1, NULL, 1, 'denied', 1, 4, 'fa fa-users', 'internal'),
('akun_grup', 'Grup', 'akun/grup', 1, 0, 'akun', 1, 'denied', 0, 0, 'fa fa-home', 'internal'),
('akun_hak_akses', 'Hak Akses Pengguna', 'akun/hak_akses', 1, 0, 'akun', 1, 'denied', 0, 3, 'fa fa-edit', 'internal'),
('akun_karyawan', 'Karyawan', 'akun/karyawan', 1, 0, 'akun', 1, 'denied', 0, 2, 'fa fa-home', 'internal'),
('akun_pelanggan', 'Pelanggan', 'akun/pelanggan', 1, 0, 'akun', 1, 'denied', 0, 20, 'fa fa-home', 'internal'),
('akun_pengguna', 'Super Admin', 'akun/pengguna', 1, 0, 'akun', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('akun_usergroup', 'Grup Pelanggan', 'akun/usergroup', 1, 0, 'akun', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('alamatongkir', 'Alamat &amp; Ongkir', '#', 0, 1, NULL, 1, 'denied', 0, 1, 'fa fa-building', 'internal'),
('alamatongkir_jne', 'JNE', 'alamatongkir/jne', 1, 0, 'alamatongkir', 1, 'denied', 1, 71, 'fa fa-home', 'internal'),
('alamatongkir_jnt', 'JNT', 'alamatongkir/jnt', 1, 0, 'alamatongkir', 1, 'denied', 0, 72, 'fa fa-home', 'internal'),
('alamatongkir_kabkota', 'Kabkota', 'alamatongkir/kabkota', 1, 0, 'alamatongkir', 1, 'denied', 1, 52, 'fa fa-home', 'internal'),
('alamatongkir_kecamatan', 'Kecamatan', 'alamatongkir/kecamatan', 1, 0, 'alamatongkir', 1, 'denied', 1, 53, 'fa fa-home', 'internal'),
('alamatongkir_kelurahan', 'Kelurahan', 'alamatongkir/kelurahan', 1, 0, 'alamatongkir', 1, 'denied', 0, 54, 'fa fa-home', 'internal'),
('alamatongkir_negara', 'Negara', 'alamatongkir/negara', 1, 0, 'alamatongkir', 1, 'denied', 1, 50, 'fa fa-home', 'internal'),
('alamatongkir_posems', 'POS EMS', 'alamatongkir/posems', 1, 0, 'alamatongkir', 1, 'denied', 0, 74, 'fa fa-home', 'internal'),
('alamatongkir_poskilat', 'POS Kilat', 'alamatongkir/poskilat', 1, 0, 'alamatongkir', 1, 'denied', 0, 73, 'fa fa-home', 'internal'),
('alamatongkir_provinsi', 'Provinsi', 'alamatongkir/provinsi', 1, 0, 'alamatongkir', 1, 'denied', 1, 51, 'fa fa-home', 'internal'),
('blog', 'Blog', 'blog', 0, 0, NULL, 1, 'denied', 1, 3, 'fa fa-file-text-o', 'internal'),
('blog_kategori', 'Kategori', 'blog/kategori', 1, 0, 'blog', 1, 'denied', 1, 12, 'fa fa-home', 'internal'),
('blog_post', 'Post', 'blog/post', 1, 0, 'blog', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('cabang', 'Cabang', '#', 0, 1, NULL, 1, 'denied', 0, 4, 'fa fa-user-secret', 'internal'),
('cabang_barang', 'Barang per Cabang', 'cabang/barang', 1, 0, 'cabang', 1, 'denied', 1, 20, 'fa fa-home', 'internal'),
('cabang_detail', 'Detail', 'cabang/detail', 1, 0, 'cabang', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('cabang_pengumuman', 'Pengumuman', 'cabang/pengumuman', 1, 0, 'erpmaster', 1, 'denied', 1, 84, 'fa fa-home', 'internal'),
('cms', 'CMS', 'cms', 0, 1, NULL, 1, 'denied', 1, 12, 'fa fa-file-text-o', 'internal'),
('cms_blog', 'Blog', 'cms/blog', 1, 0, 'cms', 1, 'denied', 0, 21, 'fa fa-home', 'internal'),
('cms_homepage', 'Homepage', 'cms/homepage', 1, 0, 'cms', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('cms_media', 'Media', 'cms/media', 1, 0, 'cms', 1, 'denied', 1, 20, 'fa fa-home', 'internal'),
('cms_menu', 'Menu', 'cms/menu', 1, 0, 'cms', 1, 'denied', 1, 11, 'fa fa-home', 'internal'),
('cms_portofolio', 'Portofolio', 'cms/portofolio', 1, 0, 'cms', 1, 'denied', 1, 30, 'fa fa-home', 'internal'),
('cms_slider', 'Slider', 'cms/slider', 1, 0, 'cms', 1, 'denied', 1, 12, 'fa fa-home', 'internal'),
('cms_testimonial', 'Testimonial', 'cms/testimonial', 1, 0, 'cms', 1, 'denied', 1, 22, 'fa fa-home', 'internal'),
('crm', 'CRM', 'crm', 0, 1, NULL, 1, 'denied', 1, 7, 'fa fa-heartbeat', 'internal'),
('crm_booking', 'Booking', 'crm/booking', 1, 0, 'crm', 1, 'denied', 0, 1, 'fa fa-home', 'internal'),
('crm_cs', 'CS', 'crm/cs', 1, 0, 'crm', 1, 'denied', 0, 3, 'fa fa-home', 'internal'),
('crm_guestbook', 'Guest Book', 'crm/guestbook', 1, 0, 'crm', 1, 'denied', 1, 22, 'fa fa-home', 'internal'),
('crm_kategori', 'Kategori', 'crm/kategori', 1, 0, 'crm', 1, 'denied', 0, 1, 'fa fa-home', 'internal'),
('crm_komplain', 'Komplain', 'crm/komplain', 1, 0, 'crm', 1, 'denied', 0, 5, 'fa fa-home', 'internal'),
('crm_konsultasi', 'Konsultasi', 'crm/konsultasi', 1, 0, 'crm', 1, 'denied', 0, 0, 'fa fa-home', 'internal'),
('crm_pemberitahuan', 'Pemberitahuan', 'crm/pemberitahuan', 1, 0, 'crm', 1, 'denied', 0, 3, 'fa fa-home', 'internal'),
('crm_pesan', 'Pesan', 'crm/pesan', 1, 0, 'crm', 1, 'denied', 0, 0, 'fa fa-home', 'internal'),
('crm_retur', 'Retur', 'crm/retur', 1, 0, 'crm', 1, 'denied', 0, 4, 'fa fa-home', 'internal'),
('crm_support', 'Support', 'crm/support', 1, 0, 'crm', 1, 'denied', 0, 2, 'fa fa-home', 'internal'),
('dashboard', 'Dashboard', '', 0, 0, NULL, 1, 'allowed', 1, 0, 'fa fa-home', 'internal'),
('ecommerce', 'Ecommerce', 'ecommerce', 0, 1, NULL, 1, 'denied', 1, 10, 'fa fa-shopping-cart', 'internal'),
('ecommerce_bank', 'Bank', 'ecommerce/bank', 1, 0, 'ecommerce', 1, 'denied', 0, 80, 'fa fa-home', 'internal'),
('ecommerce_bantuan', 'Bantuan (CS)', 'ecommerce/bantuan', 1, 0, 'ecommerce', 1, 'denied', 0, 60, 'fa fa-home', 'internal'),
('ecommerce_custom', 'Custom', 'ecommerce/custom', 1, 0, 'ecommerce', 1, 'denied', 0, 20, 'fa fa-home', 'internal'),
('ecommerce_homepage', 'Homepage', 'ecommerce/homepage', 1, 1, 'ecommerce', 1, 'denied', 0, 50, 'fa fa-home', 'internal'),
('ecommerce_homepage_produk', 'Produk', 'ecommerce/homepage/produk', 2, 0, 'ecommerce_homepage', 1, 'denied', 1, 52, 'fa fa-home', 'internal'),
('ecommerce_homepage_slider', 'Slider', 'ecommerce/homepage/slider', 2, 0, 'ecommerce_homepage', 1, 'denied', 1, 52, 'fa fa-home', 'internal'),
('ecommerce_kategori', 'Kategori', 'ecommerce/kategori', 1, 0, 'ecommerce', 1, 'denied', 1, 20, 'fa fa-home', 'internal'),
('ecommerce_menu', 'Menu', 'ecommerce/menu', 1, 0, 'ecommerce', 1, 'denied', 0, 50, 'fa fa-home', 'internal'),
('ecommerce_order', 'Order', 'ecommerce/order', 1, 0, 'ecommerce', 1, 'denied', 0, 10, 'fa fa-home', 'internal'),
('ecommerce_pelanggan', 'Pelanggan', 'ecommerce/pelanggan', 1, 0, 'ecommerce', 1, 'denied', 1, 60, 'fa fa-home', 'internal'),
('ecommerce_pembayaran', 'Cara Bayar', 'ecommerce/pembayaran', 1, 0, 'ecommerce', 1, 'denied', 0, 81, 'fa fa-home', 'internal'),
('ecommerce_pengaturan', 'Pengaturan', 'ecommerce/pengaturan', 1, 0, 'ecommerce', 1, 'denied', 0, 90, 'fa fa-home', 'internal'),
('ecommerce_pengiriman', 'Pengiriman', 'ecommerce/pengiriman', 1, 0, 'ecommerce', 1, 'denied', 0, 45, 'fa fa-home', 'internal'),
('ecommerce_produk', 'Produk', 'ecommerce/produk', 1, 0, 'ecommerce', 1, 'denied', 1, 25, 'fa fa-home', 'internal'),
('ecommerce_promo', 'Promo', 'ecommerce/promo', 1, 0, 'ecommerce', 1, 'denied', 0, 56, 'fa fa-home', 'internal'),
('ecommerce_slider', 'Slider', 'ecommerce/slider', 1, 0, 'ecommerce', 1, 'denied', 1, 55, 'fa fa-home', 'internal'),
('erpmaster', 'ERP Master', '#', 0, 1, NULL, 1, 'denied', 0, 1, 'fa fa-building', 'internal'),
('erpmaster_bank', 'Bank', 'erpmaster/bank', 1, 0, 'erpmaster', 1, 'denied', 1, 60, 'fa fa-home', 'internal'),
('erpmaster_brand', 'Brand', 'erpmaster/brand', 1, 0, 'erpmaster', 1, 'denied', 0, 41, 'fa fa-home', 'internal'),
('erpmaster_company', 'Cabang', 'erpmaster/company', 1, 0, 'erpmaster', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('erpmaster_gudang', 'Gudang', 'erpmaster/gudang', 1, 0, 'erpmaster', 1, 'denied', 1, 12, 'fa fa-home', 'internal'),
('erpmaster_jabatan', 'Jabatan', 'erpmaster/jabatan', 1, 0, 'erpmaster', 1, 'denied', 1, 11, 'fa fa-home', 'internal'),
('erpmaster_pembayaran', 'Cara Bayar', 'erpmaster/pembayaran', 1, 0, 'erpmaster', 1, 'denied', 0, 61, 'fa fa-home', 'internal'),
('erpmaster_toko', 'Toko', 'erpmaster/toko', 1, 0, 'erpmaster', 1, 'denied', 0, 41, 'fa fa-home', 'internal'),
('erpmaster_vendor', 'Supplier', 'erpmaster/vendor', 1, 0, 'erpmaster', 1, 'denied', 1, 40, 'fa fa-home', 'internal'),
('event', 'Event', '#', 0, 0, NULL, 1, 'denied', 1, 4, 'fa fa-calendar', 'internal'),
('event_kajian', 'Kajian', 'event/kajian', 1, 0, 'event', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('event_kegiatan', 'Kegiatan', 'event/kegiatan', 1, 0, 'event', 1, 'denied', 1, 2, 'fa fa-home', 'internal'),
('gudang', 'Gudang', '#', 0, 1, NULL, 1, 'denied', 0, 8, 'fa fa-gift', 'internal'),
('gudang_bahanbaku', 'Bahan Baku', 'gudang/bb', 1, 0, 'gudang', 1, 'denied', 1, 70, 'fa fa-home', 'internal'),
('gudang_pengajuan', 'Pengajuan', 'gudang/pengajuan', 1, 0, 'gudang', 1, 'denied', 1, 60, 'fa fa-home', 'internal'),
('gudang_pengiriman', 'Pengiriman Barang', 'gudang/pengiriman', 1, 0, 'gudang', 1, 'denied', 1, 12, 'fa fa-home', 'internal'),
('gudang_permintaan', 'Permintaan Barang', 'gudang/permintaan', 1, 0, 'gudang', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('gudang_persetujuan', 'Persetujuan Permintaan', 'gudang/persetujuan', 1, 0, 'gudang', 1, 'denied', 1, 11, 'fa fa-home', 'internal'),
('gudang_pindah', 'Pindah Barang', 'gudang/pindah', 1, 0, 'gudang', 1, 'denied', 1, 30, 'fa fa-home', 'internal'),
('gudang_produksi', 'Barang Produksi', 'gudang/produksi', 1, 0, 'gudang', 1, 'denied', 1, 31, 'fa fa-home', 'internal'),
('gudang_retur', 'Barang Retur', 'gudang/retur', 1, 0, 'gudang', 1, 'denied', 1, 32, 'fa fa-home', 'internal'),
('gudang_so', 'Stok Opname', 'gudang/so', 1, 0, 'gudang', 1, 'denied', 1, 33, 'fa fa-home', 'internal'),
('gudang_stok', 'Penyimpanan &amp; Stok', 'gudang/stok', 1, 0, 'gudang', 1, 'denied', 1, 34, 'fa fa-home', 'internal'),
('hr', 'HR', '#', 0, 1, NULL, 1, 'denied', 0, 5, 'fa fa-user', 'internal'),
('keuangan', 'Keuangan', 'keuangan', 0, 1, NULL, 1, 'denied', 0, 6, 'fa fa-money', 'internal'),
('keuangan_deposit', 'Deposit', 'keuangan/deposit', 1, 0, 'keuangan', 1, 'denied', 1, 6, 'fa fa-home', 'internal'),
('keuangan_inventaris', 'Inventaris', 'keuangan/inventaris', 1, 0, 'keuangan', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('keuangan_kas', 'Kas', 'keuangan/kas', 1, 0, 'keuangan', 1, 'denied', 1, 2, 'fa fa-home', 'internal'),
('keuangan_laporan', 'Laporan', 'keuangan/laporan', 1, 0, 'keuangan', 1, 'denied', 1, 12, 'fa fa-home', 'internal'),
('keuangan_memo', 'Kredit Memo', 'keuangan/memo', 1, 0, 'keuangan', 1, 'denied', 1, 7, 'fa fa-home', 'internal'),
('keuangan_pengajuan', 'Pengajuan', 'keuangan/pengajuan', 1, 0, 'keuangan', 1, 'denied', 1, 4, 'fa fa-home', 'internal'),
('keuangan_perjalanan', 'Perjalanan', 'keuangan/perjalanan', 1, 0, 'keuangan', 1, 'denied', 1, 3, 'fa fa-home', 'internal'),
('keuangan_rekening', 'Rekening', 'keuangan/rekening', 1, 0, 'keuangan', 1, 'denied', 1, 8, 'fa fa-home', 'internal'),
('klinik', 'Klinik', '#', 0, 1, NULL, 1, 'denied', 0, 5, 'fa fa-asterisk', 'internal'),
('klinik_rekammedis', 'Rekam Medis', 'klinik/rekammedis', 1, 0, 'klinik', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('laporan', 'Laporan', 'laporan', 0, 1, NULL, 1, 'denied', 0, 30, 'fa fa-file-text', 'internal'),
('laporan_asistensi', 'Asistensi', 'laporan/asistensi', 1, 0, 'laporan', 1, 'denied', 1, 11, 'fa fa-home', 'internal'),
('laporan_barang', 'Barang', 'laporan/barang', 1, 0, 'laporan', 1, 'denied', 0, 5, 'fa fa-home', 'internal'),
('laporan_bestseller', 'Best Seller', 'laporan/bestseller', 1, 0, 'laporan', 1, 'denied', 1, 24, 'fa fa-home', 'internal'),
('laporan_booking', 'Booking', 'laporan/booking', 1, 0, 'laporan', 0, 'denied', 0, 3, 'fa fa-home', 'internal'),
('laporan_kasir', 'Kasir', 'laporan/kasir', 1, 0, 'laporan', 1, 'denied', 1, 7, 'fa fa-home', 'internal'),
('laporan_pelanggan', 'Pelanggan', 'laporan/pelanggan', 1, 0, 'laporan', 1, 'denied', 1, 2, 'fa fa-home', 'internal'),
('laporan_penjualan', 'Penjualan', 'laporan/penjualan', 1, 0, 'laporan', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('laporan_terapis', 'Terapis', 'laporan/terapis', 1, 0, 'laporan', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('pembelian', 'Pembelian', 'pembelian', 0, 1, NULL, 1, 'denied', 0, 5, 'fa fa-opencart', 'internal'),
('pembelian_barang', 'Pembelian Barang', 'pembelian/barang', 1, 0, 'pembelian', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('pembelian_historystok', 'History Stok', 'pembelian/historystok', 1, 0, 'pembelian', 1, 'denied', 1, 3, 'fa fa-home', 'internal'),
('pembelian_karantina', 'Karantina Batch', 'pembelian/karantina', 1, 0, 'pembelian', 1, 'denied', 1, 5, 'fa fa-home', 'internal'),
('pembelian_mutasi', 'Mutasi', 'pembelian/mutasi', 1, 0, 'pembelian', 1, 'denied', 1, 4, 'fa fa-home', 'internal'),
('pembelian_orderan', 'Orderan', 'pembelian/orderan', 1, 0, 'pembelian', 1, 'denied', 0, 8, 'fa fa-home', 'internal'),
('pembelian_produksi', 'Produksi', 'pembelian/produksi', 1, 0, 'pembelian', 1, 'denied', 0, 9, 'fa fa-home', 'internal'),
('pembelian_racikan', 'Proses Racikan', 'pembelian/racikan', 1, 0, 'pembelian', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('pengiriman', 'Pengiriman', 'pengiriman', 0, 1, NULL, 1, 'denied', 0, 5, 'fa fa-truck', 'internal'),
('pengiriman_ekspedisi', 'Ekspedisi', 'pengriman/ekspedisi', 1, 0, 'pengiriman', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('pengiriman_kurir', 'Kurir', 'pengiriman/kurir', 1, 0, 'pengiriman', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('pengiriman_packing', 'Packing', 'pengiriman/packing', 1, 0, 'pengiriman', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('pengiriman_qc', 'QC', 'pengiriman/qc', 1, 0, 'pengiriman', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('penjualan', 'Penjualan', 'penjualan', 0, 1, NULL, 1, 'denied', 0, 9, 'fa fa-book', 'internal'),
('penjualan_bonus', 'Bonus', 'penjualan/bonus', 1, 0, 'penjualan', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('penjualan_cabang', 'Cabang', 'penjualan/cabang', 0, 0, 'penjualan', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('penjualan_offline', 'Offline', 'penjualan/offline', 1, 0, 'penjualan', 0, 'denied', 0, 20, 'fa fa-home', 'internal'),
('penjualan_online', 'Online', 'penjualan/online', 1, 0, 'penjualan', 1, 'denied', 0, 30, 'fa fa-home', 'internal'),
('penjualan_outlet', 'Outlet (offline)', 'penjualan/outlet', 1, 0, 'penjualan', 1, 'denied', 0, 21, 'fa fa-home', 'internal'),
('penjualan_promosi', 'Promosi', 'penjualan/promosi', 1, 0, 'penjualan', 1, 'denied', 1, 6, 'fa fa-home', 'internal'),
('penjualan_specialprice', 'Special Price', 'penjualan/specialprice', 1, 0, 'penjualan', 1, 'denied', 1, 4, 'fa fa-home', 'internal'),
('penjualan_voucher', 'Voucher', 'penjualan/voucher', 1, 0, 'penjualan', 1, 'denied', 1, 6, 'fa fa-home', 'internal'),
('po', 'P.O.', '#', 0, 1, NULL, 1, 'denied', 0, 16, 'fa fa-dropbox', 'internal'),
('po_faktur', 'Faktur', 'po/faktur', 1, 0, 'po', 1, 'denied', 1, 22, 'fa fa-home', 'internal'),
('po_konfirmasipesanan', 'Konfirmasi Pesanan', 'po/konfirmasipesanan', 1, 0, 'po', 1, 'denied', 1, 2, 'fa fa-home', 'internal'),
('po_outstanding', 'Outstanding', 'po/outstanding', 1, 0, 'po', 1, 'denied', 1, 42, 'fa fa-home', 'internal'),
('po_penerimaan', 'Penerimaan', 'po/penerimaan', 1, 0, 'po', 1, 'denied', 1, 30, 'fa fa-home', 'internal'),
('po_pengiriman', 'Pengiriman', 'po/pengiriman', 1, 0, 'po', 1, 'denied', 1, 20, 'fa fa-home', 'internal'),
('po_permintaan', 'Permintaan', 'po/permintaan', 1, 0, 'po', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('po_persetujuan', 'Persetujuan', 'po/persetujuan', 1, 0, 'po', 0, 'denied', 0, 10, 'fa fa-home', 'internal'),
('po_selisih', 'Selisih', 'po/selisih', 1, 0, 'po', 1, 'denied', 1, 40, 'fa fa-home', 'internal'),
('po_suratjalan', 'Surat Jalan', 'po/suratjalan', 1, 0, 'po', 1, 'denied', 0, 21, 'fa fa-home', 'internal'),
('po_suratpenerimaan', 'Surat Penerimaan', 'po/suratpenerimaan', 1, 0, 'po', 1, 'denied', 1, 32, 'fa fa-home', 'internal'),
('po_suratpesanan', 'Surat Pesanan', 'po/suratpesanan', 1, 0, 'po', 1, 'denied', 1, 3, 'fa fa-home', 'internal'),
('po_transit', 'Transit Pengiriman', 'po/transit', 1, 0, 'po', 1, 'denied', 1, 22, 'fa fa-home', 'internal'),
('po_verifikasipenerimaan', 'Verifikasi Penerimaan', 'po/verifikasipenerimaan', 1, 0, 'po', 1, 'denied', 1, 31, 'fa fa-home', 'internal'),
('produk', 'Produk Master', '#', 0, 1, NULL, 1, 'denied', 0, 3, 'fa fa-building', 'internal'),
('produksi', 'Produksi', 'produksi', 0, 1, NULL, 1, 'denied', 0, 6, 'fa fa-cogs', 'internal'),
('produksi_pekerjaan', 'Pekerjaan', 'produksi/pekerjaan', 1, 0, 'produksi', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('produksi_proses', 'Proses', 'produksi/proses', 1, 0, 'produksi', 1, 'denied', 1, 3, 'fa fa-home', 'internal'),
('produksi_rencana', 'Rencana', 'produksi/rencana', 1, 0, 'produksi', 1, 'denied', 1, 2, 'fa fa-home', 'internal'),
('produk_barang', 'Barang', 'produk/barang', 1, 0, 'produk', 1, 'denied', 1, 15, 'fa fa-home', 'internal'),
('produk_grup', 'Grup Barang', 'produk/grup', 1, 0, 'produk', 1, 'denied', 0, 3, 'fa fa-home', 'internal'),
('produk_jasa', 'Jasa', 'produk/jasa', 1, 0, 'produk', 1, 'denied', 1, 16, 'fa fa-home', 'internal'),
('produk_jenis', 'Grup Jenis', 'produk/jenis', 1, 0, 'produk', 1, 'denied', 1, 5, 'fa fa-home', 'internal'),
('produk_kategori', 'Kategori Produk', 'produk/kategori', 1, 0, 'produk', 1, 'denied', 1, 10, 'fa fa-home', 'internal'),
('produk_meta', 'Meta', 'produk/meta', 1, 0, 'produk', 1, 'denied', 0, 11, 'fa fa-home', 'internal'),
('produk_paket', 'Paket', 'produk/paket', 1, 0, 'produk', 1, 'denied', 1, 20, 'fa fa-home', 'internal'),
('produk_produk', 'Produk', 'produk/produk', 1, 0, 'produk', 1, 'denied', 0, 12, 'fa fa-home', 'internal'),
('produk_racikan', 'Racikan', 'produk/racikan', 1, 0, 'produk', 1, 'denied', 1, 20, 'fa fa-home', 'internal'),
('produk_satuan', 'Satuan/Unit', 'produk/satuan', 1, 0, 'produk', 1, 'denied', 1, 4, 'fa fa-home', 'internal'),
('retur', 'retur', '#', 0, 1, NULL, 1, 'denied', 0, 16, 'fa fa-sign-out', 'internal'),
('retur_barang', 'Barang', 'retur/barang', 1, 0, 'retur', 0, 'denied', 0, 1, 'fa fa-home', 'internal'),
('retur_kirim', 'Pengiriman', 'retur/kirim', 1, 0, 'retur', 1, 'denied', 1, 1, 'fa fa-home', 'internal'),
('retur_mutasi', 'Mutasi', 'retur/mutasi', 1, 0, 'retur', 0, 'denied', 0, 11, 'fa fa-home', 'internal'),
('retur_recycle', 'Daur Ulang', 'retur/recycle', 1, 0, 'retur', 1, 'denied', 1, 20, 'fa fa-home', 'internal'),
('retur_selisih', 'Selisih', 'retur/selisih', 1, 0, 'retur', 1, 'denied', 1, 6, 'fa fa-home', 'internal'),
('retur_terima', 'Penerimaan', 'retur/terima', 1, 0, 'retur', 1, 'denied', 1, 2, 'fa fa-home', 'internal'),
('retur_transit', 'Transit Pengiriman', 'retur/transit', 1, 0, 'retur', 1, 'denied', 1, 3, 'fa fa-home', 'internal');

-- --------------------------------------------------------

--
-- Table structure for table `a_pengguna`
--

CREATE TABLE `a_pengguna` (
  `id` int(4) NOT NULL,
  `a_company_id` int(5) DEFAULT NULL COMMENT 'penempatan',
  `a_company_nama` varchar(255) NOT NULL DEFAULT '-',
  `a_company_kode` varchar(32) NOT NULL DEFAULT '-',
  `a_jabatan_id` int(11) DEFAULT NULL,
  `a_jabatan_nama` varchar(255) NOT NULL DEFAULT 'Staff',
  `username` varchar(24) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `welcome_message` varchar(255) NOT NULL,
  `scope` enum('all','current_below','current_only','none') NOT NULL DEFAULT 'none',
  `nip` varchar(32) DEFAULT '-',
  `alamat` varchar(255) NOT NULL,
  `alamat_kecamatan` varchar(255) NOT NULL,
  `alamat_kabkota` varchar(255) NOT NULL,
  `alamat_provinsi` varchar(255) NOT NULL,
  `alamat_negara` varchar(255) NOT NULL,
  `alamat_kodepos` varchar(12) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` int(1) NOT NULL DEFAULT 1,
  `status_pernikahan` enum('belum menikah','menikah','duda','janda') NOT NULL DEFAULT 'belum menikah',
  `telp_rumah` varchar(25) NOT NULL,
  `telp_hp` varchar(25) NOT NULL,
  `bank_rekening_nomor` varchar(255) NOT NULL,
  `bank_rekening_nama` varchar(255) NOT NULL,
  `bank_nama` varchar(255) NOT NULL,
  `kerja_terakhir` varchar(255) NOT NULL,
  `kerja_terakhir_jabatan` varchar(255) NOT NULL,
  `kerja_terakhir_gaji` float NOT NULL,
  `pendidikan_terakhir` varchar(255) NOT NULL,
  `pendidikan_terakhir_jenjang` enum('SD','SMP','SMA','S1','D3','D2','S2') NOT NULL DEFAULT 'SMA',
  `pendidikan_terakhir_tahun` year(4) NOT NULL DEFAULT 1971,
  `ibu_nama` varchar(255) NOT NULL,
  `ibu_pekerjaan` varchar(255) NOT NULL,
  `tgl_kerja_mulai` date NOT NULL,
  `tgl_kerja_akhir` date NOT NULL,
  `tgl_kontrak_akhir` date DEFAULT NULL,
  `karyawan_status` enum('Kontrak','Magang','Tetap','Harian Lepas') NOT NULL,
  `is_karyawan` int(1) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `a_pengguna_id` int(11) DEFAULT NULL COMMENT 'atasan langsung'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='tabel pengguna';

--
-- Dumping data for table `a_pengguna`
--

INSERT INTO `a_pengguna` (`id`, `a_company_id`, `a_company_nama`, `a_company_kode`, `a_jabatan_id`, `a_jabatan_nama`, `username`, `password`, `email`, `nama`, `foto`, `welcome_message`, `scope`, `nip`, `alamat`, `alamat_kecamatan`, `alamat_kabkota`, `alamat_provinsi`, `alamat_negara`, `alamat_kodepos`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `status_pernikahan`, `telp_rumah`, `telp_hp`, `bank_rekening_nomor`, `bank_rekening_nama`, `bank_nama`, `kerja_terakhir`, `kerja_terakhir_jabatan`, `kerja_terakhir_gaji`, `pendidikan_terakhir`, `pendidikan_terakhir_jenjang`, `pendidikan_terakhir_tahun`, `ibu_nama`, `ibu_pekerjaan`, `tgl_kerja_mulai`, `tgl_kerja_akhir`, `tgl_kontrak_akhir`, `karyawan_status`, `is_karyawan`, `is_active`, `a_pengguna_id`) VALUES
(1, NULL, '-', '-', NULL, 'Staff', 'mimin', '$2y$10$8pUAp1hW/inr2woacSh9y.Cxy1frZmAUxxaQCTps.lbIaE/YzHECu', 'drosanda@outlook.co.id', 'S-Admin1', 'media/pengguna/2019/06/c4ca4238a0b923820dcc509a6f75849b2222.png', 'Selamat Beraktifitas', 'all', '-', '', '', '', '', '', '', '', NULL, 1, 'belum menikah', '', '', '', '', '', '', '', 0, '', 'SMA', 1971, '', '', '0000-00-00', '0000-00-00', NULL, 'Kontrak', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `a_pengguna_module`
--

CREATE TABLE `a_pengguna_module` (
  `id` int(8) NOT NULL,
  `a_pengguna_id` int(4) NOT NULL,
  `a_modules_identifier` varchar(255) DEFAULT NULL,
  `rule` enum('allowed','disallowed','allowed_except','disallowed_except') NOT NULL,
  `tmp_active` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='hak akses pengguna';

--
-- Dumping data for table `a_pengguna_module`
--

INSERT INTO `a_pengguna_module` (`id`, `a_pengguna_id`, `a_modules_identifier`, `rule`, `tmp_active`) VALUES
(1, 1, NULL, 'allowed_except', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `b_user`
--

CREATE TABLE `b_user` (
  `id` int(7) NOT NULL,
  `a_kelas_id` int(5) DEFAULT NULL,
  `fb_id` varchar(128) DEFAULT NULL,
  `google_id` varchar(128) DEFAULT NULL,
  `kode` varchar(24) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fnama` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'First Name',
  `lnama` varchar(255) NOT NULL COMMENT 'lastname',
  `utype` enum('admin','user','tim') NOT NULL DEFAULT 'user',
  `latitude` decimal(14,10) NOT NULL,
  `longitude` decimal(14,10) NOT NULL,
  `gender` int(1) DEFAULT NULL COMMENT 'gender, 1=male,0=female',
  `bplace` varchar(48) NOT NULL DEFAULT '-',
  `bdate` date DEFAULT NULL COMMENT 'birth dat',
  `cdate` datetime NOT NULL COMMENT 'created date',
  `adate` date DEFAULT NULL COMMENT 'activation date',
  `edate` date DEFAULT NULL COMMENT 'deactivation date',
  `telp` varchar(25) NOT NULL COMMENT 'phone number',
  `image` varchar(255) NOT NULL COMMENT 'internal profile picture url',
  `intro_teks` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Profile welcome text',
  `alamat` varchar(72) NOT NULL DEFAULT '',
  `alamat2` varchar(72) NOT NULL DEFAULT '',
  `kecamatan` varchar(48) NOT NULL DEFAULT '',
  `kabkota` varchar(72) NOT NULL DEFAULT '-',
  `provinsi` varchar(72) NOT NULL DEFAULT '',
  `kodepos` varchar(8) NOT NULL DEFAULT '',
  `pekerjaan` varchar(48) NOT NULL DEFAULT '-',
  `fb` varchar(72) NOT NULL DEFAULT '',
  `ig` varchar(72) NOT NULL DEFAULT '',
  `api_web_token` varchar(48) NOT NULL DEFAULT '' COMMENT 'token for web api req',
  `api_mobile_token` varchar(32) NOT NULL DEFAULT '' COMMENT 'token for mobile api req',
  `api_social_id` varchar(48) NOT NULL DEFAULT '' COMMENT 'Social ID obtained from third party API request result',
  `fcm_token` varchar(255) NOT NULL COMMENT 'firebase content messaging token',
  `poin` int(11) NOT NULL,
  `device` varchar(16) NOT NULL DEFAULT 'android',
  `is_agree` int(1) NOT NULL DEFAULT 0 COMMENT 'accepted aggrement',
  `is_confirmed` int(1) NOT NULL DEFAULT 0 COMMENT 'email confirmation, 1=confirmed, 0=not yet',
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='customer table, for seller and buyer';

-- --------------------------------------------------------

--
-- Table structure for table `b_user_angkatan`
--

CREATE TABLE `b_user_angkatan` (
  `id` int(4) NOT NULL,
  `b_user_id` int(4) NOT NULL,
  `a_kelas_id` int(4) NOT NULL,
  `angkatan` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `c_tugas`
--

CREATE TABLE `c_tugas` (
  `id` int(4) NOT NULL,
  `a_pengguna_id` int(4) NOT NULL,
  `a_kelas_ids` varchar(56) NOT NULL DEFAULT '',
  `judul` varchar(128) NOT NULL DEFAULT '',
  `deskripsi` text NOT NULL DEFAULT '',
  `type` varchar(56) NOT NULL DEFAULT 'dokumen',
  `cdate` datetime NOT NULL,
  `sdate` datetime NOT NULL,
  `edate` datetime NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `c_tugas_user`
--

CREATE TABLE `c_tugas_user` (
  `id` int(11) NOT NULL,
  `c_tugas_id` int(11) DEFAULT NULL,
  `b_user_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `status` enum('pending','accept','reject','revision') NOT NULL DEFAULT 'pending',
  `nilai` varchar(6) NOT NULL DEFAULT '',
  `catatan` varchar(255) NOT NULL DEFAULT '',
  `count_revision` int(11) DEFAULT NULL,
  `is_late` int(1) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `a_kelas`
--
ALTER TABLE `a_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `a_modules`
--
ALTER TABLE `a_modules`
  ADD PRIMARY KEY (`identifier`),
  ADD KEY `children_identifier` (`children_identifier`);

--
-- Indexes for table `a_pengguna`
--
ALTER TABLE `a_pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `a_pengguna_username_unq` (`username`),
  ADD KEY `a_company_id` (`a_company_id`),
  ADD KEY `a_jabatan_id` (`a_jabatan_id`),
  ADD KEY `nip` (`nip`),
  ADD KEY `a_pengguna_id` (`a_pengguna_id`);

--
-- Indexes for table `a_pengguna_module`
--
ALTER TABLE `a_pengguna_module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fka_pengguna_id` (`a_pengguna_id`),
  ADD KEY `fka_modules_identifier` (`a_modules_identifier`);

--
-- Indexes for table `b_user`
--
ALTER TABLE `b_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_b_user_unq` (`email`),
  ADD KEY `idx_api_web_token` (`api_web_token`),
  ADD KEY `api_social_id` (`api_social_id`),
  ADD KEY `fb_id` (`fb_id`),
  ADD KEY `google_id` (`google_id`),
  ADD KEY `device` (`device`),
  ADD KEY `username` (`kode`),
  ADD KEY `a_kelas_id` (`a_kelas_id`) USING BTREE;

--
-- Indexes for table `b_user_angkatan`
--
ALTER TABLE `b_user_angkatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `c_tugas`
--
ALTER TABLE `c_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `a_pengguna_id` (`a_pengguna_id`);

--
-- Indexes for table `c_tugas_user`
--
ALTER TABLE `c_tugas_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `c_tugas_id` (`c_tugas_id`),
  ADD KEY `b_user_id` (`b_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `a_kelas`
--
ALTER TABLE `a_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `a_pengguna`
--
ALTER TABLE `a_pengguna`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `a_pengguna_module`
--
ALTER TABLE `a_pengguna_module`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `b_user_angkatan`
--
ALTER TABLE `b_user_angkatan`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `c_tugas`
--
ALTER TABLE `c_tugas`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `c_tugas_user`
--
ALTER TABLE `c_tugas_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
