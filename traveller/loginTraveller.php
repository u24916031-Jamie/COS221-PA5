<?php
	 if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
	if (isset($_SESSION["user_type"])) {
		if ($_SESSION["user_type"] == "Travel Agency"){
			header("Location: ../agent/agentPackages.php");
			exit;
		} else{
			header("Location: ../traveller/browsePackage.php");
			exit;
		}
	}
		
		
		
?>


<!doctype html>
<html>
  <head>
    <title>Traveller Login</title>
    <link rel="stylesheet" href="../css/homePage.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" />
  </head>
  <body>
    <div class = "page">
      <div class = "left-section">
        <h2>TRIPISTRY TRAVELLER</h2>
      </div>
      <div class = "right-section">
        <div class = "login-card">
          <h3>Login</h3>
          <form action="../api.php" method="POST" id="loginForm">
          <input type="hidden" name="type" value="login">
          <input type="hidden" name="user_type" value="Traveller">

          <input type = "email" name="email" placeholder = "Email" required>
          <input type = "password" name="password" placeholder = "Password" required>
          <button type="submit" class="agent-button">Login</button>
          </form>
          <p>Don't have an account?<a href = "signUpTraveller.php"> Sign up </a></p>
        </div>
      </div>
    </div>
    <a href="../index.html" style="position:fixed; left:16px; bottom:16px; z-index:1000; display:inline-block; padding:10px 14px; background:#0047A3; color:#fff; border-radius:8px; text-decoration:none; font-size:0.95rem;">Back to Home</a>
  </body>
  <script src="./js/loginTraveller.js"></script>
</html>
