<?php
	 if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
	if (!isset($_SESSION["user_type"])){
		header("Location: ./loginAgency.php");
		exit;
	}else {
		if ($_SESSION["user_type"] == "Traveller"){
			header("Location: ../traveller/browsePackage.php");
			exit;
		}
	}	
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Packages</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/browsePackages.css" /> 
</head>
<body>
    <?php include '../navbar.php'; ?>
    <div class="browse-container">
        <h1 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">My Offered Packages</h1>
        <div class="packages-grid" id="packagesGrid"></div>
    </div>
    <script src="js/agentPackages.js"></script>
</body>
</html>