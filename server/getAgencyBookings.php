<?php
function getAgencyBookings($data) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "Travel Agency") {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['status' => 'error', 'data' => 'Unauthorized']);
        return;
    }
    $db = Database::instance();
    $bookings = $db->getAgencyBookings($_SESSION["user_id"]);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $bookings]);
    exit();
}
?>