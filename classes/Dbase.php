<?php

class Dbase{

    private $db         = "ptv";
    private $host       = "localhost";
    private $db_user    = "root";
    private $db_pass    = "";
    
    protected $conn_db;
    public $last_query     = null;
    public $affected_rows  = 0;
    private $insert_keys   = array();
    private $insert_values = array();
    private $update_sets   = array();


    public function __construct(){
		$this->connect();
    }
    
	
	//connect to database
	public function connect(){
        $this->conn_db = new mysqli($this->host, $this->db_user, $this->db_pass, $this->db);
		if(mysqli_connect_errno()){
			die("Couldn't Establish Secure Connection: ".mysqli_connect_error());		
		}
    }


	//return last inserted id
	public function lastId() {
		return mysqli_insert_id($this->conn_db);
    }
    

	//close database connection
    public function close(){
        if(!mysqli_close($this->conn_db)){
            die("Problems closing Databse connection");
        }
    }

    /* ===============================================================================
    | ---------------------------- VALIDATION -------------------------------------- | 
    ================================================================================= */

    //data validation/sanitization 
    function validate($data){

        $data  =  trim($data);
        $data  =  stripslashes($data);
        $data  =  htmlspecialchars($data);
        $data  =  $this->esc_data($data);

        return $data;
    }

    //ESCAPE FUNCTION
    function esc_data($id){
        return  mysqli_real_escape_string($this->conn_db, $id);
    }
    /* ===============================================================================
    | ---------------------------- DATABASE FUNCTIONS ------------------------------- | 
    ================================================================================= */

    //query databse
    public function query($sql){
		$this->last_query = $sql;
		$result = mysqli_query($this->conn_db, $sql);
		$this->displayQuery($result);
		return $result;
    }
    

	//display query error
	public function displayQuery($result){
		if(!$result){
			$output  = "Database Query Failed: ".mysqli_error($this->conn_db)."<br/>";
			$output .= "Last SQL query was: ".$this->last_query;
			die($output);
		} else {
			$this->affected_rows = mysqli_affected_rows($this->conn_db);
		}
    }
    

    //FETCH ALL RESULT
    public function fetchAll($sql){
        $output = array();
        $result = $this->query($sql);
        if($result){
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $output[] = $row;
            }
        }
        return $output;
    }


    //FETCH ONE RESULT
    public function fetchOne($sql){
        $out    = $this->fetchAll($sql);
        $output = array_shift($out);
        return $output;
    }


    // FETCH ALL FROM TABLE
    public function fetchAllData($table, $orderby){
        $query = "SELECT * FROM `$table` ORDER BY `$table`.`$orderby` DESC";
        return $this->fetchAll($query);
    }


    //FETCH DATA BY ID FROM TABLE
    public function fetchDataByField($table, $table_field, $field){
        $query  = "SELECT * FROM `$table` WHERE `$table`.`$table_field` = '{$field}' LIMIT 1";
        return $this->fetchAll($query);
    }


    // FETCH ROW
    public function fetchRows($sql){
        $result = $this->query($sql);
        return $this->num_row = mysqli_num_rows($result);
    }

	public function prepareInsert($array = null) {
		if (!empty($array)) {
			foreach($array as $key => $value) {
				$this->insert_keys[] = $key;
				$this->insert_values[] = $value;
			}
		}
	}
	
	public function insert($table = null) {
		
		if (
			!empty($table) && 
			!empty($this->insert_keys) && 
			!empty($this->insert_values)
		) {
		
			$sql  = "INSERT INTO `{$table}` (`";
			$sql .= implode("`, `", $this->insert_keys);
			$sql .= "`) VALUES ('";
			$sql .= implode("', '", $this->insert_values);
			$sql .= "')";
			
			if ($this->query($sql)) {
				$this->_id = $this->lastId();
				return true;
			}
			return false;
		
		}
		
    }
    

	public function prepareUpdate($array = null) {
		if (!empty($array)) {
			foreach($array as $key => $value) {
				$this->update_sets[] = "`{$key}` = '".$value."'";
			}
		}
    }
    
    
	public function update($table = null, $table_id = null, $id = null) {
		if (!empty($table) && !empty($id) && !empty($this->update_sets)) {
			$sql  = "UPDATE `{$table}` SET ";
			$sql .= implode(", ", $this->update_sets);
			$sql .= " WHERE `$table_id` = '".$this->esc_data($id)."'";
			return $this->query($sql);
		}
	}






}