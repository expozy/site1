<?php 
define( "_VALID_PHP", true);


$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType !== "application/json")
    die(json_encode([
    'value' => 0,
    'error' => 'Content-Type is not set as "application/json"',
    'data' => null,
    ]));

$content = trim(file_get_contents("php://input"));

$data = json_decode($content, true);
			//$var_str = var_export($data, true);file_put_contents('newfile2_'.str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ').'.txt', $var_str);

if(! is_array($data))
die(json_encode([
    'value' => 0,
    'error' => 'Received JSON is improperly formatted',
    'data' => null,
]));



if(isset($data['savecontent'])){

	require_once 'savecontent.php';
	
}

if(isset($data['login'])){
	require_once 'login.php';
}


?>