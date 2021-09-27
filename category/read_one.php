<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/category.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare category object
$category = new Category($db);
 
// set ID property of record to read
$category->cid = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of category to be edited
$category->readOne();
 
if($category->cid!=null){
    // create array
    $category_arr = array(
        
"cid" => $category->cid,
"name" => $category->name
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "category found","document"=> $category_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user category does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "category does not exist.","document"=> ""));
}
?>
