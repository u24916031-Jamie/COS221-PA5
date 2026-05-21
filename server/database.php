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
		$env = parse_ini_file('.env'); // create a .env file
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
        $fillInType = "Travel Agency";
				$stmt->bind_param('s', $fillInType);
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
		$stmt = $this->conn->prepare('SELECT 1 FROM user WHERE Email=?');
    $email = $userEmail;
		$stmt->bind_param('s', $email);
		

		$stmt->execute();
		$result = $stmt->get_result();

    $exists = ($result->fetch_assoc() !== null);
    $stmt->close();


		return $exists;

		
	}
	public function tripExists($trip_id, $package_id){
		$stmt = $this->conn->prepare('SELECT 1 FROM group_trip WHERE trip_id=? AND package_id=?');
		$stmt->bind_param('ii', $trip_id, $package_id);

		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc() != null;

		
	}
	public function codeExists($code_name){
		$stmt = $this->conn->prepare('SELECT 1 FROM promo_code WHERE code_name=?');
		$stmt->bind_param('s', $code_name);

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
    
		if($row==null){

			return false;
		}

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
	public function searchPackages($params) {
        $searchString = "%" . ($params["search"] ?? "") . "%";
        
        $sortParam = strtolower($params["sort"] ?? "price");
        $sortString = "P.Price";
        if ($sortParam === "rating") {
            $sortString = "Rating";
        }
        $orderParam = strtoupper($params["order"] ?? "ASC");
        $orderString = ($orderParam === "DESC") ? "DESC" : "ASC";

       $sql = "SELECT p.Package_id, p.Name, p.Price, p.Description, 
                   IFNULL(AVG(r.Rating), 0) AS Rating,
                   ta.Agency_name,
                   (SELECT pi.Image FROM package_images pi WHERE pi.Package_id = p.Package_id LIMIT 1) AS Image
            FROM package p 
            LEFT JOIN review r ON p.Target_id = r.Target_id 
            LEFT JOIN travel_agency ta ON p.User_id = ta.User_id
            LEFT JOIN includes inc ON p.Package_id = inc.Package_id
            LEFT JOIN service s ON inc.service_id = s.service_id
            LEFT JOIN destination d ON s.service_id = d.service_id
            WHERE p.Name LIKE ? 
               OR p.Description LIKE ? 
               OR s.City LIKE ?
               OR d.Description LIKE ?
               OR ta.Agency_name LIKE ?
            GROUP BY p.Package_id, p.Name, p.Price, p.Description, ta.Agency_name
            ORDER BY $sortString $orderString";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param('sssss', $searchString, $searchString, $searchString, $searchString, $searchString);
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
        $stmt->close();
        return $ret;
    }


	//made the images easier to store with forloop
    public function addImagesToPackage($savedFiles, $package_id){
        if (empty($savedFiles)){
            return;
        }
        
        $stmt = $this->conn->prepare('INSERT INTO PACKAGE_IMAGES (Package_id, Image) VALUES (?, ?)');
        foreach ($savedFiles as $img) {
            $stmt->bind_param('is', $package_id, $img);
            $stmt->execute();
        }
        $stmt->close();
    }

	public function createPackage($params){
        $stmt = $this->conn->prepare('INSERT INTO REVIEW_TARGET (Target_Type) VALUES (?)');
        $type = "Package";
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $target_id = $this->conn->insert_id;
        $stmt->close();

        $stmt = $this->conn->prepare('INSERT INTO PACKAGE (Name, Price, Description, User_id, Target_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sdssi', $params["name"], $params["price"], $params["description"], $_SESSION["user_id"], $target_id);
        $stmt->execute();
        $package_id = $this->conn->insert_id;
        $stmt->close();

        if (!empty($params["images"])) {
            $this->addImagesToPackage($params["images"], $package_id);
        }

        if (!empty($params["services"])) {
            foreach ($params["services"] as $svc) {
                $stmt = $this->conn->prepare("INSERT INTO SERVICE (Street, City, Code) VALUES (?, ?, ?)");
                $street = $svc['street'] ?? 'N/A';
                $city = $svc['city'] ?? 'N/A';
                $code = $svc['code'] ?? '0000';
                $stmt->bind_param('sss', $street, $city, $code);
                $stmt->execute();
                $service_id = $this->conn->insert_id;
                $stmt->close();

                $type = strtolower($svc['type']);
                if ($type === 'accommodation' || $type === 'attraction' || $type === 'restaurant') {
                    $table = strtoupper($type);
                    $stmt = $this->conn->prepare("INSERT INTO $table (service_id, Name) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['name']);
                    $stmt->execute(); 
                    $stmt->close();
                } elseif ($type === 'flight') {
                    $stmt = $this->conn->prepare("INSERT INTO FLIGHT (service_id, Flight_number) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['flight_number']);
                    $stmt->execute(); 
                    $stmt->close();
                } elseif ($type === 'destination') {
                    $stmt = $this->conn->prepare("INSERT INTO DESTINATION (service_id, Description) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['description']);
                    $stmt->execute(); 
                    $stmt->close();
                }

                $stmt = $this->conn->prepare("INSERT INTO INCLUDES (Package_id, service_id, type) VALUES (?, ?, ?)");
                $stmt->bind_param('iis', $package_id, $service_id, $svc['type']);
                $stmt->execute();
                $stmt->close();
            }
        }
        return true;
    }

	public function bookPackage($params){
		$code_id = null;
		$trip_id = null;
		if (isset($params["trip_id"])){
			$trip_id = $params["trip_id"];
		}
		if (isset($params["code_name"])){
			$stmt = $this->conn->prepare('SELECT code_id FROM promo_code WHERE code_name=?');
			$stmt->bind_param('s', $params["code_name"]);
			$stmt->execute();
			$result = $stmt->get_result();
			$code_id = ($result->fetch_assoc())["code_id"];
			$stmt->close();
		}

		$stmt = $this->conn->prepare('INSERT INTO books (user_id, package_id, code_id, trip_id) VALUES (?, ?, ?, ?)');
		$stmt->bind_param('iiii', $_SESSION["user_id"], $params["package_id"], $code_id, $trip_id);
		$ret = $stmt->execute();
		$stmt->close();

	}

	public function getAccomodation($service_id){
		$stmt = $this->conn->prepare('SELECT s.street, s.city, s.code, s.type, a.name
		FROM service s 
		JOIN accomodation a ON s.service_id = a.service_id
		WHERE s.service_id=?');
		$stmt->bind_param('i', $service_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function getAttraction($service_id){
		$stmt = $this->conn->prepare('SELECT s.street, s.city, s.code, s.type, a.name
		FROM service s 
		JOIN attraction a ON s.service_id = a.service_id
		WHERE s.service_id=?');
		$stmt->bind_param('i', $service_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function getDestination($service_id){
	$stmt = $this->conn->prepare('SELECT s.street, s.city, s.code, s.type, d.description
		FROM service s 
		JOIN destination d ON s.service_id = d.service_id
		WHERE s.service_id=?');
		$stmt->bind_param('i', $service_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function getRestaurant($service_id){
		$stmt = $this->conn->prepare('SELECT s.street, s.city, s.code, r.name
		FROM service s 
		JOIN restaurant r ON s.service_id = r.service_id
		WHERE s.service_id=?');
		$stmt->bind_param('i', $service_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function getFlight($service_id){
		$stmt = $this->conn->prepare('SELECT s.street, s.city, s.code, s.type, f.flight_number
		FROM service s 
		JOIN flight f ON s.service_id = f.service_id
		WHERE s.service_id=?');
		$stmt->bind_param('i', $service_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}


	public function getPackage($package_id){
		$stmt = $this->conn->prepare('SELECT p.name, p.price, p.description, p.target_id, ta.agency_name 
		FROM package p
		LEFT JOIN travel_agency ta ON p.user_id = ta.user_id
		WHERE p.package_id=?');
		$stmt->bind_param('i', $package_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$packageInfo = $result->fetch_assoc();

		$stmt = $this->conn->prepare('SELECT Image FROM PACKAGE_IMAGES WHERE Package_id = ?');
        $stmt->bind_param('i', $package_id);
        $stmt->execute();
        $imgResult = $stmt->get_result();
        $images = [];
        while($row = $imgResult->fetch_assoc()) {
            $images[] = $row['Image'];
        }
        $stmt->close();

		$stmt = $this->conn->prepare('SELECT i.service_id, i.type
		FROM includes i 
		JOIN service s ON i.service_id = s.service_id
		WHERE i.package_id=?');

		$stmt->bind_param('i', $package_id);
		$stmt->execute();
		$result = $stmt->get_result();

		$services = [];

		while($val = $result->fetch_assoc()){
            switch(strtolower($val["type"])){
                case("accommodation"): 
                    $services[] = $this->getAccomodation($val["service_id"]);
                    break;
                case("attraction"):   
                    $services[] = $this->getAttraction($val["service_id"]);
                    break;
                case("destination"):  
                    $services[] = $this->getDestination($val["service_id"]);
                    break;
                case("flight"):      
                    $services[] = $this->getFlight($val["service_id"]);
                    break;
                case("restaurant"): 
                    $services[] = $this->getRestaurant($val["service_id"]);
                    break;
            }
        }

		$ret = [
			"packageInfo" => $packageInfo,
			"services" => $services,
			"images" => $images
		];
		return $ret;

	}
	
// edit package
// delete package
// create group trip
}


?>
