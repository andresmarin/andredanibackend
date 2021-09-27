<?php
class Wedding{
 
    // database connection and table name
    private $conn;
    private $table_name = "wedding";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $wid;
public $partner_a;
public $partner_b;
public $date;
public $uid;
    
 
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  WHERE ".$where."";
		
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
	// read wedding
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  q.name, z.email, t.* FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  q.name, z.email, t.* FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  WHERE t.wid LIKE ? OR t.partner_a LIKE ?  OR q.name LIKE ?  OR t.partner_b LIKE ?  OR t.date LIKE ?  OR t.uid LIKE ?  OR z.email LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
$stmt->bindParam(7, $searchKey);
	 
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
		$query = "SELECT  q.name, z.email, t.* FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  q.name, z.email, t.* FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  WHERE t.wid = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// bind id
		$stmt->bindParam(1, $this->wid);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		 $num = $stmt->rowCount();
		if($num>0){
		// set values to object properties
		
$this->wid = $row['wid'];
$this->partner_a = $row['partner_a'];
$this->name = $row['name'];
$this->partner_b = $row['partner_b'];
$this->date = $row['date'];
$this->uid = $row['uid'];
$this->email = $row['email'];
		}
		else{
		$this->wid=null;
		}
	}

	
	
	// create wedding
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET partner_a=:partner_a,partner_b=:partner_b,date=:date,uid=:uid";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->partner_a=htmlspecialchars(strip_tags($this->partner_a));
$this->partner_b=htmlspecialchars(strip_tags($this->partner_b));
$this->date=htmlspecialchars(strip_tags($this->date));
$this->uid=htmlspecialchars(strip_tags($this->uid));
	 
		// bind values
		
$stmt->bindParam(":partner_a", $this->partner_a);
$stmt->bindParam(":partner_b", $this->partner_b);
$stmt->bindParam(":date", $this->date);
$stmt->bindParam(":uid", $this->uid);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the wedding
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET partner_a=:partner_a,partner_b=:partner_b,date=:date,uid=:uid WHERE wid = :wid";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->partner_a=htmlspecialchars(strip_tags($this->partner_a));
$this->partner_b=htmlspecialchars(strip_tags($this->partner_b));
$this->date=htmlspecialchars(strip_tags($this->date));
$this->uid=htmlspecialchars(strip_tags($this->uid));
$this->wid=htmlspecialchars(strip_tags($this->wid));
	 
		// bind new values
		
$stmt->bindParam(":partner_a", $this->partner_a);
$stmt->bindParam(":partner_b", $this->partner_b);
$stmt->bindParam(":date", $this->date);
$stmt->bindParam(":uid", $this->uid);
$stmt->bindParam(":wid", $this->wid);
	 
		$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
	}
	
	// delete the wedding
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE wid = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->wid=htmlspecialchars(strip_tags($this->wid));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->wid);
	 $stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
function readBypartner_a(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  q.name, z.email, t.* FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  WHERE t.partner_a = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->partner_a);

$stmt->execute();
return $stmt;
}

function readBypartner_b(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  q.name, z.email, t.* FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  WHERE t.partner_b = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->partner_b);

$stmt->execute();
return $stmt;
}

function readByuid(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  q.name, z.email, t.* FROM ". $this->table_name ." t  join partner q on t.partner_a = q.pid  join user z on t.uid = z.uid  WHERE t.uid = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->uid);

$stmt->execute();
return $stmt;
}

	//extra function will be generated for one to many relations
}
?>
