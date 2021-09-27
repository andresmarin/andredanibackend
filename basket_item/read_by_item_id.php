<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/basket_item.php';
 include_once '../token/validatetoken.php';
// instantiate database and basket_item object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$basket_item = new Basket_Item($db);

$basket_item->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$basket_item->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$basket_item->item_id = isset($_GET['item_id']) ? $_GET['item_id'] : die();
// read basket_item will be here

// query basket_item
$stmt = $basket_item->readByitem_id();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //basket_item array
    $basket_item_arr=array();
	$basket_item_arr["pageno"]=$basket_item->pageNo;
	$basket_item_arr["pagesize"]=$basket_item->no_of_records_per_page;
    $basket_item_arr["total_count"]=$basket_item->total_record_count();
    $basket_item_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $basket_item_item=array(
            
"title" => html_entity_decode($title),
"item_id" => $item_id,
"token" => $token,
"bid" => $bid
        );
 
        array_push($basket_item_arr["records"], $basket_item_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show basket_item data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "basket_item found","document"=> $basket_item_arr));
    
}else{
 // no basket_item found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no basket_item found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No basket_item found.","document"=> ""));
    
}
 


