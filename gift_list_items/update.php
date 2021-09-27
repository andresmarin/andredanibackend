<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/gift_list_items.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare gift_list_items object
$gift_list_items = new Gift_List_Items($db);
 
// get id of gift_list_items to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of gift_list_items to be edited
$gift_list_items->item_id = $data->item_id;

if(
!isEmpty($data->item_id)
&&!isEmpty($data->gid)
){
// set gift_list_items property values

if(!isEmpty($data->item_id)) { 
$gift_list_items->item_id = $data->item_id;
} else { 
$gift_list_items->item_id = '';
}
if(!isEmpty($data->gid)) { 
$gift_list_items->gid = $data->gid;
} else { 
$gift_list_items->gid = '';
}
 
// update the gift_list_items
if($gift_list_items->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the gift_list_items, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update gift_list_items","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update gift_list_items. Data is incomplete.","document"=> ""));
}
?>
