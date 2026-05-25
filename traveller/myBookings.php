<?php
	 if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
	if (!isset($_SESSION["user_type"])){
		header("Location: ./loginTraveller.php");
		exit;
	}else {
		if ($_SESSION["user_type"] == "Travel Agency"){
			header("Location: ../agent/agentPackages.php");
			exit;
		}
	}
		
		
		
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | Tripistry</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/browsePackages.css">
    <link rel="stylesheet" href="../css/myBookings.css">
</head>
<body>
    <?php include '../navbar.php'; ?>
    
    <div class="browse-container">
        <div class="header-section">
            <h1 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">My Booked Trips</h1>
        </div>
        
        <div class="packages-grid" id="bookingsGrid">
        </div>
    </div>


    <script src="./js/myBookings.js"></script>
</body>
</html>