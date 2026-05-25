-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2026 at 03:54 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tripistry_v7`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodation`
--

CREATE TABLE `accommodation` (
  `Service_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `attraction`
--

CREATE TABLE `attraction` (
  `Service_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `Booking_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Package_id` int(11) NOT NULL,
  `Code_id` int(11) DEFAULT NULL,
  `Trip_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `destination`
--

CREATE TABLE `destination` (
  `Service_id` int(11) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `Service_id` int(11) NOT NULL,
  `Flight_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `group_trip`
--

CREATE TABLE `group_trip` (
  `Trip_id` int(11) NOT NULL,
  `Package_id` int(11) NOT NULL,
  `Departure_date` date NOT NULL,
  `Capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `includes`
--

CREATE TABLE `includes` (
  `Package_id` int(11) NOT NULL,
  `Service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `Package_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Description` text DEFAULT NULL,
  `User_id` int(11) NOT NULL,
  `Target_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `package_images`
--

CREATE TABLE `package_images` (
  `Package_id` int(11) NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `promo_code`
--

CREATE TABLE `promo_code` (
  `Code_id` int(11) NOT NULL,
  `Discount_percentage` decimal(5,2) NOT NULL,
  `Code_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `Service_id` int(11) NOT NULL,
  `Name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `Review_id` int(11) NOT NULL,
  `Rating` int(11) NOT NULL CHECK (`Rating` >= 1 and `Rating` <= 5),
  `Comment` text DEFAULT NULL,
  `Date` date NOT NULL,
  `User_id` int(11) NOT NULL,
  `Target_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `review_target`
--

CREATE TABLE `review_target` (
  `Target_id` int(11) NOT NULL,
  `Target_Type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `Service_id` int(11) NOT NULL,
  `Street` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Code` varchar(20) DEFAULT NULL,
  `Type` enum('Attraction','Accommodation','Destination','Flight','Restaurant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `traveller`
--

CREATE TABLE `traveller` (
  `User_id` int(11) NOT NULL,
  `Fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Id_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `travel_agency`
--

CREATE TABLE `travel_agency` (
  `User_id` int(11) NOT NULL,
  `Agency_name` varchar(100) NOT NULL,
  `Contact_Fname` varchar(50) DEFAULT NULL,
  `Contact_Lname` varchar(50) DEFAULT NULL,
  `Target_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_id` int(11) NOT NULL,
  `User_type` varchar(20) NOT NULL,
  `Password_hash` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Cell` varchar(20) DEFAULT NULL,
  `salt` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `attraction`
--
ALTER TABLE `attraction`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`Booking_id`),
  ADD KEY `Package_id` (`Package_id`),
  ADD KEY `Code_id` (`Code_id`) USING BTREE,
  ADD KEY `Trip_id` (`Trip_id`);

--
-- Indexes for table `destination`
--
ALTER TABLE `destination`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `group_trip`
--
ALTER TABLE `group_trip`
  ADD PRIMARY KEY (`Trip_id`),
  ADD KEY `GROUP_TRIP_FK` (`Package_id`);

--
-- Indexes for table `includes`
--
ALTER TABLE `includes`
  ADD PRIMARY KEY (`Package_id`,`Service_id`),
  ADD KEY `Service_id` (`Service_id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`Package_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Target_id` (`Target_id`);

--
-- Indexes for table `package_images`
--
ALTER TABLE `package_images`
  ADD PRIMARY KEY (`Package_id`,`Image`);

--
-- Indexes for table `promo_code`
--
ALTER TABLE `promo_code`
  ADD PRIMARY KEY (`Code_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`Review_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Target_id` (`Target_id`);

--
-- Indexes for table `review_target`
--
ALTER TABLE `review_target`
  ADD PRIMARY KEY (`Target_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`Service_id`);

--
-- Indexes for table `traveller`
--
ALTER TABLE `traveller`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Id_number` (`Id_number`);

--
-- Indexes for table `travel_agency`
--
ALTER TABLE `travel_agency`
  ADD PRIMARY KEY (`User_id`),
  ADD KEY `Target_id` (`Target_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `Booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_trip`
--
ALTER TABLE `group_trip`
  MODIFY `Trip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `Package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_code`
--
ALTER TABLE `promo_code`
  MODIFY `Code_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `Review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_target`
--
ALTER TABLE `review_target`
  MODIFY `Target_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD CONSTRAINT `ACCOMMODATION_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `service` (`Service_id`);

--
-- Constraints for table `attraction`
--
ALTER TABLE `attraction`
  ADD CONSTRAINT `ATTRACTION_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `service` (`Service_id`);

--
-- Constraints for table `destination`
--
ALTER TABLE `destination`
  ADD CONSTRAINT `DESTINATION_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `service` (`Service_id`);

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `FLIGHT_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `service` (`Service_id`);

--
-- Constraints for table `group_trip`
--
ALTER TABLE `group_trip`
  ADD CONSTRAINT `GROUP_TRIP_ibfk_1` FOREIGN KEY (`Package_id`) REFERENCES `package` (`Package_id`);

--
-- Constraints for table `includes`
--
ALTER TABLE `includes`
  ADD CONSTRAINT `INCLUDES_ibfk_1` FOREIGN KEY (`Package_id`) REFERENCES `package` (`Package_id`),
  ADD CONSTRAINT `INCLUDES_ibfk_2` FOREIGN KEY (`Service_id`) REFERENCES `service` (`Service_id`);

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `PACKAGE_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `travel_agency` (`User_id`),
  ADD CONSTRAINT `PACKAGE_ibfk_2` FOREIGN KEY (`Target_id`) REFERENCES `review_target` (`Target_id`);

--
-- Constraints for table `package_images`
--
ALTER TABLE `package_images`
  ADD CONSTRAINT `PACKAGE_IMAGES_ibfk_1` FOREIGN KEY (`Package_id`) REFERENCES `package` (`Package_id`);

--
-- Constraints for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `RESTAURANT_ibfk_1` FOREIGN KEY (`Service_id`) REFERENCES `service` (`Service_id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `REVIEW_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `traveller` (`User_id`),
  ADD CONSTRAINT `REVIEW_ibfk_2` FOREIGN KEY (`Target_id`) REFERENCES `review_target` (`Target_id`);

--
-- Constraints for table `traveller`
--
ALTER TABLE `traveller`
  ADD CONSTRAINT `TRAVELLER_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `user` (`User_id`);

--
-- Constraints for table `travel_agency`
--
ALTER TABLE `travel_agency`
  ADD CONSTRAINT `TRAVEL_AGENCY_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `user` (`User_id`),
  ADD CONSTRAINT `TRAVEL_AGENCY_ibfk_2` FOREIGN KEY (`Target_id`) REFERENCES `review_target` (`Target_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
