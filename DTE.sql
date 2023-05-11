-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 11, 2023 at 09:11 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dte`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `accountID` int NOT NULL AUTO_INCREMENT,
  `personID` int DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT 'Active',
  `level` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT 'Customer',
  `User name` varchar(25) NOT NULL,
  PRIMARY KEY (`accountID`),
  KEY `personID` (`personID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `aircraft`
--

DROP TABLE IF EXISTS `aircraft`;
CREATE TABLE IF NOT EXISTS `aircraft` (
  `aircraftID` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `manufacturingYear` int DEFAULT NULL,
  PRIMARY KEY (`aircraftID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `airport`
--

DROP TABLE IF EXISTS `airport`;
CREATE TABLE IF NOT EXISTS `airport` (
  `airportID` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`airportID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `CustomerID` int NOT NULL AUTO_INCREMENT,
  `accountID` int DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `Age` int DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `PostalCode` varchar(20) DEFAULT NULL,
  `SpecialNeeds` tinyint(1) DEFAULT NULL,
  `Points` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`CustomerID`),
  KEY `accountID` (`accountID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

DROP TABLE IF EXISTS `flight`;
CREATE TABLE IF NOT EXISTS `flight` (
  `flightID` int NOT NULL AUTO_INCREMENT,
  `departureAirportID` int DEFAULT NULL,
  `arrivalAirportID` int DEFAULT NULL,
  `departureTime` datetime DEFAULT NULL,
  `returnTime` datetime DEFAULT NULL,
  `gate` varchar(20) DEFAULT NULL,
  `status` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT 'Active',
  `aircraftID` int DEFAULT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`flightID`),
  KEY `departureAirportID` (`departureAirportID`),
  KEY `arrivalAirportID` (`arrivalAirportID`),
  KEY `aircraftID` (`aircraftID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `flightreservation`
--

DROP TABLE IF EXISTS `flightreservation`;
CREATE TABLE IF NOT EXISTS `flightreservation` (
  `reservationNumber` int NOT NULL AUTO_INCREMENT,
  `meals` varchar(255) DEFAULT NULL,
  `CustomerID` int DEFAULT NULL,
  `flightID` int DEFAULT NULL,
  `payment Amount` int DEFAULT NULL,
  `reservation` datetime DEFAULT NULL,
  `Seat` varchar(25) NOT NULL,
  `Accomodation` varchar(25) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT 'None',
  PRIMARY KEY (`reservationNumber`),
  KEY `CustomerID` (`CustomerID`),
  KEY `flightID` (`flightID`),
  KEY `paymentID` (`payment Amount`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `paymentID` int NOT NULL AUTO_INCREMENT,
  `fee` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`paymentID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE IF NOT EXISTS `person` (
  `personID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`personID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;

-- --------------------------------------------------------

--
-- Table structure for table `seat`
--

DROP TABLE IF EXISTS `seat`;
CREATE TABLE IF NOT EXISTS `seat` (
  `seatID` int NOT NULL,
  `flightID` int DEFAULT NULL,
  `type` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT 'None',
  `seatClass` varchar(50) DEFAULT NULL,
  `seatNumber` varchar(10) DEFAULT NULL,
  `occupied` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`seatID`),
  KEY `flightID` (`flightID`)
) ENGINE=MyISAM DEFAULT CHARSET=armscii8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
