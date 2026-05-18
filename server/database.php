<?php

	$env = parse_ini_file('.env');
	$username = $env['USERNAME'];
	$password = $env['PASSWORD'];
	$dbname = $env['DBNAME'];
	class Database {
		private $conn;

		public static function instance() {
			static $instance = null;
			if($instance === null){

				$instance = new Database();
			}
			return $instance; 
		}
		private function __construct() { 
			$host = 'wheatley.cs.up.ac.za';
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$this->conn = new mysqli($host, $username, $password);
			$this->conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
			if($this->conn->connect_error){

				die('Connection failure: ' . $this->conn->connect_error);
			}
			else {
				$this->conn->select_db($dbname);
			}
		}
		public function __destruct() {
			$this->conn->close();
		}

		public function addUser($userData){
			$stmt = $this->conn->prepare('INSERT INTO user (user_id, user_type, password_hash, email, cell, salt) VALUES (NULL, ?, ?, ?, ?, ?)');
			$stmt->bind_param('sssss', $user_type, $password_hash, $email, $cell, $salt);
			
			$user_type = $userData['user_type'];
			$password_hash = $userData['password_hash'];
			$email = $userData['email'];
			$cell = $userData['cell'];
			$salt = $userData['salt'];

			$ret = $stmt->execute();
			$stmt->close();
			if ($ret){
   				$user_id = $this->conn->insert_id;

				if ($user_type == "Traveller"){
					$stmt = $this->conn->prepare('INSERT INTO traveller (user_id, fname, lname, id_number) VALUES (?, ?, ?, ?)');
					$stmt->bind_param('ssss', $user_id, $fname, $lname, $id_number);
					
					$user_id = $user_id;
					$fname = $userData['fname'];
					$lname = $userData['lname'];
					$id_number = $userData['id_number'];

					$ret = $stmt->execute();
					$stmt->close();


				}else if ($user_type == "Travel Agency"){
					$stmt = $this->conn->prepare('INSERT INTO review_target (target_id, target_type) VALUES (NULL, ?)');
					$stmt->bind_param('s', "Travel Agency");
					$ret = $stmt->execute();
					$stmt->close();
					$target_id = $this->conn->insert_id;
					
					$stmt = $this->conn->prepare('INSERT INTO travel_agency (user_id, agency_name, contact_fname,contact_lname, target_id) VALUES (?, ?, ?, ?, ?)');
					$stmt->bind_param('sssss', $user_id, $agency_name, $contact_fname, $contact_lname, $target_id);
					
					$user_id = $user_id;
					$agency_name = $userData['agency_name'];
					$contact_fname = $userData['contact_fname'];
					$contact_lname = $userData['contact_lname'];
					$target_id = $target_id;
					
					$ret = $stmt->execute();
					$stmt->close();
				}
			}
			return $ret;
		}

		public function emailExists($userEmail){
			$stmt = $this->conn->prepare('SELECT 1 FROM user WHERE email=?');
			$stmt->bind_param('s', $email);
			$email = $userEmail;

			$stmt->execute();
			$result = $stmt->get_result();
			return $result->fetch_assoc() != null;

			
		}
	public function loginUser($email, $password){
			$stmt = $this->conn->prepare('SELECT salt FROM user WHERE email=?');
			$stmt->bind_param('s', $email);

			$stmt->execute();
			$result = $stmt->get_result();
			$salt = $result->fetch_assoc()["salt"];
			if ($salt == null){
				return false;
			}

			
			$hashed_pass = hash('sha256', $password . $salt);

			$stmt = $this->conn->prepare('SELECT 1 FROM user WHERE email=? AND password_hash=?');
			$stmt->bind_param('ss', $email, $hashed_pass);

			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->fetch_assoc() == null){
				return false;
			}

			return true;
		}


		// user_id, user_type, password_hash, email, salt, cell

		// if user_type == "Traveller"
		// fname, lname, id_number

		//if user_type == "Travel Agency"
		// agency_name, contact_fname, contact_lname, target_id 
		public function getUserData($email){
			// should always be a valid email
			$stmt = $this->conn->prepare('SELECT * FROM user U LEFT JOIN traveller T ON U.user_id = T.user_id LEFT JOIN travel_agency A ON U.user_id = A.user_id WHERE U.email = ?');
			$stmt->bind_param('s', $email);

			$stmt->execute();
			$result = $stmt->get_result();
		
		
			return $result->fetch_assoc();
		}


		// params has search[city] d.g.("Durban") and sort[cost] d.g.("ASC" / "DESC")
		public function searchServices($params){
			$searchSQL = "";
			$sortSQL = "";
			if (isset($params["search"])){
				if (isset($params["search"]["city"])){
					$searchSQL = " WHERE city LIKE ?";
				}
			}
			if (isset($params["sort"])){
				if (isset($params["sort"]["cost"])){
					$sortSQL = " ORDERBY cost ?";
				}
			}


			$sql = 'SELECT * FROM service' . $searchSQL . $sortSQL;
			

			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param('ss',"%".$params["search"]["city"]."%", $params["sort"]["cost"]);


			$stmt->execute();
			$result = $stmt->get_result();
			if (!$result) {
				echo("Query failed: " . $conn->error);
			}
			$ret = [];
			while($val = $result->fetch_assoc()){
				$ret[] = $val;
			}
			return $ret;


		}

}


?>