<?php
require_once( "../../../core/autoload.php");



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
