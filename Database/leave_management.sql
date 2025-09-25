-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 08:50 AM
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
-- Database: `leave_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `manager_id`) VALUES
(1, 'Administration', NULL),
(2, 'Human Resource', 15),
(3, 'Sales and Marketing', 18),
(4, 'Accounting', 3),
(5, 'Information Technology', 17);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hire_date` date NOT NULL,
  `department_id` int(11) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('administrator','employee','manager','') NOT NULL DEFAULT 'employee',
  `mobile` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `first_name`, `last_name`, `email`, `hire_date`, `department_id`, `gender`, `manager_id`, `username`, `password`, `role`, `mobile`) VALUES
(1, 'Elsie', 'Orleans Lindsay', 'elsie@gmail.com', '2025-05-01', 1, 'Female', NULL, 'adminelsie', '$2y$10$wkyq81UMcfu9XnZr8vNPMu/bDrpuI04xFqRfmOUh8kWfQZJa8MLKa', 'administrator', 200000000),
(3, 'Janet', 'Lindsay', 'ljay@gmail.com', '2025-02-10', 4, 'Female', NULL, 'manjay', '$2y$10$BRaLrWJpEFFEYd8b4lbWFOAdiJnCRjKYY43rjiVvKwLDdSX4i.e5S', 'manager', 243331718),
(9, 'Cynthia', 'Ofori', 'ceecee@gmail.com', '2025-08-11', 4, 'Female', 3, 'empcynofo', '$2y$10$ydGq/oD0OB95cstq6zKhEOSzkQcIwA0AfwybxBEG2XIznORg6ecwy', 'employee', 547771234),
(15, 'Samuelle_Marie', 'Anderson', 'smander@example.com', '2025-03-13', 2, 'Female', NULL, 'samuelleman', '$2y$10$2eKqIVYgWLrYOo/9.tRnyuTJN4ZQss4wRc3XIeCH/LseqrMJPR.Ie', 'manager', 599990000),
(17, 'Rayan', 'Tunde', 'rayray@example.com', '2025-09-16', 5, 'Male', NULL, 'raaliemp', '$2y$10$KcuZbjFT5ZjVYC0R.dg/f.4n4VNXj9sAyLiQW1ej.zluJovULCcCW', 'manager', 543002001),
(18, 'Leslie', 'Lindsay', 'lol@example.com', '2025-05-07', 3, 'Male', NULL, 'greylol', '$2y$10$4KRZKwp8kJPJNssXV3omHe137n56abKjY/HlxkPCbcc5GSdZlcc76', 'manager', 300000200),
(19, 'Solomon', 'Grandy', 'bornmon@example.com', '2025-08-07', 3, 'Male', NULL, 'solemp', '$2y$10$g52Gu.On0YzufpqMbuZCW.Z4/6YZoIvnsHXatR1anNMaZ0Xb1abce', 'employee', 300000000),
(20, 'Akosua', 'Akoto', 'akoako@example.com', '2025-09-27', 2, 'Female', NULL, 'akokoemp', '$2y$10$5.a8xm14XOECvsMwSJi/RePv.z9cuR6gEXdj7CZJdZeM.RCreV3wO', 'employee', 300000001),
(22, 'Baba', 'Tigya', 'tigyaso@example.com', '2025-09-05', 4, 'Male', NULL, 'tigyagya', '$2y$10$Ze1cME7nvsAZUzZjCJ3cBO0y/8v9NLlKvJGAC1kAz.TTmP7tcSyhS', 'employee', 202020202),
(23, 'Mikaela', 'Sompaso', 'miksops@example.com', '2025-09-16', 4, 'Female', NULL, 'miksompa', '$2y$10$6RAYIrIDfm77z5ONnDo2TuvrxFX.sEFtCTwc/XbZXFpKLHudpj872', 'employee', 300004000);

-- --------------------------------------------------------

--
-- Table structure for table `leave_balance`
--

