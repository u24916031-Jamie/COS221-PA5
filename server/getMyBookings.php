<?php
function getMyBookings($data) {

    if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "Traveller") {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Unauthorized']);
        return;
    }

    $db = Database::instance();
    $bookings = $db->getMyBookings($_SESSION["user_id"]);
    
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $bookings]);
    exit();
}
?>