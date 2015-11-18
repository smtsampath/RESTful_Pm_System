-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Host: 127.11.95.130:3306
-- Generation Time: Nov 18, 2015 at 11:26 PM
-- Server version: 5.5.45
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_pm_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sender_user_id` int(11) NOT NULL,
  `receiver_user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `epoch` int(10) NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_user_id`, `receiver_user_id`, `message`, `epoch`) VALUES
(1, 2, 3, 'Hey what is up?', 1447888538),
(2, 3, 2, 'Not much, how are you doing?', 1447888576);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password_hash` text NOT NULL,
  `api_key` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `first_name`, `last_name`, `password_hash`, `api_key`, `status`, `created_at`) VALUES
(1, 'smtsampath@hotmail.com', 'Thushara', 'Sathkumara', '$2a$10$b82f4f796fa013bc1cba8eneJbPREDb9rEHpkZ/oUx4D5NB4yaPRW', 'b60a2fc8c0a50221855f2abbf0aadace', 1, '2015-11-19 01:47:30'),
(2, 'tcolligan@apppartner.com', 'Thomas', 'Colligan', '$2a$10$dbb5cc93e6114acfe7337uU5/VToaSHpLysWsPuCgq1FpXGgWCkLC', 'fd15b487f194bee4f552975ee59fd367', 1, '2015-11-19 01:49:37'),
(3, 'jgreen@apppartner.com', 'Jake', 'Green', '$2a$10$5a3548fd299f381160870ebb71dyW4qRx6HIzh9wgm91jAS6giRBq', '1a5dfd07a5acd60ebd04d8065a6de8fa', 1, '2015-11-19 01:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_messages`
--

CREATE TABLE IF NOT EXISTS `user_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`user_id`,`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_messages`
--

INSERT INTO `user_messages` (`id`, `user_id`, `message_id`) VALUES
(1, 2, 1),
(2, 3, 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
