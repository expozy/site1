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

    $filename = $_FILES['file']['name'];
    
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if(!($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='webp'||$ext=='svg'||$ext=='wepm'||$ext=='ico'||$ext=='mp4'||$ext=='mp3')) {
        die(json_encode(['error' => 'File type not allowed']));
    }

    $fileUrl = $AWS_URL . '/' . $filename;

    $result = $s3->putObject([
        'Bucket' => $AWS_BUCKET,
        'Key' => $fileUrl, 
        'Body' => fopen($_FILES['file']['tmp_name'], 'rb'), // Open the file for reading as binary
        // 'ACL' => 'public-read', 
    ]);
    
    if ($result) {
        echo json_encode(['url' => $fileUrl]);
    } else {
        echo json_encode(['error' => 'Failed to upload file to S3']);
    }
}
?>
