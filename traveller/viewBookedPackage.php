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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Trip | Tripistry</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/packageDetails.css" />
</head>
<body>
    <?php include '../navbar.php'; ?>
    <main class="details-container">
        <a href="myBookings.php" class="back-link">&larr; Back to My Bookings</a>

        <div class="slideshow-container">
            <div id="slides" class="slides-wrapper"></div>
            <button class="nav-btn prev" onclick="changeSlide(-1)">&#10094;</button>
            <button class="nav-btn next" onclick="changeSlide(1)">&#10095;</button>
        </div>

        <div id="loadingState" class="state-container">
            <div class="spinner"></div>
            <p>Loading trip details...</p>
        </div>

        <div id="packageContent" class="package-layout hidden">
            <div class="main-details">
                <div class="card">
                    <div class="package-header">
                        <span class="badge" id="pkgIdBadge"></span>
                        <h1 id="pkgTitle"></h1>
                        <p class="agency-tag">Offered by <strong id="pkgAgency"></strong></p>
                    </div>
                    <div class="section-divider"></div>
                    <div class="content-section">
                        <h2>Overview</h2>
                        <p id="pkgDescription" class="text-body"></p>
                    </div>
                    <div class="section-divider"></div>
                    <div class="content-section">
                        <h2>Included Services</h2>
                        <div id="pkgServices" class="services-grid"></div>
                    </div>
                </div>
            </div>
            <aside class="sidebar">
                <div class="card sticky-sidebar">
                    <div class="price-box">
                        <span class="price-label">Total Price Paid</span>
                        <h2 class="price-amount">ZAR <span id="pkgPrice"></span></h2>
                    </div>
                    <div class="guarantee-box">
                        <p>✅ Instant Confirmation</p>
                        <p>🔒 Secure Payment</p>
                    </div>
                </div>
            </aside>
        </div>
    </main>
    <script src="./js/viewBookedPackage.js"></script>
</body>
</html>