<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


if(session_id() ==  ''){
		session_start();
}

$content = trim(file_get_contents("php://input"));

$data = json_decode($content, true);
			//$var_str = var_export($data, true);file_put_contents('newfile2_'.str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ').'.txt', $var_str);

if(! is_array($data)){
	die(json_encode([
		'value' => 0,
		'error' => 'Received JSON is improperly formatted',
		'data' => null,
	]));
}

if(isset($data['user']) && isset($data['token'])){
	
	
	$_SESSION['uid'] = $data['user']['id'];
	$_SESSION['email'] = $data['user']['email'];
	$_SESSION['names'] = $data['user']['first_name'] . ' ' . $data['user']['last_name'];
	$_SESSION['balance'] = $data['user']['balance'];
	$_SESSION['userlevel'] = $data['user']['userlevel'];
	$_SESSION['token'] = $data['token'];
}
print json_encode(['status'=>1]);