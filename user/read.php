<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/user.php';
 include_once '../token/validatetoken.php';
// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$user = new User($db);

$user->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$user->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read user will be here

// query user
$stmt = $user->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //user array
    $user_arr=array();
	$user_arr["pageno"]=$user->pageNo;
	$user_arr["pagesize"]=$user->no_of_records_per_page;
    $user_arr["total_count"]=$user->total_record_count();
    $user_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $user_item=array(
            
"uid" => $uid,
"email" => html_entity_decode($email),
"password" => $password
        );
 
        array_push($user_arr["records"], $user_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show user data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "user found","document"=> $user_arr));
    
}else{
 // no user found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no user found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No user found.","document"=> ""));
    
}
 


