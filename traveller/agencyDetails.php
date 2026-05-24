<?php
	 if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
if (!isset($_SESSION["user_type"])){
	echo '
	<script>
	window.location.href = "loginTraveller.php";
	</script>
	';
}


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Details | Tripistry</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/browsePackages.css" />
    <link rel="stylesheet" href="../css/agencyDetails.css" />
</head>
<body>
    <?php include '../navbar.php'; ?>
    <div class="browse-container">
        <a href="browsePackage.php" style="color: white; text-decoration: none; align-self: flex-start; margin-bottom: 20px; font-weight: bold;">
            &larr; Back to Browse
        </a>

        <div class="agency-banner">
            <h1 id="agencyName">Loading Agency...</h1>
            <p id="agencyContact">Please wait</p>
            <p id="agencyEmail"></p>
        </div>

        <div class="packages-grid" id="agencyPackagesGrid">
        </div>
    </div>
    <script src="./js/agencyDetails.js"></script>
</body>
</html>