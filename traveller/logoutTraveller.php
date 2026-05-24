<?php
session_start();
// Security check: If they aren't logged in, send them straight back to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: loginTraveller.php");
    exit();
}
?>
<!doctype html>
<html>
<head>
    <title>Logout - Tripistry</title>
    <link rel="stylesheet" href="../css/homePage.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" />
    <style>
        /* Small localized styles just for the confirmation presentation box */
        .logout-card {
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: 100px auto;
        }
        .btn-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }
        .confirm-btn { background-color: #d9534f; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;}
        .cancel-btn { background-color: #f0ad4e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;}
    </style>
</head>
<body>
    <div class="page">
        <div class="logout-card">
            <h3>Are you sure you want to log out?</h3>
            <p>You will need to re-enter your credentials to access your account again.</p>
            
            <div class="btn-container">
                <form id="logoutForm">
                    <input type="hidden" name="type" value="logout">
                    <button type="submit" class="confirm-btn">Yes, Logout</button>
                </form>
                <a href="browsePackage.php" class="cancel-btn">Cancel</a>
            </div>
        </div>
    </div>

    <script src="./js/logoutTraveller.js"></script>
</body>
</html>