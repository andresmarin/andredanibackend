<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/gift_list.php';
 //include_once '../token/validatetoken.php';
// instantiate database and gift_list object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$gift_list = new Gift_List($db);

$gift_list->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$gift_list->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read gift_list will be here

// query gift_list
$stmt = $gift_list->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //gift_list array
    $gift_list_arr=array();
	$gift_list_arr["pageno"]=$gift_list->pageNo;
	$gift_list_arr["pagesize"]=$gift_list->no_of_records_per_page;
    $gift_list_arr["total_count"]=$gift_list->total_record_count();
    $gift_list_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $gift_list_item=array(
            
"gid" => $gid,
"custom_url" => $custom_url,
"is_preview" => $is_preview,
"salutation" => html_entity_decode($salutation),
"wid" => $wid
        );
 
        array_push($gift_list_arr["records"], $gift_list_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show gift_list data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "gift_list found","document"=> $gift_list_arr));
    
}else{
 // no gift_list found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no gift_list found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No gift_list found.","document"=> ""));
    
}
 


