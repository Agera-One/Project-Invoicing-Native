-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 18, 2026 at 07:50 AM
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
-- Database: `invoicing_wevelope`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `business_entity` varchar(255) NOT NULL,
  `sector` varchar(255) NOT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `country` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `subdistrict` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `logo` text NOT NULL,
  `signature` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `email`, `phone`, `business_entity`, `sector`, `website`, `description`, `country`, `province`, `city`, `subdistrict`, `address`, `logo`, `signature`) VALUES
(2, 'Red Hat, inc', 'redhat@example.com', '081234567892', 'PT', 'Open Source Software', '', 'Red Hat is an American enterprise software company that provides open-source solutions for operating systems, hybrid cloud infrastructure, container platforms, automation, virtualization, middleware, and enterprise support services', 'United States', 'North Carolina', 'Raleigh', 'Downtown Raleigh', '100 East Davie Street, Raleigh, NC 27601, United States', 'logo_2_1786508251.png', 'signature_2_1786508318.png'),
(4, 'Amura Store', 'amura@example.com', '08649236332', 'Perorangan', 'Topup Game', NULL, '', 'Indonesia', 'East Java', 'Surabaya', 'Merdeka', 'Jl. Merdeka No. 45, RT 01/RW 02', '', ''),
(9, 'Levro', 'levro@gmail.com', '48376374356356', 'PT', 'Sedulut Tunggal Kopi', NULL, '', 'sgvdeath', 'tndeagtn', 'agdnaetn', 'aegtngdetan', 'adegndeana', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `customer_code` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` text NOT NULL,
  `company_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_code`, `name`, `email`, `phone`, `address`, `company_id`) VALUES
(57, 'CUST-2026-0001', 'Budi Santoso', 'budi.santoso@example.com', '81234567803', 'Jl. Kenanga No. 8, Surabaya', 2),
(58, 'CUST-2026-0002', 'jesko', 'jesko@example.com', '08127145725435', 'jalan', 4),
(59, 'CUST-2026-0003', 'hanif', 'hanif@example.com', '0813457785433', 'jalan jalan jalan', 2),
(60, 'CUST-2026-0004', 'QQQQQQQQQQQQQQQ', 'dzaki@example.com', '0346578543', 'xetcfygbi', 2),
(63, 'CUST-2026-0007', 'hartono', 'aiocbi@adiuvb', '34567876543', 'esdrtfvyb', 2),
(68, 'CUST-2026-0008', 'Andi Pratama', 'andi.pratama@example.com', '81234567801', 'Jl. Melati No. 12, Bandung', 2),
(69, 'CUST-2026-0009', 'Siti Rahmawati', 'siti.rahmawati@example.com', '81234567802', 'Jl. Mawar No. 25, Jakarta', 2),
(70, 'CUST-2026-0010', 'Rina Wulandari', 'rina.wulandari@example.com', '81234567804', 'Jl. Anggrek No. 17, Yogyakarta', 2),
(71, 'CUST-2026-0011', 'Dimas Saputra', 'dimas.saputra@example.com', '81234567805', 'Jl. Cempaka No. 31, Semarang', 2);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int NOT NULL,
  `invoice_code` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date` date NOT NULL,
  `due_date` date NOT NULL,
  `pic_id` int NOT NULL,
  `company_id` int NOT NULL,
  `customer_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_code`, `date`, `due_date`, `pic_id`, `company_id`, `customer_id`) VALUES
(86, 'INV-2026-0001', '2026-07-27', '2026-08-03', 12, 2, 57),
(87, 'INV-2026-0002', '2026-07-30', '2026-08-03', 12, 2, 59),
(88, 'INV-2026-0003', '2026-07-27', '2026-08-03', 13, 4, 58);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_detail`
--

CREATE TABLE `invoice_detail` (
  `id` int NOT NULL,
  `unit_price` int NOT NULL,
  `quantity` int NOT NULL,
  `amount` bigint NOT NULL,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice_detail`
--

INSERT INTO `invoice_detail` (`id`, `unit_price`, `quantity`, `amount`, `invoice_id`, `item_id`) VALUES
(1, 500000, 10, 5000000, 87, 35),
(3, 500000, 3, 1500000, 86, 35),
(5, 100000, 10, 1000000, 88, 36),
(6, 500000, 3, 1500000, 87, 35),
(10, 500000, 15, 7500000, 87, 85);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int NOT NULL,
  `ref_no` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` bigint NOT NULL,
  `company_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `ref_no`, `name`, `price`, `company_id`) VALUES
