<?php

function viewPackage($data){
	$db = Database::instance();

	if(!isset($data["package_id"])){
		$retdata = [
			"status" => "fail",
			"timestamp" => time(),
			"data" => [
				"reason" => "No package_id provided"
			]
		];
		header("HTTP/1.1 400 Bad Request");
		header("Content-Type: application/json");

		echo json_encode($retdata);
		return;
	}


	$ret = $db->viewPackage($data);


	
	header('HTTP/1.1 200 Ok');
	header('Content-Type: application/json');
	$retdata = [
		'status' => 'success',
		'data' => $ret
	];
			
	echo json_encode($retdata);


}


?>