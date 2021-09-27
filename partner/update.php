<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/partner.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare partner object
$partner = new Partner($db);
 
// get id of partner to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of partner to be edited
$partner->pid = $data->pid;

if(
!isEmpty($data->name)
&&!isEmpty($data->lastname1)
){
// set partner property values

if(!isEmpty($data->name)) { 
$partner->name = $data->name;
} else { 
$partner->name = '';
}
if(!isEmpty($data->lastname1)) { 
$partner->lastname1 = $data->lastname1;
} else { 
$partner->lastname1 = '';
}
$partner->lastname2 = $data->lastname2;
$partner->email = $data->email;
 
// update the partner
if($partner->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the partner, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update partner","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update partner. Data is incomplete.","document"=> ""));
}
?>
