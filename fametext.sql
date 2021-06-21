-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 18, 2021 at 02:22 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fatetext`
--

-- --------------------------------------------------------

--
-- Table structure for table `guess_history`
--

CREATE TABLE `guess_history` (
  `guess_id` bigint(20) NOT NULL,
  `guess_sen` text NOT NULL,
  `user_ans` varchar(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  `guess_ans` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE `actions` (
  `actionid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `actionstr` int(11) NOT NULL,
  `datecreated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `bookid` bigint(20) NOT NULL,
  `titlestr` text NOT NULL,
  `authorstr` text NOT NULL,
  `datapath` text NOT NULL,
  `isLoaded` boolean  DEFAULT false,
  `type`    text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `chests`
--

CREATE TABLE `chests` (
  `chestid` bigint(20) NOT NULL,
  `datastr` text NOT NULL,
  `bookid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gems`
--

CREATE TABLE `gems` (
  `gemid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `chestid` bigint(20) NOT NULL,
  `tokid` bigint(20) NOT NULL,
  `stepint` int(11) NOT NULL,
  `datecreated` int(11) NOT NULL,
  `wordcount` int(11) NOT NULL,
  `charcount` int(11) NOT NULL,
  `lastloaded` int(11) NOT NULL,
  `bookguess` bigint(20) NOT NULL DEFAULT '0',
  `authguess` text,
  `ansrows` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hallart`
--

CREATE TABLE `hallart` (
  `artid` bigint(20) NOT NULL,
  `datestr` text NOT NULL,
  `category` text NOT NULL,
  `arturl` text NOT NULL,
  `sumstr` text NOT NULL,
  `userid` bigint(20) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log1`
--

CREATE TABLE `log1` (
  `logid` bigint(20) NOT NULL,
  `nowtime` bigint(11) NOT NULL,
  `ipaddr` varchar(63) NOT NULL,
  `webagent` text NOT NULL,
  `pagename` text NOT NULL,
  `refpage` text NOT NULL,
  `hostname` varchar(127) NOT NULL,
  `elapsed` double NOT NULL,
  `userid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `steps`
--

CREATE TABLE `steps` (
  `gemid` int(11) NOT NULL,
  `stepstr` text NOT NULL,
  `whichint` int(11) NOT NULL,
  `datecreated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `toks`
--

CREATE TABLE `toks` (
  `tokid` bigint(20) NOT NULL,
  `tokstr` text NOT NULL,
  `chestidstr` text NOT NULL,
  `bookid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` bigint(20) NOT NULL,
  `username` text NOT NULL,
  `hashpass` text NOT NULL,
  `flagsint` int(11) NOT NULL,
  `lastgem` bigint(20) NOT NULL,
  `datecreated` int(11) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `lastchange` int(11) NOT NULL,
  `datarows` int(11) NOT NULL DEFAULT '5',
  `storycoins` int(11) NOT NULL,
  `lastcategory` text NOT NULL,
  `lastcustom` text NOT NULL,
  `lastsearch` text NOT NULL,
  `chatindex` int(11) NOT NULL,
  `actionindex` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--



--
-- Indexes for table `chests`
--
ALTER TABLE `chests`
  ADD PRIMARY KEY (`chestid`);

--
-- Indexes for table `gems`
--
ALTER TABLE `gems`
  ADD PRIMARY KEY (`gemid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `log1`
--
ALTER TABLE `log1`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `toks`
--
ALTER TABLE `toks`
  ADD PRIMARY KEY (`tokid`),
  ADD UNIQUE KEY `tokstr` (`tokstr`(32),`bookid`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bookid`);


--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `bookid` bigint(20) NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT for table `chests`
--
ALTER TABLE `chests`
  MODIFY `chestid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gems`
--
ALTER TABLE `gems`
  MODIFY `gemid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log1`
--
ALTER TABLE `log1`
  MODIFY `logid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `toks`
--
ALTER TABLE `toks`
  MODIFY `tokid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guess_history`
--
ALTER TABLE `guess_history`
  MODIFY `guess_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guess_history`
--
ALTER TABLE `guess_history`
  MODIFY `guess_id` bigint(20) NOT NULL AUTO_INCREMENT;

  --
-- Indexes for table `guess_history`
--
ALTER TABLE `guess_history`
  ADD PRIMARY KEY (`guess_id`);