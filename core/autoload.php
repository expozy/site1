<?php

if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }


require_once(str_replace("autoload.php", "", realpath(__FILE__)).'classes/class.core.php');



ini_set('display_errors', 1);
error_reporting(1);
$BASEPATH = str_replace("core/autoload.php", "", realpath(__FILE__));

define("BASEPATH", $BASEPATH);

require_once(BASEPATH.'core/classes/class.timer.php');

$timer = new Timer();


if(session_id() ==  ''){
		session_start();
}


// Include Config File
$configFile = BASEPATH . "core/config.php";
require_once($configFile);



require_once(BASEPATH.'core/classes/class.cache.php');

require_once(BASEPATH.'core/helpers/functions.autoload.php');
require_once(BASEPATH.'core/classes/class.api.php');


	//require_once(BASEPATH.'core/classes/class.currency.php');
	//$cur = New Currency();


$core = new FrontCore();
$core->get_web();

require_once(BASEPATH.'core/classes/class.lang.php');
$lang = New Lang();

require_once(BASEPATH.'core/classes/class.template.php');
require_once(BASEPATH.'core/classes/class.page.php');
$page = new Page();



require_once(BASEPATH.'core/classes/class.users.php');
$user = New Users();

require_once(BASEPATH.'core/classes/class.editor.php');





define('SITEURL',			$core->site_url);


define('ADMINURL',			SITEURL . "/admin");
define('CBURL',				SITEURL.'/editor/cb/');

require_once(BASEPATH.'core/classes/class.inline.php');
$inline = new Inline();




?>
