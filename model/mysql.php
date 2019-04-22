<?php
include_once "main.php";
class Mysql{
	public $conn = null;
	public $main = null;
	function __construct(){
		$servername = $GLOBALS["DB_hostname"];
		$username = $GLOBALS['DB_username'];
		$password = $GLOBALS['DB_password'];
		$dbname = $GLOBALS['DB_dbname'];
		
		$this->conn = new mysqli($servername, $username, $password, $dbname);
		if ($this->conn->connect_error) {
    		die("Connection failed: " . $this->conn->connect_error);
		}
		$this->main = new Main();
	}
	function __destruct(){
		$this->conn->close();
	}
	function SQL_Query($type,$sql){
		if (!($this->conn->query($sql) === TRUE)){
			if($type == "INSERT"){
				$this->main->Alert("Creating record fail: " . $this->conn->error);
			}else if($type == "UPDATE"){
				$this->main->Alert("Error updating record: " . $this->conn->error);
			}else if($type == "DELETE"){
				$this->main->Alert("Error delete record: " . $this->conn->error);
			}
			return false;
		}else return true;
	}
	function userCheck($type,$account,$password=null){
		if($type == "users"){
			$sql = "SELECT uid, username, password FROM users WHERE email='".$account."' AND isActive=1";
		}else if($type == "shops"){
			$sql = "SELECT sid, name, password FROM shops WHERE sid='".$account."' AND isActive=1";
		}
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0){
			if($row = $result->fetch_assoc()){
				if(md5($password,FALSE) != $row["password"]) return -1;
				else{
					if($type == "users") return array(
						"id" => $row["uid"],
						"name" => $row["username"],
					);
					else if($type == "shops") return array(
						"id" => $row["sid"],
						"name" => $row["name"],
					);
				}
			}
		}else return false;
	}
	function getNumber($name){
		switch ($name){
			case 'users':
				$varName = 'member_last_id'; 
				$lab = 'A'; $len = 5;  break;
			case 'shops':
				$varName = 'shop_last_id'; 
				$lab = 'S'; $len = 4;  break;
			case 'products':
				$varName = 'product_last_id'; 
				$lab = 'P'; $len = 6;  break;
			default:
				$varName = null;  break;
		}
		$sql = "SELECT var FROM vars WHERE name='".$varName."'";
		$result = $this->conn->query($sql);
		$value = 0;
		if ($result->num_rows > 0){
			if($row = $result->fetch_assoc()) $value = $row["var"];
		}else $value = -1;
		if($value != -1){
			$sql = "UPDATE vars SET var =".($value+1)." WHERE name='".$varName."'";
			$this->SQL_Query("UPDATE",$sql);
			return $lab.str_pad($value,$len,'0',STR_PAD_LEFT);
		}else return null;
	} 
}
?>