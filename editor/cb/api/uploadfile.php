<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $filename = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if(!($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='webp'||$ext=='svg'||$ext=='wepm'||$ext=='ico'||$ext=='mp4'||$ext=='mp3')) {
        die(json_encode(['error' => 'File type not allowed']));
    }

    //$new_name = $filename . '-' . time() . '.' . $ext;
    $new_name = $filename . '.' . $ext;

    move_uploaded_file($_FILES['file']['tmp_name'], $path . $new_name);

    echo json_encode([
        'url' => $urlpath . $new_name
    ]);
}
?>
