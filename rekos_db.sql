-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 02:38 PM
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
-- Database: `rekos_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_alternatif` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `alamat`, `harga`, `deskripsi`, `gambar`) VALUES
(1, 'Kost A', 'Jl. Merdeka No. 10', 1500000, 'Kost nyaman dekat kampus dengan fasilitas lengkap', 'kosA.webp'),
(2, 'Kost B', 'Jl. Sudirman No. 25', 1200000, 'Kost murah dengan lokasi strategis', 'kosB.webp'),
(3, 'Kost C', 'Jl. Pahlawan No. 7', 1800000, 'Kost eksklusif dengan AC dan Wi-Fi', 'kosC.webp'),
(4, 'Kost D', 'Jl. Diponegoro No. 15', 1000000, 'Kost ekonomis dengan fasilitas standar', 'kosD.webp'),
(5, 'Kost E', 'Jl. Gatot Subroto No. 30', 2000000, 'Kost premium full furnished', 'kosE.webp'),
(6, 'Kost F', 'Jl. Ahmad Yani No. 12', 1400000, 'Kost strategis dekat pusat perbelanjaan', 'kosF.webp');

-- --------------------------------------------------------

--
-- Table structure for table `gambar_interior`
--

CREATE TABLE `gambar_interior` (
  `id` int(11) NOT NULL,
  `id_alternatif` int(11) DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gambar_interior`
--

INSERT INTO `gambar_interior` (`id`, `id_alternatif`, `nama_file`) VALUES
(1, 1, 'kosA1.webp'),
(2, 1, 'kosA2.webp'),
(3, 1, 'kosA3.webp'),
(4, 1, 'kosA4.webp'),
(5, 1, 'kosA5.webp'),
(6, 1, 'kosA6.webp');

-- --------------------------------------------------------

--
-- Table structure for table `kos`
--

CREATE TABLE `kos` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi_singkat` text DEFAULT NULL,
  `deskripsi_lengkap` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `skor` decimal(5,4) DEFAULT 0.0000,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kos`
--

INSERT INTO `kos` (`id`, `nama`, `harga`, `deskripsi_singkat`, `deskripsi_lengkap`, `alamat`, `fasilitas`, `gambar`, `skor`, `created_at`) VALUES
(1, 'Kost E', 2000000.00, 'Kost premium full furnished', 'Kost E adalah kost premium dengan fasilitas lengkap dan mewah. Kamar sudah termasuk furniture berkualitas tinggi dengan desain modern.', 'Jl. Menteng Raya No. 12, Jakarta Pusat', 'AC, Kamar Mandi Dalam, TV, Lemari, Kasur, Meja Kerja, WiFi, Dapur Bersama, Laundry', NULL, 0.0000, '2025-04-14 09:40:27'),
(2, 'Kost A', 1500000.00, 'Kost nyaman dekat kampus dengan fasilitas lengkap', 'Kost A berlokasi strategis dekat dengan kampus utama. Lingkungan yang nyaman dan aman untuk mahasiswa.', 'Jl. Kampus Barat No. 45, Depok', 'AC, Kamar Mandi Dalam, Lemari, Kasur, Meja Belajar, WiFi, Parkir Motor', NULL, 0.0000, '2025-04-14 09:40:27'),
(3, 'Kost B', 1200000.00, 'Kost murah dengan lokasi strategis', 'Kost B menawarkan harga terjangkau dengan lokasi yang sangat strategis dekat pusat kota dan transportasi umum.', 'Jl. Sudirman No. 78, Jakarta Selatan', 'Kipas Angin, Kamar Mandi Dalam, Lemari, Kasur, WiFi, Parkir Motor', NULL, 0.0000, '2025-04-14 09:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` float NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`, `jenis`) VALUES
(1, 'Harga', 0.3, 'cost'),
(2, 'Jarak ke Kampus', 0.25, 'cost'),
(3, 'Fasilitas', 0.2, 'benefit'),
(4, 'Keamanan', 0.15, 'benefit'),
(5, 'Kebersihan', 0.1, 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_alternatif`
--

CREATE TABLE `nilai_alternatif` (
  `id` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_alternatif`
--

INSERT INTO `nilai_alternatif` (`id`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(1, 1, 1, 1500000),
(2, 1, 2, 500),
(3, 1, 3, 4),
(4, 1, 4, 4),
(5, 1, 5, 3),
(6, 2, 1, 1200000),
(7, 2, 2, 800),
(8, 2, 3, 3),
(9, 2, 4, 3),
(10, 2, 5, 3),
(11, 3, 1, 1800000),
(12, 3, 2, 1000),
(13, 3, 3, 5),
(14, 3, 4, 4),
(15, 3, 5, 4),
(16, 4, 1, 1000000),
(17, 4, 2, 1500),
(18, 4, 3, 2),
(19, 4, 4, 3),
(20, 4, 5, 2),
(21, 5, 1, 2000000),
(22, 5, 2, 700),
(23, 5, 3, 5),
(24, 5, 4, 5),
(25, 5, 5, 5),
(26, 6, 1, 1400000),
(27, 6, 2, 1200),
(28, 6, 3, 4),
(29, 6, 4, 4),
(30, 6, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'test', 'tes@gmail.com', '$2y$10$2Tp9SYJfijxZTYSPFTfMduPMlniOHf.W48UCUCGRUEJUCvhdnLkSG', '2025-05-15 11:40:42'),
(2, 'uji', 'uji@gmail.com', '$2y$10$zik07RdZBwe3It2wufi/5ueZOpp5jt1hhNUaC.llmDyiAS2Sp10JO', '2025-05-15 12:18:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `gambar_interior`
--
ALTER TABLE `gambar_interior`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_alternatif` (`id_alternatif`);

--
-- Indexes for table `kos`
--
ALTER TABLE `kos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gambar_interior`
--
ALTER TABLE `gambar_interior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kos`
--
ALTER TABLE `kos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gambar_interior`
--
ALTER TABLE `gambar_interior`
  ADD CONSTRAINT `gambar_interior_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`);

--
-- Constraints for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  ADD CONSTRAINT `nilai_alternatif_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`),
  ADD CONSTRAINT `nilai_alternatif_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
