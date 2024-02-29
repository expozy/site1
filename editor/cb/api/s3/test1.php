<?php
require_once 'config.php';

$url = 'https://api.openai.com/v1/chat/completions';
$model = 'gpt-3.5-turbo';
$DEFAULT_TEMPERATURE = 0.6;
$DEFAULT_TOP_P = 0.9;
$DEFAULT_NUM = 1;

$question = 'Explain our galaxy in a beginner-friendly and interesting way.';
$context = '';
$system = 'You are a helpful assistant';

$messages = [
    ['role' => 'system', 'content' => $system],
    ['role' => 'assistant', 'content' => $context ?: ''],
    ['role' => 'user', 'content' => $question],
];

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $OPENAI_API_KEY,
];

$data = [
    'model' => $model,
    'messages' => $messages,
    'temperature' => $DEFAULT_TEMPERATURE,
    'top_p' => $DEFAULT_TOP_P,
    'n' => $DEFAULT_NUM,
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

curl_close($ch);

$responseData = json_decode($response, true);

$answer = $responseData;

echo json_encode(['answer' => $answer]);
?>
