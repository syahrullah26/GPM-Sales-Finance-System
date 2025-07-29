-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2025 at 01:57 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `purnamamandiri`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `perusahaan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `no_po` varchar(100) DEFAULT NULL,
  `no_sj` varchar(100) DEFAULT NULL,
  `tanggal_invoice` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `total_beli` decimal(15,2) DEFAULT NULL,
  `total_jual` decimal(15,2) DEFAULT NULL,
  `total_laba` decimal(15,2) DEFAULT NULL,
  `total_persen` decimal(6,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `perusahaan`, `alamat`, `no_invoice`, `no_po`, `no_sj`, `tanggal_invoice`, `jatuh_tempo`, `total_beli`, `total_jual`, `total_laba`, `total_persen`, `created_at`) VALUES
(3, 'PT.Leighton contractors', 'JL.Toyo Giri Bekasi', '8162/PM/INV/VI/2025', '8051740', '6940/GPM/VI/25', '2025-07-04', '2025-07-04', 2050000.00, 3475000.00, 1425000.00, 69.51, '2025-07-04 15:15:46'),
(4, 'CV. Bastami Frozen Food', 'TAMAN MULA SAKTI INDAH N4/08', '8165/PM/INV/VI/2025', '8051739', '6935/GPM/VI/25', '2025-08-01', '2025-08-09', 5000000.00, 10000000.00, 5000000.00, 100.00, '2025-07-04 15:24:07'),
(5, 'PT.Leighton contractors', 'JL.Toyo Giri Bekasi', '8163/PM/INV/VI/2025', '8051738', '6939/GPM/VI/25', '2025-07-04', '2025-08-04', 2100000.00, 8200000.00, 6100000.00, 290.48, '2025-07-04 16:25:06'),
(6, 'PT.Leighton contractors', 'JL.Toyo Giri Bekasi', '8168/PM/INV/VI/2025', '8051742', '6950/GPM/VI/25', '2025-07-07', '2025-08-02', 15000000.00, 32200000.00, 17200000.00, 114.67, '2025-07-07 08:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `harga_beli` decimal(15,2) DEFAULT NULL,
  `harga_jual` decimal(15,2) DEFAULT NULL,
  `subtotal_beli` decimal(15,2) DEFAULT NULL,
  `subtotal_jual` decimal(15,2) DEFAULT NULL,
  `laba` decimal(15,2) DEFAULT NULL,
  `persentase` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `nama_barang`, `quantity`, `satuan`, `harga_beli`, `harga_jual`, `subtotal_beli`, `subtotal_jual`, `laba`, `persentase`) VALUES
(15, 3, 'Sticker 3M logo LCI uk 100 x 20 cm', 50, 'pcs', 20000.00, 32000.00, 1000000.00, 1600000.00, 600000.00, 60.00),
(16, 3, 'Sticker  3M Logo LCI uk 40 x 50 cm', 150, 'pcs', 7000.00, 12500.00, 1050000.00, 1875000.00, 825000.00, 78.57),
(17, 4, 'Sticker  3M Logo Bastami uk 40 x 50 cm', 1002, 'pcs', 5000.00, 10000.00, 5000000.00, 10000000.00, 5000000.00, 100.00),
(18, 5, 'Sticker 3M logo LCI uk 100 x 20 cm', 20, 'pcs', 5000.00, 10000.00, 100000.00, 200000.00, 100000.00, 100.00),
(19, 5, 'Sticker  3M Logo LCI uk 40 x 50 cm', 1000, 'pcs', 2000.00, 8000.00, 2000000.00, 8000000.00, 6000000.00, 300.00),
(20, 6, 'Sticker 3M logo LCI uk 100 x 20 cm', 1000, 'pcs', 7000.00, 13200.00, 7000000.00, 13200000.00, 6200000.00, 88.57),
(21, 6, 'Sticker  3M Logo LCI uk 40 x 50 cm', 2000, 'pcs', 4000.00, 9500.00, 8000000.00, 19000000.00, 11000000.00, 137.50);

-- --------------------------------------------------------

--
-- Table structure for table `penawaran`
--

CREATE TABLE `penawaran` (
  `id` int(11) NOT NULL,
  `no_sp` varchar(255) DEFAULT NULL,
  `nama_perusahaan` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `status` enum('menunggu','selesai','batal') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penawaran`
--

INSERT INTO `penawaran` (`id`, `no_sp`, `nama_perusahaan`, `alamat`, `tanggal`, `total`, `status`, `created_at`, `updated_at`) VALUES
(21, '1/SP/VII/2025', 'PT. Gerak Koin Indonesia', 'Senayan Jakarta Selatan', '2025-07-05', 1450000.00, 'batal', '2025-07-05 12:24:29', '2025-07-07 08:42:28'),
(22, '5/SP/VII/2025', 'PT.Leighton contractors', 'JL.Toyo Giri Bekasi', '2025-07-07', 32200000.00, 'selesai', '2025-07-07 07:23:01', '2025-07-07 08:25:20'),
(23, '2/SP/VII/2025', 'CV. Bastami Frozen Food', 'TAMAN MULA SAKTI INDAH N4/08', '2025-07-07', 3025000.00, 'menunggu', '2025-07-07 08:45:27', '2025-07-07 08:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `penawaran_items`
--

CREATE TABLE `penawaran_items` (
  `id` int(11) NOT NULL,
  `penawaran_id` int(11) DEFAULT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_beli` decimal(15,2) DEFAULT NULL,
  `harga_jual` decimal(15,2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah` decimal(15,2) DEFAULT NULL,
  `laba` decimal(15,2) DEFAULT NULL,
  `persentase_laba` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penawaran_items`
--

INSERT INTO `penawaran_items` (`id`, `penawaran_id`, `nama_barang`, `quantity`, `satuan`, `harga_beli`, `harga_jual`, `keterangan`, `jumlah`, `laba`, `persentase_laba`) VALUES
(10, 21, 'Sticker 3M logo WorldCoin uk 100 x 20 cm', 100, 'pcs', 10000.00, 14500.00, 'Sesuai Dengan Sample', 1450000.00, 450000.00, 45.00),
(11, 22, 'Sticker 3M logo LCI uk 100 x 20 cm', 1000, 'pcs', 7000.00, 13200.00, 'Sesuai Dengan Sample', 13200000.00, 6200000.00, 88.57),
(12, 22, 'Sticker  3M Logo LCI uk 40 x 50 cm', 2000, 'pcs', 4000.00, 9500.00, 'Sesuai Dengan Sample', 19000000.00, 11000000.00, 137.50),
(13, 23, 'Sticker  3M Logo Bastami uk 40 x 50 cm', 242, 'pcs', 7000.00, 12500.00, 'Sesuai Dengan Sample', 3025000.00, 1331000.00, 78.57);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `penawaran`
--
ALTER TABLE `penawaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penawaran_items`
--
ALTER TABLE `penawaran_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penawaran_id` (`penawaran_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `penawaran`
--
ALTER TABLE `penawaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `penawaran_items`
--
ALTER TABLE `penawaran_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penawaran_items`
--
ALTER TABLE `penawaran_items`
  ADD CONSTRAINT `penawaran_items_ibfk_1` FOREIGN KEY (`penawaran_id`) REFERENCES `penawaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