(35, 'REF-2026-0001', 'Keyboard Mechanical Fantech', 500000, 2),
(36, 'REF-2026-0002', 'Mouse Logitech M220', 100000, 4),
(41, 'REF-2026-0006', 'Monitor LG 24 Inch', 1850000, 2),
(42, 'REF-2026-0007', 'Monitor Samsung 24 Inch', 2100000, 4),
(43, 'REF-2026-0008', 'Keyboard Logitech K120', 175000, 2),
(44, 'REF-2026-0009', 'Mouse Logitech M331', 325000, 4),
(45, 'REF-2026-0010', 'Webcam Logitech C270', 450000, 2),
(46, 'REF-2026-0011', 'Headset HyperX Cloud Stinger', 850000, 4),
(47, 'REF-2026-0012', 'USB Flashdisk Sandisk 64GB', 125000, 2),
(48, 'REF-2026-0013', 'USB Flashdisk Sandisk 128GB', 210000, 4),
(49, 'REF-2026-0014', 'SSD Kingston NV2 500GB', 750000, 2),
(50, 'REF-2026-0015', 'SSD Samsung 980 500GB', 950000, 4),
(51, 'REF-2026-0016', 'RAM Kingston 8GB DDR4', 350000, 2),
(52, 'REF-2026-0017', 'RAM Kingston 16GB DDR4', 650000, 4),
(53, 'REF-2026-0018', 'RAM Corsair 8GB DDR4', 425000, 2),
(54, 'REF-2026-0019', 'RAM Corsair 16GB DDR4', 725000, 4),
(55, 'REF-2026-0020', 'Power Supply 450W', 550000, 2),
(56, 'REF-2026-0021', 'Power Supply 550W', 700000, 4),
(57, 'REF-2026-0022', 'Power Supply 650W', 850000, 2),
(58, 'REF-2026-0023', 'Casing PC ATX', 650000, 4),
(59, 'REF-2026-0024', 'Casing PC Gaming RGB', 950000, 2),
(60, 'REF-2026-0025', 'Cooling Fan 120mm', 85000, 4),
(61, 'REF-2026-0026', 'CPU Cooler Deepcool', 450000, 2),
(62, 'REF-2026-0027', 'HDMI Cable 2 Meter', 75000, 4),
(63, 'REF-2026-0028', 'DisplayPort Cable 2 Meter', 125000, 2),
(64, 'REF-2026-0029', 'LAN Cable Cat6 5 Meter', 90000, 4),
(65, 'REF-2026-0030', 'LAN Cable Cat6 10 Meter', 150000, 2),
(66, 'REF-2026-0031', 'Router TP-Link Archer C6', 650000, 4),
(67, 'REF-2026-0032', 'Router TP-Link Archer AX10', 950000, 2),
(68, 'REF-2026-0033', 'Switch TP-Link 8 Port', 350000, 4),
(69, 'REF-2026-0034', 'Switch TP-Link 16 Port', 750000, 2),
(70, 'REF-2026-0035', 'Printer Epson L3210', 2350000, 4),
(71, 'REF-2026-0036', 'Printer Canon PIXMA G2020', 2200000, 2),
(72, 'REF-2026-0037', 'Printer HP Ink Tank 315', 1950000, 4),
(73, 'REF-2026-0038', 'Laptop Stand Aluminium', 275000, 2),
(74, 'REF-2026-0039', 'Laptop Stand Adjustable', 350000, 4),
(75, 'REF-2026-0040', 'Mouse Pad Gaming XL', 175000, 2),
(76, 'REF-2026-0041', 'Mouse Pad Gaming XXL', 225000, 4),
(77, 'REF-2026-0042', 'USB Hub 4 Port', 150000, 2),
(78, 'REF-2026-0043', 'USB Hub Type C 6 Port', 325000, 4),
(79, 'REF-2026-0044', 'Bluetooth Speaker JBL', 850000, 2),
(80, 'REF-2026-0045', 'Bluetooth Speaker Anker', 650000, 4),
(81, 'REF-2026-0046', 'Mechanical Keyboard Fantech MK872', 750000, 2),
(82, 'REF-2026-0047', 'Mechanical Keyboard Royal Kludge', 950000, 4),
(83, 'REF-2026-0048', 'Wireless Mouse Fantech', 225000, 2),
(84, 'REF-2026-0049', 'Wireless Mouse Rexus', 185000, 4),
(85, 'REF-2026-0050', 'Gaming Headset Fantech', 550000, 2),
(86, 'REF-2026-0051', 'Gaming Headset Rexus', 475000, 4),
(87, 'REF-2026-0052', 'Keyboard Wireless Logitech', 450000, 2),
(88, 'REF-2026-0053', 'Mouse Wireless Logitech M185', 175000, 4),
(90, 'REF-2026-0055', 'External HDD Toshiba 1TB', 900000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int NOT NULL,
  `payment_code` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date` date NOT NULL,
  `amount` bigint NOT NULL,
  `invoice_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `payment_code`, `date`, `amount`, `invoice_id`) VALUES
