<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }


/** =========================================================
 * Function : array_sort_by_column()
 *
 * @param     $arr
 * @param     $col
 * @param int $dir
========================================================== */
function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
	$sort_col = array();
	foreach ($arr as $key=> $row) {
		$sort_col[$key] = $row[$col];
	}

	array_multisort($sort_col, $dir, $arr);

	//Usage: array_sort_by_column($array, 'order');
}


/** =========================================================
 * Function : search_in_array()
 *
 * @param $array
 * @param $key
 * @param $value
 *
 * @return array
========================================================== */
function search_in_array($array, $key, $value)
{
	$results = array();

	if (is_array($array)) {
		if (isset($array[$key]) && $array[$key] == $value) {
			$results[] = $array;
		}

		foreach ($array as $subarray) {
			$results = array_merge($results, search_in_array($subarray, $key, $value));
		}
	}

	return $results;
}


?>