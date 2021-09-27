<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/basket_item.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare basket_item object
$basket_item = new Basket_Item($db);
 
// set ID property of record to read
$basket_item->item_id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of basket_item to be edited
$basket_item->readOne();
 
if($basket_item->item_id!=null){
    // create array
    $basket_item_arr = array(
        
"title" => html_entity_decode($basket_item->title),
"item_id" => $basket_item->item_id,
"token" => $basket_item->token,
"bid" => $basket_item->bid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "basket_item found","document"=> $basket_item_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user basket_item does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "basket_item does not exist.","document"=> ""));
}
?>
