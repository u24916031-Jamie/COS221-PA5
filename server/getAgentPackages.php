<?php
function getAgentPackages($data) {
    if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "Travel Agency") {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Unauthorized']);
        return;
    }

    $db = Database::instance();
    $packages = $db->getAgentPackages($_SESSION["user_id"]);
    
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $packages]);
    exit();
}
?>