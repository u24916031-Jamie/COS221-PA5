<?php
function updatePackage($data) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "Travel Agency") {
        echo json_encode(['status' => 'error', 'data' => 'Unauthorized']);
        return;
    }

    $db = Database::instance();
    $params = [
        "package_id" =>$data["package_id"],
        "name" => $data["name"] ?? $_POST["name"] ?? '',
        "price" => $data["price"] ?? $_POST["price"] ?? 0,
        "description" => $data["description"] ?? $_POST["description"] ?? '',
        "services" => $data["services"] ?? $_POST["services"] ?? [],
        "existing_images" => $data["existing_images"] ?? $_POST["existing_images"] ?? [],
        "images" => []
    ];

    if (isset($_FILES['packageImages'])) {
        $fileCount = count($_FILES['packageImages']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['packageImages']['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['packageImages']['tmp_name'][$i];
                $safeName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($_FILES['packageImages']['name'][$i]));
                $fileName = time() . '_' . $safeName;
                $destination = './img/' . $fileName; 

                if (move_uploaded_file($tmpName, $destination)) {
                    $params['images'][] = '../img/' . $fileName; 
                }
            }
        }
    }

    if ($db->updatePackage($params)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'data' => 'Failed to update package']);
    }
    exit();
}
?>