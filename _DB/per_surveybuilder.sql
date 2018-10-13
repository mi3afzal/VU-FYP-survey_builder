-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2013 at 08:21 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `per_surveybuilder`
--

-- --------------------------------------------------------

--
-- Table structure for table `sb_modules`
--

CREATE TABLE IF NOT EXISTS `sb_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sb_modules`
--

INSERT INTO `sb_modules` (`id`, `page`, `title`) VALUES
(1, 'users', 'Users'),
(2, 'survey_bulder', 'Survey Bulder'),
(3, 'permissions', 'Permissions');

-- --------------------------------------------------------

--
-- Table structure for table `sb_modules_actions`
--

CREATE TABLE IF NOT EXISTS `sb_modules_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sb_modules_actions`
--

INSERT INTO `sb_modules_actions` (`id`, `module_id`, `action`, `title`, `description`) VALUES
(1, 1, 'add_new_user', 'Add New User', 'This option allow user to add a new user with any role'),
(2, 1, 'users', 'Users List', 'This option allow user to see list of all users'),
(3, 1, 'edit_profile', 'Edit profiles', 'This option allow user to edit profile of other users'),
(4, 2, 'build_new_survey', 'Build New Survey', 'This option allow user to build new survey'),
(5, 2, 'survey_bulder', 'Survey List', 'This option allow user to see surveys of other users'),
(6, 2, 'edit_survey', 'Edit Survey', 'This option allow user to edit old survey'),
(7, 3, 'new_user_type', 'Add New User Role', 'This option allow user to add new user type or role');

-- --------------------------------------------------------

--
-- Table structure for table `sb_modules_actions_to_user_type`
--

CREATE TABLE IF NOT EXISTS `sb_modules_actions_to_user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_action_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `sb_modules_actions_to_user_type`
--

INSERT INTO `sb_modules_actions_to_user_type` (`id`, `module_action_id`, `user_type_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 4, 3),
(9, 5, 3),
(10, 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sb_modules_to_user_type`
--

CREATE TABLE IF NOT EXISTS `sb_modules_to_user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sb_modules_to_user_type`
--

INSERT INTO `sb_modules_to_user_type` (`id`, `module_id`, `user_type_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sb_status_list`
--

CREATE TABLE IF NOT EXISTS `sb_status_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sb_status_list`
--

INSERT INTO `sb_status_list` (`id`, `status`, `title`) VALUES
(1, 'Active', 'Activate'),
(2, 'Disabled', 'Disable'),
(3, 'Deleted', 'Delete'),
(4, 'Banned', 'Ban'),
(5, 'Restricted', 'Restrict'),
(6, 'Not Activated', 'Not Active'),
(7, 'Password requested', 'Password request');

-- --------------------------------------------------------

--
-- Table structure for table `sb_survey`
--

CREATE TABLE IF NOT EXISTS `sb_survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `form_structure` longtext NOT NULL,
  `status_list_id` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `expired_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sb_survey_responses`
--

CREATE TABLE IF NOT EXISTS `sb_survey_responses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `response` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sb_users`
--

CREATE TABLE IF NOT EXISTS `sb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL DEFAULT 'Male',
  `verification_code` varchar(100) NOT NULL,
  `user_types_id` int(11) NOT NULL,
  `status_list_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status_list_id` (`status_list_id`),
  KEY `user_types_id` (`user_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sb_users`
--

INSERT INTO `sb_users` (`id`, `first_name`, `last_name`, `email`, `password`, `gender`, `verification_code`, `user_types_id`, `status_list_id`, `created_at`) VALUES
(1, 'Tassawar', 'Hussain', 'bc080200973@vu.edu.pk', 'e10adc3949ba59abbe56e057f20f883e', 'Male', '', 1, 1, '2013-09-04 00:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `sb_user_types`
--

CREATE TABLE IF NOT EXISTS `sb_user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `status_list_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status_list_id` (`status_list_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sb_user_types`
--

INSERT INTO `sb_user_types` (`id`, `type`, `status_list_id`) VALUES
(1, 'Supper Admin', 1),
(2, 'Admin', 2),
(3, 'Surveyor', 1),
(4, 'Customer', 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sb_users`
--
ALTER TABLE `sb_users`
  ADD CONSTRAINT `sb_users_ibfk_1` FOREIGN KEY (`status_list_id`) REFERENCES `sb_status_list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sb_users_ibfk_2` FOREIGN KEY (`user_types_id`) REFERENCES `sb_user_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sb_user_types`
--
ALTER TABLE `sb_user_types`
  ADD CONSTRAINT `sb_user_types_ibfk_1` FOREIGN KEY (`status_list_id`) REFERENCES `sb_status_list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
