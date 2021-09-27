<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/wedding.php';
 include_once '../token/validatetoken.php';
// instantiate database and wedding object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$wedding = new Wedding($db);

$wedding->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$wedding->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$wedding->partner_a = isset($_GET['partner_a']) ? $_GET['partner_a'] : die();
// read wedding will be here

// query wedding
$stmt = $wedding->readBypartner_a();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //wedding array
    $wedding_arr=array();
	$wedding_arr["pageno"]=$wedding->pageNo;
	$wedding_arr["pagesize"]=$wedding->no_of_records_per_page;
    $wedding_arr["total_count"]=$wedding->total_record_count();
    $wedding_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $wedding_item=array(
            
"wid" => $wid,
"name" => $name,
"partner_a" => $partner_a,
"name" => $name,
"partner_b" => $partner_b,
"date" => $date,
"email" => html_entity_decode($email),
"uid" => $uid
        );
 
        array_push($wedding_arr["records"], $wedding_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show wedding data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "wedding found","document"=> $wedding_arr));
    
}else{
 // no wedding found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no wedding found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No wedding found.","document"=> ""));
    
}
 


