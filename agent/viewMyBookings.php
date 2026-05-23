<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agency Bookings | Tripistry</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/agentView.css" />
</head>
<body>
    <?php include '../navbar.php'; ?>
    <div class="dashboard">
        <h2>Traveller Bookings</h2>
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