<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/image.php';
 include_once '../token/validatetoken.php';
// instantiate database and image object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$image = new Image($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$image->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$image->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query image
$stmt = $image->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //image array
    $image_arr=array();
	$image_arr["pageno"]=$image->pageNo;
	$image_arr["pagesize"]=$image->no_of_records_per_page;
    $image_arr["total_count"]=$image->total_record_count();
    $image_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $image_item=array(
            
"image_id" => $image_id,
"name" => $name,
"url" => $url,
"title" => html_entity_decode($title),
"item_id" => $item_id
        );
 
        array_push($image_arr["records"], $image_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show image data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "image found","document"=> $image_arr));
    
}else{
 // no image found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no image found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No image found.","document"=> ""));
    
}
 


