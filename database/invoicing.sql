-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2026 at 02:10 AM
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
  `business_entity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sector` varchar(255) DEFAULT NULL,
  `website_url` text,
  `description` text,
  `country` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city_or_regency` varchar(255) DEFAULT NULL,
  `subdistrict` varchar(255) DEFAULT NULL,
  `address` text,
  `logo` text,
  `signature` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `email`, `phone`, `business_entity`, `sector`, `website_url`, `description`, `country`, `province`, `city_or_regency`, `subdistrict`, `address`, `logo`, `signature`) VALUES
(1, 'Red Hat, Inc.', 'redhat@example.com', '081234567891', 'PT', 'Open Source Software', '', 'Red Hat is an American enterprise software company that provides open-source solutions for operating systems, hybrid cloud infrastructure, container platforms, automation, virtualization, middleware, and enterprise support services.', 'Indonesia', 'North Carolina', 'Raleigh', 'Downtown Raleigh', '100 East Davie Street, Raleigh, NC 27601, United States', 'logo_1_1784535584.png', 'signature_1_1784533072.png');

-- --------------------------------------------------------

--
-- Table structure for table `company_pic`
--

CREATE TABLE `company_pic` (
  `id` int NOT NULL,
  `position_id` int DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company_pic`
--

INSERT INTO `company_pic` (`id`, `position_id`, `department_id`, `name`, `phone`, `email`, `status`) VALUES
(8, 1, 8, 'Elon Musk', '081234567890', 'elon@example.com', 'active'),
(10, 10, 5, 'jekso', '0882009259927', 'dzakiprasetyo98@gmail.com', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `customer_code` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_code`, `name`, `email`, `phone`, `address`) VALUES
(2, 'CUST-00002', 'Budi Santoso', 'budi.santoso@example.com', '081234567802', 'Jl. Kenanga No. 5, Sidoarjo'),
(3, 'CUST-00003', 'Citra Lestari', 'citra.lestari@example.com', '081234567803', 'Jl. Mawar No. 18, Gresik'),
(4, 'CUST-00004', 'Dewi Anggraini', 'dewi.anggraini@example.com', '081234567804', 'Jl. Melati No. 7, Malang'),
(5, 'CUST-00005', 'Eko Prasetyo', 'eko.prasetyo@example.com', '081234567805', 'Jl. Diponegoro No. 21, Kediri'),
(6, 'CUST-00006', 'Fitri Handayani', 'fitri.handayani@example.com', '081234567806', 'Jl. Ahmad Yani No. 9, Jombang'),
(7, 'CUST-00007', 'Galih Nugroho', 'galih.nugroho@example.com', '081234567807', 'Jl. Veteran No. 33, Mojokerto'),
(8, 'CUST-00008', 'Hani Wulandari', 'hani.wulandari@example.com', '081234567808', 'Jl. Cempaka No. 15, Pasuruan'),
(9, 'CUST-00009', 'Indra Wijaya', 'indra.wijaya@example.com', '081234567809', 'Jl. Anggrek No. 11, Probolinggo'),
(10, 'CUST-00010', 'Joko Susilo', 'joko.susilo@example.com', '081234567810', 'Jl. Pahlawan No. 44, Blitar'),
(11, 'CUST-00011', 'Kartika Putri', 'kartika.putri@example.com', '081234567811', 'Jl. Teratai No. 6, Tulungagung'),
(12, 'CUST-00012', 'Lukman Hakim', 'lukman.hakim@example.com', '081234567812', 'Jl. Rajawali No. 27, Lamongan'),
(13, 'CUST-00013', 'Maya Sari', 'maya.sari@example.com', '081234567813', 'Jl. Flamboyan No. 3, Banyuwangi'),
(14, 'CUST-00014', 'Nanda Prakoso', 'nanda.prakoso@example.com', '081234567814', 'Jl. Imam Bonjol No. 19, Madiun'),
(15, 'CUST-00015', 'Olivia Permata', 'olivia.permata@example.com', '081234567815', 'Jl. Gajah Mada No. 24, Ngawi'),
(16, 'CUST-00016', 'Putra Mahendra', 'putra.mahendra@example.com', '081234567816', 'Jl. Sudirman No. 31, Bojonegoro'),
(17, 'CUST-00017', 'Qori Aulia', 'qori.aulia@example.com', '081234567817', 'Jl. Cemara No. 10, Tuban'),
(18, 'CUST-00018', 'Rizky Ramadhan', 'rizky.ramadhan@example.com', '081234567818', 'Jl. Kutilang No. 14, Ponorogo'),
(19, 'CUST-00019', 'Siti Rahma', 'siti.rahma@example.com', '081234567819', 'Jl. Merpati No. 22, Magetan'),
(20, 'CUST-00020', 'Taufik Hidayat', 'taufik.hidayat@example.com', '081234567820', 'Jl. Mangga No. 8, Pacitan');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(2, 'Accounting'),
(16, 'Administration'),
(13, 'Customer Service'),
(19, 'Export Import'),
(1, 'Finance'),
(14, 'General Affairs'),
(7, 'Human Resources'),
(8, 'Information Technology'),
(15, 'Legal'),
(11, 'Logistics'),
(6, 'Marketing'),
(9, 'Operations'),
(4, 'Procurement'),
(10, 'Production'),
(18, 'Project Management'),
(3, 'Purchasing'),
(5, 'Sales'),
(17, 'Tax'),
(12, 'Warehouse');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `pic_id` int NOT NULL,
  `company_id` int DEFAULT '1',
  `invoice_code` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `customer_id`, `pic_id`, `company_id`, `invoice_code`, `date`, `due_date`) VALUES
(64, 2, 8, 1, 'INV-000001', '2026-07-06', '2026-07-06'),
(68, 3, 8, 1, 'INV-000002', '2026-07-06', '2026-07-06'),
(71, 5, 8, 1, 'INV-000003', '2026-07-06', '2026-07-06'),
(72, 2, 8, 1, 'INV-000004', '2026-07-05', '2026-07-14'),
(79, 2, 8, 1, 'INV-000005', '2026-07-08', '2026-07-16'),
(83, 2, 8, 1, 'INV-000019', '2026-07-14', '2026-07-21'),
(85, 19, 10, 1, 'INV-0720-0001', '2026-07-20', '2026-07-27'),
(86, 12, 8, 1, 'INV-2026-0001', '2026-07-21', '2026-07-28'),
(87, 18, 8, 1, 'INV-2026-0002', '2026-07-21', '2026-07-28');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_detail`
--

CREATE TABLE `invoice_detail` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL,
  `unit_price` bigint NOT NULL,
  `quantity` int NOT NULL,
  `amount` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice_detail`
--

INSERT INTO `invoice_detail` (`id`, `invoice_id`, `item_id`, `unit_price`, `quantity`, `amount`) VALUES
(52, 68, 20, 1100000, 1000, 1100000000),
(55, 68, 13, 550000, 100, 55000000),
(60, 64, 3, 450000, 10, 4500000),
(67, 71, 18, 950000, 11, 10450000),
(68, 71, 7, 950000, 2, 1900000),
(69, 71, 3, 450000, 1, 450000),
(70, 71, 4, 2150000, 1, 2150000),
(71, 71, 5, 780000, 1, 780000),
(72, 71, 6, 320000, 1, 320000),
(73, 72, 3, 450000, 435, 195750000),
(74, 72, 8, 95000, 387, 36765000),
(75, 83, 3, 450000, 1000, 450000000),
(76, 79, 14, 85000, 100, 8500000),
(77, 85, 19, 1850000, 1, 1850000),
(78, 86, 5, 780000, 421, 328380000),
(79, 86, 14, 85000, 36, 3060000),
(80, 86, 6, 320000, 465, 148800000),
(81, 87, 3, 450000, 1, 450000),
(82, 87, 5, 780000, 1, 780000),
(100, 87, 12, 350000, 12, 4200000),
(101, 87, 9, 2450000, 1, 2450000);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int NOT NULL,
  `ref_no` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `ref_no`, `name`, `price`) VALUES
(3, 'pisang', 'Keyboard Mechanical Fantech', 450000),
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
(20, 'REF-0020', 'UPS APC 650VA', 1100000);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `payment_code` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date` date NOT NULL,
  `amount` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `customer_id`, `invoice_id`, `payment_code`, `date`, `amount`) VALUES
