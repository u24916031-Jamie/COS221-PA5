<?php

function bookPackage($data){
	$db = Database::instance();

	if (!isset($_SESSION["user_id"])){
		header('HTTP/1.1 401 Unauthorized');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Must be logged in to book a package'
		];
				
		echo json_encode($retdata);
		return;
	}
	if ($_SESSION["user_type"] != "Traveller"){
		header('HTTP/1.1 401 Unauthorized');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Only a traveller may book a package'
		];
				
		echo json_encode($retdata);
		return;
	}

	if (!isset($data["package_id"])){

		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Post parameters are missing'
		];
				
		echo json_encode($retdata);
		return;
	}
	if (!$db->tripExists($data["trip_id"], $data["package_id"])){
		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Invalid trip id given'
		];
				
		echo json_encode($retdata);
		return;
	}

	if (!$db->promoCodeExists($data["code_name"])){
	header('HTTP/1.1 400 Bad Request');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Invalid promo code given'
		];
				
		echo json_encode($retdata);
		return;
	}



	$db->bookPackage($data);



	header('HTTP/1.1 200 Ok');
	header('Content-Type: application/json');
	$retdata = [
		'status' => 'success'
	];
			
	echo json_encode($retdata);


}


?>