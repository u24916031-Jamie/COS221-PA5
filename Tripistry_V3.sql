-- 1. BASE TABLES (With Auto-Increment)
CREATE TABLE `USER` (
    `User_id` INT AUTO_INCREMENT PRIMARY KEY,
    `User_type` VARCHAR(20) NOT NULL,
    `Password_hash` VARCHAR(255) NOT NULL,
    `Email` VARCHAR(100) UNIQUE NOT NULL,
    `Cell` VARCHAR(20)
);

CREATE TABLE `SERVICE` (
    `Service_id` INT AUTO_INCREMENT PRIMARY KEY,
    `Street` VARCHAR(100),
    `City` VARCHAR(50),
    `Code` VARCHAR(20),
    `Cost` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `REVIEW_TARGET` (
    `Target_id` INT AUTO_INCREMENT PRIMARY KEY,
    `Target_Type` VARCHAR(50) NOT NULL
);

CREATE TABLE `PROMO_CODE` (
    `Code_id` INT AUTO_INCREMENT PRIMARY KEY,
    `Discount_percentage` DECIMAL(5, 2) NOT NULL
);

-- 2. INHERITED TABLES (No Auto-Increment, PK is a FK)
CREATE TABLE `TRAVELLER` (
    `User_id` INT PRIMARY KEY,
    `Fname` VARCHAR(50) NOT NULL,
    `Lname` VARCHAR(50) NOT NULL,
    `Id_number` VARCHAR(20) UNIQUE NOT NULL,
    FOREIGN KEY (`User_id`) REFERENCES `USER`(`User_id`)
);

CREATE TABLE `TRAVEL_AGENCY` (
    `User_id` INT PRIMARY KEY,
    `Agency_name` VARCHAR(100) NOT NULL,
    `Contact_Fname` VARCHAR(50),
    `Contact_Lname` VARCHAR(50),
    `Target_id` INT,
    FOREIGN KEY (`User_id`) REFERENCES `USER`(`User_id`),
    FOREIGN KEY (`Target_id`) REFERENCES `REVIEW_TARGET`(`Target_id`)
);

CREATE TABLE `FLIGHT` (
    `Service_id` INT PRIMARY KEY,
    `Flight_number` VARCHAR(20) NOT NULL,
    FOREIGN KEY (`Service_id`) REFERENCES `SERVICE`(`Service_id`)
);

CREATE TABLE `RESTAURANT` ( 
    `Service_id` INT PRIMARY KEY,
    `Name` VARCHAR(20),
    FOREIGN KEY (`Service_id`) REFERENCES `SERVICE`(`Service_id`)
);

CREATE TABLE `ATTRACTION` (
    `Service_id` INT PRIMARY KEY,
    `Name` VARCHAR(100) NOT NULL,
    FOREIGN KEY (`Service_id`) REFERENCES `SERVICE`(`Service_id`)
);

CREATE TABLE `ACCOMMODATION` (
    `Service_id` INT PRIMARY KEY,
    `Name` VARCHAR(100) NOT NULL,
    FOREIGN KEY (`Service_id`) REFERENCES `SERVICE`(`Service_id`)
);

CREATE TABLE `DESTINATION` (
    `Service_id` INT PRIMARY KEY,
    `Description` TEXT,
    FOREIGN KEY (`Service_id`) REFERENCES `SERVICE`(`Service_id`)
);

-- 3. OTHER RELATED TABLES
CREATE TABLE `USER_ADDRESS` (
    `User_id` INT,
    `Street` VARCHAR(100),
    `City` VARCHAR(50),
    `Code` VARCHAR(20),
    PRIMARY KEY (`User_id`, `Street`, `City`, `Code`),
    FOREIGN KEY (`User_id`) REFERENCES `USER`(`User_id`)
);

CREATE TABLE `AGENCY_FINANCIALS` (
    `Financial_id` INT PRIMARY KEY,
    `Bank_AccountNo` VARCHAR(50) NOT NULL,
    `Tax_AccountNo` VARCHAR(50) NOT NULL,
    `User_id` INT UNIQUE NOT NULL, -- UNIQUE constraint for 1:1
    FOREIGN KEY (`User_id`) REFERENCES `TRAVEL_AGENCY`(`User_id`)
);

CREATE TABLE `LICENSE` (
    `License_number` VARCHAR(50) PRIMARY KEY,
    `Issue_date` DATE NOT NULL,
    `User_id` INT UNIQUE NOT NULL,
    FOREIGN KEY (`User_id`) REFERENCES `TRAVEL_AGENCY`(`User_id`)
);

CREATE TABLE `PACKAGE` (
    `Package_id` INT AUTO_INCREMENT PRIMARY KEY,
    `Name` VARCHAR(100) NOT NULL,
    `Price` DECIMAL(10, 2) NOT NULL,
    `Description` TEXT,
    `User_id` INT NOT NULL,
    `Target_id` INT,
    FOREIGN KEY (`User_id`) REFERENCES `TRAVEL_AGENCY`(`User_id`),
    FOREIGN KEY (`Target_id`) REFERENCES `REVIEW_TARGET`(`Target_id`)
);

CREATE TABLE `REVIEW` (
    `Review_id` INT AUTO_INCREMENT PRIMARY KEY,
    `Rating` INT NOT NULL CHECK (`Rating` >= 1 AND `Rating` <= 5),
    `Comment` TEXT,
    `Date` DATE NOT NULL,
    `User_id` INT NOT NULL,
    `Target_id` INT NOT NULL,
    FOREIGN KEY (`User_id`) REFERENCES `TRAVELLER`(`User_id`),
    FOREIGN KEY (`Target_id`) REFERENCES `REVIEW_TARGET`(`Target_id`)
);

-- 4. RELATIONSHIP TABLES (M:N)
CREATE TABLE `BOOKS` (
    `User_id` INT,
    `Package_id` INT,
    PRIMARY KEY (`User_id`, `Package_id`),
    FOREIGN KEY (`User_id`) REFERENCES `TRAVELLER`(`User_id`),
    FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE`(`Package_id`)
);

CREATE TABLE `INCLUDES` (
    `Package_id` INT,
    `Service_id` INT,
    PRIMARY KEY (`Package_id`, `Service_id`),
    FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE`(`Package_id`),
    FOREIGN KEY (`Service_id`) REFERENCES `SERVICE`(`Service_id`)
);


CREATE TABLE `PACKAGE_IMAGES` (
    `Package_id` INT,
    `Image` VARCHAR(255), -- store a URL to image
    PRIMARY KEY (`Package_id`, Image),
    FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE`(`Package_id`)
);
CREATE TABLE `GROUP_TRIP` ( -- required in specs
    `Package_id` INT,
    `Departure_date` DATE,
    `Capacity` INT NOT NULL,
    PRIMARY KEY (`Package_id`, `Departure_date`),
    FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE`(`Package_id`) 
);
CREATE TABLE `APPLIES` ( -- N-ary relationship between Traveller, Package, and Promo Code
    `User_id` INT,
    `Package_id` INT,
    `Code_id` INT,
    PRIMARY KEY (`User_id`, `Package_id`, `Code_id`),
    FOREIGN KEY (`User_id`) REFERENCES `TRAVELLER`(`User_id`), -- references TRAVELLER!!!!!!!!!!!
    FOREIGN KEY (`Package_id`) REFERENCES `PACKAGE`(`Package_id`),
    FOREIGN KEY (`Code_id`) REFERENCES `PROMO_CODE`(`Code_id`)
);