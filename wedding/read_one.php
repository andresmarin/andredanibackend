<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/wedding.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare wedding object
$wedding = new Wedding($db);
 
// set ID property of record to read
$wedding->wid = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of wedding to be edited
$wedding->readOne();
 
if($wedding->wid!=null){
    // create array
    $wedding_arr = array(
        
"wid" => $wedding->wid,
"name" => $wedding->name,
"partner_a" => $wedding->partner_a,
"name" => $wedding->name,
"partner_b" => $wedding->partner_b,
"date" => $wedding->date,
"email" => html_entity_decode($wedding->email),
"uid" => $wedding->uid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "wedding found","document"=> $wedding_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user wedding does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "wedding does not exist.","document"=> ""));
}
?>
