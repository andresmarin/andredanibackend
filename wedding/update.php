<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/wedding.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare wedding object
$wedding = new Wedding($db);
 
// get id of wedding to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of wedding to be edited
$wedding->wid = $data->wid;

if(
!isEmpty($data->partner_a)
&&!isEmpty($data->partner_b)
&&!isEmpty($data->date)
&&!isEmpty($data->uid)
){
// set wedding property values

if(!isEmpty($data->partner_a)) { 
$wedding->partner_a = $data->partner_a;
} else { 
$wedding->partner_a = '';
}
if(!isEmpty($data->partner_b)) { 
$wedding->partner_b = $data->partner_b;
} else { 
$wedding->partner_b = '';
}
if(!isEmpty($data->date)) { 
$wedding->date = $data->date;
} else { 
$wedding->date = '';
}
if(!isEmpty($data->uid)) { 
$wedding->uid = $data->uid;
} else { 
$wedding->uid = '';
}
 
// update the wedding
if($wedding->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the wedding, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update wedding","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update wedding. Data is incomplete.","document"=> ""));
}
?>
