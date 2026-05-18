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



}


?>