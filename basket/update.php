<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/basket.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare basket object
$basket = new Basket($db);
 
// get id of basket to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of basket to be edited
$basket->bid = $data->bid;

if(
!isEmpty($data->token)
&&!isEmpty($data->gid)
){
// set basket property values

if(!isEmpty($data->token)) { 
$basket->token = $data->token;
} else { 
$basket->token = '';
}
if(!isEmpty($data->gid)) { 
$basket->gid = $data->gid;
} else { 
$basket->gid = '';
}
$basket->uid = $data->uid;
 
// update the basket
if($basket->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the basket, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update basket","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update basket. Data is incomplete.","document"=> ""));
}
?>
