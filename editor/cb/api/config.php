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


// If using S3 Storage:
$AWS_KEY = 'eab139e5b0d89d841e0c1c3b9200ba5f';
$AWS_SECRET = '10742bf3d3c5a9da69b91b1da4015e245b28aa67277bc8b49fb86ee7d5859e24';
$AWS_BUCKET = 'expozy';
$AWS_URL = 'e26c7578e31272319bbb33cfa27ac9ee.r2.cloudflarestorage.com';
$AWS_REGION = 'eeur'; // Specify region here
$prefix = "kokotest"; // Specify a directory here (optional)
// More info: https://docs.aws.amazon.com/AmazonS3/latest/userguide/WebsiteHosting.html
?>