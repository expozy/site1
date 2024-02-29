<?php
require_once 'config.php';

$url = 'https://api.openai.com/v1/chat/completions';
$model = 'gpt-3.5-turbo';
$DEFAULT_TEMPERATURE = 0.6;
$DEFAULT_TOP_P = 0.9;
$DEFAULT_NUM = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);
    $question = $body['question'];
    $context = $body['context'];
    $system = $body['system'];
    $functs = $body['functs'];
    $temperature = $body['temperature'];
    $topP = $body['topP'];
    $num = $body['num'];

    $messages = [
        ['role' => 'system', 'content' => $system],
        ['role' => 'assistant', 'content' => $context ?: ''],
        ['role' => 'user', 'content' => $question]
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $OPENAI_API_KEY,
    ];

    $data = [
        'model' => $model,
        'messages' => $messages,
        'temperature' => floatval($temperature) ?: $DEFAULT_TEMPERATURE,
        'top_p' => floatval($topP) ?: $DEFAULT_TOP_P,
        'n' => intval($num) ?: $DEFAULT_NUM,
    ];

    if (!empty($functs)) {
        $data['functions'] = $functs;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    $response = curl_exec($ch);

    if ($response === false) {
        // Handle the cURL error here
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        $responseData = json_decode($response, true);

        $answer = empty($functs)
            ? $responseData
            : ($responseData['choices'][0]['message']['function_call'] ?
            $responseData['choices'][0]['message']['function_call']['arguments'] :
            $responseData['choices'][0]['message']);

        echo json_encode(['answer' => $answer]);

        curl_close($ch);
    }
}
?>
