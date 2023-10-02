<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');

    die();
}

define( "_VALID_PHP", true);
require_once '../../../core/autoload.php';

$keys = Api::get()->keys();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$OPENAI_API_KEY = $keys['openaicom_key'] ?? '';
$path = '../uploads/'; // Specify upload folder here
// $path = __DIR__ . '/uploads/'; 
$urlpath = 'uploads/'; // URL path for viewing
$GETIMG_API_KEY = $keys['getimgai_key'] ?? '';
?>