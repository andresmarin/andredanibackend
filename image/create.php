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
 
// instantiate image object
include_once '../objects/image.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$image = new Image($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->name)
&&!isEmpty($data->url)
&&!isEmpty($data->item_id)){
 
    // set image property values
	 
if(!isEmpty($data->name)) { 
$image->name = $data->name;
} else { 
$image->name = '';
}
if(!isEmpty($data->url)) { 
$image->url = $data->url;
} else { 
$image->url = '';
}
if(!isEmpty($data->item_id)) { 
$image->item_id = $data->item_id;
} else { 
$image->item_id = '';
}
 	$lastInsertedId=$image->create();
    // create the image
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the image, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create image","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create image. Data is incomplete.","document"=> ""));
}
?>
