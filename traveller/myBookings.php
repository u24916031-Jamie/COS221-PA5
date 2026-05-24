<?php
	 if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
if (!isset($_SESSION["user_type"])){
	echo '
	<script>
	window.location.href = "loginTraveller.html";
	</script>
	';
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

    <div id="reviewModal" class="modal-overlay hidden">
        <div class="modal-content">
            <span class="close-modal" id="closeModal">&times;</span>
            <h2>Review this Package</h2>
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

    <script src="./js/myBookings.js"></script>
</body>
</html>