-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 20 أبريل 2025 الساعة 16:20
-- إصدار الخادم: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `metrox`
--

-- --------------------------------------------------------

--
-- بنية الجدول `alerts`
--

CREATE TABLE `alerts` (
  `alertID` int(11) NOT NULL,
  `stationName` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `alertType` varchar(50) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `arrival_times`
--

CREATE TABLE `arrival_times` (
  `id` int(11) NOT NULL,
  `stationID` int(11) DEFAULT NULL,
  `arrivalTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `arrival_times`
--

INSERT INTO `arrival_times` (`id`, `stationID`, `arrivalTime`) VALUES
(1, 1, '07:00:00'),
(2, 1, '10:00:00'),
(3, 1, '13:00:00'),
(4, 1, '17:00:00'),
(5, 1, '21:00:00'),
(6, 2, '07:15:00'),
(7, 2, '10:15:00'),
(8, 2, '13:15:00'),
(9, 2, '17:15:00'),
(10, 2, '21:15:00'),
(11, 3, '07:30:00'),
(12, 3, '10:30:00'),
(13, 3, '13:30:00'),
(14, 3, '17:30:00'),
(15, 3, '21:30:00'),
(16, 4, '07:45:00'),
(17, 4, '10:45:00'),
(18, 4, '13:45:00'),
(19, 4, '17:45:00'),
(20, 4, '21:45:00'),
(21, 5, '08:00:00'),
(22, 5, '11:00:00'),
(23, 5, '14:00:00'),
(24, 5, '18:00:00'),
(25, 5, '22:00:00'),
(26, 6, '08:15:00'),
(27, 6, '11:15:00'),
(28, 6, '14:15:00'),
(29, 6, '18:15:00'),
(30, 6, '22:15:00'),
(31, 7, '08:30:00'),
(32, 7, '11:30:00'),
(33, 7, '14:30:00'),
(34, 7, '18:30:00'),
(35, 7, '22:30:00'),
(36, 8, '08:45:00'),
(37, 8, '11:45:00'),
(38, 8, '14:45:00'),
(39, 8, '18:45:00'),
(40, 8, '22:45:00'),
(41, 9, '09:00:00'),
(42, 9, '12:00:00'),
(43, 9, '15:00:00'),
(44, 9, '19:00:00'),
(45, 9, '23:00:00');

-- --------------------------------------------------------

--
-- بنية الجدول `commuter`
--

CREATE TABLE `commuter` (
  `commuterID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `journey`
--

CREATE TABLE `journey` (
  `journeyNum` int(11) NOT NULL,
  `startStation` int(11) NOT NULL,
  `endStation` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `status` enum('on-time','delayed','cancelled') DEFAULT 'on-time',
  `ticketID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `journeystation`
--

CREATE TABLE `journeystation` (
  `journeyNum` int(11) NOT NULL,
  `stationID` int(11) NOT NULL,
  `stopOrder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `metrocabin`
--

CREATE TABLE `metrocabin` (
  `metroCabinID` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `speed` decimal(5,2) DEFAULT NULL,
  `gateType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `station`
--

CREATE TABLE `station` (
  `stationID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `street` varchar(100) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `metroStatus` enum('On Time','Delayed','Cancelled') DEFAULT 'On Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `station`
--

INSERT INTO `station` (`stationID`, `name`, `latitude`, `longitude`, `street`, `neighborhood`, `status`, `metroStatus`) VALUES
(1, 'King Fahad Station', '24.713600', '46.675300', 'King Fahad Rd', 'Olaya', 'open', 'On Time'),
(2, 'Riyadh Season Station', '24.715000', '46.690000', 'Prince Turki St', 'Hittin', 'open', 'Delayed'),
(3, 'KAFD Station', '24.774300', '46.633600', 'King Salman Rd', 'KAFD', 'open', 'Cancelled'),
(4, 'Central Station', '24.632500', '46.716000', 'King Abdulaziz Rd', 'Al-Malaz', 'closed', 'On Time'),
(5, 'King Abdullah Financial District', '24.789500', '46.636400', 'KAFD Blvd', 'Al Aqeeq', 'open', 'On Time'),
(6, 'Al Malaz Station', '24.658000', '46.732100', 'Al Ihsa St', 'Al-Malaz', 'open', 'Delayed'),
(7, 'Olaya South Station', '24.698700', '46.676500', 'Tahlia St', 'Olaya', 'open', 'On Time'),
(8, 'Hittin North Station', '24.728800', '46.669000', 'Imam Saud St', 'Hittin', 'open', 'On Time'),
(9, 'Al Aqeeq North', '24.801100', '46.643900', 'Anas Bin Malik Rd', 'Al Aqeeq', 'open', 'Delayed');

-- --------------------------------------------------------

--
-- بنية الجدول `stationmetrocabin`
--

CREATE TABLE `stationmetrocabin` (
  `stationID` int(11) NOT NULL,
  `metroCabinID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `ticket`
--

CREATE TABLE `ticket` (
  `ticketID` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `ticketType` enum('Family','Individual') NOT NULL,
  `NOofMember` int(11) DEFAULT NULL,
  `validityPeriod` enum('one-time','daily','weekly') DEFAULT 'one-time',
  `commuterID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alertID`);

--
-- Indexes for table `arrival_times`
--
ALTER TABLE `arrival_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stationID` (`stationID`);

--
-- Indexes for table `commuter`
--
ALTER TABLE `commuter`
  ADD PRIMARY KEY (`commuterID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `journey`
--
ALTER TABLE `journey`
  ADD PRIMARY KEY (`journeyNum`),
  ADD KEY `startStation` (`startStation`),
  ADD KEY `endStation` (`endStation`),
  ADD KEY `ticketID` (`ticketID`);

--
-- Indexes for table `journeystation`
--
ALTER TABLE `journeystation`
  ADD PRIMARY KEY (`journeyNum`,`stationID`),
  ADD KEY `stationID` (`stationID`);

--
-- Indexes for table `metrocabin`
--
ALTER TABLE `metrocabin`
  ADD PRIMARY KEY (`metroCabinID`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`stationID`);

--
-- Indexes for table `stationmetrocabin`
--
ALTER TABLE `stationmetrocabin`
  ADD PRIMARY KEY (`stationID`,`metroCabinID`),
  ADD KEY `metroCabinID` (`metroCabinID`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticketID`),
  ADD KEY `commuterID` (`commuterID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alertID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `arrival_times`
--
ALTER TABLE `arrival_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `commuter`
--
ALTER TABLE `commuter`
  MODIFY `commuterID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journey`
--
ALTER TABLE `journey`
  MODIFY `journeyNum` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metrocabin`
--
ALTER TABLE `metrocabin`
  MODIFY `metroCabinID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `station`
--
ALTER TABLE `station`
  MODIFY `stationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `ticketID` int(11) NOT NULL AUTO_INCREMENT;

--
-- قيود الجداول المحفوظة
--

--
-- القيود للجدول `arrival_times`
--
ALTER TABLE `arrival_times`
  ADD CONSTRAINT `arrival_times_ibfk_1` FOREIGN KEY (`stationID`) REFERENCES `station` (`stationID`) ON DELETE CASCADE;

--
-- القيود للجدول `journey`
--
ALTER TABLE `journey`
  ADD CONSTRAINT `journey_ibfk_1` FOREIGN KEY (`startStation`) REFERENCES `station` (`stationID`),
  ADD CONSTRAINT `journey_ibfk_2` FOREIGN KEY (`endStation`) REFERENCES `station` (`stationID`),
  ADD CONSTRAINT `journey_ibfk_3` FOREIGN KEY (`ticketID`) REFERENCES `ticket` (`ticketID`) ON DELETE SET NULL;

--
-- القيود للجدول `journeystation`
--
ALTER TABLE `journeystation`
  ADD CONSTRAINT `journeystation_ibfk_1` FOREIGN KEY (`journeyNum`) REFERENCES `journey` (`journeyNum`) ON DELETE CASCADE,
  ADD CONSTRAINT `journeystation_ibfk_2` FOREIGN KEY (`stationID`) REFERENCES `station` (`stationID`);

--
-- القيود للجدول `stationmetrocabin`
--
ALTER TABLE `stationmetrocabin`
  ADD CONSTRAINT `stationmetrocabin_ibfk_1` FOREIGN KEY (`stationID`) REFERENCES `station` (`stationID`) ON DELETE CASCADE,
  ADD CONSTRAINT `stationmetrocabin_ibfk_2` FOREIGN KEY (`metroCabinID`) REFERENCES `metrocabin` (`metroCabinID`) ON DELETE CASCADE;

--
-- القيود للجدول `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`commuterID`) REFERENCES `commuter` (`commuterID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
