<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/partner.php';
 include_once '../token/validatetoken.php';
// instantiate database and partner object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$partner = new Partner($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$partner->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$partner->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query partner
$stmt = $partner->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //partner array
    $partner_arr=array();
	$partner_arr["pageno"]=$partner->pageNo;
	$partner_arr["pagesize"]=$partner->no_of_records_per_page;
    $partner_arr["total_count"]=$partner->total_record_count();
    $partner_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $partner_item=array(
            
"pid" => $pid,
"name" => $name,
"lastname1" => $lastname1,
"lastname2" => $lastname2,
"email" => html_entity_decode($email)
        );
 
        array_push($partner_arr["records"], $partner_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show partner data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "partner found","document"=> $partner_arr));
    
}else{
 // no partner found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no partner found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No partner found.","document"=> ""));
    
}
 


