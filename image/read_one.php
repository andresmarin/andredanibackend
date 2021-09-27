<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/image.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare image object
$image = new Image($db);
 
// set ID property of record to read
$image->image_id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of image to be edited
$image->readOne();
 
if($image->image_id!=null){
    // create array
    $image_arr = array(
        
"image_id" => $image->image_id,
"name" => $image->name,
"url" => $image->url,
"title" => html_entity_decode($image->title),
"item_id" => $image->item_id
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "image found","document"=> $image_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user image does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "image does not exist.","document"=> ""));
}
?>
