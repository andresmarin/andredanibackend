<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/basket.php';
 include_once '../token/validatetoken.php';
// instantiate database and basket object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$basket = new Basket($db);

$basket->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$basket->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read basket will be here

// query basket
$stmt = $basket->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //basket array
    $basket_arr=array();
	$basket_arr["pageno"]=$basket->pageNo;
	$basket_arr["pagesize"]=$basket->no_of_records_per_page;
    $basket_arr["total_count"]=$basket->total_record_count();
    $basket_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $basket_item=array(
            
"bid" => $bid,
"token" => $token,
"gid" => $gid,
"uid" => $uid
        );
 
        array_push($basket_arr["records"], $basket_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show basket data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "basket found","document"=> $basket_arr));
    
}else{
 // no basket found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no basket found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No basket found.","document"=> ""));
    
}
 


