-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 03:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mpsi_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `absensi_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `workshop_id` bigint(20) UNSIGNED NOT NULL,
  `waktu_absensi` datetime NOT NULL,
  `status_absensi` varchar(20) NOT NULL DEFAULT 'hadir',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`absensi_id`, `user_id`, `workshop_id`, `waktu_absensi`, `status_absensi`, `created_at`, `updated_at`) VALUES
(1, 10, 7, '2025-11-26 12:29:38', 'hadir', '2025-11-26 05:29:38', '2025-11-26 05:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `forum_diskusi`
--

CREATE TABLE `forum_diskusi` (
  `discussion_id` bigint(20) UNSIGNED NOT NULL,
  `workshop_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forum_diskusi`
--

INSERT INTO `forum_diskusi` (`discussion_id`, `workshop_id`, `user_id`, `message`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 7, 10, 'hai', '2025-11-26 05:32:36', '2025-11-26 05:32:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE `keywords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `keyword` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id`, `keyword`) VALUES
(1, 'Adaptasi Lingkungan Baru'),
(2, 'Pelatihan Emosi'),
(3, 'Konsep Dasar Generative AI'),
(4, 'Jenis AI'),
(5, 'Pemanfaatan AI'),
(6, 'svf'),
(7, 'sdf'),
(8, 'dsvfdbghn'),
(9, 'sdsfdgfh'),
(10, 'dfsbdgnv'),
(11, 'advsfdbg'),
(12, 'ewrgthytj'),
(13, 'gky'),
(14, 'retrytjuk'),
(15, 'rerthdtdyuyk'),
(16, 'Linkedn'),
(17, 'pelatihan'),
(18, 'Personal Branding');

-- --------------------------------------------------------

--
-- Table structure for table `materi_workshop`
--

CREATE TABLE `materi_workshop` (
  `materi_id` int(11) NOT NULL,
  `workshop_id` int(11) NOT NULL,
  `judul_topik` varchar(255) NOT NULL,
  `file_materi_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materi_workshop`
--

INSERT INTO `materi_workshop` (`materi_id`, `workshop_id`, `judul_topik`, `file_materi_url`) VALUES
(1, 2, 'Pengenalan Generative AI', 'workshop_materials/1764165450_Daftar Peserta - Linkedn.pdf'),
(2, 7, 'Linkedn', 'workshop_materials/1764165482_PPT KEL 13 - LoRa, NB-IoT & BLE (FIX).pdf');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_07_023328_create_sessions_table', 1),
(2, '2025_11_08_025614_add_pemateri_until_to_users_table', 2),
(3, '2025_11_08_040803_add_phone_address_to_users_table', 3),
(4, '2025_11_10_040307_update_status_workshop_enum_on_workshops_table', 4),
(5, '2025_11_13_053758_create_forum_diskusi_table', 5),
(6, '2025_11_13_233926_create_absensi_table', 6),
(7, '2025_11_26_124500_fix_materi_autoincrement', 7);

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `pendaftaran_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `workshop_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `status_pendaftaran` enum('pending','diterima','ditolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`pendaftaran_id`, `user_id`, `workshop_id`, `tanggal_daftar`, `status_pendaftaran`) VALUES
(1, 10, 7, '2025-11-26', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `request_workshop`
--

CREATE TABLE `request_workshop` (
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_request` enum('menunggu','disetujui','ditolak') DEFAULT 'menunggu',
  `tanggal_tanggapan` date DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_workshop`
--

INSERT INTO `request_workshop` (`request_id`, `user_id`, `judul`, `deskripsi`, `status_request`, `tanggal_tanggapan`, `catatan_admin`) VALUES
(1, 5, 'Manajemen Waktu untuk Mahasiswa Baru', 'Workshop ini membantu mahasiswa baru mengatur waktu kuliah dan kegiatan organisasi secara efektif.', 'menunggu', NULL, NULL),
(2, 6, 'Pelatihan Public Speaking Dasar', 'Meningkatkan kemampuan berbicara di depan umum untuk mahasiswa berbagai jurusan.', 'disetujui', '2025-11-07', 'Disetujui karena tema menarik dan relevan dengan kebutuhan kampus.'),
(3, 7, 'Pelatihan Desain Grafis dengan Canva', 'Workshop praktis membuat konten digital kreatif untuk media sosial kampus.', 'ditolak', '2025-11-03', 'Tema sudah pernah diadakan bulan lalu.'),
(4, 8, 'Pengenalan Artificial Intelligence bagi Pemula', 'Workshop untuk memperkenalkan konsep dasar AI kepada mahasiswa non-teknik.', 'menunggu', NULL, NULL),
(5, 9, 'Strategi Membangun Startup Mahasiswa', 'Pelatihan wirausaha digital yang memotivasi mahasiswa untuk berinovasi.', 'disetujui', '2025-11-10', 'Disetujui dengan syarat kerjasama dengan Inkubator Bisnis UNAND.'),
(6, 20, 'Pengembangan Soft Skill Mahasiswa Baru', 'Workshop ini berfokus pada peningkatan kemampuan komunikasi, kerja tim, dan manajemen diri untuk mahasiswa baru.', 'menunggu', NULL, NULL),
(7, 21, 'Pelatihan Dasar Microsoft Excel untuk Penelitian', 'Memberikan pemahaman dasar penggunaan Excel untuk analisis data sederhana bagi mahasiswa semua jurusan.', 'menunggu', NULL, NULL),
(8, 22, 'Kreativitas Konten Digital untuk Promosi Kampus', 'Membantu mahasiswa memahami cara membuat konten digital menarik untuk keperluan promosi kegiatan kampus.', 'menunggu', NULL, NULL),
(9, 23, 'Pengenalan Data Science bagi Mahasiswa Non-Teknik', 'Workshop pengantar yang menjelaskan konsep dasar data science dan penerapannya di berbagai bidang studi.', 'menunggu', NULL, NULL),
(10, 24, 'Pelatihan Menulis Artikel Ilmiah untuk Jurnal Kampus', 'Pelatihan tentang cara menyusun artikel ilmiah dengan struktur yang benar sesuai standar jurnal kampus.', 'disetujui', '2025-11-26', 'akan di pertimbangkan'),
(11, 10, 'Personal Branding', 'Public speaking', 'disetujui', '2025-11-26', 'akan dipertimbangkan');

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE `sertifikat` (
  `sertifikat_id` bigint(20) UNSIGNED NOT NULL,
  `pendaftaran_id` bigint(20) UNSIGNED NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `tanggal_generate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Aw9eux4HnN0DIAyDOrPYJjtcYNXnnuZYc3C60CRb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVdES2l0QmtLZGpMWUdIc0hiQ1Y2Z2xDemwyN0FYclFDUnY0bENtUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1764132503),
