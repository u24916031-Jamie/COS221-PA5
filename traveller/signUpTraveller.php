<?php
	 if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
	if (isset($_SESSION["user_type"])){
		if ($_SESSION["user_type"] == "Travel Agency"){
			header("Location: ../agent/agentPackages.php");
			exit;
		}else{
			header("Location: ../traveller/browsePackage.php");
			exit;
		}
	}	
?>


<!doctype html>
<html>
  <head>
    <title>Traveller Signup</title>
    <link rel="stylesheet" href="../css/homePage.css" />
    <link rel="stylesheet" href="../css/signUp.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" /> 
  </head>

  <div class = "login-card" style = "width: 500px">
    <form id="signupForm">
      <h3>Sign Up</h3>
        <input type="hidden" name="type" value="register">
        <input type="hidden" name="user_type" value="Traveller">

        <input type = "email" name="email" placeholder = "Email" required>
        <input type = "password" name="password" placeholder = "Password" required>
        <input type="text" name = "cell" placeholder="Cellphone number" required>
        <input type="text" name="fname" placeholder="Enter your first name" required>
        <input type="text" name="lname" placeholder="Enter your surname" required>
        <input type="text" name="id_number" placeholder="Enter your id number" required>
        <div id="signupError" class="form-error"></div>
      <button type="submit" class="agent-button">Sign up</button>
      <p>Already have an account?<a href = "loginTraveller.php"> Sign in </a></p>
    </form>
  </div>
  <script>
    const signupForm = document.getElementById('signupForm');
    const signupError = document.getElementById('signupError');
    signupForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      signupError.textContent = '';
      const formData = new FormData(signupForm);
      const data = Object.fromEntries(formData.entries());
      const response = await fetch('../api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await response.json();
      if (!response.ok) {
        signupError.textContent = result.data?.reason || 'Signup failed. Please try again.';
        return;
      }
      window.location.href = 'loginTraveller.php';
    });
  </script>
</html>
