<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/item.php';
 include_once '../token/validatetoken.php';
// instantiate database and item object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$item = new Item($db);

$item->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$item->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$item->cid = isset($_GET['cid']) ? $_GET['cid'] : die();
// read item will be here

// query item
$stmt = $item->readBycid();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //item array
    $item_arr=array();
	$item_arr["pageno"]=$item->pageNo;
	$item_arr["pagesize"]=$item->no_of_records_per_page;
    $item_arr["total_count"]=$item->total_record_count();
    $item_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $item_item=array(
            
"item_id" => $item_id,
"title" => html_entity_decode($title),
"description" => $description,
"price" => $price,
"quantity_needed" => $quantity_needed,
"unlimited" => $unlimited,
"favorite" => $favorite,
"visible" => $visible,
"purchased" => $purchased,
"name" => $name,
"cid" => $cid
        );
 
        array_push($item_arr["records"], $item_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show item data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "item found","document"=> $item_arr));
    
}else{
 // no item found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no item found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No item found.","document"=> ""));
    
}
 


