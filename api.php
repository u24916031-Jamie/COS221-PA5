<?php

	session_start();
	
	require_once("./server/database.php");
	require_once("./server/login.php");
	require_once("./server/logout.php");
	require_once("./server/register.php");
	require_once("./server/searchServices.php");
	require_once("./server/searchPackages.php");
	require_once("./server/review.php");
	require_once("./server/createPackage.php");
	require_once("./server/getPackage.php");
	require_once("./server/bookPackage.php");
	require_once("./server/viewPackage.php");
	require_once("./server/getAgentPackages.php");
	require_once("./server/deletePackage.php");
	require_once("./server/updatePackage.php");
	
	$db = Database::instance();
	$method = $_SERVER["REQUEST_METHOD"];
	
	
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
		if (isset($data["type"])){
			switch ($data["type"]){
			
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
			case "createPackage":
				createPackage($data);
				break;

			case "getPackage":
				getPackage($data);
				break;
			case "bookPackage":
				bookPackage($data);
				break;
			case "viewPackage":
				viewPackage($data);
				break;
			case "getAgentPackages":
				getAgentPackages($data);
				break;
			case "deletePackage":
				deletePackage($data);
				break;
			case "updatePackage":
				updatePackage($data);
				break;



				case "viewTravelAgency":


				case "createGroupTrip":


				
				
				
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
