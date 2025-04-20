-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 25, 2025 at 06:59 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: metrox
--

-- --------------------------------------------------------

--
-- Table structure for table commuter
--

CREATE TABLE commuter (
  commuterID int(11) NOT NULL,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(255) NOT NULL,
  latitude decimal(9,6) DEFAULT NULL,
  longitude decimal(9,6) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL,
profile_pic VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table journey
--

CREATE TABLE journey (
  journeyNum int(11) NOT NULL,
  startStation int(11) NOT NULL,
  endStation int(11) NOT NULL,
  duration int(11) NOT NULL,
  status enum('on-time','delayed','cancelled') DEFAULT 'on-time',
  ticketID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table journeystation
--

CREATE TABLE journeystation (
  journeyNum int(11) NOT NULL,
  stationID int(11) NOT NULL,
  stopOrder int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table metrocabin
--

CREATE TABLE metrocabin (
  metroCabinID int(11) NOT NULL,
  capacity int(11) NOT NULL,
  speed decimal(5,2) DEFAULT NULL,
  gateType varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table station
--

CREATE TABLE station (
  stationID int(11) NOT NULL,
  name varchar(100) NOT NULL,
  latitude decimal(9,6) NOT NULL,
  longitude decimal(9,6) NOT NULL,
  street varchar(100) DEFAULT NULL,
  neighborhood varchar(100) DEFAULT NULL,
  status enum('open','closed') DEFAULT 'open',
  metroStatus ENUM('On Time', 'Delayed', 'Cancelled') DEFAULT 'On Time'

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO station (stationID, name, latitude, longitude, street, neighborhood, status, metroStatus) VALUES
(1, 'King Fahad Station', 24.7136, 46.6753, 'King Fahad Rd', 'Olaya', 'open', 'On Time'),
(2, 'Riyadh Season Station', 24.7150, 46.6900, 'Prince Turki St', 'Hittin', 'open', 'Delayed'),
(3, 'KAFD Station', 24.7743, 46.6336, 'King Salman Rd', 'KAFD', 'open', 'Cancelled'),
(4, 'Central Station', 24.6325, 46.7160, 'King Abdulaziz Rd', 'Al-Malaz', 'closed', 'On Time'),
(5, 'King Abdullah Financial District', 24.7895, 46.6364, 'KAFD Blvd', 'Al Aqeeq', 'open', 'On Time'),
(6, 'Al Malaz Station', 24.6580, 46.7321, 'Al Ihsa St', 'Al-Malaz', 'open', 'Delayed'),
(7, 'Olaya South Station', 24.6987, 46.6765, 'Tahlia St', 'Olaya', 'open', 'On Time'),
(8, 'Hittin North Station', 24.7288, 46.6690, 'Imam Saud St', 'Hittin', 'open', 'On Time'),
(9, 'Al Aqeeq North', 24.8011, 46.6439, 'Anas Bin Malik Rd', 'Al Aqeeq', 'open', 'Delayed');


-- --------------------------------------------------------





-- --------------------------------------------------------

--
-- Table structure for table stationmetrocabin
--

CREATE TABLE stationmetrocabin (
  stationID int(11) NOT NULL,
  metroCabinID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table ticket
--

CREATE TABLE ticket (
  ticketID int(11) NOT NULL,
  price decimal(10,2) NOT NULL,
  ticketType enum('Family','Individual') NOT NULL,
  NOofMember int(11) DEFAULT NULL,
  validityPeriod enum('one-time','daily','weekly') DEFAULT 'one-time',
  commuterID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table commuter
--
ALTER TABLE commuter
  ADD PRIMARY KEY (commuterID),
  ADD UNIQUE KEY email (email);

--
-- Indexes for table journey
--
ALTER TABLE journey
  ADD PRIMARY KEY (journeyNum),
  ADD KEY startStation (startStation),
  ADD KEY endStation (endStation),
  ADD KEY ticketID (ticketID);

--
-- Indexes for table journeystation
--
ALTER TABLE journeystation
  ADD PRIMARY KEY (journeyNum,stationID),
  ADD KEY stationID (stationID);

--
-- Indexes for table metrocabin
--
ALTER TABLE metrocabin
  ADD PRIMARY KEY (metroCabinID);

--
-- Indexes for table station
--
ALTER TABLE station
  ADD PRIMARY KEY (stationID);

--
-- Indexes for table stationmetrocabin
--
ALTER TABLE stationmetrocabin
  ADD PRIMARY KEY (stationID,metroCabinID),
  ADD KEY metroCabinID (metroCabinID);

--
-- Indexes for table ticket
--
ALTER TABLE ticket
  ADD PRIMARY KEY (ticketID),
  ADD KEY commuterID (commuterID);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table commuter
--
ALTER TABLE commuter
  MODIFY commuterID int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table journey
--
ALTER TABLE journey
  MODIFY journeyNum int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table metrocabin
--
ALTER TABLE metrocabin
  MODIFY metroCabinID int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table station
--
ALTER TABLE station
  MODIFY stationID int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table ticket
--
ALTER TABLE ticket
  MODIFY ticketID int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table journey
--
ALTER TABLE journey
  ADD CONSTRAINT journey_ibfk_1 FOREIGN KEY (startStation) REFERENCES station (stationID),
  ADD CONSTRAINT journey_ibfk_2 FOREIGN KEY (endStation) REFERENCES station (stationID),
  ADD CONSTRAINT journey_ibfk_3 FOREIGN KEY (ticketID) REFERENCES ticket (ticketID) ON DELETE SET NULL;

--
-- Constraints for table journeystation
--
ALTER TABLE journeystation
  ADD CONSTRAINT journeystation_ibfk_1 FOREIGN KEY (journeyNum) REFERENCES journey (journeyNum) ON DELETE CASCADE,
  ADD CONSTRAINT journeystation_ibfk_2 FOREIGN KEY (stationID) REFERENCES station (stationID);

--
-- Constraints for table stationmetrocabin
--
ALTER TABLE stationmetrocabin
  ADD CONSTRAINT stationmetrocabin_ibfk_1 FOREIGN KEY (stationID) REFERENCES station (stationID) ON DELETE CASCADE,
  ADD CONSTRAINT stationmetrocabin_ibfk_2 FOREIGN KEY (metroCabinID) REFERENCES metrocabin (metroCabinID) ON DELETE CASCADE;

--
-- Constraints for table ticket
--
ALTER TABLE ticket
  ADD CONSTRAINT ticket_ibfk_1 FOREIGN KEY (commuterID) REFERENCES commuter (commuterID) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE IF NOT EXISTS alerts (
  alertID INT NOT NULL AUTO_INCREMENT,
  stationName VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  alertType VARCHAR(50) NOT NULL,
  createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (alertID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
