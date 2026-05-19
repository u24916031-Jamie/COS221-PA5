<?php


// search packages ( search[description], sort[cost, ratings], order[ASC or DESC])
function searchPackages($data){
	$db = Database::instance();


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

	$allowed_sort = ["cost", "rating"];
	if (!in_array($data["sort"], $allowed_sort)){
		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'sort parameter must be cost or rating'
		];
				
		echo json_encode($retdata);
		return;
	}



	$ret = $db->searchPackages($data);
	
	header('HTTP/1.1 200 Ok');
	header('Content-Type: application/json');
	$retdata = [
		'status' => 'success',
		'data' => $ret
	];
			
	echo json_encode($retdata);


}


?>