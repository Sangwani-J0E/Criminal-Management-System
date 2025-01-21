-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2024 at 06:18 AM
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
-- Database: `cmsdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `case_id` int(11) NOT NULL,
  `case_name` varchar(100) NOT NULL,
  `case_description` text NOT NULL,
  `status` enum('open','in_progress','closed') NOT NULL DEFAULT 'open',
  `investigator_id` int(11) DEFAULT NULL,
  `date_assigned` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crime_records`
--

CREATE TABLE `crime_records` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `district_of_origin` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `identification_type` enum('Driver''s License','National ID') NOT NULL,
  `identification_number` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `crime_name` varchar(100) NOT NULL,
  `severity` enum('Low','Moderate','High','Severe') NOT NULL,
  `time_of_occurrence` datetime NOT NULL,
  `place_of_crime` varchar(100) NOT NULL,
  `victims` text DEFAULT NULL,
  `evidence` text DEFAULT NULL,
  `potential_charge` text DEFAULT NULL,
  `time_served` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitude` float(10,6) DEFAULT NULL,
  `longitude` float(10,6) DEFAULT NULL,
  `case_status` enum('Open','Closed','In Progress') NOT NULL DEFAULT 'Open',
  `assigned_investigator` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crime_records`
--

INSERT INTO `crime_records` (`id`, `full_name`, `gender`, `nationality`, `district_of_origin`, `date_of_birth`, `identification_type`, `identification_number`, `address`, `image_path`, `crime_name`, `severity`, `time_of_occurrence`, `place_of_crime`, `victims`, `evidence`, `potential_charge`, `time_served`, `created_at`, `latitude`, `longitude`, `case_status`, `assigned_investigator`) VALUES
(1, 'Sangwani Mkandawire', 'Male', 'Malawian', 'lilongwe', '1994-03-13', 'Driver\'s License', '163714313871', 'lilongwe', NULL, 'Idk', 'Severe', '2024-11-09 02:42:00', 'Kawale', 'none', 'none', 'idk', '6 years', '2024-11-08 22:40:29', NULL, NULL, 'Open', NULL),
(2, 'Grace Chisambi', 'Female', 'Malawian', 'lilongwe', '2015-07-09', 'Driver\'s License', '213812789347123', 'somewhere', 'uploads/w7w6cmla56y.png', 'Idk', 'Low', '2016-03-09 05:20:00', 'mzuni', 'someone', 'knife', 'murder', '6 years', '2024-11-09 15:18:53', NULL, NULL, 'Open', NULL),
(4, 'Daniel Chikopa', 'Male', 'Malawian', 'blantyre', '2011-03-01', 'Driver\'s License', '13838461312', 'area 13,\r\nlilongwe sector 3', 'uploads/w7w6cmla56y.png', 'Armed Robbery', 'Low', '2024-11-07 01:59:00', 'area 23', 'Denis Kalanga', 'Fire arm, video Footage, witness reports', 'Roberry', 'ndefined', '2024-11-10 10:14:17', NULL, NULL, 'In Progress', 9);

-- --------------------------------------------------------

--
-- Table structure for table `investigator_cases`
--

CREATE TABLE `investigator_cases` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `victim_name` varchar(100) NOT NULL,
  `victim_statement` text NOT NULL,
  `witness_statement` text NOT NULL,
  `crime_report` text NOT NULL,
  `crime_time` datetime NOT NULL,
  `crime_background` text NOT NULL,
  `criminal_intent` text NOT NULL,
  `case_status` enum('Open','In Progress','Closed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investigator_cases`
--

INSERT INTO `investigator_cases` (`id`, `case_id`, `victim_name`, `victim_statement`, `witness_statement`, `crime_report`, `crime_time`, `crime_background`, `criminal_intent`, `case_status`) VALUES
(1, 4, 'I dont even know', 'well', 'will never know', 'reporting', '2024-11-14 14:10:00', 'it went like this', 'robbery', 'Open'),
(2, 4, 'I dont even know', 'well', 'will never know', 'reporting', '2024-11-14 14:10:00', 'it went like this', 'robbery', 'Open');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','investigator') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin_user', '$2y$10$g8WnX7elhYvai1iI3yK8BujtZ0/46Lf3DXP2kAMRYVOQ1kgUnRi/i', 'admin', '2024-11-08 22:11:14'),
(2, 'investigator_user', '$2y$10$0Z/iSPbwX4272OORzj1MheUk0y89iI15aNPWwSJHK4eyKWMQrQA6O', 'investigator', '2024-11-08 22:11:14'),
(7, 'NExt', '$2y$10$3BSKn29rLF52FzrcnesEGuhwR0EYpudFwL8APuNAKw7eqwzrJvC3G', 'admin', '2024-11-09 11:03:22'),
(8, 'admin', '$2y$10$g.z9g1ism9DZ7ILvHNEVyutcLgYcH0yLOcMrZD6yPkrKdOPnhw546', 'admin', '2024-11-09 15:50:57'),
(9, 'user', '$2y$10$/lm4QFJZuGjzMofvM4u8DOBXD57d7T7DgGObdHVsiMXXOI6THFrsG', 'investigator', '2024-11-09 15:51:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `investigator_id` (`investigator_id`);

--
-- Indexes for table `crime_records`
--
ALTER TABLE `crime_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investigator_cases`
--
ALTER TABLE `investigator_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crime_records`
--
ALTER TABLE `crime_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `investigator_cases`
--
ALTER TABLE `investigator_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`investigator_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `investigator_cases`
--
ALTER TABLE `investigator_cases`
  ADD CONSTRAINT `investigator_cases_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `crime_records` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
