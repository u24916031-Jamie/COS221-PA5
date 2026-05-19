<!doctype html>
<html lang="en">
  <head>
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
          <form action="server/api.php" method="POST" enctype="multipart/form-data">
            <h3 style="text-align: center; width: 100%; margin-top: 0;">Create Single Package</h3>
            
            <div class="section-title">General Details</div>
            <input type="text" name="packageName" placeholder="Package Name" required>
            <input type="number" name="packagePrice" placeholder="Total Price (ZAR)" required>
            <textarea name="packageDescription" placeholder="Package Description" required></textarea>

            <div class="section-title">Media</div>
            <div class="image-upload-container">
              <label for="package-images-single" class="custom-file-upload">
                <span style="font-size: 24px; display: block; margin-bottom: 5px;">📷</span>
                Click to select Package Images
              </label>
              <input type="file" id="package-images-single" class="hidden-upload" name="packageImages[]" accept="image/png, image/jpeg, image/jpg, image/webp" multiple>
              <div id="image-preview-container-single" class="preview-container"></div>
            </div>

            <div class="section-title" style="margin-top: 30px;">Services Builder</div>
            <div id="services-container" style="width: 100%;"></div>

            <button type="button" id="add-service-btn" style="background: rgba(255, 255, 255, 0.2); border: 2px dashed rgba(255, 255, 255, 0.5); color: white; padding: 10px 20px; border-radius: 15px; cursor: pointer; margin: 15px auto; display: block; width: 85%; font-family: 'Inter', sans-serif; transition: 0.3s;">
              + Add New Service
            </button>

            <input type="hidden" name="type" value="addPackageSingle">

            <button type="submit" class="submit" style="margin-top: 40px; width: 85%;">Publish Single Package</button>
          </form>
        </div>
      </div>
    </div>

    <script src="../js/addPackageSingle.js"></script>
  </body>
</html>
