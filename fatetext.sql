-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 21, 2021 at 08:48 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fametext`
--

-- --------------------------------------------------------

--
-- Table structure for table `datas`
--

CREATE TABLE `datas` (
  `doid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `typeid` bigint(20) NOT NULL,
  `originid` bigint(20) NOT NULL,
  `titlestr` text NOT NULL,
  `jsonstr` text NOT NULL,
  `domainstr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `datas`
--

INSERT INTO `datas` (`doid`, `userid`, `typeid`, `originid`, `titlestr`, `jsonstr`, `domainstr`) VALUES
(1, 1, 1, 1, 'void', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `hallart`
--

CREATE TABLE `hallart` (
  `artid` bigint(20) NOT NULL,
  `datestr` text NOT NULL,
  `arturl` text NOT NULL,
  `sumstr` text NOT NULL,
  `userid` bigint(20) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `linkid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `sourceid` bigint(20) NOT NULL,
  `targetid` bigint(20) NOT NULL,
  `indexid` bigint(20) NOT NULL
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
-- Table structure for table `toks`
--

CREATE TABLE `toks` (
  `tokid` bigint(20) NOT NULL,
  `tokstr` text NOT NULL,
  `tripidstr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `tripid` bigint(20) NOT NULL,
  `gem1` text NOT NULL,
  `gem2` text NOT NULL,
  `gem3` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `typeid` bigint(20) NOT NULL,
  `namestr` text NOT NULL,
  `metaid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`typeid`, `namestr`, `metaid`) VALUES
(1, 'void', 1),
(2, 'user', 1),
(3, 'book', 1),
(4, 'tok', 1),
(5, 'gem', 1),
(6, 'step', 1),
(7, 'diff', 1),
(8, 'concept', 1),
(9, 'comment', 1),
(10, 'search', 1),
(11, 'result', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` bigint(20) NOT NULL,
  `username` text NOT NULL,
  `hashpass` text NOT NULL,
  `firstdate` int(11) NOT NULL,
  `lastdate` int(11) NOT NULL,
  `chatopen` int(11) NOT NULL DEFAULT '0',
  `textarea` int(11) NOT NULL DEFAULT '0',
  `fatesplash` int(11) NOT NULL DEFAULT '0',
  `agreetos` int(11) NOT NULL DEFAULT '0',
  `searchrows` int(11) NOT NULL DEFAULT '1',
  `searchcols` int(11) NOT NULL DEFAULT '35'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `hashpass`, `firstdate`, `lastdate`, `chatopen`, `textarea`, `fatesplash`, `agreetos`, `searchrows`, `searchcols`) VALUES
(1, 'sys', 'b7c5514b60010cd017d5cb03cf7449560e0e8899', 0, 1611090557, 1, 0, 1, 1, 3, 35);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datas`
--
ALTER TABLE `datas`
  ADD PRIMARY KEY (`doid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `typeid` (`typeid`),
  ADD KEY `originid` (`originid`);

--
-- Indexes for table `hallart`
--
ALTER TABLE `hallart`
  ADD PRIMARY KEY (`artid`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`linkid`),
  ADD KEY `sourceid` (`sourceid`),
  ADD KEY `sourceid_2` (`sourceid`,`indexid`);

--
-- Indexes for table `log1`
--
ALTER TABLE `log1`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `toks`
--
ALTER TABLE `toks`
  ADD PRIMARY KEY (`tokid`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`tripid`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`typeid`),
  ADD UNIQUE KEY `namestr` (`namestr`(7)),
  ADD KEY `metaid` (`metaid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `datas`
--
ALTER TABLE `datas`
  MODIFY `doid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hallart`
--
ALTER TABLE `hallart`
  MODIFY `artid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `linkid` bigint(20) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `tripid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `typeid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