(29, 'PAY-2026-0001', '2026-07-27', 800000, 87),
(32, 'PAY-2026-0003', '2026-07-27', 1000000, 88),
(33, 'PAY-2026-0004', '2026-07-27', 1500000, 86),
(34, 'PAY-2026-0005', '2026-08-03', 1000000, 87),
(35, 'PAY-2026-0006', '2026-08-14', 11000000, 87);

-- --------------------------------------------------------

--
-- Table structure for table `pic`
--

CREATE TABLE `pic` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(320) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `position` varchar(255) NOT NULL,
  `company_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pic`
--

INSERT INTO `pic` (`id`, `name`, `phone`, `email`, `is_active`, `position`, `company_id`) VALUES
(12, 'Zidan', '08123456789', 'zidan@example.com', 1, 'Sales', 2),
(13, 'Agera', '081931t638262', 'agera@example.com', 1, 'Managaer', 4),
(14, 'stolid', '0821377426418', 'stolid@example.com', 1, 'staff', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `company_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `company_id`) VALUES
(7, 'Administrator', 'admin@example.com', '$2y$10$g8sMphcnRD7MFKocUmiZC.JR.1wrI4Tpki04cRfMrkM3rVD93X9ia', '2026-07-27 01:49:43', '2026-07-27 01:49:43', 2),
(8, 'Rakha Nafis', 'rakha@example.com', '$2y$10$6EIHhYOlupMw/BEiT2svEeXv98AOlFw1v1Ijszg1e/I.0wBstJZLu', '2026-07-27 07:03:33', '2026-07-27 07:03:33', 2),
(9, 'zidan', 'zidan@example.com', '$2y$10$osDYFn.grXa38qh3lugqVepcpm9w5yhDxAE/N4sOxBDMOBabuNCNW', '2026-07-27 07:35:40', '2026-07-27 07:35:40', 4),
(10, 'ambatukam', 'ambatukam@example.com', '$2y$10$hDtsiR6sM9cfiVhPVNnkr.23F31yG72wfOJucjH68y5en2/mebxOq', '2026-07-29 06:05:07', '2026-07-29 06:05:07', 2),
(11, 'dzaki', 'dzaki@example.com', '$2y$10$e6ohVv.sH9n3lqf23.d8AuABfYB3iQnJCIz8Tpw8jI4P/BGFBRFCu', '2026-07-31 07:07:01', '2026-08-01 13:05:28', 2),
(16, 'Kurniahadi', 'kurniahadi@gmail.com', '$2y$10$sq6L3NACp0JM.RqV/UO4uOZjGbnyDQy.QNNjRH9umszqRXfHDOjU6', '2026-08-16 11:22:24', '2026-08-16 11:22:24', 9),
(17, 'Abdillah', 'abdillah@gmail.com', '$2y$10$q6zlBhEMELx1it9VRfGdt.s08pqeJhMoxwsKnhd2qMiyt67vgY2vK', '2026-08-16 11:23:20', '2026-08-16 11:23:20', 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `phone_UNIQUE` (`phone`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `customer_code` (`customer_code`),
  ADD KEY `fk_customer_company1_idx` (`company_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_invoice` (`invoice_code`),
  ADD KEY `fk_invoice_company_pic1_idx` (`pic_id`),
  ADD KEY `fk_invoice_company1_idx` (`company_id`),
  ADD KEY `fk_invoice_customer1_idx` (`customer_id`);

--
-- Indexes for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_invoice_detail_invoice1_idx` (`invoice_id`),
  ADD KEY `fk_invoice_detail_item1_idx` (`item_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_no` (`ref_no`),
  ADD KEY `fk_item_company1_idx` (`company_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_invoice1_idx` (`invoice_id`);

--
-- Indexes for table `pic`
--
ALTER TABLE `pic`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_pic_company1_idx` (`company_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_company1_idx` (`company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `pic`
--
ALTER TABLE `pic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_company_pic1` FOREIGN KEY (`pic_id`) REFERENCES `pic` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD CONSTRAINT `fk_invoice_detail_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `fk_invoice_detail_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_item_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `pic`
--
ALTER TABLE `pic`
  ADD CONSTRAINT `fk_pic_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
