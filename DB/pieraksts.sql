-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 16, 2018 at 10:45 PM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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

DROP TABLE IF EXISTS `beam_sizes`;
CREATE TABLE IF NOT EXISTS `beam_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size` decimal(12,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `person_id` char(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity_rate` decimal(12,2) DEFAULT NULL,
  `hour_rate` decimal(12,2) DEFAULT NULL,
  `working_from` date DEFAULT NULL,
  `working_to` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_id` (`person_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_positions`
--

DROP TABLE IF EXISTS `employees_positions`;
CREATE TABLE IF NOT EXISTS `employees_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `position_id` (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_sawmill_productions`
--

DROP TABLE IF EXISTS `employees_sawmill_productions`;
CREATE TABLE IF NOT EXISTS `employees_sawmill_productions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `sawmill_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `sawmill_id` (`sawmill_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_sorted_productions`
--

DROP TABLE IF EXISTS `employees_sorted_productions`;
CREATE TABLE IF NOT EXISTS `employees_sorted_productions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `sorted_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `sorted_id` (`sorted_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
CREATE TABLE IF NOT EXISTS `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sawmill_maintenance`
--

DROP TABLE IF EXISTS `sawmill_maintenance`;
CREATE TABLE IF NOT EXISTS `sawmill_maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sawmill_production_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sawmill_production_id` (`sawmill_production_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sawmill_productions`
--

DROP TABLE IF EXISTS `sawmill_productions`;
CREATE TABLE IF NOT EXISTS `sawmill_productions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL,
  `time_from` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_to` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice` int(11) NOT NULL,
  `beam_count` int(11) NOT NULL,
  `beam_capacity` decimal(12,3) NOT NULL,
  `lumber_count` int(11) NOT NULL,
  `lumber_capacity` decimal(12,3) NOT NULL,
  `percentage` decimal(12,2) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `beam_size_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice` (`invoice`),
  KEY `beam_size_id` (`beam_size_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sorted_productions`
--

DROP TABLE IF EXISTS `sorted_productions`;
CREATE TABLE IF NOT EXISTS `sorted_productions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `thickness` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `capacity` decimal(12,3) NOT NULL,
  `capacity_piece` decimal(7,5) NOT NULL,
  `sorting_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sorting_id` (`sorting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sorting_productions`
--

DROP TABLE IF EXISTS `sorting_productions`;
CREATE TABLE IF NOT EXISTS `sorting_productions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL,
  `time_from` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_to` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice` int(11) NOT NULL,
  `thickness` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `capacity` decimal(12,3) NOT NULL,
  `defect_count` int(11) DEFAULT NULL,
  `reserved` bit(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `times`
--

DROP TABLE IF EXISTS `times`;
CREATE TABLE IF NOT EXISTS `times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL,
  `invoice` int(11) DEFAULT NULL,
  `vacation` char(2) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `sick_leave` char(2) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `nonattendace` char(2) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `pregnancy` char(2) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `employee_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'p',
  `active` bit(1) NOT NULL DEFAULT b'1',
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `active`, `created`) VALUES
(1, 'admin', '$2y$10$l5ddIbCYL6DMmMv1IGTTSuYFg6GRA0RO1oEcnN2R.FSqklIGyxAIS', 'a', b'1', '2018-02-13');

-- --------------------------------------------------------

--
-- Table structure for table `working_times`
--

DROP TABLE IF EXISTS `working_times`;
CREATE TABLE IF NOT EXISTS `working_times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `datetime` timestamp NOT NULL,
  `invoice` int(11) DEFAULT NULL,
  `working_hours` int(11) DEFAULT NULL,
  `overtime_hours` int(11) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
