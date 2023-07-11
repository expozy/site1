<?php session_start(); 

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType !== "application/json")
    die(json_encode([
    'value' => 0,
    'error' => 'Content-Type is not set as "application/json"',
    'data' => null,
    ]));

$content = trim(file_get_contents("php://input"));

$decoded = json_decode($content, true);

if(! is_array($decoded))
die(json_encode([
    'value' => 0,
    'error' => 'Received JSON is improperly formatted',
    'data' => null,
]));

$content = $decoded['content'];
$mainCss = $decoded['mainCss'];
$sectionCss = $decoded['sectionCss'];

$_SESSION['content'] = $content;
$_SESSION['mainCss'] = $mainCss;
$_SESSION['sectionCss'] = $sectionCss;

die(json_encode([
'data' => [
    'success' => $content
]
]));
?>