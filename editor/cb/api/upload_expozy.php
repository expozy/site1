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

define( "_VALID_PHP", true);
require_once '../../../core/autoload.php';

//Specify url path
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
$filename = $decoded['filename'] ?? "ai-".md5(microtime()).".jpg";
$get = $decoded['get'] ?? '';




$path_info = pathinfo($filename);
$ext = strtolower($path_info['extension']);

if($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='webp'||$ext=='svg'||$ext=='wepm'||$ext=='ico'||$ext=='mp4') {


	if(!empty($get)){
		$editor = new Editor($get['i']);
	}
	
	$response = Api::admin_api(true)->data(['base64' => $b64str, 'filename'=>$filename, 'path' => $page->type, 'multiSize'=>1])->post()->images();

	
	//var_dump($response);
	
	if($response['status'] ==1){
			die(json_encode([
			
				'message' => 'Success',
				'url' => $response['url'],
				'response' => $response
			
		]));
	} else {
		die(json_encode([
			'message' => 'Error',
			'error' => $response,
			]));
	}
    

} else {
    die(json_encode([
    'message' => 'Error',
    'error' => 'Received JSON is improperly formatted',
    ]));
}

?>