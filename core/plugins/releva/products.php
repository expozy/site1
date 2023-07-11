<?php

define( "_VALID_PHP", true);
require_once( "../../autoload.php");



$result = Api::data($_GET)->get()->releva();

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

