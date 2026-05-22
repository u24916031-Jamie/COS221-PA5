<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Package | Agency</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/homePage.css" />
    <link rel="stylesheet" href="../css/signUp.css" />
    <link rel="stylesheet" href="../css/addPackageSingle.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page">
        <div class="left-section"></div>
        <div class="right-section">
            <div class="login-card">
                <form id="editPackageForm" enctype="multipart/form-data">
                    <h3 class="form-header">Edit Package</h3>
                    <input type="hidden" id="editPackageId" name="package_id">
                    <input type="hidden" name="type" value="updatePackage">
                    
                    <div class="section-title">General Details</div>
                    <input type="text" id="editName" name="name" placeholder="Package Name" required>
                    <input type="number" id="editPrice" name="price" placeholder="Total Price (ZAR)" step="0.01" required>
                    <textarea id="editDescription" name="description" placeholder="Package Description" required></textarea>
                    
                    <div class="section-title">Media</div>
                    <p style="font-size: 12px; color: #ccc; margin-bottom: 10px;">Uploading new images will replace existing ones (Max 10).</p>
                    <div class="image-upload-container">
                        <label for="package-images-single" class="custom-file-upload">
                            <span class="upload-icon">📷</span>
                            Click to select New Images
                        </label>
                        <input type="file" id="package-images-single" class="hidden-upload" name="packageImages[]" accept="image/png, image/jpeg, image/jpg, image/webp" multiple>
                        <div id="image-preview-container-single" class="preview-container"></div>
                    </div>

                    <div class="section-title">Services Builder</div>
                    <p style="font-size: 12px; color: #ccc; margin-bottom: 10px;">Max 10 services.</p>
                    <div id="services-container"></div>

                    <button type="button" id="add-service-btn" class="add-service-btn">
                        + Add New Service
                    </button>
                    
                    <button type="submit" class="submit-btn" id="saveChangesBtn">Save Changes</button>
                    <button type="button" class="submit-btn" style="background: #dc3545; margin-top: 10px;" onclick="window.location.href='agentPackages.php'">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/editPackage.js"></script>
</body>
</html>