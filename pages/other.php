<?php

define( "_VALID_PHP", true);
require_once( "../core/autoload.php");

if(get('demo')){
	$_SESSION['mainanceMode'] = 1;

	redirect_to("/");
	die();
}


if(get('downloadPages')){
	$pages = Page::downloadPages();
	
	redirect_to("/");
	die();
}