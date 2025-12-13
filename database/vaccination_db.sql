-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 03:34 PM
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
-- Database: `vaccination_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL DEFAULT 'admin',
  `password` varchar(11) NOT NULL DEFAULT 'admin',
  `admin_name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `admin_name`, `email`, `role_id`, `isActive`, `create_date`) VALUES
(1, 'super admin', 'SA', 'SUPER ADMIN', '', 1, 1, '2025-12-07 02:52:55'),
(6, 'admin', 'admin1', 'ADMIN', 'admin@vaccining.com', 2, 1, '2025-12-12 01:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `childrens`
--

CREATE TABLE `childrens` (
  `child_id` int(11) NOT NULL,
  `child_name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `child_dob` date NOT NULL,
  `child_gender` varchar(25) NOT NULL,
  `bf_crc_no` varchar(16) NOT NULL,
  `child_pob` varchar(255) DEFAULT NULL,
  `child_bloodg` varchar(11) NOT NULL DEFAULT 'N\\A',
  `child_allergic` varchar(255) NOT NULL DEFAULT 'N\\A',
  `parent_id` int(11) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `childrens`
--

INSERT INTO `childrens` (`child_id`, `child_name`, `father_name`, `mother_name`, `child_dob`, `child_gender`, `bf_crc_no`, `child_pob`, `child_bloodg`, `child_allergic`, `parent_id`, `isActive`, `create_date`) VALUES
(9, 'Aaish Ahmed', 'Nadeem Ahmed', 'Misbah Nadeem', '2013-12-30', 'male', '42301-4271335-3', 'Karachi Gulshan Iqbal', 'A+', '', 3, 1, '2025-12-08 23:17:15'),
(10, 'Sinwa Nadeem', 'Nadeem Ahmed', 'Misbah Nadeem', '2007-12-31', 'female', '42301-4271335-4', 'Karachi Gulshan Iqbal', 'AB+', '', 3, 1, '2025-12-09 02:16:16'),
(11, 'Sakina', 'Syed Qaim', 'Mehmal Fatima', '2025-09-06', 'female', '42201153123123', 'city hospital', 'A+', '', 5, 1, '2025-12-10 15:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `hospital_id` int(11) NOT NULL,
  `hospital_name` varchar(255) NOT NULL,
  `lc_rg_no` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hospital_address` varchar(1000) NOT NULL,
  `hospital_description` varchar(1200) DEFAULT NULL,
  `hospital-website` varchar(1200) DEFAULT 'No website provided',
  `password` varchar(2000) NOT NULL,
  `hospital_img` varchar(1200) DEFAULT NULL,
  `terms_privacy_accept` tinyint(1) NOT NULL DEFAULT 1,
  `role_id` int(11) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`hospital_id`, `hospital_name`, `lc_rg_no`, `phone`, `email`, `hospital_address`, `hospital_description`, `hospital-website`, `password`, `hospital_img`, `terms_privacy_accept`, `role_id`, `isActive`, `create_date`) VALUES
(2, 'Shaukat Khanum', 'hosreg1234566', '03132110254', 'hamiznadeem@gmail.com', 'jinnah road karachi', 'hospital', 'www.example2.com', '$2y$10$Lz55xcAfSLdRVIK4phdHpOnIV6/S.f/9ClAhEzK.rP287xMXoobVi', 'android-chrome-512x512.png', 1, 3, 1, '2025-12-07 03:15:09');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiry_id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `inquiry_message` varchar(2000) NOT NULL,
  `inquiry_status` enum('pending','solved','working','') NOT NULL DEFAULT 'pending',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`inquiry_id`, `sender_name`, `sender_email`, `subject`, `inquiry_message`, `inquiry_status`, `create_date`) VALUES
