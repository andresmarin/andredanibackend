<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/purchased_item.php';
 include_once '../token/validatetoken.php';
// instantiate database and purchased_item object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$purchased_item = new Purchased_Item($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$purchased_item->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$purchased_item->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query purchased_item
$stmt = $purchased_item->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //purchased_item array
    $purchased_item_arr=array();
	$purchased_item_arr["pageno"]=$purchased_item->pageNo;
	$purchased_item_arr["pagesize"]=$purchased_item->no_of_records_per_page;
    $purchased_item_arr["total_count"]=$purchased_item->total_record_count();
    $purchased_item_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $purchased_item_item=array(
            
"title" => html_entity_decode($title),
"item_id" => $item_id,
"pid" => $pid
        );
 
        array_push($purchased_item_arr["records"], $purchased_item_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show purchased_item data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "purchased_item found","document"=> $purchased_item_arr));
    
}else{
 // no purchased_item found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no purchased_item found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No purchased_item found.","document"=> ""));
    
}
 


