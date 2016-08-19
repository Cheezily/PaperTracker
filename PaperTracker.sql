-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 19, 2016 at 11:23 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `PaperTracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `fromUsername` varchar(30) NOT NULL,
  `toUsername` varchar(30) NOT NULL,
  `whenSent` datetime NOT NULL,
  `message` text NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `paperID` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `reviewerID` int(11) DEFAULT NULL,
  `filename` varchar(50) NOT NULL,
  `status` enum('awaiting_assignment','awaiting_review','awaiting_revisions','accepted','rejected') NOT NULL DEFAULT 'awaiting_assignment',
  `reply_filename` varchar(50) DEFAULT NULL,
  `when_submitted` datetime NOT NULL,
  `when_revised` datetime DEFAULT NULL,
  `when_completed` datetime DEFAULT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `papers`
--

INSERT INTO `papers` (`paperID`, `username`, `reviewerID`, `filename`, `status`, `reply_filename`, `when_submitted`, `when_revised`, `when_completed`, `title`) VALUES
(12, 'user', NULL, 'TEST.docx', 'awaiting_assignment', NULL, '2016-08-19 22:20:43', NULL, NULL, 'This is the first uploaded!');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `account_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `passwordHash` varchar(60) NOT NULL,
  `role` enum('author','reviewer','admin') NOT NULL DEFAULT 'author'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `account_created`, `last_login`, `first_name`, `last_name`, `email`, `passwordHash`, `role`) VALUES
(1, 'admin', '2016-08-19 00:00:00', '2016-08-19 13:48:30', 'Firstname', 'Lastname', 'admin@email.com', '$2y$10$pEPtxGEQCUWvu6CBfavyIeHxrZnZo.cDaCCkHzqzU7p.uKbESm4tS', 'admin'),
(2, 'user', '2016-08-19 20:50:58', '2016-08-19 15:20:30', 'firstname', 'lastname', 'user@email.com', '$2y$11$oQ4PS8cdXbhUbxZ9Gz/rLOApcNFwdGe41IZ0x/1zFJyQWINHnOW5u', 'author');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`paperID`),
  ADD UNIQUE KEY `filename` (`filename`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `paperID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
