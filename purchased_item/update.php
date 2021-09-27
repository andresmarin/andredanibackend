<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/purchased_item.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare purchased_item object
$purchased_item = new Purchased_Item($db);
 
// get id of purchased_item to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of purchased_item to be edited
$purchased_item->item_id = $data->item_id;

if(
!isEmpty($data->item_id)
&&!isEmpty($data->pid)
){
// set purchased_item property values

if(!isEmpty($data->item_id)) { 
$purchased_item->item_id = $data->item_id;
} else { 
$purchased_item->item_id = '';
}
if(!isEmpty($data->pid)) { 
$purchased_item->pid = $data->pid;
} else { 
$purchased_item->pid = '';
}
 
// update the purchased_item
if($purchased_item->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the purchased_item, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update purchased_item","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update purchased_item. Data is incomplete.","document"=> ""));
}
?>
