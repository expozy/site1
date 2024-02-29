<?php
require_once 'config.php';

$textToImageUrl = 'https://api.getimg.ai/v1/stable-diffusion/text-to-image';

$prompt = 'a minimalist furniture design, a small simple yellow vase, shot in a photo studio, wide angle, clean and bright background, lots of white space, minimalist look, pastel color, soft lighting';
$negative_prompt = '';
$model = 'realistic-vision-v3';
$width = 512;
$height = 512;
$steps = 75;
$guidance = 9;
$scheduler = 'dpmsolver++';
$output_format = 'jpeg';
$folder_path = '';

$messages = [
    'model' => $model,
    'prompt' => $prompt,
    'negative_prompt' => $negative_prompt,
    'width' => $width,
    'height' => $height,
    'steps' => $steps,
    'guidance' => $guidance,
    'scheduler' => $scheduler,
    'output_format' => $output_format
];
$jsonPayload = json_encode($messages);

$headers = array(
    'Authorization: Bearer ' . $GETIMG_API_KEY,
    'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $textToImageUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 200) {
    $responseData = json_decode($response, true);
    if (!isset($responseData['error'])) {
        $randomFileName = generateRandomFileName('');
        $filePath = $path . $folder_path . '/' . $randomFileName . '.jpg';
        $fileUrl = $urlpath . $folder_path . '/' . $randomFileName . '.jpg';
        
        if (!file_exists($filePath)) { // Do not replace if file exists
            file_put_contents($filePath, base64_decode($responseData['image']));
            echo json_encode(['url' => $fileUrl]);
        } else {
            http_response_code(200);
            echo json_encode(['ok' => true, 'status' => 500, 'error' => 'File already exists.']);
        }
    } else {
        // echo json_encode(['error' => 'Something went wrong.']);
        http_response_code(500);
        echo json_encode(['error' => $responseData['error']]);
    } 
} else {
    // http_response_code(500);
    // echo json_encode(['error' => 'Something went wrong.']);

    $errorDetails = json_decode($response, true);
    $errorMessage = isset($errorDetails['error']) ? $errorDetails['error'] : 'Unexpected error';
    
    http_response_code($httpCode);
    echo json_encode(['error' => $errorMessage, 'http_code' => $httpCode]);
}

curl_close($ch);


function generateRandomString($length) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $result .= $characters[$randomIndex];
    }
    return $result;
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
?>
