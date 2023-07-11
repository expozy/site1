<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }


/** =========================================================
 * Function : sanitize()
 *
 * @param      $string
 * @param bool $trim
 * @param bool $int
 * @param bool $str
 *
 * @return mixed|string
========================================================== */
function sanitize($string, $trim = false, $int = false, $str = false)
{
	//$string = filter_var($string, FILTER_SANITIZE_STRING);
	$string = trim($string);
	$string = stripslashes($string);
	$string = strip_tags($string);
	$string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);

	if ($trim)
		$string = substr($string, 0, $trim);
		//$string = mb_substr($string, 0, $trim);
	if ($int)
		$string = preg_replace("/[^0-9\s]/", "", $string);
	if ($str)
		$string = preg_replace("/[^a-zA-Z\s]/", "", $string);

	return $string;
}


/** =========================================================
 * Function : cleanSanitize()
 *
 * @param        $string
 * @param bool   $trim
 * @param string $end_char
 *
 * @return mixed|string
========================================================== */
function cleanSanitize($string, $trim = false, $end_char = ' &#8230;')
{
	$string = cleanOut($string);
	$string = filter_var($string, FILTER_SANITIZE_STRING);
	$string = trim($string);
	$string = stripslashes($string);
	$string = strip_tags($string);
	$string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);

	if ($trim) {
		if (strlen($string) < $trim) {
			return $string;
		}

		$string = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $string));

		if (strlen($string) <= $trim) {
			return $string;
		}

		$out = "";
		foreach (explode(' ', trim($string)) as $val) {
			$out .= $val.' ';

			if (strlen($out) >= $trim) {
				$out = trim($out);
				return (strlen($out) == strlen($string)) ? $out : $out.$end_char;
			}
		}
	}
	return $string;
}


/** =========================================================
 * Function : get_between()
 *
 * @param $content
 * @param $start
 * @param $end
 *
 * @return string
========================================================== */
function get_between($content, $start, $end){
	$r = explode($start, $content);
	if (isset($r[1])){
		$r = explode($end, $r[1]);
		return $r[0];
	}
	return '';
}


/** =========================================================
 * Function : cleanOut()
 *
 * @param $text
 *
 * @return string
========================================================== */
function cleanOut($text) {
	$text =  strtr($text, array('\r\n' => "", '\r' => "", '\n' => ""));
	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	$text = str_replace('<br>', '<br />', $text);
	return stripslashes($text);
}


/** =========================================================
 * Function : req()
 *
 * @param bool $sym
========================================================== */
function req($sym = false)
{
	print ($sym) ? '<span class="required tooltips" data-original-title="Задължително поле" data-placement="top" data-container="body">' . $sym . '</span>' : '<span class="required tooltips" data-original-title="Задължително поле" data-placement="top" data-container="body" style="color: #e02222;">*</span>';
}




/** =========================================================
 * Function : isValidEmail()
 *
 * @param $email
 *
 * @return bool|int
========================================================== */
function isValidEmail($email)
{
	if (function_exists('filter_var')) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	} else {
		return preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email);
	}
}


/** =========================================================
 * Function : dodate()
 *
 * @param $format
 * @param $date
 *
 * @return string
========================================================== */
function dodate($format, $date)
{
	return strftime($format, strtotime($date));
}

function d($data, $die = false){
	if(isset($data)){
		echo '<pre>';
		if(is_array($data) || is_object($data)){
			print_r($data);
		}
		else{
			echo $data;
		}
		echo '</pre>';
	}
	
	if($die){
		die();
	}
}
?>