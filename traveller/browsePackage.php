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


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Browse</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/homePage.css" />
    <link rel="stylesheet" href="../css/browsePackages.css" />
  </head>
  <body>
    <?php include '../navbar.php'; ?>

    <div class="browse-container">
      <div class="search-section">
        <input type="search" id="locationSearch" placeholder="Where to?">
        
        <select id="sortBy" class="sort-dropdown">
          <option value="price">Sort by Price</option>
          <option value="rating">Sort by Rating</option>
        </select>
        
        <select id="sortOrder" class="sort-dropdown">
          <option value="asc">Ascending</option>
          <option value="desc">Descending</option>
        </select>

        <button class="search-btn" id="searchBtn">Search</button>
      </div>

      <div class="packages-grid" id="packagesGrid"></div>
    </div>

    <script src="./js/browsePackages.js"></script>
  </body>
</html>
