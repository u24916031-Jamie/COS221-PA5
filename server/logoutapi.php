<?php

function logout(){



	$loggedIn = $_SESSION["loggedin"];
	if ($loggedIn){

		unset($_SESSION["loggedin"]);
		unset($_SESSION["user_id"]);
		unset($_SESSION["user_type"]);
		unset($_SESSION["email"]);
		unset($_SESSION["cell"]);
		unset($_SESSION["fname"]);
		unset($_SESSION["lname"]);
		unset($_SESSION["id_number"]);
		unset($_SESSION["agency_name"]);
		unset($_SESSION["contact_fname"]);
		unset($_SESSION["contact_lname"]);
		unset($_SESSION["target_id"]);


	}	
	session_unset();
	session_destroy();

	
	header("HTTP/1.1 200 OK");
	header("Content-Type: application/json");
	$retdata = [
		"status" => "success",
		"timestamp" => time()

	];
	echo(json_encode($retdata));
}



?>