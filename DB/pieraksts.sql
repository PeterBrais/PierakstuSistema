-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 18, 2018 at 08:45 AM
-- Server version: 5.5.56-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pieraksts`
--

-- --------------------------------------------------------

--
-- Table structure for table `beam_sizes`
--

CREATE TABLE IF NOT EXISTS `beam_sizes` (
  `id` int(11) NOT NULL,
  `size` decimal(12,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `person_id` char(12) NOT NULL,
  `place` varchar(12) NOT NULL,
  `shift` char(2) DEFAULT NULL,
  `capacity_rate` decimal(12,2) DEFAULT NULL,
  `hour_rate` decimal(12,2) DEFAULT NULL,
  `working_from` date DEFAULT NULL,
  `working_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees_positions`
--

CREATE TABLE IF NOT EXISTS `employees_positions` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees_sawmill_productions`
--

CREATE TABLE IF NOT EXISTS `employees_sawmill_productions` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `sawmill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees_sorted_productions`
--

CREATE TABLE IF NOT EXISTS `employees_sorted_productions` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `sorted_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE IF NOT EXISTS `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sawmill_maintenance`
--

CREATE TABLE IF NOT EXISTS `sawmill_maintenance` (
  `id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `sawmill_production_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sawmill_productions`
--

CREATE TABLE IF NOT EXISTS `sawmill_productions` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_from` varchar(10) NOT NULL,
  `time_to` varchar(10) NOT NULL,
  `invoice` int(11) NOT NULL,
  `beam_count` int(11) NOT NULL,
  `beam_capacity` decimal(12,3) NOT NULL,
  `lumber_count` int(11) NOT NULL,
  `lumber_capacity` decimal(12,3) NOT NULL,
  `percentage` decimal(12,2) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `beam_size_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sorted_productions`
--

CREATE TABLE IF NOT EXISTS `sorted_productions` (
  `id` int(11) NOT NULL,
  `type` char(2) NOT NULL,
  `count` int(11) NOT NULL,
  `thickness` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `capacity` decimal(12,3) NOT NULL,
  `capacity_piece` decimal(7,5) NOT NULL,
  `sorting_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sorting_productions`
--

CREATE TABLE IF NOT EXISTS `sorting_productions` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_from` varchar(10) NOT NULL,
  `time_to` varchar(10) NOT NULL,
  `invoice` int(11) NOT NULL,
  `thickness` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `capacity` decimal(12,3) NOT NULL,
  `defect_count` int(11) DEFAULT NULL,
  `reserved` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `times`
--

CREATE TABLE IF NOT EXISTS `times` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `invoice` int(11) DEFAULT NULL,
  `vacation` char(2) DEFAULT '0',
  `sick_leave` char(2) DEFAULT '0',
  `nonattendace` char(2) DEFAULT '0',
  `pregnancy` char(2) DEFAULT '0',
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `role` char(2) NOT NULL DEFAULT 'p',
  `active` bit(1) NOT NULL DEFAULT b'1',
  `created` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `active`, `created`) VALUES
(1, 'admin', '$2y$10$nbKB8n2NzFsQ65DOAiSlLevryFOxLCP1NK1HhaPtBUw1we2ueYSVq', 'a', b'1', '2018-02-13'),
(2, 'rmuser01', '$2y$10$wdfupNyLkaGTG476X4eA3.hOjMlkRUGk9yHj3ozD.6kU26UQESEuu', 'a', b'1', '2018-04-18'),
(3, 'rmuser02', '$2y$10$vGMXWZ3nDWQImiMNgk.5te6.D.p0WMZOsSsl34rYQ4/Ujd4PIm4Gy', 'a', b'1', '2018-04-18');

-- --------------------------------------------------------

--
-- Table structure for table `working_times`
--

CREATE TABLE IF NOT EXISTS `working_times` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `invoice` int(11) DEFAULT NULL,
  `working_hours` int(11) DEFAULT NULL,
  `overtime_hours` int(11) DEFAULT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beam_sizes`
--
ALTER TABLE `beam_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `person_id` (`person_id`);

--
-- Indexes for table `employees_positions`
--
ALTER TABLE `employees_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `employees_sawmill_productions`
--
ALTER TABLE `employees_sawmill_productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `sawmill_id` (`sawmill_id`);

--
-- Indexes for table `employees_sorted_productions`
--
ALTER TABLE `employees_sorted_productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `sorted_id` (`sorted_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sawmill_maintenance`
--
ALTER TABLE `sawmill_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sawmill_production_id` (`sawmill_production_id`);

--
-- Indexes for table `sawmill_productions`
--
ALTER TABLE `sawmill_productions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice` (`invoice`),
  ADD KEY `beam_size_id` (`beam_size_id`);

--
-- Indexes for table `sorted_productions`
--
ALTER TABLE `sorted_productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sorting_id` (`sorting_id`);

--
-- Indexes for table `sorting_productions`
--
ALTER TABLE `sorting_productions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `times`
--
ALTER TABLE `times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `working_times`
--
ALTER TABLE `working_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beam_sizes`
--
ALTER TABLE `beam_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees_positions`
--
ALTER TABLE `employees_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees_sawmill_productions`
--
ALTER TABLE `employees_sawmill_productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees_sorted_productions`
--
ALTER TABLE `employees_sorted_productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sawmill_maintenance`
--
ALTER TABLE `sawmill_maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sawmill_productions`
--
ALTER TABLE `sawmill_productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sorted_productions`
--
ALTER TABLE `sorted_productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sorting_productions`
--
ALTER TABLE `sorting_productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `times`
--
ALTER TABLE `times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `working_times`
--
ALTER TABLE `working_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees_positions`
--
ALTER TABLE `employees_positions`
  ADD CONSTRAINT `employees_positions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `employees_positions_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`);

--
-- Constraints for table `employees_sawmill_productions`
--
ALTER TABLE `employees_sawmill_productions`
  ADD CONSTRAINT `employees_sawmill_productions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `employees_sawmill_productions_ibfk_2` FOREIGN KEY (`sawmill_id`) REFERENCES `sawmill_productions` (`id`);

--
-- Constraints for table `employees_sorted_productions`
--
ALTER TABLE `employees_sorted_productions`
  ADD CONSTRAINT `employees_sorted_productions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `employees_sorted_productions_ibfk_2` FOREIGN KEY (`sorted_id`) REFERENCES `sorted_productions` (`id`);

--
-- Constraints for table `sawmill_maintenance`
--
ALTER TABLE `sawmill_maintenance`
  ADD CONSTRAINT `sawmill_maintenance_ibfk_1` FOREIGN KEY (`sawmill_production_id`) REFERENCES `sawmill_productions` (`id`);

--
-- Constraints for table `sawmill_productions`
--
ALTER TABLE `sawmill_productions`
  ADD CONSTRAINT `sawmill_productions_ibfk_1` FOREIGN KEY (`beam_size_id`) REFERENCES `beam_sizes` (`id`);

--
-- Constraints for table `sorted_productions`
--
ALTER TABLE `sorted_productions`
  ADD CONSTRAINT `sorted_productions_ibfk_1` FOREIGN KEY (`sorting_id`) REFERENCES `sorting_productions` (`id`);

--
-- Constraints for table `times`
--
ALTER TABLE `times`
  ADD CONSTRAINT `times_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `working_times`
--
ALTER TABLE `working_times`
  ADD CONSTRAINT `working_times_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
