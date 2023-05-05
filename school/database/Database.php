<?php
/* 
*  PDO DATABASE CLASS
*/
require_once('config/config.php');

class Database {
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;
	
	private $connection;
	private $error;
	private $stmt;
	private $dbconnected = false;
	
	//private $log;

	public function __construct() {

		// Set PDO Connection
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		$options = array (
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
		);

		// Create a new PDO instanace
		try {
			$this->connection = new PDO ($dsn, $this->user, $this->pass, $options);
			$this->dbconnected = true;

		}// Catch database message errors
		catch ( PDOException $e ) {
			//Make Directory 'logs' if not exist
			if(!scandir("../../../sms1/databaselogs")){
				mkdir('../../../sms1/databaselogs/');
			}
			date_default_timezone_set('Africa/Lagos');
			date_default_timezone_get();
			file_put_contents("../../../sms1/databaselogs/dberror.log", "Date: " . date('M j Y - G:i:sa') . " ---- Error: " . $e->getMessage().PHP_EOL, FILE_APPEND);
			//echo '<script type="text/javascript"> alert("'.$e->getMessage().'")</script>';
			die($e->getMessage());// Log and display error in the event that there is an issue connecting
		}
	}

	//Get the Error Message
	public function getError(){
		return $this->error;
	}

	public function isConnected(){
		return $this->dbconnected;
	}
	
	// Prepare statement with query
	public function query($query) {
		$this->stmt = $this->connection->prepare($query);
	}		

	// Execute the prepared statement
	public function execute(){
		return $this->stmt->execute();
	}	
	
	// Get result set as array of objects
	public function resultset(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Get record row count
	public function rowCount(){
		return $this->stmt->rowCount();
	}	


	// Get single record as object
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}	
	

	// Bind values
	public function bind($param, $value, $type = null) {
		if (is_null ($type)) {
			switch (true) {
				case is_int ($value) :
					$type = PDO::PARAM_INT;
					break;
				case is_bool ($value) :
					$type = PDO::PARAM_BOOL;
					break;
				case is_null ($value) :
					$type = PDO::PARAM_NULL;
					break;
				default :
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	//Disconnecting database
	public function Disconect(){
		$this->connection = null;
		$this->dbconnected = false;		
	}

	//TODO
	public function getLogger($user, $logMessage){
		$at = strpos($user, '@');
		
		$result = substr($user, 0, $at);
		
		if(!scandir("../Error_logs/".$result)){
			mkdir('../Error_logs/'.$result);
		}
		else{
			date_default_timezone_set('Africa/Lagos');
			date_default_timezone_get();
			//$ip = $_SERVER['REMOTE_ADDR'];
			$location = "../Error_logs/".$result.".log";
			file_put_contents($location, "Message:  (".$logMessage.") Login from ".$user." with the ip address: [ ".$_SERVER['REMOTE_ADDR']. " ] Date: " . date('M j Y - G:i:s A').PHP_EOL, FILE_APPEND);
		}
		
	}
}
