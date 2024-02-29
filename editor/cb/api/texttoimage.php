<?php
require_once 'config.php';

$textToImageUrl = 'https://api.getimg.ai/v1/stable-diffusion/text-to-image';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $prompt = $requestData['prompt'];
    $negative_prompt = $requestData['negative_prompt'];
    $model = $requestData['model'] ?? 'realistic-vision-v3';
    $width = $requestData['width'] ?? 1024;
    $height = $requestData['height'] ?? 1024;
    $steps = $requestData['steps'] ?? 75;
    $guidance = $requestData['guidance'] ?? 9;
    $scheduler = $requestData['scheduler'] ?? 'dpmsolver++';
    $output_format = $requestData['output_format'] ?? 'jpeg';
    $folder_path = $requestData['folder_path'];

    // $prompt = 'a minimalist furniture design, a small simple yellow vase, shot in a photo studio, wide angle, clean and bright background, lots of white space, minimalist look, pastel color, soft lighting';
    // $negative_prompt = '';
    // $model = 'realistic-vision-v3';
    // $width = 512;
    // $height = 512;
    // $steps = 75;
    // $guidance = 9;
    // $scheduler = 'dpmsolver++';
    // $output_format = 'jpeg';
    // $folder_path = '';
	
	$response = Api::admin_api(true)->data(['description' => $prompt,  'multiSize'=>1])->post()->generate_image();
	echo json_encode($response);
	die();
	
	
}
?>
