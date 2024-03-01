<?php
define( "_VALID_PHP", true);
require_once( "../../../core/autoload.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	die();
}



$data = json_decode(file_get_contents('php://input'), true);


if(! is_array($data)){
		die(json_encode([
			'value' => 0,
			'error' => 'Received JSON is improperly formatted',
			'data' => null,
		]));
}
$content = $data['content'];
$mainCss = $data['mainCss'];
$sectionCss = $data['sectionCss'];
$tailwindCss = $data['tailwindCss'];
$get = $data['get'];
$revision_title = $data['revision_title'];



$_SESSION['content'] = $content;
$_SESSION['mainCss'] = $mainCss;
$_SESSION['sectionCss'] = $sectionCss;


$editor = new Editor($get['i']);


if(!empty($content)){
	$result = $editor->save($content, $get['lang']??'', $tailwindCss, $revision_title);
	
	
} else {
	$result = ['status' =>0 , 'msg' => 'Empty content'];
}


echo json_encode($result, JSON_PRETTY_PRINT);
