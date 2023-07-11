<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }


/** =========================================================
 * Function : redirect_to()
 *
 * @param $location
========================================================== */
function redirect_to($location)
{
	if (!headers_sent()) {
		header('Location: ' . $location);
		exit;
	} else
		echo '<script type="text/javascript">';
	echo 'window.location.href="' . $location . '";';
	echo '</script>';
	echo '<noscript>';
	echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
	echo '</noscript>';
}


/** =========================================================
 * Function : url()
 *
 * @param      $type
 * @param bool $slug
 * @param bool $pars
 *
 * @return string
========================================================== */
function url($type, $slug = false, $pars = false)
{
	$url = SITEURL;

	switch ($type) {
		//Shop
		case "cart":
			$url .=  '/cart/' . $pars;
		break;

		case "checkout":
			$url .=  '/checkout/' . $pars;
		break;



		//Pages
		case "index":
			$url .=  '/' . $slug . '.html' . $pars;
		break;
		case "page":
			$url .=  '/page/' . $slug . '.html' . $pars;
		break;



		//Blog
		case "blog":
			$url .=  '/blog/' . $slug . '.html' . $pars;
		break;

	}

	return $url;
}

/** =========================================================
 * Function : get_url()
 *
 * @param      $s
 * @param bool $use_forwarded_host
 *
 * @return string
========================================================== */
function get_url($s, $use_forwarded_host = false )
{
	$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
	$sp       = strtolower( $s['SERVER_PROTOCOL'] );
	$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
	$port     = $s['SERVER_PORT'];
	$port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
	$host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
	$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
	return $protocol . '://' . $host;
}
?>