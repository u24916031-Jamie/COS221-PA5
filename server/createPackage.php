<?php

function createPackage($data)
{
    if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "Travel Agency")
	{
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Unauthorized. Must be an Agency.']);
        return;
    }

    if (empty($data["name"]) || empty($data["price"]) || empty($data["description"]))
		{
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Missing required package details.']);
        return;
    }

    $uploadedImages = [];
    if (isset($_FILES['packageImages'])) 
		{
        $fileCount = count($_FILES['packageImages']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['packageImages']['error'][$i] === UPLOAD_ERR_OK) 
				{
                $tmpName = $_FILES['packageImages']['tmp_name'][$i];
                $fileName = time() . '_' . basename($_FILES['packageImages']['name'][$i]);
                $destination = './img/' . $fileName; 

                if (move_uploaded_file($tmpName, $destination)) 
					{
                    $uploadedImages[] = '../img/' . $fileName; 
                }
            }
        }
    }

    $db = Database::instance();
    $params = [
        "name" => $data["name"],
        "price" => $data["price"],
        "description" => $data["description"],
        "images" => $uploadedImages,
        "services" => $data["services"] ?? [] 
    ];

    if ($db->createPackage($params)) 
		{
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
    } else 
	{
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data' => 'Failed to save to database.']);
    }
    exit();
}
?>
