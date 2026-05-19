<?php
function saveFiles($package_id){

	if (!isset($_FILES['images'])) {
		http_response_code(400);
		echo json_encode(['error' => 'No files uploaded.']);
		exit;
	}

	$uploadedFiles = $_FILES['images'];
	$uploadDir = '../img/uploads/';

	if (!is_dir($uploadDir)) {
		mkdir($uploadDir, 0755, true);
	}

	$savedFiles = [];
	$errors = [];

	foreach ($uploadedFiles['name'] as $index => $name) {
		$tmpName  = $uploadedFiles['tmp_name'][$index];
		$error    = $uploadedFiles['error'][$index];
		$size     = $uploadedFiles['size'][$index];
		
		if ($error !== UPLOAD_ERR_OK) {
			$errors[] = "File '$name' failed to upload with error code $error.";
			continue;
		}

		$fileInfo = getimagesize($tmpName);
		if ($fileInfo === false) {
			$errors[] = "File '$name' is not a valid image.";
			continue;
		}

		$safeName = time() . '_' . basename($name);
		$targetPath = $uploadDir . $safeName;

		if (move_uploaded_file($tmpName, $targetPath)) {
			$savedFiles[] = $targetPath;
		} else {
			$errors[] = "Could not save file '$name'.";
		}
	}
	
	$db = Database::instance();
	$db->addImagesToPackage($savedFiles, $package_id);

	if (count($savedFiles) > 0) {
		header('HTTP/1.1 200 OK');
		header('Content-Type: application/json');
		$retdata = [
			'status' => "success",
			'data' => count($savedFiles) . ' image(s) uploaded successfully.',
			'files' => $savedFiles,
			'errors' => $errors
		];
		echo json_encode($retdata);
	} else {
		header('HTTP/1.1 500 Internal Server Error');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'No images were successfully saved.',
			'errors' => $errors
		];
		echo json_encode($retdata);
	}
}

?>