CREATE TABLE `leave_balance` (
  `employee_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `current_balance` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_accrual_date` date DEFAULT NULL,
  `next_accrual_date` date DEFAULT NULL,
  `total_accrued_since_hire` decimal(6,2) NOT NULL DEFAULT 0.00,
  `total_taken_since_hire` decimal(6,2) NOT NULL DEFAULT 0.00,
  `balance_asof_date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_policy`
--

CREATE TABLE `leave_policy` (
  `policy_id` int(11) NOT NULL,
  `policy_name` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `accrual_rate` decimal(5,2) NOT NULL,
  `maxdays_peryear` int(11) NOT NULL DEFAULT 0,
  `noticeperiod_days` int(11) NOT NULL DEFAULT 0,
  `gender_specific` enum('male','female','all') DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_policy`
--

INSERT INTO `leave_policy` (`policy_id`, `policy_name`, `type_id`, `accrual_rate`, `maxdays_peryear`, `noticeperiod_days`, `gender_specific`, `description`) VALUES
(1, 'Annual Leave', 1, 1.60, 20, 14, 'all', 'Time off for personal activities'),
(2, 'Bereavement Leave', 2, 0.42, 5, 1, 'all', 'Loss of a family member'),
(3, 'Sick Leave', 3, 0.58, 7, 1, 'all', 'For sicknesses, injuries or hospital visits'),
(4, 'Maternity Leave', 4, 7.00, 84, 30, 'female', 'For Pregnant women close to due date'),
(5, 'Paternity Leave', 5, 0.83, 10, 1, 'male', 'For father to help with child'),
(6, 'Casual Leave', 6, 0.58, 7, 1, 'all', 'Personal matters'),
(7, 'Study Leave', 7, 0.00, 10, 7, 'all', 'Time off for examinations');

-- --------------------------------------------------------

--
-- Table structure for table `leave_request`
--

CREATE TABLE `leave_request` (
  `request_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `leave_duration` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by` int(11) DEFAULT NULL,
  `approved_on` timestamp NULL DEFAULT NULL,
  `manager_comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_request`
--

INSERT INTO `leave_request` (`request_id`, `employee_id`, `type_id`, `start_date`, `end_date`, `leave_duration`, `reason`, `status`, `request_date`, `approved_by`, `approved_on`, `manager_comments`) VALUES
(1, 9, 4, '2025-08-30', '2025-11-20', 84, 'Pregnant', 'approved', '2025-07-30 12:29:31', NULL, NULL, NULL),
(2, 9, 3, '2025-09-18', '2025-09-19', 1, 'sick', 'approved', '2025-09-16 12:36:02', NULL, NULL, NULL),
(3, 9, 6, '2025-09-22', '2025-09-23', 1, '', 'rejected', '2025-09-16 12:44:30', NULL, NULL, NULL),
(4, 20, 1, '2025-09-30', '2025-10-17', 13, '', 'pending', '2025-09-16 12:59:27', NULL, NULL, NULL),
(5, 23, 7, '2025-10-01', '2025-10-08', 5, '', 'pending', '2025-09-16 13:00:40', NULL, NULL, NULL),
(7, 22, 3, '2025-09-18', '2025-09-20', 2, '', 'pending', '2025-09-16 13:20:05', NULL, NULL, NULL),
(8, 3, 1, '2025-10-02', '2025-10-02', 0, 'I need a break', 'pending', '2025-09-18 05:34:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE `leave_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `requires_approval` tinyint(1) NOT NULL DEFAULT 1,
  `default_accrualrate_perperiod` decimal(5,2) NOT NULL DEFAULT 0.00,
  `max_carryover_days` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`type_id`, `type_name`, `description`, `requires_approval`, `default_accrualrate_perperiod`, `max_carryover_days`) VALUES
(1, 'Annual Leave', 'Time off for personal activities', 1, 1.60, 0),
(2, 'Bereavement Leave', 'Loss of a family member', 1, 0.42, 0),
(3, 'Sick Leave', 'For sicknesses or injuries or hospital visits', 1, 0.58, 0),
(4, 'Maternity Leave', 'For Pregnant women close to due date', 1, 7.00, 0),
(5, 'Paternity Leave', 'For father to help with child', 1, 0.83, 0),
(6, 'Casual Leave', 'Personal matters', 1, 0.58, 0),
(7, 'Study Leave', 'Time off for examinations', 1, 0.83, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `leave_balance`
--
ALTER TABLE `leave_balance`
  ADD PRIMARY KEY (`employee_id`,`type_id`,`year`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `leave_policy`
--
ALTER TABLE `leave_policy`
  ADD PRIMARY KEY (`policy_id`),
  ADD UNIQUE KEY `policy_name` (`policy_name`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `leave_request`
--
ALTER TABLE `leave_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `leave_request_ibfk_2` (`type_id`);

--
-- Indexes for table `leave_type`
--
ALTER TABLE `leave_type`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `leave_request`
--
ALTER TABLE `leave_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `leave_balance`
--
ALTER TABLE `leave_balance`
  ADD CONSTRAINT `leave_balance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `leave_balance_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `leave_type` (`type_id`);

--
-- Constraints for table `leave_policy`
--
ALTER TABLE `leave_policy`
  ADD CONSTRAINT `leave_policy_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `leave_type` (`type_id`);

--
-- Constraints for table `leave_request`
--
ALTER TABLE `leave_request`
  ADD CONSTRAINT `leave_request_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `leave_request_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `leave_type` (`type_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_request_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
