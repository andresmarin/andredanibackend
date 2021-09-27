<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/basket.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare basket object
$basket = new Basket($db);
 
// set ID property of record to read
$basket->bid = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of basket to be edited
$basket->readOne();
 
if($basket->bid!=null){
    // create array
    $basket_arr = array(
        
"bid" => $basket->bid,
"token" => $basket->token,
"gid" => $basket->gid,
"uid" => $basket->uid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "basket found","document"=> $basket_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user basket does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "basket does not exist.","document"=> ""));
}
?>
