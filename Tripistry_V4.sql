-- phpMyAdmin SQL Dump
-- version 5.0.4deb2~bpo10+1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2026 at 11:42 AM
-- Server version: 10.3.39-MariaDB-0+deb10u2
-- PHP Version: 7.3.31-1~deb10u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u24916031_tripistry`
--

-- --------------------------------------------------------

--
-- Table structure for table `ACCOMMODATION`
--

CREATE TABLE `ACCOMMODATION` (
  `Service_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ATTRACTION`
--

CREATE TABLE `ATTRACTION` (
  `Service_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `BOOKS`
--

CREATE TABLE `BOOKS` (
  `User_id` int(11) NOT NULL,
  `Package_id` int(11) NOT NULL,
  `Code_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DESTINATION`
--

CREATE TABLE `DESTINATION` (
  `Service_id` int(11) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `FLIGHT`
--

CREATE TABLE `FLIGHT` (
  `Service_id` int(11) NOT NULL,
  `Flight_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GROUP_TRIP`
--

CREATE TABLE `GROUP_TRIP` (
  `Package_id` int(11) NOT NULL,
  `Departure_date` date NOT NULL,
  `Capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `INCLUDES`
--

CREATE TABLE `INCLUDES` (
  `Package_id` int(11) NOT NULL,
  `Service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PACKAGE`
--

CREATE TABLE `PACKAGE` (
  `Package_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Description` text DEFAULT NULL,
  `User_id` int(11) NOT NULL,
  `Target_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PACKAGE_IMAGES`
--

CREATE TABLE `PACKAGE_IMAGES` (
  `Package_id` int(11) NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PROMO_CODE`
--

CREATE TABLE `PROMO_CODE` (
  `Code_id` int(11) NOT NULL,
  `Discount_percentage` decimal(5,2) NOT NULL,
  `Code_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `RESTAURANT`
--

CREATE TABLE `RESTAURANT` (
  `Service_id` int(11) NOT NULL,
  `Name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `REVIEW`
--

CREATE TABLE `REVIEW` (
  `Review_id` int(11) NOT NULL,
  `Rating` int(11) NOT NULL CHECK (`Rating` >= 1 and `Rating` <= 5),
  `Comment` text DEFAULT NULL,
  `Date` date NOT NULL,
  `User_id` int(11) NOT NULL,
  `Target_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `REVIEW_TARGET`
--

CREATE TABLE `REVIEW_TARGET` (
  `Target_id` int(11) NOT NULL,
  `Target_Type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `SERVICE`
--

CREATE TABLE `SERVICE` (
  `Service_id` int(11) NOT NULL,
  `Street` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Code` varchar(20) DEFAULT NULL,
  `Cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `TRAVELLER`
--

CREATE TABLE `TRAVELLER` (
  `User_id` int(11) NOT NULL,
  `Fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Id_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `TRAVEL_AGENCY`
--

CREATE TABLE `TRAVEL_AGENCY` (
  `User_id` int(11) NOT NULL,
  `Agency_name` varchar(100) NOT NULL,
  `Contact_Fname` varchar(50) DEFAULT NULL,
  `Contact_Lname` varchar(50) DEFAULT NULL,
  `Target_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `User_id` int(11) NOT NULL,
  `User_type` varchar(20) NOT NULL,
  `Password_hash` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Cell` varchar(20) DEFAULT NULL,
  `salt` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ACCOMMODATION`
--
ALTER TABLE `ACCOMMODATION`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `ATTRACTION`
--
ALTER TABLE `ATTRACTION`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `BOOKS`
--
ALTER TABLE `BOOKS`
  ADD PRIMARY KEY (`User_id`,`Package_id`),
  ADD KEY `Package_id` (`Package_id`),
  ADD KEY `Code_id` (`Code_id`);

--
-- Indexes for table `DESTINATION`
--
ALTER TABLE `DESTINATION`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `FLIGHT`
--
ALTER TABLE `FLIGHT`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `GROUP_TRIP`
--
ALTER TABLE `GROUP_TRIP`
  ADD PRIMARY KEY (`Package_id`,`Departure_date`);

--
-- Indexes for table `INCLUDES`
--
ALTER TABLE `INCLUDES`
  ADD PRIMARY KEY (`Package_id`,`Service_id`),
  ADD KEY `Service_id` (`Service_id`);

--
-- Indexes for table `PACKAGE`
--
ALTER TABLE `PACKAGE`
  ADD PRIMARY KEY (`Package_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Target_id` (`Target_id`);

--
-- Indexes for table `PACKAGE_IMAGES`
--
ALTER TABLE `PACKAGE_IMAGES`
  ADD PRIMARY KEY (`Package_id`,`Image`);

--
-- Indexes for table `PROMO_CODE`
--
ALTER TABLE `PROMO_CODE`
  ADD PRIMARY KEY (`Code_id`);

--
-- Indexes for table `RESTAURANT`
--
ALTER TABLE `RESTAURANT`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `REVIEW`
--
ALTER TABLE `REVIEW`
  ADD PRIMARY KEY (`Review_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Target_id` (`Target_id`);

--
-- Indexes for table `REVIEW_TARGET`
--
ALTER TABLE `REVIEW_TARGET`
  ADD PRIMARY KEY (`Target_id`);

--
-- Indexes for table `SERVICE`
--
ALTER TABLE `SERVICE`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `TRAVELLER`
--
ALTER TABLE `TRAVELLER`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Id_number` (`Id_number`);

--
-- Indexes for table `TRAVEL_AGENCY`
--
ALTER TABLE `TRAVEL_AGENCY`
  ADD PRIMARY KEY (`User_id`),
  ADD KEY `Target_id` (`Target_id`);

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `PACKAGE`
--
ALTER TABLE `PACKAGE`
  MODIFY `Package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PROMO_CODE`
--
ALTER TABLE `PROMO_CODE`
  MODIFY `Code_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `REVIEW`
--
ALTER TABLE `REVIEW`
  MODIFY `Review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `REVIEW_TARGET`
--
ALTER TABLE `REVIEW_TARGET`
  MODIFY `Target_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `SERVICE`
--
ALTER TABLE `SERVICE`
  MODIFY `Service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ACCOMMODATION`
--
ALTER TABLE `ACCOMMODATION`
  ADD CONSTRAINT `ACCOMMODATION_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `SERVICE` (`Service_id`);

--
-- Constraints for table `ATTRACTION`
--
ALTER TABLE `ATTRACTION`
  ADD CONSTRAINT `ATTRACTION_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `SERVICE` (`Service_id`);

--
-- Constraints for table `BOOKS`
--
ALTER TABLE `BOOKS`
  ADD CONSTRAINT `BOOKS_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `TRAVELLER` (`User_id`),
  ADD CONSTRAINT `BOOKS_ibfk_2` FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE` (`Package_id`),
  ADD CONSTRAINT `BOOKS_ibfk_3` FOREIGN KEY (`Code_id`) REFERENCES `PROMO_CODE` (`Code_id`);

--
-- Constraints for table `DESTINATION`
--
ALTER TABLE `DESTINATION`
  ADD CONSTRAINT `DESTINATION_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `SERVICE` (`Service_id`);

--
-- Constraints for table `FLIGHT`
--
ALTER TABLE `FLIGHT`
  ADD CONSTRAINT `FLIGHT_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `SERVICE` (`Service_id`);

--
-- Constraints for table `GROUP_TRIP`
--
ALTER TABLE `GROUP_TRIP`
  ADD CONSTRAINT `GROUP_TRIP_ibfk_1` FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE` (`Package_id`);

--
-- Constraints for table `INCLUDES`
--
ALTER TABLE `INCLUDES`
  ADD CONSTRAINT `INCLUDES_ibfk_1` FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE` (`Package_id`),
  ADD CONSTRAINT `INCLUDES_ibfk_2` FOREIGN KEY (`Service_id`) REFERENCES `SERVICE` (`Service_id`);

--
-- Constraints for table `PACKAGE`
--
ALTER TABLE `PACKAGE`
  ADD CONSTRAINT `PACKAGE_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `TRAVEL_AGENCY` (`User_id`),
  ADD CONSTRAINT `PACKAGE_ibfk_2` FOREIGN KEY (`Target_id`) REFERENCES `REVIEW_TARGET` (`Target_id`);

--
-- Constraints for table `PACKAGE_IMAGES`
--
ALTER TABLE `PACKAGE_IMAGES`
  ADD CONSTRAINT `PACKAGE_IMAGES_ibfk_1` FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE` (`Package_id`);

--
-- Constraints for table `RESTAURANT`
--
ALTER TABLE `RESTAURANT`
  ADD CONSTRAINT `RESTAURANT_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `SERVICE` (`Service_id`);

--
-- Constraints for table `REVIEW`
--
ALTER TABLE `REVIEW`
  ADD CONSTRAINT `REVIEW_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `TRAVELLER` (`User_id`),
  ADD CONSTRAINT `REVIEW_ibfk_2` FOREIGN KEY (`Target_id`) REFERENCES `REVIEW_TARGET` (`Target_id`);

--
-- Constraints for table `TRAVELLER`
--
ALTER TABLE `TRAVELLER`
  ADD CONSTRAINT `TRAVELLER_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `USER` (`User_id`);

--
-- Constraints for table `TRAVEL_AGENCY`
--
ALTER TABLE `TRAVEL_AGENCY`
  ADD CONSTRAINT `TRAVEL_AGENCY_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `USER` (`User_id`),
  ADD CONSTRAINT `TRAVEL_AGENCY_ibfk_2` FOREIGN KEY (`Target_id`) REFERENCES `REVIEW_TARGET` (`Target_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
