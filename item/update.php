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
 
// prepare item object
$item = new Item($db);
 
// get id of item to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of item to be edited
$item->item_id = $data->item_id;

/*if(
!isEmpty($data->title)
&&!isEmpty($data->description)
&&!isEmpty($data->price)
&&!isEmpty($data->quantity_needed)
&&!isEmpty($data->unlimited)
&&!isEmpty($data->favorite)
&&!isEmpty($data->visible)
&&!isEmpty($data->purchased)
&&!isEmpty($data->cid)
){*/
// set item property values

if(!isEmpty($data->title)) { 
$item->title = $data->title;
} else { 
$item->title = '';
}
if(!isEmpty($data->description)) { 
$item->description = $data->description;
} else { 
$item->description = '';
}
if(!isEmpty($data->price)) { 
$item->price = $data->price;
} else { 
$item->price = '';
}
if(!isEmpty($data->quantity_needed)) { 
$item->quantity_needed = $data->quantity_needed;
} else { 
$item->quantity_needed = '';
}
if(!isEmpty($data->unlimited)) { 
$item->unlimited = $data->unlimited;
} else { 
$item->unlimited = '0';
}
if(!isEmpty($data->favorite)) { 
$item->favorite = $data->favorite;
} else { 
$item->favorite = '0';
}
if(!isEmpty($data->visible)) { 
$item->visible = $data->visible;
} else { 
$item->visible = '0';
}
if(!isEmpty($data->purchased)) { 
$item->purchased = $data->purchased;
} else { 
$item->purchased = '0';
}
if(!isEmpty($data->cid)) { 
$item->cid = $data->cid;
} else { 
$item->cid = '';
}
 
// update the item
if($item->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the item, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update item","document"=> ""));
    
}
/*
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update item. Data is incomplete.","document"=> ""));
}*/
?>
