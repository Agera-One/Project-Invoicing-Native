-- phpMyAdmin SQL Dump
-- version 5.2.3-1.fc43
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 22, 2026 at 08:15 PM
-- Server version: 8.4.9
-- PHP Version: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invoicing`
--

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int NOT NULL,
  `ref_no` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `ref_no`, `name`, `price`) VALUES
(1, 'REF-0001', 'Laptop Asus VivoBook', 4500000),
(2, 'REF-0002', 'Mouse Logitech M220', 185000),
(3, 'REF-0003', 'Keyboard Mechanical Fantech', 450000),
(4, 'REF-0004', 'Monitor LG 24 Inch', 2150000),
(5, 'REF-0005', 'Headset HyperX Cloud Stinger', 780000),
(6, 'REF-0006', 'Webcam Logitech C270', 320000),
(7, 'REF-0007', 'SSD Samsung 500GB', 950000),
(8, 'REF-0008', 'Flashdisk SanDisk 64GB', 95000),
(9, 'REF-0009', 'Printer Epson L3210', 2450000),
(10, 'REF-0010', 'Router TP-Link Archer C6', 650000),
(11, 'REF-0011', 'Smartphone Samsung Galaxy A35', 4200000),
(12, 'REF-0012', 'Power Bank Anker 10000mAh', 350000),
(13, 'REF-0013', 'Speaker JBL Go 3', 550000),
(14, 'REF-0014', 'Kabel HDMI 2 Meter', 85000),
(15, 'REF-0015', 'Cooling Pad Laptop', 175000),
(16, 'REF-0016', 'Smartwatch Redmi Watch 5', 1200000),
(17, 'REF-0017', 'Microphone Fifine K669B', 480000),
(18, 'REF-0018', 'Meja Komputer Minimalis', 950000),
(19, 'REF-0019', 'Kursi Gaming RGB', 1850000),
(20, 'REF-0020', 'UPS APC 650VA', 1100000),
(21, 'REF-0021', 'Zidan Rasyid Susanto', 1000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
