<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Details | Tripistry</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/packageDetails.css" />
</head>
<body>
    <?php include '../navbar.php'; ?>
    <main class="details-container">
        <a href="browsePackage.php" class="back-link">&larr; Back to Packages</a>
        
        <div class="slideshow-container">
            <div id="slides" class="slides-wrapper"></div>
            <button class="nav-btn prev" onclick="changeSlide(-1)">&#10094;</button>
            <button class="nav-btn next" onclick="changeSlide(1)">&#10095;</button>
        </div>

        <div id="loadingState" class="state-container">
            <div class="spinner"></div>
            <p>Loading package details...</p>
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
                        <span class="price-label">Total Price</span>
                        <h2 class="price-amount">ZAR <span id="pkgPrice">0.00</span></h2>
                    </div>
                    <button id="bookBtn" class="btn-primary full-width">Book This Package</button>
                    <div class="guarantee-box">
                        <p>✅ Instant Confirmation</p>
                        <p>🔒 Secure Payment</p>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <div id="bookingModal" class="modal-overlay hidden">
    <div class="modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <h2 style="color: #1F80FF; margin-top: 0;">Confirm Your Trip</h2>
        <form id="bookingForm">
            <label style="font-weight: 600; font-size: 14px; margin-bottom: -10px;">Number of Guests</label>
            <input type="number" name="guests" placeholder="1" min="1" required>
            
            <label style="font-weight: 600; font-size: 14px; margin-bottom: -10px;">Promo Code</label>
            <input type="text" name="code_name" placeholder="Optional">
            
            <label style="font-weight: 600; font-size: 14px; margin-bottom: -10px;">Start Date</label>
            <input type="date" name="start_date" required>
            
            <label style="font-weight: 600; font-size: 14px; margin-bottom: -10px;">End Date</label>
            <input type="date" name="end_date" required>
            
            <button type="submit" class="btn-primary" style="margin-top: 10px;">Confirm & Book</button>
        </form>
    </div>
</div>

    <script src="./js/packageDetails.js"></script>
</body>
</html>
