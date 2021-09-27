<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/item.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare item object
$item = new Item($db);
 
// set ID property of record to read
$item->item_id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of item to be edited
$item->readOne();
 
if($item->item_id!=null){
    // create array
    $item_arr = array(
        
"item_id" => $item->item_id,
"title" => html_entity_decode($item->title),
"description" => $item->description,
"price" => $item->price,
"quantity_needed" => $item->quantity_needed,
"unlimited" => $item->unlimited,
"favorite" => $item->favorite,
"visible" => $item->visible,
"purchased" => $item->purchased,
"name" => $item->name,
"cid" => $item->cid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "item found","document"=> $item_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user item does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "item does not exist.","document"=> ""));
}
?>
