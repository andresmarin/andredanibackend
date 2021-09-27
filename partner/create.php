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
 
// instantiate partner object
include_once '../objects/partner.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$partner = new Partner($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->name)
&&!isEmpty($data->lastname1)){
 
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
 	$lastInsertedId=$partner->create();
    // create the partner
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the partner, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create partner","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create partner. Data is incomplete.","document"=> ""));
}
?>
