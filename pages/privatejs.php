<?php

define( "_VALID_PHP", true);
require_once( "../core/autoload.php");


if(!get('slug')){
	die();
}


if($user->uid){
	
	$page->slug = str_replace(".html", "",  get('slug'));
	$page->private = true;
	$page->load_page();
						
	echo $page->html;
	die();
}
