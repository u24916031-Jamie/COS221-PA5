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
    <title>Add Single Package</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/homePage.css" />
    <link rel="stylesheet" href="../css/signUp.css" />
    <link rel="stylesheet" href="../css/addPackageSingle.css" />
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page">
        <div class="left-section"></div>
        
        <div class="right-section">
            <div class="login-card">
                <form id="createPackageForm" enctype="multipart/form-data">
                    <h3 class="form-header">Create Single Package</h3>
                    
                    <div class="section-title">General Details</div>
                    <input type="text" name="name" placeholder="Package Name" required>
                    <input type="number" name="price" placeholder="Total Price (ZAR)" required>
                    <textarea name="description" placeholder="Package Description" required></textarea>

                    <div class="section-title">Media</div>
                    <div class="image-upload-container">
                        <label for="package-images-single" class="custom-file-upload">
                            <span class="upload-icon">📷</span>
                            Click to select Package Images
                        </label>
                        <input type="file" id="package-images-single" class="hidden-upload" name="packageImages[]" accept="image/png, image/jpeg, image/jpg, image/webp" multiple>
                        <div id="image-preview-container-single" class="preview-container"></div>
                    </div>

                    <div class="section-title">Services Builder</div>
                    <div id="services-container"></div>

                    <button type="button" id="add-service-btn" class="add-service-btn">
                        + Add New Service
                    </button>

                    <input type="hidden" name="type" value="createPackage">

                    <button type="submit" class="submit-btn">Publish Single Package</button>
					 <button type="button" class="submit-btn" style="background: #dc3545; margin-top: 10px;" onclick="window.location.href='agentPackages.php'">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <script src="./js/addPackageSingle.js"></script>
</body>
</html>