(2, 'hamiz', 'hamiznadeem@gmail.com', 'Booking Issue', 'issue', 'solved', '2025-12-10 04:31:34'),
(3, 'hamiz', 'hamiznadeem@gmail.com', 'General Inquiry', 'hello', 'solved', '2025-12-10 04:35:16'),
(4, 'hamiz', 'hamiznadeem@gmail.com', 'General Inquiry', 'hello', 'solved', '2025-12-10 04:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `cnic` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(2000) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parent_id`, `fname`, `cnic`, `phone`, `email`, `password`, `role_id`, `isActive`, `create_date`) VALUES
(3, 'kinza raza', '545454545554', '03132110254', 'hamiznadeem@gmail.com', '$2y$10$H44IuuFeTDXfMcjqDmlL7OHF.M6t88r19DzDA7iNDuXkUYkzL.dh2', 4, 1, '2025-12-07 11:38:54'),
(5, 'qaimraza', '4220182892427', '03101083391', 'qaim@gmail.com', '$2y$10$h2IYh5gkTHkQjgpE9LSnQuKnygif7q0NW/Eq44T8Qg4hwenV/coQ6', 4, 1, '2025-12-10 15:18:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_des` varchar(255) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_des`, `isActive`) VALUES
(1, 'SUPER ADMIN', 'System Owner', 1),
(2, 'ADMIN', 'Manage User', 1),
(3, 'HOSPITAL', 'Hospital User', 1),
(4, 'PARENT', 'Parent User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vaccination_schedules`
--

CREATE TABLE `vaccination_schedules` (
  `schedule_id` int(11) NOT NULL,
  `child_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `vaccine_id` int(11) DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time` varchar(15) NOT NULL,
  `status` enum('pending','completed','cancelled','approved') NOT NULL DEFAULT 'pending',
  `report` varchar(1200) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccination_schedules`
--

INSERT INTO `vaccination_schedules` (`schedule_id`, `child_id`, `parent_id`, `hospital_id`, `vaccine_id`, `scheduled_date`, `scheduled_time`, `status`, `report`, `create_date`) VALUES
(3, 10, 3, 2, 3, '2025-12-11', '10:00 AM', 'cancelled', NULL, '2025-12-10 05:00:35'),
(4, 9, 3, 2, 4, '2025-12-13', '10:00 AM', 'completed', 'Screenshot 2025-04-26 144749.png', '2025-12-11 01:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `vaccine_id` int(11) NOT NULL,
  `vaccine_name` varchar(255) NOT NULL,
  `batch_no` varchar(255) NOT NULL,
  `doses` int(11) NOT NULL,
  `vaccine_type` enum('Oral','Injection','Nasal') CHARACTER SET utf8mb4 COLLATE utf8mb4_german2_ci NOT NULL,
  `target_age` varchar(25) NOT NULL,
  `m_date` date NOT NULL,
  `exp_date` date NOT NULL,
  `stock_status` enum('high','low','out','') NOT NULL DEFAULT 'out',
  `hospital_id` int(11) DEFAULT NULL,
  `batch_info` varchar(1000) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`vaccine_id`, `vaccine_name`, `batch_no`, `doses`, `vaccine_type`, `target_age`, `m_date`, `exp_date`, `stock_status`, `hospital_id`, `batch_info`, `create_date`) VALUES
(3, 'POLIO (OPV)', 'BATCH-2025-Dec', 2, 'Oral', '1 to 6 year', '2025-12-09', '2026-12-09', 'high', 2, 'nothing', '2025-12-11 00:06:45'),
(4, 'Injection', 'BATCH-2025-Dec', 2, 'Injection', '1 to 2 year&#039;s', '2023-10-31', '2026-12-10', 'high', 2, '', '2025-12-11 00:20:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_role` (`role_id`);

--
-- Indexes for table `childrens`
--
ALTER TABLE `childrens`
  ADD PRIMARY KEY (`child_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`hospital_id`),
  ADD UNIQUE KEY `lc_rg_unique` (`lc_rg_no`),
  ADD UNIQUE KEY `hos_phone_unique` (`phone`),
  ADD UNIQUE KEY `hos_email_unique` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiry_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`),
  ADD UNIQUE KEY `cnic_uniq` (`cnic`),
  ADD UNIQUE KEY `phone_uniq` (`phone`),
  ADD UNIQUE KEY `email_uniq` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `vaccination_schedules`
--
ALTER TABLE `vaccination_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `child_id` (`child_id`),
  ADD KEY `vaccine_id` (`vaccine_id`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`vaccine_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `childrens`
--
ALTER TABLE `childrens`
  MODIFY `child_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `hospital_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vaccination_schedules`
--
ALTER TABLE `vaccination_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `childrens`
--
ALTER TABLE `childrens`
  ADD CONSTRAINT `childrens_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD CONSTRAINT `hospitals_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vaccination_schedules`
--
ALTER TABLE `vaccination_schedules`
  ADD CONSTRAINT `vaccination_schedules_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `vaccination_schedules_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `vaccination_schedules_ibfk_3` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`child_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `vaccination_schedules_ibfk_4` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`vaccine_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD CONSTRAINT `vaccines_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
