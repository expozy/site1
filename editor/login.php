<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


if(session_id() ==  ''){
		session_start();
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