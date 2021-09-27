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
 
// instantiate gift_list_items object
include_once '../objects/gift_list_items.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$gift_list_items = new Gift_List_Items($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->item_id)
&&!isEmpty($data->gid)){
 
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
 	$lastInsertedId=$gift_list_items->create();
    // create the gift_list_items
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the gift_list_items, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create gift_list_items","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create gift_list_items. Data is incomplete.","document"=> ""));
}
?>
