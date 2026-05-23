<?php
function bookPackage($data) {
    $db = Database::instance();
    if (!isset($_SESSION["user_id"])) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['status' => 'error', 'data' => 'Must be logged in']);
        return;
    }
    if (!isset($data["package_id"]) || !isset($data["start_date"]) || !isset($data["end_date"]) || !isset($data["guests"])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['status' => 'error', 'data' => 'Missing booking details']);
        return;
    }
    if ($db->bookPackage($data)) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['status' => 'success']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['status' => 'error', 'data' => 'Booking failed']);
    }
}
?>
