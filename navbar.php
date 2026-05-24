<nav class="navbar">
  <div class="nav-group">
    <a href="/index.html">Home</a>
    
         
    <?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
         
    $userType = $_SESSION["User_type"] ?? $_SESSION["user_type"] ?? null;
    
    if ($userType === "Travel Agency") {
        echo '<a href="/agent/addSinglePackage.php">Add Package</a>';
        echo '<a href="/agent/agentPackages.php">My Packages</a>';
        echo '<a href="/agent/viewMyBookings.php">Bookings</a>'; 
    } elseif ($userType === "Traveller") {
        echo '<a href="/traveller/myBookings.php">My Bookings</a>';
        echo '<a href="/traveller/browsePackage.php">Browse</a>';
    }
    ?>
  </div>
  <div class="nav-group">
    <span class="user-display">
        <?php echo isset($_SESSION["user_type"]) ? "Welcome, " . $_SESSION["fname"] : "Welcome"; ?>
    </span>
	<?php
	if (isset($_SESSION["user_type"])){
		if ($_SESSION["user_type"] == "Traveller"){
			echo '<a href="./logoutTraveller.php" class="logout-btn">Logout</a>';
		}else {
			echo '<a href="./logoutAgency.php" class="logout-btn">Logout</a>';
		}
	}
    


	?>
  </div>
</nav>
