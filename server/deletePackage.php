<?php
function deletePackage($data) {
    if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "Travel Agency") {
        echo json_encode(['status' => 'error', 'data' => 'Unauthorized']);
        return;
    }
    $db = Database::instance();
    if ($db->deletePackage($data['package_id'], $_SESSION["user_id"])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'data' => 'Failed to delete package']);
    }
    exit();
}
?>