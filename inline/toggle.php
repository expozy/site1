<?php
define( "_VALID_PHP", true);

require_once( "../core/autoload.php");


$inline->toggle();

redirect_to($_SERVER['HTTP_REFERER']);