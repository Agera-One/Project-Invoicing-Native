-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 30, 2026 at 01:05 AM
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
-- Database: `invoice_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `business_entity` varchar(255) NOT NULL,
  `sector` varchar(255) NOT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text NOT NULL,
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
(2, 'Red Hat, Inc.', 'redhat@example.com', '081234567891', 'CV', 'Open Source Software', '', 'Red Hat is an American enterprise software company that provides open-source solutions for operating systems, hybrid cloud infrastructure, container platforms, automation, virtualization, middleware, and enterprise support services', 'United States', 'North Carolina', 'Raleigh', 'Downtown Raleigh', '100 East Davie Street, Raleigh, NC 27601, United States', 'logo_2_1785135406.png', 'signature_2_1785135412.png'),
(4, 'Amura Store', 'amura@example.com', '08649236332', 'Perorangan', 'Topup Game', NULL, '', 'Indonesia', 'East Java', 'Surabaya', 'Merdeka', 'Jl. Merdeka No. 45, RT 01/RW 02', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `customer_code` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `phone` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` text NOT NULL,
  `company_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_code`, `name`, `email`, `phone`, `address`, `company_id`) VALUES
(57, 'CUST-2026-0001', 'Budi Santoso', 'budi.santoso@example.com', '081234567801', 'Jl. Kenanga No. 5, Sidoarjo', 2),
(58, 'CUST-2026-0002', 'jesko', 'jesko@example.com', '08127145725435', 'jalan', 4),
(59, 'CUST-2026-0003', 'hanif', 'hanif@example.com', '0813457785433', 'jalan jalan jalan', 2);

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
  `customer_id` int NOT NULL,
  `created_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_code`, `date`, `due_date`, `pic_id`, `company_id`, `customer_id`, `created_by`) VALUES
(86, 'INV-2026-0001', '2026-07-27', '2026-08-03', 12, 2, 57, 7),
(87, 'INV-2026-0002', '2026-07-30', '2026-08-03', 12, 2, 59, 7),
(88, 'INV-2026-0003', '2026-07-27', '2026-08-03', 13, 4, 58, 9);

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
(1, 500000, 1, 500000, 87, 35),
(2, 400000, 2, 800000, 87, 35),
(3, 500000, 3, 1500000, 86, 35),
(5, 100000, 10, 1000000, 88, 36),
(6, 500000, 3, 1500000, 87, 35);

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
(37, 'REF-2026-0003', 'kacang', 10000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int NOT NULL,
  `payment_code` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date` date NOT NULL,
  `amount` bigint NOT NULL,
  `invoice_id` int NOT NULL,
  `created_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `payment_code`, `date`, `amount`, `invoice_id`, `created_by`) VALUES
(29, 'PAY-2026-0001', '2026-07-27', 800000, 87, 7),
(30, 'PAY-2026-0002', '2026-07-27', 500000, 87, 7),
(32, 'PAY-2026-0003', '2026-07-27', 1000000, 88, 9),
(33, 'PAY-2026-0004', '2026-07-27', 1500000, 86, 7);

-- --------------------------------------------------------

--
-- Table structure for table `pic`
--

CREATE TABLE `pic` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
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
(10, 'ambatukam', 'ambatukam@example.com', '$2y$10$hDtsiR6sM9cfiVhPVNnkr.23F31yG72wfOJucjH68y5en2/mebxOq', '2026-07-29 06:05:07', '2026-07-29 06:05:07', 2);

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
  ADD KEY `fk_invoice_user1_idx` (`created_by`),
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
  ADD KEY `fk_payment_user1_idx` (`created_by`),
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pic`
--
ALTER TABLE `pic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `fk_invoice_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_user1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_payment_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_user1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

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
