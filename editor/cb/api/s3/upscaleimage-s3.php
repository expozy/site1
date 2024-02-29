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

$upscaleImageUrl = 'https://api.getimg.ai/v1/enhacements/upscale';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $image = $requestData['image'];
    $folder_path = $requestData['folder_path'];

    $messages = [
        'model' => 'real-esrgan-4x',
        'image' => $image,
        'scale' => 4,
        'output_format' => 'jpeg'
    ];

    $jsonPayload = json_encode($messages);

    $headers = array(
        'Authorization: Bearer ' . $GETIMG_API_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $upscaleImageUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        if (!isset($responseData['error'])) {
            $randomFileName = generateRandomFileName('lg');

            $imageData = base64_decode($responseData['image']);
            $key = path_join($prefix, $folder_path, $randomFileName . '.jpg');
            
            try {
                $result = $s3->putObject([
                    'Bucket' => $AWS_BUCKET,
                    'Key' => $key,
                    'Body' => $imageData,
                    'ContentType' => 'image/jpeg',
                ]);

                if ($result['@metadata']['statusCode'] === 200) {
                    $fileUrl = $AWS_URL . '/' . $key;
                    echo json_encode(['url' => $fileUrl]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to upload to AWS S3']);
                }
            } catch (AwsException $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Something went wrong.']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Something went wrong.']);
    }

    curl_close($ch);
}

function generateRandomFileName($s) {
    $randomLength = 5;
    $randomString = generateRandomString($randomLength);
    if ($s) {
        return "ai-$randomString-$s";
    } else {
        return "ai-$randomString";
    }
}

function generateRandomString($length) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $result .= $characters[$randomIndex];
    }
    return $result;
}

function path_join(...$parts) {
    $nonEmptyParts = array_filter($parts, 'strlen'); // Filter out empty parts
    $path = implode(DIRECTORY_SEPARATOR, $nonEmptyParts);
    return ltrim($path, DIRECTORY_SEPARATOR);
}
?>
