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
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Specify url path
$path = 'uploads/'; // Physical path, relative to this file (saveimage.php)
$urlpath = $path; //'https://.../uploads/'; // Use this in case URL path is different than physical path. For example: $urlpath = '/admin/contentbox/uploads/';

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType !== "application/json")
    die(json_encode([
        'message' => 'Error',
        'error' => 'Content-Type is not set as "application/json"',
    ]));

$content = trim(file_get_contents("php://input"));

$decoded = json_decode($content, true);

if(! is_array($decoded))
die(json_encode([
'message' => 'Error',
'error' => 'Received JSON is improperly formatted',
]));

$b64str = $decoded['image'];
$filename = $decoded['filename'];

$path_info = pathinfo($filename);
$ext = strtolower($path_info['extension']);

if($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='webp'||$ext=='svg'||$ext=='wepm'||$ext=='ico'||$ext=='mp4') {

    $success = file_put_contents($path . $filename, base64_decode($b64str)); 

    die(json_encode([
    'data' => [
        'message' => 'Success',
        'url' => $urlpath . $filename,
    ]
    ]));

} else {
    die(json_encode([
    'message' => 'Error',
    'error' => 'Received JSON is improperly formatted',
    ]));
}

?>