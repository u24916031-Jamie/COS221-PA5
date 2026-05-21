--Basic Information Retrieval
SELECT T.Fname, T.Lname, U.Email, U.Cell 
FROM TRAVELLER T
JOIN USER U ON T.User_id = U.User_id;

--Find all Travel Agencies and their names
SELECT Agency_name, Contact_Fname, Contact_Lname 
FROM TRAVEL_AGENCY;

--List all packages under a certain price point
SELECT Name, Price, Description 
FROM PACKAGE 
WHERE Price <= 5000.00;

--Show which Travellers have booked which Packages
SELECT T.Fname, T.Lname, P.Name AS Package_Name
FROM BOOKS B
JOIN TRAVELLER T ON B.User_id = T.User_id
JOIN PACKAGE P ON B.Package_id = P.Package_id;

--List all Flight details included in a specific Package
SELECT P.Name AS Package_Name, F.Flight_number, S.City, S.Cost
FROM PACKAGE P
JOIN INCLUDES I ON P.Package_id = I.Package_id
JOIN SERVICE S ON I.Service_id = S.Service_id
JOIN FLIGHT F ON S.Service_id = F.Service_id;


--eish
--Find all Accommodations and Restaurants for a Package
SELECT P.Name, Acc.Name AS Hotel_Name, Res.Name AS Restaurant_Name
FROM PACKAGE P
JOIN INCLUDES I ON P.Package_id = I.Package_id
LEFT JOIN ACCOMMODATION Acc ON I.Service_id = Acc.Service_id
LEFT JOIN RESTAURANT Res ON I.Service_id = Res.Service_id;


--Calculate the Average Rating for a specific Package
SELECT P.Name, AVG(R.Rating) as Average_Rating
FROM PACKAGE P
JOIN REVIEW R ON P.Target_id = R.Target_id
GROUP BY P.Name;

--Identify highly-rated Travel Agencies
SELECT TA.Agency_name, AVG(R.Rating) as Avg_Agency_Rating
FROM TRAVEL_AGENCY TA
JOIN REVIEW R ON TA.Target_id = R.Target_id
GROUP BY TA.Agency_name
HAVING Avg_Agency_Rating >= 4.0;

--Find which Promo Codes were used by Travellers
SELECT T.Fname, T.Lname, P.Name AS Package, PC.Discount_percentage
FROM APPLIES A
JOIN TRAVELLER T ON A.User_id = T.User_id
JOIN PACKAGE P ON A.Package_id = P.Package_id
JOIN PROMO_CODE PC ON A.Code_id = PC.Code_id;

--Verify Agency Licenses
SELECT TA.Agency_name, L.License_number, L.Issue_date
FROM TRAVEL_AGENCY TA
JOIN LICENSE L ON TA.User_id = L.User_id;
