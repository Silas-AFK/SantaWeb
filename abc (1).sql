-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 11:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abc`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `class` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `class`, `image`, `uploaded_at`) VALUES
(1, 'SILAS BOOK', 'FORM 2', 'uploads/blue.jpg', '2025-08-02 08:07:39'),
(2, 'SILAS BOOK', 'FORM 9', 'uploads/eff.jpg', '2025-08-02 18:49:05'),
(4, 'JOHN BOOK', 'FORM 6 GENERAL', 'uploads/6893eb67c69c2.jpg', '2025-08-06 23:55:19'),
(6, 'XXC', 'CSCC', 'uploads/68a4cebbf1373.jpg', '2025-08-19 19:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `published_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `voucher_no` varchar(100) NOT NULL,
  `org_name` varchar(255) DEFAULT NULL,
  `org_address` text DEFAULT NULL,
  `org_contact` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payee_name` varchar(255) NOT NULL,
  `payee_address` text DEFAULT NULL,
  `payee_contact` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `payment_method` enum('Cash','Cheque','Bank Transfer','Mobile Money') DEFAULT 'Cash',
  `account_details` text DEFAULT NULL,
  `total_due` decimal(12,2) DEFAULT 0.00,
  `total_paid` decimal(12,2) DEFAULT 0.00,
  `balance` decimal(12,2) DEFAULT 0.00,
  `authorized_by` varchar(255) DEFAULT NULL,
  `authorized_designation` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_designation` varchar(255) DEFAULT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `received_designation` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `pdf_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_vouchers`
--

CREATE TABLE `payment_vouchers` (
  `id` int(11) NOT NULL,
  `organization_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `payment_voucher_no` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `payee_name` varchar(255) NOT NULL,
  `payee_address` varchar(255) NOT NULL,
  `payee_contact` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `payment_method` enum('Cash','Cheque','Bank Transfer','Mobile Money') NOT NULL,
  `account_details` varchar(255) DEFAULT NULL,
  `total_due_amount` decimal(10,2) NOT NULL,
  `total_amount_paid` decimal(10,2) NOT NULL,
  `balance_due` decimal(10,2) NOT NULL,
  `authorized_by` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `approved_by` varchar(255) NOT NULL,
  `designation_approved` varchar(255) NOT NULL,
  `received_by` varchar(255) NOT NULL,
  `designation_received` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_vouchers`
--

INSERT INTO `payment_vouchers` (`id`, `organization_name`, `address`, `contact`, `payment_voucher_no`, `date`, `payee_name`, `payee_address`, `payee_contact`, `description`, `payment_method`, `account_details`, `total_due_amount`, `total_amount_paid`, `balance_due`, `authorized_by`, `designation`, `approved_by`, `designation_approved`, `received_by`, `designation_received`, `remarks`, `created_at`) VALUES
(11, 'SANTA MEMORIAL COMPREHENSIVE COLLEGE', 'Bamenda town', '68898989', 'PV-20250819-3315', '2025-08-19', 'JOHN BORRI', 'new bell', '67898990', 'HJN', 'Bank Transfer', '65555555', 90000.00, 700000.00, -610000.00, 'bah joy', 'principal', 'Afanwi Silas Shu', 'VICE PRINCIPAL', 'SMCC', 'TEACHER', 'HH', '2025-08-19 18:57:48'),
(12, 'SANTA MEMORIAL COMPREHENSIVE COLLEGE', 'Bamenda town', '68898989', 'PV-20250819-4041', '2025-08-19', 'JOHN BORRI', 'new bell', '67898990', 'SS', 'Cheque', '65555555', 90000.00, 700000.00, -610000.00, 'bah joy', 'principal', 'Afanwi Silas Shu', 'VICE PRINCIPAL', 'SMCC', 'TEACHER', 'DFV', '2025-08-19 19:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `key` varchar(100) NOT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('results_paused', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_received_by` (`received_by`);

--
-- Indexes for table `payment_vouchers`
--
ALTER TABLE `payment_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_voucher_no` (`payment_voucher_no`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payment_vouchers`
--
ALTER TABLE `payment_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
