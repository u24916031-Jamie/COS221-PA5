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
        <a href="browsePackage.php" class="back-link">
            &larr; Back to Packages
        </a>
        <div class="slideshow-container">
            <div id="slides" class="slides-wrapper"></div>
            <button class="nav-btn prev" onclick="changeSlide(-1)">&#10094;</button>
            <button class="nav-btn next" onclick="changeSlide(1)">&#10095;</button>
        </div>

        <div id="loadingState" class="state-container">
            <div class="spinner"></div>
            <p>Loading package details...</p>
        </div>

        <div id="errorState" class="state-container hidden">
            <h2 id="errorMessage">An error occurred</h2>
            <p id="errorDescription">We couldn't load this package.</p>
            <button onclick="window.location.reload()" class="btn-primary mt-4">Try Again</button>
        </div>

        <div id="packageContent" class="package-layout hidden">
            
            <div class="main-details">
                <div class="card">
                    <div class="package-header">
                        <span class="badge" id="pkgIdBadge">ID: --</span>
                        <h1 id="pkgTitle">Package Title</h1>
                        <p class="agency-tag">Offered by <strong id="pkgAgency">Loading...</strong></p>
                    </div>

                    <div class="section-divider"></div>

                    <div class="content-section">
                        <h2>Overview</h2>
                        <p id="pkgDescription" class="text-body">Loading description...</p>
                    </div>

                    <div class="section-divider"></div>

                    <div class="content-section">
                        <h2>Included Services</h2>
                        <div id="pkgServices" class="services-grid">
                            </div>
                    </div>
                </div>
            </div>

            <aside class="sidebar">
                <div class="card sticky-sidebar">
                    <div class="price-box">
                        <span class="price-label">Total Price</span>
                        <h2 class="price-amount">ZAR <span id="pkgPrice">0.00</span></h2>
                    </div>
                    
                    <button id="bookBtn" class="btn-primary full-width mt-4">Book This Package</button>
                    
                    <div class="guarantee-box">
                        <p>✓ Instant Confirmation</p>
                        <p>✓ Secure Payment</p>
                    </div>
                </div>
            </aside>

        </div>
    </main>

    <script src="../js/packageDetails.js"></script>
</body>
</html>