(12, 3, 68, '1111111111', '2026-07-05', 100000000),
(13, 2, 64, '222222', '2026-07-05', 4500000),
(14, 3, 68, '333333', '2026-07-09', 100000000),
(15, 5, 71, '000000', '2026-07-12', 16050000),
(16, 10, 72, '99999', '2026-07-12', 200000000),
(21, 2, 79, '5623566', '2026-07-17', 8500000),
(23, 19, 85, 'PAY-0720-0001', '2026-07-20', 850000),
(24, 19, 85, 'PAY-0720-0002', '2026-07-20', 1000000),
(26, 3, 68, 'PAY-2026-0001', '2026-07-21', 55000000);

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `name`) VALUES
(15, 'Administrator'),
(16, 'Assistant'),
(6, 'Assistant Manager'),
(8, 'Coordinator'),
(3, 'Director'),
(13, 'Executive'),
(4, 'General Manager'),
(5, 'Manager'),
(12, 'Officer'),
(1, 'Owner'),
(2, 'President Director'),
(17, 'Secretary'),
(10, 'Senior Staff'),
(14, 'Specialist'),
(11, 'Staff'),
(7, 'Supervisor'),
(9, 'Team Leader');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@example.com', '$2y$10$7LFqBXiWDQkkFshwijx/g.kSdeTn4haiUmPAGcYbenk9G4u5W8JHu', '2026-07-15 15:30:16', '2026-07-15 15:30:16'),
(2, 'Zidan', 'zidan@example.com', '$2y$10$GYrdEopPOm8.IgK6yEEVYObQDO5pjp4Jqhllff1YrFu/tc1FDA9Ne', '2026-07-15 15:30:16', '2026-07-15 15:30:16'),
(4, 'jesko', 'jesko@gmail.com', '$2y$10$akcwRBX5kKagdLkN3ENWh.JwdG59d/7YyxFIAgKVv8buiIYtI3w.a', '2026-07-15 15:30:16', '2026-07-15 15:53:43'),
(5, 'rakha', 'rakha@example.com', '$2y$10$pdKBvX/PZMq6AJ.z2Q8vY.nI4X5CyW2cMHIzaWdoKw/3jPFlaIhZK', '2026-07-15 16:01:07', '2026-07-15 16:01:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_pic`
--
ALTER TABLE `company_pic`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_position` (`position_id`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `customer_code` (`customer_code`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_invoice` (`invoice_code`),
  ADD KEY `fk_user` (`pic_id`),
  ADD KEY `invoice_ibfk_1` (`customer_id`),
  ADD KEY `fk_company` (`company_id`);

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
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_pic`
--
ALTER TABLE `company_pic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_pic`
--
ALTER TABLE `company_pic`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_position` FOREIGN KEY (`position_id`) REFERENCES `position` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pic` FOREIGN KEY (`pic_id`) REFERENCES `company_pic` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
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
