-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 23, 2013 at 06:33 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `eztc`
--

-- --------------------------------------------------------

--
-- Table structure for table `Employee`
--

CREATE TABLE `Employee` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `Project`
--

CREATE TABLE `Project` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(32) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Code` (`Code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `TimeEntry`
--

CREATE TABLE `TimeEntry` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectId` int(11) NOT NULL,
  `EmployeeId` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Hours` tinyint(4) NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ProjectId` (`ProjectId`),
  KEY `EmployeeId` (`EmployeeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=272 ;

--
-- RELATIONS FOR TABLE `TimeEntry`:
--   `ProjectId`
--       `Project` -> `Id`
--   `EmployeeId`
--       `Employee` -> `Id`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `TimeEntry`
--
ALTER TABLE `TimeEntry`
  ADD CONSTRAINT `TimeEntry_ibfk_1` FOREIGN KEY (`ProjectId`) REFERENCES `Project` (`Id`),
  ADD CONSTRAINT `TimeEntry_ibfk_2` FOREIGN KEY (`EmployeeId`) REFERENCES `Employee` (`Id`);
