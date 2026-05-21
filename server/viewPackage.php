<?php
function viewPackage($data) {
    $db = Database::instance();
    
    $package_id = $data['package_id'] ?? null;
    
    if (!$package_id) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'No package ID provided']);
        return;
    }
    
    $result = $db->getPackage($package_id);
    
    header('Content-Type: application/json');
    if ($result) {
        echo json_encode(['status' => 'success', 'data' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'data' => 'Package not found']);
    }
}
?>