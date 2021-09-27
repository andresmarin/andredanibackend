<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
// get database connection
include_once '../config/database.php';
 
// instantiate item object
include_once '../objects/item.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$item = new Item($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->title)
&&!isEmpty($data->description)
&&!isEmpty($data->price)
&&!isEmpty($data->quantity_needed)
&&!isEmpty($data->unlimited)
&&!isEmpty($data->favorite)
&&!isEmpty($data->visible)
&&!isEmpty($data->purchased)
&&!isEmpty($data->cid)){
 
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
 	$lastInsertedId=$item->create();
    // create the item
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the item, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create item","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create item. Data is incomplete.","document"=> ""));
}
?>
