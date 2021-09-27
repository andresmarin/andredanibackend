<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/category.php';
 include_once '../token/validatetoken.php';
// instantiate database and category object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$category->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$category->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query category
$stmt = $category->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //category array
    $category_arr=array();
	$category_arr["pageno"]=$category->pageNo;
	$category_arr["pagesize"]=$category->no_of_records_per_page;
    $category_arr["total_count"]=$category->search_record_count($data,$orAnd);
    $category_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $category_item=array(
            
"cid" => $cid,
"name" => $name
        );
 
        array_push($category_arr["records"], $category_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show category data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "category found","document"=> $category_arr));
    
}else{
 // no category found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no category found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No category found.","document"=> ""));
    
}
 


