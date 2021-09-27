<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/gift_list.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare gift_list object
$gift_list = new Gift_List($db);
 
// get gift_list id
$data = json_decode(file_get_contents("php://input"));
 
// set gift_list id to be deleted
$gift_list->gid = $data->gid;
 
// delete the gift_list
if($gift_list->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Gift_List was deleted","document"=> ""));
    
}
 
// if unable to delete the gift_list
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete gift_list.","document"=> ""));
}
?>
