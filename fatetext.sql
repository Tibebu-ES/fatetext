-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 26, 2021 at 10:20 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fatetext`
--

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
  `datapath` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chests`
--

CREATE TABLE `chests` (
  `chestid` bigint(20) NOT NULL,
  `datastr` text NOT NULL,
  `previd` bigint(20) NOT NULL,
  `nextid` bigint(20) NOT NULL,
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
  `datecreated` int(11) NOT NULL
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

--
-- Dumping data for table `hallart`
--

INSERT INTO `hallart` (`artid`, `datestr`, `arturl`, `sumstr`, `userid`) VALUES
(1, '01_05_21', 'http://www.gutenberg.org/ebooks/228', 'Aeneid.txt: The Aeneid by Virgil', 1),
(2, '01_06_21', 'http://www.gutenberg.org/ebooks/8438', 'Ethics.txt: The Ethics of Aristotle by Aristotle', 1),
(3, '01_07_21', 'http://www.gutenberg.org/ebooks/14020', 'Horace.txt: The Works of Horace by Horace', 1),
(4, '01_08_21', 'http://www.gutenberg.org/ebooks/6130', 'Iliad.txt: The Iliad by Homer', 1),
(5, '01_09_21', 'http://www.gutenberg.org/ebooks/10', 'KJBible.txt: The King James Version of the Bible', 1),
(6, '01_10_21', 'http://www.gutenberg.org/ebooks/2680', 'Marcus.txt: Meditations by Emperor of Rome Marcus Aurelius', 1),
(7, '01_11_21', 'http://www.gutenberg.org/ebooks/1727', 'Odyssey.txt: The Odyssey by Homer', 1),
(8, '01_12_21', 'http://www.gutenberg.org/ebooks/6762', 'Politics.txt: Politics: A Treatise on Government by Aristotle', 1),
(9, '01_13_21', 'http://www.gutenberg.org/ebooks/1497', 'Republic.txt: The Republic by Plato', 1),
(10, '01_14_21', 'http://www.gutenberg.org/ebooks/100', 'TheBard.txt: The Complete Works of William Shakespeare', 1);

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

--
-- Dumping data for table `log1`
--

INSERT INTO `log1` (`logid`, `nowtime`, `ipaddr`, `webagent`, `pagename`, `refpage`, `hostname`, `elapsed`, `userid`) VALUES
(1, 1611699608, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', '/fametext/index.php/hall/', 'http://localhost:8888/fametext/index.php?page=home&cmd=silentlogout&FATESID=n9df74l0qv0g4ql8ch87ql796e', '', 0.0065879821777344, 0),
(2, 1611699612, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', '/fametext/index.php/date/01_05_21', 'http://localhost:8888/fametext/index.php?page=hall&FATESID=n9df74l0qv0g4ql8ch87ql796e', '', 0.0055811405181885, 0);

-- --------------------------------------------------------

--
-- Table structure for table `steps`
--

CREATE TABLE `steps` (
  `gemid` int(11) NOT NULL,
  `stepstr` int(11) NOT NULL,
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
  `chestidstr` text NOT NULL
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
  `searchrows` int(11) NOT NULL,
  `searchcols` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `hashpass`, `flagsint`, `lastgem`, `datecreated`, `lastlogin`, `searchrows`, `searchcols`) VALUES
(1, 'demo', 'b7c5514b60010cd017d5cb03cf7449560e0e8899', 15, 0, 1611699056, 1611699056, 3, 60);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bookid`);

--
-- Indexes for table `chests`
--
ALTER TABLE `chests`
  ADD PRIMARY KEY (`chestid`);

--
-- Indexes for table `gems`
--
ALTER TABLE `gems`
  ADD PRIMARY KEY (`gemid`);

--
-- Indexes for table `hallart`
--
ALTER TABLE `hallart`
  ADD PRIMARY KEY (`artid`);

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
  ADD UNIQUE KEY `tokstr` (`tokstr`(16));

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hallart`
--
ALTER TABLE `hallart`
  MODIFY `artid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `log1`
--
ALTER TABLE `log1`
  MODIFY `logid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
