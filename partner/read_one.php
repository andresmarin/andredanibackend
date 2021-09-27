<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/partner.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare partner object
$partner = new Partner($db);
 
// set ID property of record to read
$partner->pid = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of partner to be edited
$partner->readOne();
 
if($partner->pid!=null){
    // create array
    $partner_arr = array(
        
"pid" => $partner->pid,
"name" => $partner->name,
"lastname1" => $partner->lastname1,
"lastname2" => $partner->lastname2,
"email" => html_entity_decode($partner->email)
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "partner found","document"=> $partner_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user partner does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "partner does not exist.","document"=> ""));
}
?>
