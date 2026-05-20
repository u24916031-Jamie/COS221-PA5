<?php



function register($data){
	$db = Database::instance();
	if ($db->emailExists($data["email"])){
		//user with email already exists
		$retdata = [
			"status" => "fail",
			"timestamp" => time(),
			"data" => [
				"reason" => "User with provided email already exists."
			]
		];


		header("HTTP/1.1 409 Conflict");
		header("Content-Type: application/json");

		echo json_encode($retdata);
		return;
	}
	// add user to DB


	if (strlen($data["password"]) <= 8){
		$retdata = [
			"status" => "fail",
			"timestamp" => time(),
			"data" => [
				"reason" => "Password must be longer than 8 characters."
			]
		];
		header("HTTP/1.1 400 Bad Request");
		header("Content-Type: application/json");
		echo json_encode($retdata);

		return;
	}


	if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/", $data["password"])){
		$retdata = [
			"status" => "fail",
			"timestamp" => time(),
			"data" => [
				"reason" => "Password must contain atleast one upper case character, one lower case character, one digit and one symbol."
			]
		];
		header("HTTP/1.1 400 Bad Request");
		header("Content-Type: application/json");
		echo json_encode($retdata);
		return;
	}


	$salt = bin2hex(random_bytes(8));
	$hashed_pass = hash('sha256', $data["email"] . $salt);

	
	$newUserData = [
		// user
		"user_type"=>$data["User_type"],
		"password_hash"=>$hashed_pass,
		"email"=>$data["email"],
		"cell"=>$data["cell"],
		"salt"=>$salt,
	// traveller
		"fname"=>(isset($data["fname"])) ? $data["fname"] : null,
		"lname"=>(isset($data["lname"])) ? $data["lname"] : null,
		"id_number"=>(isset($data["id_number"])) ? $data["id_number"] : null,
// travel agency
		"agency_name"=>(isset($data["agency_name"])) ? $data["agency_name"] : null,
		"contact_fname"=>(isset($data["contact_fname"])) ? $data["contact_fname"] : null,
		"contact_lname"=>(isset($data["contact_lname"])) ? $data["contact_lname"] : null,
		];
	$res = $db->addUser($newUserData);
	

	header("HTTP/1.1 201 Created");
	header("Content-Type: application/json");
	$retdata = [
		"status" => "success",
		"timestamp" => time(),

	];
			
	echo json_encode($retdata);

  exit();
}



	?>