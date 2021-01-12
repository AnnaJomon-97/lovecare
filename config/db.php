<?php
/**
 * Class for database operations
 */
class Dboperations
{
	public $conn;
	function __construct()
	{ 
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "furnitureshop_db";
		$this->conn = new mysqli(DB_HOST,DB_USER, DB_PASSWORD,DB_NAME);
		if ($this->conn->connect_error) {
		  die("Connection failed: " . $this->conn->connect_error);
		}
	}
	/**
	 * Function to execute SQL query
	*/
	function execute($sql=""){
		$result = $this->conn->query($sql);
		if ( $result === FALSE) {
			$this->db_error($sql);
		}
		return $result;
	}
	/**
	 * Function to execute SQL query and return result as array
	*/
	function execute_get($sql=""){
		$result_array = [];
		$result = $this->conn->query($sql);
		if ( $result === FALSE) {
			$this->db_error($sql);
		}else{
			while ($row = $result->fetch_assoc()) {
			    $result_array[] = $row;
			}
		}
		return $result_array;		
	}
	/**
	 * Function to execute SQL query and return single result as object
	*/
	function execute_get_row($sql=""){
		$result_row = null;
		$result = $this->conn->query($sql);
		if ( $result === FALSE) {
			$this->db_error($sql);
		}else{
			$result_row = $result->fetch_object();
		}
		return $result_row;		
	}
	/**
	 * Function to get the records from the table as array by passing table name
	*/
	function get( $table ="" ,$condition =[],$order =[] ){
		$sql = "SELECT *  FROM `".$table."` WHERE 1";
		if(is_array($condition) ){
			foreach ($condition as $filed => $value) {
				$sql .= " AND ".$filed." = '".$value."' ";
			}
		}
		if(is_array($order) ){
			$order_by = [];
			foreach ($order as $filed => $value) {
				$order_by[] = " ORDER BY ".$filed." = '".$value."' ";
			}
			$order_by = implode(", ", $order_by);
			$sql .= $order_by;
		}	
		$result_array = [];
		$result = $this->conn->query($sql);
		if ( $result === FALSE) {
			$this->db_error($sql);

		}else{
			while ($row = $result->fetch_assoc()) {
			    $result_array[] = $row;
			}
		}
		return $result_array;	
	}
	/**
	 * Function to get single record as object by passing table name
	*/
	function get_row( $table ="" ,$condition= [] ){
		$sql = "SELECT *  FROM `".$table."` WHERE 1";
		if(is_array($condition) ){
			foreach ($condition as $filed => $value) {
				$sql .= " AND ".$filed." = '".$value."' ";
			}
		}
		$result_row = null;
		$result = $this->conn->query($sql);
		if ( $result === FALSE) {
			$this->db_error($sql);
		}else{
			$result_row = $result->fetch_object();
		}
		return $result_row;	
	}
	/**
	 * Function to get single colum value by condition
	*/
	function get_colum_value(  $table ,$colum ,$condition= [] ){
		$sql = "SELECT `".$colum."`  FROM `".$table."` WHERE 1";
		if(is_array($condition) ){
			foreach ($condition as $filed => $value) {
				$sql .= " AND ".$filed." = '".$value."' ";
			}
		}
		$colum_value = "";
		$result = $this->conn->query($sql);
		if ( $result === FALSE) {
			$this->db_error($sql);
		}else{
			$result_row = $result->fetch_object();
			if ( $result_row  ){
				$colum_value = $result_row->$colum;
			}
		}
		return $colum_value;	
	}


	/**
	 * Function to delete record
	*/
	function delete( $table ="" ,$condition =[],$order =[] ){
		$sql = "DELETE  FROM `".$table."` WHERE 1";
		if(is_array($condition) ){
			foreach ($condition as $filed => $value) {
				$sql .= " AND ".$filed." = '".$value."' ";
			}
		}
		$result = $this->conn->query($sql);
		if ( $result === FALSE) {
			$this->db_error($sql);
		}
		return $result;
	}
	/**
	 * Function to get id of last inserted record
	*/
	function get_insert_id(){
		return $this->conn->insert_id;
	}

	/**
	 * get record count
	*/

	function get_count( $table ="" ,$condition =[] ){
		$sql = "SELECT COUNT(*) AS rec_count FROM `".$table."` WHERE 1";
		if(is_array($condition) ){
			foreach ($condition as $filed => $value) {
				$sql .= " AND ".$filed." = '".$value."' ";
			}
		}
		$rec_count = null;
		$result = $this->conn->query($sql);
		$result_row = $result->fetch_object();
		if ( $result_row  ){
			$rec_count = $result_row->rec_count;
		}
		return $rec_count;
	}
	
	/**
	 * Function to show error
	*/

	function db_error($info =""){
		echo  $this->conn->error."<br>";
		echo '<div style="padding:20px;border:1px solid #333"><strong>'.$info.'</strong></div>';
		exit;
	}
	

	
}
$db =  new Dboperations;

?>