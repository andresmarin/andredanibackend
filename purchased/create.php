<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
// get database connection
include_once '../config/database.php';
 
// instantiate purchased object
include_once '../objects/purchased.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$purchased = new Purchased($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->gid)){
 
    // set purchased property values
	 
if(!isEmpty($data->gid)) { 
$purchased->gid = $data->gid;
} else { 
$purchased->gid = '';
}
$purchased->uid = $data->uid;
$purchased->name = $data->name;
$purchased->email = $data->email;
$purchased->message = $data->message;
 	$lastInsertedId=$purchased->create();
    // create the purchased
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the purchased, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create purchased","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create purchased. Data is incomplete.","document"=> ""));
}
?>
