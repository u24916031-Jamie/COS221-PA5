<?php
function viewTravelAgency($data) {
    $db = Database::instance();
    $agency_id = $data['agency_id'] ?? null;
    if (!$agency_id) {
        echo json_encode(['status' => 'error', 'data' => 'No Agency ID']);
        return;
    }
    $info = $db->getAgencyDetails($agency_id);
    $pkgs = $db->getPackagesByAgency($agency_id);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => ['info' => $info, 'packages' => $pkgs]]);
    exit();
}
?>