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
 
// instantiate gift_list object
include_once '../objects/gift_list.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$gift_list = new Gift_List($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->is_preview)
&&!isEmpty($data->wid)){
 
    // set gift_list property values
	 
$gift_list->custom_url = $data->custom_url;
if(!isEmpty($data->is_preview)) { 
$gift_list->is_preview = $data->is_preview;
} else { 
$gift_list->is_preview = '1';
}
$gift_list->salutation = $data->salutation;
if(!isEmpty($data->wid)) { 
$gift_list->wid = $data->wid;
} else { 
$gift_list->wid = '';
}
 	$lastInsertedId=$gift_list->create();
    // create the gift_list
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the gift_list, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create gift_list","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create gift_list. Data is incomplete.","document"=> ""));
}
?>
