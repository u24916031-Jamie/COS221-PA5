<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agency Bookings | Tripistry</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/agentView.css" />
    <link rel="stylesheet" href="../css/addPackageSingle.css" />
</head>
<body>
    <?php include '../navbar.php'; ?>
    <div class="dashboard">
        <h2>Traveller Bookings</h2>
        <div class="page-actions" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
            <button type="button" class="submit-btn" style="width:auto; margin:0; padding:12px 22px;" onclick="window.location.href='agentPackages.php'">View My Packages</button>
            <button type="button" class="submit-btn" style="width:auto; margin:0; padding:12px 22px;" onclick="window.location.href='addSinglePackage.php'">+ Add New Package</button>
            <button type="button" class="submit-btn" style="width:auto; margin:0; padding:12px 22px; background:#dc3545;" onclick="window.location.href='../index.html'">Back to Home</button>
        </div>
       <table style="width:100%; border-collapse: collapse; background: white; padding: 20px;">
    <thead>
        <tr style="text-align:left; border-bottom: 2px solid #ddd;">
            <th style="padding: 10px;">Traveller</th>
            <th style="padding: 10px;">Email</th>
            <th style="padding: 10px;">Package Booked</th>
            <th style="padding: 10px;">Total Cost (ZAR)</th>
            <th style="padding: 10px;">Dates</th>
            <th style="padding: 10px;">Group Trip</th>
            <th style="padding: 10px;">Guests</th>
        </tr>
    </thead>
    <tbody id="bookingsTableBody">
    </tbody>
</table>
    </div>
    <script src="js/viewMyBookings.js"></script>
</body>
</html>