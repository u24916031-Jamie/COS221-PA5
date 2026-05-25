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
                        <p class="agency-tag">Offered by <a id="agencyLink"><strong id="pkgAgency"></strong></a></p>
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
				 <div class="card sticky-sidebar">
					<div class="review-container" id="review-container">
						<button class="book-btn review-btn" id="reviewPackage">Review Package</button>
						<button class="book-btn review-btn" id="reviewAgency">Review Agency</button>
					</div>
                </div>
            </aside>
        </div>
    </main>

    <div id="reviewModal" class="modal-overlay hidden">
        <div class="modal-content">
            <span class="close-modal" id="closeModal">&times;</span>
            <h2>Review this <span id="reviewTargetType">Package</span></h2>
            <form id="reviewForm">
                <input type="hidden" name="type" value="review">
                <input type="hidden" id="reviewTargetId" name="target_id" value="">
                <input type="hidden" id="reviewDate" name="date" value="">

                <label for="reviewRating">Rating (1-5)</label>
                <select id="reviewRating" name="rating" required>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Terrible</option>
                </select>

                <label for="reviewComment">Comment</label>
                <textarea id="reviewComment" name="comment" placeholder="Tell us about your experience..." required></textarea>

                <button type="submit" class="submit-review-btn">Submit Review</button>
            </form>
        </div>
    </div>


    <script src="./js/viewBookedPackage.js"></script>
</body>
</html>