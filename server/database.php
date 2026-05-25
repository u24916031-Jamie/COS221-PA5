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
		$stmt = $this->conn->prepare('INSERT INTO user (User_id, User_type, Password_hash, Email, Cell, salt) VALUES (NULL, ?, ?, ?, ?, ?)');
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
				$stmt = $this->conn->prepare('INSERT INTO traveller (User_id, Fname, Lname,Id_number) VALUES (?, ?, ?, ?)');
				$stmt->bind_param('ssss', $user_id, $fname, $lname, $id_number);
				
				$user_id = $user_id;
				$fname = $userData['fname'];
				$lname = $userData['lname'];
				$id_number = $userData['id_number'];

				$ret = $stmt->execute();
				$stmt->close();


			}else if ($user_type == "Travel Agency"){
				$stmt = $this->conn->prepare('INSERT INTO review_target (Target_id, Target_type) VALUES (NULL, ?)');
        $fillInType = "Travel Agency";
				$stmt->bind_param('s', $fillInType);
				$ret = $stmt->execute();
				$stmt->close();
				$target_id = $this->conn->insert_id;


				
				$stmt = $this->conn->prepare('INSERT INTO travel_agency (User_id, Agency_name, Contact_Fname,Contact_Lname, Target_id) VALUES (?, ?, ?, ?, ?)');
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
		$stmt = $this->conn->prepare('SELECT * 
			FROM user
			LEFT JOIN traveller USING (user_id)
			LEFT JOIN travel_agency USING (user_id)
			WHERE email = ?');
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

	public function review($params) {
        $user_id = $_SESSION["user_id"];
        $target_id = $params['target_id'];
		/*
		var_dump($user_id);
		var_dump($target_id);
        $stmt = $this->conn->prepare("
            SELECT B.end_date 
            FROM books B 
            JOIN package P ON B.Package_id = P.Package_id 
            WHERE B.User_id = ? AND P.Target_id = ? 
            ORDER BY B.end_date DESC LIMIT 1
        ");
        $stmt->bind_param('ii', $user_id, $target_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
		var_dump($res);
		var_dump(strtotime($res['end_date']));
		var_dump(strtotime('today'));
        if (!$res || strtotime($res['end_date']) >= strtotime('today')) {
            return false;
        }
		*/

        $stmt = $this->conn->prepare('INSERT INTO review (review_id, rating, comment, date, user_id, target_id) VALUES (NULL, ?, ?, ?, ?, ?)');
        $rating = $params['rating'];
        $comment = $params['comment'];
        $date = $params['date'];

        $stmt->bind_param('sssss', $rating, $comment, $date, $user_id, $target_id);
        $ret = $stmt->execute();
        $stmt->close();
        
        return $ret;
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

       $sql = "SELECT p.Package_id, p.User_id as Agency_id, p.Name, p.Price, p.Description, 
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
	
	public function getPackagesByAgency($agency_id) {
		$stmt = $this->conn->prepare("
			SELECT P.Package_id, P.Name, P.Price, P.Description,
				(SELECT Image FROM package_images PI WHERE PI.Package_id = P.Package_id LIMIT 1) AS Image
			FROM package P
			WHERE P.User_id = ?
		");
		$stmt->bind_param('i', $agency_id);
		$stmt->execute();
		$result = $stmt->get_result();
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
        foreach ($params["services"] as $svc) 
			{
            $stmt = $this->conn->prepare("INSERT INTO SERVICE (Street, City, Code, Type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $svc['street'], $svc['city'], $svc['code'], $svc['type']);
            $stmt->execute();
            $service_id = $this->conn->insert_id;
            $stmt->close();

                $type = strtolower($svc['type']);
                if ($type === 'accommodation' || $type === 'attraction' || $type === 'restaurant') {
                    $table = strtoupper($type);
                    $stmt = $this->conn->prepare("INSERT INTO $table (Service_id, Name) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['name']);
                    $stmt->execute(); 
                    $stmt->close();
                } elseif ($type === 'flight') {
                    $stmt = $this->conn->prepare("INSERT INTO FLIGHT (Service_id, Flight_number) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['flight_number']);
                    $stmt->execute(); 
                    $stmt->close();
                } elseif ($type === 'destination') {
                    $stmt = $this->conn->prepare("INSERT INTO DESTINATION (Service_id, Description) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['description']);
                    $stmt->execute(); 
                    $stmt->close();
                }

                $stmt = $this->conn->prepare("INSERT INTO INCLUDES (Package_id, Service_id) VALUES (?, ?)");
				$stmt->bind_param('ii', $package_id, $service_id);
				$stmt->execute();
				$stmt->close();
            }
        }
        return true;
    }

	public function bookPackage($params) {
		// Check for unique booking: User + Package + Start Date
		$stmt = $this->conn->prepare('SELECT 1 FROM books WHERE User_id = ? AND Package_id = ? AND start_date = ?');
		$stmt->bind_param('iis', $_SESSION["user_id"], $params["package_id"], $params["start_date"]);
		$stmt->execute();
		if($stmt->get_result()->fetch_assoc()){
			$stmt->close();
			return false;
		}
		$stmt->close();

		$trip_id = null;
		$guests = (int)$params['guests'];
		if ($guests > 1) {
			$stmt = $this->conn->prepare("INSERT INTO group_trip (Package_id, Departure_date, Capacity) VALUES (?, ?, ?)");
			$stmt->bind_param('isi', $params['package_id'], $params['start_date'], $guests);
			$stmt->execute();
			$trip_id = $this->conn->insert_id;
			$stmt->close();
		}

		$code_id = null;
		if (!empty($params["code_name"])){
			$stmt = $this->conn->prepare('SELECT Code_id FROM promo_code WHERE Code_name = ?');
			$stmt->bind_param('s', $params["code_name"]);
			$stmt->execute();
			$res = $stmt->get_result()->fetch_assoc();
			if ($res) $code_id = (int)$res["Code_id"];
			$stmt->close();
		}

		$stmt = $this->conn->prepare('INSERT INTO books (User_id, Package_id, Code_id, Trip_id, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)');
		$stmt->bind_param('iiiiss', $_SESSION["user_id"], $params["package_id"], $code_id, $trip_id, $params['start_date'], $params['end_date']);
		$ret = $stmt->execute();
		$stmt->close();
		return $ret;
	}
	public function getAccommodation($service_id){
		$stmt = $this->conn->prepare('SELECT s.street, s.city, s.code, s.type, a.name
		FROM service s 
		JOIN Accommodation a ON s.service_id = a.service_id
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
		$stmt = $this->conn->prepare('SELECT p.Name as name, p.Price as price, p.Description as description, p.Target_id as target_id, ta.Target_id as agency_target_id, ta.Agency_name as agency_name, ta.User_id as agency_id  
		FROM package p LEFT JOIN travel_agency ta ON p.User_id = ta.User_id WHERE p.Package_id=?');
		$stmt->bind_param('i', $package_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$packageInfo = $result->fetch_assoc();
		$stmt->close();

		$stmt = $this->conn->prepare('SELECT Image FROM package_images WHERE Package_id = ?');
		$stmt->bind_param('i', $package_id);
		$stmt->execute();
		$imgResult = $stmt->get_result();
		$images = [];
		while($row = $imgResult->fetch_assoc()) {
			$images[] = $row['Image'];
		}
		$stmt->close();

		$stmt = $this->conn->prepare('SELECT i.Service_id as service_id, s.Type as type, s.Street as street, s.City as city, s.Code as code,
			acc.Name as acc_name, 
			attr.Name as attr_name,
			rest.Name as rest_name,
			fli.Flight_number as flight_number, 
			dest.Description as dest_desc 
			FROM includes i  
			JOIN service s ON i.Service_id = s.Service_id 
			LEFT JOIN accommodation acc ON s.Service_id = acc.Service_id 
			LEFT JOIN attraction attr ON s.Service_id = attr.Service_id 
			LEFT JOIN restaurant rest ON s.Service_id = rest.Service_id 
			LEFT JOIN flight fli ON s.Service_id = fli.Service_id 
			LEFT JOIN destination dest ON s.Service_id = dest.Service_id 
			WHERE i.Package_id=?');
		$stmt->bind_param('i', $package_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$services = [];
		while($val = $result->fetch_assoc()){
			$serviceData = [
				'type' => $val['type'],
				'street' => $val['street'],
				'city' => $val['city'],
				'code' => $val['code'],
				'name' => $val['acc_name'] ?? $val['attr_name'] ?? $val['rest_name'] ?? $val['flight_number'] ?? $val['dest_desc']
			];
			$services[] = $serviceData;
		}
		$stmt->close();
		return ["packageInfo" => $packageInfo, "services" => $services, "images" => $images];
	}
	
	public function updatePackage($params) 
	{
        $package_id = $params['package_id'];
        $user_id = $_SESSION['user_id'];
        
        $stmt = $this->conn->prepare('UPDATE package SET name=?, price=?, description=? WHERE package_id=? AND user_id=?');
        $stmt->bind_param('sdsii', $params["name"], $params["price"], $params["description"], $package_id, $user_id);
        $ret = $stmt->execute();
        $stmt->close();
        if (!$ret) return false;

        $stmt = $this->conn->prepare('DELETE FROM package_images WHERE package_id = ?');
        $stmt->bind_param('i', $package_id);
        $stmt->execute();
        $stmt->close();

        if (!empty($params["existing_images"])) 
		{
            $this->addImagesToPackage($params["existing_images"], $package_id);
        }
        if (!empty($params["images"])) 
		{
            $this->addImagesToPackage($params["images"], $package_id);
        }

        if (isset($params["services"])) 
		{
            $stmt = $this->conn->prepare('SELECT service_id FROM includes WHERE package_id = ?');
            $stmt->bind_param('i', $package_id);
            $stmt->execute();
            $servicesResult = $stmt->get_result();
            $stmt->close();

            $stmt = $this->conn->prepare('DELETE FROM includes WHERE package_id = ?');
            $stmt->bind_param('i', $package_id);
            $stmt->execute();
            $stmt->close();
            
            while($row = $servicesResult->fetch_assoc()) {
                $sid = $row['service_id'];
                $this->conn->query("DELETE FROM accommodation WHERE service_id = $sid");
                $this->conn->query("DELETE FROM attraction WHERE service_id = $sid");
                $this->conn->query("DELETE FROM restaurant WHERE service_id = $sid");
                $this->conn->query("DELETE FROM flight WHERE service_id = $sid");
                $this->conn->query("DELETE FROM destination WHERE service_id = $sid");
                $this->conn->query("DELETE FROM service WHERE service_id = $sid");
            }

            foreach ($params["services"] as $svc) 
			{
                if(empty($svc['type'])) continue; 
                
                $stmt = $this->conn->prepare("INSERT INTO SERVICE (Street, City, Code, Type) VALUES (?, ?, ?, ?)");
                $street = $svc['street'] ?? 'N/A';
                $city = $svc['city'] ?? 'N/A';
                $code = $svc['code'] ?? '0000';
				
                $stmt->bind_param('ssss', $street, $city, $code, $svc['type']);
                $stmt->execute();
                $service_id = $this->conn->insert_id;
                $stmt->close();

                $type = strtolower($svc['type']);
                if ($type === 'accommodation' || $type === 'attraction' || $type === 'restaurant') 
				{
                    $table = strtoupper($type);
                    $stmt = $this->conn->prepare("INSERT INTO $table (Service_id, Name) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['name']);
                    $stmt->execute(); 
                    $stmt->close();
                } 
				elseif ($type === 'flight') 
				{
                    $stmt = $this->conn->prepare("INSERT INTO FLIGHT (Service_id, Flight_number) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['flight_number']);
                    $stmt->execute(); 
                    $stmt->close();
                } 
				elseif ($type === 'destination') 
				{
                    $stmt = $this->conn->prepare("INSERT INTO DESTINATION (Service_id, Description) VALUES (?, ?)");
                    $stmt->bind_param('is', $service_id, $svc['description']);
                    $stmt->execute(); 
                    $stmt->close();
                }

                $stmt = $this->conn->prepare("INSERT INTO INCLUDES (Package_id, Service_id) VALUES (?, ?)");
                $stmt->bind_param('ii', $package_id, $service_id);
                $stmt->execute();
                $stmt->close();
            }
        }
        return true;
    }
	public function deletePackage($package_id, $user_id) 
	{
        $stmt = $this->conn->prepare('SELECT target_id FROM package WHERE package_id = ? AND user_id = ?');
        $stmt->bind_param('ii', $package_id, $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($res == null) 
		{
            return false;
        }
        $target_id = $res['target_id'];

        $stmt = $this->conn->prepare('DELETE FROM books WHERE package_id = ?');
        $stmt->bind_param('i', $package_id);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $this->conn->prepare('DELETE FROM group_trip WHERE package_id = ?');
        $stmt->bind_param('i', $package_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare('DELETE FROM package_images WHERE package_id = ?');
        $stmt->bind_param('i', $package_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare('SELECT service_id FROM includes WHERE package_id = ?');
        $stmt->bind_param('i', $package_id);
        $stmt->execute();
        $servicesResult = $stmt->get_result();
        $stmt->close();
        
        $stmt = $this->conn->prepare('DELETE FROM includes WHERE package_id = ?');
        $stmt->bind_param('i', $package_id);
        $stmt->execute();
        $stmt->close();
        
        while($row = $servicesResult->fetch_assoc()) 
		{
            $sid = $row['service_id'];
            $this->conn->query("DELETE FROM accommodation WHERE service_id = $sid");
            $this->conn->query("DELETE FROM attraction WHERE service_id = $sid");
            $this->conn->query("DELETE FROM restaurant WHERE service_id = $sid");
            $this->conn->query("DELETE FROM flight WHERE service_id = $sid");
            $this->conn->query("DELETE FROM destination WHERE service_id = $sid");
            $this->conn->query("DELETE FROM service WHERE service_id = $sid");
        }

        if ($target_id) 
		{
            $stmt = $this->conn->prepare('DELETE FROM review WHERE target_id = ?');
            $stmt->bind_param('i', $target_id);
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $this->conn->prepare('DELETE FROM package WHERE package_id = ?');
        $stmt->bind_param('i', $package_id);
        $ret = $stmt->execute();
        $stmt->close();

        if ($target_id) 
		{
            $stmt = $this->conn->prepare('DELETE FROM review_target WHERE target_id = ?');
            $stmt->bind_param('i', $target_id);
            $stmt->execute();
            $stmt->close();
        }
        
        return $ret;
    }
	public function getAgentPackages($user_id) {
        $stmt = $this->conn->prepare("SELECT P.Package_id, P.Name, P.Price, P.Description, (SELECT Image FROM PACKAGE_IMAGES PI WHERE PI.Package_id = P.Package_id LIMIT 1) AS Image FROM PACKAGE P WHERE P.User_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $ret = [];
        while($val = $result->fetch_assoc()) {
            $ret[] = $val;
        }
        return $ret;
    }

    public function getAgencyBookings($agency_id) {
        $stmt = $this->conn->prepare("
            SELECT P.Name as PackageName, U.Email, T.Fname, T.Lname, P.Price, B.start_date, B.end_date, 
                   (CASE WHEN B.Trip_id IS NOT NULL THEN 'Yes' ELSE 'No' END) as IsGroupTrip,
                   IFNULL(GT.Capacity, 1) as Guests,
                   (P.Price * IFNULL(GT.Capacity, 1)) as TotalPrice
            FROM books B 
            JOIN package P ON B.Package_id = P.Package_id 
            JOIN user U ON B.User_id = U.User_id 
            JOIN traveller T ON B.User_id = T.User_id 
            LEFT JOIN group_trip GT ON B.Trip_id = GT.Trip_id
            WHERE P.User_id = ?
        ");
        $stmt->bind_param('i', $agency_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ret = [];
        while($val = $result->fetch_assoc()){ $ret[] = $val; }
        $stmt->close();
        return $ret;
    }

    public function getMyBookings($user_id) {
        $stmt = $this->conn->prepare("
            SELECT P.Package_id, P.Name, P.Price, P.Description, P.Target_id, B.start_date, B.end_date, 
                   IFNULL(GT.Capacity, 1) as Guests,
                   (P.Price * IFNULL(GT.Capacity, 1)) as TotalPrice,
                   (SELECT Image FROM package_images PI WHERE PI.Package_id = P.Package_id LIMIT 1) AS Image 
            FROM books B 
            JOIN package P ON B.Package_id = P.Package_id 
            LEFT JOIN group_trip GT ON B.Trip_id = GT.Trip_id
            WHERE B.User_id = ?
        ");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ret = [];
        while($val = $result->fetch_assoc()){ $ret[] = $val; }
        $stmt->close();
        return $ret;
    }

	public function getAgencyDetails($agency_id){
		// should always be a valid email
		$stmt = $this->conn->prepare('SELECT * FROM user U JOIN travel_agency A ON U.user_id = A.user_id WHERE U.user_id=?');
		$stmt->bind_param('s', $agency_id);

		$stmt->execute();
		$result = $stmt->get_result();
	
	
		return $result->fetch_assoc();
	}
	public function getAgencyRating($agency_id){
		// should always be a valid email
		$stmt = $this->conn->prepare('SELECT IFNULL(AVG(R.Rating), 0) AS rating FROM review R JOIN travel_agency A ON R.target_id = A.target_id WHERE A.user_id=? GROUP BY R.target_id');
		$stmt->bind_param('s', $agency_id);

		$stmt->execute();
		$result = $stmt->get_result();
	
	
		return $result->fetch_assoc();
	}
}
?>
