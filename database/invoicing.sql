-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 04, 2026 at 12:01 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

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
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `business_entity` enum('PT','CV','Firma','Koperasi','Perorangan') DEFAULT NULL,
  `website_url` text,
  `sector` varchar(255) DEFAULT NULL,
  `description` text,
  `country` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city_or_regency` varchar(255) DEFAULT NULL,
  `subdistrict` varchar(255) DEFAULT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `phone`, `address`) VALUES
(2, 'Budi Santoso', 'budi.santoso@example.com', '081234567802', 'Jl. Kenanga No. 5, Sidoarjo'),
(3, 'Citra Lestari', 'citra.lestari@example.com', '081234567803', 'Jl. Mawar No. 18, Gresik'),
(4, 'Dewi Anggraini', 'dewi.anggraini@example.com', '081234567804', 'Jl. Melati No. 7, Malang'),
(5, 'Eko Prasetyo', 'eko.prasetyo@example.com', '081234567805', 'Jl. Diponegoro No. 21, Kediri'),
(6, 'Fitri Handayani', 'fitri.handayani@example.com', '081234567806', 'Jl. Ahmad Yani No. 9, Jombang'),
(7, 'Galih Nugroho', 'galih.nugroho@example.com', '081234567807', 'Jl. Veteran No. 33, Mojokerto'),
(8, 'Hani Wulandari', 'hani.wulandari@example.com', '081234567808', 'Jl. Cempaka No. 15, Pasuruan'),
(9, 'Indra Wijaya', 'indra.wijaya@example.com', '081234567809', 'Jl. Anggrek No. 11, Probolinggo'),
(10, 'Joko Susilo', 'joko.susilo@example.com', '081234567810', 'Jl. Pahlawan No. 44, Blitar'),
(11, 'Kartika Putri', 'kartika.putri@example.com', '081234567811', 'Jl. Teratai No. 6, Tulungagung'),
(12, 'Lukman Hakim', 'lukman.hakim@example.com', '081234567812', 'Jl. Rajawali No. 27, Lamongan'),
(13, 'Maya Sari', 'maya.sari@example.com', '081234567813', 'Jl. Flamboyan No. 3, Banyuwangi'),
(14, 'Nanda Prakoso', 'nanda.prakoso@example.com', '081234567814', 'Jl. Imam Bonjol No. 19, Madiun'),
(15, 'Olivia Permata', 'olivia.permata@example.com', '081234567815', 'Jl. Gajah Mada No. 24, Ngawi'),
(16, 'Putra Mahendra', 'putra.mahendra@example.com', '081234567816', 'Jl. Sudirman No. 31, Bojonegoro'),
(17, 'Qori Aulia', 'qori.aulia@example.com', '081234567817', 'Jl. Cemara No. 10, Tuban'),
(18, 'Rizky Ramadhan', 'rizky.ramadhan@example.com', '081234567818', 'Jl. Kutilang No. 14, Ponorogo'),
(19, 'Siti Rahma', 'siti.rahma@example.com', '081234567819', 'Jl. Merpati No. 22, Magetan'),
(20, 'Taufik Hidayat', 'taufik.hidayat@example.com', '081234567820', 'Jl. Mangga No. 8, Pacitan'),
(26, 'Eka Wahyuni', 'eka.wahyuni@example.com', '081234567826', 'Jl. Pemuda No. 10, Surabaya'),
(27, 'Fajar Subekti', 'fajar.subekti@example.com', '081234567827', 'Jl. Pahlawan No. 55, Sidoarjo'),
(28, 'Gita Permati', 'gita.permata@example.com', '081234567828', 'Jl. Jayandaru No. 22, Asro');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '1',
  `invoice_code` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `customer_id`, `user_id`, `invoice_code`, `date`, `due_date`) VALUES
(64, 2, 1, 'INV-000001', '1111-11-11', '1111-11-11'),
(68, 3, 1, 'INV-000002', '5555-05-05', '5555-05-05'),
(70, 5, 1, 'INV-000004', '4444-04-04', '4444-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_detail`
--

CREATE TABLE `invoice_detail` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL,
  `unit_price` int NOT NULL,
  `quantity` int NOT NULL,
  `amount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice_detail`
--

INSERT INTO `invoice_detail` (`id`, `invoice_id`, `item_id`, `unit_price`, `quantity`, `amount`) VALUES
(52, 68, 20, 1100000, 1000, 1100000000),
(55, 68, 13, 550000, 1000, 550000),
(59, 68, 11, 4200000, 1000, 4200000),
(60, 64, 3, 450000, 10, 4500000);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int NOT NULL,
  `ref_no` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `ref_no`, `name`, `price`) VALUES
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
(21, 'REF-0021', 'Zidan Rasyid Susanto', 100);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `payment_code` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date` date NOT NULL,
  `amount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `customer_id`, `invoice_id`, `payment_code`, `date`, `amount`) VALUES
(7, 2, 64, '1111111111', '1111-11-11', 135000000),
(9, 2, 64, '222223', '1111-11-11', 50000000),
(10, 3, 68, '333333', '3333-03-31', 1000000000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `phone`, `email`, `position`) VALUES
(1, 'Linus Torvald', '081234567830', 'linus@example.com', 'founder'),
(2, 'Administrator', '1246789900975', 'admin@example.com', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_invoice` (`invoice_code`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `invoice_ibfk_1` (`customer_id`);

--
-- Indexes for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invoice` (`invoice_id`),
  ADD KEY `fk_item` (`item_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_no` (`ref_no`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`,`invoice_id`),
  ADD KEY `fl_invoice_2` (`invoice_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD CONSTRAINT `fk_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fl_invoice_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
