-- 1. Delete Relationship/Transaction tables (The most "dependent" tables)
DELETE FROM `APPLIES`;
DELETE FROM `BOOKS`;
DELETE FROM `INCLUDES`;
DELETE FROM `REVIEW`;

-- 2. Delete Package-specific tables
DELETE FROM `PACKAGE_IMAGES`;
DELETE FROM `GROUP_TRIP`;
DELETE FROM `PACKAGE`;

-- 3. Delete Agency/User secondary tables
DELETE FROM `LICENSE`;
DELETE FROM `AGENCY_FINANCIALS`;
DELETE FROM `USER_ADDRESS`;

-- 4. Delete specialized Service tables
DELETE FROM `DESTINATION`;
DELETE FROM `ACCOMMODATION`;
DELETE FROM `ATTRACTION`;
DELETE FROM `RESTAURANT`;
DELETE FROM `FLIGHT`;

-- 5. Delete specialized User tables
DELETE FROM `TRAVELLER`;
DELETE FROM `TRAVEL_AGENCY`;

-- 6. Finally, delete the Base/Parent tables
DELETE FROM `SERVICE`;
DELETE FROM `PROMO_CODE`;
DELETE FROM `REVIEW_TARGET`;
DELETE FROM `USER`;

-- Reset counters for Base Tables
ALTER TABLE `USER` AUTO_INCREMENT = 1;
ALTER TABLE `SERVICE` AUTO_INCREMENT = 1;
ALTER TABLE `REVIEW_TARGET` AUTO_INCREMENT = 1;
ALTER TABLE `PROMO_CODE` AUTO_INCREMENT = 1;

-- Reset counters for Other Primary Tables
ALTER TABLE `PACKAGE` AUTO_INCREMENT = 1;
ALTER TABLE `REVIEW` AUTO_INCREMENT = 1;


-- USER
INSERT INTO `USER` (`User_type`, `Password_hash`, `Email`, `Cell`) VALUES 
('Traveller', 'hash_alice_123', 'alice@example.com', '0821112222'),
('Traveller', 'hash_bob_456', 'bob.smith@example.com', '0713334444'),
('Travel Agency', 'hash_wild_789', 'bookings@wildsafari.co.za', '0115556666'),
('Travel Agency', 'hash_coast_101', 'info@coastaltours.com', '0319998888');

-- Capturing IDs for later use
SET @alice_id = 1, @bob_id = 2, @safari_agency_id = 3, @coastal_agency_id = 4;

-- TRAVELLER
INSERT INTO `TRAVELLER` (`User_id`, `Fname`, `Lname`, `Id_number`) VALUES 
(@alice_id, 'Alice', 'Johnson', '9001015000081'),
(@bob_id, 'Bob', 'Smith', '8505205000082');

-- REVIEW_TARGET (Required for Agencies and Packages)
INSERT INTO `REVIEW_TARGET` (`Target_Type`) VALUES 
('Travel Agency'), ('Travel Agency'), ('Package'), ('Package');

SET @target_safari = 1, @target_coastal = 2, @target_pkg1 = 3, @target_pkg2 = 4;

-- TRAVEL_AGENCY
INSERT INTO `TRAVEL_AGENCY` (`User_id`, `Agency_name`, `Contact_Fname`, `Contact_Lname`, `Target_id`) VALUES 
(@safari_agency_id, 'Wild Safari Tours', 'Sarah', 'Khumalo', @target_safari),
(@coastal_agency_id, 'Coastal Tours', 'Mark', 'Van Wyk', @target_coastal);

-- USER_ADDRESS
INSERT INTO `USER_ADDRESS` (`User_id`, `Street`, `City`, `Code`) VALUES 
(@alice_id, '12 Rose Lane', 'Cape Town', '8001'),
(@bob_id, '45 Oak Ave', 'Johannesburg', '2000');

