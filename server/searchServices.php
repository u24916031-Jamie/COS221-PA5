<?php

	

function searchServices($data){
	$db = Database::instance();

	if (!isset($data["search"]) || !isset($data["order"])){

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

	$allowed_order = ["ASC", "DESC"];
	if (!in_array($data["order"], $allowed_order)){
		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'order parameter must be ASC or DESC'
		];
				
		echo json_encode($retdata);
		return;
	}


	$ret = $db->searchServices($data);
	
	header('HTTP/1.1 200 Ok');
	header('Content-Type: application/json');
	$retdata = [
		'status' => 'success',
		'data' => $ret
	];
			
	echo json_encode($retdata);


}


?>