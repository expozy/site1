<?php

if(!isset($_POST['saas_key'])) die();

$newKey = $_POST['saas_key'];

// Пътят до файла
$file = __DIR__."/saas_key.php";

// Прочитане на съдържанието на файла
$content = file_get_contents($file);

// Търсене и заместване на ключа с новия ключ, използвайки регулярен израз
$newContent = preg_replace('/define\("SAAS_KEY",".*?"\);/', 'define("SAAS_KEY","' . $newKey . '");', $content, 1);

// Записване на промененото съдържание обратно във файла
file_put_contents($file, $newContent);

//изтрива се
unlink(__FILE__);
