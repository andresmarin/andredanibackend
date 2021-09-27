<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 include_once '../token/token.php';
  include_once '../objects/user.php';
include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;
 
$database = new Database();
$db = $database->getConnection();
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if(
!empty($data->username)
&&!empty($data->password)
){

//validate your username and password from database call (copy the code from other generated table files or user table (if you have user or admin table)
$users = new User($db);
$users->email = $data->username;
$users->password = md5($data->password);
$users->login_validation();
if($users->uid!=null){
$token["data"]=$users;
$jwt = JWT::encode($token, SECRET_KEY);
$tokenOutput = array(
			"access_token" => $jwt,
            "expires_in" => $tokenExp,
			"token_type" => "bearer",
			);
		$userData=$users;
  http_response_code(200);
  print json_encode(array("status" => "success", "code" => 1,"message"=> "Token Generated","document"=> $tokenOutput,"user"=>$userData));
}else{
	http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Invalid login.","document"=> ""));
}}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to login.","document"=> ""));
}
?>

