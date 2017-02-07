-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 08, 2017 at 12:19 AM
-- Server version: 5.5.53-0+deb8u1
-- PHP Version: 5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `treasurehuntbot_zelda`
--

-- --------------------------------------------------------

--
-- Table structure for table `identities`
--

CREATE TABLE `identities` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'Internal ID',
  `telegram_id` int(11) NOT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `school_code` char(10) CHARACTER SET utf8 DEFAULT NULL,
  `first_access` datetime NOT NULL,
  `last_access` datetime NOT NULL,
  `is_admin` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` char(8) COLLATE utf8_unicode_ci NOT NULL,
  `target_state` tinyint(4) DEFAULT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `code`, `target_state`, `description`) VALUES
(1, 'BqeyekUv', 10, 'Borgo Mercatale'),
(2, 'Q7zF0q0V', 20, 'Magistero'),
(3, 'IZoJzRgJ', 30, 'Selfie point'),
(4, 'vVVxASDS', 40, 'Mensa Tridente'),
(5, 'GvL7tVCc', 50, 'Teatro La Vela'),
(6, 'd9Ua9NvL', 60, 'Informatica Applicata stand');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(10) UNSIGNED NOT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `identity` int(10) UNSIGNED DEFAULT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reached_locations`
--

CREATE TABLE `reached_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `timestamp` datetime NOT NULL,
  `correct_answer` tinyint(1) NOT NULL DEFAULT '0',
  `answer_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `ISTAT_COM` smallint(6) UNSIGNED NOT NULL,
  `ISTAT_PROV` tinyint(3) UNSIGNED NOT NULL,
  `ISTAT_REG` tinyint(3) UNSIGNED NOT NULL,
  `ISTAT_REG_7C` smallint(7) UNSIGNED NOT NULL,
  `codice_scuola` char(10) NOT NULL,
  `denominazione` varchar(255) NOT NULL,
  `des_tipo_scuola` varchar(255) NOT NULL,
  `indirizzo` varchar(255) DEFAULT NULL,
  `comune` varchar(255) DEFAULT NULL,
  `cap` char(5) DEFAULT NULL,
  `telefono` varchar(16) DEFAULT NULL,
  `fax` varchar(16) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pec` varchar(255) DEFAULT NULL,
  `sito_web` varchar(255) DEFAULT NULL,
  `cod_istituto_principale` char(10) DEFAULT NULL,
  `statale` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `type` smallint(5) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `message_id` int(10) UNSIGNED NOT NULL,
  `counter` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `identities`
--
ALTER TABLE `identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_id` (`telegram_id`),
  ADD KEY `school_code` (`school_code`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `identity` (`identity`),
  ADD KEY `details_index` (`level`,`tag`) USING BTREE;

--
-- Indexes for table `reached_locations`
--
ALTER TABLE `reached_locations`
  ADD PRIMARY KEY (`id`,`location_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`codice_scuola`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`type`,`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `identities`
--
ALTER TABLE `identities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Internal ID', AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `identities`
--
ALTER TABLE `identities`
  ADD CONSTRAINT `school_constraint` FOREIGN KEY (`school_code`) REFERENCES `schools` (`codice_scuola`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `identity_constraint` FOREIGN KEY (`identity`) REFERENCES `identities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `reached_locations`
--
ALTER TABLE `reached_locations`
  ADD CONSTRAINT `location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `user_id` FOREIGN KEY (`id`) REFERENCES `identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
