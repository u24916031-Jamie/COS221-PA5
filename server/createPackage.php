<?php

function createPackage($data){

	if (!isset($_SESSION["user_id"])){
				header('HTTP/1.1 401 Unauthorized');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Must be logged in to create a package'
		];
				
		echo json_encode($retdata);
		return;
	}
	if ($_SESSION["user_type"]!= "Travel Agency"){
		header('HTTP/1.1 401 Unauthorized');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Only a travel agency may make a package'
		];
				
		echo json_encode($retdata);
		return;
	}

	if (!isset($data["name"]) || !isset($data["price"]) || !isset($data["description"])){

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

	if ($data["price"] < 0){
		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'price cannot be negative'
		];
				
		echo json_encode($retdata);
		return;
	}

	$db = Database::instance();

	$db->createPackage($data);



	header('HTTP/1.1 200 Ok');
	header('Content-Type: application/json');
	$retdata = [
		'status' => 'success'
	];
			
	echo json_encode($retdata);


}


?>