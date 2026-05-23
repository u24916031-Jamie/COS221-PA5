<nav class="navbar">
  <div class="nav-group">
    <a href="/COS221-PA5/index.html">Home</a>
    <a href="/COS221-PA5/traveller/browsePackage.php">Browse</a>
    
    <?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] === "Travel Agency") {
        echo '<a href="/COS221-PA5-main/agent/addSinglePackage.php">Add Package</a>';
        echo '<a href="/COS221-PA5-main/agent/agentPackages.php">My Packages</a>';
    }
    ?>
  </div>
  
  <div class="nav-group">
    <span class="user-display">
        <?php echo isset($_SESSION["user_type"]) ? "Welcome, " . $_SESSION["user_type"] : "Welcome"; ?>
    </span>
    <a href="/COS221-PA5-main/traveller/logoutTraveller.php" class="logout-btn">Logout</a>
  </div>
</nav>
