<?php
require_once 'config.php';
$url = $_GET['url']; // Get the image URL from the query parameter

if (!$url) {
    http_response_code(400);
    echo 'Bad Request';
    exit;
}

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);

if ($response === false) {
    echo 'Failed to fetch file';
    http_response_code(500);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

if ($httpCode !== 200) {
    echo 'File not found';
    http_response_code($httpCode);
    exit;
}

header("Content-Type: $contentType");
echo $response;

curl_close($ch);
?>