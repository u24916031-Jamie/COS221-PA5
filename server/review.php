<?php
function review($data) {
    if (!isset($_SESSION["user_id"])){
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Must be logged in to make a review']);
        return;
    }

    if (!isset($data["rating"]) || !isset($data["comment"]) || !isset($data["date"]) || !isset($data["target_id"])){
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Post parameters are missing']);
        return;
    }

    if ($data["rating"] < 1 || $data["rating"] > 5){
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Rating must be between 1 and 5']);
        return;
    }

    $db = Database::instance();
    
    if ($db->review($data)) {
        header('HTTP/1.1 200 Ok');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
    } else {
        header('HTTP/1.1 403 Forbidden');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'You can only review a trip after the end date has passed.']);
    }
}
?>
