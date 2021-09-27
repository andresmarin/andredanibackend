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
 
// instantiate wedding object
include_once '../objects/wedding.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$wedding = new Wedding($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->partner_a)
&&!isEmpty($data->partner_b)
&&!isEmpty($data->date)
&&!isEmpty($data->uid)){
 
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
 	$lastInsertedId=$wedding->create();
    // create the wedding
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the wedding, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create wedding","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create wedding. Data is incomplete.","document"=> ""));
}
?>
