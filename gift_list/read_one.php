<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/gift_list.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare gift_list object
$gift_list = new Gift_List($db);
 
// set ID property of record to read
$gift_list->gid = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of gift_list to be edited
$gift_list->readOne();
 
if($gift_list->gid!=null){
    // create array
    $gift_list_arr = array(
        
"gid" => $gift_list->gid,
"custom_url" => $gift_list->custom_url,
"is_preview" => $gift_list->is_preview,
"salutation" => html_entity_decode($gift_list->salutation),
"wid" => $gift_list->wid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "gift_list found","document"=> $gift_list_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user gift_list does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "gift_list does not exist.","document"=> ""));
}
?>