('De5N2d8ygM0b0gat0vIVO6U6MCV9EJ73p4lqDL5j', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzliOFlvYnY3aXVVQ3JuRVlrbjJjZXhvcnRlN1VMUDF2VGN3dExNWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1764143451),
('Z13CPyyTgh4z4bD3mwECJI7ZjVPv8tY1O6dU4vlZ', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMERoTFdhT2I5TVlQZ3kzQ2dYckNUWmVZQ0c4MXhVTkhpUVZsMWgzTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZW5nZ3VuYS9jZXJ0aWZpY2F0ZS83L2Rvd25sb2FkIjtzOjU6InJvdXRlIjtzOjI5OiJwZW5nZ3VuYS5jZXJ0aWZpY2F0ZS5kb3dubG9hZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEwO30=', 1764168129);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nim_nidn` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pengguna','pemateri','admin') DEFAULT 'pengguna',
  `pemateri_until` datetime DEFAULT NULL,
  `prodi_fakultas` varchar(255) DEFAULT NULL,
  `foto_profil_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nim_nidn`, `nama`, `email`, `nomor_telepon`, `alamat`, `password`, `role`, `pemateri_until`, `prodi_fakultas`, `foto_profil_url`) VALUES
(1, '2311522037', 'admin', 'admin@gmail.com', '087733225511', 'jalan unand', '$2y$12$Ln8p1.x9n/VNRRZoIbOZBOMxfV.nQ3CLMLzaT3prMxG6eX/cboWoW', 'admin', NULL, 'Fakultas Teknik', 'profiles/profile_1_1762575225.png'),
(2, '2311523015', 'pengguna', 'pengguna@gmail.com', NULL, NULL, '$2y$12$/2RJQQaKn0KHD9cL7JPvuuatolDtArlSdAectNTZ3KQX4Bxos8pwO', 'pengguna', NULL, 'Fakultas Kedokteran', 'path_to_admin_photo.jpg'),
(3, '2311521015', 'Fatthiya Azzahra, M.Psi', 'lala@gmail.com', NULL, NULL, '$2y$12$zVrNM7NJAMjJtD0QhsG7MuP7oLQ06xTh7JOgO6fXN04MffoVYPGMG', 'pengguna', NULL, 'Fakultas Kedokteran', 'path_to_admin_photo.jpg'),
(4, '2311522037', 'Farhan Fitrahadi S.Kom', 'purhun@gmail.com', NULL, NULL, '$2y$12$qS/S1dOq07iziiVq77tbZeJUw9GFk/C0i.hpgJcw.MrlGyG7x./Pm', 'pemateri', '2025-12-03 12:41:34', 'Fakultas Teknologi Informasi', 'path_to_admin_photo.jpg'),
(5, '2311522025', 'Ervizon Fariz', 'paris@gmail.com', NULL, NULL, '$2y$12$2..gmRcnlmIJQuSf/bQDp.2OOqYZmIBuMfZtTw7QthSSBQioqkF6u', 'pemateri', '2025-12-03 12:15:53', 'Fakultas Ekonomi dan Bisnis', 'path_to_admin_photo.jpg'),
(6, '2311522006', 'Pengguna 6', 'pengguna6@gmail.com', NULL, NULL, '$2y$12$QNmBIYXEsKD9Hl1OyflUkuGqQKUdij.27n2guFidKBEdGo14zv4Qq', 'pengguna', NULL, 'Fakultas Pertanian', 'default_profile.jpg'),
(7, '2311522007', 'Pengguna 7', 'pengguna7@gmail.com', NULL, NULL, '$2y$12$.0yQ7mLNArMKO/dcx7WZG.ZczYiU646.Vw5Rs3rfg79BfT77kP3gW', 'pengguna', NULL, 'Fakultas Farmasi', 'default_profile.jpg'),
(8, '2311522008', 'Pengguna 8', 'pengguna8@gmail.com', NULL, NULL, '$2y$12$VbHZr5FSfbWQtNBr.3TTvu8bnFao47mDlT1wHj4xNzjyn9TVHDNbG', 'pengguna', NULL, 'Fakultas Ekonomi dan Bisnis', 'default_profile.jpg'),
(9, '2311522009', 'Pengguna 9', 'pengguna9@gmail.com', NULL, NULL, '$2y$12$tMVTkptBbfKhCBUzbmcvTuNO0ArMFpocrG5XBkbju5L8NnYDQzpJm', 'pengguna', NULL, 'Fakultas Teknologi Pertanian', 'default_profile.jpg'),
(10, '2311522010', 'Pengguna 10', 'pengguna10@gmail.com', NULL, NULL, '$2y$12$z4r5X.bgdXxhYM3RQqSL0e47o2gF5hxHsi.ntwHjD8/iMLdDG5yUW', 'pengguna', NULL, 'Fakultas Ilmu Sosial dan Ilmu Politik', 'default_profile.jpg'),
(11, '2311522011', 'Pengguna 11', 'pengguna11@gmail.com', NULL, NULL, '$2y$12$Jm0XNP4wgh.zwooY/th5COlwrcTxR3RMBasADApqOrwlKSvS4Eyja', 'pengguna', NULL, 'Fakultas Peternakan', 'default_profile.jpg'),
(12, '2311522012', 'Pengguna 12', 'pengguna12@gmail.com', NULL, NULL, '$2y$12$q3iEjXzDNANOXSWssoTc0OgBJezQ.NOjJnVxoE9ZynDvW938xsbxy', 'pengguna', NULL, 'Fakultas Ilmu Sosial dan Ilmu Politik', 'default_profile.jpg'),
(13, '2311522013', 'Pengguna 13', 'pengguna13@gmail.com', NULL, NULL, '$2y$12$ajX6Dk0.TArgWcK6ybkr5.bY9/7/Fa.TfRJEg5O76A55IxBr0ygJG', 'pengguna', NULL, 'Fakultas Ekonomi dan Bisnis', 'default_profile.jpg'),
(14, '2311522014', 'Pengguna 14', 'pengguna14@gmail.com', NULL, NULL, '$2y$12$mCR7h2HaZeAxnF7KfBjhDeHogIsyPHYhJcb0M8wUfVkb6c2IW12xC', 'pengguna', NULL, 'Fakultas Teknologi Informasi', 'default_profile.jpg'),
(15, '2311522015', 'Pengguna 15', 'pengguna15@gmail.com', NULL, NULL, '$2y$12$SgBGbxX9N5maURJqIH3pRO75zlr.F7viVKQzYH1WjEz194IzAUh6e', 'pengguna', NULL, 'Fakultas Peternakan', 'default_profile.jpg'),
(16, '2311522016', 'Pengguna 16', 'pengguna16@gmail.com', NULL, NULL, '$2y$12$q6.V8vTitZFQV8Rcs5Fiiurz4B95L8eG3cAgirnbLNHAMEyrJx6T6', 'pengguna', NULL, 'Fakultas Peternakan', 'default_profile.jpg'),
(17, '2311522017', 'Pengguna 17', 'pengguna17@gmail.com', NULL, NULL, '$2y$12$AJ9Q4c0qouj5fZP2ApI/gOiDQLf8PuSuvGlGNx5CQWBC38FeCOgV.', 'pengguna', NULL, 'Fakultas Ilmu Sosial dan Ilmu Politik', 'default_profile.jpg'),
(18, '2311522018', 'Pengguna 18', 'pengguna18@gmail.com', NULL, NULL, '$2y$12$05V46ElpOB47impj/9wQFuB3FmFWlvmzXjx1XlZ2c6OxykUTQkPH6', 'pengguna', NULL, 'Fakultas Matematika dan Ilmu Pengetahuan Alam', 'default_profile.jpg'),
(19, '2311522019', 'Pengguna 19', 'pengguna19@gmail.com', NULL, NULL, '$2y$12$vDSkD8y9SszDnXDoxcOb/.JW4YdlZgfVKnqsWPIjmt96kv7ERwce2', 'pengguna', NULL, 'Fakultas Teknologi Informasi', 'default_profile.jpg'),
(20, '2311522020', 'Pengguna 20', 'pengguna20@gmail.com', NULL, NULL, '$2y$12$1mNM69c0DNWxyM0PzgouaeKtv40mSYNpDaWLYi1TB0v9vlkzvCyTW', 'pengguna', NULL, 'Fakultas Kedokteran Gigi', 'default_profile.jpg'),
(21, '2311522021', 'Pengguna 21', 'pengguna21@gmail.com', NULL, NULL, '$2y$12$m1ymt8OWJpBkjpkQsAWzPecPscCIA/PlNkhDDAHTZ2QL1rJlh0Ya2', 'pengguna', NULL, 'Fakultas Kedokteran', 'default_profile.jpg'),
(22, '2311522022', 'Pengguna 22', 'pengguna22@gmail.com', NULL, NULL, '$2y$12$sdWWsjzwbVM.3nuAcPKx.uIcRyGJAst.pu85kwL.cWSTtSpYgS6ju', 'pengguna', NULL, 'Fakultas Matematika dan Ilmu Pengetahuan Alam', 'default_profile.jpg'),
(23, '2311522023', 'Pengguna 23', 'pengguna23@gmail.com', NULL, NULL, '$2y$12$2j7kf5KcyYEGBDWImxJZzO2Rim/ojmOMtyVXibtBemF2Gt3JhKG6y', 'pengguna', NULL, 'Fakultas Pertanian', 'default_profile.jpg'),
(24, '2311522024', 'Pengguna 24', 'pengguna24@gmail.com', NULL, NULL, '$2y$12$buJFj8v8bD0w3Pdq7BrHp.5kGxyz40MxUogVhKhIzo/SChs3KcsVy', 'pengguna', NULL, 'Fakultas Kedokteran', 'default_profile.jpg'),
(25, '2311522025', 'Pengguna 25', 'pengguna25@gmail.com', NULL, NULL, '$2y$12$lnpx.yIjKlwgJQ.Y23RBCOz.fdjonXeXZYDyCmR6b5OjqjtOn1jbG', 'pengguna', NULL, 'Fakultas Teknik', 'default_profile.jpg'),
(26, '2311522026', 'Pengguna 26', 'pengguna26@gmail.com', NULL, NULL, '$2y$12$HcQAVjSthUHQz/3yBV7BS.mlKNsP8lE3lYM7DqDrPKfUjB.B0NBiy', 'pengguna', NULL, 'Fakultas Pertanian', 'default_profile.jpg'),
(27, '2311522027', 'Pengguna 27', 'pengguna27@gmail.com', NULL, NULL, '$2y$12$QGseT.SyUdH9uL5ijSHACu8WYiHFQ3UEXn9VOgKhzR6W3Tno30ZQq', 'pengguna', NULL, 'Fakultas Kesehatan Masyarakat', 'default_profile.jpg'),
(28, '2311522028', 'Pengguna 28', 'pengguna28@gmail.com', NULL, NULL, '$2y$12$CGakXztzp9qUMjhSXiCh/.vN9wA9cqMhIcaCADMyaewthB5kf1c/u', 'pengguna', NULL, 'Fakultas Teknologi Informasi', 'default_profile.jpg'),
(29, '2311522029', 'Pengguna 29', 'pengguna29@gmail.com', NULL, NULL, '$2y$12$FHjqiHDSfK/RpzIQFatC2u4ZUwtt6RD8KX2Q1/UkVDOUG5EVaOcii', 'pengguna', NULL, 'Fakultas Ekonomi dan Bisnis', 'default_profile.jpg'),
(30, '2311522030', 'Pengguna 30', 'pengguna30@gmail.com', NULL, NULL, '$2y$12$vs3KGnv1eONPIXF6twVKjeDeGsybHUkehe.FxAKoCYU3uwexzt7kK', 'pengguna', NULL, 'Fakultas Teknik', 'default_profile.jpg'),
(31, '2311522031', 'Pengguna 31', 'pengguna31@gmail.com', NULL, NULL, '$2y$12$8a1eIb4t0HYkBOnlrfaI3eEHAL/wKvAPw8pcU7.WJi5DOGrYC3YB6', 'pengguna', NULL, 'Fakultas Hukum', 'default_profile.jpg'),
(32, '2311522032', 'Pengguna 32', 'pengguna32@gmail.com', NULL, NULL, '$2y$12$yaXdwAXiGfPFGD.VGovKYu7x3MkenS8uYMUtxk58eMTGaEdYNd3wm', 'pengguna', NULL, 'Fakultas Ekonomi dan Bisnis', 'default_profile.jpg'),
(33, '2311522033', 'Pengguna 33', 'pengguna33@gmail.com', NULL, NULL, '$2y$12$dStSjngsZD3ADhbRqmqghe3Q8.evcjHW5GA2pcVMkT2W5TJQX/AZq', 'pengguna', NULL, 'Fakultas Kedokteran Gigi', 'default_profile.jpg'),
(34, '2311522034', 'Pengguna 34', 'pengguna34@gmail.com', NULL, NULL, '$2y$12$W44UovAZxcOfbq7BjAnRIOs4va4SQoPdt3GtW47JBM2lhnS65WHWK', 'pengguna', NULL, 'Fakultas Pertanian', 'default_profile.jpg'),
(35, '2311522035', 'Pengguna 35', 'pengguna35@gmail.com', NULL, NULL, '$2y$12$4LeR2RUuUplVuEHBDM92UOqu2z8Qqg4XpU0oiP1uZ.75atpHdgPDy', 'pengguna', NULL, 'Fakultas Ilmu Budaya', 'default_profile.jpg'),
(36, '2311522036', 'Pengguna 36', 'pengguna36@gmail.com', NULL, NULL, '$2y$12$LntKj2Yy0ad52qp.rx8GDe/998p143lqzlZdHrkU8FI5AiAIJqCy2', 'pengguna', NULL, 'Fakultas Ilmu Budaya', 'default_profile.jpg'),
(37, '2311522037', 'Pengguna 37', 'pengguna37@gmail.com', NULL, NULL, '$2y$12$1fifEMd81gvSFl/6dHZUfO0A3lND4YexLuKMFGLsQBXHVX3n6udLa', 'pengguna', NULL, 'Fakultas Teknik', 'default_profile.jpg'),
(38, '2311522038', 'Pengguna 38', 'pengguna38@gmail.com', NULL, NULL, '$2y$12$ptxkiGUk/vYvFkqQAowGyOjf5fBK4LENz702ph5seVImoApIJ6WUa', 'pengguna', NULL, 'Fakultas Teknologi Informasi', 'default_profile.jpg'),
(39, '2311522039', 'Pengguna 39', 'pengguna39@gmail.com', NULL, NULL, '$2y$12$HLBY9XGqL2zIgv4cOMkJxek0/yOLwED22cqwGZHbr55iq0YGBj34C', 'pengguna', NULL, 'Fakultas Peternakan', 'default_profile.jpg'),
(40, '2311522040', 'Pengguna 40', 'pengguna40@gmail.com', NULL, NULL, '$2y$12$BivNnqm9PEfPKRLMCOUCwecMWoCBcWVYmthCp9fp8D1wUQ6UJ7.8O', 'pengguna', NULL, 'Fakultas Kedokteran Gigi', 'default_profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `workshops`
--

CREATE TABLE `workshops` (
  `workshop_id` bigint(20) UNSIGNED NOT NULL,
  `pemateri_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kuota` int(11) DEFAULT NULL,
  `kuota_terisi` int(11) DEFAULT 0,
  `sampul_poster_url` varchar(255) DEFAULT NULL,
  `status_workshop` enum('aktif','nonaktif','selesai','penuh') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workshops`
--

INSERT INTO `workshops` (`workshop_id`, `pemateri_id`, `judul`, `deskripsi`, `tanggal`, `waktu`, `lokasi`, `kuota`, `kuota_terisi`, `sampul_poster_url`, `status_workshop`, `created_at`, `updated_at`) VALUES
(1, 3, 'Pelatihan Memanajemen Emosi di Lingkungan Baru', 'Workshop ini dirancang untuk membantu peserta memahami pentingnya pengelolaan emosi dalam menghadapi lingkungan baru. Melalui sesi interaktif, peserta akan belajar mengenali emosi diri, teknik mengendalikan stres, serta strategi membangun adaptasi positif. Dengan keterampilan ini, peserta diharapkan mampu meningkatkan kepercayaan diri, menjaga kesehatan mental, dan berinteraksi lebih baik dalam situasi baru.', '2025-11-12', '17:30:00', 'Lantai 5 Aula Perpustakaan UNAND', 100, 0, 'workshop_images/WpEp60KznusAkoeo4aGkytjEJ65aUxt4fHlXmyN9.jpg', 'selesai', '2025-11-06 23:20:50', '2025-11-19 16:06:34'),
(2, 4, 'Pengenalan Generative AI', 'Workshop ini dirancang untuk memberikan pemahaman dasar mengenai Generative AI, termasuk konsep, teknologi di baliknya, serta aplikasinya dalam berbagai bidang. Peserta akan mendapatkan teori sekaligus praktik untuk mengembangkan ide kreatif menggunakan AI.', '2025-11-26', '13:40:00', 'Lantai 5 Aula Perpustakaan UNAND', 100, 0, 'workshop_images/RAxiErtPqHeQfwedKJZhTmi1jLzcCKoRkuwUduSa.png', 'aktif', '2025-11-07 11:38:58', '2025-11-26 04:55:55'),
(3, 4, 'dvfgn', 'sadfsdgfhmj', '2025-11-10', '17:33:00', 'sdfsdgfnhmgj', 12, 0, 'workshop_images/Wu5ioJjOTjMIHaJPfsUZ8Qzhp0tPbVe0fTTQTG7b.jpg', 'selesai', '2025-11-10 03:30:28', '2025-11-10 10:35:14'),
(4, 4, 'sdsfdgfhgjhk', 'fsgdhfgjh,k', '2025-11-11', '02:57:00', 'fghmjk,.l/', 21, 0, 'workshop_images/mOaaLzxm0qqEj0ykbvvcUFQOe6AzBMs1bDWuZmet.png', 'selesai', '2025-11-10 12:57:06', '2025-11-11 02:26:06'),
(5, 3, 'fhgjh,kk', 'cdvfbgnhm,', '2025-12-11', '04:00:00', 'sdfsgdhfhj,k.l', 12, 0, 'workshop_images/U6JmRPhNmujDCLMCn7wEXR6GvSc85u6sArWSKoxy.png', 'aktif', '2025-11-10 12:59:58', '2025-11-10 12:59:58'),
(6, 4, 'adsfgdhmj,.', 'fsgfdhfjg,hk.jl', '2025-12-15', '09:40:00', 'dfdghmgjk.l', 324, 0, 'workshop_images/WH9KM2dYdqWTEJvbcq8KlLX6KN3O4ZbmA034kXmj.jpg', 'aktif', '2025-11-10 13:23:16', '2025-11-10 13:23:16'),
(7, 4, 'Linkedn', 'pelatihan linkedn', '2025-11-26', '13:43:00', 'Lantai 5 Aula Perpustakaan Universitas Andalas', 40, 1, 'workshop_images/MAbLJ4ESVuu6UBHSARxmfdIRAHk3lSO1Wfz6dW6w.png', 'selesai', '2025-11-26 05:13:47', '2025-11-26 05:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `workshop_keyword`
--

CREATE TABLE `workshop_keyword` (
  `workshop_id` bigint(20) UNSIGNED NOT NULL,
  `keyword_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workshop_keyword`
--

INSERT INTO `workshop_keyword` (`workshop_id`, `keyword_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 5),
(3, 6),
(4, 7),
(4, 8),
(4, 9),
(5, 10),
(5, 11),
(5, 12),
(6, 13),
(6, 14),
(6, 15),
(7, 16),
(7, 17),
(7, 18);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`absensi_id`),
  ADD UNIQUE KEY `absensi_user_id_workshop_id_unique` (`user_id`,`workshop_id`),
  ADD KEY `absensi_workshop_id_index` (`workshop_id`),
  ADD KEY `absensi_user_id_index` (`user_id`);

