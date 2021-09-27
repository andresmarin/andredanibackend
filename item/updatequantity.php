<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/item.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 

$sql = 'UPDATE item
        SET quantity_needed = :quantity_needed
        WHERE item_id = :item_id';

// prepare statement
$statement = $db->prepare($sql);

// bind params
$statement->bindParam(':item_id', $data->item_id, PDO::PARAM_INT);
$statement->bindParam(':quantity_needed', $data->quantity_needed);

// execute the UPDATE statment
if ($statement->execute()) {
	// set response code - 200 ok
    http_response_code(200);
  
    // tell the user
  echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
 
  // set response code - 503 service unavailable
  http_response_code(503);

  // tell the user
echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update item","document"=> ""));
  
}




?>
