<?php
class Item{
 
    // database connection and table name
    private $conn;
    private $table_name = "item";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $item_id;
public $title;
public $description;
public $price;
public $quantity_needed;
public $unlimited;
public $favorite;
public $visible;
public $purchased;
public $cid;
    
 
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join category l on t.cid = l.cid  WHERE ".$where."";
		
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
	// read item
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  l.name, t.* FROM ". $this->table_name ." t  join category l on t.cid = l.cid order by favorite desc LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  l.name, t.* FROM ". $this->table_name ." t  join category l on t.cid = l.cid  WHERE t.item_id LIKE ? OR t.title LIKE ?  OR t.description LIKE ?  OR t.price LIKE ?  OR t.quantity_needed LIKE ?  OR t.unlimited LIKE ?  OR t.favorite LIKE ?  OR t.visible LIKE ?  OR t.purchased LIKE ?  OR t.cid LIKE ?  OR l.name LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$stmt->bindParam(8, $searchKey);
$stmt->bindParam(9, $searchKey);
$stmt->bindParam(10, $searchKey);
$stmt->bindParam(11, $searchKey);
	 
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
		$query = "SELECT  l.name, t.* FROM ". $this->table_name ." t  join category l on t.cid = l.cid  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  l.name, t.* FROM ". $this->table_name ." t  join category l on t.cid = l.cid  WHERE t.item_id = ? LIMIT 0,1";
	 
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
$this->description = $row['description'];
$this->price = $row['price'];
$this->quantity_needed = $row['quantity_needed'];
$this->unlimited = $row['unlimited'];
$this->favorite = $row['favorite'];
$this->visible = $row['visible'];
$this->purchased = $row['purchased'];
$this->cid = $row['cid'];
$this->name = $row['name'];
		}
		else{
		$this->item_id=null;
		}
	}

	
	
	// create item
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET title=:title,description=:description,price=:price,quantity_needed=:quantity_needed,unlimited=:unlimited,favorite=:favorite,visible=:visible,purchased=:purchased,cid=:cid";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->title=htmlspecialchars(strip_tags($this->title));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->price=htmlspecialchars(strip_tags($this->price));
$this->quantity_needed=htmlspecialchars(strip_tags($this->quantity_needed));
$this->unlimited=htmlspecialchars(strip_tags($this->unlimited));
$this->favorite=htmlspecialchars(strip_tags($this->favorite));
$this->visible=htmlspecialchars(strip_tags($this->visible));
$this->purchased=htmlspecialchars(strip_tags($this->purchased));
$this->cid=htmlspecialchars(strip_tags($this->cid));
	 
		// bind values
		
$stmt->bindParam(":title", $this->title);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":price", $this->price);
$stmt->bindParam(":quantity_needed", $this->quantity_needed);
$stmt->bindParam(":unlimited", $this->unlimited);
$stmt->bindParam(":favorite", $this->favorite);
$stmt->bindParam(":visible", $this->visible);
$stmt->bindParam(":purchased", $this->purchased);
$stmt->bindParam(":cid", $this->cid);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the item
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET title=:title,description=:description,price=:price,quantity_needed=:quantity_needed,unlimited=:unlimited,favorite=:favorite,visible=:visible,purchased=:purchased,cid=:cid WHERE item_id = :item_id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->title=htmlspecialchars(strip_tags($this->title));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->price=htmlspecialchars(strip_tags($this->price));
$this->quantity_needed=htmlspecialchars(strip_tags($this->quantity_needed));
$this->unlimited=htmlspecialchars(strip_tags($this->unlimited));
$this->favorite=htmlspecialchars(strip_tags($this->favorite));
$this->visible=htmlspecialchars(strip_tags($this->visible));
$this->purchased=htmlspecialchars(strip_tags($this->purchased));
$this->cid=htmlspecialchars(strip_tags($this->cid));
$this->item_id=htmlspecialchars(strip_tags($this->item_id));
	 
		// bind new values
		
$stmt->bindParam(":title", $this->title);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":price", $this->price);
$stmt->bindParam(":quantity_needed", $this->quantity_needed);
$stmt->bindParam(":unlimited", $this->unlimited);
$stmt->bindParam(":favorite", $this->favorite);
$stmt->bindParam(":visible", $this->visible);
$stmt->bindParam(":purchased", $this->purchased);
$stmt->bindParam(":cid", $this->cid);
$stmt->bindParam(":item_id", $this->item_id);
	 
		$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
	}
	
	// delete the item
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

	
function readBycid(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  l.name, t.* FROM ". $this->table_name ." t  join category l on t.cid = l.cid  WHERE t.cid = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->cid);

$stmt->execute();
return $stmt;
}

	//extra function will be generated for one to many relations
}
?>
