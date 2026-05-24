<?php
//require_once "database.php";


function login($data){
	$db = Database::instance();

  $email = isset($data['email'])?$data['email']:'';
  $password = isset($data['password'])?$data['password']:'';
	
	if (!$db->loginUser($email, $password)){
		// email / password not matching
		$retdata = [
			"status" => "fail",
			"timestamp" => time(),
			"data" => [
				"reason" => "Invalid login credentials provided"
			]
		];


		header("HTTP/1.1 409 Conflict");
		header("Content-Type: application/json");

		echo json_encode($retdata);
		exit();
	}
	//get details to populate session once user logged in

	$ret = $db->getUserData($email);
	if (isset($data['user_type']) && $data['user_type'] !== $ret['User_type']) {
		$retdata = [
			"status" => "fail",
			"timestamp" => time(),
			"data" => [
				"reason" => "This login page is for {$data['user_type']} accounts only."
			]
		];
		header("HTTP/1.1 403 Forbidden");
		header("Content-Type: application/json");
		echo json_encode($retdata);
		return;
	}

	$_SESSION["email"] =  $ret["Email"];
	$_SESSION["cell"] = $ret["Cell"];

	// if user_type == "Traveller"
	$_SESSION["fname"] = (isset($ret["Fname"])) ? $ret["Fname"]: null;
	$_SESSION["lname"] = (isset($ret["Lname"])) ? $ret["Lname"]: null;
	$_SESSION["id_number"] = (isset($ret["Id_number"])) ? $ret["Id_number"]: null;

	// if user_type == "Travel Agency"
	$_SESSION["agency_name"] = (isset($ret["Agency_name"])) ? $ret["Agency_name"]: null;
	$_SESSION["contact_fname"] = (isset($ret["Contact_Fname"])) ? $ret["Contact_Fname"]: null;
	$_SESSION["contact_lname"] = (isset($ret["Contact_Lname"])) ? $ret["Contact_Lname"]: null;
	$_SESSION["target_id"] = (isset($ret["Target_id"])) ? $ret["Target_id"]: null;

	//redirect based on type
  
  
	header("HTTP/1.1 200 Ok");
	header("Content-Type: application/json");
	$retdata = [
		"status" => "success",
		"timestamp" => time(),

	];
			
	echo json_encode($retdata);


  exit();
}

?>