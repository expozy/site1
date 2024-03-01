<?php
define( "_VALID_PHP", true);

require_once( "../../core/autoload.php");


$order_id = $_GET["order_id"];
$lang = $_GET['lang'];

$row = array(
    'tbi' => 1,
    'type' => 'order',
    'order_id' => $order_id,
    'lang' => $lang
);



$result = Api::data($row)->post()->payment_confirm();


if($result['status'] === 1){
    header('Location: '.$result['redirect']);
}else{

}

