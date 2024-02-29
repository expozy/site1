<?php
require_once 'config.php';
require 'aws.phar';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3 = new S3Client([
    'version' => 'latest',
    'region' => $AWS_REGION,
    'credentials' => [
        'key' => $AWS_KEY,
        'secret' => $AWS_SECRET,
    ],
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $b64str = $requestData['image'];
    $filename = $requestData['filename'];

    $path_info = pathinfo($filename);
    $ext = strtolower($path_info['extension']);

    if (!in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp', 'svg', 'wepm', 'ico', 'mp4', 'mp3'])) {
        die(json_encode(['error' => 'File type not allowed']));
    }

    $fileUrl = $AWS_URL . '/' . $filename;

    $result = $s3->putObject([
        'Bucket' => $AWS_BUCKET,
        'Key' => $fileUrl,
        'Body' => base64_decode($b64str),
        // 'ACL' => 'public-read', 
    ]);

    if ($result) {
        echo json_encode(['url' => $fileUrl]);
    } else {
        echo json_encode(['error' => 'Failed to upload file to S3']);
    }
}
?>
