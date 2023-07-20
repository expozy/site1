<?php
define( "_VALID_PHP", true);

require_once( "core/autoload.php");

// d($_GET);
$page->load_page();

require_once 'pages/header.php';

include 'pages/index.php';


require_once 'pages/footer.php';
