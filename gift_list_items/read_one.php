<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/gift_list_items.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare gift_list_items object
$gift_list_items = new Gift_List_Items($db);
 
// set ID property of record to read
$gift_list_items->item_id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of gift_list_items to be edited
$gift_list_items->readOne();
 
if($gift_list_items->item_id!=null){
    // create array
    $gift_list_items_arr = array(
        
"title" => html_entity_decode($gift_list_items->title),
"item_id" => $gift_list_items->item_id,
"gid" => $gift_list_items->gid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "gift_list_items found","document"=> $gift_list_items_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user gift_list_items does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "gift_list_items does not exist.","document"=> ""));
}
?>
