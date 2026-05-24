<nav class="navbar">
  <div class="nav-group">
    <a href="/index.html">Home</a>
    <a href="/traveller/browsePackage.php">Browse</a>
    
    <?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] === "Travel Agency") {
        echo '<a href="/agent/addSinglePackage.php">Add Package</a>';
        echo '<a href="/agent/agentPackages.php">My Packages</a>';
    }
    ?>
  </div>
  
  <div class="nav-group">
    <span class="user-display">
        <?php echo isset($_SESSION["user_type"]) ? "Welcome, " . $_SESSION["user_type"] : "Welcome"; ?>
    </span>
    <a href="./logoutTraveller.php" class="logout-btn">Logout</a>
  </div>
</nav>
