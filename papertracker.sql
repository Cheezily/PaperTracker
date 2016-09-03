-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2016 at 05:34 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `papertracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messageID` int(12) NOT NULL,
  `fromUsername` varchar(30) NOT NULL,
  `toUsername` varchar(30) NOT NULL,
  `whenSent` datetime NOT NULL,
  `message` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `newMessage` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageID`, `fromUsername`, `toUsername`, `whenSent`, `message`, `title`, `newMessage`) VALUES
(105, 'admin', 'user', '2016-08-25 10:14:06', 'message', 'General Question', 1);

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `paperID` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `reviewername` varchar(30) DEFAULT NULL,
  `recommendation` enum('accept','minor','major','reject') DEFAULT NULL,
  `draftFilename` varchar(50) NOT NULL,
  `firstReviewFilename` varchar(50) DEFAULT NULL,
  `revisedFilename` varchar(50) DEFAULT NULL,
  `finalReviewFilename` varchar(50) DEFAULT NULL,
  `status` enum('awaiting_assignment','awaiting_review','awaiting_revisions','revisions_submitted','accepted','rejected') NOT NULL DEFAULT 'awaiting_assignment',
  `whenSubmitted` datetime NOT NULL,
  `whenFirstReply` datetime DEFAULT NULL,
  `whenRevised` datetime DEFAULT NULL,
  `whenFinalReply` datetime DEFAULT NULL,
  `whenCompleted` datetime DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `recentlyUpdated` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 'admin', '2016-08-19 00:00:00', '2016-09-03 10:07:59', 'Firstname', 'Lastname', 'admin@email.com', '$2y$10$pEPtxGEQCUWvu6CBfavyIeHxrZnZo.cDaCCkHzqzU7p.uKbESm4tS', 'admin'),
(3, 'reviewer', '2016-08-20 04:31:38', '2016-09-02 09:10:57', 'firstname', 'lastname', 'rev@wmil.com', '$2y$11$cIqYazL6Z3tEc9AR0ZwQyuDpDSFIlOtBqky1FlbzAryUuJDMW6Hmq', 'reviewer'),
(5, 'user', '2016-08-21 04:34:55', '2016-09-03 10:05:15', 'first', 'last', 'email@email.com', '$2y$11$fSL8mjGwt/SSWhJKfFMIwuPOAiIqx4Wo6Hi8mTqyfFoZ2MA3vtD/y', 'author'),
(6, 'newuser', '2016-08-31 01:11:11', '2016-08-30 19:32:22', 'first', 'last', 'email@email.com', '$2y$11$tIcWiltjNU5Xi.SUVcN06.DaFytYq7e/LRMhgU3GoMxnfsNKJd9mO', 'author');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageID`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`paperID`),
  ADD UNIQUE KEY `filename` (`draftFilename`);

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messageID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `paperID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
