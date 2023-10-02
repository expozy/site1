<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $b64str = $requestData['image'];
    $filename = $requestData['filename'];

    $path_info = pathinfo($filename);
    $ext = strtolower($path_info['extension']);

    if(!($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='webp'||$ext=='svg'||$ext=='wepm'||$ext=='ico'||$ext=='mp4'||$ext=='mp3')) {
        die(json_encode(['error' => 'File type not allowed']));
    }

    file_put_contents($path . $filename, base64_decode($b64str)); 

    echo json_encode([
        'url' => $urlpath . $filename
    ]);
}
?>
