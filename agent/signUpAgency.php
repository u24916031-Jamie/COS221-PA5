<!doctype html>
<html>
  <head>
    <title>Travel Agency Signup</title>
    <link rel="stylesheet" href="../css/homePage.css" />
    <link rel="stylesheet" href="../css/signUp.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" /> 
  </head>

  <div class = "login-card" style = "width: 500px">
    <form id="signupForm">
      <h3>Sign Up And Become a Agent!</h3>
        <input type="hidden" name="type" value="register">
        <input type="hidden" name="user_type" value="Travel Agency">

        <input type = "email" name="email" placeholder = "Email" required>
        <input type = "password" name="password" placeholder = "Password" required>
        <input type="text" name = "cell" placeholder="Cellphone number" required>
        <input type="text" name="agency_name" placeholder="Enter agency name">
        <input type="text" name="contact_fname" placeholder="Enter contact person first name">
        <input type="text" name="contact_lname" placeholder="Enter contact person surname">
        <div id="signupError" class="form-error"></div>

        <button type="submit" class="agent-button">Sign up</button>
      </form>
      <script src = "./js/signUpAgency.js"></script>
        
    
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
      window.location.href = 'loginAgency.php';
    });
  </script>
</html>
