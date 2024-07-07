-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2024 at 10:22 AM
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
-- Database: `blood_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `request_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `volume` int(5) NOT NULL,
  `request_date` date NOT NULL,
  `status` enum('pending','fulfilled','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`request_id`, `recipient_id`, `blood_type`, `volume`, `request_date`, `status`) VALUES
(1, 6, 'A-', 2001, '2024-06-20', 'cancelled'),
(2, 6, 'O-', 450, '2024-07-01', 'cancelled'),
(3, 3, 'A+', 100, '2024-07-07', 'pending'),
(4, 3, 'AB+', 200, '2024-07-07', 'pending'),
(5, 3, 'O+', 100000, '2024-07-07', 'pending'),
(6, 6, 'A+', 200, '2024-07-07', 'pending'),
(7, 6, 'O+', 1700, '2024-07-07', 'cancelled'),
(8, 8, 'O+', 777, '2024-07-07', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `blood_test_results`
--

CREATE TABLE `blood_test_results` (
  `result_id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `test_date` date NOT NULL,
  `test_result` enum('positive','negative') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_test_results`
--

INSERT INTO `blood_test_results` (`result_id`, `unit_id`, `test_date`, `test_result`) VALUES
(1, 1, '2024-06-02', 'negative'),
(2, 2, '2024-06-16', 'negative');

-- --------------------------------------------------------

--
-- Table structure for table `blood_units`
--

CREATE TABLE `blood_units` (
  `unit_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `volume` int(5) NOT NULL,
  `expiration_date` date NOT NULL,
  `status` enum('available','unavailable','donated') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_units`
--

INSERT INTO `blood_units` (`unit_id`, `donor_id`, `blood_type`, `volume`, `expiration_date`, `status`, `created_at`) VALUES
(1, 2, 'A+', 500, '2024-12-31', 'available', '2024-07-07 07:34:11'),
(2, 2, 'O-', 450, '2024-11-30', 'available', '2024-07-07 07:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `donation_date` date NOT NULL,
  `location_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `donor_id`, `unit_id`, `donation_date`, `location_id`) VALUES
(1, 2, 1, '2024-06-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_representative_info`
--

CREATE TABLE `hospital_representative_info` (
  `hospital_rep_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_representative_info`
--

INSERT INTO `hospital_representative_info` (`hospital_rep_id`, `user_id`, `hospital_id`) VALUES
(1, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `inventory_manager_id` int(11) DEFAULT NULL,
  `received_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  `status` enum('available','expired') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `unit_id`, `inventory_manager_id`, `received_date`, `expiration_date`, `status`) VALUES
(1, 1, 5, '2024-06-03', '2024-12-31', 'available'),
(2, 2, 5, '2024-06-17', '2024-11-30', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `lab_technician_info`
--

CREATE TABLE `lab_technician_info` (
  `lab_technician_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lab_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_technician_info`
--

INSERT INTO `lab_technician_info` (`lab_technician_id`, `user_id`, `lab_id`) VALUES
(1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `type` enum('lab','hospital') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `name`, `address`, `phone_number`, `type`) VALUES
(1, 'Central Lab', '123 Lab St, Lab City', '1111111111', 'lab'),
(2, 'North Hospital', '456 Hospital St, Hospital City', '2222222222', 'hospital'),
(3, 'South Hospital', '789 Hospital St, Hospital City', '3333333333', 'hospital');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','donor','recipient','lab_technician','inventory_manager','hospital_rep') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `email`, `phone_number`, `address`, `approved`, `created_at`) VALUES
(1, 'admin', '$2y$10$qOoEXwVGQu2eaVUoF9xOTOGehS13HlSZ0mN4aMZT63NTOyM4xQ6jO', 'admin', 'admin1@example.com', '1234567890', '123 Admin St, Admin City', 1, '2024-07-07 07:34:11'),
(2, 'donor', '$2y$10$/ZN44TAqLL.5JLFfH3G52uizAtQjEo80dZiU95vQTL6JqFZ.V6PDy', 'donor', 'donor1@example.com', '2345678901', '456 Donor St, Donor City', 1, '2024-07-07 07:34:11'),
(3, 'recipient', '$2y$10$N8Cw5Jcub6.R2CAVTLxBNu.77OY7Kg0QksukApIObs93CiSXhGoRm', 'recipient', 'recipient1@example.com', '3456789012', '789 Recipient St, Recipient City', 1, '2024-07-07 07:34:11'),
(4, 'lab', '$2y$10$ZAyCiKEckHW42Mfu1WYNReCo1O4SzaLYzTkG2UBQWJr83m9jmQa0m', 'lab_technician', 'labtech1@example.com', '4567890123', '321 Lab St, Lab City', 1, '2024-07-07 07:34:11'),
(5, 'inventory', '$2y$10$fHPt1aS.SQtxLChFHCkh.eQ1KK5jx7UNRORcyLaFbVjQFMbqqZPfi', 'inventory_manager', 'invmanager1@example.com', '5678901234', '654 Inventory St, Inventory City', 1, '2024-07-07 07:34:11'),
(6, 'hospital', '$2y$10$6nM9cVG0d8HUMedHCkn9o.zg73Y./g19MSakMsxUxRtN7tKJINfBW', 'hospital_rep', 'hosprep1@example.com', '6789012345', '987 Hospital St, Hospital City', 1, '2024-07-07 07:34:11'),
(7, 'hospital2', '$2y$10$M7rXfmTv6gr1kGH5SMfv7.oU421p4ZmM/SFb.MnmPF2dg44cYNSe6', 'hospital_rep', 'hosrep2@example.com', '123124425', '999 Hospital St, Hospital City', 0, '2024-07-07 08:07:03'),
(8, 'recipient2', '$2y$10$2SdWa3FtzEKVnBelGtnMaOZtZn/2OM557LBniirNs7Fo/8GhKnAkq', 'recipient', 'recipient2@example.com', '12392370', '777 Recipient St, Recipient City', 1, '2024-07-07 08:11:59'),
(9, 'admin2', '$2y$10$vriJFFCNuqMnMZO0Sdub5OU5u7CbWcd.EtP5M69/eL52LomUj5Umi', 'admin', 'admin2@example.com', '442423423', '111 Admin St, Admin City', 0, '2024-07-07 08:14:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `blood_test_results`
--
ALTER TABLE `blood_test_results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `blood_units`
--
ALTER TABLE `blood_units`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `hospital_representative_info`
--
ALTER TABLE `hospital_representative_info`
  ADD PRIMARY KEY (`hospital_rep_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `inventory_manager_id` (`inventory_manager_id`);

--
-- Indexes for table `lab_technician_info`
--
ALTER TABLE `lab_technician_info`
  ADD PRIMARY KEY (`lab_technician_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lab_id` (`lab_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blood_test_results`
--
ALTER TABLE `blood_test_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blood_units`
--
ALTER TABLE `blood_units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hospital_representative_info`
--
ALTER TABLE `hospital_representative_info`
  MODIFY `hospital_rep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lab_technician_info`
--
ALTER TABLE `lab_technician_info`
  MODIFY `lab_technician_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD CONSTRAINT `blood_requests_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `blood_test_results`
--
ALTER TABLE `blood_test_results`
  ADD CONSTRAINT `blood_test_results_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `blood_units` (`unit_id`) ON DELETE CASCADE;

--
-- Constraints for table `blood_units`
--
ALTER TABLE `blood_units`
  ADD CONSTRAINT `blood_units_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `blood_units` (`unit_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `donations_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE SET NULL;

--
-- Constraints for table `hospital_representative_info`
--
ALTER TABLE `hospital_representative_info`
  ADD CONSTRAINT `hospital_representative_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hospital_representative_info_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `locations` (`location_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `blood_units` (`unit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`inventory_manager_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `lab_technician_info`
--
ALTER TABLE `lab_technician_info`
  ADD CONSTRAINT `lab_technician_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_technician_info_ibfk_2` FOREIGN KEY (`lab_id`) REFERENCES `locations` (`location_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
