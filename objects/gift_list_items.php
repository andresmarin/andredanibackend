<?php
class Gift_List_Items{
 
    // database connection and table name
    private $conn;
    private $table_name = "gift_list_items";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $item_id;
public $gid;
    
 
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join item n on t.item_id = n.item_id  WHERE ".$where."";
		
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
	// read gift_list_items
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  n.title, t.* FROM ". $this->table_name ." t  join item n on t.item_id = n.item_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  n.title, t.* FROM ". $this->table_name ." t  join item n on t.item_id = n.item_id  WHERE t.item_id LIKE ? OR n.title LIKE ?  OR t.gid LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
	 
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
		$query = "SELECT  n.title, t.* FROM ". $this->table_name ." t  join item n on t.item_id = n.item_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  n.title, t.* FROM ". $this->table_name ." t  join item n on t.item_id = n.item_id  WHERE t.item_id = ? LIMIT 0,1";
	 
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
$this->gid = $row['gid'];
		}
		else{
		$this->item_id=null;
		}
	}

	
	
	// create gift_list_items
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET item_id=:item_id,gid=:gid";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->item_id=htmlspecialchars(strip_tags($this->item_id));
$this->gid=htmlspecialchars(strip_tags($this->gid));
	 
		// bind values
		
$stmt->bindParam(":item_id", $this->item_id);
$stmt->bindParam(":gid", $this->gid);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the gift_list_items
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET item_id=:item_id,gid=:gid WHERE item_id = :item_id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->item_id=htmlspecialchars(strip_tags($this->item_id));
$this->gid=htmlspecialchars(strip_tags($this->gid));
$this->item_id=htmlspecialchars(strip_tags($this->item_id));
	 
		// bind new values
		
$stmt->bindParam(":item_id", $this->item_id);
$stmt->bindParam(":gid", $this->gid);
$stmt->bindParam(":item_id", $this->item_id);
	 
		$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
	}
	
	// delete the gift_list_items
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
$query = "SELECT  n.title, t.* FROM ". $this->table_name ." t  join item n on t.item_id = n.item_id  WHERE t.item_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->item_id);

$stmt->execute();
return $stmt;
}

	//extra function will be generated for one to many relations
}
?>
