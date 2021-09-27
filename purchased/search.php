<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/purchased.php';
 include_once '../token/validatetoken.php';
// instantiate database and purchased object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$purchased = new Purchased($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$purchased->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$purchased->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query purchased
$stmt = $purchased->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //purchased array
    $purchased_arr=array();
	$purchased_arr["pageno"]=$purchased->pageNo;
	$purchased_arr["pagesize"]=$purchased->no_of_records_per_page;
    $purchased_arr["total_count"]=$purchased->total_record_count();
    $purchased_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $purchased_item=array(
            
"pid" => $pid,
"gid" => $gid,
"uid" => $uid
        );
 
        array_push($purchased_arr["records"], $purchased_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show purchased data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "purchased found","document"=> $purchased_arr));
    
}else{
 // no purchased found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no purchased found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No purchased found.","document"=> ""));
    
}
 


