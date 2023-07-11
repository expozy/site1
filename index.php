<?php
define( "_VALID_PHP", true);

require_once( "core/autoload.php");


$page->load_page();

require_once 'header.php';

include 'templates/pages/index.php';


require_once 'footer.php';
