<?php
class Basket_Item{
 
    // database connection and table name
    private $conn;
    private $table_name = "basket_item";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $item_id;
public $bid;
    
 
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join item p on t.item_id = p.item_id  join basket w on t.bid = w.bid  WHERE ".$where."";
		
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
	// read basket_item
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  p.title, w.token, t.* FROM ". $this->table_name ." t  join item p on t.item_id = p.item_id  join basket w on t.bid = w.bid  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  p.title, w.token, t.* FROM ". $this->table_name ." t  join item p on t.item_id = p.item_id  join basket w on t.bid = w.bid  WHERE t.item_id LIKE ? OR p.title LIKE ?  OR t.bid LIKE ?  OR w.token LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
	 
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
		$query = "SELECT  p.title, w.token, t.* FROM ". $this->table_name ." t  join item p on t.item_id = p.item_id  join basket w on t.bid = w.bid  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  p.title, w.token, t.* FROM ". $this->table_name ." t  join item p on t.item_id = p.item_id  join basket w on t.bid = w.bid  WHERE t.item_id = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// bind id
		$stmt->bindParam(1, $this->item_id);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		 $num = $stmt->rowCount();
		if($num>0){
		// set values to object properties
		
$this->item_id = $row['item_id'];
$this->title = $row['title'];
$this->bid = $row['bid'];
$this->token = $row['token'];
		}
		else{
		$this->item_id=null;
		}
	}

	
	
	// create basket_item
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET item_id=:item_id,bid=:bid";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->item_id=htmlspecialchars(strip_tags($this->item_id));
$this->bid=htmlspecialchars(strip_tags($this->bid));
	 
		// bind values
		
$stmt->bindParam(":item_id", $this->item_id);
$stmt->bindParam(":bid", $this->bid);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the basket_item
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET item_id=:item_id,bid=:bid WHERE item_id = :item_id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->item_id=htmlspecialchars(strip_tags($this->item_id));
$this->bid=htmlspecialchars(strip_tags($this->bid));
$this->item_id=htmlspecialchars(strip_tags($this->item_id));
	 
		// bind new values
		
$stmt->bindParam(":item_id", $this->item_id);
$stmt->bindParam(":bid", $this->bid);
$stmt->bindParam(":item_id", $this->item_id);
	 
		$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
	}
	
	// delete the basket_item
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE item_id = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->item_id=htmlspecialchars(strip_tags($this->item_id));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->item_id);
	 $stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
function readByitem_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  p.title, w.token, t.* FROM ". $this->table_name ." t  join item p on t.item_id = p.item_id  join basket w on t.bid = w.bid  WHERE t.item_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->item_id);

$stmt->execute();
return $stmt;
}

function readBybid(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  p.title, w.token, t.* FROM ". $this->table_name ." t  join item p on t.item_id = p.item_id  join basket w on t.bid = w.bid  WHERE t.bid = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->bid);

$stmt->execute();
return $stmt;
}

	//extra function will be generated for one to many relations
}
?>
