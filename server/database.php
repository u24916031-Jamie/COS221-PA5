<?php


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
		$host = 'localhost'; //change host to the local or sm
    $env = parse_ini_file('.env.example');
    $username = $env['USERNAME'];
    $password = $env['PASSWORD'];
    $dbname = $env['DBNAME'];

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
    //added check for non-existing emails&users
		$row = $result->fetch_assoc();
    
    if($row==null)
      return false;

    $salt = $row["salt"];
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


	// params has search e.g.("Durban"), and order e.g.("ASC" / "DESC")
	public function searchServices($params){
		$searchSQL = "";
		$orderSQL = "";
		if (isset($params["search"])){
			$searchSQL = " WHERE city LIKE ?";
		}

		if (isset($params["order"])){
			
			$orderSQL = " ORDERBY service_id ?";
		}


		$sql = 'SELECT * FROM service' . $searchSQL . $orderSQL;
		

		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param('sss',"%".$params["search"]."%", $params["order"]);


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

	public function review($params){
		$stmt = $this->conn->prepare('INSERT INTO review (review_id, rating, comment, date, user_id, target_id) VALUES (NULL, ?, ?, ?, ?, ?)');
		$stmt->bind_param('sssss', $rating, $comment, $date, $user_id, $target_id);
		
		$rating = $params['rating'];
		$comment = $params['comment'];
		$date = $params['date'];
		$user_id = $_SESSION["user_id"];
		$target_id = $params['target_id'];

		$ret = $stmt->execute();
		$stmt->close();


	}
		//added sort on destination and package name
	public function searchPackages($params) 
	{
        $searchString = "%" . ($params["search"] ?? "") . "%";
        
        $sortParam = strtolower($params["sort"] ?? "price");
        $sortString = "P.Price";
        if ($sortParam === "rating") {
            $sortString = "Rating";
        }
        $orderParam = strtoupper($params["order"] ?? "ASC");
        $orderString = ($orderParam === "DESC") ? "DESC" : "ASC";

        $sql = "SELECT P.Package_id, P.Name, P.Price, P.Description, 
                       IFNULL(AVG(R.Rating), 0) AS Rating,
                       (SELECT Image FROM PACKAGE_IMAGES PI WHERE PI.Package_id = P.Package_id LIMIT 1) AS Image
                FROM PACKAGE P 
                LEFT JOIN REVIEW R ON P.Target_id = R.Target_id 
                LEFT JOIN INCLUDES INC ON P.Package_id = INC.Package_id
                LEFT JOIN SERVICE S ON INC.Service_id = S.Service_id
                LEFT JOIN DESTINATION D ON S.Service_id = D.Service_id
                WHERE P.Name LIKE ? 
                   OR P.Description LIKE ? 
                   OR S.City LIKE ?
                   OR D.Description LIKE ?
                GROUP BY P.Package_id, P.Name, P.Price, P.Description
                ORDER BY $sortString $orderString";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param('ssss', $searchString, $searchString, $searchString, $searchString);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return [];
        }
        $ret = [];
        while($val = $result->fetch_assoc()){
            $ret[] = $val;
        }
        return $ret;
    }


	public function addImagesToPackage($savedFiles, $package_id){
		if (count($savedFiles) == 0){
			return;
		}
		$SQL = 'INSERT INTO package_images (package_id, image) VALUES (?, ?)';

		$mask = "ss";
		$inputs = [];

		$inputs[] = $package_id;
		$inputs[] = $savedFiles[0];

		for ($i = 1;$i < count($savedFiles); $i++){
			$mask .= "ss";

			$inputs[] = $package_id;
			$inputs[] = $savedFiles[i];
			$SQL .= ", (?, ?)";			
			

		
		}


		$stmt = $this->conn->prepare($SQL);

		$stmt->bind_param($mask, ...$inputs);
		$stmt->execute();
	}

	public function createPackage($params){
		$stmt = $this->conn->prepare('INSERT INTO review_target (target_id, target_type) VALUES (NULL, ?)');
		$stmt->bind_param('s', "Package");
		$ret = $stmt->execute();
		$stmt->close();
		$target_id = $this->conn->insert_id;

		$stmt = $this->conn->prepare('INSERT INTO package(name, price, description, user_id, target_id VALUES (?, ?, ?, ?, ?)');
		$stmt->bind_param('sssss', $params["name"], $params["price"], $params["description"], $_SESSION["user_id"], $target_id);
		$ret = $stmt->execute();
		$stmt->close();
		$package_id = $this->conn->insert_id;

		$this->addImagesToPackage($params["images"], $package_id);
		

	}
// create package
// edit package
// delete package
// create group trip
}


?>
