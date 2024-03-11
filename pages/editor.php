<?php
define( "_VALID_PHP", true);
require_once( "../core/autoload.php");
$_POST = json_decode(file_get_contents('php://input'), true);


if(post('saveCss') && post('slug') && post('css')){
	
	$result = ['status' => 0, 'error'=> 'Save error!'];
	if($user->is_superAdmin()){
		$template = new Template('index', post('slug'));
		$template->save_css(post('css'));
		
		$template_header = new Template('index', 'header');
		$template_header->save_css(post('css'));
		
		$result = ['status' => 1];
	}
	

	echo json_encode($result, JSON_PRETTY_PRINT);
	die();
}