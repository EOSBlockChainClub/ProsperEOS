-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2019 at 12:06 PM
-- Server version: 5.7.23
-- PHP Version: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dish2019`
--

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `minimum_contribution` decimal(10,8) NOT NULL,
  `eos_public_address` varchar(255) DEFAULT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `minimum_contribution`, `eos_public_address`, `created_datetime`) VALUES
(1, 'Iphone Case Filipino Design', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '15.00000000', 'EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn', '2019-03-30 00:00:00'),
(2, 'Pebble Placemat', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '5.00000000', 'EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn', '2019-03-30 00:00:00'),
(3, 'Jar Garden Mini Terrarium', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '5.00000000', 'michaelgalon', '2019-03-30 00:00:00'),
(4, 'Bike Wheel Clock', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '5.00000000', 'EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn', '2019-03-30 00:00:00'),
(5, 'Stainless Capiz Light', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '5.00000000', 'thegigamike1', '2019-03-30 00:00:00'),
(8, 'Rattan Backpack', 'This is a test', '10.00000000', 'EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn', '2019-03-30 14:27:00');

-- --------------------------------------------------------

--
-- Table structure for table `project_contributor`
--

CREATE TABLE `project_contributor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `eos_public_address` varchar(255) DEFAULT NULL,
  `amount` decimal(10,8) NOT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_spending_request`
--

CREATE TABLE `project_spending_request` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `description` text,
  `amount` decimal(10,8) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_finalized` enum('N','Y') NOT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_spending_request_vote`
--

CREATE TABLE `project_spending_request_vote` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_spending_request_id` bigint(20) UNSIGNED NOT NULL,
  `eos_public_address` varchar(255) DEFAULT NULL,
  `is_approved` enum('N','Y') NOT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `eos_public_address` varchar(255) DEFAULT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `description`, `eos_public_address`, `created_datetime`) VALUES
(1, 'Mik Galon\'s Rattan', 'We sell high quality rattan.', 'EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn', '2019-03-30 21:35:58'),
(2, 'Amah\'s Capiz', 'We sell high quality capiz.', 'amahcapiz11', '2019-03-30 21:35:58'),
(3, 'Zeev Galon\'s Pebbles', 'We sell high quality pebbles.', 'thegigamike1', '2019-03-30 21:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('admin','supplier','member') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `salt` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` enum('N','Y') NOT NULL,
  `created_datetime` datetime NOT NULL,
  `created_user_id` bigint(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role`, `email`, `password`, `first_name`, `last_name`, `salt`, `active`, `created_datetime`, `created_user_id`) VALUES
(1, 'admin', 'admin@gigamike.net', 'e698b4a0b08532cdff8443a4dd615588', 'Mik', 'Galon', 'kJ(26<$y>u01=1Su6V[t,GuDxMS=TCcAmkR%(V}FL/Wh?+_T`;', 'Y', '2014-12-30 11:40:44', 1),
(10, 'member', 'member@gigamike.net', 'f4198c36bc89c8809a82f7b53c3bb8cb', 'Michael', 'Galon', '\\TF7K~7z?F3G2F1>\'MkRn)mY+W4=YoK6DpLoobj/*}V<DfYK4E', 'Y', '2018-10-17 08:08:10', 0),
(11, 'supplier', 'supplier@gigamike.net', '3dc63898f07898c047cf21ab021eb0e1', 'Michael', 'Galon', 'T-PeBza@]pLB&N3\\!R54~sZc`JhAzuPP#\"6E{wdYh2{m_/K```', 'Y', '2018-11-15 11:24:28', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `created_datetime` (`created_datetime`);

--
-- Indexes for table `project_contributor`
--
ALTER TABLE `project_contributor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_datetime` (`created_datetime`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project_spending_request`
--
ALTER TABLE `project_spending_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_datetime` (`created_datetime`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `project_spending_request_vote`
--
ALTER TABLE `project_spending_request_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_datetime` (`created_datetime`),
  ADD KEY `project_spending_request_id` (`project_spending_request_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `created_datetime` (`created_datetime`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_contributor`
--
ALTER TABLE `project_contributor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_spending_request`
--
ALTER TABLE `project_spending_request`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_spending_request_vote`
--
ALTER TABLE `project_spending_request_vote`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
