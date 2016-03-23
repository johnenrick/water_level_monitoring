-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2016 at 06:46 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `water_level_monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
`ID` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`ID`, `description`, `longitude`, `latitude`) VALUES
(1, 'Sensor 1', 123.91861975193024, 10.345169725972394),
(2, 'Sensor 2', 123.91795456409453, 10.349201490672645);

-- --------------------------------------------------------

--
-- Table structure for table `water_level`
--

CREATE TABLE IF NOT EXISTS `water_level` (
`ID` int(11) NOT NULL,
  `device_ID` int(11) NOT NULL,
  `measurement` double NOT NULL,
  `datetime` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device`
--
ALTER TABLE `device`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `water_level`
--
ALTER TABLE `water_level`
 ADD PRIMARY KEY (`ID`), ADD KEY `device_ID` (`device_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `water_level`
--
ALTER TABLE `water_level`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `water_level`
--
ALTER TABLE `water_level`
ADD CONSTRAINT `water_level_device` FOREIGN KEY (`device_ID`) REFERENCES `device` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
