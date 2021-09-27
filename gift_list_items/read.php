<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/gift_list_items.php';
 include_once '../token/validatetoken.php';
// instantiate database and gift_list_items object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$gift_list_items = new Gift_List_Items($db);

$gift_list_items->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$gift_list_items->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read gift_list_items will be here

// query gift_list_items
$stmt = $gift_list_items->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //gift_list_items array
    $gift_list_items_arr=array();
	$gift_list_items_arr["pageno"]=$gift_list_items->pageNo;
	$gift_list_items_arr["pagesize"]=$gift_list_items->no_of_records_per_page;
    $gift_list_items_arr["total_count"]=$gift_list_items->total_record_count();
    $gift_list_items_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $gift_list_items_item=array(
            
"title" => html_entity_decode($title),
"item_id" => $item_id,
"gid" => $gid
        );
 
        array_push($gift_list_items_arr["records"], $gift_list_items_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show gift_list_items data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "gift_list_items found","document"=> $gift_list_items_arr));
    
}else{
 // no gift_list_items found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no gift_list_items found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No gift_list_items found.","document"=> ""));
    
}
 


