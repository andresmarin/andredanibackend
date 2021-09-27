<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/purchased.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare purchased object
$purchased = new Purchased($db);
 
// set ID property of record to read
$purchased->pid = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of purchased to be edited
$purchased->readOne();
 
if($purchased->pid!=null){
    // create array
    $purchased_arr = array(
        
"pid" => $purchased->pid,
"gid" => $purchased->gid,
"uid" => $purchased->uid
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "purchased found","document"=> $purchased_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user purchased does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "purchased does not exist.","document"=> ""));
}
?>
