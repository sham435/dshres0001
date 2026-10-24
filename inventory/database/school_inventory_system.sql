-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2026 at 06:27 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory_check_items`
--

CREATE TABLE `inventory_check_items` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `inventory_number` varchar(100) NOT NULL,
  `book_balance` int(11) DEFAULT 0,
  `real_balance` int(11) DEFAULT 0,
  `surplus` int(11) DEFAULT 0,
  `deficiency` int(11) DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `checked_status` enum('pending','checked','verified') DEFAULT 'pending',
  `checked_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `inventory_check_sessions` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `session_name` varchar(255) NOT NULL,
  `check_date` date NOT NULL,
  `conducted_by` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `inventory_items` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `inventory_number` varchar(100) NOT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `book_number` varchar(100) NOT NULL,
  `incharge_officer_id` int(11) DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `received_from` varchar(255) DEFAULT NULL,
  `is_transferred` tinyint(1) DEFAULT 0,
  `transferred_date` date DEFAULT NULL,
  `borrowed_officer` varchar(255) DEFAULT NULL,
  `condition_status` enum('working','good','not_working','not_usable') DEFAULT 'working',
  `image_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `officers` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `officer_name` varchar(100) NOT NULL,
  `officer_id_number` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `is_incharge` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `removal_recommendations` (
  `id` int(11) NOT NULL,
  `original_item_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `inventory_number` varchar(100) NOT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `book_number` varchar(100) DEFAULT NULL,
  `incharge_officer_id` int(11) DEFAULT NULL,
  `condition_status` varchar(50) DEFAULT NULL,
  `recommendation_reason` text NOT NULL,
  `recommended_by` varchar(100) DEFAULT NULL,
  `recommendation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `removed_items_archive` (
  `id` int(11) NOT NULL,
  `original_item_id` int(11) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `inventory_number` varchar(100) NOT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `section_name` varchar(255) DEFAULT NULL,
  `book_number` varchar(100) DEFAULT NULL,
  `incharge_officer_id` int(11) DEFAULT NULL,
  `incharge_officer_name` varchar(255) DEFAULT NULL,
  `condition_status` varchar(50) DEFAULT NULL,
  `removal_reason` text NOT NULL,
  `removed_by` varchar(100) DEFAULT NULL,
  `removal_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `approval_status` varchar(50) DEFAULT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `disposal_method` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `original_created_at` datetime DEFAULT NULL,
  `original_updated_at` datetime DEFAULT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `school_info` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `principal_name` varchar(100) NOT NULL,
  `principal_phone` varchar(20) NOT NULL,
  `inventory_officer_name` varchar(100) NOT NULL,
  `inventory_officer_phone` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL,
  `section_type` enum('Primary','Secondary','AL','Other') DEFAULT 'Other',
  `main_section` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Census_number` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `passaword` varchar(255) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `sname` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory_check_items`
--
ALTER TABLE `inventory_check_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `inventory_check_sessions`
--
ALTER TABLE `inventory_check_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_number` (`inventory_number`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `incharge_officer_id` (`incharge_officer_id`),
  ADD KEY `idx_inventory_number` (`inventory_number`),
  ADD KEY `idx_section` (`section_id`),
  ADD KEY `idx_condition` (`condition_status`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `officer_id_number` (`officer_id_number`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `removal_recommendations`
--
ALTER TABLE `removal_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_original_item` (`original_item_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `removed_items_archive`
--
ALTER TABLE `removed_items_archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inventory_number` (`inventory_number`),
  ADD KEY `idx_removal_date` (`removal_date`);

--
-- Indexes for table `school_info`
--
ALTER TABLE `school_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

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
-- AUTO_INCREMENT for table `inventory_check_items`
--
ALTER TABLE `inventory_check_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventory_check_sessions`
--
ALTER TABLE `inventory_check_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `removal_recommendations`
--
ALTER TABLE `removal_recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `removed_items_archive`
--
ALTER TABLE `removed_items_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `school_info`
--
ALTER TABLE `school_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_check_items`
--
ALTER TABLE `inventory_check_items`
  ADD CONSTRAINT `inventory_check_items_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `inventory_check_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_check_sessions`
--
ALTER TABLE `inventory_check_sessions`
  ADD CONSTRAINT `inventory_check_sessions_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school_info` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_items_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `inventory_items_ibfk_3` FOREIGN KEY (`incharge_officer_id`) REFERENCES `officers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school_info` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `officers_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school_info` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
