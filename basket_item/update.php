<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/basket_item.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare basket_item object
$basket_item = new Basket_Item($db);
 
// get id of basket_item to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of basket_item to be edited
$basket_item->item_id = $data->item_id;

if(
!isEmpty($data->item_id)
&&!isEmpty($data->bid)
){
// set basket_item property values

if(!isEmpty($data->item_id)) { 
$basket_item->item_id = $data->item_id;
} else { 
$basket_item->item_id = '';
}
if(!isEmpty($data->bid)) { 
$basket_item->bid = $data->bid;
} else { 
$basket_item->bid = '';
}
 
// update the basket_item
if($basket_item->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the basket_item, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update basket_item","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update basket_item. Data is incomplete.","document"=> ""));
}
?>
