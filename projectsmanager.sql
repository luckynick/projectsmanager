-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2017 at 12:34 AM
-- Server version: 5.5.25
-- PHP Version: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `projectsmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `participations`
--

CREATE TABLE IF NOT EXISTS `participations` (
  `part_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `participant_id` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `mark` tinyint(3) unsigned DEFAULT NULL,
  `is_checked` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`part_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- Dumping data for table `participations`
--

INSERT INTO `participations` (`part_id`, `participant_id`, `project_id`, `mark`, `is_checked`) VALUES
(60, 38, 24, 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pub_date` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '\\anon_project.jpg',
  `declaration` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost` int(20) NOT NULL,
  `link` text COLLATE utf8_unicode_ci,
  `customer` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `length` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `pub_date`, `title`, `image`, `declaration`, `text`, `cost`, `link`, `customer`, `description`, `length`) VALUES
(24, '2017-01-04 22:17:47', 'First project', '\\anon_project.jpg', NULL, NULL, 7000, '', 'ICO', 'Blabla', 600);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `registration_date` datetime NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT '\\anon_user.jpg',
  `description` text,
  `keywords` varchar(255) DEFAULT '',
  `is_admin` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `first_name`, `last_name`, `registration_date`, `image`, `description`, `keywords`, `is_admin`) VALUES
(38, 'admin', 'aaf602eff6b3f4b81f5afb87fb78095a', 'admin@admin.admin', 'admin', 'admin', '2017-01-04 22:14:29', '\\user_38\\avatar.jpg', 'admin', '''individualist'' ', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
