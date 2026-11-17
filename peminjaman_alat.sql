-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql302.infinityfree.com
-- Waktu pembuatan: 15 Nov 2026 pada 06.02
-- Versi server: 11.4.13-MariaDB
-- Versi PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_43250760_peminjaman_alat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_peminjaman` int(11) DEFAULT NULL,
  `id_alat` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_peminjaman`, `id_alat`, `jumlah`) VALUES
(1, 21, 6, 2),
(2, 22, 6, 1),
(4, 24, 6, 2),
(5, 25, 6, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `keterangan`, `status`) VALUES
(8, 'Laptop', 'digunakan dengan baik dan dijaga', 'Aktif'),
(9, 'Proyektor', 'digunakan saat mau dipakai', 'Aktif'),
(10, 'Terminal Listrik', 'Menyediakan Stopkontak ekstensi\r\n', 'Aktif'),
(11, 'laptop 2', 'baik', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `tanggal`) VALUES
(10, 32, 'Login ke sistem', '2026-09-24 14:17:16'),
(11, 32, 'Menambah data peminjaman', '2026-09-24 14:18:39'),
(12, 32, 'Menghapus data peminjaman', '2026-09-24 14:18:42'),
(13, 32, 'Login ke sistem', '2026-09-24 19:47:39'),
(14, 32, 'Login ke sistem', '2026-09-25 06:54:03'),
(15, 32, 'Login ke sistem', '2026-09-25 07:10:05'),
(16, 32, 'Menambah data user', '2026-09-25 07:10:18'),
(17, 32, 'Login ke sistem', '2026-09-25 07:18:06'),
(18, 32, 'Mengubah data user', '2026-09-25 07:18:14'),
(19, 32, 'Menghapus data user', '2026-09-25 07:19:49'),
(20, 32, 'Menghapus data user', '2026-09-25 07:19:52'),
(21, 32, 'Menghapus data user', '2026-09-25 07:21:02'),
(22, 32, 'Menghapus data user', '2026-09-25 07:21:04'),
(23, 32, 'Menghapus data user', '2026-09-25 07:21:05'),
(24, 32, 'Menghapus data user', '2026-09-25 07:21:09'),
(25, 32, 'Menghapus data user', '2026-09-25 07:21:14'),
(26, 32, 'Menghapus data user', '2026-09-25 07:21:18'),
(27, 32, 'Menghapus data user', '2026-09-25 07:21:22'),
(28, 32, 'Menambah kategori', '2026-09-25 07:22:16'),
(29, 32, 'Mengubah kategori', '2026-09-25 07:22:20'),
(30, 32, 'Menghapus kategori', '2026-09-25 07:22:23'),
(31, 32, 'Menambah kategori', '2026-09-25 07:23:04'),
(32, 32, 'Menambah data alat', '2026-09-25 07:23:35'),
(33, 32, 'Mengubah data alat', '2026-09-25 07:23:43'),
(34, 32, 'Mengubah data alat', '2026-09-25 07:23:57'),
(35, 32, 'Menghapus data alat', '2026-09-25 07:23:59'),
(36, 32, 'Menghapus data alat', '2026-09-25 07:24:00'),
(37, 32, 'Menghapus data alat', '2026-09-25 07:24:03'),
(38, 32, 'Menambah kategori', '2026-09-25 07:24:18'),
(39, 32, 'Menghapus kategori', '2026-09-25 07:24:22'),
(40, 32, 'Menghapus kategori', '2026-09-25 07:24:23'),
(41, 32, 'Mengubah data alat', '2026-09-25 07:24:28'),
(42, 32, 'Menambah data peminjaman', '2026-09-25 07:27:43'),
(43, 32, 'Mengubah data peminjaman', '2026-09-25 07:27:47'),
(44, 32, 'Menambah data peminjaman', '2026-09-25 07:27:59'),
(45, 6, 'Menghapus data user', '2026-09-25 07:53:17'),
(46, 6, 'Menghapus data user', '2026-09-25 07:53:19'),
(47, 6, 'Logout dari sistem', '2026-09-25 08:02:34'),
(48, 6, 'Menghapus data alat', '2026-09-25 08:02:55'),
(49, 6, 'Menghapus data user', '2026-09-25 08:03:06'),
(50, 6, 'Menghapus data user', '2026-09-25 08:03:16'),
(51, 6, 'Menghapus data user', '2026-09-25 08:03:22'),
(52, 6, 'Menghapus data user', '2026-09-25 08:03:25'),
(53, 6, 'Menghapus data user', '2026-09-25 08:03:28'),
(54, 6, 'Menghapus data user', '2026-09-25 08:03:31'),
(55, 6, 'Menghapus kategori', '2026-09-25 08:03:39'),
(56, 6, 'Menambah data peminjaman', '2026-09-25 08:04:09'),
(57, 6, 'Menambah data pengembalian', '2026-09-25 08:04:26'),
(58, 6, 'Menghapus data peminjaman', '2026-09-25 08:04:38'),
(59, 6, 'Menghapus data peminjaman', '2026-09-25 08:04:39'),
(60, 6, 'Menambah data peminjaman', '2026-09-25 08:04:46'),
(61, 6, 'Menambah data pengembalian', '2026-09-25 08:05:06'),
(62, 6, 'Mengubah data peminjaman', '2026-09-25 08:05:22'),
(63, 6, 'Logout dari sistem', '2026-09-25 08:05:28'),
(64, 23, 'Mengubah data pengembalian', '2026-09-25 08:16:33'),
(65, 23, 'Mengubah data pengembalian', '2026-09-25 08:16:36'),
(66, 23, 'Mengubah data peminjaman', '2026-09-25 08:21:32'),
(67, 23, 'Mengubah data peminjaman', '2026-09-25 08:21:34'),
(68, 23, 'Logout dari sistem', '2026-09-25 08:33:48'),
(69, 23, 'Mengubah data pengembalian', '2026-09-25 08:55:20'),
(70, 23, 'Logout dari sistem', '2026-09-25 08:57:14'),
(71, 6, 'Menambah data peminjaman', '2026-09-25 08:57:49'),
(72, 6, 'Menambah data peminjaman', '2026-09-25 08:58:01'),
(73, 23, 'Logout dari sistem', '2026-09-25 08:59:01'),
(74, 6, 'Logout dari sistem', '2026-09-25 09:02:03'),
(75, 23, 'Logout dari sistem', '2026-09-25 09:02:26'),
(76, 6, 'Menambah data user', '2026-09-25 09:44:59'),
(77, 6, 'Logout dari sistem', '2026-09-25 09:45:10'),
(78, 23, 'Logout dari sistem', '2026-09-25 09:45:37'),
(79, 39, 'Logout dari sistem', '2026-09-25 09:46:05'),
(80, 23, 'Logout dari sistem', '2026-09-25 10:05:24'),
(81, 6, 'Logout dari sistem', '2026-09-25 10:16:40'),
(82, 39, 'Logout dari sistem', '2026-09-25 10:17:13'),
(83, 23, 'Menghapus data pengembalian', '2026-09-25 10:17:34'),
(84, 23, 'Menghapus data pengembalian', '2026-09-25 10:17:35'),
(85, 23, 'Logout dari sistem', '2026-09-25 10:17:40'),
(86, 6, 'Mengubah data user', '2026-09-25 10:17:54'),
(87, 6, 'Mengubah data user', '2026-09-25 10:17:57'),
(88, 6, 'Menambah data peminjaman', '2026-09-25 10:18:12'),
(89, 6, 'Logout dari sistem', '2026-09-25 10:18:28'),
(90, 30, 'Logout dari sistem', '2026-09-25 10:19:39'),
(91, 6, 'Logout dari sistem', '2026-09-25 10:48:51'),
(92, 23, 'Logout dari sistem', '2026-09-25 10:49:09'),
(93, 39, 'Logout dari sistem', '2026-09-25 10:49:38'),
(94, 6, 'Mengubah data user', '2026-09-25 11:30:46'),
(95, 6, 'Mengubah data user', '2026-09-25 11:30:55'),
(96, 6, 'Logout dari sistem', '2026-09-25 11:31:08'),
(97, 6, 'Logout dari sistem', '2026-09-25 11:43:30'),
(98, 23, 'Logout dari sistem', '2026-09-25 11:43:47'),
(99, 30, 'Logout dari sistem', '2026-09-25 11:44:26'),
(100, 23, 'Logout dari sistem', '2026-09-25 11:44:42'),
(101, 23, 'Menghapus data pengembalian', '2026-09-25 12:05:41'),
(102, 23, 'Logout dari sistem', '2026-09-25 12:05:56'),
(103, 6, 'Menambah data user', '2026-09-26 14:42:28'),
(104, 6, 'Menambah data alat', '2026-09-26 14:43:09'),
(105, 6, 'Menambah data peminjaman', '2026-09-26 14:43:29'),
(106, 6, 'Logout dari sistem', '2026-09-26 14:43:42'),
(107, 40, 'Logout dari sistem', '2026-09-26 14:44:08'),
(108, 23, 'Logout dari sistem', '2026-09-26 14:44:49'),
(109, 6, 'Logout dari sistem', '2026-09-26 19:48:21'),
(110, 6, 'Logout dari sistem', '2026-09-27 13:21:36'),
(111, 39, 'Logout dari sistem', '2026-09-27 13:22:10'),
(112, 23, 'Logout dari sistem', '2026-09-27 13:22:45'),
(113, 6, 'Menambah data user', '2026-09-27 13:32:10'),
(114, 6, 'Logout dari sistem', '2026-09-27 13:32:21'),
(115, 41, 'Logout dari sistem', '2026-09-27 13:32:32'),
(116, 6, 'Logout dari sistem', '2026-09-27 13:32:38'),
(117, 41, 'Logout dari sistem', '2026-09-27 13:32:57'),
(118, 39, 'Logout dari sistem', '2026-09-27 13:33:37'),
(119, 23, 'Logout dari sistem', '2026-09-27 13:34:15'),
(120, 18, 'Logout dari sistem', '2026-09-27 13:34:42'),
(121, 6, 'Menghapus data user', '2026-09-28 08:31:52'),
(122, 6, 'Menghapus data user', '2026-09-28 08:31:55'),
(123, 6, 'Menghapus data user', '2026-09-28 08:31:57'),
(124, 6, 'Menghapus data user', '2026-09-28 08:31:59'),
(125, 6, 'Menghapus data user', '2026-09-28 08:32:02'),
(126, 6, 'Menghapus data user', '2026-09-28 08:32:06'),
(127, 6, 'Logout dari sistem', '2026-09-28 08:32:08'),
(128, 41, 'Menambah data alat', '2026-09-28 08:32:40'),
(129, 41, 'Menambah data peminjaman', '2026-09-28 08:33:01'),
(130, 41, 'Logout dari sistem', '2026-09-28 08:33:04'),
(131, 25, 'Logout dari sistem', '2026-09-28 08:33:22'),
(132, 41, 'Logout dari sistem', '2026-09-28 09:24:24'),
(133, 41, 'Logout dari sistem', '2026-09-28 09:27:26'),
(134, 41, 'Mengubah data user', '2026-09-28 09:27:55'),
(135, 41, 'Logout dari sistem', '2026-09-28 09:27:58'),
(136, 39, 'Logout dari sistem', '2026-09-28 10:08:37'),
(137, 41, 'Logout dari sistem', '2026-09-28 10:09:01'),
(138, 40, 'Logout dari sistem', '2026-09-28 10:15:40'),
(139, 40, 'Logout dari sistem', '2026-09-28 10:30:16'),
(140, 40, 'Menambah data peminjaman', '2026-09-28 10:44:13'),
(141, 40, 'Logout dari sistem', '2026-09-28 10:44:23'),
(142, 40, 'Logout dari sistem', '2026-09-28 10:58:38'),
(143, 41, 'Menambah data user', '2026-09-28 10:59:20'),
(144, 41, 'Logout dari sistem', '2026-09-28 10:59:33'),
(145, 42, 'Logout dari sistem', '2026-09-28 11:13:02'),
(146, 42, 'Logout dari sistem', '2026-09-28 11:46:44'),
(147, 42, 'Logout dari sistem', '2026-09-28 11:55:40'),
(148, 39, 'Logout dari sistem', '2026-09-28 11:55:58'),
(149, 42, 'Menambah data pengembalian', '2026-09-28 11:56:16'),
(150, 42, 'Menghapus data pengembalian', '2026-09-28 11:56:36'),
(151, 42, 'Logout dari sistem', '2026-09-28 11:56:47'),
(152, 41, 'Menambah data peminjaman', '2026-09-28 12:20:37'),
(153, 41, 'Logout dari sistem', '2026-09-28 12:20:52'),
(154, 39, 'Menambah data pengembalian', '2026-09-28 12:21:41'),
(155, 39, 'Menghapus data pengembalian', '2026-09-28 12:21:49'),
(156, 39, 'Logout dari sistem', '2026-09-28 12:22:06'),
(157, 40, 'Logout dari sistem', '2026-09-28 12:22:43'),
(158, 41, 'Logout dari sistem', '2026-09-29 10:38:13'),
(159, 39, 'Logout dari sistem', '2026-09-29 10:38:38'),
(160, 40, 'Logout dari sistem', '2026-09-29 10:39:10'),
(161, 41, 'Logout dari sistem', '2026-09-29 17:37:26'),
(162, 39, 'Logout dari sistem', '2026-09-29 17:37:44'),
(163, 40, 'Logout dari sistem', '2026-09-29 17:38:06'),
(164, 39, 'Logout dari sistem', '2026-09-29 17:38:13'),
(165, 41, 'Logout dari sistem', '2026-09-30 09:03:06'),
(166, 39, 'Logout dari sistem', '2026-09-30 09:03:40'),
(167, 40, 'Logout dari sistem', '2026-09-30 09:03:59'),
(168, 41, 'Menambah kategori', '2026-10-01 10:59:03'),
(169, 41, 'Menambah data peminjaman', '2026-10-02 07:15:59'),
(170, 41, 'Logout dari sistem', '2026-10-02 07:24:46'),
(171, 39, 'Logout dari sistem', '2026-10-02 07:25:05'),
(172, 28, 'Menambah data peminjaman', '2026-10-02 07:25:29'),
(173, 28, 'Logout dari sistem', '2026-10-02 07:26:26'),
(174, 39, 'Logout dari sistem', '2026-10-02 07:26:46'),
(175, 39, 'Menambah data pengembalian', '2026-10-02 07:46:51'),
(176, 39, 'Logout dari sistem', '2026-10-02 07:47:00'),
(177, 28, 'Menambah data pengembalian', '2026-10-02 07:47:18'),
(178, 28, 'Logout dari sistem', '2026-10-02 07:47:27'),
(179, 39, 'Logout dari sistem', '2026-10-03 15:06:38'),
(180, 42, 'Logout dari sistem', '2026-10-03 15:06:54'),
(181, 41, 'Menambah data alat', '2026-10-07 07:38:53'),
(182, 41, 'Menghapus data alat', '2026-10-07 07:39:03'),
(183, 41, 'Menambah data alat', '2026-10-07 07:39:25'),
(184, 41, 'Mengubah data alat', '2026-10-07 07:40:18'),
(185, 41, 'Menghapus data alat', '2026-10-07 07:40:25'),
(186, 41, 'Mengubah data alat', '2026-10-07 07:41:03'),
(187, 41, 'Mengubah data alat', '2026-10-07 07:41:37'),
(188, 41, 'Menghapus kategori', '2026-10-07 07:42:04'),
(189, 41, 'Menambah data alat', '2026-10-07 07:43:00'),
(190, 41, 'Mengubah data alat', '2026-10-07 07:43:19'),
(191, 41, 'Menambah data alat', '2026-10-07 07:43:46'),
(192, 41, 'Mengubah kategori', '2026-10-07 07:44:01'),
(193, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:18'),
(194, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:26'),
(195, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:27'),
(196, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:30'),
(197, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:34'),
(198, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:37'),
(199, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:40'),
(200, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:44'),
(201, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:46'),
(202, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:49'),
(203, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:54'),
(204, 41, 'Menghapus data peminjaman', '2026-10-07 07:44:56'),
(205, 41, 'Menghapus data peminjaman', '2026-10-07 07:45:01'),
(206, 41, 'Menghapus data pengembalian', '2026-10-07 07:46:03'),
(207, 41, 'Logout dari sistem', '2026-10-07 07:46:37'),
(208, 41, 'Logout dari sistem', '2026-10-07 07:46:45'),
(209, 39, 'Logout dari sistem', '2026-10-07 07:47:11'),
(210, 42, 'Logout dari sistem', '2026-10-07 07:47:42'),
(211, 39, 'Logout dari sistem', '2026-10-07 07:56:17'),
(212, 42, 'Menambah data peminjaman', '2026-10-07 07:56:38'),
(213, 42, 'Logout dari sistem', '2026-10-07 07:56:44'),
(214, 39, 'Logout dari sistem', '2026-10-07 08:17:06'),
(215, 42, 'Menambah data pengembalian', '2026-10-07 08:17:20'),
(216, 42, 'Logout dari sistem', '2026-10-07 08:17:23'),
(217, 39, 'Mengembalikan alat', '2026-10-07 08:17:33'),
(218, 39, 'Logout dari sistem', '2026-10-07 08:17:38'),
(219, 41, 'Logout dari sistem', '2026-10-15 19:00:13'),
(220, 41, 'Logout dari sistem', '2026-10-23 01:51:50'),
(221, 23, 'Logout dari sistem', '2026-10-23 01:52:23'),
(222, 42, 'Logout dari sistem', '2026-10-23 01:53:06'),
(223, 41, 'Logout dari sistem', '2026-10-23 02:29:09'),
(224, 41, 'Mengubah data user', '2026-10-23 08:28:13'),
(225, 41, 'Mengubah data user', '2026-10-23 08:28:44'),
(226, 41, 'Mengubah data user', '2026-10-23 08:29:07'),
(227, 39, 'Logout dari sistem', '2026-10-28 23:20:06'),
(228, 39, 'Logout dari sistem', '2026-11-08 05:04:29'),
(229, 43, 'Login ke sistem', '2026-11-08 19:57:42'),
(230, 43, 'Mengganti password', '2026-11-08 19:58:17'),
(231, 43, 'Logout dari sistem', '2026-11-08 19:58:26'),
(232, 43, 'Login ke sistem', '2026-11-08 20:10:04'),
(233, 43, 'Logout dari sistem', '2026-11-08 20:12:10'),
(234, 43, 'Login ke sistem', '2026-11-08 22:35:52'),
(235, 43, 'Login ke sistem', '2026-11-08 23:03:28'),
(236, 43, 'Mengubah data user', '2026-11-08 23:03:54'),
(237, 43, 'Mengubah data user', '2026-11-08 23:04:07'),
(238, 43, 'Menghapus data peminjaman', '2026-11-08 23:05:44'),
(239, 43, 'Menambah data user', '2026-11-08 23:06:39'),
(240, 43, 'Logout dari sistem', '2026-11-08 23:06:46'),
(241, 44, 'Login ke sistem', '2026-11-08 23:06:54'),
(242, 44, 'Logout dari sistem', '2026-11-08 23:07:02'),
(243, 43, 'Login ke sistem', '2026-11-08 23:07:14'),
(244, 43, 'Menambah data user', '2026-11-08 23:07:35'),
(245, 43, 'Logout dari sistem', '2026-11-08 23:07:40'),
(246, 45, 'Login ke sistem', '2026-11-08 23:07:50'),
(247, 45, 'Logout dari sistem', '2026-11-08 23:08:13'),
(248, 43, 'Login ke sistem', '2026-11-09 17:19:16'),
(249, 43, 'Logout dari sistem', '2026-11-09 17:43:54'),
(250, 43, 'Login ke sistem', '2026-11-09 17:44:08'),
(251, 43, 'Menambah kategori', '2026-11-09 17:45:20'),
(252, 43, 'Menambah data alat', '2026-11-09 17:45:57'),
(253, 43, 'Mengubah data alat', '2026-11-09 17:46:10'),
(254, 43, 'Menghapus data alat', '2026-11-09 17:46:17'),
(255, 43, 'Menambah data alat', '2026-11-09 17:46:39'),
(256, 43, 'Menambah data user', '2026-11-09 17:47:37'),
(257, 43, 'Mengubah data user', '2026-11-09 17:49:01'),
(258, 43, 'Menghapus data user', '2026-11-09 17:49:15'),
(259, 43, 'Logout dari sistem', '2026-11-09 17:51:49'),
(260, 43, 'Login ke sistem', '2026-11-09 17:51:58'),
(261, 43, 'Mengubah data user', '2026-11-09 17:52:09'),
(262, 43, 'Logout dari sistem', '2026-11-09 17:52:12'),
(263, 39, 'Login ke sistem', '2026-11-09 17:52:32'),
(264, 39, 'Logout dari sistem', '2026-11-09 17:53:51'),
(265, 43, 'Login ke sistem', '2026-11-09 17:53:59'),
(266, 43, 'Mengubah data user', '2026-11-09 17:54:10'),
(267, 43, 'Logout dari sistem', '2026-11-09 17:54:13'),
(268, 46, 'Login ke sistem', '2026-11-09 17:54:30'),
(269, 46, 'Mengajukan peminjaman alat', '2026-11-09 17:55:22'),
(270, 46, 'Logout dari sistem', '2026-11-09 17:55:34'),
(271, 39, 'Login ke sistem', '2026-11-09 17:55:46'),
(272, 39, 'Menyetujui peminjaman #24', '2026-11-09 17:55:58'),
(273, 39, 'Logout dari sistem', '2026-11-09 17:56:04'),
(274, 46, 'Login ke sistem', '2026-11-09 17:56:12'),
(275, 46, 'Mengembalikan alat', '2026-11-09 17:56:27'),
(276, 46, 'Logout dari sistem', '2026-11-09 17:56:43'),
(277, 39, 'Login ke sistem', '2026-11-09 17:56:52'),
(278, 39, 'Logout dari sistem', '2026-11-09 17:57:23'),
(279, 46, 'Login ke sistem', '2026-11-09 17:57:30'),
(280, 46, 'Mengajukan peminjaman alat', '2026-11-09 17:57:54'),
(281, 46, 'Logout dari sistem', '2026-11-09 17:58:03'),
(282, 39, 'Login ke sistem', '2026-11-09 17:58:10'),
(283, 39, 'Menolak peminjaman #25', '2026-11-09 17:58:54'),
(284, 39, 'Logout dari sistem', '2026-11-09 18:01:40'),
(285, 46, 'Login ke sistem', '2026-11-09 18:01:46'),
(286, 43, 'Login ke sistem', '2026-11-09 20:03:01'),
(287, 43, 'Login ke sistem', '2026-11-09 22:48:41'),
(288, 43, 'Logout dari sistem', '2026-11-09 22:48:47'),
(289, 43, 'Login ke sistem', '2026-11-14 20:52:57'),
(290, 43, 'Logout dari sistem', '2026-11-14 20:53:02'),
(291, 43, 'Login ke sistem', '2026-11-14 23:50:23'),
(292, 43, 'Logout dari sistem', '2026-11-14 23:50:39'),
(293, 43, 'Login ke sistem', '2026-11-15 02:50:48'),
(294, 43, 'Logout dari sistem', '2026-11-15 02:50:57'),
(295, 43, 'Login ke sistem', '2026-11-15 02:53:41'),
(296, 43, 'Menambah data user', '2026-11-15 02:54:28'),
(297, 43, 'Logout dari sistem', '2026-11-15 02:54:40'),
(298, 47, 'Login ke sistem', '2026-11-15 02:54:46'),
(299, 47, 'Logout dari sistem', '2026-11-15 02:54:50'),
(300, 39, 'Login ke sistem', '2026-11-15 02:54:56'),
(301, 42, 'Login ke sistem', '2026-11-15 02:59:29'),
(302, 42, 'Logout dari sistem', '2026-11-15 02:59:46'),
(303, 14, 'Login ke sistem', '2026-11-15 02:59:54'),
(304, 14, 'Logout dari sistem', '2026-11-15 03:01:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(8, 20, '2026-09-05', '2026-09-08', 'Disetujui'),
(11, 30, '2026-09-05', '2026-09-06', 'Disetujui'),
(17, 25, '2026-09-10', '2026-09-12', 'Dikembalikan'),
(20, 27, '2026-09-06', '2026-09-07', 'Dikembalikan'),
(22, 28, '2026-10-02', '2026-10-03', 'Dikembalikan'),
(24, 46, '2026-11-10', '2026-11-11', 'Dikembalikan'),
(25, 46, '2026-11-10', '2026-11-11', 'Ditolak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_alat`
--

CREATE TABLE `tabel_alat` (
  `id_alat` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL,
  `kondisi_alat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tabel_alat`
--

INSERT INTO `tabel_alat` (`id_alat`, `id_kategori`, `nama_alat`, `stok`, `kondisi_alat`) VALUES
(6, 8, 'Asus Rog Zephyrus M16', 10, 'Baik'),
(7, 8, 'Lenovo Yoga Pro7i', 5, 'Baik'),
(9, 9, 'Tripsky YG300', 10, 'Baik'),
(10, 9, 'Aukey RD-860', 10, 'Baik'),
(11, 10, 'BARDI Smart Power Strip Extension', 10, 'Baik'),
(12, 10, 'Uticon ST1582', 10, 'Baik'),
(14, 11, 'asus', 1, 'Baik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_pengembalian`
--

CREATE TABLE `tabel_pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `kondisi_pengembalian` varchar(50) NOT NULL,
  `status` varchar(30) NOT NULL,
  `denda` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tabel_pengembalian`
--

INSERT INTO `tabel_pengembalian` (`id_pengembalian`, `id_peminjam`, `tanggal_pengembalian`, `kondisi_pengembalian`, `status`, `denda`) VALUES
(7, 22, '2026-10-03', 'Baik', 'Dikembalikan', '0.00'),
(10, 24, '2026-11-10', 'Baik', 'Dikembalikan', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_user`
--

CREATE TABLE `tabel_user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` enum('admin','petugas','peminjam') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tabel_user`
--

INSERT INTO `tabel_user` (`id_user`, `nama`, `username`, `password`, `level`) VALUES
(14, 'Budi', 'budi', '$2y$10$crK7QYJxU2VSxAu4BCUB3.CFr8VZSy4NcicLrQ4.g4kK9SepK/3s6', 'peminjam'),
(15, 'Andi', 'andi', '$2y$10$nH6ftp61VvbRkvAU48OKj.UNtLeTTg06MH9jQOHB5dM3UK9kGBjEW', 'peminjam'),
(18, 'Eka', 'eka', '$2y$10$iEHUgstJKjDaFIHoJpDXnucL/nSHhgc.1vrJ4AXt9VnuH17eqd7wC', 'peminjam'),
(19, 'Fajar', 'fajar', '$2y$10$5Y7cWWTPMbArI78ovmMVtuE769Q6/gOgFBojUBIuZZlfnOoyxGlv2', 'peminjam'),
(20, 'Nisa', 'nisa', '$2y$10$4PCBxzXGwY6IQ1lFkN3VNOQv8vcs3hbHjIKC596XfHgCUV9XWxMKK', 'peminjam'),
(21, 'Putri', 'putri', '$2y$10$qavSJdXQfpjgUMjvLecUs.LUyeXBfoucBGSieq8.a4jQhXAoy6xpq', 'peminjam'),
(23, 'ezar', 'ezar', '$2y$10$9PcpPszMju0JNXFsx8/Fd.vDjYNT/I3s8M2KZU8DmW8Dl1pr3qEFy', 'petugas'),
(24, 'Rizky', 'rizky', '$2y$10$3o1Q2IKxF4tHPmVzxwTR5eyT/xyNmSwDvpgHKgBNp8IDdrAL6a0mS', 'peminjam'),
(25, 'Gilang', 'gilang', '$2y$10$hb/cUO4LzIh7OHKRxwXe7eBsThZfouqyVmWXeBySY83Fuo.yyjWWy', 'peminjam'),
(26, 'Rehan', 'rehan', '$2y$10$gv6JUDxW.zpjvFOOOAhG8u1TjDMTfXww9Zj3vhb7AN0pJYtOeGDU.', 'peminjam'),
(27, 'Deni', 'deni', '$2y$10$3DH/t9bNIpi6SDV/g8lY6u0Sy0aqMjl00gMB9a6S2/6hSxeegHKXa', 'peminjam'),
(28, 'Arif', 'arif', '$2y$10$QrHh28bNrX.wcFBadXVCS.K776CUVzDAsFPddb/zGFqce5IOPdmpO', 'peminjam'),
(30, 'Gina', 'gina', '$2y$10$GTcZVofhPVkXSIlOeskKwelhzggTTxCfXWdtfjE6SlJE5baxbj8oi', 'peminjam'),
(33, 'Petugas Sekolah', 'petugas.sekolah', '$2y$10$MKxBEK8OK..WS/.WQF6v0epWBozV55lBESh8Ulz8FLISi/H6w8Fjq', 'petugas'),
(39, 'adi', 'adi', '$2y$12$iXoMT/Zq2d9wixY.EnmSJOJoaD0KPZdAPocj.4064Hmkshh/8ZyEe', 'petugas'),
(41, 'peralta', 'mariano', '$2y$10$HNdif96r/bXHw7qXvBDPvud7np4RhHplJMDiqEfRSNKXWLvQNSyzm', 'peminjam'),
(42, 'rans', 'rann', '$2y$10$iFmT5.4oQa76EQnlGEU0hONEPCTqfJJZCKEoE5z3ba0v2Q7FNdDz.', 'petugas'),
(43, 'Administrator', 'admin', '$2y$12$DeCbfRF8c4vpOq8f6O.L7u8qDUMM3SiY5P5xQ2HlT/LyW3wQjlUuO', 'admin'),
(44, 'har', 'har', '$2y$12$JxTUb9gIdjKji6Q.oiUV0OSUGcBziR55FBDcB.cSTW3Snd5rJPWwG', 'peminjam'),
(45, 'zar', 'zar', '$2y$12$YalxvPG8mDkNW4eEa3r6JOP5ll04g1Hn0PZbL.DsmjrhJDIC3ZIaC', 'petugas'),
(46, 'adit', 'adit', '$2y$12$9NIFLLQzz0a91Qyu7lcUT.SL8p4ncYfdtNBN54mXZ12SVeNYxBBnW', 'peminjam'),
(47, 'adi', 'adis', '$2y$12$KBdSN3qlAojFddGlz0VoC./daNLJN9RkSOdZEJUm0pg5Kxzoe7Pzm', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tabel_alat`
--
ALTER TABLE `tabel_alat`
  ADD PRIMARY KEY (`id_alat`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `tabel_pengembalian`
--
ALTER TABLE `tabel_pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_peminjam` (`id_peminjam`);

--
-- Indeks untuk tabel `tabel_user`
--
ALTER TABLE `tabel_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uq_tabel_user_username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `tabel_alat`
--
ALTER TABLE `tabel_alat`
  MODIFY `id_alat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tabel_pengembalian`
--
ALTER TABLE `tabel_pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tabel_user`
--
ALTER TABLE `tabel_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
