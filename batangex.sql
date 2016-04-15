-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2016 at 04:39 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `batangex`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_audit_trail`
--

CREATE TABLE `admin_audit_trail` (
  `id` int(11) NOT NULL,
  `action` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_id` int(120) DEFAULT NULL,
  `user_id` int(120) DEFAULT NULL,
  `details` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_category`
--

CREATE TABLE `prod_category` (
  `id` int(120) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prod_category`
--

INSERT INTO `prod_category` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Consoles', 'Consoles', '0', '2016-04-15 02:19:56', '2016-04-15 02:19:56'),
(2, 'asd', 'asd', '0', '2016-04-15 08:00:53', '2016-04-15 08:00:53'),
(3, 'jhuenl pogi', 'asdasdasasd', '1', '2016-04-15 08:01:44', '2016-04-15 09:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `prod_image`
--

CREATE TABLE `prod_image` (
  `id` int(120) NOT NULL,
  `prod_id` int(120) DEFAULT NULL,
  `img_file` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_img` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_information`
--

CREATE TABLE `prod_information` (
  `id` int(120) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pro_cat_id` int(120) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_inventory`
--

CREATE TABLE `prod_inventory` (
  `id` int(120) NOT NULL,
  `prod_id` int(120) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_invoice`
--

CREATE TABLE `prod_invoice` (
  `id` int(11) NOT NULL,
  `remarks` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_on_cart`
--

CREATE TABLE `prod_on_cart` (
  `id` int(120) NOT NULL,
  `prod_id` int(120) DEFAULT NULL,
  `cus_id` int(120) DEFAULT NULL,
  `qty` int(6) DEFAULT NULL,
  `ip_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_sold`
--

CREATE TABLE `prod_sold` (
  `id` int(120) NOT NULL,
  `prod_id` int(120) DEFAULT NULL,
  `cus_id` int(120) DEFAULT NULL,
  `prod_invoice_id` int(120) DEFAULT NULL,
  `qty` int(6) DEFAULT NULL,
  `ip_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_specs`
--

CREATE TABLE `prod_specs` (
  `id` int(120) NOT NULL,
  `prod_id` int(120) DEFAULT NULL,
  `specs` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isAdmin` enum('0','1','2','3') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `isVerified` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vCode` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `isAdmin`, `isVerified`, `remember_token`, `vCode`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', '$2y$10$iTBv729vxAqgdrErOoJdM.pYUeKV4tHPzBV1kiINoFwslSLXLRjcW', 'jhunel_2389@yahoo.com', '3', '1', 'zwYG0tzJHCeIiUbpo9xUKI13uNje7v8TKFlH4MlPAQPSegQaWhTeZaLQgM3C', NULL, '2016-04-14 18:24:10', '2016-04-15 01:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(200) NOT NULL,
  `user_id` int(200) DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_address` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `first_name`, `last_name`, `email`, `mobile`, `address`, `dob`, `gender`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super', 'Admin', 'jhunel_2389@yahoo.com', '0000', 'LPC', '1999-02-17', '1', NULL, '2016-04-14 18:25:10', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_audit_trail`
--
ALTER TABLE `admin_audit_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_category`
--
ALTER TABLE `prod_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_image`
--
ALTER TABLE `prod_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_information`
--
ALTER TABLE `prod_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_inventory`
--
ALTER TABLE `prod_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_invoice`
--
ALTER TABLE `prod_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_on_cart`
--
ALTER TABLE `prod_on_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_sold`
--
ALTER TABLE `prod_sold`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prod_specs`
--
ALTER TABLE `prod_specs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fw_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_audit_trail`
--
ALTER TABLE `admin_audit_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prod_category`
--
ALTER TABLE `prod_category`
  MODIFY `id` int(120) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `prod_image`
--
ALTER TABLE `prod_image`
  MODIFY `id` int(120) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prod_information`
--
ALTER TABLE `prod_information`
  MODIFY `id` int(120) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prod_inventory`
--
ALTER TABLE `prod_inventory`
  MODIFY `id` int(120) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prod_invoice`
--
ALTER TABLE `prod_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prod_on_cart`
--
ALTER TABLE `prod_on_cart`
  MODIFY `id` int(120) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prod_sold`
--
ALTER TABLE `prod_sold`
  MODIFY `id` int(120) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prod_specs`
--
ALTER TABLE `prod_specs`
  MODIFY `id` int(120) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
