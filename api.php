<?php

	session_start();
	
	require_once("./server/database.php");
	require_once("./server/searchServices");
	require_once("./server/searchPackages");
	require_once("./server/review");
	require_once("./server/loginapi");
	require_once("./server/logoutapi");
	require_once("./server/registerapi");
	
	$db = Database::instance();
	$method = $_SERVER["REQUEST_METHOD"];
	
	$data = null;
	
	if ($method == "POST"){

		if (!empty($_POST)) {
   			$data = $_POST;
		} else {
    		$raw_data = file_get_contents('php://input');
   			$data = json_decode($raw_data, true) ?? null;
		}
		// empty request body
		if ($data == null){
			header('HTTP/1.1 400 Bad Request');
			header('Content-Type: application/json');
			$retdata = [
				'status' => 'error',
				'timestamp' => time(),
				'data' => 'Missing request body'
			];
					
			echo json_encode($retdata);
			exit();
		}

		if (isset($data->type)){
			switch ($data->type){
			
			case "login":
				login($data);
				break;
			case "logout":
				logout();
				break;
			case "register":
				register($data);
				break;
			case "searchServices":
				searchServices($data);
				break;
			case "searchPackages":
				searchPackages($data);
				break;
			case "review":
				review($data);
				break;

			default:
			// invalid request type
				header('HTTP/1.1 400 Bad Request');
				header('Content-Type: application/json');
				$retdata = [
					'status' => 'error',
					'timestamp' => time(),
					'data' => 'Invalid request type given'
				];
						
				echo json_encode($retdata);
			}
		}
		else{
			// missing request type
			header('HTTP/1.1 400 Bad Request');
			header('Content-Type: application/json');
			$retdata = [
				'status' => 'error',
				'timestamp' => time(),
				'data' => 'Missing request type'
			];
					
			echo json_encode($retdata);
		}
		}

	else{
		// not post method
		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: application/json');
		$retdata = [
			'status' => 'error',
			'timestamp' => time(),
			'data' => 'Unsupported request method'
		];
				
		echo json_encode($retdata);
	}
								
								

    


?>