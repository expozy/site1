<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }


/** =========================================================
 * Function : yes_no()
 *
 * @param $val
 *
 * @return string
========================================================== */
function yes_no($val)
{
	return ($val == 1) ? 'ДА' : 'НЕ';
}


/** =========================================================
 * Function : get_active()
 *
 * @param     $id
 * @param int $kind / 0 = Активен / 1 = Активна / 2 = Активно / 3 = Активни
 *
 * @return string
========================================================== */
function get_active($id, $kind = 0)
{
	switch ($kind){
		case 0:
		default:
			$add = 'ен';
		break;

		case 1:
			$add = 'на';
		break;

		case 2:
			$add = 'но';
		break;

		case 3:
			$add = 'ни';
		break;
	}

	if ($id == 1) {
		$display = '<span class="label label-sm label-success">Актив' . $add . '</span>';
	} else {
		$display = '<span class="label label-sm label-info">Неактив' . $add . '</span>';
	}

	return $display;
}


/** =========================================================
 * Function : get_confirmed()
 *
 * @param     $id
 * @param int $kind / 0 = Потвърден / 1 = Потвърдена / 2 = Потвърдено / 3 = Потвърдени
 *
 * @return string
========================================================== */
function get_confirmed($id, $kind = 0)
{
	switch ($kind){
		case 0:
		default:
			$add = 'ен';
		break;

		case 1:
			$add = 'ена';
		break;

		case 2:
			$add = 'ено';
		break;

		case 3:
			$add = 'ени';
		break;
	}

	if ($id == 1) {
		$display = '<span class="label label-sm label-success">Провер' . $add . '</span>';
	} else {
		$display = '<span class="label label-sm label-info">Непровер' . $add . '</span>';
	}

	return $display;
}


/** =========================================================
 * Function : get_tag()
 *
 * @param     $tag_id
 *
 * @return string
========================================================== */
function get_tag($tag_id)
{
	switch ($tag_id){
		case 1:
		default:
			$tag = '<span class="label label-sm label-success">нов</span>';
			break;

		case 2:
			$tag = '<span class="label label-sm bg-grey-mint">с чип</span>';
			break;

		case 3:
			$tag = '<span class="label label-sm bg-blue">собствена станция</span>';
			break;
	}

	return $tag;
}


/** =========================================================
 * Function : get_checked()
 *
 * @param $row
 * @param $status
========================================================== */
function get_checked($row, $status)
{
	if ($row == $status) {
		return 'checked="checked"';
	}
}


/** =========================================================
 * Function : get_selected()
 *
 * @param $row
 * @param $status
========================================================== */
function get_selected($row, $status)
{
	if ($row == $status) {
		return 'selected="selected"';
	}
}

?>