-- LICENSE
INSERT INTO `LICENSE` (`License_number`, `Issue_date`, `User_id`) VALUES 
('L-2024-SAF', '2024-01-15', @safari_agency_id),
('L-2024-CST', '2024-02-20', @coastal_agency_id);

-- AGENCY_FINANCIALS
INSERT INTO `AGENCY_FINANCIALS` (`Financial_id`, `Bank_AccountNo`, `Tax_AccountNo`, `User_id`) VALUES 
(1, 'ACC998877', 'TAX112233', @safari_agency_id),
(2, 'ACC445566', 'TAX445566', @coastal_agency_id);

-- SERVICE
INSERT INTO `SERVICE` (`Street`, `City`, `Code`, `Cost`) VALUES 
('King Shaka Drive', 'La Mercy', '4405', 850.00), -- Flight
('1 King Shaka Ave', 'Durban', '4001', 250.00),     -- Attraction
('20 Battery Beach Rd', 'Durban', '4001', 1500.00), -- Accommodation
('199 Peter Mokaba Rd', 'Durban', '4001', 350.00),  -- Restaurant
('Main CBD', 'Durban', '4000', 0.00);               -- Destination

SET @s_flight = 1, @s_attr = 2, @s_hotel = 3, @s_rest = 4, @s_dest = 5;

-- Specialized Service Tables
INSERT INTO `FLIGHT` (`Service_id`, `Flight_number`) VALUES (@s_flight, 'FA100');
INSERT INTO `ATTRACTION` (`Service_id`, `Name`) VALUES (@s_attr, 'uShaka Marine World');
INSERT INTO `ACCOMMODATION` (`Service_id`, `Name`) VALUES (@s_hotel, 'Sun Coast Hotel');
INSERT INTO `RESTAURANT` (`Service_id`, `Name`) VALUES (@s_rest, 'Mozambik');
INSERT INTO `DESTINATION` (`Service_id`, `Description`) VALUES (@s_dest, 'South Africa\'s premier beach destination.');

-- PROMO_CODE
INSERT INTO `PROMO_CODE` (`Discount_percentage`) VALUES (10.00), (15.50);

-- PACKAGE
INSERT INTO `PACKAGE` (`Name`, `Price`, `Description`, `User_id`, `Target_id`) VALUES 
('Durban Summer Escape', 4500.00, 'All-inclusive beach trip', @safari_agency_id, @target_pkg1),
('Coastal Weekend', 2200.00, 'Quick getaway', @coastal_agency_id, @target_pkg2);

-- PACKAGE_IMAGES
INSERT INTO `PACKAGE_IMAGES` (`Package_id`, `Image`) VALUES 
(1, 'https://cdn.travel.com/img1.jpg'),
(2, 'https://cdn.travel.com/img2.jpg');

-- GROUP_TRIP
INSERT INTO `GROUP_TRIP` (`Package_id`, `Departure_date`, `Capacity`) VALUES 
(1, '2026-06-15', 20),
(2, '2026-07-01', 10);

-- INCLUDES (Connecting Packages to Services)
INSERT INTO `INCLUDES` (`Package_id`, `Service_id`) VALUES (1, @s_flight), (1, @s_hotel), (2, @s_attr);

-- BOOKS
INSERT INTO `BOOKS` (`User_id`, `Package_id`) VALUES (@alice_id, 1), (@bob_id, 2);

-- APPLIES (Alice used a promo code for Package 1)
INSERT INTO `APPLIES` (`User_id`, `Package_id`, `Code_id`) VALUES (@alice_id, 1, 1), (@bob_id, 2, 2);

-- REVIEW
INSERT INTO `REVIEW` (`Rating`, `Comment`, `Date`, `User_id`, `Target_id`) VALUES 
(5, 'Amazing safari agency!', '2026-05-10', @alice_id, @target_safari),
(4, 'Great package value.', '2026-05-12', @bob_id, @target_pkg2);
