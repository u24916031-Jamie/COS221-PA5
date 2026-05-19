<?php

function review($data){
	// 

	if (!isset($_SESSION["user_id"])){
				header('HTTP/1.1 401 Unauthorized');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Must be logged in to make a review'
		];
				
		echo json_encode($retdata);
		return;
	}

	if (!isset($data["rating"]) || !isset($data["comment"]) || !isset($data["date"]) || !isset($data["target_id"])){

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

	$db = Database::instance();

	$db->review($data);


	header('HTTP/1.1 200 Ok');
	header('Content-Type: application/json');
	$retdata = [
		'status' => 'success'
	];
			
	echo json_encode($retdata);

}



?>