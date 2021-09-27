<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/purchased_item.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare purchased_item object
$purchased_item = new Purchased_Item($db);
 
// set ID property of record to read
$purchased_item->item_id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of purchased_item to be edited
$purchased_item->readOne();
 
if($purchased_item->item_id!=null){
    // create array
    $purchased_item_arr = array(
        
"title" => html_entity_decode($purchased_item->title),
"item_id" => $purchased_item->item_id,
"pid" => $purchased_item->pid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "purchased_item found","document"=> $purchased_item_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user purchased_item does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "purchased_item does not exist.","document"=> ""));
}
?>
