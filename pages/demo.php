<?php

define( "_VALID_PHP", true);
require_once( "../core/autoload.php");

$_SESSION['mainanceMode'] = 1;

redirect_to("/");