--
-- Indexes for table `forum_diskusi`
--
ALTER TABLE `forum_diskusi`
  ADD PRIMARY KEY (`discussion_id`),
  ADD KEY `forum_diskusi_workshop_id_index` (`workshop_id`),
  ADD KEY `forum_diskusi_user_id_index` (`user_id`),
  ADD KEY `forum_diskusi_created_at_index` (`created_at`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materi_workshop`
--
ALTER TABLE `materi_workshop`
  ADD PRIMARY KEY (`materi_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`pendaftaran_id`),
  ADD KEY `fk_pendaftaran_user` (`user_id`),
  ADD KEY `fk_pendaftaran_workshop` (`workshop_id`);

--
-- Indexes for table `request_workshop`
--
ALTER TABLE `request_workshop`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_request_user` (`user_id`);

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`sertifikat_id`),
  ADD KEY `fk_sertifikat_pendaftaran` (`pendaftaran_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`workshop_id`),
  ADD KEY `fk_workshops_pemateri` (`pemateri_id`);

--
-- Indexes for table `workshop_keyword`
--
ALTER TABLE `workshop_keyword`
  ADD PRIMARY KEY (`workshop_id`,`keyword_id`),
  ADD KEY `fk_wk_keyword` (`keyword_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `absensi_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forum_diskusi`
--
ALTER TABLE `forum_diskusi`
  MODIFY `discussion_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `materi_workshop`
--
ALTER TABLE `materi_workshop`
  MODIFY `materi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `pendaftaran_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_workshop`
--
ALTER TABLE `request_workshop`
  MODIFY `request_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sertifikat`
--
ALTER TABLE `sertifikat`
  MODIFY `sertifikat_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `workshops`
--
ALTER TABLE `workshops`
  MODIFY `workshop_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_workshop_id_foreign` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`workshop_id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `fk_pendaftaran_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pendaftaran_workshop` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`workshop_id`) ON DELETE CASCADE;

--
-- Constraints for table `request_workshop`
--
ALTER TABLE `request_workshop`
  ADD CONSTRAINT `fk_request_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD CONSTRAINT `fk_sertifikat_pendaftaran` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`pendaftaran_id`) ON DELETE CASCADE;

--
-- Constraints for table `workshops`
--
ALTER TABLE `workshops`
  ADD CONSTRAINT `fk_workshops_pemateri` FOREIGN KEY (`pemateri_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `workshop_keyword`
--
ALTER TABLE `workshop_keyword`
  ADD CONSTRAINT `fk_wk_keyword` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wk_workshop` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`workshop_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
