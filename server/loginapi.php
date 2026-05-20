<?php
require_once "database.php";


function login($data){
	$db = Database::instance();
	
	if (!$db->loginUser($data->email, $data->password)){
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
		return;
	}
	// login


	// user_id, user_type, password_hash, email, salt, cell

	// if user_type == "Traveller"
	// fname, lname, id_number

	//if user_type == "Travel Agency"
	// agency_name, contact_fname, contact_lname, target_id
	$ret = $db->getUserData($data->email);
	$_SESSION["loggedin"] = true;
	$_SESSION["user_id"] = $ret["user_id"];
	$_SESSION["user_type"] = $ret["user_type"];
	$_SESSION["email"] =  $ret["email"];
	$_SESSION["cell"] = $ret["cell"];

	// if user_type == "Traveller"
	$_SESSION["fname"] = (isset($ret["fname"])) ? $ret["fname"]: null;
	$_SESSION["lname"] = (isset($ret["lname"])) ? $ret["lname"]: null;
	$_SESSION["id_number"] = (isset($ret["id_number"])) ? $ret["id_number"]: null;

	// if user_type == "Travel Agency"
	$_SESSION["agency_name"] = (isset($ret["agency_name"])) ? $ret["agency_name"]: null;
	$_SESSION["contact_fname"] = (isset($ret["contact_fname"])) ? $ret["contact_fname"]: null;
	$_SESSION["contact_lname"] = (isset($ret["contact_lname"])) ? $ret["contact_lname"]: null;
	$_SESSION["target_id"] = (isset($ret["target_id"])) ? $ret["target_id"]: null;

	
	// header("HTTP/1.1 200 OK");
	// header("Content-Type: application/json");
	// $retdata = [
	// 	"status" => "success",
	// 	"timestamp" => time(),
	// ];
			
	// echo json_encode($retdata);
  if($ret["user_type"] == "Travel Agency"){
    header("Location: ../client/agentView.html");
  }else if($ret["user_type"] == "Traveller"){
    header("Location: ../traveller/browsePackage.php");
  }
  exit();
}


	?>