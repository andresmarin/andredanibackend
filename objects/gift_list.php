<?php
class Gift_List{
 
    // database connection and table name
    private $conn;
    private $table_name = "gift_list";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $gid;
public $custom_url;
public $is_preview;
public $salutation;
public $wid;
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

	function total_record_count() {
	$query = "select count(1) as total from ". $this->table_name ."";
	$stmt = $this->conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['total'];
	}
	
	function search_record_count($columnArray,$orAnd){
		
		$where="";
		
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
			$columnLogic=$col->columnLogic;
			if($where==""){
				$where="LOWER(t.".$columnName . ") ".$columnLogic." :".$columnName;
			}else{
				$where=$where." ". $orAnd ." LOWER(t." . $columnName . ") ".$columnLogic." :".$columnName;
			}
		}
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  WHERE ".$where."";
		
		$stmt = $this->conn->prepare($query);
		$paramCount=1;
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
		if(strtoupper($col->columnLogic)=="LIKE"){
		$columnValue="%".strtolower($col->columnValue)."%";
		}else{
		$columnValue=strtolower($col->columnValue);
		}
			
			$stmt->bindValue(":".$columnName, $columnValue);
			$paramCount++;
			
		}
		
		$stmt->execute();
		return $stmt;
	}
	// read gift_list
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  t.* FROM ". $this->table_name ." t  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	//Search table
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 

		// select all query
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.gid LIKE ? OR t.custom_url LIKE ?  OR t.is_preview LIKE ?  OR t.salutation LIKE ?  OR t.wid LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	function searchByColumn($columnArray,$orAnd){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$where="";
		
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
			$columnLogic=$col->columnLogic;
			if($where==""){
				$where="LOWER(t.".$columnName . ") ".$columnLogic." :".$columnName;
			}else{
				$where=$where." ". $orAnd ." LOWER(t." . $columnName . ") ".$columnLogic." :".$columnName;
			}
		}
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
		$stmt = $this->conn->prepare($query);
		$paramCount=1;
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
		if(strtoupper($col->columnLogic)=="LIKE"){
		$columnValue="%".strtolower($col->columnValue)."%";
		}else{
		$columnValue=strtolower($col->columnValue);
		}
			
			$stmt->bindValue(":".$columnName, $columnValue);
			$paramCount++;
			
		}
		
		$stmt->execute();
		return $stmt;
	}
	
	

	function readOne(){
	 
		// query to read single record
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.gid = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// bind id
		$stmt->bindParam(1, $this->gid);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		 $num = $stmt->rowCount();
		if($num>0){
		// set values to object properties
		
$this->gid = $row['gid'];
$this->custom_url = $row['custom_url'];
$this->is_preview = $row['is_preview'];
$this->salutation = $row['salutation'];
$this->wid = $row['wid'];
		}
		else{
		$this->gid=null;
		}
	}

	
	
	// create gift_list
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET custom_url=:custom_url,is_preview=:is_preview,salutation=:salutation,wid=:wid";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->custom_url=htmlspecialchars(strip_tags($this->custom_url));
$this->is_preview=htmlspecialchars(strip_tags($this->is_preview));
$this->salutation=htmlspecialchars(strip_tags($this->salutation));
$this->wid=htmlspecialchars(strip_tags($this->wid));
	 
		// bind values
		
$stmt->bindParam(":custom_url", $this->custom_url);
$stmt->bindParam(":is_preview", $this->is_preview);
$stmt->bindParam(":salutation", $this->salutation);
$stmt->bindParam(":wid", $this->wid);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the gift_list
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET custom_url=:custom_url,is_preview=:is_preview,salutation=:salutation,wid=:wid WHERE gid = :gid";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->custom_url=htmlspecialchars(strip_tags($this->custom_url));
$this->is_preview=htmlspecialchars(strip_tags($this->is_preview));
$this->salutation=htmlspecialchars(strip_tags($this->salutation));
$this->wid=htmlspecialchars(strip_tags($this->wid));
$this->gid=htmlspecialchars(strip_tags($this->gid));
	 
		// bind new values
		
$stmt->bindParam(":custom_url", $this->custom_url);
$stmt->bindParam(":is_preview", $this->is_preview);
$stmt->bindParam(":salutation", $this->salutation);
$stmt->bindParam(":wid", $this->wid);
$stmt->bindParam(":gid", $this->gid);
	 
		$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
	}
	
	// delete the gift_list
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE gid = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->gid=htmlspecialchars(strip_tags($this->gid));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->gid);
	 $stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
	//extra function will be generated for one to many relations
}
?>
