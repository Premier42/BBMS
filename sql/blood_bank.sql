-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2024 at 11:26 AM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `user_id`) VALUES
(1, 14),
(3, 28);

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `request_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `volume` decimal(5,2) NOT NULL,
  `request_date` date NOT NULL,
  `status` enum('pending','fulfilled','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`request_id`, `recipient_id`, `blood_type`, `volume`, `request_date`, `status`) VALUES
(1, NULL, 'A+', 200.00, '2024-06-15', 'pending'),
(2, NULL, 'B-', 300.00, '2024-06-16', 'pending'),
(3, NULL, 'O+', 400.00, '2024-06-17', 'pending'),
(4, NULL, 'AB+', 500.00, '2024-06-18', 'pending'),
(5, NULL, 'A-', 250.00, '2024-06-19', 'pending');

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
(1, 1, '2024-06-15', 'positive'),
(2, 2, '2024-06-16', 'negative'),
(3, 3, '2024-06-17', 'positive'),
(4, 4, '2024-06-18', 'negative'),
(5, 5, '2024-06-19', 'positive');

-- --------------------------------------------------------

--
-- Table structure for table `blood_units`
--

CREATE TABLE `blood_units` (
  `unit_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `volume` decimal(5,2) NOT NULL,
  `expiration_date` date NOT NULL,
  `status` enum('available','unavailable','donated') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_units`
--

INSERT INTO `blood_units` (`unit_id`, `donor_id`, `blood_type`, `volume`, `expiration_date`, `status`, `created_at`) VALUES
(1, NULL, 'A+', 500.00, '2024-12-31', 'available', '2024-06-28 06:11:12'),
(2, NULL, 'B-', 300.00, '2024-12-31', 'available', '2024-06-28 06:11:12'),
(3, NULL, 'O+', 400.00, '2024-12-31', 'available', '2024-06-28 06:11:12'),
(4, NULL, 'AB+', 200.00, '2024-12-31', 'available', '2024-06-28 06:11:12'),
(5, NULL, 'O-', 350.00, '2024-12-31', 'available', '2024-06-28 06:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `donation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `donor_id`, `unit_id`, `donation_date`) VALUES
(1, NULL, 1, '2024-06-15'),
(2, NULL, 2, '2024-06-16'),
(3, NULL, 3, '2024-06-17'),
(4, NULL, 4, '2024-06-18'),
(5, NULL, 5, '2024-06-19');

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
(1, 1, NULL, '2024-06-15', '2024-12-31', 'available'),
(2, 2, NULL, '2024-06-16', '2024-12-31', 'available'),
(3, 3, NULL, '2024-06-17', '2024-12-31', 'available'),
(4, 4, NULL, '2024-06-18', '2024-12-31', 'available'),
(5, 5, NULL, '2024-06-19', '2024-12-31', 'available');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `email`, `phone_number`, `address`, `created_at`, `approved`) VALUES
(14, 'admin', '$2y$10$qOoEXwVGQu2eaVUoF9xOTOGehS13HlSZ0mN4aMZT63NTOyM4xQ6jO', 'admin', 'admin@bbms.com', '1234', 'admin address', '2024-06-28 06:23:30', 1),
(18, 'donor', '$2y$10$/ZN44TAqLL.5JLFfH3G52uizAtQjEo80dZiU95vQTL6JqFZ.V6PDy', 'donor', 'donor@bbms.com', '1111', 'donor address', '2024-06-28 09:04:39', 1),
(19, 'recipient', '$2y$10$N8Cw5Jcub6.R2CAVTLxBNu.77OY7Kg0QksukApIObs93CiSXhGoRm', 'recipient', 'recipient@bbms.com', '2222', 'recipient address', '2024-06-28 09:05:40', 1),
(20, 'lab', '$2y$10$ZAyCiKEckHW42Mfu1WYNReCo1O4SzaLYzTkG2UBQWJr83m9jmQa0m', 'lab_technician', 'lab@bbms.com', '3333', 'lab address', '2024-06-28 09:13:54', 1),
(21, 'lab2', '$2y$10$VrUQqPIH32hHoeEIUs8jRuW34ibBqhlRJmp4ND63S7TzHzq3kTI3C', 'lab_technician', 'lab2@bbms.com', '3333', 'lab2 address', '2024-06-28 09:15:09', 0),
(22, 'inventory', '$2y$10$fHPt1aS.SQtxLChFHCkh.eQ1KK5jx7UNRORcyLaFbVjQFMbqqZPfi', 'inventory_manager', 'inventory@bbms.com', '4444', 'inventory address', '2024-06-28 09:18:13', 1),
(23, 'inventory2', '$2y$10$GZQ5jsljpxlfbaAeKsF7YOJ1Nw7K4EmVg3Vfhz37JikiNkqwMKaW6', 'inventory_manager', 'inventory2@bbms.com', '444', 'inventory2 address', '2024-06-28 09:18:49', 0),
(24, 'hospital', '$2y$10$6nM9cVG0d8HUMedHCkn9o.zg73Y./g19MSakMsxUxRtN7tKJINfBW', 'hospital_rep', 'hospital@bbms.com', '5555', 'hospital address', '2024-06-28 09:20:11', 1),
(25, 'hospital2', '$2y$10$gUKmxJBAPJT1rtQFwKhYXOrJZlGgIYACfa7CHNsdTaVe.5upZRO6y', 'hospital_rep', 'hospital2@bbms.com', '555', 'hospital address', '2024-06-28 09:20:46', 0),
(28, 'admin2', '$2y$10$ozs./xnnOeOXMNTbn8n8zuT2SwN/sw5ivPW0l945GqEGik8fpHMZ6', 'admin', 'admin2@bbms.com', '1111', 'admin2 address', '2024-06-28 09:25:19', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `inventory_manager_id` (`inventory_manager_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blood_test_results`
--
ALTER TABLE `blood_test_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blood_units`
--
ALTER TABLE `blood_units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `blood_units` (`unit_id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `blood_units` (`unit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`inventory_manager_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
