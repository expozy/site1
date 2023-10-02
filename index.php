<?php
define( "_VALID_PHP", true);

require_once( "core/autoload.php");


foreach ($_GET as $key => $param) {
  if(is_array($_GET[$key])){
    $_GET[$key.'[]'] = $_GET[$key];
    unset($_GET[$key]);
  }
}

$page->load_page();



require_once 'pages/header.php';

include 'pages/index.php';


require_once 'pages/footer.php';
